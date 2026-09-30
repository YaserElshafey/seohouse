<?php
/**
 * Dependency checks: ACF (PRO or Secure Custom Fields) and SEO House Core.
 * Missing dependencies show an admin notice; the front end degrades without fatal errors
 * and never re-creates demo content.
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

/** ACF with the field types the theme uses (Repeater, Flexible Content, Options). */
function sh_acf_ready(): bool {
	return function_exists( 'get_field' ) && function_exists( 'acf_get_field_type' ) && acf_get_field_type( 'flexible_content' ) && acf_get_field_type( 'repeater' );
}

function sh_core_ready(): bool {
	return defined( 'SH_CORE_VERSION' );
}

add_action(
	'admin_notices',
	static function () {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		$missing = array();
		if ( ! function_exists( 'get_field' ) ) {
			$missing[] = 'Advanced Custom Fields PRO (أو Secure Custom Fields)';
		} elseif ( ! sh_acf_ready() ) {
			$missing[] = 'نسخة ACF تتضمن Repeater وFlexible Content (ACF PRO أو Secure Custom Fields) — النسخة المجانية من ACF لا تكفي';
		}
		if ( ! sh_core_ready() ) {
			$missing[] = 'إضافة SEO House Core';
		}
		if ( ! $missing ) {
			return;
		}
		printf(
			'<div class="notice notice-error"><p><strong>%s</strong> %s</p></div>',
			esc_html__( 'ثيم سيو هاوس يحتاج:', 'seohouse' ),
			esc_html( implode( '، ', $missing ) )
		);
	}
);

/**
 * Read a field safely. Returns $default when ACF is not available.
 *
 * @param string   $name    Field name.
 * @param int|string|false $post_id Post ID, 'option', or false for current post.
 * @param mixed    $default Fallback.
 * @return mixed
 */
function sh_field( string $name, $post_id = false, $default = null ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}
	$v = get_field( $name, $post_id );
	return ( null === $v || '' === $v || false === $v ) ? $default : $v;
}

/** Global option from "إعدادات سيو هاوس". */
function sh_option( string $name, $default = null ) {
	return sh_field( $name, 'option', $default );
}
