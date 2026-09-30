<?php
/**
 * Shared helpers for SEO House Core.
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

function sh_core_acf_ready(): bool {
	return function_exists( 'get_field' ) && function_exists( 'acf_get_field_type' ) && acf_get_field_type( 'flexible_content' ) && acf_get_field_type( 'repeater' );
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
