<?php
/**
 * Dependency checks: Advanced Custom Fields (free 6.x is enough) and SEO House Core.
 * Missing dependencies show an admin notice; the front end degrades without fatal errors
 * and never re-creates demo content.
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

/** ACF is active and SEO House Core has registered its list field (sections and lists need both). */
function sh_acf_ready(): bool {
	return function_exists( 'sh_core_acf_ready' ) && sh_core_acf_ready();
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
			$missing[] = 'Advanced Custom Fields (الإصدار 6 المجاني يكفي)';
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
