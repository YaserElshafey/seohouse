<?php
/**
 * Reviews sections: the content comes only from the reviews plugin's shortcode (page override
 * «التقييمات في هذه الصفحة» → the shared one in «إعدادات سيو هاوس ← التكاملات»). Without a
 * shortcode the theme leaves the section out. The design's example cards and placeholder box are
 * never shown, so their fields are hidden from the page editor (values are kept, not deleted).
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

/** Keys of the reviews section groups in the page field groups. */
function sh_core_reviews_groups(): array {
	static $keys = null;
	if ( null !== $keys ) {
		return $keys;
	}
	$keys = array();
	foreach ( (array) glob( SH_CORE_DIR . 'acf-json/group_sh_page_*.json' ) as $file ) {
		$g = json_decode( (string) file_get_contents( $file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		foreach ( (array) ( $g['fields'] ?? array() ) as $f ) {
			if ( 'group' === ( $f['type'] ?? '' ) && preg_match( '/^s_reviews(_\d+)?$/', (string) ( $f['name'] ?? '' ) ) ) {
				$keys[] = $f['key'];
			}
		}
	}
	return $keys;
}

add_filter(
	'acf/prepare_field',
	static function ( $field ) {
		if ( ! is_array( $field ) || ! is_admin() ) {
			return $field;
		}
		$groups = sh_core_reviews_groups();
		if ( in_array( $field['key'] ?? '', $groups, true ) ) {
			$field['instructions'] = __( 'محتوى القسم من شورت كود التقييمات («التقييمات في هذه الصفحة» في الشريط الجانبي، أو الشورت كود العام من «إعدادات سيو هاوس ← التكاملات»). بلا شورت كود لا يظهر القسم.', 'seohouse-core' );
			return $field;
		}
		if ( in_array( $field['parent'] ?? '', $groups, true ) && ! in_array( $field['name'] ?? '', array( 'sh_hide', 'sh_anchor', 'eyebrow', 'title', 'text' ), true ) ) {
			// example testimonials / placeholder texts of the design: never displayed
			return false;
		}
		return $field;
	},
	20
);
