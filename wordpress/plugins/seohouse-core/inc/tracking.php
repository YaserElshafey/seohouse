<?php
/**
 * One tracking source: Google Tag Manager (preferred) or GA4, from the settings page.
 * Nothing is loaded when both are empty. Front-end events (site.js) push only
 * non-personal fields: sh_cta_click {sh_target}, sh_lead_submitted {sh_source, sh_service}.
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

function sh_tracking_ids(): array {
	$gtm = strtoupper( trim( (string) sh_core_option( 'sh_gtm_id', '' ) ) );
	$ga4 = strtoupper( trim( (string) sh_core_option( 'sh_ga4_id', '' ) ) );
	return array(
		'gtm' => preg_match( '/^GTM-[A-Z0-9]+$/', $gtm ) ? $gtm : '',
		'ga4' => preg_match( '/^G-[A-Z0-9]+$/', $ga4 ) ? $ga4 : '',
	);
}

add_action(
	'wp_head',
	static function () {
		if ( is_user_logged_in() && current_user_can( 'edit_posts' ) && ! apply_filters( 'sh_track_editors', false ) ) {
			return;
		}
		$ids = sh_tracking_ids();
		if ( $ids['gtm'] ) {
			echo "<script>window.dataLayer=window.dataLayer||[];(function(w,d,s,l,i){w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s);j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','" . esc_js( $ids['gtm'] ) . "');</script>\n";
		} elseif ( $ids['ga4'] ) {
			printf( '<script async src="%s"></script>' . "\n", esc_url( 'https://www.googletagmanager.com/gtag/js?id=' . $ids['ga4'] ) );
			echo "<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','" . esc_js( $ids['ga4'] ) . "');</script>\n";
		}
	},
	20
);
