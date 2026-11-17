<?php
/**
 * Only the files of this release are the theme.
 *
 * theme-files.json (written by tools/theme-manifest.py when the zip is built) lists every file the
 * release ships. A file in the theme folder that is not listed is a leftover: a migration plugin
 * (e.g. All-in-One WP Migration) or an FTP copy adds files over the folder and never removes the
 * previous theme's ones, such as an old front-page.php that calls functions this release does not
 * have. Such files are:
 *
 * - never used as templates: they are left out of WordPress's template hierarchy and of the page
 *   template list, so the release's own template answers instead;
 * - reported to administrators, with a button that moves them out of the theme folder into one
 *   archive in uploads/seohouse-backups (nothing is deleted without a copy; listed files are never
 *   touched). WP-CLI: wp seohouse-theme leftovers [--move].
 *
 * Uploading the theme zip with «استبدال الحالي بالمرفوع» replaces the whole folder, so it leaves
 * no leftovers either.
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

/** Release files: relative path => sha1. Empty array when the manifest is missing (guard off). */
function sh_theme_release_files(): array {
	static $files = null;
	if ( null === $files ) {
		$m     = json_decode( (string) @file_get_contents( SH_THEME_DIR . '/theme-files.json' ), true ); // phpcs:ignore
		$files = array();
		foreach ( (array) ( $m['files'] ?? array() ) as $f ) {
			if ( ! empty( $f['path'] ) ) {
				$files[ $f['path'] ] = (string) ( $f['sha1'] ?? '' );
			}
		}
	}
	return $files;
}

/**
 * Files in the theme folder that are not part of the release.
 *
 * @return array{leftovers:string[],changed:string[],missing:string[]}
 */
function sh_theme_file_check( bool $hashes = false ): array {
	$out   = array( 'leftovers' => array(), 'changed' => array(), 'missing' => array() );
	$files = sh_theme_release_files();
	if ( ! $files ) {
		return $out;
	}
	$base = trailingslashit( wp_normalize_path( SH_THEME_DIR ) );
	$seen = array();
	$it   = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( SH_THEME_DIR, FilesystemIterator::SKIP_DOTS ) );
	foreach ( $it as $f ) {
		if ( ! $f->isFile() ) {
			continue;
		}
		$rel = substr( wp_normalize_path( $f->getPathname() ), strlen( $base ) );
		if ( 'theme-files.json' === $rel ) {
			continue;
		}
		if ( ! isset( $files[ $rel ] ) ) {
			$out['leftovers'][] = $rel;
			continue;
		}
		$seen[ $rel ] = true;
		if ( $hashes && $files[ $rel ] && sha1_file( $f->getPathname() ) !== $files[ $rel ] ) {
			$out['changed'][] = $rel;
		}
	}
	$out['missing'] = array_values( array_diff( array_keys( $files ), array_keys( $seen ) ) );
	sort( $out['leftovers'] );
	return $out;
}

/** Is this template file (absolute or relative to the theme) a release file? */
function sh_theme_is_release_file( string $file ): bool {
	$files = sh_theme_release_files();
	if ( ! $files ) {
		return true;
	}
	$base = trailingslashit( wp_normalize_path( SH_THEME_DIR ) );
	$file = wp_normalize_path( $file );
	if ( str_starts_with( $file, $base ) ) {
		$file = substr( $file, strlen( $base ) );
	}
	return isset( $files[ ltrim( $file, '/' ) ] );
}

// leftovers are never chosen as templates
foreach ( array( 'index', '404', 'archive', 'author', 'category', 'tag', 'taxonomy', 'date', 'embed', 'home', 'frontpage', 'privacypolicy', 'page', 'paged', 'search', 'single', 'singular', 'attachment' ) as $sh_type ) {
	add_filter(
		"{$sh_type}_template_hierarchy",
		static function ( $templates ) {
			return array_values(
				array_filter(
					(array) $templates,
					static fn( $t ) => ! file_exists( SH_THEME_DIR . '/' . $t ) || sh_theme_is_release_file( $t )
				)
			);
		}
	);
}
unset( $sh_type );

