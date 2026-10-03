<?php
/**
 * Platforms and tools library («إعدادات سيو هاوس ← المحتوى المشترك ← المنصات والأدوات»).
 *
 * Each entry: name, logo (media library), optional link; the list order is the order of the
 * choices in the page sections. The page sections ("منصات نعمل عليها", tools, store heroes) keep
 * their own selection and order: their logo field is a choice from this library.
 *
 * Entries created by the initialisation carry the design's file path as internal reference
 * (`ref`, e.g. assets/platforms/official/salla.svg), which is exactly the value pages already
 * store — so existing pages keep their logos and pick up a replaced logo automatically. Entries
 * added later are referenced as "sh_row:<id>".
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

/** @return array<int,array{value:string,name:string,logo:int,url:string}> in library order */
function sh_core_platforms(): array {
	static $list = null;
	if ( null !== $list ) {
		return $list;
	}
	$list = array();
	if ( ! function_exists( 'get_field' ) ) {
		return $list;
	}
	$rows = get_field( 'sh_platforms', 'option', false );
	foreach ( is_array( $rows ) ? $rows : array() as $r ) {
		$id   = (int) ( $r['_row_id'] ?? 0 );
		$ref  = trim( (string) ( $r['field_sh_opt_platforms__ref'] ?? '' ) );
		$name = trim( (string) ( $r['field_sh_opt_platforms__name'] ?? '' ) );
		if ( ! $id && '' === $ref ) {
			continue;
		}
		$list[] = array(
			'value' => '' !== $ref ? $ref : 'sh_row:' . $id,
			'name'  => $name,
			'logo'  => (int) ( $r['field_sh_opt_platforms__logo'] ?? 0 ),
			'url'   => (string) ( $r['field_sh_opt_platforms__url'] ?? '' ),
		);
	}
	return $list;
}

/** Library entry for a stored logo value (library value or the design file path). */
function sh_core_platform( string $value ): ?array {
	$value = ltrim( trim( $value ), '/' );
	if ( '' === $value ) {
		return null;
	}
	foreach ( sh_core_platforms() as $p ) {
		if ( $p['value'] === $value ) {
			return $p;
		}
	}
	// same design file referenced with another folder (e.g. assets/platforms/salla.svg)
	if ( str_starts_with( $value, 'assets/platforms/' ) ) {
		foreach ( sh_core_platforms() as $p ) {
			if ( str_starts_with( $p['value'], 'assets/platforms/' ) && basename( $p['value'] ) === basename( $value ) ) {
				return $p;
			}
		}
	}
	return null;
}

/** Logo URL of an entry ('' when it has no logo in the library). */
function sh_core_platform_logo_url( array $p ): string {
	return $p['logo'] ? (string) wp_get_attachment_url( $p['logo'] ) : '';
}

/*
 * Page editors: a logo field is a choice from the library. Choices that a page already stores
 * but the library no longer has stay listed, so saving a page never drops its logo.
 */
add_filter(
	'acf/load_field/type=select',
	static function ( $field ) {
		if ( ! is_admin() || empty( $field['choices'] ) || ! is_array( $field['choices'] ) ) {
			return $field;
		}
		$keys = array_keys( $field['choices'] );
		if ( ! str_starts_with( (string) $keys[0], 'assets/platforms/' ) ) {
			return $field;
		}
		$lib = sh_core_platforms();
		if ( ! $lib ) {
			return $field;
		}
		$choices = array();
		foreach ( $lib as $p ) {
			$choices[ $p['value'] ] = '' !== $p['name'] ? $p['name'] : $p['value'];
		}
		foreach ( $field['choices'] as $k => $label ) {
			if ( ! isset( $choices[ $k ] ) && ! sh_core_platform( (string) $k ) ) {
				$choices[ $k ] = $label;
			}
		}
		$field['choices']      = $choices;
		$field['instructions'] = __( 'من «إعدادات سيو هاوس ← المحتوى المشترك ← المنصات والأدوات». غيّر الشعار نفسه أو أضف منصة من هناك.', 'seohouse-core' );
		return $field;
	}
);

// the internal reference is set by the initialisation and never edited by hand
add_filter( 'acf/prepare_field/key=field_sh_opt_platforms__ref', '__return_false' );
