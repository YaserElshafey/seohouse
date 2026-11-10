<?php
/**
 * Remote maintenance over the WordPress REST API (2.8.0): publish the SEO House theme / Core packages and
 * link writers, without logging in to wp-admin.
 *
 * Who can call it: an administrator, authenticated as WordPress allows for REST (an Application Password
 * created in «المستخدمون ← الملف الشخصي ← كلمات مرور التطبيقات», over HTTPS), sent as Basic authentication or
 * as `X-SeoHouse-Auth: user:password`. Each route checks the same
 * capabilities wp-admin asks for the same action (uploading a theme or plugin, switching the theme,
 * editing others' posts), so it allows nothing an administrator cannot already do in wp-admin. Revoking
 * the Application Password ends remote access.
 *
 * Routes (namespace seohouse/v1):
 *   GET  ping               public: whether the X-SeoHouse-Auth header arrived and was accepted (no data)
 *   GET  site               versions and settings that matter for publishing and indexing (read-only): front
 *                           and posts page, environment type, Rank Math state, category counts, case studies
 *   POST deploy             kind=theme|core, package=<zip upload>, sha256=<hex>
 *                           Only the packages named `seohouse` (theme) / `seohouse-core` (plugin) are accepted;
 *                           the upload must match sha256. The live copy is saved as a ZIP backup, the package
 *                           installed as «استبدال الحالي بالمرفوع» does, the theme activated / plugin kept active,
 *                           caches purged, then the homepage is requested: on a server error (5xx) the backup is
 *                           installed back the same way and the response says so.
 *   POST rollback           kind=theme|core: install the latest backup back (each backup is used once)
 *   POST recount            recount the published articles of categories and tags
 *   GET  writers            WordPress accounts that can write, team profiles and their links, articles per writer
 *   POST writers/link       member=<team profile id>, user=<account id or 0 to unlink>
 *   POST writers/assign     user=<account id>, dry_run=1|0: every article (any status but trash) gets this
 *                           account as author and loses its «الكاتب من فريق العمل» override, so the article is
 *                           credited to the account's team profile
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

const SH_REMOTE_NS          = 'seohouse/v1';
const SH_REMOTE_MAX_BYTES   = 60 * 1024 * 1024;
const SH_REMOTE_KEEP_BACKUP = 3;

add_action( 'rest_api_init', 'sh_remote_routes' );

/**
 * The same Application Password, also accepted in an `X-SeoHouse-Auth: <user>:<application password>` header
 * (2.8.1): a plain value the cloud environment's network secret can send as is (no base64), and one that
 * hosts which drop the Authorization header still pass. REST requests over HTTPS only (or a local test
 * site); WordPress validates the password exactly as for Basic authentication.
 */
add_filter( 'determine_current_user', 'sh_remote_header_auth', 25 );

/** Why the X-SeoHouse-Auth header was not accepted on this request ('' when it was, or was not sent). */
function sh_remote_auth_problem( ?string $set = null ): string {
	static $problem = '';
	if ( null !== $set ) {
		$problem = $set;
	}
	return $problem;
}

