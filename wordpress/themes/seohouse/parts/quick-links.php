<?php
/**
 * Quick links used by the 404 and empty search states (from the "legal"-independent primary pages).
 *
 * @package SEOHouse
 * @var array $args { style: 'chips'|'buttons' }
 */

defined( 'ABSPATH' ) || exit;

$paths = (array) ( $args['paths'] ?? array( '/services/', '/results/', '/blog/' ) );
$links = array();
foreach ( $paths as $p ) {
	$page = '/blog/' === $p ? get_post( (int) get_option( 'page_for_posts' ) ) : get_page_by_path( trim( $p, '/' ) );
	if ( $page && 'publish' === $page->post_status ) {
		$links[] = array( get_permalink( $page ), function_exists( 'sh_crumb_label' ) ? sh_crumb_label( $page, true ) : get_the_title( $page ) );
	}
}
$chip = 'buttons' === ( $args['style'] ?? 'chips' )
	? 'min-height: 50px; display: inline-flex; align-items: center; padding: 0px 20px; border-radius: 14px; color: var(--sh-crumb-current); background: rgba(255, 255, 255, 0.05);'
	: 'font-size: 14.5px; color: var(--sh-crumb-current); background: rgba(255, 255, 255, 0.05); border-radius: 999px; padding: 10px 16px;';
foreach ( $links as $l ) {
	printf( '<a href="%s" class="sh-hv-chip" style="%s">%s</a>', esc_url( $l[0] ), esc_attr( $chip ), esc_html( $l[1] ) );
}
