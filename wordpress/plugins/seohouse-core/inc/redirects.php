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

/*
 * Menu items pointing to content that is not published (the legal pages kept as drafts until
 * their text is approved) are left out for visitors, so no menu ever links to a 404. They show
 * again by themselves once the page is published. The menu editor still lists them.
 */
add_filter(
	'wp_get_nav_menu_items',
	static function ( $items ) {
		if ( is_admin() || ! is_array( $items ) ) {
			return $items;
		}
		$drop = array();
		foreach ( $items as $i => $item ) {
			if ( 'post_type' === ( $item->type ?? '' ) && (int) $item->object_id && 'publish' !== get_post_status( (int) $item->object_id ) ) {
				$drop[] = (int) $item->ID;
				unset( $items[ $i ] );
			}
		}
		if ( $drop ) {
			// children of a hidden item go with it
			foreach ( $items as $i => $item ) {
				if ( in_array( (int) $item->menu_item_parent, $drop, true ) ) {
					unset( $items[ $i ] );
				}
			}
		}
		return array_values( $items );
	},
	20
);
