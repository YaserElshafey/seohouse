<?php
/**
 * Visible breadcrumb. The trail itself comes from SEO House Core
 * (sh_breadcrumb_trail), which also feeds the BreadcrumbList schema, so both always match.
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

function sh_breadcrumbs(): void {
	$trail = function_exists( 'sh_breadcrumb_trail' ) ? sh_breadcrumb_trail() : array(
		array( 'label' => 'الرئيسية', 'url' => home_url( '/' ) ),
		array( 'label' => wp_get_document_title(), 'url' => '' ),
	);
	if ( count( $trail ) < 2 ) {
		return;
	}
	$last = count( $trail ) - 1;
	echo '<nav aria-label="مسار التنقل" style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px; font-size: 13px; color: var(--sh-crumb);">';
	foreach ( $trail as $i => $c ) {
		if ( $i === $last ) {
			printf( '<span aria-current="page" style="color: var(--sh-crumb-current);">%s</span>', esc_html( $c['label'] ) );
		} else {
			printf( '<a href="%s" style="color: var(--sh-crumb);">%s</a><span aria-hidden="true">←</span>', esc_url( $c['url'] ), esc_html( $c['label'] ) );
		}
	}
	echo '</nav>';
}
