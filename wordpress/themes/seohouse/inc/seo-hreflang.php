<?php
/**
 * hreflang for the regional versions of the SEO service (Rank Math does not print hreflang).
 *
 * The group: /services/seo/ (ar + x-default) and its Saudi, Egyptian and UAE versions. Pages are
 * found by path, links come from get_permalink() of their IDs. The same five lines are printed on
 * each of the four pages and nowhere else. Nothing is printed unless every page of the group is
 * published, public, indexable and canonical to itself (a page with a custom Rank Math canonical or
 * noindex would make the set invalid; such a conflict is left for the editor, the canonical is never
 * changed here).
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

/** hreflang => page path, in output order. */
const SH_HREFLANG_GROUP = array(
	'ar-SA'     => 'services/seo/ksa',
	'ar-EG'     => 'services/seo/egypt',
	'ar-AE'     => 'services/seo/uae',
	'ar'        => 'services/seo',
	'x-default' => 'services/seo',
);

/**
 * The group's links, or an empty array with the reason when it cannot be printed.
 *
 * @return array{links:array<string,string>,ids:int[],problem:string}
 */
function sh_hreflang_group(): array {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$links = array();
	$ids   = array();
	foreach ( SH_HREFLANG_GROUP as $lang => $path ) {
		$page = get_page_by_path( $path, OBJECT, 'page' );
		if ( ! $page || 'publish' !== $page->post_status || '' !== $page->post_password ) {
			return $cache = array( 'links' => array(), 'ids' => array(), 'problem' => sprintf( 'page /%s/ is not published', $path ) );
		}
		$robots = (array) get_post_meta( $page->ID, 'rank_math_robots', true );
		if ( in_array( 'noindex', $robots, true ) ) {
			return $cache = array( 'links' => array(), 'ids' => array(), 'problem' => sprintf( 'page /%s/ is noindex', $path ) );
		}
		$url    = (string) get_permalink( $page );
		$custom = trim( (string) get_post_meta( $page->ID, 'rank_math_canonical_url', true ) );
		if ( '' !== $custom && untrailingslashit( $custom ) !== untrailingslashit( $url ) ) {
			return $cache = array( 'links' => array(), 'ids' => array(), 'problem' => sprintf( 'page /%s/ has a custom canonical (%s)', $path, $custom ) );
		}
		$links[ $lang ] = $url;
		$ids[]          = (int) $page->ID;
	}
	return $cache = array( 'links' => $links, 'ids' => array_values( array_unique( $ids ) ), 'problem' => '' );
}

add_action(
	'wp_head',
	static function () {
		if ( ! is_page() || is_paged() ) {
			return;
		}
		$g = sh_hreflang_group();
		if ( ! $g['links'] || ! in_array( (int) get_queried_object_id(), $g['ids'], true ) ) {
			return;
		}
		foreach ( $g['links'] as $lang => $url ) {
			printf( '<link rel="alternate" hreflang="%s" href="%s" />' . "\n", esc_attr( $lang ), esc_url( $url ) );
		}
	},
	2
);