add_filter(
	'template_include',
	static function ( $template ) {
		// last line of defence, e.g. a page whose stored template is a leftover file
		if ( $template && str_starts_with( wp_normalize_path( $template ), trailingslashit( wp_normalize_path( SH_THEME_DIR ) ) ) && ! sh_theme_is_release_file( $template ) ) {
			return SH_THEME_DIR . '/' . ( is_page() ? 'page.php' : 'index.php' );
		}
		return $template;
	},
	99
);

add_filter(
	'theme_templates',
	static function ( $templates ) {
		foreach ( array_keys( (array) $templates ) as $file ) {
			if ( ! sh_theme_is_release_file( (string) $file ) ) {
				unset( $templates[ $file ] );
			}
		}
		return $templates;
	}
);

/**
 * Moves the leftovers into one archive in uploads/seohouse-backups (or, without ZipArchive, into a
 * folder there with ".bak" added to each name so nothing in it can run), then removes them and the
 * folders they leave empty. Returns [moved count, where].
 */
function sh_theme_move_leftovers(): array {
	$left = sh_theme_file_check()['leftovers'];
	if ( ! $left ) {
		return array( 0, '' );
	}
	$up  = wp_upload_dir();
	$dir = trailingslashit( $up['basedir'] ) . 'seohouse-backups';
	wp_mkdir_p( $dir );
	if ( ! file_exists( $dir . '/index.php' ) ) {
		file_put_contents( $dir . '/index.php', "<?php // Silence.\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		file_put_contents( $dir . '/.htaccess', "Require all denied\nDeny from all\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	}
	$name = 'theme-leftovers-' . gmdate( 'Ymd-His' ) . '-' . wp_generate_password( 16, false );
	$kept = array();
	if ( class_exists( 'ZipArchive' ) ) {
		$zip  = new ZipArchive();
		$file = $dir . '/' . $name . '.zip';
		if ( true !== $zip->open( $file, ZipArchive::CREATE ) ) {
			return array( 0, '' );
		}
		foreach ( $left as $rel ) {
			if ( $zip->addFile( SH_THEME_DIR . '/' . $rel, 'seohouse/' . $rel ) ) {
				$kept[] = $rel;
			}
		}
		$zip->close();
		$where = $file;
	} else {
		$where = $dir . '/' . $name;
		foreach ( $left as $rel ) {
			wp_mkdir_p( dirname( $where . '/' . $rel ) );
			if ( copy( SH_THEME_DIR . '/' . $rel, $where . '/' . $rel . '.bak' ) ) {
				$kept[] = $rel;
			}
		}
	}
	$moved = 0;
	foreach ( $kept as $rel ) {
		if ( @unlink( SH_THEME_DIR . '/' . $rel ) ) { // phpcs:ignore
			++$moved;
		}
	}
	// folders left empty (deepest first)
	$dirs = array_unique( array_map( static fn( $r ) => dirname( $r ), $kept ) );
	usort( $dirs, static fn( $a, $b ) => substr_count( $b, '/' ) - substr_count( $a, '/' ) );
	foreach ( $dirs as $d ) {
		while ( '.' !== $d && '' !== $d ) {
			$abs = SH_THEME_DIR . '/' . $d;
			if ( ! is_dir( $abs ) || ( new FilesystemIterator( $abs ) )->valid() || ! @rmdir( $abs ) ) { // phpcs:ignore
				break;
			}
			$d = dirname( $d );
		}
	}
	update_option( 'sh_theme_leftovers_moved', array( 'time' => time(), 'count' => $moved, 'where' => str_replace( ABSPATH, '', $where ) ), false );
	return array( $moved, str_replace( ABSPATH, '', $where ) );
}

add_action(
	'admin_post_sh_theme_leftovers',
	static function () {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( '', 403 );
		}
		check_admin_referer( 'sh_theme_leftovers' );
		[ $n, $where ] = sh_theme_move_leftovers();
		wp_safe_redirect( add_query_arg( array( 'sh_leftovers_moved' => $n ), wp_get_referer() ? wp_get_referer() : admin_url( 'themes.php' ) ) );
		exit;
	}
);

