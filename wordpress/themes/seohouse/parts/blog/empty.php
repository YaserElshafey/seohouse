<?php
/**
 * Empty state for article lists.
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;
?>
<div style="margin-top: 22px; border-radius: 16px; background: #fff; padding: clamp(20px, 2.4vw, 28px); box-shadow: rgba(var(--sh-ink-rgb), 0.06) 0px 0px 0px 1px; text-align: center;">
	<p style="margin: 0; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18px; color: var(--sh-ink);"><?php echo esc_html( $args['title'] ?? __( 'لا توجد مقالات منشورة هنا بعد.', 'seohouse' ) ); ?></p>
	<a href="<?php echo esc_url( get_permalink( (int) get_option( 'page_for_posts' ) ) ); ?>" style="display: inline-block; margin-top: 10px; font-size: 14.5px; font-weight: 600; color: var(--sh-blue);"><?php esc_html_e( 'كل المقالات', 'seohouse' ); ?> ←</a>
</div>
