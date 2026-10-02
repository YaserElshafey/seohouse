<?php
/**
 * Content setup from the design content pack (content-pack/).
 *
 * Guarantees:
 *  - Runs only on demand (WP-CLI or the admin screen), never on page load or activation.
 *  - Every created record carries a stable source key (_sh_source_key); re-running never
 *    duplicates pages, records, media or menus.
 *  - Existing records are skipped unless an update is requested for them. An update only
 *    overwrites values that are still exactly as imported; records edited in the admin are
 *    reported as "protected" (use --force to overwrite deliberately).
 *  - Relations (links, images, authors) are resolved after all records exist, to real IDs
 *    of this installation.
 *  - Dry run reports the plan without writing anything.
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

class SH_Importer {

	const META_KEY  = '_sh_source_key';
	const META_HASH = '_sh_import_hash';

	/** @var string */
	private $dir;
	/** @var bool */
	private $dry;
	/** @var string[] */
	private $only;
	/** @var string[] keys or groups to update (pages, team, cases, posts, menus, options, page:<key>, ...) */
	private $update;
	/** @var bool */
	private $force;

	/** @var array<string,int> route path => post ID */
	private $routes = array();
	/** @var array<string,int> asset path => attachment ID */
	private $media = array();
	/** @var array<string,int> source key => post ID */
	private $keys = array();
	/** @var int fake IDs for dry run */
	private $fake = -1;

	/** @var array<int,array{0:string,1:string,2:string}> */
	public $log = array();
	/** @var array<string,int> */
	public $counts = array( 'created' => 0, 'updated' => 0, 'skipped' => 0, 'protected' => 0, 'failed' => 0 );

	public function __construct( string $dir, array $opts = array() ) {
		$this->dir    = rtrim( $dir, '/\\' );
		$this->dry    = ! empty( $opts['dry_run'] );
		$this->only   = array_filter( (array) ( $opts['only'] ?? array() ) );
		$this->update = array_filter( (array) ( $opts['update'] ?? array() ) );
		$this->force  = ! empty( $opts['force'] );
	}

	/**
	 * Content pack to use: SH_CONTENT_DIR if defined; otherwise the pack bundled with Core
	 * (seohouse-core/content-pack), unless a newer pack was uploaded to uploads/seohouse-content.
	 */
	public static function default_dir(): string {
		if ( defined( 'SH_CONTENT_DIR' ) ) {
			return SH_CONTENT_DIR;
		}
		$up       = wp_upload_dir( null, false );
		$uploaded = trailingslashit( $up['basedir'] ) . 'seohouse-content';
		$bundled  = SH_CORE_DIR . 'content-pack';
		$ver      = static function ( $dir ) {
			$m = file_exists( $dir . '/manifest.json' ) ? json_decode( (string) file_get_contents( $dir . '/manifest.json' ), true ) : null; // phpcs:ignore WordPress.WP.AlternativeFunctions
			return is_array( $m ) ? (string) ( $m['version'] ?? '0' ) : '';
		};
		$vu = $ver( $uploaded );
		$vb = $ver( $bundled );
		if ( '' !== $vu && ( '' === $vb || version_compare( $vu, $vb, '>' ) ) ) {
			return $uploaded;
		}
		return '' !== $vb ? $bundled : $uploaded;
	}

	/** Manifest of a pack folder (or null). */
	public static function manifest_of( string $dir ): ?array {
		$f = rtrim( $dir, '/' ) . '/manifest.json';
		$m = file_exists( $f ) ? json_decode( (string) file_get_contents( $f ), true ) : null; // phpcs:ignore WordPress.WP.AlternativeFunctions
		return is_array( $m ) ? $m : null;
	}

	private function note( string $status, string $key, string $msg = '' ): void {
		$this->log[] = array( $status, $key, $msg );
		if ( isset( $this->counts[ $status ] ) ) {
			++$this->counts[ $status ];
		}
	}

	private function want( string $group ): bool {
		return ! $this->only || in_array( $group, $this->only, true );
	}

	private function wants_update( string $group, string $key ): bool {
		return in_array( 'all', $this->update, true ) || in_array( $group, $this->update, true ) || in_array( $key, $this->update, true );
	}

	private function json( string $rel ) {
		$f = $this->dir . '/' . $rel;
		if ( ! file_exists( $f ) ) {
			return null;
		}
		return json_decode( (string) file_get_contents( $f ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	}

	/* ================================================================ run */

	public function run(): bool {
		$manifest = $this->prepare();
		if ( ! $manifest ) {
			return false;
		}
		$this->phase_records();
		$this->phase_fill( array( 'pages', 'team', 'cases', 'posts' ) );
		$this->phase_finish( $manifest );
		return 0 === $this->counts['failed'];
	}

	/**
	 * Steps of a full import, each short enough for one web request (the admin screen runs them in
	 * order; each step is idempotent, so an interrupted run can simply be started again).
	 *
	 * @return array<int,array{0:string,1?:int,2?:int,label:string}>
	 */
	public function plan(): array {
		$steps  = array();
		$assets = $this->pack_assets();
		for ( $i = 0; $i < count( $assets ); $i += 4 ) {
			$steps[] = array( 'media', $i, 4, 'label' => sprintf( 'الصور %d–%d من %d', $i + 1, min( $i + 4, count( $assets ) ), count( $assets ) ) );
		}
		$steps[] = array( 'records', 'label' => 'إنشاء الصفحات والسجلات' );
		$pages   = $this->load_pages( (array) $this->json( 'manifest.json' ) );
		for ( $i = 0; $i < count( $pages ); $i += 6 ) {
			$steps[] = array( 'pages', $i, 6, 'label' => sprintf( 'محتوى الصفحات %d–%d من %d', $i + 1, min( $i + 6, count( $pages ) ), count( $pages ) ) );
		}
		$steps[] = array( 'records-fill', 'label' => 'الفريق ودراسات الحالة والمقالات' );
		$steps[] = array( 'finish', 'label' => 'القوائم والإعدادات والقراءة' );
		return $steps;
	}

	/** Runs one step of plan(). */
	public function run_step( array $step ): bool {
		$manifest = $this->prepare();
		if ( ! $manifest ) {
			return false;
		}
		switch ( $step[0] ) {
			case 'media':
				foreach ( array_slice( $this->pack_assets(), (int) $step[1], (int) $step[2], true ) as $rel => $alt ) {
					$this->asset_id( $rel, $alt );
				}
				break;
			case 'records':
				$this->phase_records();
				break;
			case 'pages':
				$this->index_routes();
				$this->phase_fill( array( 'pages' ), array( (int) $step[1], (int) $step[2] ) );
				break;
			case 'records-fill':
				$this->index_routes();
				$this->phase_fill( array( 'team', 'cases', 'posts' ) );
				break;
			case 'finish':
				$this->index_routes();
				if ( $this->want( 'pages' ) ) {
					$this->crumb_ancestors( $this->load_pages( $manifest ) );
				}
				$this->phase_finish( $manifest );
				break;
		}
		wp_defer_term_counting( false );
		return 0 === $this->counts['failed'];
	}

	/** Every image the pack uses (relative path → alt text), in a stable order. */
	public function pack_assets(): array {
		$out  = array();
		$walk = static function ( $v ) use ( &$walk, &$out ) {
			if ( is_array( $v ) ) {
				if ( isset( $v['__asset'] ) && is_string( $v['__asset'] ) && ! isset( $out[ $v['__asset'] ] ) ) {
					$out[ $v['__asset'] ] = (string) ( $v['alt'] ?? '' );
				}
				foreach ( $v as $x ) {
					$walk( $x );
				}
			}
		};
		foreach ( (array) glob( $this->dir . '/pages/*.json' ) as $f ) {
			$walk( json_decode( (string) file_get_contents( $f ), true ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		}
		foreach ( array( 'cases', 'posts', 'options', 'menus', 'extra' ) as $d ) {
			$walk( $this->json( "data/$d.json" ) );
		}
		foreach ( (array) $this->json( 'data/team.json' ) as $m ) {
			if ( ! empty( $m['photo'] ) && ! isset( $out[ $m['photo'] ] ) ) {
				$out[ $m['photo'] ] = $m['name'] . ' — ' . $m['role'];
			}
		}
		return $out;
	}

	/** Checks, source note, existing records. @return array|false the manifest */
	private function prepare() {
		if ( ! sh_core_acf_ready() ) {
			$this->note( 'failed', 'acf', 'Advanced Custom Fields غير مفعّلة. لم يُنفّذ شيء.' );
			return false;
		}
		$manifest = $this->json( 'manifest.json' );
		if ( ! is_array( $manifest ) ) {
			$this->note( 'failed', 'source', 'لم يُعثر على manifest.json في: ' . $this->dir );
			return false;
		}
		$this->note( 'info', 'source', 'حزمة المحتوى ' . ( $manifest['version'] ?? '' ) . ' — بصمة التصميم: ' . ( $manifest['designFingerprint'] ?? '—' ) . ( $this->dry ? ' — تشغيل تجريبي (بدون كتابة)' : '' ) );
		wp_defer_term_counting( true );
		if ( function_exists( 'wp_suspend_cache_invalidation' ) ) {
			wp_suspend_cache_invalidation( false );
		}
		$this->index_existing();
		$this->manifest = $manifest;
		return $manifest;
	}

	/** @var array */
	private $manifest = array();

	/** Phase 1: settings and every record, so routes and authors exist before relations are resolved. */
	private function phase_records(): void {
		if ( $this->want( 'settings' ) ) {
			$this->step_settings();
			if ( ! get_option( 'sh_content_last_import' ) ) {
				$this->step_defaults();
			}
		}
		if ( $this->want( 'pages' ) ) {
			foreach ( $this->load_pages( $this->manifest ) as $p ) {
				$this->ensure_page( $p );
			}
		}
		if ( $this->want( 'team' ) ) {
			foreach ( (array) $this->json( 'data/team.json' ) as $m ) {
				$this->ensure_post( 'team_member', $m['key'], array( 'post_title' => $m['name'], 'post_name' => $m['slug'], 'menu_order' => (int) $m['order'] ) );
			}
		}
		if ( $this->want( 'cases' ) ) {
			foreach ( (array) $this->json( 'data/cases.json' ) as $c ) {
				$this->ensure_post( 'case_study', $c['key'], array( 'post_title' => $c['title'], 'post_name' => $c['slug'], 'post_status' => $c['status'], 'menu_order' => (int) $c['order'] ) );
			}
		}
		if ( $this->want( 'posts' ) ) {
			foreach ( (array) $this->json( 'data/categories.json' ) as $cat ) {
				$this->ensure_category( $cat );
			}
			foreach ( (array) $this->json( 'data/posts.json' ) as $p ) {
				$this->ensure_post(
					'post',
					$p['key'],
					array_filter(
						array(
							'post_title'    => $p['title'],
							'post_name'     => $p['slug'],
							'post_status'   => $p['status'] ?? 'publish', // articles written for review are drafts
							'post_date'     => $p['date'] ?? '',          // no date for drafts: set when published
							'post_content'  => $p['content'],
							'post_excerpt'  => $p['excerpt'],
							'post_category' => array( $this->category_id( $p['category']['slug'] ) ),
						)
					)
				);
			}
		}
		$this->index_routes();
	}

	/**
	 * Phase 2: fields and relations.
	 *
	 * @param string[]   $groups pages, team, cases, posts
	 * @param array|null $slice  [offset, length] of pages
	 */
	private function phase_fill( array $groups, ?array $slice = null ): void {
		if ( in_array( 'pages', $groups, true ) && $this->want( 'pages' ) ) {
			$pages = $this->load_pages( $this->manifest );
			foreach ( $slice ? array_slice( $pages, $slice[0], $slice[1] ) : $pages as $p ) {
				$this->fill_page( $p );
			}
			if ( ! $slice ) {
				$this->crumb_ancestors( $pages );
			}
		}
		if ( in_array( 'team', $groups, true ) && $this->want( 'team' ) ) {
			foreach ( (array) $this->json( 'data/team.json' ) as $m ) {
				$this->fill_team( $m );
			}
		}
		if ( in_array( 'cases', $groups, true ) && $this->want( 'cases' ) ) {
			foreach ( (array) $this->json( 'data/cases.json' ) as $c ) {
				$this->fill_case( $c );
			}
		}
		if ( in_array( 'posts', $groups, true ) && $this->want( 'posts' ) ) {
			foreach ( (array) $this->json( 'data/posts.json' ) as $p ) {
				$this->fill_post( $p );
			}
		}
	}

	/** Phase 3: menus, settings, reading; marks the import as done. */
	private function phase_finish( array $manifest ): void {
		if ( $this->want( 'menus' ) ) {
			$this->step_menus();
		}
		if ( $this->want( 'options' ) ) {
			$this->step_options();
		}
		if ( $this->want( 'settings' ) ) {
			$this->step_reading();
		}
		wp_defer_term_counting( false );
		if ( ! $this->dry ) {
			flush_rewrite_rules( false );
			update_option( 'sh_content_last_import', array( 'time' => time(), 'version' => $manifest['version'] ?? '', 'fingerprint' => $manifest['designFingerprint'] ?? '', 'counts' => $this->counts ) );
		}
	}

	/* ================================================================ lookup */

	private function index_existing(): void {
		global $wpdb;
		$rows = $wpdb->get_results( $wpdb->prepare( "SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = %s", self::META_KEY ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		foreach ( $rows as $r ) {
			if ( get_post_status( (int) $r->post_id ) ) {
				if ( str_starts_with( $r->meta_value, 'asset:' ) ) {
					$this->media[ substr( $r->meta_value, 6 ) ] = (int) $r->post_id;
				} else {
					$this->keys[ $r->meta_value ] = (int) $r->post_id;
				}
			}
		}
	}

	private function index_routes(): void {
		foreach ( $this->keys as $key => $id ) {
			if ( $id <= 0 ) {
				continue;
			}
			if ( 'publish' !== get_post_status( $id ) ) {
				// drafts: remember the path they will have once published, so menus and link fields
				// point to the record itself (hidden from visitors until it is published)
				$p = get_post( $id );
				if ( $p ) {
					$p              = clone $p;
					$p->post_status = 'publish';
					$link           = get_permalink( $p );
					if ( $link ) {
						$this->draft_routes[ $this->path_of( $link ) ] = $id;
					}
				}
				continue;
			}
			$link = get_permalink( $id );
			if ( $link ) {
				$this->routes[ $this->path_of( $link ) ] = $id;
			}
		}
		$front = $this->keys['page:home'] ?? (int) get_option( 'page_on_front' );
		if ( $front ) {
			$this->routes['/'] = $front;
		}
	}

	private function path_of( string $url ): string {
		$p    = rawurldecode( (string) wp_parse_url( $url, PHP_URL_PATH ) );
		$home = rawurldecode( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ) );
		if ( $home && '/' !== $home && str_starts_with( $p, $home ) ) {
			$p = '/' . ltrim( substr( $p, strlen( $home ) ), '/' );
		}
		return trailingslashit( $p ? $p : '/' );
	}

	private function route_id( string $route ): int {
		$r = trailingslashit( rawurldecode( strtok( $route, '#?' ) ) );
		return $this->routes[ $r ] ?? ( $this->draft_routes[ $r ] ?? 0 );
	}

	/** @var array<string,int> path → ID of records that are not published yet */
	private $draft_routes = array();

	/* ================================================================ settings */

	private function step_settings(): void {
		$struct = get_option( 'permalink_structure' );
		if ( '/blog/%postname%/' === $struct && 'blog/category' === get_option( 'category_base' ) ) {
			$this->note( 'skipped', 'permalinks', 'بنية الروابط مضبوطة مسبقًا' );
			return;
		}
		// WordPress install defaults are replaced on the first setup; anything else was chosen by someone.
		$install_default = in_array( $struct, array( '', '/%year%/%monthnum%/%day%/%postname%/', '/index.php/%year%/%monthnum%/%day%/%postname%/' ), true ) && ! get_option( 'sh_content_last_import' );
		if ( $struct && '/blog/%postname%/' !== $struct && ! $install_default && ! $this->force ) {
			$this->note( 'protected', 'permalinks', 'بنية روابط مختلفة مضبوطة يدويًا (' . $struct . '). استخدم --force لتطبيق /blog/%postname%/.' );
			return;
		}
		if ( ! $this->dry ) {
			sh_core_apply_permalinks();
		}
		$this->note( 'updated', 'permalinks', '/blog/%postname%/ و /blog/category/{slug}/' );
	}

	/** WordPress install samples ("Hello world!", "Sample Page", sample comment) are removed only if untouched. */
	private function step_defaults(): void {
		foreach ( array( 1 => 'post', 2 => 'page' ) as $id => $type ) {
			$p = get_post( $id );
			if ( ! $p || $p->post_type !== $type || get_post_meta( $id, self::META_KEY, true ) || 'trash' === $p->post_status ) {
				continue;
			}
			if ( $p->post_modified_gmt !== $p->post_date_gmt ) {
				$this->note( 'protected', 'default:' . $p->post_name, 'محتوى افتراضي عُدّل؛ لم يُحذف' );
				continue;
			}
			if ( ! $this->dry ) {
				wp_trash_post( $id );
			}
			$this->note( 'updated', 'default:' . $p->post_name, 'نُقل محتوى ووردبريس الافتراضي إلى سلة المهملات' );
		}
	}

	private function step_reading(): void {
		$front = $this->keys['page:home'] ?? 0;
		$blog  = $this->keys['page:blog'] ?? 0;
		if ( ! $front || ! $blog ) {
			return;
		}
		if ( (int) get_option( 'page_on_front' ) === $front && (int) get_option( 'page_for_posts' ) === $blog ) {
			return;
		}
		if ( ! $this->dry ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $front );
			update_option( 'page_for_posts', $blog );
		}
		$this->note( 'updated', 'reading', 'الرئيسية وصفحة المقالات' );
	}

	/* ================================================================ media */

	private function asset_id( ?string $rel, string $alt = '' ): int {
		if ( ! $rel ) {
			return 0;
		}
		if ( isset( $this->media[ $rel ] ) ) {
			return $this->media[ $rel ];
		}
		$file = $this->dir . '/assets/' . $rel;
		if ( ! file_exists( $file ) ) {
			$this->note( 'failed', 'asset:' . $rel, 'الملف غير موجود في حزمة المحتوى' );
			return 0;
		}
		if ( $this->dry ) {
			$this->note( 'created', 'asset:' . $rel, 'وسائط (تجريبي)' );
			$this->media[ $rel ] = $this->fake--;
			return $this->media[ $rel ];
		}
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$tmp = wp_tempnam( basename( $file ) );
		copy( $file, $tmp );
		$name = sanitize_file_name( preg_replace( '/\.png\.png$/i', '.png', basename( $rel ) ) );
		$id   = media_handle_sideload( array( 'name' => $name, 'tmp_name' => $tmp ), 0, $alt ? $alt : null );
		if ( is_wp_error( $id ) ) {
			@unlink( $tmp ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
			$this->note( 'failed', 'asset:' . $rel, $id->get_error_message() );
			return 0;
		}
		update_post_meta( $id, self::META_KEY, 'asset:' . $rel );
		if ( $alt ) {
			update_post_meta( $id, '_wp_attachment_image_alt', sanitize_text_field( $alt ) );
		}
		$this->media[ $rel ] = (int) $id;
		$this->note( 'created', 'asset:' . $rel, 'وسائط' );
		return (int) $id;
	}

	/* ================================================================ records */

	private function load_pages( array $manifest ): array {
		$pages = array();
		foreach ( (array) $manifest['pages'] as $p ) {
			$seed = $this->json( 'pages/' . $p['key'] . '.json' );
			if ( ! $seed ) {
				continue;
			}
			if ( 'page' === $seed['kind'] || 'blog' === $seed['key'] ) {
				$pages[] = $seed;
			}
		}
		usort( $pages, static fn( $a, $b ) => substr_count( $a['route'], '/' ) <=> substr_count( $b['route'], '/' ) );
		return $pages;
	}

	private function ensure_page( array $seed ): void {
		$key   = 'page:' . $seed['key'];
		$route = $seed['route'];
		$segs  = array_values( array_filter( explode( '/', trim( $route, '/' ) ) ) );
		$slug  = $segs ? rawurldecode( end( $segs ) ) : 'home';
		$parent_route = count( $segs ) > 1 ? '/' . implode( '/', array_slice( $segs, 0, -1 ) ) . '/' : '';
		$parent       = 0;
		if ( $parent_route ) {
			foreach ( $this->keys as $k => $id ) {
				if ( str_starts_with( $k, 'page:' ) && ( $this->page_routes[ $k ] ?? '' ) === $parent_route ) {
					$parent = $id;
				}
			}
			if ( ! $parent ) {
				$pp     = get_page_by_path( trim( $parent_route, '/' ) );
				$parent = $pp ? $pp->ID : 0;
			}
			if ( ! $parent ) {
				$this->note( 'failed', $key, 'الصفحة الأب غير موجودة: ' . $parent_route );
				return;
			}
		}
		$this->page_routes[ $key ] = $route;
		$args = array(
			'post_status' => $seed['status'] ?? 'publish',
			'post_title'  => $seed['title'],
			'post_name'   => $slug,
			'post_parent' => $parent,
			'menu_order'  => 0,
		);
		if ( ! empty( $seed['template'] ) ) {
			$args['page_template'] = $seed['template'];
		}
		// adopt a page that already owns the approved path (clean installs have none)
		if ( ! isset( $this->keys[ $key ] ) ) {
			$existing = '/' === $route ? null : get_page_by_path( trim( rawurldecode( $route ), '/' ) );
			// WordPress's own untouched draft privacy page is adopted instead of blocking the path.
			if ( $existing && (int) get_option( 'wp_page_for_privacy_policy' ) === $existing->ID && 'draft' === $existing->post_status && ! get_post_meta( $existing->ID, self::META_KEY, true ) ) {
				if ( ! $this->dry ) {
					update_post_meta( $existing->ID, self::META_KEY, $key );
					update_post_meta( $existing->ID, '_wp_page_template', $args['page_template'] ?? '' );
					wp_update_post( array( 'ID' => $existing->ID, 'post_title' => $args['post_title'], 'post_content' => '' ) );
				}
				$this->keys[ $key ] = $this->dry ? $this->fake-- : $existing->ID;
				$this->note( 'updated', $key, 'اعتُمدت صفحة الخصوصية الافتراضية في ووردبريس (تبقى مسودة)' );
				return;
			}
			if ( $existing ) {
				$this->note( 'protected', $key, 'يوجد محتوى على المسار ' . $route . ' لم تنشئه الأداة؛ لم يُستبدل. اربطه يدويًا أو احذفه ثم أعد التشغيل.' );
				return;
			}
		}
		$this->ensure_post( 'page', $key, $args );
	}

	/** @var array<string,string> */
	private $page_routes = array();

	private function ensure_post( string $type, string $key, array $args ): int {
		if ( isset( $this->keys[ $key ] ) ) {
			$this->note( 'skipped', $key, 'موجود' );
			return $this->keys[ $key ];
		}
		if ( $this->dry ) {
			$this->keys[ $key ] = $this->fake--;
			$this->note( 'created', $key, $type . ' (تجريبي)' );
			return $this->keys[ $key ];
		}
		$template = $args['page_template'] ?? '';
		unset( $args['page_template'] );
		$id = wp_insert_post(
			array_merge(
				array(
					'post_type'   => $type,
					'post_status' => 'publish',
					'meta_input'  => array( self::META_KEY => $key ),
				),
				$args
			),
			true
		);
		if ( is_wp_error( $id ) ) {
			$this->note( 'failed', $key, $id->get_error_message() );
			return 0;
		}
		if ( $template ) {
			update_post_meta( $id, '_wp_page_template', $template );
		}
		$this->keys[ $key ] = (int) $id;
		$this->note( 'created', $key, $type );
		return (int) $id;
	}

	private function ensure_category( array $cat ): void {
		$term = get_term_by( 'slug', $cat['slug'], 'category' );
		if ( $term ) {
			$this->note( 'skipped', $cat['key'], 'موجود' );
			return;
		}
		if ( $this->dry ) {
			$this->note( 'created', $cat['key'], 'تصنيف (تجريبي)' );
			return;
		}
		$t = wp_insert_term( $cat['name'], 'category', array( 'slug' => $cat['slug'], 'description' => $cat['description'] ?? '' ) );
		$this->note( is_wp_error( $t ) ? 'failed' : 'created', $cat['key'], is_wp_error( $t ) ? $t->get_error_message() : 'تصنيف' );
	}

	private function category_id( string $slug ): int {
		$t = get_term_by( 'slug', $slug, 'category' );
		return $t ? (int) $t->term_id : (int) get_option( 'default_category' );
	}

	/* ================================================================ values */

	/** Replace {__route}, {__asset} placeholders with IDs of this installation; drop internal "_" keys. */
	private function resolve( $v ) {
		if ( ! is_array( $v ) ) {
			return $v;
		}
		if ( array_key_exists( '__route', $v ) ) {
			$id = $this->route_id( (string) $v['__route'] );
			if ( ! $id ) {
				$this->note( 'info', 'route', 'رابط بلا صفحة مقابلة بعد: ' . $v['__route'] . ' (سيُحفظ فارغًا)' );
			}
			return $id ? $id : '';
		}
		if ( array_key_exists( '__asset', $v ) ) {
			return $this->asset_id( $v['__asset'], (string) ( $v['alt'] ?? '' ) );
		}
		$out = array();
		foreach ( $v as $k => $x ) {
			if ( is_string( $k ) && '_' === $k[0] && 'acf_fc_layout' !== $k ) {
				continue;
			}
			$out[ $k ] = $this->resolve( $x );
		}
		return $out;
	}

	/**
	 * Field name → key for the top-level fields of the Core groups (options, case study, team,
	 * article, SEO, menu item, blog page). Values are always written by key so ACF never has to
	 * guess a field from a name that also exists elsewhere.
	 */
	private function field_key( string $name ): string {
		static $map = null;
		if ( null === $map ) {
			$map = array();
			foreach ( (array) glob( SH_CORE_DIR . 'acf-json/group_sh_*.json' ) as $file ) {
				if ( str_contains( (string) $file, 'group_sh_page_' ) ) {
					continue;
				}
				$g = json_decode( (string) file_get_contents( $file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions
				foreach ( (array) ( $g['fields'] ?? array() ) as $f ) {
					if ( ! empty( $f['name'] ) ) {
						$map[ $f['name'] ] = $f['key'];
					}
				}
			}
		}
		return $map[ $name ] ?? $name;
	}

	/** Hash of what is stored now for a set of fields (raw values). */
	private function state_hash( int $id, array $names ): string {
		$vals = array();
		foreach ( $names as $n ) {
			$vals[ $n ] = get_field( $n, $id, false );
		}
		return md5( wp_json_encode( $vals ) );
	}

	/**
	 * Decide whether fields of a record may be written.
	 *
	 * @return string 'write' | 'skip' | 'protected'
	 */
	private function write_mode( int $id, string $group, string $key, array $names ): string {
		$stored = get_post_meta( $id, self::META_HASH, true );
		if ( ! $stored ) {
			return 'write'; // first fill
		}
		if ( ! $this->wants_update( $group, $key ) ) {
			return 'skip';
		}
		if ( $this->force || $stored === $this->state_hash( $id, $names ) ) {
			return 'write';
		}
		return 'protected';
	}

	/**
	 * Write a map of field name => value on a post, respecting write_mode.
	 *
	 * @param array<string,mixed> $fields name => value (field keys resolved by ACF from the post's groups)
	 */
	private function write_fields( int $id, string $group, string $key, array $fields, array $keys_by_name = array() ): void {
		if ( $id <= 0 ) {
			if ( $this->dry ) {
				$this->note( 'info', $key, 'حقول: ' . count( $fields ) . ' (تجريبي)' );
			}
			return;
		}
		$mode = $this->write_mode( $id, $group, $key, array_keys( $fields ) );
		if ( 'skip' === $mode ) {
			return;
		}
		if ( 'protected' === $mode ) {
			$this->note( 'protected', $key, 'عُدّل من لوحة التحكم بعد التجهيز؛ لم يُكتب فوقه. (--force للكتابة)' );
			return;
		}
		$resolved = $this->resolve( $fields );
		if ( $this->dry ) {
			$this->note( 'updated', $key, 'حقول: ' . implode( '، ', array_keys( $resolved ) ) . ' (تجريبي)' );
			return;
		}
		$before = (string) get_post_meta( $id, self::META_HASH, true );
		$first  = '' === $before;
		foreach ( $resolved as $name => $value ) {
			update_field( $keys_by_name[ $name ] ?? $this->field_key( $name ), $value, $id );
		}
		$after = $this->state_hash( $id, array_keys( $fields ) );
		update_post_meta( $id, self::META_HASH, $after );
		if ( function_exists( 'sh_search_index' ) ) {
			sh_search_index( $id );
		}
		if ( ! $first ) {
			$this->note( $after === $before ? 'skipped' : 'updated', $key, $after === $before ? 'بلا تغيير' : 'حقول' );
		}
	}

	private function fill_page( array $seed ): void {
		$key = 'page:' . $seed['key'];
		$id  = $this->keys[ $key ] ?? 0;
		if ( ! $id ) {
			return;
		}
		$fields = array();
		$keys   = array();
		if ( 'page' === $seed['kind'] ) {
			$group = json_decode( (string) file_get_contents( SH_CORE_DIR . 'acf-json/group_sh_page_' . str_replace( '-', '_', $seed['key'] ) . '.json' ), true ); // phpcs:ignore
			if ( ! $group ) {
				$this->note( 'failed', $key, 'مجموعة الحقول غير موجودة' );
				return;
			}
			// one ACF Group field per design section ("s_<layout>"), written by field key
			$section_keys = array();
			foreach ( $group['fields'] as $gf ) {
				if ( 'group' === ( $gf['type'] ?? '' ) && str_starts_with( (string) $gf['name'], 's_' ) ) {
					$section_keys[ $gf['name'] ] = $gf['key'];
				}
			}
			foreach ( (array) $seed['sections'] as $section ) {
				$name = 's_' . ( $section['acf_fc_layout'] ?? '' );
				if ( ! isset( $section_keys[ $name ] ) ) {
					$this->note( 'failed', $key, 'قسم بلا حقل مقابل: ' . $name );
					continue;
				}
				unset( $section['acf_fc_layout'] );
				$fields[ $name ] = $section;
				$keys[ $name ]   = $section_keys[ $name ];
			}
		}
		$seo_title = $seed['seo']['title'] ?? '';
		$fields['sh_seo_title']       = $seo_title;
		$fields['sh_seo_description'] = $seed['seo']['description'] ?? '';
		$fields['sh_seo_noindex']     = str_contains( (string) ( $seed['seo']['robots'] ?? '' ), 'noindex' ) ? 1 : 0;
		$fields['sh_schema_type']     = $seed['seo']['schema_type'] ?? 'auto';
		$fields['sh_schema_service']  = $seed['seo']['schema_service'] ?? '';
		$crumbs                       = (array) ( $seed['crumbs'] ?? array() );
		$last                         = $crumbs ? end( $crumbs ) : null;
		$fields['sh_crumb']           = $last && $last['label'] !== $seed['title'] ? $last['label'] : '';
		static $extra = null;
		if ( null === $extra ) {
			$extra = (array) $this->json( 'data/extra.json' );
		}
		$fields = array_merge( $fields, (array) ( $extra[ $key ] ?? array() ) );
		$this->write_fields( $id, 'pages', $key, $fields, $keys );
	}

	/** A page may carry a different label when it appears as a parent (from the children's breadcrumbs). */
	private function crumb_ancestors( array $pages ): void {
		$labels = array();
		foreach ( $pages as $p ) {
			foreach ( array_slice( (array) ( $p['crumbs'] ?? array() ), 1, -1 ) as $c ) {
				if ( ! empty( $c['href'] ) ) {
					$labels[ trailingslashit( rawurldecode( $c['href'] ) ) ] = $c['label'];
				}
			}
		}
		foreach ( $labels as $route => $label ) {
			$id = $this->route_id( $route );
			if ( $id > 0 && ! $this->dry && ! get_field( 'sh_crumb_ancestor', $id ) && sh_crumb_label( $id ) !== $label ) {
				update_field( $this->field_key( 'sh_crumb_ancestor' ), $label, $id );
			}
		}
	}

	private function fill_team( array $m ): void {
		$id = $this->keys[ $m['key'] ] ?? 0;
		if ( $id > 0 && ! $this->dry && ! has_post_thumbnail( $id ) && ! empty( $m['photo'] ) ) {
			$img = $this->asset_id( $m['photo'], $m['name'] . ' — ' . $m['role'] );
			if ( $img > 0 ) {
				set_post_thumbnail( $id, $img );
			}
		}
		$this->write_fields(
			$id,
			'team',
			$m['key'],
			array(
				'role'        => $m['role'],
				'experience'  => $m['experience'],
				'specialties' => $m['specialties'],
				'linkedin'    => $m['linkedin'],
				'home_photo'  => 1,
			)
		);
	}

	private function fill_case( array $c ): void {
		$id = $this->keys[ $c['key'] ] ?? 0;
		$f  = $c['fields'];
		$f['sh_seo_noindex'] = ! empty( $c['noindex'] ) ? 1 : 0;
		$this->write_fields( $id, 'cases', $c['key'], $f );
	}

	private function fill_post( array $p ): void {
		$id = $this->keys[ $p['key'] ] ?? 0;
		if ( $id > 0 && ! $this->dry && ! has_post_thumbnail( $id ) && ! empty( $p['featured'] ) ) {
			$img = $this->asset_id( $p['featured']['__asset'], $p['featured']['alt'] ?? '' );
			if ( $img > 0 ) {
				set_post_thumbnail( $id, $img );
			}
		}
		$f                  = $p['fields'];
		$f['author_member'] = $this->keys[ $p['author_member'] ] ?? '';
		$this->write_fields( $id, 'posts', $p['key'], $f );
	}

	/* ================================================================ menus */

	private function step_menus(): void {
		$menus = (array) $this->json( 'data/menus.json' );
		$locs  = get_nav_menu_locations();
		foreach ( $menus as $loc => $menu ) {
			$key  = 'menu:' . $loc;
			$term = null;
			foreach ( wp_get_nav_menus() as $m ) {
				if ( get_term_meta( $m->term_id, self::META_KEY, true ) === $key ) {
					$term = $m;
				}
			}
			if ( $term && ! $this->wants_update( 'menus', $key ) ) {
				$this->note( 'skipped', $key, 'موجودة' );
				if ( empty( $locs[ $loc ] ) && ! $this->dry ) {
					$locs[ $loc ] = $term->term_id;
				}
				continue;
			}
			// a menu edited in Appearance › Menus after the import is never rebuilt (unless --force)
			if ( $term && ! $this->force && get_term_meta( $term->term_id, self::META_HASH, true ) !== $this->menu_hash( $term->term_id ) ) {
				$this->note( 'protected', $key, 'عُدّلت القائمة من لوحة التحكم؛ لم يُكتب فوقها. (--force للكتابة)' );
				continue;
			}
			if ( $this->dry ) {
				$this->note( $term ? 'updated' : 'created', $key, 'قائمة (تجريبي)' );
				continue;
			}
			$before = $term ? $this->menu_hash( $term->term_id ) : '';
			if ( $term ) {
				foreach ( (array) wp_get_nav_menu_items( $term->term_id ) as $it ) {
					wp_delete_post( $it->ID, true );
				}
				$menu_id = $term->term_id;
			} else {
				$menu_id = wp_create_nav_menu( $menu['name'] );
				if ( is_wp_error( $menu_id ) ) {
					$this->note( 'failed', $key, $menu_id->get_error_message() );
					continue;
				}
				update_term_meta( $menu_id, self::META_KEY, $key );
			}
			$this->add_menu_items( $menu_id, $menu['items'], 0 );
			$locs[ $loc ] = $menu_id;
			$after        = $this->menu_hash( (int) $menu_id );
			update_term_meta( $menu_id, self::META_HASH, $after );
			if ( $term && $after === $before ) {
				$this->note( 'skipped', $key, 'بلا تغيير' );
			} else {
				$this->note( $term ? 'updated' : 'created', $key, 'قائمة' );
			}
		}
		if ( ! $this->dry ) {
			set_theme_mod( 'nav_menu_locations', $locs );
		}
	}

	/** Fingerprint of a menu as editors see it: order, labels, targets, descriptions, Core item fields. */
	private function menu_hash( int $menu_id ): string {
		// read straight from the database: right after building a menu the menu-items cache is stale
		clean_term_cache( $menu_id, 'nav_menu' );
		$posts = get_posts(
			array(
				'post_type'        => 'nav_menu_item',
				'post_status'      => 'any',
				'posts_per_page'   => -1,
				'orderby'          => 'menu_order',
				'order'            => 'ASC',
				'cache_results'    => false,
				'suppress_filters' => true,
				'tax_query'        => array( array( 'taxonomy' => 'nav_menu', 'field' => 'term_id', 'terms' => $menu_id ) ), // phpcs:ignore WordPress.DB.SlowDBQuery
			)
		);
		$list  = array_map( 'wp_setup_nav_menu_item', $posts );
		$items = array();
		$pos   = array();
		foreach ( $list as $it ) {
			$pos[ $it->ID ] = count( $pos );
		}
		foreach ( $list as $it ) {
			$items[] = array(
				$it->title,
				'custom' === $it->type ? $it->url : $it->object . ':' . $it->object_id,
				$pos[ (int) $it->menu_item_parent ] ?? -1,
				$it->description,
				get_post_meta( $it->ID, 'sh_menu_icon', true ),
				get_post_meta( $it->ID, 'sh_menu_all_label', true ),
				get_post_meta( $it->ID, 'sh_menu_layout', true ),
			);
		}
		return md5( wp_json_encode( $items ) );
	}

	private function add_menu_items( int $menu_id, array $items, int $parent ): void {
		foreach ( $items as $pos => $it ) {
			$args = array(
				'menu-item-title'       => $it['label'],
				'menu-item-status'      => 'publish',
				'menu-item-parent-id'   => $parent,
				'menu-item-position'    => $pos + 1,
				'menu-item-description' => $it['desc'] ?? '',
			);
			$pid = ! empty( $it['route'] ) ? $this->route_id( $it['route'] ) : 0;
			if ( $pid > 0 ) {
				$args['menu-item-object-id'] = $pid;
				$args['menu-item-object']    = get_post_type( $pid );
				$args['menu-item-type']      = 'post_type';
			} else {
				$args['menu-item-type'] = 'custom';
				$args['menu-item-url']  = ! empty( $it['route'] ) ? home_url( $it['route'] ) : '#';
			}
			$item_id = wp_update_nav_menu_item( $menu_id, 0, $args );
			if ( is_wp_error( $item_id ) ) {
				$this->note( 'failed', 'menu-item', $item_id->get_error_message() );
				continue;
			}
			foreach ( array( 'icon' => 'sh_menu_icon', 'all_label' => 'sh_menu_all_label', 'layout' => 'sh_menu_layout' ) as $src => $field ) {
				if ( ! empty( $it[ $src ] ) ) {
					update_field( $this->field_key( $field ), $it[ $src ], $item_id );
				}
			}
			if ( ! empty( $it['children'] ) ) {
				$this->add_menu_items( $menu_id, $it['children'], (int) $item_id );
			}
		}
	}

	/* ================================================================ options */

	private function step_options(): void {
		$opts = (array) $this->json( 'data/options.json' );
		$hash = get_option( 'sh_options_import_hash' );
		$now  = md5( wp_json_encode( array_map( static fn( $n ) => get_field( $n, 'option', false ), array_keys( $opts ) ) ) );
		if ( $hash && ! $this->wants_update( 'options', 'options' ) ) {
			$this->note( 'skipped', 'options', 'الإعدادات مجهّزة مسبقًا' );
			return;
		}
		if ( $hash && $hash !== $now && ! $this->force ) {
			$this->note( 'protected', 'options', 'عُدّلت الإعدادات من لوحة التحكم؛ لم يُكتب فوقها. (--force للكتابة)' );
			return;
		}
		$resolved = $this->resolve( $opts );
		if ( $this->dry ) {
			$this->note( $hash ? 'updated' : 'created', 'options', implode( '، ', array_keys( $opts ) ) . ' (تجريبي)' );
			return;
		}
		foreach ( $resolved as $name => $value ) {
			update_field( $this->field_key( $name ), $value, 'option' );
		}
		update_option( 'sh_options_import_hash', md5( wp_json_encode( array_map( static fn( $n ) => get_field( $n, 'option', false ), array_keys( $opts ) ) ) ), false );
		$this->note( $hash ? 'updated' : 'created', 'options', 'إعدادات سيو هاوس' );
	}

	/* ================================================================ verification */

	/**
	 * Compare the design values in the pack with what WordPress now returns
	 * (sections, headings, paragraphs, links, images) — words are never rewritten.
	 *
	 * @return array<int,array{0:string,1:int,2:int,3:array}> [key, fields checked, mismatches, samples]
	 */
	public function verify(): array {
		$this->index_existing();
		$this->index_routes();
		$manifest = (array) $this->json( 'manifest.json' );
		$out      = array();
		foreach ( $this->load_pages( $manifest ) as $seed ) {
			$id = $this->keys[ 'page:' . $seed['key'] ] ?? 0;
			if ( ! $id || 'page' !== $seed['kind'] ) {
				continue;
			}
			$stored = sh_core_sections( (int) $id );
			$want   = array();
			$have   = array();
			$this->flatten( $this->expected( $seed['sections'] ), '', $want );
			$this->flatten( is_array( $stored ) ? $stored : array(), '', $have );
			$bad = array();
			foreach ( $want as $path => $v ) {
				$h = $have[ $path ] ?? null;
				if ( (string) $h !== (string) $v ) {
					$bad[ $path ] = array( $v, $h );
				}
			}
			$out[] = array( $seed['key'], count( $want ), count( $bad ), array_slice( $bad, 0, 5, true ) );
		}
		return $out;
	}

	private function expected( $v ) {
		if ( ! is_array( $v ) ) {
			return $v;
		}
		if ( array_key_exists( '__route', $v ) ) {
			$id = $this->route_id( (string) $v['__route'] );
			return $id ? get_permalink( $id ) : '';
		}
		if ( array_key_exists( '__asset', $v ) ) {
			return $this->media[ $v['__asset'] ] ?? 0;
		}
		$o = array();
		foreach ( $v as $k => $x ) {
			if ( is_string( $k ) && '_' === $k[0] && 'acf_fc_layout' !== $k ) {
				continue;
			}
			$o[ $k ] = $this->expected( $x );
		}
		return $o;
	}

	private function flatten( $v, string $prefix, array &$out ): void {
		if ( is_array( $v ) ) {
			foreach ( $v as $k => $x ) {
				$this->flatten( $x, '' === $prefix ? (string) $k : "$prefix.$k", $out );
			}
			return;
		}
		if ( is_bool( $v ) ) {
			$v = $v ? '1' : '0';
		}
		$out[ $prefix ] = (string) $v;
	}
}
