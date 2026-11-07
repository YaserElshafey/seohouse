<?php
/**
 * hreflang (Rank Math does not print hreflang; Polylang's own is switched off in Core's inc/i18n.php).
 *
 * The regional group: /services/seo/ (ar + x-default) and its Saudi, Egyptian and UAE versions. Pages are
 * found by path, links come from get_permalink() of their IDs. The same lines are printed on every page of
 * the group and nowhere else. Nothing is printed unless every Arabic page of the group is published,
 * public, indexable and canonical to itself (a page with a custom Rank Math canonical or noindex would
 * make the set invalid; such a conflict is left for the editor, the canonical is never changed here).
 * The English version of a group page joins the set (en-SA, en-EG, en-AE, en) once it is indexable.
 *
 * Any other page with an indexable translation (Polylang): ar, en and x-default (the Arabic page).
 * English pages kept noindex while under review are never announced.
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
	// English versions that are released (indexable), in the same order
	if ( function_exists( 'sh_translation' ) && function_exists( 'sh_i18n_indexable' ) ) {
		foreach ( SH_HREFLANG_GROUP as $lang => $path ) {
			if ( 'x-default' === $lang ) {
				continue;
			}
			$en = sh_translation( (int) get_page_by_path( $path, OBJECT, 'page' )->ID, 'en' );
			if ( $en && sh_i18n_indexable( $en ) ) {
				$links[ str_replace( 'ar', 'en', $lang ) ] = (string) get_permalink( $en );
				$ids[]                                       = $en;
			}
		}
	}
	return $cache = array( 'links' => $links, 'ids' => array_values( array_unique( $ids ) ), 'problem' => '' );
}

add_action(
	'wp_head',
	static function () {
		if ( ! is_singular() || is_paged() ) {
			return;
		}
		$g = sh_hreflang_group();
		if ( $g['links'] && in_array( (int) get_queried_object_id(), $g['ids'], true ) ) {
			foreach ( $g['links'] as $lang => $url ) {
				printf( '<link rel="alternate" hreflang="%s" href="%s" />' . "\n", esc_attr( $lang ), esc_url( $url ) );
			}
			return;
		}
		sh_hreflang_translations();
	},
	2
);

/** ar / en / x-default for a page with an indexable translation outside the regional group. */
function sh_hreflang_translations(): void {
	if ( ! function_exists( 'sh_i18n_alternates' ) || ! is_singular() ) {
		return;
	}
	$alts = sh_i18n_alternates( (int) get_queried_object_id() );
	if ( ! $alts || ! isset( $alts['ar'] ) ) {
		return;
	}
	foreach ( $alts as $lang => $alt ) {
		printf( '<link rel="alternate" hreflang="%s" href="%s" />' . "\n", esc_attr( $lang ), esc_url( $alt[1] ) );
	}
	printf( '<link rel="alternate" hreflang="x-default" href="%s" />' . "\n", esc_url( $alts['ar'][1] ) );
}
