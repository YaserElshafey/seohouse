<?php
/**
 * Site search form (design: search / 404). Uses WordPress's "s" parameter; there is no /search/ route.
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;
$uid = wp_unique_id( 'sh-q-' );
?>
<form role="search" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" style="display: flex; gap: 10px; max-width: <?php echo esc_attr( $args['max'] ?? '640px' ); ?>; width: 100%;">
	<label for="<?php echo esc_attr( $uid ); ?>" class="screen-reader-text"><?php esc_html_e( 'ابحث في الموقع', 'seohouse' ); ?></label>
	<input id="<?php echo esc_attr( $uid ); ?>" name="s" type="search" placeholder="<?php esc_attr_e( 'ابحث في الخدمات والمقالات', 'seohouse' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" style="flex: 1 1 auto; min-width: 0px; min-height: 52px; border-radius: 14px; border: 1.5px solid rgba(255, 255, 255, 0.16); background: rgba(255, 255, 255, 0.04); color: var(--sh-text); padding: 0px 16px; font-size: 16px; color-scheme: dark;">
	<button type="submit" class="sh-hv-cta" style="flex: 0 0 auto; min-height: 52px; padding: 0px 22px; border-radius: 14px; border: 0px; background: var(--sh-lime); color: var(--sh-ink); font-weight: 700; font-size: 15.5px; cursor: pointer;"><?php esc_html_e( 'بحث', 'seohouse' ); ?></button>
</form>