function sh_remote_header_auth( $user_id ) {
	if ( $user_id || empty( $_SERVER['HTTP_X_SEOHOUSE_AUTH'] ) ) {
		return $user_id;
	}
	$uri = (string) ( $_SERVER['REQUEST_URI'] ?? '' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	if ( false === strpos( $uri, '/' . rest_get_url_prefix() . '/' ) && false === strpos( $uri, 'rest_route=' ) ) {
		return $user_id;
	}
	if ( ! is_ssl() && 'local' !== wp_get_environment_type() ) {
		sh_remote_auth_problem( 'not_https' );
		return $user_id;
	}
	if ( ! wp_is_application_passwords_available() ) {
		sh_remote_auth_problem( 'unavailable' );
		return $user_id;
	}
	$raw = trim( wp_unslash( (string) $_SERVER['HTTP_X_SEOHOUSE_AUTH'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	if ( 0 === stripos( $raw, 'basic ' ) ) {
		$raw = (string) base64_decode( substr( $raw, 6 ), true ); // the Basic form works too
	} elseif ( 0 === stripos( $raw, 'bearer ' ) ) {
		$raw = trim( substr( $raw, 7 ) ); // a prefix left in the secret's settings
	}
	if ( false === strpos( $raw, ':' ) ) {
		sh_remote_auth_problem( 'format' );
		return $user_id;
	}
	[ $login, $pass ] = explode( ':', $raw, 2 );
	$user             = wp_authenticate_application_password( null, trim( $login ), trim( $pass ) );
	if ( $user instanceof WP_User ) {
		return $user->ID;
	}
	sh_remote_auth_problem( 'invalid' ); // one answer for a wrong password and an unusable account (no login confirmation)
	return $user_id;
}

/**
 * A request that sent X-SeoHouse-Auth and was not accepted gets the reason instead of a bare 401. The
 * reasons never tell whether a username exists (wrong user and wrong password read the same).
 */
add_filter(
	'rest_authentication_errors',
	static function ( $result ) {
		if ( null !== $result ) {
			return $result;
		}
		get_current_user_id(); // make sure the header was looked at
		$problem = sh_remote_auth_problem();
		if ( '' === $problem || is_user_logged_in() ) {
			return $result;
		}
		$why = array(
			'not_https'         => 'وصل الطلب إلى ووردبريس على أنه ليس HTTPS، فلا تُقبل كلمات مرور التطبيقات.',
			'unavailable'       => 'كلمات مرور التطبيقات غير متاحة على الموقع (عطّلتها إضافة أمان أو إعداد في الاستضافة).',
			'format'            => 'قيمة X-SeoHouse-Auth ليست بالشكل اسم-المستخدم:كلمة-مرور-التطبيق.',
			'disabled_for_user' => 'كلمات مرور التطبيقات معطلة لهذا الحساب.',
			'invalid'           => 'اسم المستخدم أو كلمة مرور التطبيق غير صحيحين، أو أُلغيت كلمة المرور.',
		);
		return new WP_Error( 'sh_auth_' . $problem, $why[ $problem ] ?? $problem, array( 'status' => 401 ) );
	},
	99
);

function sh_remote_routes(): void {
	register_rest_route(
		SH_REMOTE_NS,
		'/ping',
		array(
			'methods'             => 'GET',
			'callback'            => static fn() => new WP_REST_Response(
				array(
					'ok'          => true,
					'auth_header' => ! empty( $_SERVER['HTTP_X_SEOHOUSE_AUTH'] ),
					'signed_in'   => is_user_logged_in(),
				)
			),
			'permission_callback' => '__return_true',
		)
	);
	register_rest_route(
		SH_REMOTE_NS,
		'/site',
		array(
			'methods'             => 'GET',
			'callback'            => 'sh_remote_site',
			'permission_callback' => static fn() => current_user_can( 'manage_options' ),
		)
	);
	$kind = array(
		'kind' => array(
			'required' => true,
			'type'     => 'string',
			'enum'     => array( 'theme', 'core' ),
		),
	);
	register_rest_route(
		SH_REMOTE_NS,
		'/deploy',
		array(
			'methods'             => 'POST',
			'callback'            => 'sh_remote_deploy',
			'permission_callback' => 'sh_remote_can_deploy',
			'args'                => $kind + array(
				'sha256' => array(
					'required' => true,
					'type'     => 'string',
					'pattern'  => '^[0-9a-fA-F]{64}$',
				),
			),
		)
	);
	register_rest_route(
		SH_REMOTE_NS,
		'/rollback',
		array(
			'methods'             => 'POST',
			'callback'            => 'sh_remote_rollback',
			'permission_callback' => 'sh_remote_can_deploy',
			'args'                => $kind,
		)
	);
	register_rest_route(
		SH_REMOTE_NS,
		'/recount',
		array(
			'methods'             => 'POST',
			'callback'            => 'sh_remote_recount',
			'permission_callback' => static fn() => current_user_can( 'manage_categories' ),
		)
	);
	register_rest_route(
		SH_REMOTE_NS,
		'/writers',
		array(
			'methods'             => 'GET',
			'callback'            => 'sh_remote_writers',
			'permission_callback' => static fn() => current_user_can( 'edit_others_posts' ) && current_user_can( 'list_users' ),
		)
	);
	register_rest_route(
		SH_REMOTE_NS,
		'/writers/link',
		array(
			'methods'             => 'POST',
			'callback'            => 'sh_remote_writer_link',
			'permission_callback' => static fn( WP_REST_Request $r ) => current_user_can( 'edit_post', (int) $r['member'] ) && current_user_can( 'list_users' ),
			'args'                => array(
				'member' => array(
					'required' => true,
					'type'     => 'integer',
				),
				'user'   => array(
					'required' => true,
					'type'     => 'integer',
				),
			),
		)
	);
	register_rest_route(
		SH_REMOTE_NS,
		'/writers/assign',
		array(
			'methods'             => 'POST',
			'callback'            => 'sh_remote_writer_assign',
			'permission_callback' => static fn() => current_user_can( 'edit_others_posts' ) && current_user_can( 'edit_published_posts' ) && current_user_can( 'list_users' ),
			'args'                => array(
				'user'    => array(
					'required' => true,
					'type'     => 'integer',
				),
				'dry_run' => array(
					'type'    => 'boolean',
					'default' => true,
				),
			),
		)
	);
}

function sh_remote_can_deploy( WP_REST_Request $r ): bool {
	if ( 'theme' === $r['kind'] ) {
		return current_user_can( 'install_themes' ) && current_user_can( 'update_themes' ) && current_user_can( 'switch_themes' );
	}
	return current_user_can( 'install_plugins' ) && current_user_can( 'update_plugins' ) && current_user_can( 'activate_plugins' );
}

/** Slug, folder and main file of a package kind. */
function sh_remote_target( string $kind ): array {
	return 'theme' === $kind
		? array(
			'slug' => 'seohouse',
			'dir'  => get_theme_root() . '/seohouse',
			'main' => 'style.css',
		)
		: array(
			'slug' => 'seohouse-core',
			'dir'  => WP_PLUGIN_DIR . '/seohouse-core',
			'main' => 'seohouse-core.php',
		);
}

function sh_remote_versions(): array {
	$theme = wp_get_theme( 'seohouse' );
	return array(
		'theme'        => $theme->exists() ? (string) $theme->get( 'Version' ) : null,
		'core'         => defined( 'SH_CORE_VERSION' ) ? SH_CORE_VERSION : null,
		'active_theme' => get_stylesheet(),
	);
}

function sh_remote_site(): WP_REST_Response {
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	$plugins = array();
	foreach ( get_plugins() as $file => $p ) {
		if ( is_plugin_active( $file ) ) {
			$plugins[ $file ] = $p['Version'];
		}
	}
	return new WP_REST_Response(
		sh_remote_versions() + array(
			'home'             => home_url( '/' ),
			'wordpress'        => get_bloginfo( 'version' ),
			'php'              => PHP_VERSION,
			'locale'           => get_locale(),
			'search_visible'   => (bool) get_option( 'blog_public' ), // «إعدادات ← القراءة ← منع محركات البحث» unchecked
			'permalinks'       => (string) get_option( 'permalink_structure' ),
			'file_mods'        => ! ( defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS ),
			'filesystem'       => get_filesystem_method(),
			'active_plugins'   => $plugins,
			'backups'          => array(
				'theme' => array_map( 'basename', sh_remote_backups( 'theme' ) ),
				'core'  => array_map( 'basename', sh_remote_backups( 'core' ) ),
			),
			'indexing'         => sh_remote_indexing(),
		)
	);
}

/** Settings and content that decide what search engines see (read-only report). */
function sh_remote_indexing(): array {
	$path  = static fn( $id ) => $id ? wp_make_link_relative( (string) get_permalink( (int) $id ) ) : null;
	$cats  = array();
	foreach ( get_terms(
		array(
			'taxonomy'   => 'category',
			'hide_empty' => false,
		)
	) as $t ) {
		$real   = (int) ( new WP_Query(
			array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'cat'            => $t->term_id,
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'no_found_rows'  => false,
			)
		) )->found_posts;
		$cats[] = array(
			'slug'      => $t->slug,
			'stored'    => (int) $t->count,
			'published' => $real,
		);
	}
	$cases = array();
	foreach ( get_posts(
		array(
			'post_type'      => 'case_study',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		)
	) as $c ) {
		$cases[] = array(
			'title' => $c->post_title,
			'path'  => rawurldecode( wp_make_link_relative( (string) get_permalink( $c ) ) ),
		);
	}
	return array(
		'environment'      => wp_get_environment_type(), // anything but production noindexes when Rank Math is off (seo.php)
		'review_copy'      => function_exists( 'sh_seo_is_review_copy' ) && sh_seo_is_review_copy(), // whole site noindex
		'show_on_front'    => (string) get_option( 'show_on_front' ),
		'front_page'       => $path( get_option( 'page_on_front' ) ),
		'posts_page'       => $path( get_option( 'page_for_posts' ) ),
		'rank_math'        => function_exists( 'sh_rankmath_active' ) && sh_rankmath_active(),
		'rank_math_moved'  => function_exists( 'sh_rankmath_migrated' ) && sh_rankmath_migrated(),
		'robots_txt_file'  => file_exists( ABSPATH . 'robots.txt' ),
		'categories'       => $cats,
		'case_studies'     => $cases,
		'redirects'        => count( (array) sh_core_option( 'sh_redirects', array() ) ),
	);
}

/** Backup ZIPs of a kind, newest first. */
function sh_remote_backups( string $kind ): array {
	$all = glob( sh_remote_backup_root() . '/' . sh_remote_target( $kind )['slug'] . '-[0-9]*.zip' ) ?: array(); // seohouse-2…, not seohouse-core-…
	rsort( $all );
	return $all;
}

/**
 * Backups live outside wp-content/upgrade (WordPress empties that folder on every install), as ZIP files
 * so no copy of the code can run from another path. The folder refuses web access on Apache/LiteSpeed,
 * and each name carries a random part.
 */
function sh_remote_backup_root(): string {
	return WP_CONTENT_DIR . '/seohouse-backups';
}

/** Check an uploaded package: one top folder named after the target, with its main file. */
function sh_remote_check_zip( string $file, array $target ) {
	if ( ! class_exists( 'ZipArchive' ) ) {
		return new WP_Error( 'sh_zip', 'ZipArchive غير متاح على الخادم، فلا يمكن فحص الحزمة ولا أخذ نسخة احتياطية.', array( 'status' => 500 ) );
	}
	$zip = new ZipArchive();
	if ( true !== $zip->open( $file ) ) {
		return new WP_Error( 'sh_zip', 'الملف ليس ZIP صالحًا.', array( 'status' => 400 ) );
	}
	$main = false;
	for ( $i = 0; $i < $zip->numFiles; $i++ ) {
		$name = (string) $zip->getNameIndex( $i );
		if ( 0 !== strpos( $name, $target['slug'] . '/' ) || false !== strpos( $name, '..' ) ) {
			$zip->close();
			return new WP_Error( 'sh_zip', 'الحزمة يجب أن تحوي مجلد «' . $target['slug'] . '» فقط.', array( 'status' => 400 ) );
		}
		$main = $main || $target['slug'] . '/' . $target['main'] === $name;
	}
	$zip->close();
	return $main ? true : new WP_Error( 'sh_zip', 'الملف الرئيسي ' . $target['main'] . ' غير موجود في الحزمة.', array( 'status' => 400 ) );
}

function sh_remote_fs() {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	if ( 'direct' !== get_filesystem_method() || ! WP_Filesystem() ) {
		return new WP_Error( 'sh_fs', 'لا يمكن الكتابة في ملفات الموقع مباشرة (طريقة الملفات: ' . get_filesystem_method() . ').', array( 'status' => 500 ) );
	}
	return true;
}

/** Save the live folder as a dated ZIP and keep the latest few. Returns the ZIP path ('' when nothing is installed yet). */
function sh_remote_backup( string $kind ) {
	$target = sh_remote_target( $kind );
	if ( ! is_dir( $target['dir'] ) ) {
		return '';
	}
	$root = sh_remote_backup_root();
	wp_mkdir_p( $root );
	if ( ! file_exists( $root . '/.htaccess' ) ) {
		file_put_contents( $root . '/.htaccess', "Require all denied\n<IfModule !mod_authz_core.c>\nDeny from all\n</IfModule>\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		file_put_contents( $root . '/index.php', "<?php\n// Silence is golden.\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	}
	$v    = sh_remote_versions()[ 'theme' === $kind ? 'theme' : 'core' ];
	$dest = $root . '/' . $target['slug'] . '-' . gmdate( 'Ymd-His' ) . '-' . preg_replace( '/[^0-9.]/', '', (string) $v ) . '-' . wp_generate_password( 8, false ) . '.zip';
	$zip  = new ZipArchive();
	$ok   = true === $zip->open( $dest, ZipArchive::CREATE | ZipArchive::OVERWRITE );
	if ( $ok ) {
		$base = dirname( $target['dir'] ) . '/';
		$it   = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $target['dir'], FilesystemIterator::SKIP_DOTS ) );
		foreach ( $it as $f ) {
			if ( $f->isFile() ) {
				$zip->addFile( $f->getPathname(), str_replace( '\\', '/', substr( $f->getPathname(), strlen( $base ) ) ) );
			}
		}
		$ok = $zip->close();
	}
	if ( ! $ok ) {
		return new WP_Error( 'sh_backup', 'تعذر أخذ نسخة احتياطية، فلم يُنشر شيء.', array( 'status' => 500 ) );
	}
	foreach ( array_slice( sh_remote_backups( $kind ), SH_REMOTE_KEEP_BACKUP ) as $old ) {
		wp_delete_file( $old );
	}
	return $dest;
}

/** Install a ZIP (upload or backup) over the live copy as «استبدال الحالي بالمرفوع» does, then activate. */
function sh_remote_install( string $kind, string $zip ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
	require_once ABSPATH . 'wp-admin/includes/theme.php';
	$package = wp_tempnam( sh_remote_target( $kind )['slug'] . '.zip' );
	copy( $zip, $package );
	$skin     = new WP_Ajax_Upgrader_Skin();
	$upgrader = 'theme' === $kind ? new Theme_Upgrader( $skin ) : new Plugin_Upgrader( $skin );
	$result   = $upgrader->install( $package, array( 'overwrite_package' => true ) );
	wp_delete_file( $package );
	if ( ! $result || is_wp_error( $result ) ) {
		$err = is_wp_error( $result ) ? $result->get_error_message() : implode( ' ', (array) $skin->get_error_messages() );
		return new WP_Error( 'sh_install', 'فشل التثبيت: ' . $err, array( 'status' => 500 ) );
	}
	if ( 'theme' === $kind ) {
		wp_clean_themes_cache();
		if ( 'seohouse' !== get_stylesheet() ) {
			switch_theme( 'seohouse' );
		}
	} elseif ( ! is_plugin_active( 'seohouse-core/seohouse-core.php' ) ) {
		$act = activate_plugin( 'seohouse-core/seohouse-core.php' );
		if ( is_wp_error( $act ) ) {
			return new WP_Error( 'sh_activate', 'ثُبّتت الإضافة لكن تفعيلها فشل: ' . $act->get_error_message(), array( 'status' => 500 ) );
		}
	}
	return true;
}

/** Empty the page caches of the common caching plugins and PHP's opcode cache. */
function sh_remote_purge(): array {
	$done = array( 'object-cache' );
	wp_cache_flush();
	if ( function_exists( 'opcache_reset' ) ) {
		@opcache_reset(); // phpcs:ignore WordPress.PHP.NoSilencedErrors -- may be restricted by the host
		$done[] = 'opcache';
	}
	if ( has_action( 'litespeed_purge_all' ) ) {
		do_action( 'litespeed_purge_all' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals -- LiteSpeed Cache hook.
		$done[] = 'litespeed';
	}
	if ( function_exists( 'rocket_clean_domain' ) ) {
		rocket_clean_domain();
		$done[] = 'wp-rocket';
	}
	if ( function_exists( 'w3tc_flush_all' ) ) {
		w3tc_flush_all();
		$done[] = 'w3-total-cache';
	}
	if ( function_exists( 'wp_cache_clear_cache' ) ) {
		wp_cache_clear_cache();
		$done[] = 'wp-super-cache';
	}
	if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
		sg_cachepress_purge_cache();
		$done[] = 'siteground';
	}
	if ( class_exists( 'autoptimizeCache' ) && method_exists( 'autoptimizeCache', 'clearall' ) ) {
		autoptimizeCache::clearall();
		$done[] = 'autoptimize';
	}
	if ( has_action( 'wpfc_clear_all_cache' ) ) {
		do_action( 'wpfc_clear_all_cache', true ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals -- WP Fastest Cache hook.
		$done[] = 'wp-fastest-cache';
	}
	return $done;
}

/** Homepage status after a change (null when the site cannot request itself). */
function sh_remote_home_status(): ?int {
	$r = wp_remote_get(
		add_query_arg( 'sh-deploy-check', time(), home_url( '/' ) ),
		array(
			'timeout'     => 20,
			'redirection' => 3,
			'sslverify'   => apply_filters( 'https_local_ssl_verify', false ), // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals -- core filter.
		)
	);
	return is_wp_error( $r ) ? null : (int) wp_remote_retrieve_response_code( $r );
}

function sh_remote_deploy( WP_REST_Request $r ) {
	$kind   = (string) $r['kind'];
	$target = sh_remote_target( $kind );
	$files  = $r->get_file_params();
	$file   = $files['package'] ?? null;
	if ( ! $file || UPLOAD_ERR_OK !== (int) $file['error'] || ! is_uploaded_file( $file['tmp_name'] ) ) {
		return new WP_Error( 'sh_upload', 'لم يصل ملف الحزمة (الحقل package).', array( 'status' => 400 ) );
	}
	if ( (int) $file['size'] > SH_REMOTE_MAX_BYTES ) {
		return new WP_Error( 'sh_upload', 'الحزمة أكبر من المسموح.', array( 'status' => 413 ) );
	}
	if ( ! hash_equals( strtolower( (string) $r['sha256'] ), hash_file( 'sha256', $file['tmp_name'] ) ) ) {
		return new WP_Error( 'sh_sha', 'بصمة الملف لا تطابق sha256 المرسلة.', array( 'status' => 400 ) );
	}
	$ok = sh_remote_check_zip( $file['tmp_name'], $target );
	if ( is_wp_error( $ok ) ) {
		return $ok;
	}
	$fs = sh_remote_fs();
	if ( is_wp_error( $fs ) ) {
		return $fs;
	}
	$before = sh_remote_versions()[ 'theme' === $kind ? 'theme' : 'core' ];
	$backup = sh_remote_backup( $kind );
	if ( is_wp_error( $backup ) ) {
		return $backup;
	}
	$ok = sh_remote_install( $kind, $file['tmp_name'] );
	if ( is_wp_error( $ok ) ) {
		if ( $backup && ! file_exists( $target['dir'] . '/' . $target['main'] ) ) {
			sh_remote_install( $kind, $backup ); // the live copy is gone: put the previous one back
		}
		return $ok;
	}
	if ( 'core' === $kind && function_exists( 'sh_core_protect_files' ) ) {
		sh_core_protect_files(); // the folder was replaced: write its .htaccess again
	}
	$purged = sh_remote_purge();
	$status = sh_remote_home_status();
	$rolled = false;
	$failed = null;
	if ( null !== $status && $status >= 500 && $backup ) {
		$failed = $status;
		$rolled = ! is_wp_error( sh_remote_install( $kind, $backup ) );
		sh_remote_purge();
		$status = sh_remote_home_status();
	}
	return new WP_REST_Response(
		array(
			'kind'        => $kind,
			'before'      => $before,
			'installed'   => sh_remote_file_version( $target ),
			'active'      => 'theme' === $kind ? get_stylesheet() : ( is_plugin_active( 'seohouse-core/seohouse-core.php' ) ? 'seohouse-core' : '' ),
			'backup'      => $backup ? basename( $backup ) : '',
			'purged'      => $purged,
			'home_status' => $status,
			'rolled_back' => $rolled,
			'message'     => $failed ? 'الصفحة الرئيسية أعادت خطأ ' . $failed . ' بعد التثبيت، فأُعيدت النسخة السابقة.' : '',
		),
		$failed ? 500 : 200
	);
}

/** Version written in the installed main file (the running Core constant is still the old one). */
function sh_remote_file_version( array $target ): string {
	$data = get_file_data( $target['dir'] . '/' . $target['main'], array( 'Version' => 'Version' ) );
	return (string) ( $data['Version'] ?? '' );
}

function sh_remote_rollback( WP_REST_Request $r ) {
	$kind   = (string) $r['kind'];
	$backup = sh_remote_backups( $kind )[0] ?? '';
	if ( ! $backup ) {
		return new WP_Error( 'sh_backup', 'لا توجد نسخة احتياطية.', array( 'status' => 404 ) );
	}
	$fs = sh_remote_fs();
	if ( is_wp_error( $fs ) ) {
		return $fs;
	}
	$ok = sh_remote_install( $kind, $backup );
	if ( is_wp_error( $ok ) ) {
		return $ok;
	}
	wp_delete_file( $backup ); // used: a second rollback goes one step further back
	return new WP_REST_Response(
		array(
			'kind'        => $kind,
			'restored'    => basename( $backup ),
			'version'     => sh_remote_file_version( sh_remote_target( $kind ) ),
			'purged'      => sh_remote_purge(),
			'home_status' => sh_remote_home_status(),
		)
	);
}

/** Recount the published articles of every category and tag (what «منع فهرسة الأقسام الفارغة» relies on). */
function sh_remote_recount(): WP_REST_Response {
	$done = array();
	foreach ( array( 'category', 'post_tag' ) as $tax ) {
		$ids = get_terms(
			array(
				'taxonomy'   => $tax,
				'hide_empty' => false,
				'fields'     => 'ids',
			)
		);
		if ( $ids && ! is_wp_error( $ids ) ) {
			wp_update_term_count_now( $ids, $tax );
			$done[ $tax ] = count( $ids );
		}
	}
	return new WP_REST_Response( array( 'recounted' => $done ) );
}

function sh_remote_writers(): WP_REST_Response {
	$users = array();
	foreach ( get_users( array( 'capability' => array( 'edit_posts' ) ) ) as $u ) {
		$users[] = array(
			'id'      => (int) $u->ID,
			'login'   => $u->user_login,
			'name'    => $u->display_name,
			'roles'   => array_values( $u->roles ),
			'posts'   => (int) count_user_posts( $u->ID, 'post' ),
			'profile' => sh_core_member_for_user( (int) $u->ID ),
		);
	}
	$members = array();
	foreach ( get_posts(
		array(
			'post_type'      => 'team_member',
			'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		)
	) as $m ) {
		$members[] = array(
			'id'     => (int) $m->ID,
			'name'   => $m->post_title,
			'slug'   => $m->post_name,
			'status' => $m->post_status,
			'url'    => get_permalink( $m ),
			'user'   => (int) get_post_meta( $m->ID, 'author', true ),
		);
	}
	$posts = array();
	foreach ( get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => array( 'publish', 'draft', 'pending', 'future', 'private' ),
			'posts_per_page' => -1,
		)
	) as $p ) {
		$posts[] = array(
			'id'            => (int) $p->ID,
			'title'         => $p->post_title,
			'status'        => $p->post_status,
			'author'        => (int) $p->post_author,
			'author_member' => (int) get_post_meta( $p->ID, 'author_member', true ),
			'credited'      => sh_core_member_for_post( $p ),
		);
	}
	return new WP_REST_Response( compact( 'users', 'members', 'posts' ) );
}

function sh_remote_writer_link( WP_REST_Request $r ) {
	$member = (int) $r['member'];
	$user   = (int) $r['user'];
	if ( 'team_member' !== get_post_type( $member ) ) {
		return new WP_Error( 'sh_member', 'ليس عضوًا في فريق العمل.', array( 'status' => 404 ) );
	}
	if ( $user && ! user_can( $user, 'edit_posts' ) ) {
		return new WP_Error( 'sh_user', 'الحساب غير موجود أو لا يستطيع الكتابة.', array( 'status' => 400 ) );
	}
	if ( function_exists( 'update_field' ) ) {
		update_field( 'author', $user ? $user : '', $member ); // ACF: «حساب الكاتب في ووردبريس»
	} else {
		update_post_meta( $member, 'author', $user ? $user : '' );
	}
	return new WP_REST_Response(
		array(
			'member' => $member,
			'user'   => $user,
			'url'    => get_permalink( $member ),
		)
	);
}

function sh_remote_writer_assign( WP_REST_Request $r ) {
	$user = (int) $r['user'];
	if ( ! $user || ! user_can( $user, 'edit_posts' ) ) {
		return new WP_Error( 'sh_user', 'الحساب غير موجود أو لا يستطيع الكتابة.', array( 'status' => 400 ) );
	}
	$dry     = (bool) $r['dry_run'];
	$changed = array();
	foreach ( get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => array( 'publish', 'draft', 'pending', 'future', 'private' ),
			'posts_per_page' => -1,
		)
	) as $p ) {
		$override = (int) get_post_meta( $p->ID, 'author_member', true );
		if ( (int) $p->post_author === $user && ! $override ) {
			continue;
		}
		$changed[] = array(
			'id'            => (int) $p->ID,
			'title'         => $p->post_title,
			'author_before' => (int) $p->post_author,
			'override'      => $override,
		);
		if ( ! $dry ) {
			// only the author changes: no new modified date, no slug or content change
			global $wpdb;
			$wpdb->update( $wpdb->posts, array( 'post_author' => $user ), array( 'ID' => $p->ID ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			clean_post_cache( $p->ID );
			if ( $override ) {
				function_exists( 'update_field' ) ? update_field( 'author_member', '', $p->ID ) : delete_post_meta( $p->ID, 'author_member' );
			}
		}
	}
	return new WP_REST_Response(
		array(
			'user'     => $user,
			'profile'  => sh_core_member_for_user( $user ),
			'dry_run'  => $dry,
			'articles' => $changed,
		)
	);
}
