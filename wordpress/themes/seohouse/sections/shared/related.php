<?php
/**
 * Shared component: related pages strip ("صفحات مرتبطة").
 *
 * @package SEOHouse
 * @var array $args { f: { lead, links[ label, link ] } }
 */

defined( 'ABSPATH' ) || exit;

$f     = $args['f'] ?? array();
$links = array_filter( (array) ( $f['links'] ?? array() ), static fn( $l ) => ! empty( $l['label'] ) && ! empty( $l['link'] ) );
if ( ! $links ) {
	return;
}
?>
<section data-screen-label="Related" style="position: relative; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
	<div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
		<nav aria-label="<?php echo esc_attr( $f['lead'] ? rtrim( $f['lead'], ':： ' ) : __( 'صفحات مرتبطة', 'seohouse' ) ); ?>" style="display: flex; flex-wrap: wrap; gap: 12px 16px; align-items: center;">
			<?php if ( ! empty( $f['lead'] ) ) : ?>
			<span style="font-size: 15px; color: var(--sh-crumb);"><?php echo esc_html( $f['lead'] ); ?></span>
			<?php endif; ?>
			<?php foreach ( $links as $l ) : ?>
			<a href="<?php echo esc_url( sh_link( $l['link'] ) ); ?>" style="font-size: 15px; font-weight: 600;"><?php echo esc_html( $l['label'] ); ?> <span aria-hidden="true">←</span></a>
			<?php endforeach; ?>
		</nav>
	</div>
</section>