add_action(
	'admin_notices',
	static function () {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( isset( $_GET['sh_leftovers_moved'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			$last = get_option( 'sh_theme_leftovers_moved' );
			echo '<div class="notice notice-success is-dismissible" id="sh-theme-leftovers-done"><p>' . esc_html( sprintf( 'نُقل %d ملفًا قديمًا من مجلد القالب إلى نسخة احتياطية: %s', (int) $_GET['sh_leftovers_moved'], $last['where'] ?? '' ) ) . '</p></div>'; // phpcs:ignore WordPress.Security.NonceVerification
		}
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		if ( $screen && ! in_array( $screen->id, array( 'dashboard', 'themes', 'toplevel_page_seohouse-settings', 'seohouse_page_seohouse-content-setup' ), true ) && ! str_contains( (string) $screen->id, 'seohouse' ) ) {
			return;
		}
		$c = sh_theme_file_check();
		if ( ! $c['leftovers'] ) {
			return;
		}
		echo '<div class="notice notice-warning" id="sh-theme-leftovers"><p><strong>' . esc_html( sprintf( 'في مجلد قالب سيو هاوس %d ملفًا ليست من إصدار القالب %s', count( $c['leftovers'] ), SH_THEME_VERSION ) ) . '</strong> — ' . esc_html__( 'بقايا قالب سابق (تبقى عادة بعد النقل بإضافة نقل أو بالنسخ عبر FTP). لا يستخدمها الموقع قوالبَ، لكن بعضها قد يُقرأ من المتصفح. النقل يحفظها في نسخة مضغوطة داخل uploads/seohouse-backups ثم يحذفها من مجلد القالب؛ ملفات الإصدار لا تُلمس.', 'seohouse' ) . '</p>';
		echo '<details><summary>' . esc_html__( 'الملفات', 'seohouse' ) . '</summary><ul dir="ltr" style="text-align:left;columns:2">';
		foreach ( $c['leftovers'] as $f ) {
			echo '<li><code>' . esc_html( $f ) . '</code></li>';
		}
		echo '</ul></details>';
		echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '"><input type="hidden" name="action" value="sh_theme_leftovers">';
		wp_nonce_field( 'sh_theme_leftovers' );
		echo '<p><button class="button button-primary" onclick="return confirm(\'' . esc_js( __( 'نقل الملفات القديمة خارج مجلد القالب؟ تُحفظ نسخة منها أولًا.', 'seohouse' ) ) . '\')">' . esc_html__( 'نقل الملفات القديمة خارج مجلد القالب', 'seohouse' ) . '</button></p></form></div>';
	}
);

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	/**
	 * Lists (and with --move moves out) files in the theme folder that are not part of the release.
	 *
	 * ## OPTIONS
	 *
	 * [--move]
	 * : Move them into an archive in uploads/seohouse-backups.
	 */
	WP_CLI::add_command(
		'seohouse-theme leftovers',
		static function ( $args, $assoc ) {
			$c = sh_theme_file_check( true );
			foreach ( $c['leftovers'] as $f ) {
				WP_CLI::log( 'leftover  ' . $f );
			}
			foreach ( $c['changed'] as $f ) {
				WP_CLI::log( 'changed   ' . $f );
			}
			foreach ( $c['missing'] as $f ) {
				WP_CLI::log( 'missing   ' . $f );
			}
			if ( ! empty( $assoc['move'] ) && $c['leftovers'] ) {
				[ $n, $where ] = sh_theme_move_leftovers();
				WP_CLI::success( "moved $n → $where" );
				return;
			}
			WP_CLI::success( count( $c['leftovers'] ) . ' leftover(s), ' . count( $c['changed'] ) . ' changed, ' . count( $c['missing'] ) . ' missing' );
		}
	);
}
