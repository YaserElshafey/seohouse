<?php
/**
 * «نقل عناوين وأوصاف SEO»: the title and meta description the main site actually shows → the
 * Rank Math fields of the same URL on this site (the /new/ review copy).
 *
 * Matching: every page, post, result, sector, team page, category and tag here, plus the home page
 * and the blog archive, by its FULL path after removing this site's folder (/new/). The main site's
 * paths are built from its own database over the existing read-only connection (SH_Post_Migration:
 * page parents, permalink structure, its rewrite rules for custom types and categories, front page
 * and posts page), so /services/seo/technical-seo/ never matches a /technical-seo/ elsewhere.
 *
 * Values: read from the HTML the main site serves for that path (its <title> and
 * <meta name="description">), because many come from Rank Math templates, not from a field. They
 * are written as plain text where Rank Math reads them on this site: post meta for pages, posts and
 * custom types (also the static front page and the posts page), term meta for categories and tags,
 * the Titles & Meta option for a home page that lists posts.
 *
 * Never touched: H1, content, slug, robots, canonical. An empty old description leaves this site's
 * description as it is; equal values are skipped; a value edited here by hand is never replaced.
 * "By hand" = a stored value that is none of the automatic ones: empty, Core's design value, the
 * value the post migration copied from the main site, or the value this screen wrote last time.
 * Every run keeps a copy of the current values first (option + JSON file), and the first value
 * ever replaced is kept per object so «استعادة» can put it back.
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

class SH_SEO_Transfer {

	const META_LAST = '_sh_seo_transfer';      // what this screen wrote: {title?, description?, time, from}
	const META_ORIG = '_sh_seo_transfer_orig'; // stored values before the first write: {title, description}
	const OPT_HOME  = 'sh_seo_transfer_home';  // same pair for the home page option: {last, orig}
	const OPT_REP   = 'sh_seo_transfer_report';
	const OPT_BAK   = 'sh_seo_transfer_backups';

	/** @var SH_Post_Migration */
	public $src;
	public $error = '';
	/** @var array<string,string> */
	public $src_opts = array();
	/** @var array<string,array> path => source object */
	public $src_map = array();
	/** @var string[] how custom types and taxonomies were placed (for the screen) */
	public $src_notes = array();
	/** @var array<int,array> */
	public $rows = array();
	/** @var array<string,int> */
	public $counts = array();

	const FIELDS = array(
		'title'       => 'rank_math_title',
		'description' => 'rank_math_description',
	);

	/* ================================================================ helpers */

	/** Text as a visitor reads it: entities decoded, spaces collapsed. */
	public static function norm( $s ): string {
		$s = html_entity_decode( (string) $s, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
		$s = str_replace( "\xC2\xA0", ' ', $s );
		return trim( (string) preg_replace( '/\s+/u', ' ', $s ) );
	}

	/** Path as a comparable key: decoded, leading slash. */
	public static function key( string $path ): string {
		$path = rawurldecode( (string) wp_parse_url( $path, PHP_URL_PATH ) );
		return '/' . ltrim( $path, '/' );
	}

	public static function encode( string $key ): string {
		return implode( '/', array_map( 'rawurlencode', explode( '/', $key ) ) );
	}

	/** <title>, meta description, robots and canonical from the <head> of a page. */
	public static function parse( string $html ): array {
		$head = $html;
		$end  = stripos( $html, '</head>' );
		if ( false !== $end ) {
			$head = substr( $html, 0, $end );
		}
		$out = array( 'title' => '', 'description' => '', 'robots' => '', 'canonical' => '', 'has_title' => false, 'has_description' => false );
		if ( preg_match( '#<title[^>]*>(.*?)</title>#is', $head, $m ) ) {
			$out['title']     = self::norm( $m[1] );
			$out['has_title'] = true;
		}
		preg_match_all( '#<(meta|link)\s[^>]*>#i', $head, $tags, PREG_SET_ORDER );
		foreach ( $tags as $t ) {
			preg_match_all( '#([a-z:-]+)\s*=\s*(?:"([^"]*)"|\'([^\']*)\')#i', $t[0], $am, PREG_SET_ORDER );
			$a = array();
			foreach ( $am as $x ) {
				$a[ strtolower( $x[1] ) ] = $x[2] . ( $x[3] ?? '' );
			}
			if ( 'meta' === strtolower( $t[1] ) && isset( $a['name'] ) ) {
				$n = strtolower( $a['name'] );
				if ( 'description' === $n && ! $out['has_description'] ) {
					$out['description']     = self::norm( $a['content'] ?? '' );
					$out['has_description'] = true;
				} elseif ( 'robots' === $n ) {
					$out['robots'] = self::norm( $a['content'] ?? '' );
				}
			} elseif ( 'link' === strtolower( $t[1] ) && 'canonical' === strtolower( $a['rel'] ?? '' ) ) {
				$out['canonical'] = $a['href'] ?? '';
			}
		}
		return $out;
	}

	/**
	 * GET each URL once, without following redirects. Returns url => {code, location, html|error}.
	 *
	 * @param string[] $urls
	 */
	public static function fetch( array $urls ): array {
		$out  = array();
		$args = array(
			'timeout'     => 30,
			'redirection' => 0,
			'user-agent'  => 'SEOHouse-SEO-Transfer/' . SH_CORE_VERSION . '; ' . home_url( '/' ),
			'headers'     => array( 'Cache-Control' => 'no-cache' ),
		);
		$args = apply_filters( 'sh_seo_transfer_http_args', $args );
		$urls = array_values( array_unique( $urls ) );
		// several pages at a time: a full site is read well within a web server's time limit
		$multi = class_exists( '\\WpOrg\\Requests\\Requests' ) ? '\\WpOrg\\Requests\\Requests' : ( class_exists( '\\Requests' ) ? '\\Requests' : '' );
		if ( $multi && ! ( defined( 'WP_HTTP_BLOCK_EXTERNAL' ) && WP_HTTP_BLOCK_EXTERNAL ) && ! apply_filters( 'sh_seo_transfer_sequential', false ) ) {
			$opts = array(
				'timeout'          => $args['timeout'],
				'follow_redirects' => false,
				'useragent'        => $args['user-agent'],
				'verify'           => ABSPATH . WPINC . '/certificates/ca-bundle.crt',
			);
			foreach ( array_chunk( $urls, 8 ) as $chunk ) {
				$reqs = array();
				foreach ( $chunk as $u ) {
					$reqs[ $u ] = array( 'url' => $u, 'headers' => $args['headers'], 'type' => 'GET' );
				}
				foreach ( $multi::request_multiple( $reqs, $opts ) as $u => $r ) {
					if ( $r instanceof \Exception || ! is_object( $r ) || ! isset( $r->status_code ) ) {
						$out[ $u ] = array( 'code' => 0, 'location' => '', 'html' => '', 'error' => $r instanceof \Exception ? $r->getMessage() : 'no response' );
						continue;
					}
					$out[ $u ] = array( 'code' => (int) $r->status_code, 'location' => (string) ( $r->headers['location'] ?? '' ), 'html' => (string) $r->body, 'error' => '' );
				}
			}
			return $out;
		}
		foreach ( $urls as $u ) {
			$r = wp_remote_get( $u, $args );
			if ( is_wp_error( $r ) ) {
				$out[ $u ] = array( 'code' => 0, 'location' => '', 'html' => '', 'error' => $r->get_error_message() );
				continue;
			}
			$out[ $u ] = array(
				'code'     => (int) wp_remote_retrieve_response_code( $r ),
				'location' => (string) wp_remote_retrieve_header( $r, 'location' ),
				'html'     => (string) wp_remote_retrieve_body( $r ),
				'error'    => '',
			);
		}
		return $out;
	}

	/* ================================================================ main site */

	public function connect(): bool {
		$this->src = new SH_Post_Migration( array( 'dry_run' => true ) );
		if ( ! $this->src->connect() ) {
			$this->error = $this->src->error;
			return false;
		}
		return true;
	}

	/**
	 * Every public path of the main site, built from its database: path key => object.
	 * Object: {kind: post|term|home, type, id, label, meta: {title, description}}.
	 */
	public function source_map(): array {
		$s    = $this->src;
		$opts = array();
		foreach ( $s->query( "SELECT option_name, option_value FROM {$s->table('options')} WHERE option_name IN ('show_on_front','page_on_front','page_for_posts','permalink_structure','category_base','tag_base','rewrite_rules','rank-math-options-general','rank-math-options-titles')" ) as $r ) {
			$opts[ $r['option_name'] ] = $r['option_value'];
		}
		$this->src_opts = $opts;
		$ps             = (string) ( $opts['permalink_structure'] ?? '' );
		if ( '' === $ps ) {
			$this->error = 'الموقع الأساسي يستخدم الروابط الافتراضية (?p=)؛ لا يمكن المطابقة بالمسار.';
			return array();
		}
		$slash = str_ends_with( $ps, '/' ) ? '/' : '';
		$front = false !== strpos( $ps, '%' ) ? substr( $ps, 0, strpos( $ps, '%' ) ) : '/';
		$rules = maybe_unserialize( $opts['rewrite_rules'] ?? '' );
		$rules = is_array( $rules ) ? $rules : array();
		$types = wp_list_pluck( $s->query( "SELECT DISTINCT post_type FROM {$s->table('posts')} WHERE post_status = 'publish'" ), 'post_type' );

		// bases from the main site's own rewrite rules: custom types, categories, tags
		$base = array();
		foreach ( $rules as $pattern => $query ) {
			if ( ! preg_match( '#^index\.php\?([a-z0-9_-]+)=\$matches\[1\]#i', (string) $query, $q ) || ! preg_match( '#^([a-z0-9_\-/%]+?)/\((?:\[\^/\]\+|\.\+\?|\.\+)\)#i', (string) $pattern, $p ) ) {
				continue;
			}
			$qv  = $q[1];
			$key = 'category_name' === $qv ? 'category' : ( 'tag' === $qv ? 'post_tag' : $qv );
			if ( ! in_array( $key, array( 'category', 'post_tag' ), true ) && ( ! in_array( $qv, $types, true ) || in_array( $qv, array( 'post', 'page', 'attachment' ), true ) ) ) {
				continue;
			}
			if ( ! isset( $base[ $key ] ) || strlen( $p[1] ) < strlen( $base[ $key ] ) ) {
				$base[ $key ] = $p[1];
			}
		}
		$general = maybe_unserialize( $opts['rank-math-options-general'] ?? '' );
		$strip   = is_array( $general ) && 'on' === ( $general['strip_category_base'] ?? '' );
		if ( ! isset( $base['category'] ) ) {
			$base['category'] = ltrim( $front, '/' ) . ( '' !== (string) ( $opts['category_base'] ?? '' ) ? trim( $opts['category_base'], '/' ) : 'category' );
		}
		if ( ! isset( $base['post_tag'] ) ) {
			$base['post_tag'] = ltrim( $front, '/' ) . ( '' !== (string) ( $opts['tag_base'] ?? '' ) ? trim( $opts['tag_base'], '/' ) : 'tag' );
		}
		foreach ( $base as $k => $b ) {
			$this->src_notes[] = $k . ' → /' . ( 'category' === $k && $strip ? '' : $b . '/' ) . '…';
		}

		$map   = array();
		$posts = $s->query( "SELECT ID, post_type, post_name, post_parent, post_title, post_date, post_author FROM {$s->table('posts')} WHERE post_status = 'publish' AND post_type NOT IN ('attachment','revision','nav_menu_item','wp_block','wp_template','wp_template_part','wp_navigation','wp_global_styles','customize_changeset','oembed_cache','user_request','custom_css')" );
		$by_id = array();
		foreach ( $posts as $p ) {
			$by_id[ (int) $p['ID'] ] = $p;
		}
		$chain = static function ( array $p ) use ( $by_id ): string {
			$parts = array( rawurldecode( $p['post_name'] ) );
			$guard = 0;
			while ( (int) $p['post_parent'] && isset( $by_id[ (int) $p['post_parent'] ] ) && $guard++ < 20 ) {
				$p = $by_id[ (int) $p['post_parent'] ];
				array_unshift( $parts, rawurldecode( $p['post_name'] ) );
			}
			return implode( '/', $parts );
		};
		$front_id = 'page' === ( $opts['show_on_front'] ?? '' ) ? (int) ( $opts['page_on_front'] ?? 0 ) : 0;
		$meta     = array();
		$ids      = array_map( 'intval', array_keys( $by_id ) );
		if ( $ids ) {
			foreach ( $s->query( "SELECT post_id, meta_key, meta_value FROM {$s->table('postmeta')} WHERE meta_key IN ('rank_math_title','rank_math_description','rank_math_primary_category') AND post_id IN (" . implode( ',', $ids ) . ')' ) as $r ) {
				$meta[ (int) $r['post_id'] ][ $r['meta_key'] ] = $r['meta_value'];
			}
		}
		foreach ( $posts as $p ) {
			$id   = (int) $p['ID'];
			$type = $p['post_type'];
			$slug = rawurldecode( $p['post_name'] );
			if ( '' === $slug ) {
				continue;
			}
			if ( $id === $front_id ) {
				$path = '/';
			} elseif ( 'page' === $type ) {
				$path = '/' . $chain( $p ) . $slash;
			} elseif ( 'post' === $type ) {
				$path = $this->post_path( $ps, $p, $meta[ $id ] ?? array() );
			} elseif ( isset( $base[ $type ] ) ) {
				$path = '/' . trim( $base[ $type ], '/' ) . '/' . $chain( $p ) . $slash;
			} else {
				continue; // a type the main site shows at no address we can derive
			}
			$map[ self::key( $path ) ] = array(
				'kind'  => 'post',
				'type'  => $type,
				'id'    => $id,
				'label' => $p['post_title'],
				'meta'  => array(
					'title'       => (string) ( $meta[ $id ]['rank_math_title'] ?? '' ),
					'description' => (string) ( $meta[ $id ]['rank_math_description'] ?? '' ),
				),
			);
		}
		$titles = maybe_unserialize( $opts['rank-math-options-titles'] ?? '' );
		$titles = is_array( $titles ) ? $titles : array();
		// archives of custom types (e.g. /results/ listing the results): rule "base/?$" → post_type=…
		foreach ( $rules as $pattern => $query ) {
			if ( preg_match( '#^index\.php\?post_type=([a-z0-9_-]+)$#i', (string) $query, $q ) && preg_match( '#^([a-z0-9_\-/]+)/\?\$$#i', (string) $pattern, $p ) && in_array( $q[1], $types, true ) ) {
				$path = self::key( '/' . trim( $p[1], '/' ) . '/' );
				if ( ! isset( $map[ $path ] ) ) {
					$map[ $path ]      = array(
						'kind'  => 'archive',
						'type'  => $q[1],
						'id'    => 0,
						'label' => $q[1] . ' (أرشيف)',
						'meta'  => array( 'title' => (string) ( $titles[ "pt_{$q[1]}_archive_title" ] ?? '' ), 'description' => (string) ( $titles[ "pt_{$q[1]}_archive_description" ] ?? '' ) ),
					);
					$this->src_notes[] = 'أرشيف ' . $q[1] . ' → ' . $path;
				}
			}
		}
		if ( 'page' !== ( $opts['show_on_front'] ?? 'posts' ) || ! $front_id ) {
			$map['/']    = array(
				'kind'  => 'home',
				'type'  => 'home',
				'id'    => 0,
				'label' => 'الرئيسية',
				'meta'  => array( 'title' => (string) ( $titles['homepage_title'] ?? '' ), 'description' => (string) ( $titles['homepage_description'] ?? '' ) ),
			);
		}

		// categories and tags
		$terms = $s->query( "SELECT t.term_id, t.name, t.slug, tt.taxonomy, tt.parent FROM {$s->table('term_taxonomy')} tt JOIN {$s->table('terms')} t ON t.term_id = tt.term_id WHERE tt.taxonomy IN ('category','post_tag')" );
		$tby   = array();
		foreach ( $terms as $t ) {
			$tby[ $t['taxonomy'] ][ (int) $t['term_id'] ] = $t;
		}
		$tmeta = array();
		if ( $terms ) {
			foreach ( $s->query( "SELECT term_id, meta_key, meta_value FROM {$s->table('termmeta')} WHERE meta_key IN ('rank_math_title','rank_math_description') AND term_id IN (" . implode( ',', array_map( 'intval', wp_list_pluck( $terms, 'term_id' ) ) ) . ')' ) as $r ) {
				$tmeta[ (int) $r['term_id'] ][ $r['meta_key'] ] = $r['meta_value'];
			}
		}
		foreach ( $terms as $t ) {
			$parts = array( rawurldecode( $t['slug'] ) );
			$x     = $t;
			$guard = 0;
			while ( 'category' === $t['taxonomy'] && (int) $x['parent'] && isset( $tby['category'][ (int) $x['parent'] ] ) && $guard++ < 20 ) {
				$x = $tby['category'][ (int) $x['parent'] ];
				array_unshift( $parts, rawurldecode( $x['slug'] ) );
			}
			$prefix = 'category' === $t['taxonomy'] && $strip ? '' : trim( $base[ $t['taxonomy'] ], '/' ) . '/';
			$tid    = (int) $t['term_id'];
			$map[ self::key( '/' . $prefix . implode( '/', $parts ) . '/' ) ] = array(
				'kind'  => 'term',
				'type'  => $t['taxonomy'],
				'id'    => $tid,
				'label' => $t['name'],
				'meta'  => array(
					'title'       => (string) ( $tmeta[ $tid ]['rank_math_title'] ?? '' ),
					'description' => (string) ( $tmeta[ $tid ]['rank_math_description'] ?? '' ),
				),
			);
		}
		return $this->src_map = $map;
	}

	private function post_path( string $ps, array $p, array $meta ): string {
		$d    = strtotime( $p['post_date'] );
		$repl = array(
			'%postname%' => rawurldecode( $p['post_name'] ),
			'%post_id%'  => (string) $p['ID'],
			'%year%'     => gmdate( 'Y', $d ),
			'%monthnum%' => gmdate( 'm', $d ),
			'%day%'      => gmdate( 'd', $d ),
			'%hour%'     => gmdate( 'H', $d ),
			'%minute%'   => gmdate( 'i', $d ),
			'%second%'   => gmdate( 's', $d ),
		);
		if ( str_contains( $ps, '%category%' ) ) {
			$s    = $this->src;
			$cats = $s->query( "SELECT t.term_id, t.slug, tt.parent FROM {$s->table('term_relationships')} tr JOIN {$s->table('term_taxonomy')} tt ON tt.term_taxonomy_id = tr.term_taxonomy_id JOIN {$s->table('terms')} t ON t.term_id = tt.term_id WHERE tt.taxonomy = 'category' AND tr.object_id = " . (int) $p['ID'] . ' ORDER BY t.term_id' );
			$pick = $cats[0] ?? null;
			foreach ( $cats as $c ) {
				if ( (int) $c['term_id'] === (int) ( $meta['rank_math_primary_category'] ?? 0 ) ) {
					$pick = $c;
				}
			}
			$repl['%category%'] = $pick ? rawurldecode( $pick['slug'] ) : 'uncategorized';
		}
		if ( str_contains( $ps, '%author%' ) ) {
			$u                = $this->src->query( "SELECT user_nicename FROM {$this->src->table('users')} WHERE ID = " . (int) $p['post_author'] );
			$repl['%author%'] = $u ? $u[0]['user_nicename'] : '';
		}
		return strtr( $ps, $repl );
	}

	/* ================================================================ this site */

	/** Everything here that has a title/description in Rank Math, with its path. */
	public function target_items(): array {
		$home  = untrailingslashit( home_url() );
		$items = array();
		$rel   = static function ( string $url ) use ( $home ): string {
			return str_starts_with( $url, $home ) ? substr( $url, strlen( $home ) ) : (string) wp_parse_url( $url, PHP_URL_PATH );
		};
		$types = array_values( array_diff( get_post_types( array( 'public' => true ) ), array( 'attachment' ) ) );
		$front = 'page' === get_option( 'show_on_front' ) ? (int) get_option( 'page_on_front' ) : 0;
		$posts = get_posts(
			array(
				'post_type'        => $types,
				'post_status'      => array( 'publish', 'draft', 'pending', 'private', 'future' ),
				'posts_per_page'   => -1,
				'orderby'          => array( 'post_type' => 'ASC', 'menu_order' => 'ASC', 'title' => 'ASC' ),
				'suppress_filters' => true,
			)
		);
		foreach ( $posts as $p ) {
			if ( (int) $p->ID === $front ) {
				$path = '/';
			} else {
				$tmp = clone $p; // the address it has (or will have once published)
				if ( 'publish' !== $p->post_status ) {
					$tmp->post_status = 'publish';
					$tmp->post_name   = $p->post_name ? $p->post_name : sanitize_title( $p->post_title );
				}
				$path = $rel( (string) get_permalink( $tmp ) );
			}
			if ( '' === $path || str_contains( $path, '?' ) ) {
				continue;
			}
			$items[] = array(
				'kind'   => 'post',
				'type'   => $p->post_type,
				'id'     => (int) $p->ID,
				'label'  => get_the_title( $p ),
				'status' => $p->post_status,
				'path'   => self::key( $path ),
			);
		}
		if ( ! $front ) {
			$items[] = array( 'kind' => 'home', 'type' => 'home', 'id' => 0, 'label' => 'الرئيسية', 'status' => 'publish', 'path' => '/' );
		}
		foreach ( get_terms( array( 'taxonomy' => array( 'category', 'post_tag' ), 'hide_empty' => false ) ) as $t ) {
			$link = get_term_link( $t );
			if ( is_wp_error( $link ) ) {
				continue;
			}
			$items[] = array(
				'kind'   => 'term',
				'type'   => $t->taxonomy,
				'id'     => (int) $t->term_id,
				'label'  => $t->name,
				'status' => 'publish',
				'path'   => self::key( $rel( $link ) ),
			);
		}
		return $items;
	}

	/** Stored Rank Math value ('' when empty). */
	public static function stored( array $it, string $field ): string {
		$key = self::FIELDS[ $field ];
		if ( 'post' === $it['kind'] ) {
			return (string) get_post_meta( $it['id'], $key, true );
		}
		if ( 'term' === $it['kind'] ) {
			return (string) get_term_meta( $it['id'], $key, true );
		}
		$titles = get_option( 'rank-math-options-titles', array() );
		return (string) ( $titles[ 'title' === $field ? 'homepage_title' : 'homepage_description' ] ?? '' );
	}

	private static function last( array $it ): array {
		if ( 'post' === $it['kind'] ) {
			$v = get_post_meta( $it['id'], self::META_LAST, true );
		} elseif ( 'term' === $it['kind'] ) {
			$v = get_term_meta( $it['id'], self::META_LAST, true );
		} else {
			$v = get_option( self::OPT_HOME, array() )['last'] ?? array();
		}
		return is_array( $v ) ? $v : array();
	}

	/** Values that were put there automatically (anything else non-empty was typed by someone). */
	private function automatic( array $it, string $field, ?array $src ): array {
		$vals = array( '' );
		$last = self::last( $it );
		if ( isset( $last[ $field ] ) ) {
			$vals[] = (string) $last[ $field ];
		}
		if ( $src ) {
			$vals[] = (string) $src['meta'][ $field ]; // copied by «نقل المقالات»
		}
		if ( 'post' === $it['kind'] && function_exists( 'sh_rankmath_core_value' ) ) {
			$p = get_post( $it['id'] );
			if ( $p ) {
				$vals[] = (string) sh_rankmath_core_value( $p, 'title' === $field ? 'sh_seo_title' : 'sh_seo_description' ); // design value
			}
		}
		return array_map( array( __CLASS__, 'norm' ), $vals );
	}

	/** What Rank Math shows for an object that cannot be requested (drafts): its field or its template. */
	private static function computed( array $it, string $field ): string {
		$v = self::stored( $it, $field );
		if ( ! class_exists( '\RankMath\Helper' ) || ! method_exists( '\RankMath\Helper', 'replace_vars' ) ) {
			return self::norm( $v );
		}
		if ( '' === $v ) {
			$tpl = 'post' === $it['kind'] ? "pt_{$it['type']}_{$field}" : ( 'term' === $it['kind'] ? "tax_{$it['type']}_{$field}" : 'homepage_' . $field );
			$v   = (string) \RankMath\Helper::get_settings( 'titles.' . $tpl );
		}
		$obj = 'post' === $it['kind'] ? get_post( $it['id'] ) : ( 'term' === $it['kind'] ? get_term( $it['id'] ) : array() );
		return self::norm( \RankMath\Helper::replace_vars( $v, $obj ) );
	}

	/* ================================================================ plan */

	/**
	 * One row per object here: its old values (main site, same path), new values (this site) and
	 * the proposed action per field: write | same | empty | manual | nosource | error.
	 */
	public function plan(): array {
		$this->rows   = array();
		$this->counts = array( 'write' => 0, 'same' => 0, 'empty' => 0, 'manual' => 0, 'nosource' => 0, 'error' => 0 );
		if ( ! $this->src_map && ! $this->source_map() ) {
			return array();
		}
		$items   = $this->target_items();
		$src_url = untrailingslashit( $this->src->source_home() );
		$new_url = untrailingslashit( home_url() );
		$old_q   = array();
		$new_q   = array();
		foreach ( $items as $it ) {
			if ( isset( $this->src_map[ $it['path'] ] ) ) {
				$old_q[] = $src_url . self::encode( $it['path'] );
				if ( 'publish' === $it['status'] ) {
					$new_q[] = $new_url . self::encode( $it['path'] );
				}
			}
		}
		$old_r = self::fetch( $old_q );
		$new_r = self::fetch( $new_q );
		$used  = array();
		foreach ( $items as $it ) {
			$src = $this->src_map[ $it['path'] ] ?? null;
			$row = $it + array(
				'source'  => $src ? array( 'kind' => $src['kind'], 'type' => $src['type'], 'id' => $src['id'], 'label' => $src['label'] ) : null,
				'old'     => array( 'title' => '', 'description' => '', 'robots' => '', 'canonical' => '' ),
				'new'     => array( 'title' => '', 'description' => '' ),
				'stored'  => array( 'title' => self::stored( $it, 'title' ), 'description' => self::stored( $it, 'description' ) ),
				'action'  => array(),
				'note'    => '',
			);
			if ( ! $src ) {
				$row['action'] = array( 'title' => 'nosource', 'description' => 'nosource' );
				++$this->counts['nosource'];
				$this->rows[] = $row;
				continue;
			}
			$used[ $it['path'] ] = true;
			$o                   = $old_r[ $src_url . self::encode( $it['path'] ) ] ?? null;
			if ( ! $o || 200 !== $o['code'] ) {
				$row['note']   = ! $o ? '' : ( $o['error'] ? $o['error'] : 'HTTP ' . $o['code'] . ( $o['location'] ? ' → ' . $o['location'] : '' ) );
				$row['action'] = array( 'title' => 'error', 'description' => 'error' );
				++$this->counts['error'];
				$this->rows[] = $row;
				continue;
			}
			$row['old'] = array_intersect_key( self::parse( $o['html'] ), $row['old'] );
			if ( 'archive' === $src['kind'] ) {
				$row['note'] = 'في الأساسي أرشيف نوع «' . $src['type'] . '» وقيمه من قالب Rank Math التلقائي؛ راجعها بعد النقل';
			}
			if ( 'publish' === $it['status'] ) {
				$n = $new_r[ $new_url . self::encode( $it['path'] ) ] ?? null;
				if ( $n && 200 === $n['code'] ) {
					$row['new'] = array_intersect_key( self::parse( $n['html'] ), $row['new'] );
				} else {
					$row['new']  = array( 'title' => self::computed( $it, 'title' ), 'description' => self::computed( $it, 'description' ) );
					$row['note'] = ( '' !== $row['note'] ? $row['note'] . ' — ' : '' ) . 'تعذّر طلب الصفحة هنا' . ( $n ? ' (HTTP ' . $n['code'] . ')' : '' ) . '؛ القيم الجديدة محسوبة من Rank Math';
				}
			} else {
				$row['new']  = array( 'title' => self::computed( $it, 'title' ), 'description' => self::computed( $it, 'description' ) );
				$row['note'] = ( '' !== $row['note'] ? $row['note'] . ' — ' : '' ) . 'غير منشور هنا؛ القيم الجديدة محسوبة من Rank Math';
			}
			foreach ( array_keys( self::FIELDS ) as $f ) {
				$old = $row['old'][ $f ];
				if ( '' === $old ) {
					$a = 'empty';
				} elseif ( $old === self::norm( $row['new'][ $f ] ) ) {
					$a = 'same';
				} elseif ( ! in_array( self::norm( $row['stored'][ $f ] ), $this->automatic( $it, $f, $src ), true ) ) {
					$a = 'manual';
				} else {
					$a = 'write';
				}
				$row['action'][ $f ] = $a;
				++$this->counts[ $a ];
			}
			$this->rows[] = $row;
		}
		$this->src_only = array_diff_key( $this->src_map, $used );
		return $this->rows;
	}

	/** @var array<string,array> main-site paths with no object here */
	public $src_only = array();

	/* ================================================================ run */

	/** Writes the 'write' rows. Returns {written, skipped, backup}. */
	public function run(): array {
		$rows = $this->rows ? $this->rows : $this->plan();
		$res  = array( 'written' => 0, 'changed' => 0, 'objects' => 0, 'backup' => '' );
		if ( ! $rows ) {
			return $res;
		}
		$res['backup'] = $this->backup( $rows );
		$now           = time();
		foreach ( $rows as $i => $r ) {
			$todo = array_keys( array_filter( $r['action'], static fn( $a ) => 'write' === $a ) );
			if ( ! $todo ) {
				continue;
			}
			$last = self::last( $r );
			$orig = $this->orig( $r );
			$done = 0;
			foreach ( $todo as $f ) {
				// re-read just before writing: never replace a value someone saved meanwhile
				if ( self::stored( $r, $f ) !== $r['stored'][ $f ] ) {
					$this->rows[ $i ]['action'][ $f ] = 'manual';
					++$res['changed'];
					continue;
				}
				if ( ! array_key_exists( $f, $orig ) ) {
					$orig[ $f ] = $r['stored'][ $f ];
				}
				$this->store( $r, $f, $r['old'][ $f ] );
				$last[ $f ] = $r['old'][ $f ];
				++$done;
			}
			if ( $done ) {
				$last['time'] = $now;
				$last['from'] = untrailingslashit( $this->src->source_home() ) . $r['path'];
				$this->mark( $r, $last, $orig );
				$res['written'] += $done;
				++$res['objects'];
			}
		}
		update_option( 'sh_seo_transfer_last_run', array( 'time' => $now, 'written' => $res['written'], 'objects' => $res['objects'], 'backup' => $res['backup'] ), false );
		return $res;
	}

	private function orig( array $r ): array {
		if ( 'post' === $r['kind'] ) {
			$v = get_post_meta( $r['id'], self::META_ORIG, true );
		} elseif ( 'term' === $r['kind'] ) {
			$v = get_term_meta( $r['id'], self::META_ORIG, true );
		} else {
			$v = get_option( self::OPT_HOME, array() )['orig'] ?? array();
		}
		return is_array( $v ) ? $v : array();
	}

	private function mark( array $r, array $last, array $orig ): void {
		if ( 'post' === $r['kind'] ) {
			update_post_meta( $r['id'], self::META_LAST, wp_slash( $last ) );
			update_post_meta( $r['id'], self::META_ORIG, wp_slash( $orig ) );
		} elseif ( 'term' === $r['kind'] ) {
			update_term_meta( $r['id'], self::META_LAST, wp_slash( $last ) );
			update_term_meta( $r['id'], self::META_ORIG, wp_slash( $orig ) );
		} else {
			update_option( self::OPT_HOME, array( 'last' => $last, 'orig' => $orig ), false );
		}
	}

	/** Writes one value where Rank Math reads it ('' removes it). */
	private static function store( array $r, string $f, string $value ): void {
		$key = self::FIELDS[ $f ];
		if ( 'post' === $r['kind'] ) {
			'' === $value ? delete_post_meta( $r['id'], $key ) : update_post_meta( $r['id'], $key, wp_slash( $value ) );
		} elseif ( 'term' === $r['kind'] ) {
			'' === $value ? delete_term_meta( $r['id'], $key ) : update_term_meta( $r['id'], $key, wp_slash( $value ) );
		} else {
			$titles = get_option( 'rank-math-options-titles', array() );
			$titles = is_array( $titles ) ? $titles : array();
			$titles[ 'title' === $f ? 'homepage_title' : 'homepage_description' ] = $value;
			update_option( 'rank-math-options-titles', $titles );
		}
	}

	/** Copy of every current stored value (all objects, not only the ones about to change). */
	private function backup( array $rows ): string {
		$copy = array(
			'time'   => gmdate( 'c' ),
			'site'   => home_url( '/' ),
			'source' => $this->src->source_home(),
			'values' => array(),
		);
		foreach ( $rows as $r ) {
			$copy['values'][] = array(
				'kind'        => $r['kind'],
				'type'        => $r['type'],
				'id'          => $r['id'],
				'path'        => $r['path'],
				'title'       => $r['stored']['title'],
				'description' => $r['stored']['description'],
			);
		}
		$all = get_option( self::OPT_BAK, array() );
		$all = is_array( $all ) ? $all : array();
		array_unshift( $all, $copy );
		update_option( self::OPT_BAK, array_slice( $all, 0, 10 ), false );
		$up  = wp_upload_dir();
		$dir = trailingslashit( $up['basedir'] ) . 'seohouse-backups';
		wp_mkdir_p( $dir );
		if ( ! file_exists( $dir . '/index.php' ) ) {
			file_put_contents( $dir . '/index.php', "<?php // Silence.\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions
			file_put_contents( $dir . '/.htaccess', "Require all denied\nDeny from all\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		}
		$file = $dir . '/seo-meta-before-transfer-' . gmdate( 'Ymd-His' ) . '-' . wp_generate_password( 6, false ) . '.json';
		file_put_contents( $file, wp_json_encode( $copy, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		return str_replace( ABSPATH, '', $file );
	}

	/**
	 * Puts back the values from before the first transfer, for fields still holding what this screen
	 * wrote (an edit made since is kept). Returns the number of values restored.
	 */
	public static function restore(): int {
		$n     = 0;
		$items = array();
		foreach ( get_posts( array( 'post_type' => array_values( get_post_types( array( 'public' => true ) ) ), 'post_status' => 'any', 'posts_per_page' => -1, 'meta_key' => self::META_ORIG, 'fields' => 'ids' ) ) as $id ) { // phpcs:ignore WordPress.DB.SlowDBQuery
			$items[] = array( 'kind' => 'post', 'id' => (int) $id, 'type' => get_post_type( $id ) );
		}
		foreach ( get_terms( array( 'taxonomy' => array( 'category', 'post_tag' ), 'hide_empty' => false, 'meta_key' => self::META_ORIG ) ) as $t ) { // phpcs:ignore WordPress.DB.SlowDBQuery
			$items[] = array( 'kind' => 'term', 'id' => (int) $t->term_id, 'type' => $t->taxonomy );
		}
		if ( get_option( self::OPT_HOME ) ) {
			$items[] = array( 'kind' => 'home', 'id' => 0, 'type' => 'home' );
		}
		$self = new self();
		foreach ( $items as $it ) {
			$orig = $self->orig( $it );
			$last = self::last( $it );
			foreach ( $orig as $f => $v ) {
				if ( isset( self::FIELDS[ $f ] ) && isset( $last[ $f ] ) && self::norm( self::stored( $it, $f ) ) === self::norm( $last[ $f ] ) ) {
					self::store( $it, $f, (string) $v );
					++$n;
				}
			}
			if ( 'post' === $it['kind'] ) {
				delete_post_meta( $it['id'], self::META_ORIG );
				delete_post_meta( $it['id'], self::META_LAST );
			} elseif ( 'term' === $it['kind'] ) {
				delete_term_meta( $it['id'], self::META_ORIG );
				delete_term_meta( $it['id'], self::META_LAST );
			} else {
				delete_option( self::OPT_HOME );
			}
		}
		return $n;
	}

	/* ================================================================ comparison */

	/**
	 * Requests every shared URL (same path on both sites, published here) and compares the <title>
	 * and meta description shown. Saved as the last report.
	 */
	public function compare(): array {
		if ( ! $this->rows ) {
			$this->plan();
		}
		$src_url = untrailingslashit( $this->src->source_home() );
		$new_url = untrailingslashit( home_url() );
		$shared  = array_values( array_filter( $this->rows, static fn( $r ) => $r['source'] && 'publish' === $r['status'] ) );
		$old_r   = self::fetch( array_map( static fn( $r ) => $src_url . self::encode( $r['path'] ), $shared ) );
		$new_r   = self::fetch( array_map( static fn( $r ) => $new_url . self::encode( $r['path'] ), $shared ) );
		$out     = array();
		$sum     = array( 'match' => 0, 'expected' => 0, 'mismatch' => 0 );
		foreach ( $shared as $r ) {
			$o    = $old_r[ $src_url . self::encode( $r['path'] ) ] ?? array( 'code' => 0, 'html' => '' );
			$n    = $new_r[ $new_url . self::encode( $r['path'] ) ] ?? array( 'code' => 0, 'html' => '' );
			$op   = self::parse( 200 === $o['code'] ? $o['html'] : '' );
			$np   = self::parse( 200 === $n['code'] ? $n['html'] : '' );
			$line = array(
				'path'   => $r['path'],
				'label'  => $r['label'],
				'type'   => $r['type'],
				'http'   => $o['code'] . '/' . $n['code'],
				'robots' => $np['robots'],
				'fields' => array(),
			);
			$worst = 'match';
			foreach ( array_keys( self::FIELDS ) as $f ) {
				$ok     = 200 === $o['code'] && 200 === $n['code'] && $op[ $f ] === $np[ $f ];
				$reason = '';
				if ( ! $ok ) {
					$a      = $r['action'][ $f ] ?? '';
					$reason = 200 !== $o['code'] || 200 !== $n['code'] ? 'لم تُقرأ الصفحة (' . $line['http'] . ')' : ( 'manual' === $a ? 'معدّل يدويًا هنا — تُرك' : ( 'empty' === $a ? 'الوصف في الأساسي فارغ — بقي وصف الجديد' : 'يحتاج مراجعة' ) );
				}
				$state                = $ok ? 'match' : ( 'يحتاج مراجعة' === $reason || str_starts_with( $reason, 'لم' ) ? 'mismatch' : 'expected' );
				$line['fields'][ $f ] = array( 'old' => $op[ $f ], 'new' => $np[ $f ], 'state' => $state, 'reason' => $reason );
				if ( 'mismatch' === $state || ( 'expected' === $state && 'match' === $worst ) ) {
					$worst = $state;
				}
			}
			$line['state'] = $worst;
			++$sum[ $worst ];
			$out[] = $line;
		}
		$report = array( 'time' => time(), 'source' => $src_url, 'site' => $new_url, 'summary' => $sum, 'rows' => $out );
		update_option( self::OPT_REP, $report, false );
		return $report;
	}

	public static function report_csv( array $rep ): string {
		$h = fopen( 'php://temp', 'r+' );
		fputcsv( $h, array( 'path', 'type', 'state', 'old_title', 'new_title', 'title_state', 'title_reason', 'old_description', 'new_description', 'description_state', 'description_reason', 'robots_new', 'http_old/new' ), ',', '"', '' );
		foreach ( $rep['rows'] ?? array() as $l ) {
			$t = $l['fields']['title'];
			$d = $l['fields']['description'];
			fputcsv( $h, array( $l['path'], $l['type'], $l['state'], $t['old'], $t['new'], $t['state'], $t['reason'], $d['old'], $d['new'], $d['state'], $d['reason'], $l['robots'], $l['http'] ), ',', '"', '' );
		}
		rewind( $h );
		$csv = (string) stream_get_contents( $h );
		fclose( $h );
		return "\xEF\xBB\xBF" . $csv;
	}
}

/* ------------------------------------------------------------------ screen */

add_action(
	'admin_menu',
	static function () {
		add_submenu_page( 'seohouse-settings', __( 'نقل عناوين وأوصاف SEO', 'seohouse-core' ), __( 'نقل عناوين وأوصاف SEO', 'seohouse-core' ), 'manage_options', 'seohouse-seo-transfer', 'sh_seo_transfer_page' );
	},
	27
);

add_action(
	'admin_post_sh_seo_transfer_csv',
	static function () {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( '', 403 );
		}
		check_admin_referer( 'sh_seo_transfer_csv' );
		$rep = get_option( SH_SEO_Transfer::OPT_REP, array() );
		nocache_headers();
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=seo-transfer-report-' . gmdate( 'Ymd-His', (int) ( $rep['time'] ?? time() ) ) . '.csv' );
		echo SH_SEO_Transfer::report_csv( is_array( $rep ) ? $rep : array() ); // phpcs:ignore WordPress.Security.EscapeOutput
		exit;
	}
);

function sh_seo_transfer_label( string $a, string $f ): string {
	$l = array(
		'write'    => 'title' === $f ? 'يُنقل العنوان' : 'يُنقل الوصف',
		'same'     => 'متطابق — تخطٍّ',
		'empty'    => 'title' === $f ? 'العنوان القديم فارغ — يبقى الحالي' : 'الوصف القديم فارغ — يبقى الحالي',
		'manual'   => 'معدّل يدويًا هنا — لا يُكتب فوقه',
		'nosource' => 'لا صفحة بنفس المسار في الأساسي',
		'error'    => 'تعذّرت قراءة الصفحة القديمة',
	);
	return $l[ $a ] ?? $a;
}

function sh_seo_transfer_type( array $r ): string {
	$t = array( 'page' => 'صفحة', 'post' => 'مقال', 'case_study' => 'نتيجة', 'team_member' => 'فريق', 'category' => 'تصنيف', 'post_tag' => 'وسم', 'home' => 'الرئيسية', 'sector' => 'قطاع' );
	$s = $t[ $r['type'] ] ?? $r['type'];
	if ( 'post' === $r['kind'] && (int) get_option( 'page_for_posts' ) === (int) $r['id'] ) {
		$s = 'أرشيف المدونة';
	} elseif ( '/' === $r['path'] ) {
		$s = 'الرئيسية';
	} elseif ( 'page' === $r['type'] && str_starts_with( $r['path'], '/sectors/' ) ) {
		$s = 'قطاع';
	}
	return $s;
}

function sh_seo_transfer_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$action = isset( $_POST['sh_seo_transfer'] ) ? sanitize_key( wp_unslash( $_POST['sh_seo_transfer'] ) ) : '';
	if ( $action ) {
		check_admin_referer( 'sh_seo_transfer' );
		@set_time_limit( 600 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
	}
	echo '<div class="wrap" id="sh-seo-transfer"><h1>' . esc_html__( 'نقل عناوين وأوصاف SEO', 'seohouse-core' ) . '</h1>';
	echo '<p style="max-width:64em;font-size:14px">' . esc_html__( 'ينقل عنوان الصفحة (title) ووصفها (meta description) كما يظهران فعلًا في الموقع الأساسي إلى حقلي Rank Math للصفحة التي لها المسار نفسه هنا (بعد حذف /new/). يقرأ قاعدة الموقع الأساسي وصفحاته فقط ولا يكتب فيها. لا يغيّر العنوان الرئيسي H1 ولا المحتوى ولا الرابط، ولا ينقل robots أو canonical. الوصف القديم الفارغ لا يمسح وصف الجديد، والقيم المتطابقة والقيم التي عدّلتها هنا يدويًا تُترك. قبل التنفيذ تُحفظ نسخة من القيم الحالية.', 'seohouse-core' ) . '</p>';

	if ( ! function_exists( 'sh_rankmath_active' ) || ! sh_rankmath_active() ) {
		echo '<div class="notice notice-warning inline"><p>' . esc_html__( 'Rank Math غير مفعّلة هنا أو متوقفة؛ فعّلها أولًا («نقل السيو إلى Rank Math»)، فالقيم تُكتب في حقولها.', 'seohouse-core' ) . '</p></div></div>';
		return;
	}
	if ( 'restore' === $action ) {
		$n = SH_SEO_Transfer::restore();
		echo '<div class="notice notice-success inline"><p>' . esc_html( sprintf( 'أُعيدت %d قيمة إلى ما كانت عليه قبل النقل (القيم التي عُدّلت بعد النقل تُركت).', $n ) ) . '</p></div>';
		$action = 'preview';
	}
	$tr = new SH_SEO_Transfer();
	if ( ! $tr->connect() ) {
		echo '<div class="notice notice-error"><p>' . esc_html( $tr->error ) . '</p></div></div>';
		return;
	}
	$d = $tr->src->describe();
	echo '<table class="widefat" style="max-width:64em"><tbody>';
	echo '<tr><th style="width:14em">' . esc_html__( 'الموقع الأساسي', 'seohouse-core' ) . '</th><td dir="ltr">' . esc_html( $d['home'] ) . '</td></tr>';
	echo '<tr><th>' . esc_html__( 'القاعدة / البادئة', 'seohouse-core' ) . '</th><td dir="ltr">' . esc_html( $d['db'] ) . ' <small>(' . esc_html__( 'قراءة فقط', 'seohouse-core' ) . ')</small></td></tr>';
	echo '<tr><th>' . esc_html__( 'هذا الموقع', 'seohouse-core' ) . '</th><td dir="ltr">' . esc_html( home_url( '/' ) ) . '</td></tr>';
	echo '</tbody></table>';

	$res = null;
	if ( 'run' === $action ) {
		$tr->plan();
		$res = $tr->run();
		$rep = $tr->compare();
		echo '<div class="notice notice-success inline" style="max-width:64em"><p><strong>' . esc_html__( 'اكتمل النقل.', 'seohouse-core' ) . '</strong> ' . esc_html( sprintf( 'كُتبت %d قيمة في %d صفحة. ', $res['written'], $res['objects'] ) ) . ( $res['changed'] ? esc_html( sprintf( 'تُركت %d قيمة تغيّرت أثناء التنفيذ. ', $res['changed'] ) ) : '' ) . esc_html( 'نسخة القيم السابقة: ' . $res['backup'] ) . '</p></div>';
	} elseif ( 'compare' === $action ) {
		$tr->plan();
		$rep = $tr->compare();
	} elseif ( 'preview' === $action ) {
		$tr->plan();
	}
	if ( '' !== $tr->error ) {
		echo '<div class="notice notice-error inline"><p>' . esc_html( $tr->error ) . '</p></div>';
	}

	if ( $action && $tr->rows && 'run' !== $action && 'compare' !== $action ) {
		$c = $tr->counts;
		echo '<div class="notice notice-info inline" style="max-width:64em"><p><strong>' . esc_html__( 'معاينة — لم يُكتب شيء.', 'seohouse-core' ) . '</strong> ' . esc_html( sprintf( 'سيُنقل: %d — متطابق: %d — قديم فارغ: %d — معدّل يدويًا: %d — بلا مقابل: %d صفحة — تعذّرت قراءته: %d صفحة', $c['write'], $c['same'], $c['empty'], $c['manual'], $c['nosource'], $c['error'] ) ) . '</p></div>';
		echo '<p class="description">' . esc_html( 'مسارات الأنواع في الأساسي (من قاعدته): ' . implode( ' — ', $tr->src_notes ) ) . '</p>';
		echo '<table class="widefat striped sh-seo-plan" style="font-size:12px"><thead><tr><th>' . esc_html__( 'الرابط', 'seohouse-core' ) . '</th><th>' . esc_html__( 'عنوان SEO القديم', 'seohouse-core' ) . '</th><th>' . esc_html__( 'عنوان SEO الجديد', 'seohouse-core' ) . '</th><th>' . esc_html__( 'الوصف القديم', 'seohouse-core' ) . '</th><th>' . esc_html__( 'الوصف الجديد', 'seohouse-core' ) . '</th><th>' . esc_html__( 'الإجراء المقترح', 'seohouse-core' ) . '</th></tr></thead><tbody>';
		$matched = array_filter( $tr->rows, static fn( $r ) => null !== $r['source'] );
		foreach ( $matched as $r ) {
			$act = array();
			foreach ( array( 'title', 'description' ) as $f ) {
				$act[] = '<span class="sh-act sh-act--' . esc_attr( $r['action'][ $f ] ) . '" data-field="' . esc_attr( $f ) . '">' . esc_html( sh_seo_transfer_label( $r['action'][ $f ], $f ) ) . '</span>';
			}
			echo '<tr data-path="' . esc_attr( $r['path'] ) . '"><td dir="ltr" style="text-align:left"><code>' . esc_html( $r['path'] ) . '</code><br><small dir="rtl">' . esc_html( sh_seo_transfer_type( $r ) . ( 'publish' !== $r['status'] ? ' — ' . $r['status'] : '' ) ) . '</small></td>';
			echo '<td dir="auto" class="sh-old-title">' . esc_html( $r['old']['title'] ) . '</td><td dir="auto" class="sh-new-title">' . esc_html( $r['new']['title'] ) . '</td>';
			echo '<td dir="auto" class="sh-old-desc">' . ( '' === $r['old']['description'] ? '<em>' . esc_html__( '(فارغ)', 'seohouse-core' ) . '</em>' : esc_html( $r['old']['description'] ) ) . '</td><td dir="auto" class="sh-new-desc">' . esc_html( $r['new']['description'] ) . '</td>';
			echo '<td>' . implode( '<br>', $act ) . ( $r['note'] ? '<br><small>' . esc_html( $r['note'] ) . '</small>' : '' ) . '</td></tr>'; // phpcs:ignore WordPress.Security.EscapeOutput
		}
		echo '</tbody></table>';
		$none = array_filter( $tr->rows, static fn( $r ) => null === $r['source'] );
		if ( $none ) {
			echo '<details style="margin-top:12px"><summary>' . esc_html( sprintf( 'صفحات هنا بلا صفحة بنفس المسار في الأساسي (%d) — لا تُغيَّر', count( $none ) ) ) . '</summary><ul class="sh-nosource" dir="ltr" style="text-align:left">';
			foreach ( $none as $r ) {
				echo '<li><code>' . esc_html( $r['path'] ) . '</code> — <span dir="rtl">' . esc_html( $r['label'] . ' (' . sh_seo_transfer_type( $r ) . ')' ) . '</span></li>';
			}
			echo '</ul></details>';
		}
		if ( $tr->src_only ) {
			echo '<details style="margin-top:8px"><summary>' . esc_html( sprintf( 'مسارات في الأساسي بلا صفحة هنا (%d) — للعلم', count( $tr->src_only ) ) ) . '</summary><ul class="sh-srconly" dir="ltr" style="text-align:left">';
			foreach ( $tr->src_only as $p => $o ) {
				echo '<li><code>' . esc_html( $p ) . '</code> — <span dir="rtl">' . esc_html( $o['label'] . ' (' . $o['type'] . ')' ) . '</span></li>';
			}
			echo '</ul></details>';
		}
	}

	echo '<form method="post" style="margin-top:16px">';
	wp_nonce_field( 'sh_seo_transfer' );
	echo '<button class="button button-large" id="sh-seo-preview" name="sh_seo_transfer" value="preview">' . esc_html__( 'معاينة', 'seohouse-core' ) . '</button> ';
	echo '<button class="button button-primary button-large" id="sh-seo-run" name="sh_seo_transfer" value="run" onclick="return confirm(\'' . esc_js( __( 'نقل العناوين والأوصاف المقترحة إلى حقول Rank Math؟ تُحفظ نسخة من القيم الحالية أولًا.', 'seohouse-core' ) ) . '\')">' . esc_html__( 'تنفيذ النقل', 'seohouse-core' ) . '</button> ';
	echo '<button class="button button-large" id="sh-seo-compare" name="sh_seo_transfer" value="compare">' . esc_html__( 'مقارنة العرض الآن', 'seohouse-core' ) . '</button> ';
	if ( get_option( SH_SEO_Transfer::OPT_HOME ) || get_posts( array( 'post_type' => array_values( get_post_types( array( 'public' => true ) ) ), 'post_status' => 'any', 'posts_per_page' => 1, 'meta_key' => SH_SEO_Transfer::META_ORIG, 'fields' => 'ids' ) ) || get_terms( array( 'taxonomy' => array( 'category', 'post_tag' ), 'hide_empty' => false, 'meta_key' => SH_SEO_Transfer::META_ORIG, 'fields' => 'ids' ) ) ) { // phpcs:ignore WordPress.DB.SlowDBQuery
		echo '<button class="button button-link-delete" id="sh-seo-restore" name="sh_seo_transfer" value="restore" onclick="return confirm(\'' . esc_js( __( 'إعادة القيم التي كانت قبل النقل؟ القيم التي عدّلتها بعد النقل تبقى.', 'seohouse-core' ) ) . '\')">' . esc_html__( 'استعادة القيم السابقة', 'seohouse-core' ) . '</button>';
	}
	echo '</form>';

	$rep = get_option( SH_SEO_Transfer::OPT_REP );
	if ( is_array( $rep ) && ! empty( $rep['rows'] ) ) {
		$s = $rep['summary'];
		echo '<h2>' . esc_html( sprintf( 'آخر مقارنة للعرض: %s', wp_date( 'Y-m-d H:i', (int) $rep['time'] ) ) ) . '</h2>';
		echo '<p class="sh-report-summary"><strong>' . esc_html( sprintf( 'مطابق: %d — غير مطابق عن قصد: %d — غير مطابق يحتاج مراجعة: %d', $s['match'], $s['expected'], $s['mismatch'] ) ) . '</strong> — <a href="' . esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=sh_seo_transfer_csv' ), 'sh_seo_transfer_csv' ) ) . '">' . esc_html__( 'تنزيل التقرير (CSV)', 'seohouse-core' ) . '</a></p>';
		echo '<table class="widefat striped sh-seo-report" style="font-size:12px"><thead><tr><th>' . esc_html__( 'الرابط', 'seohouse-core' ) . '</th><th>' . esc_html__( 'النتيجة', 'seohouse-core' ) . '</th><th>&lt;title&gt; ' . esc_html__( 'الأساسي / الجديد', 'seohouse-core' ) . '</th><th>meta description ' . esc_html__( 'الأساسي / الجديد', 'seohouse-core' ) . '</th><th>robots ' . esc_html__( 'هنا', 'seohouse-core' ) . '</th></tr></thead><tbody>';
		$st = array( 'match' => 'مطابق', 'expected' => 'غير مطابق عن قصد', 'mismatch' => 'غير مطابق — يحتاج مراجعة' );
		foreach ( $rep['rows'] as $l ) {
			echo '<tr data-path="' . esc_attr( $l['path'] ) . '" data-state="' . esc_attr( $l['state'] ) . '"><td dir="ltr" style="text-align:left"><code>' . esc_html( $l['path'] ) . '</code></td><td>' . esc_html( $st[ $l['state'] ] ) . '</td>';
			foreach ( array( 'title', 'description' ) as $f ) {
				$x = $l['fields'][ $f ];
				$empty = '<em>' . esc_html__( '(فارغ)', 'seohouse-core' ) . '</em>';
				echo '<td dir="auto">' . ( 'match' === $x['state'] ? '✓ ' . ( '' === $x['new'] ? $empty : esc_html( $x['new'] ) ) : ( '' === $x['old'] ? $empty : esc_html( $x['old'] ) ) . '<br>≠ ' . ( '' === $x['new'] ? $empty : esc_html( $x['new'] ) ) . '<br><small>' . esc_html( $x['reason'] ) . '</small>' ) . '</td>';
			}
			echo '<td dir="ltr"><small>' . esc_html( $l['robots'] ) . '</small></td></tr>';
		}
		echo '</tbody></table>';
	}
	echo '</div>';
}
