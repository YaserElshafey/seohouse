<?php
/**
 * Shared component: FAQ accordion. The same rows feed FAQPage schema (SEO House Core).
 *
 * @package SEOHouse
 * @var array $args { f: { eyebrow, title, items[ question, answer ], schema } }
 */

defined( 'ABSPATH' ) || exit;

$f     = $args['f'] ?? array();
$items = array_values( array_filter( (array) ( $f['items'] ?? array() ), static fn( $r ) => ! empty( $r['question'] ) && ! empty( $r['answer'] ) ) );
if ( ! $items ) {
	return;
}
$uid = 'faq-' . ( (int) ( $args['index'] ?? 0 ) );
?>
<section data-screen-label="FAQ" style="border-top: 1px solid var(--sh-line);">
	<div style="max-width: 940px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
		<div data-sec-head>
			<?php if ( ! empty( $f['eyebrow'] ) ) : ?>
			<div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?php echo esc_html( $f['eyebrow'] ); ?></div>
			<?php endif; ?>
			<?php if ( ! empty( $f['title'] ) ) : ?>
			<h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?php echo esc_html( $f['title'] ); ?></h2>
			<?php endif; ?>
		</div>
		<div style="margin-top: 24px;" data-sh-accordion>
			<?php foreach ( $items as $i => $r ) : ?>
			<div style="border-top: 1px solid var(--sh-line);">
				<h3 style="margin: 0; font: inherit;">
					<button type="button" id="<?php echo esc_attr( "{$uid}-q{$i}" ); ?>" aria-expanded="<?php echo 0 === $i ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( "{$uid}-a{$i}" ); ?>" data-sh-acc style="width: 100%; min-height: 64px; background: none; border: 0px; cursor: pointer; color: var(--sh-ink); display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 14px 0px; text-align: start;">
						<span style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17.5px;"><?php echo esc_html( $r['question'] ); ?></span>
						<span aria-hidden="true" data-sign style="flex: 0 0 auto; color: var(--sh-link); font-size: 20px;"><?php echo 0 === $i ? '−' : '+'; ?></span>
					</button>
				</h3>
				<div id="<?php echo esc_attr( "{$uid}-a{$i}" ); ?>" role="region" aria-labelledby="<?php echo esc_attr( "{$uid}-q{$i}" ); ?>"<?php echo 0 === $i ? '' : ' hidden'; ?> style="padding: 0px 0px 20px;">
					<p style="font-size: 16px; color: var(--sh-ink); margin: 0px; max-width: 52em; text-wrap: pretty;"><?php echo wp_kses( $r['answer'], array( 'br' => array() ) ); ?></p>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
