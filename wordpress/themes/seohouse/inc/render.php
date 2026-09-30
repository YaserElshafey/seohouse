<?php
/**
 * Renders the Flexible Content sections of a design page template.
 * Each layout maps to sections/<page-key>/<layout>.php, or to sections/shared/<layout>.php
 * for shared components (FAQ, booking, related links).
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

/** Section rows of the current page (cached per request). */
function sh_sections_rows( ?int $post_id = null ): array {
	static $cache = array();
	$post_id = $post_id ? $post_id : (int) get_queried_object_id();
	if ( ! $post_id || ! sh_acf_ready() ) {
		return array();
	}
	if ( ! isset( $cache[ $post_id ] ) ) {
		$rows              = get_field( 'sh_sections', $post_id );
		$cache[ $post_id ] = is_array( $rows ) ? $rows : array();
	}
	return $cache[ $post_id ];
}

function sh_section_file( string $key, string $layout ): string {
	$own = "sections/{$key}/{$layout}";
	if ( locate_template( $own . '.php' ) ) {
		return $own;
	}
	$base = preg_replace( '/_\d+$/', '', $layout );
	if ( locate_template( "sections/shared/{$base}.php" ) ) {
		return "sections/shared/{$base}";
	}
	return '';
}

function sh_render_sections( string $key ): void {
	if ( ! sh_acf_ready() ) {
		if ( current_user_can( 'edit_pages' ) ) {
			echo '<div class="sh-admin-hint">' . esc_html__( 'حقول ACF غير متاحة؛ فعّل ACF PRO أو Secure Custom Fields وإضافة SEO House Core.', 'seohouse' ) . '</div>';
		}
		return;
	}
	$rows = sh_sections_rows();
	if ( ! $rows ) {
		if ( current_user_can( 'edit_pages' ) ) {
			echo '<div class="sh-admin-hint">' . esc_html__( 'هذه الصفحة بلا أقسام بعد. أضف الأقسام من محرر الصفحة أو شغّل «تجهيز المحتوى» من إعدادات سيو هاوس.', 'seohouse' ) . '</div>';
		}
		return;
	}
	echo '<main id="main" class="sh-main">';
	foreach ( $rows as $i => $row ) {
		if ( ! is_array( $row ) || ! empty( $row['sh_hide'] ) ) {
			continue;
		}
		$layout = (string) ( $row['acf_fc_layout'] ?? '' );
		$file   = $layout ? sh_section_file( $key, $layout ) : '';
		if ( ! $file ) {
			continue;
		}
		get_template_part( $file, null, array( 'f' => $row, 'key' => $key, 'index' => $i ) );
	}
	echo '</main>';
}

/** Whether the current page renders a visible booking section (header CTA target). */
function sh_page_has_booking(): bool {
	if ( ! is_singular() ) {
		return false;
	}
	foreach ( sh_sections_rows() as $row ) {
		if ( is_array( $row ) && str_starts_with( (string) ( $row['acf_fc_layout'] ?? '' ), 'booking' ) && empty( $row['sh_hide'] ) ) {
			return true;
		}
	}
	return (bool) apply_filters( 'sh_page_has_booking', false );
}
