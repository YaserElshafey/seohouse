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
function sh_core_platforms( bool $refresh = false ): array {
	static $list = null;
	if ( null !== $list && ! $refresh ) {
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

/** Choices of a page logo field: library entries, plus values the page stores that the library lacks. */
function sh_core_platform_choices( array $field ): array {
	$choices = array();
	foreach ( sh_core_platforms() as $p ) {
		$choices[ $p['value'] ] = '' !== $p['name'] ? $p['name'] : $p['value'];
	}
	foreach ( (array) ( $field['choices'] ?? array() ) as $k => $label ) {
		if ( ! isset( $choices[ $k ] ) && ! sh_core_platform( (string) $k ) ) {
			$choices[ $k ] = $label;
		}
	}
	return $choices;
}

/** A select field holding a platform logo (its design choices are the platform SVG paths). */
function sh_core_is_platform_select( $field ): bool {
	if ( ! is_array( $field ) || 'select' !== ( $field['type'] ?? '' ) || empty( $field['choices'] ) || ! is_array( $field['choices'] ) ) {
		return false;
	}
	foreach ( array_keys( $field['choices'] ) as $k ) {
		if ( str_starts_with( (string) $k, 'assets/platforms/' ) || str_starts_with( (string) $k, 'sh_row:' ) ) {
			return true;
		}
	}
	return false;
}

/*
 * The form a page editor sees: fields inside a section list are rendered from the list's own
 * definition (no load_field), so the choices are filled when the field is prepared for display.
 */
add_filter(
	'acf/prepare_field/type=select',
	static function ( $field ) {
		if ( ! sh_core_is_platform_select( $field ) || ! sh_core_platforms() ) {
			return $field;
		}
		$field['choices']       = sh_core_platform_choices( $field );
		$field['allow_null']    = 1;
		// a new item starts without a platform: the design's first logo is not a choice made here
		$field['default_value'] = '';
		// the template row of a section list (index "shclone_…") is what «إضافة عنصر» copies
		if ( null === ( $field['value'] ?? null ) || str_contains( (string) ( $field['prefix'] ?? '' ), 'shclone' ) ) {
			$field['value'] = '';
		}
		$field['instructions'] = sprintf(
			/* translators: %s: link to the platforms list */
			__( 'منصة من %s. لمنصة غير موجودة: ارفع شعارها في الحقل التالي واترك هذا الاختيار فارغًا، فتُضاف إلى القائمة عند الحفظ.', 'seohouse-core' ),
			'<a href="' . esc_url( admin_url( 'admin.php?page=seohouse-settings#sh_platforms' ) ) . '" target="_blank">' . esc_html__( '«المنصات والأدوات»', 'seohouse-core' ) . '</a>'
		);
		return $field;
	}
);

/*
 * A logo uploaded in a section without a platform chosen becomes a platform of the library
 * (name = the item's label), and the item then points to it: replacing that logo later in the
 * library updates every page that uses it.
 */
add_action(
	'acf/save_post',
	static function ( $post_id ) {
		if ( ! is_numeric( $post_id ) || 'page' !== get_post_type( (int) $post_id ) || ! function_exists( 'acf_get_field' ) ) {
			return;
		}
		$owners = array( (int) $post_id );
		$seen   = array();
		$added  = 0;
		while ( $owners ) {
			$owner = array_shift( $owners );
			$rows  = get_posts( array( 'post_type' => 'sh_row', 'post_status' => 'any', 'posts_per_page' => -1, 'fields' => 'ids', 'meta_key' => '_sh_owner', 'meta_value' => (string) $owner ) ); // phpcs:ignore WordPress.DB.SlowDBQuery
			foreach ( $rows as $rid ) {
				if ( isset( $seen[ $rid ] ) ) {
					continue;
				}
				$seen[ $rid ] = true;
				$owners[]     = $rid; // nested lists
				foreach ( get_post_meta( $rid ) as $meta => $vals ) {
					if ( ! preg_match( '/^(logo(?:_\d+)?)_image$/', $meta, $m ) || ! (int) $vals[0] ) {
						continue;
					}
					$image  = (int) $vals[0];
					$choice = (string) get_post_meta( $rid, $m[1], true );
					if ( '' !== $choice ) {
						continue; // a platform is chosen: the image is this page's own logo for it
					}
					$name = trim( (string) get_post_meta( $rid, 'label', true ) );
					if ( '' === $name ) {
						$name = trim( (string) get_post_meta( $image, '_wp_attachment_image_alt', true ) ) ?: get_the_title( $image );
					}
					$value = sh_core_platform_add( $name, $image );
					$sel   = acf_get_field( (string) get_post_meta( $rid, '_' . $m[1], true ) );
					$img   = acf_get_field( (string) get_post_meta( $rid, '_' . $meta, true ) );
					if ( $value && $sel && $img ) {
						acf_update_value( $value, $rid, $sel );
						acf_update_value( '', $rid, $img );
						++$added;
					}
				}
			}
		}
		if ( $added ) {
			set_transient( 'sh_platform_added_' . get_current_user_id(), $added, 60 );
		}
	},
	30
);

/** Appends a platform to the library; returns its reference ("sh_row:<id>"). */
function sh_core_platform_add( string $name, int $logo, string $url = '' ): string {
	$rows   = get_field( 'sh_platforms', 'option', false );
	$rows   = is_array( $rows ) ? $rows : array();
	$rows[] = array(
		'field_sh_opt_platforms__name' => '' !== $name ? $name : __( 'منصة', 'seohouse-core' ),
		'field_sh_opt_platforms__logo' => $logo,
		'field_sh_opt_platforms__url'  => $url,
		'field_sh_opt_platforms__ref'  => '',
	);
	update_field( 'field_sh_opt_platforms', $rows, 'option' );
	$list = sh_core_platforms( true );
	$last = end( $list );
	return $last ? (string) $last['value'] : '';
}

add_action(
	'admin_notices',
	static function () {
		$n = get_transient( 'sh_platform_added_' . get_current_user_id() );
		if ( $n ) {
			delete_transient( 'sh_platform_added_' . get_current_user_id() );
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html( sprintf( 'أُضيفت %d منصة جديدة إلى «المنصات والأدوات» من الشعارات المرفوعة في هذه الصفحة.', (int) $n ) ) . '</p></div>';
		}
	}
);

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
