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

/** Normalised path: decoded, leading and trailing slash, no query string. */
function sh_redirect_path( string $url ): string {
	$path = (string) wp_parse_url( $url, PHP_URL_PATH );
	$path = '/' . trim( rawurldecode( $path ), '/' ) . '/';
	return '//' === $path ? '/' : $path;
}

add_action(
	'template_redirect',
	static function () {
		if ( ! is_404() || ! function_exists( 'get_field' ) ) {
			return;
		}
		$req = sh_redirect_path( (string) ( $_SERVER['REQUEST_URI'] ?? '' ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- compared only.
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
