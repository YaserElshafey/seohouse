<?php
/**
 * Redirects for published URLs of the previous site (إعدادات سيو هاوس ← التحويلات).
 *
 * - A redirect applies only when the request would otherwise be a 404, so it never hides a
 *   published page that now lives on the same path.
 * - Targets are chosen pages (page_link), so they follow slug changes.
 * - Only equivalent destinations belong here (same topic and purpose); the list is reviewed by
 *   the site owner. `wp seohouse launch-check` verifies every published URL before launch.
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

/** Normalised site-relative path (works when WordPress lives in a folder such as /new/). */
function sh_redirect_path( string $url ): string {
	return sh_core_site_path( $url );
}

add_action(
	'template_redirect',
	static function () {
		if ( ! is_404() || ! function_exists( 'get_field' ) ) {
			return;
		}
		$req = sh_core_request_path();
		foreach ( (array) sh_core_option( 'sh_redirects', array() ) as $r ) {
			if ( empty( $r['from'] ) || empty( $r['to'] ) || sh_redirect_path( (string) $r['from'] ) !== $req ) {
				continue;
			}
			$to = is_numeric( $r['to'] ) ? get_permalink( (int) $r['to'] ) : (string) $r['to'];
			if ( $to && sh_redirect_path( $to ) !== $req ) {
				wp_safe_redirect( $to, 301, 'SEO House Core' );
				exit;
			}
		}
	},
	1
);
