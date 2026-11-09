<?php
/**
 * A screenshot card (2.8.8): the image as it is (opens at full size), then optional title and sub
 * line, and the search phrase with its position. Used by «من ترتيب عملائنا في جوجل» (results page)
 * and «ترتيبنا في جوجل» (case study).
 *
 * @var array $args { image, alt, title, sub, keyword, position, date (when it was taken), lb_title, lb_sub, tag (li|div) }
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

$img = (int) ( $args['image'] ?? 0 );
if ( ! $img ) {
	return;
}
$alt  = trim( (string) ( $args['alt'] ?? '' ) );
$alt  = '' !== $alt ? $alt : (string) get_post_meta( $img, '_wp_attachment_image_alt', true );
$full = (string) wp_get_attachment_image_url( $img, 'full' );
$tag  = 'li' === ( $args['tag'] ?? 'div' ) ? 'li' : 'div';
?>
<<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput -- li|div ?> class="sh-shot">
	<figure style="margin: 0px; height: 100%; display: flex; flex-direction: column; border-radius: 16px; background: rgb(255, 255, 255); padding: 10px; box-shadow: 0 14px 34px -28px rgba(6,11,31,.5), 0 0 0 1px var(--sh-line);">
		<a href="<?php echo esc_url( $full ); ?>" data-sh-lightbox data-alt="<?php echo esc_attr( $alt ); ?>" data-lb-title="<?php echo esc_attr( (string) ( $args['lb_title'] ?? ( $args['title'] ?? '' ) ) ); ?>" data-lb-sub="<?php echo esc_attr( (string) ( $args['lb_sub'] ?? ( $args['sub'] ?? '' ) ) ); ?>" data-lb-kw="<?php echo esc_attr( (string) ( $args['keyword'] ?? '' ) ); ?>" data-lb-pos="<?php echo esc_attr( (string) ( $args['position'] ?? '' ) ); ?>" data-lb-date="<?php echo esc_attr( (string) ( $args['date'] ?? '' ) ); ?>" aria-label="<?php echo esc_attr( __( 'فتح الصورة بالحجم الكامل:', 'seohouse' ) . ' ' . $alt ); ?>" class="sh-shot__img sh-shot__frame">
			<?php echo wp_get_attachment_image( $img, 'large', false, array( 'alt' => $alt, 'loading' => 'lazy', 'decoding' => 'async', 'sizes' => '(max-width: 599px) 100vw, 360px' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</a>
		<figcaption style="padding: 12px 6px 4px;">
			<?php if ( ! empty( $args['title'] ) ) : ?>
			<span style="display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16px; line-height: 1.5; color: var(--sh-ink);"><?php echo esc_html( $args['title'] ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $args['sub'] ) ) : ?>
			<span style="display: block; font-size: 13px; color: var(--sh-text); margin-top: 2px;"><?php echo esc_html( $args['sub'] ); ?></span>
			<?php endif; ?>
			<span class="sh-shot__kw"><?php esc_html_e( 'كلمة البحث:', 'seohouse' ); ?> <b>«<bdi><?php echo esc_html( $args['keyword'] ?? '' ); ?></bdi>»</b></span>
			<?php if ( ! empty( $args['position'] ) ) : ?>
			<span class="sh-shot__pos"><?php echo esc_html( $args['position'] ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $args['date'] ) ) : ?>
			<span class="sh-shot__date"><?php echo esc_html( sprintf( /* translators: %s: date the screenshot was taken */ __( 'لقطة بتاريخ %s', 'seohouse' ), $args['date'] ) ); ?></span>
			<?php endif; ?>
		</figcaption>
	</figure>
</<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
