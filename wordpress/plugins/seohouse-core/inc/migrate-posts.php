<?php
/**
 * Published articles of the main WordPress site → this site (the /new/ review copy).
 *
 * Source: the main site's own database, read-only. Its connection details come from its
 * wp-config.php — the folder above this install (/new/ lives inside the main site) — or from
 * SH_SOURCE_* constants in this site's wp-config.php. The file is parsed, never executed, and the
 * session is opened READ ONLY: nothing is ever written to the main site. Image files are copied
 * from the main site's uploads folder to the same relative path here.
 *
 * What is copied for every published post (all of them, from the posts table — not from a listing
 * page): title, content with its formatting, excerpt, slug, dates (published and modified), author
 * (user matched by login, then email, else created as author), categories and tags (matched by
 * slug), featured image and images inside the content (files, all sizes, metadata, alt text,
 * caption), and Rank Math data (rank_math_* fields; image and term references remapped).
 *
 * Matching: a post already migrated (meta _sh_source_post) → same record; otherwise a post with the
 * same slug (e.g. the review drafts written for these URLs) → its title, content, excerpt, status,
 * date and article fields are kept in a backup (meta _sh_pre_migration, a revision, and a JSON file
 * under uploads/seohouse-backups) before it is replaced. Running again creates nothing twice: an
 * unchanged source post is skipped, and a post edited here after the migration is protected.
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

class SH_Post_Migration {

	/** @var array{config:string,host:string,name:string,user:string,pass:string,prefix:string,root:string}|null */
	public $source;
	/** @var mysqli|null */
	private $db;
	/** @var string */
	public $error = '';
	/** @var bool */
	private $dry;
	/** @var bool */
	private $force;
	/** @var array<int,array{0:string,1:string,2:string}> status, item, message */
	public $log = array();
	/** @var array<string,int> */
	public $counts = array( 'created' => 0, 'replaced' => 0, 'updated' => 0, 'skipped' => 0, 'protected' => 0, 'failed' => 0 );

	private $src_home     = '';
	private $src_uploads  = '';
	private $src_up_dir   = '';
	private $map_att      = array(); // source attachment ID => target ID
	private $map_term     = array(); // source term_taxonomy… term_id => target term_id
	private $map_user     = array();
	private $backups      = array();

	public function __construct( array $opts = array() ) {
		$this->dry   = ! empty( $opts['dry_run'] );
		$this->force = ! empty( $opts['force'] );
		$this->source = self::find_source( $opts['config'] ?? '' );
	}

	/* ================================================================ source */

	/**
	 * Main site's connection details. Order: SH_SOURCE_DB_* constants, an explicit wp-config path
	 * (SH_SOURCE_WP_CONFIG or the CLI option), then the wp-config.php of the folder above this site
	 * (or the one above that, which WordPress also allows).
	 */
	public static function find_source( string $config = '' ): ?array {
		if ( defined( 'SH_SOURCE_DB_NAME' ) ) {
			return array(
				'config' => 'SH_SOURCE_DB_*',
				'host'   => defined( 'SH_SOURCE_DB_HOST' ) ? SH_SOURCE_DB_HOST : DB_HOST,
				'name'   => SH_SOURCE_DB_NAME,
				'user'   => defined( 'SH_SOURCE_DB_USER' ) ? SH_SOURCE_DB_USER : DB_USER,
				'pass'   => defined( 'SH_SOURCE_DB_PASSWORD' ) ? SH_SOURCE_DB_PASSWORD : DB_PASSWORD,
				'prefix' => defined( 'SH_SOURCE_TABLE_PREFIX' ) ? SH_SOURCE_TABLE_PREFIX : 'wp_',
				'root'   => defined( 'SH_SOURCE_ROOT' ) ? untrailingslashit( SH_SOURCE_ROOT ) : dirname( untrailingslashit( ABSPATH ) ),
			);
		}
		if ( '' === $config && defined( 'SH_SOURCE_WP_CONFIG' ) ) {
			$config = SH_SOURCE_WP_CONFIG;
		}
		$candidates = array();
		if ( '' !== $config ) {
			$candidates[] = $config;
		} else {
			$parent       = dirname( untrailingslashit( ABSPATH ) );
			$candidates[] = $parent . '/wp-config.php';
			if ( file_exists( $parent . '/wp-settings.php' ) ) {
				$candidates[] = dirname( $parent ) . '/wp-config.php';
			}
		}
		foreach ( $candidates as $file ) {
			if ( ! is_readable( $file ) ) {
				continue;
			}
			$php = (string) file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions
			$get = static function ( string $name ) use ( $php ): ?string {
				return preg_match( '/define\(\s*[\'"]' . $name . '[\'"]\s*,\s*([\'"])(.*?)\1\s*\)/s', $php, $m ) ? stripcslashes( $m[2] ) : null;
			};
			$name = $get( 'DB_NAME' );
			if ( null === $name ) {
				continue;
			}
			$prefix = preg_match( '/\$table_prefix\s*=\s*([\'"])(.*?)\1/', $php, $m ) ? $m[2] : 'wp_';
			$root   = file_exists( dirname( $file ) . '/wp-settings.php' ) ? dirname( $file ) : dirname( untrailingslashit( ABSPATH ) );
			return array(
				'config' => $file,
				'host'   => (string) ( $get( 'DB_HOST' ) ?? 'localhost' ),
				'name'   => $name,
				'user'   => (string) ( $get( 'DB_USER' ) ?? '' ),
				'pass'   => (string) ( $get( 'DB_PASSWORD' ) ?? '' ),
				'prefix' => $prefix,
				'root'   => $root,
			);
		}
		return null;
	}

	/** Opens the read-only connection and reads the main site's address and uploads location. */
	public function connect(): bool {
		global $wpdb;
		if ( ! $this->source ) {
			$this->error = 'لم يُعثر على إعدادات الموقع الأساسي (wp-config.php في المجلد الأعلى). أضف SH_SOURCE_WP_CONFIG أو SH_SOURCE_DB_* إلى wp-config.php.';
			return false;
		}
		$s = $this->source;
		if ( $s['name'] === DB_NAME && $s['prefix'] === $wpdb->prefix ) {
			$this->error = 'الإعدادات المكتشفة تشير إلى قاعدة هذا الموقع نفسه، لا إلى الموقع الأساسي.';
			return false;
		}
		if ( ! class_exists( 'mysqli' ) ) {
			$this->error = 'امتداد mysqli غير متاح في PHP.';
			return false;
		}
		[ $host, $port, $socket ] = self::host_parts( $s['host'] );
		mysqli_report( MYSQLI_REPORT_OFF );
		$db = mysqli_init();
		if ( ! $db || ! @$db->real_connect( $host, $s['user'], $s['pass'], $s['name'], $port, $socket ) ) { // phpcs:ignore WordPress.PHP.NoSilencedErrors
			$this->error = 'تعذّر الاتصال بقاعدة الموقع الأساسي: ' . mysqli_connect_error();
			return false;
		}
		$db->set_charset( 'utf8mb4' );
		$db->query( 'SET SESSION TRANSACTION READ ONLY' ); // nothing can be written to the main site
		$this->db = $db;
		$opts     = array();
		foreach ( $this->rows( "SELECT option_name, option_value FROM {$this->t('options')} WHERE option_name IN ('home','siteurl','upload_path','upload_url_path')" ) as $r ) {
			$opts[ $r['option_name'] ] = $r['option_value'];
		}
		if ( empty( $opts['home'] ) ) {
			$this->error = 'قاعدة الموقع الأساسي لا تحتوي جدول الإعدادات المتوقع (البادئة ' . $s['prefix'] . ').';
			return false;
		}
		$this->src_home    = untrailingslashit( $opts['home'] );
		$this->src_up_dir  = ! empty( $opts['upload_path'] ) ? ( str_starts_with( $opts['upload_path'], '/' ) ? $opts['upload_path'] : $s['root'] . '/' . $opts['upload_path'] ) : $s['root'] . '/wp-content/uploads';
		$this->src_uploads = ! empty( $opts['upload_url_path'] ) ? untrailingslashit( $opts['upload_url_path'] ) : untrailingslashit( $opts['siteurl'] ?? $opts['home'] ) . '/wp-content/uploads';
		return true;
	}

	private static function host_parts( string $host ): array {
		$port   = null;
		$socket = null;
		if ( str_contains( $host, ':' ) ) {
			[ $h, $rest ] = explode( ':', $host, 2 );
			if ( is_numeric( $rest ) ) {
				$port = (int) $rest;
			} else {
				$socket = $rest;
			}
			$host = '' !== $h ? $h : 'localhost';
		}
		return array( $host, $port, $socket );
	}

	private function t( string $table ): string {
		return '`' . str_replace( '`', '', $this->source['prefix'] . $table ) . '`';
	}

	private function rows( string $sql ): array {
		$res = $this->db->query( $sql );
		if ( ! $res ) {
			$this->error = $this->db->error;
			return array();
		}
		$out = $res->fetch_all( MYSQLI_ASSOC );
		$res->free();
		return $out;
	}

	private function ids( array $ids ): string {
		$ids = array_values( array_unique( array_filter( array_map( 'intval', $ids ) ) ) );
		return $ids ? implode( ',', $ids ) : '0';
	}

	private function meta( string $table, string $col, array $ids ): array {
		$out = array();
		foreach ( $this->rows( "SELECT {$col} AS id, meta_key, meta_value FROM {$this->t($table)} WHERE {$col} IN ({$this->ids($ids)})" ) as $r ) {
			$out[ (int) $r['id'] ][ $r['meta_key'] ][] = $r['meta_value'];
		}
		return $out;
	}

	/** Source summary for the screen (no password). */
	public function describe(): array {
		return array(
			'config'  => $this->source['config'] ?? '',
			'db'      => ( $this->source['name'] ?? '' ) . ' / ' . ( $this->source['prefix'] ?? '' ),
			'home'    => $this->src_home,
			'uploads' => $this->src_up_dir,
		);
	}

	/* ================================================================ read */

	/** All published posts of the main site, oldest first. */
	public function source_posts(): array {
		return $this->rows( "SELECT * FROM {$this->t('posts')} WHERE post_type = 'post' AND post_status = 'publish' ORDER BY post_date ASC, ID ASC" );
	}

	private function note( string $status, string $item, string $msg = '' ): void {
		$this->log[] = array( $status, $item, $msg );
		if ( isset( $this->counts[ $status ] ) ) {
			++$this->counts[ $status ];
		}
	}

	/* ================================================================ run */

	public function run(): bool {
		if ( ! $this->db && ! $this->connect() ) {
			$this->note( 'failed', 'source', $this->error );
			return false;
		}
		$posts = $this->source_posts();
		$this->note( 'info', 'source', sprintf( '%d مقالًا منشورًا في %s (%s)', count( $posts ), $this->src_home, $this->describe()['db'] ) );
		if ( ! $posts ) {
			return true;
		}
		$ids   = wp_list_pluck( $posts, 'ID' );
		$pmeta = $this->meta( 'postmeta', 'post_id', $ids );
		$terms = $this->rows(
			"SELECT tr.object_id, tt.taxonomy, tt.description, tt.parent, t.term_id, t.name, t.slug
			 FROM {$this->t('term_relationships')} tr
			 JOIN {$this->t('term_taxonomy')} tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
			 JOIN {$this->t('terms')} t ON t.term_id = tt.term_id
			 WHERE tr.object_id IN ({$this->ids($ids)}) AND tt.taxonomy IN ('category','post_tag')"
		);
		foreach ( $posts as $p ) {
			$this->migrate_one( $p, $pmeta[ (int) $p['ID'] ] ?? array(), array_values( array_filter( $terms, static fn( $t ) => (int) $t['object_id'] === (int) $p['ID'] ) ) );
		}
		$this->rank_math_entity();
		if ( ! $this->dry && $this->backups ) {
			$this->write_backup_file();
		}
		if ( ! $this->dry ) {
			update_option( 'sh_posts_migrated', array( 'time' => time(), 'source' => $this->src_home, 'counts' => $this->counts ), false );
		}
		return 0 === $this->counts['failed'];
	}

	private function migrate_one( array $p, array $meta, array $terms ): void {
		$sid   = (int) $p['ID'];
		$label = $p['post_name'] ? urldecode( $p['post_name'] ) : (string) $sid;
		$hash  = md5( wp_json_encode( array( $p['post_title'], $p['post_content'], $p['post_excerpt'], $p['post_name'], $p['post_date_gmt'], $p['post_modified_gmt'], $p['post_author'], $meta, wp_list_pluck( $terms, 'slug' ) ) ) );

		// target: same source post, else same slug
		$target = get_posts( array( 'post_type' => 'post', 'post_status' => 'any', 'posts_per_page' => 1, 'meta_key' => '_sh_source_post', 'meta_value' => (string) $sid, 'fields' => 'ids' ) ); // phpcs:ignore WordPress.DB.SlowDBQuery
		$id     = $target ? (int) $target[0] : 0;
		$mode   = $id ? 'update' : 'create';
		if ( ! $id ) {
			$same = get_posts( array( 'post_type' => 'post', 'post_status' => array( 'publish', 'draft', 'pending', 'private', 'future' ), 'name' => $p['post_name'], 'posts_per_page' => 1, 'fields' => 'ids' ) );
			if ( $same ) {
				$id   = (int) $same[0];
				$mode = 'replace';
			}
		}
		if ( 'update' === $mode ) {
			if ( get_post_meta( $id, '_sh_migration_source_hash', true ) === $hash && ! $this->force ) {
				$this->note( 'skipped', $label, 'بلا تغيير في الموقع الأساسي' );
				return;
			}
			if ( get_post_meta( $id, '_sh_migration_hash', true ) !== $this->target_hash( $id ) && ! $this->force ) {
				$this->note( 'protected', $label, 'عُدّل هنا بعد النقل؛ لم يُكتب فوقه (--force للكتابة)' );
				return;
			}
		}
		if ( $this->dry ) {
			$images = count( $this->attachment_ids( $p, $meta ) );
			$what   = array( 'create' => 'سيُنشأ', 'replace' => 'ستُحفظ نسخة من المسودة الحالية ثم تُستبدل', 'update' => 'سيُحدَّث من الموقع الأساسي' );
			$this->note( 'create' === $mode ? 'created' : ( 'replace' === $mode ? 'replaced' : 'updated' ), $label, $what[ $mode ] . ' — ' . $p['post_title'] . ' — ' . substr( $p['post_date'], 0, 10 ) . ' — صور: ' . $images );
			return;
		}

		// images first: content and meta refer to them
		foreach ( $this->attachment_ids( $p, $meta ) as $aid ) {
			$this->attachment( $aid, (int) $p['post_author'] );
		}
		$author = $this->user( (int) $p['post_author'] );
		if ( 'replace' === $mode ) {
			$this->backup( $id );
		}
		$data = array(
			'post_type'      => 'post',
			'post_title'     => $p['post_title'],
			'post_content'   => $this->rewrite( $p['post_content'] ),
			'post_excerpt'   => $this->rewrite( $p['post_excerpt'] ),
			'post_status'    => 'publish',
			'post_name'      => $p['post_name'],
			'post_author'    => $author,
			'post_date'      => $p['post_date'],
			'post_date_gmt'  => $p['post_date_gmt'],
			'edit_date'      => true, // a draft being replaced would otherwise lose the date
			'comment_status' => $p['comment_status'],
			'ping_status'    => $p['ping_status'],
			'menu_order'     => (int) $p['menu_order'],
		);
		kses_remove_filters(); // the source content is the site's own, already published markup
		if ( $id ) {
			$data['ID'] = $id;
			$res        = wp_update_post( wp_slash( $data ), true );
		} else {
			$res = wp_insert_post( wp_slash( $data ), true );
		}
		kses_init_filters();
		if ( is_wp_error( $res ) ) {
			$this->note( 'failed', $label, $res->get_error_message() );
			return;
		}
		$id = (int) $res;
		global $wpdb;
		// WordPress sets "modified" to now: keep the main site's dates
		$wpdb->update( $wpdb->posts, array( 'post_modified' => $p['post_modified'], 'post_modified_gmt' => $p['post_modified_gmt'], 'post_name' => $p['post_name'] ), array( 'ID' => $id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		clean_post_cache( $id );

		// categories and tags by slug
		foreach ( array( 'category', 'post_tag' ) as $tax ) {
			$ids = array();
			foreach ( $terms as $t ) {
				if ( $t['taxonomy'] === $tax ) {
					$ids[] = $this->term( $t );
				}
			}
			wp_set_post_terms( $id, array_filter( $ids ), $tax, false );
		}
		// featured image
		$thumb = (int) ( $meta['_thumbnail_id'][0] ?? 0 );
		if ( $thumb && ! empty( $this->map_att[ $thumb ] ) ) {
			set_post_thumbnail( $id, $this->map_att[ $thumb ] );
		} elseif ( ! $thumb ) {
			delete_post_thumbnail( $id );
		}
		// Rank Math data (references remapped); anything Rank Math stored before is replaced
		foreach ( array_keys( get_post_meta( $id ) ) as $k ) {
			if ( str_starts_with( $k, 'rank_math_' ) ) {
				delete_post_meta( $id, $k );
			}
		}
		foreach ( $meta as $k => $vals ) {
			if ( ! str_starts_with( $k, 'rank_math_' ) ) {
				continue;
			}
			foreach ( $vals as $v ) {
				add_post_meta( $id, $k, wp_slash( $this->rank_math_value( $k, maybe_unserialize( $v ) ) ) );
			}
		}
		// the review draft's own article fields (intro, reading time, author link…) do not describe the real article
		if ( 'replace' === $mode && function_exists( 'acf_get_fields' ) ) {
			foreach ( (array) acf_get_fields( 'group_sh_post' ) as $f ) {
				delete_field( $f['key'], $id );
			}
		}
		update_post_meta( $id, '_sh_source_post', (string) $sid );
		update_post_meta( $id, '_sh_source_site', $this->src_home );
		update_post_meta( $id, '_sh_migration_source_hash', $hash );
		update_post_meta( $id, '_sh_migration_hash', $this->target_hash( $id ) );
		$msg = array( 'create' => 'أُنشئ', 'replace' => 'استُبدلت المسودة (نسختها محفوظة)', 'update' => 'حُدّث' );
		$this->note( 'create' === $mode ? 'created' : ( 'replace' === $mode ? 'replaced' : 'updated' ), $label, $msg[ $mode ] . ' — ' . get_permalink( $id ) );
	}

	/**
	 * Rank Math's site entity (Titles & Meta ← Local SEO: Organization or Person, name, logo, social
	 * profiles) from the main site, so the Organization node stays as it is there. Only fields still
	 * at Rank Math's default here are filled; a value set here is kept.
	 */
	private function rank_math_entity(): void {
		if ( ! function_exists( 'sh_rankmath_active' ) || ! sh_rankmath_active() ) {
			return;
		}
		$row = $this->rows( "SELECT option_value FROM {$this->t('options')} WHERE option_name = 'rank-math-options-titles'" );
		$src = $row ? @unserialize( $row[0]['option_value'], array( 'allowed_classes' => false ) ) : false; // phpcs:ignore
		if ( ! is_array( $src ) ) {
			return;
		}
		$here    = get_option( 'rank-math-options-titles', array() );
		$here    = is_array( $here ) ? $here : array();
		$keys    = array( 'knowledgegraph_type', 'knowledgegraph_name', 'knowledgegraph_logo', 'website_name', 'website_alternate_name', 'social_url_facebook', 'twitter_author_names', 'social_additional_profiles', 'local_business_type', 'local_address', 'phone', 'email', 'url', 'opening_hours' );
		$default = array( 'knowledgegraph_type' => 'person', 'knowledgegraph_name' => get_bloginfo( 'name' ), 'website_name' => get_bloginfo( 'name' ) );
		$changed = array();
		foreach ( $keys as $k ) {
			if ( ! isset( $src[ $k ] ) || '' === $src[ $k ] || array() === $src[ $k ] ) {
				continue;
			}
			$cur = $here[ $k ] ?? '';
			if ( '' !== $cur && array() !== $cur && ( $default[ $k ] ?? null ) !== $cur ) {
				continue; // set here: kept
			}
			if ( $cur === $src[ $k ] ) {
				continue;
			}
			$here[ $k ] = $this->rewrite_deep( $src[ $k ] );
			$changed[]  = $k;
			if ( 'knowledgegraph_logo' === $k && ! $this->dry ) {
				$this->copy_upload( (string) $src[ $k ] );
			}
		}
		if ( ! $changed ) {
			return;
		}
		if ( ! $this->dry ) {
			update_option( 'rank-math-options-titles', $here );
		}
		$this->note( 'info', 'rank-math', ( $this->dry ? 'سيُنسخ' : 'نُسخ' ) . ' كيان الموقع من Rank Math في الموقع الأساسي: ' . implode( '، ', $changed ) );
	}

	/** Copies one file of the main site's uploads (by its URL) to the same path here. */
	private function copy_upload( string $url ): void {
		$base = preg_replace( '~^https?:~', '', $this->src_uploads );
		$rel  = preg_replace( '~^(?:https?:)?' . preg_quote( $base, '~' ) . '/~i', '', $url, 1, $n );
		if ( ! $n || str_contains( $rel, '..' ) ) {
			return;
		}
		$from = $this->src_up_dir . '/' . rawurldecode( $rel );
		$to   = wp_upload_dir( null, false )['basedir'] . '/' . rawurldecode( $rel );
		if ( is_readable( $from ) && ! file_exists( $to ) ) {
			wp_mkdir_p( dirname( $to ) );
			copy( $from, $to );
		}
	}

	/** Fingerprint of what the migration wrote (to tell later edits made here). */
	private function target_hash( int $id ): string {
		$p = get_post( $id );
		return $p ? md5( wp_json_encode( array( $p->post_title, $p->post_content, $p->post_excerpt, $p->post_status, $p->post_name ) ) ) : '';
	}

	private function backup( int $id ): void {
		$p = get_post( $id );
		if ( ! $p ) {
			return;
		}
		$fields = array();
		foreach ( get_post_meta( $id ) as $k => $v ) {
			$fields[ $k ] = array_map( 'maybe_unserialize', $v );
		}
		$copy = array(
			'ID'           => $id,
			'time'         => time(),
			'post_title'   => $p->post_title,
			'post_name'    => $p->post_name,
			'post_status'  => $p->post_status,
			'post_date'    => $p->post_date,
			'post_excerpt' => $p->post_excerpt,
			'post_content' => $p->post_content,
			'thumbnail'    => (int) get_post_thumbnail_id( $id ),
			'categories'   => wp_get_post_categories( $id, array( 'fields' => 'slugs' ) ),
			'meta'         => $fields,
		);
		if ( ! metadata_exists( 'post', $id, '_sh_pre_migration' ) ) {
			update_post_meta( $id, '_sh_pre_migration', wp_slash( $copy ) );
		}
		wp_save_post_revision( $id );
		$this->backups[] = $copy;
	}

	private function write_backup_file(): void {
		$up  = wp_upload_dir();
		$dir = trailingslashit( $up['basedir'] ) . 'seohouse-backups';
		wp_mkdir_p( $dir );
		if ( ! file_exists( $dir . '/index.php' ) ) {
			file_put_contents( $dir . '/index.php', "<?php // Silence.\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions
			file_put_contents( $dir . '/.htaccess', "Require all denied\nDeny from all\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		}
		$file = $dir . '/drafts-before-migration-' . gmdate( 'Ymd-His' ) . '-' . wp_generate_password( 6, false ) . '.json';
		file_put_contents( $file, wp_json_encode( $this->backups, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		$this->note( 'info', 'backup', 'نسخة المسودات المستبدلة: ' . str_replace( ABSPATH, '', $file ) );
	}

	/* ================================================================ references */

	/** Source attachment IDs used by a post: featured image, images in the content, Rank Math images. */
	private function attachment_ids( array $p, array $meta ): array {
		$ids = array();
		if ( ! empty( $meta['_thumbnail_id'][0] ) ) {
			$ids[] = (int) $meta['_thumbnail_id'][0];
		}
		foreach ( array( 'rank_math_facebook_image_id', 'rank_math_twitter_image_id' ) as $k ) {
			if ( ! empty( $meta[ $k ][0] ) ) {
				$ids[] = (int) $meta[ $k ][0];
			}
		}
		$c = (string) $p['post_content'];
		preg_match_all( '/wp-image-(\d+)|<!-- wp:(?:image|media-text|cover)\s+\{[^}]*"(?:id|mediaId)":(\d+)/', $c, $m );
		foreach ( array_merge( $m[1], $m[2] ) as $x ) {
			if ( $x ) {
				$ids[] = (int) $x;
			}
		}
		// images referenced by URL only (no class): look the file up in the main site's media
		$base = preg_quote( preg_replace( '#^https?:#', '', $this->src_uploads ), '#' );
		if ( preg_match_all( '#(?:https?:)?' . $base . '/([^"\'\s?)]+)#i', $c, $u ) ) {
			$files = array();
			foreach ( array_unique( $u[1] ) as $rel ) {
				$files[] = preg_replace( '/-\d+x\d+(?=\.[a-z0-9]+$)/i', '', $rel );
				$files[] = preg_replace( '/-(?:\d+x\d+|scaled)(?=\.[a-z0-9]+$)/i', '', $rel );
			}
			$files = array_values( array_unique( $files ) );
			$list  = implode( ',', array_map( fn( $f ) => "'" . $this->db->real_escape_string( $f ) . "'", $files ) );
			$like  = array();
			foreach ( $files as $f ) {
				$like[] = "meta_value LIKE '" . $this->db->real_escape_string( preg_replace( '/(\.[a-z0-9]+)$/i', '-scaled$1', $f ) ) . "'";
			}
			if ( $list ) {
				foreach ( $this->rows( "SELECT post_id FROM {$this->t('postmeta')} WHERE meta_key = '_wp_attached_file' AND (meta_value IN ({$list})" . ( $like ? ' OR ' . implode( ' OR ', $like ) : '' ) . ')' ) as $r ) {
					$ids[] = (int) $r['post_id'];
				}
			}
		}
		return array_values( array_unique( array_filter( $ids ) ) );
	}

	/** Copies one attachment (files of every size + metadata); returns the target ID. */
	private function attachment( int $sid, int $src_author ): int {
		if ( isset( $this->map_att[ $sid ] ) ) {
			return $this->map_att[ $sid ];
		}
		$found = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'any', 'posts_per_page' => 1, 'fields' => 'ids', 'meta_key' => '_sh_source_attachment', 'meta_value' => (string) $sid ) ); // phpcs:ignore WordPress.DB.SlowDBQuery
		if ( $found ) {
			return $this->map_att[ $sid ] = (int) $found[0];
		}
		$rows = $this->rows( "SELECT * FROM {$this->t('posts')} WHERE ID = " . (int) $sid . " AND post_type = 'attachment'" );
		if ( ! $rows ) {
			$this->note( 'failed', 'image:' . $sid, 'الصورة غير موجودة في الموقع الأساسي' );
			return $this->map_att[ $sid ] = 0;
		}
		$a    = $rows[0];
		$meta = $this->meta( 'postmeta', 'post_id', array( $sid ) )[ $sid ] ?? array();
		$file = (string) ( $meta['_wp_attached_file'][0] ?? '' );
		$info = maybe_unserialize( $meta['_wp_attachment_metadata'][0] ?? '' );
		if ( '' === $file ) {
			$this->note( 'failed', 'image:' . $sid, 'لا يوجد ملف مسجّل للصورة' );
			return $this->map_att[ $sid ] = 0;
		}
		// files: original, scaled original and every generated size, same relative path here
		$up    = wp_upload_dir( null, false );
		$dir   = dirname( $file );
		$names = array( basename( $file ) );
		if ( is_array( $info ) ) {
			if ( ! empty( $info['original_image'] ) ) {
				$names[] = $info['original_image'];
			}
			foreach ( (array) ( $info['sizes'] ?? array() ) as $s ) {
				if ( ! empty( $s['file'] ) ) {
					$names[] = $s['file'];
				}
			}
		}
		$copied = 0;
		foreach ( array_unique( $names ) as $n ) {
			$from = $this->src_up_dir . '/' . ( '.' === $dir ? '' : $dir . '/' ) . $n;
			$to   = $up['basedir'] . '/' . ( '.' === $dir ? '' : $dir . '/' ) . $n;
			if ( ! is_readable( $from ) ) {
				if ( basename( $file ) === $n ) {
					$this->note( 'failed', 'image:' . $sid, 'الملف غير موجود في مجلد الموقع الأساسي: ' . $file );
					return $this->map_att[ $sid ] = 0;
				}
				continue;
			}
			wp_mkdir_p( dirname( $to ) );
			if ( ! file_exists( $to ) || filesize( $to ) !== filesize( $from ) ) {
				copy( $from, $to );
			}
			++$copied;
		}
		$new = wp_insert_attachment(
			array(
				'post_title'     => $a['post_title'],
				'post_content'   => $a['post_content'],
				'post_excerpt'   => $a['post_excerpt'],
				'post_status'    => 'inherit',
				'post_mime_type' => $a['post_mime_type'],
				'post_date'      => $a['post_date'],
				'post_date_gmt'  => $a['post_date_gmt'],
				'post_name'      => $a['post_name'],
				'post_author'    => $this->user( (int) ( $a['post_author'] ? $a['post_author'] : $src_author ) ),
				'guid'           => $up['baseurl'] . '/' . $file,
			),
			$up['basedir'] . '/' . $file,
			0,
			true
		);
		if ( is_wp_error( $new ) ) {
			$this->note( 'failed', 'image:' . $sid, $new->get_error_message() );
			return $this->map_att[ $sid ] = 0;
		}
		update_post_meta( $new, '_wp_attached_file', $file );
		if ( is_array( $info ) ) {
			update_post_meta( $new, '_wp_attachment_metadata', $info );
		}
		if ( isset( $meta['_wp_attachment_image_alt'][0] ) ) {
			update_post_meta( $new, '_wp_attachment_image_alt', wp_slash( $meta['_wp_attachment_image_alt'][0] ) );
		}
		update_post_meta( $new, '_sh_source_attachment', (string) $sid );
		$this->note( 'created', 'image:' . basename( $file ), $copied . ' ملفات (كل المقاسات)' );
		return $this->map_att[ $sid ] = (int) $new;
	}

	private function term( array $t ): int {
		$key = $t['taxonomy'] . ':' . $t['term_id'];
		if ( isset( $this->map_term[ $key ] ) ) {
			return $this->map_term[ $key ];
		}
		$found = get_term_by( 'slug', $t['slug'], $t['taxonomy'] );
		if ( $found ) {
			return $this->map_term[ $key ] = (int) $found->term_id;
		}
		$parent = 0;
		if ( (int) $t['parent'] ) {
			$pr = $this->rows( "SELECT tt.taxonomy, tt.description, tt.parent, t.term_id, t.name, t.slug FROM {$this->t('term_taxonomy')} tt JOIN {$this->t('terms')} t ON t.term_id = tt.term_id WHERE t.term_id = " . (int) $t['parent'] . " AND tt.taxonomy = '" . $this->db->real_escape_string( $t['taxonomy'] ) . "'" );
			if ( $pr ) {
				$parent = $this->term( $pr[0] );
			}
		}
		$res = wp_insert_term( $t['name'], $t['taxonomy'], array( 'slug' => $t['slug'], 'description' => $t['description'], 'parent' => $parent ) );
		if ( is_wp_error( $res ) ) {
			$this->note( 'failed', 'term:' . urldecode( $t['slug'] ), $res->get_error_message() );
			return $this->map_term[ $key ] = 0;
		}
		$this->note( 'created', ( 'category' === $t['taxonomy'] ? 'category:' : 'tag:' ) . $t['name'], '' );
		return $this->map_term[ $key ] = (int) $res['term_id'];
	}

	private function user( int $sid ): int {
		if ( ! $sid ) {
			return get_current_user_id();
		}
		if ( isset( $this->map_user[ $sid ] ) ) {
			return $this->map_user[ $sid ];
		}
		$rows = $this->rows( "SELECT ID, user_login, user_nicename, user_email, user_url, display_name, user_registered FROM {$this->t('users')} WHERE ID = " . $sid );
		if ( ! $rows ) {
			return $this->map_user[ $sid ] = get_current_user_id();
		}
		$u     = $rows[0];
		$found = get_user_by( 'login', $u['user_login'] );
		if ( ! $found && $u['user_email'] ) {
			$found = get_user_by( 'email', $u['user_email'] );
		}
		$m = $this->meta( 'usermeta', 'user_id', array( $sid ) )[ $sid ] ?? array();
		if ( $found ) {
			// the byline must read as on the main site: a matched account still carrying WordPress's
			// default name (its login) takes the main site's name; a name chosen here is kept
			if ( $found->display_name !== $u['display_name'] ) {
				if ( $found->display_name === $found->user_login && ! $this->dry ) {
					wp_update_user( array( 'ID' => $found->ID, 'display_name' => $u['display_name'], 'first_name' => $m['first_name'][0] ?? '', 'last_name' => $m['last_name'][0] ?? '' ) );
					$this->note( 'info', 'author:' . $u['user_login'], 'اسم العرض ← ' . $u['display_name'] . ' (كما في الموقع الأساسي)' );
				} else {
					$this->note( 'info', 'author:' . $u['user_login'], 'الحساب هنا باسم «' . $found->display_name . '» والموقع الأساسي «' . $u['display_name'] . '»؛ لم يُغيَّر' );
				}
			}
			return $this->map_user[ $sid ] = (int) $found->ID;
		}
		$new = wp_insert_user(
			array(
				'user_login'    => $u['user_login'],
				'user_pass'     => wp_generate_password( 32 ),
				'user_email'    => $u['user_email'],
				'user_url'      => $u['user_url'],
				'user_nicename' => $u['user_nicename'],
				'display_name'  => $u['display_name'],
				'first_name'    => $m['first_name'][0] ?? '',
				'last_name'     => $m['last_name'][0] ?? '',
				'description'   => $m['description'][0] ?? '',
				'role'          => 'author',
			)
		);
		if ( is_wp_error( $new ) ) {
			$this->note( 'failed', 'author:' . $u['user_login'], $new->get_error_message() );
			return $this->map_user[ $sid ] = get_current_user_id();
		}
		update_user_meta( $new, '_sh_source_user', (string) $sid );
		$this->note( 'created', 'author:' . $u['display_name'], 'كاتب (دور: كاتب)، كلمة مرور عشوائية' );
		return $this->map_user[ $sid ] = (int) $new;
	}

	/* ================================================================ rewriting */

	/**
	 * Main-site addresses → this site: uploaded files first, then links to the main site's pages,
	 * then image IDs in classes and block attributes.
	 */
	public function rewrite( string $s ): string {
		if ( '' === $s ) {
			return $s;
		}
		$up      = wp_upload_dir( null, false );
		$up_from = preg_replace( '#^https?:#', '', $this->src_uploads );
		$up_to   = preg_replace( '#^https?:#', '', $up['baseurl'] );
		$s       = (string) preg_replace( '~(?:https?:)?' . preg_quote( $up_from, '~' ) . '~i', "\x01UP\x01", $s );
		$home    = preg_replace( '~^https?:~', '', $this->src_home );
		$s       = (string) preg_replace( '~(?:https?:)?' . preg_quote( $home, '~' ) . '(?=[/"\'\s?#<]|$)~i', "\x01HOME\x01", $s );
		$scheme  = is_ssl() || str_starts_with( home_url(), 'https' ) ? 'https:' : 'http:';
		$s       = str_replace( array( "\x01UP\x01", "\x01HOME\x01" ), array( $scheme . $up_to, untrailingslashit( home_url() ) ), $s );
		$map     = $this->map_att;
		$s       = preg_replace_callback( '/wp-image-(\d+)/', static fn( $m ) => 'wp-image-' . ( ! empty( $map[ (int) $m[1] ] ) ? $map[ (int) $m[1] ] : $m[1] ), $s );
		$s       = preg_replace_callback( '/(<!-- wp:(?:image|media-text|cover)\s+\{[^}]*"(?:id|mediaId)":)(\d+)/', static fn( $m ) => $m[1] . ( ! empty( $map[ (int) $m[2] ] ) ? $map[ (int) $m[2] ] : $m[2] ), $s );
		return $s;
	}

	private function rank_math_value( string $key, $v ) {
		if ( in_array( $key, array( 'rank_math_facebook_image_id', 'rank_math_twitter_image_id' ), true ) ) {
			return (string) ( $this->map_att[ (int) $v ] ?? '' );
		}
		if ( 'rank_math_primary_category' === $key ) {
			return (string) ( $this->map_term[ 'category:' . (int) $v ] ?? '' );
		}
		return $this->rewrite_deep( $v );
	}

	private function rewrite_deep( $v ) {
		if ( is_array( $v ) ) {
			foreach ( $v as $k => $x ) {
				$v[ $k ] = $this->rewrite_deep( $x );
			}
			return $v;
		}
		return is_string( $v ) ? $this->rewrite( $v ) : $v;
	}
}

/* ------------------------------------------------------------------ the importer leaves migrated articles alone */

add_filter(
	'sh_importer_skip_post',
	static function ( $skip, $id ) {
		return $skip || ( $id > 0 && '' !== (string) get_post_meta( $id, '_sh_source_post', true ) );
	},
	10,
	2
);

/* ------------------------------------------------------------------ screen «نقل المقالات» */

add_action(
	'admin_menu',
	static function () {
		add_submenu_page( 'seohouse-settings', __( 'نقل المقالات من الموقع الأساسي', 'seohouse-core' ), __( 'نقل المقالات', 'seohouse-core' ), 'manage_options', 'seohouse-migrate-posts', 'sh_post_migration_page' );
	},
	26
);

function sh_post_migration_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$action = isset( $_POST['sh_migrate'] ) ? sanitize_key( wp_unslash( $_POST['sh_migrate'] ) ) : '';
	echo '<div class="wrap"><h1>' . esc_html__( 'نقل المقالات من الموقع الأساسي', 'seohouse-core' ) . '</h1>';
	echo '<p style="max-width:62em;font-size:14px">' . esc_html__( 'ينقل كل المقالات المنشورة في الموقع الأساسي إلى هذا الموقع كما هي: العنوان، والنص وتنسيقه، والرابط، والتاريخ، والكاتب، والتصنيف، والصورة البارزة والصور داخل المقال مع نصها البديل، وبيانات Rank Math. يقرأ قاعدة الموقع الأساسي فقط ولا يكتب فيها شيئًا. المقالات الموجودة هنا بنفس الرابط تُحفظ نسختها قبل استبدالها. التشغيل مرة أخرى لا يكرر شيئًا.', 'seohouse-core' ) . '</p>';
	$m = new SH_Post_Migration( array( 'dry_run' => 'run' !== $action ) );
	if ( ! $m->connect() ) {
		echo '<div class="notice notice-error"><p>' . esc_html( $m->error ) . '</p></div></div>';
		return;
	}
	$d = $m->describe();
	echo '<table class="widefat" style="max-width:62em"><tbody>';
	echo '<tr><th style="width:14em">' . esc_html__( 'الموقع الأساسي', 'seohouse-core' ) . '</th><td dir="ltr">' . esc_html( $d['home'] ) . '</td></tr>';
	echo '<tr><th>' . esc_html__( 'القاعدة / البادئة', 'seohouse-core' ) . '</th><td dir="ltr">' . esc_html( $d['db'] ) . ' <small>(' . esc_html( $d['config'] ) . ')</small></td></tr>';
	echo '<tr><th>' . esc_html__( 'مجلد الصور', 'seohouse-core' ) . '</th><td dir="ltr">' . esc_html( $d['uploads'] ) . '</td></tr>';
	echo '<tr><th>' . esc_html__( 'المقالات المنشورة', 'seohouse-core' ) . '</th><td>' . count( $m->source_posts() ) . '</td></tr>';
	echo '<tr><th>' . esc_html__( 'روابط المقالات هنا', 'seohouse-core' ) . '</th><td dir="ltr">' . esc_html( get_option( 'permalink_structure' ) ) . ( '/blog/%postname%/' === get_option( 'permalink_structure' ) ? '' : ' — ' . esc_html__( 'ستُضبط على /blog/%postname%/ مثل الموقع الأساسي عند النقل', 'seohouse-core' ) ) . '</td></tr>';
	echo '</tbody></table>';
	if ( 'run' === $action ) {
		check_admin_referer( 'sh_migrate' );
		if ( '/blog/%postname%/' !== get_option( 'permalink_structure' ) && function_exists( 'sh_core_apply_permalinks' ) ) {
			sh_core_apply_permalinks();
		}
		@set_time_limit( 300 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
	}
	if ( $action ) {
		if ( 'run' !== $action ) {
			check_admin_referer( 'sh_migrate' );
		}
		$m->run();
		$c = $m->counts;
		echo '<div class="notice notice-' . ( $c['failed'] ? 'warning' : ( 'run' === $action ? 'success' : 'info' ) ) . ' inline" style="max-width:62em"><p><strong>' . esc_html( 'run' === $action ? 'اكتمل النقل.' : 'معاينة — لم يُكتب شيء.' ) . '</strong> ' . esc_html( sprintf( 'جديد: %d — استبدال مسودة: %d — تحديث: %d — بلا تغيير: %d — محمي: %d — فشل: %d', $c['created'], $c['replaced'], $c['updated'], $c['skipped'], $c['protected'], $c['failed'] ) ) . '</p></div>';
		echo '<table class="widefat striped" style="max-width:72em"><tbody>';
		foreach ( $m->log as $l ) {
			echo '<tr><td style="width:8em">' . esc_html( $l[0] ) . '</td><td dir="auto">' . esc_html( $l[1] ) . '</td><td dir="auto">' . esc_html( $l[2] ) . '</td></tr>';
		}
		echo '</tbody></table>';
	}
	echo '<form method="post" style="margin-top:16px">';
	wp_nonce_field( 'sh_migrate' );
	echo '<button class="button button-large" name="sh_migrate" value="preview">' . esc_html__( 'معاينة النقل', 'seohouse-core' ) . '</button> ';
	echo '<button class="button button-primary button-large" name="sh_migrate" value="run" onclick="return confirm(\'' . esc_js( __( 'نقل المقالات المنشورة من الموقع الأساسي إلى هذا الموقع؟', 'seohouse-core' ) ) . '\')">' . esc_html__( 'نقل المقالات', 'seohouse-core' ) . '</button>';
	echo '</form>';
	$last = get_option( 'sh_posts_migrated' );
	if ( is_array( $last ) ) {
		echo '<p>' . esc_html( sprintf( 'آخر نقل: %s', wp_date( 'Y-m-d H:i', (int) $last['time'] ) ) ) . '</p>';
	}
	echo '</div>';
}
