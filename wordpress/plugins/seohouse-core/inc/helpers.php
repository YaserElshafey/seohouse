<?php
/**
 * Shared helpers for SEO House Core.
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

/** ACF (free 6.x, PRO or SCF) is active and the Core field type is registered. */
function sh_core_acf_ready(): bool {
	return function_exists( 'get_field' ) && function_exists( 'acf_get_field_type' ) && acf_get_field_type( 'group' ) && acf_get_field_type( 'sh_rows' );
}

/**
 * Sections of a page in the design order: the ACF Group fields "s_<layout>" of the page's
 * template field group. Each row carries its layout name in `acf_fc_layout`.
 *
 * @return array<int,array>
 */
function sh_core_sections( int $post_id, bool $refresh = false ): array {
	static $cache = array();
	if ( $refresh ) {
		unset( $cache[ $post_id ] );
	}
	if ( isset( $cache[ $post_id ] ) ) {
		return $cache[ $post_id ];
	}
	$rows = array();
	if ( $post_id && sh_core_acf_ready() ) {
		sh_core_prime_rows( $post_id );
		foreach ( sh_core_section_fields( $post_id ) as $f ) {
			$v      = get_field( $f['key'], $post_id );
			$row    = is_array( $v ) ? $v : array();
			$rows[] = array( 'acf_fc_layout' => substr( $f['name'], 2 ) ) + $row;
		}
	}
	return $cache[ $post_id ] = $rows; // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments
}

/** The section Group fields ("s_*") of the page's template field group, in order. */
function sh_core_section_fields( int $post_id ): array {
	foreach ( acf_get_field_groups( array( 'post_id' => $post_id ) ) as $g ) {
		if ( str_starts_with( (string) $g['key'], 'group_sh_page_' ) ) {
			return array_values( array_filter( acf_get_fields( $g ), static fn( $f ) => 'group' === $f['type'] && str_starts_with( (string) $f['name'], 's_' ) ) );
		}
	}
	return array();
}

/** Loads all list-item records of a post (two levels) and their meta in two queries. */
function sh_core_prime_rows( int $post_id ): void {
	$parents = array( $post_id );
	for ( $level = 0; $level < 3 && $parents; $level++ ) {
		$ids = get_posts( array( 'post_type' => 'sh_row', 'post_status' => 'any', 'posts_per_page' => -1, 'fields' => 'ids', 'post_parent__in' => $parents, 'orderby' => 'none', 'no_found_rows' => true ) );
		if ( $ids ) {
			_prime_post_caches( $ids, false, true );
		}
		$parents = $ids;
	}
}

/** Safe get_field (returns default without ACF). */
function sh_core_field( string $name, $post_id = false, $default = null ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}
	$v = get_field( $name, $post_id );
	return ( null === $v || '' === $v || false === $v ) ? $default : $v;
}

function sh_core_option( string $name, $default = null ) {
	return sh_core_field( $name, 'option', $default );
}

/** Design key of a page (from its page template). */
function sh_core_page_key( $post = null ): string {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}
	$tpl = get_page_template_slug( $post );
	return ( $tpl && preg_match( '#^page-templates/([a-z0-9-]+)\.php$#', $tpl, $m ) ) ? $m[1] : '';
}

/** Absolute URL helper for relative design paths. */
function sh_core_url( string $path ): string {
	return '/' === ( $path[0] ?? '' ) ? home_url( $path ) : $path;
}

/** Plain text of a value (strip tags + collapse whitespace). */
function sh_core_plain( $v ): string {
	return trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( (string) $v ) ) );
}

/**
 * Site-relative path of a URL or request: decoded, without the folder WordPress lives in
 * (a site at example.com/new/ turns "/new/services/" into "/services/"), with slashes at both ends.
 */
function sh_core_site_path( string $url ): string {
	$p    = rawurldecode( (string) wp_parse_url( $url, PHP_URL_PATH ) );
	$home = rawurldecode( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ) );
	if ( $home && '/' !== $home && str_starts_with( trailingslashit( $p ), $home ) ) {
		$p = substr( $p, strlen( $home ) - 1 );
	}
	$p = '/' . trim( $p, '/' ) . '/';
	return '//' === $p ? '/' : $p;
}

/** Site-relative path of the current request. */
function sh_core_request_path(): string {
	return sh_core_site_path( (string) wp_unslash( $_SERVER['REQUEST_URI'] ?? '/' ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- compared only.
}
