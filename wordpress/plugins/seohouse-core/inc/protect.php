<?php
/**
 * Core's data files are read by PHP only (2.8.0). The content pack (JSON with draft articles and pages not
 * yet published), the field definitions and the docs inside the plugin folder are refused over the web on
 * Apache / LiteSpeed by a .htaccess the plugin writes into its own folder (release ZIPs carry no dot-files,
 * and publishing a new version replaces the folder, so it is written again when missing).
 *
 * The rules are checked once after writing: if the server answers the plugin's own stylesheet with an
 * error (a host that does not allow these directives), the file is removed and not written again for a
 * week. On nginx the file has no effect; the host's configuration would be needed.
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

function sh_core_protect_files(): void {
	$file = SH_CORE_DIR . '.htaccess';
	if ( file_exists( $file ) || get_transient( 'sh_core_protect_failed' ) || ! wp_is_writable( SH_CORE_DIR ) ) {
		return;
	}
	$rules = "# SEO House Core: data files are read by PHP only\n"
		. "<FilesMatch \"\\.(json|md|txt|csv|log|zip)$\">\n"
		. "\t<IfModule mod_authz_core.c>\n\t\tRequire all denied\n\t</IfModule>\n"
		. "\t<IfModule !mod_authz_core.c>\n\t\tOrder allow,deny\n\t\tDeny from all\n\t</IfModule>\n"
		. "</FilesMatch>\n";
	if ( false === file_put_contents( $file, $rules ) ) { // phpcs:ignore WordPress.WP.AlternativeFunctions
		return;
	}
	$r = wp_remote_get(
		SH_CORE_URL . 'assets/field-rows.css?ver=' . rawurlencode( SH_CORE_VERSION ),
		array(
			'timeout'   => 10,
			'sslverify' => false,
		)
	);
	if ( ! is_wp_error( $r ) && wp_remote_retrieve_response_code( $r ) >= 500 ) {
		wp_delete_file( $file );
		set_transient( 'sh_core_protect_failed', 1, WEEK_IN_SECONDS );
	}
}
add_action( 'admin_init', 'sh_core_protect_files' );
