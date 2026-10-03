<?php
/**
 * Section "Opportunities" — UAE SEO page: opportunity list + panel (desktop, hover/focus/click)
 * and an accordion (mobile), all from one "opps" repeater.
 *
 * @sh-manual
 * @package SEOHouse
 * @var array $args { f: layout values }
 */

defined( 'ABSPATH' ) || exit;
$f    = $args['f'] ?? array();
$opps = array_values( array_filter( (array) ( $f['opps'] ?? array() ), static fn( $o ) => ! empty( $o['label'] ) ) );
$uid  = 'shua-' . (int) ( $args['index'] ?? 0 );
$rows = static function ( $o ) use ( $f ) {
	return array_filter(
		array(
			array( $f['review_label'] ?? '', $o['review'] ?? '', 'var(--sh-sky)' ),
			array( $f['improve_label'] ?? '', $o['improve'] ?? '', 'var(--sh-sky)' ),
			array( $f['outcome_label'] ?? '', $o['outcome'] ?? '', 'var(--sh-lime)' ),
		),
		static fn( $r ) => '' !== $r[1]
	);
};
?>
<section id="<?php echo esc_attr( sh_anchor( $f, 'uae-opportunities' ) ); ?>" data-screen-label="Opportunities" data-sh-tabs="hover" style="border-bottom: 1px solid rgba(255, 255, 255, 0.1); scroll-margin-top: 88px;">
	<div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
		<div data-sec-head data-sh-center>
			<?php if ( ! empty( $f['eyebrow'] ) ) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?php echo esc_html( $f['eyebrow'] ); ?></div><?php endif; ?>
			<?php if ( ! empty( $f['title'] ) ) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?php echo esc_html( $f['title'] ); ?></h2><?php endif; ?>
		</div>
		<?php if ( $opps ) : ?>
		<div data-ua-opps style="margin-top: clamp(24px, 2.8vw, 40px); display: grid; gap: clamp(20px, 2.4vw, 36px); align-items: stretch;">
			<div role="tablist" aria-orientation="vertical" style="display: flex; flex-direction: column;">
				<?php
				foreach ( $opps as $i => $o ) :
					$on = 0 === $i;
					$base = 'text-align: start; cursor: pointer; width: 100%%; min-height: 62px; border-width: 1px 0px 0px 0px; border-style: solid; border-color: rgba(255, 255, 255, 0.12); border-inline-start: 2px solid %1$s; background: %2$s; color: %3$s; display: flex; align-items: center; gap: 14px; padding: 14px; transition: background 0.22s, border-color 0.22s, color 0.22s;';
					?>
				<button type="button" role="tab" id="<?php echo esc_attr( "$uid-t$i" ); ?>" aria-controls="<?php echo esc_attr( "$uid-p$i" ); ?>" aria-selected="<?php echo $on ? 'true' : 'false'; ?>" tabindex="<?php echo $on ? '0' : '-1'; ?>" data-sh-tab<?php echo sh_tab_style( $on, sprintf( $base, 'var(--sh-lime)', 'rgba(var(--sh-lime-rgb), 0.07)', 'rgb(255, 255, 255)' ), sprintf( $base, 'transparent', 'transparent', 'var(--sh-text)' ) ); // phpcs:ignore ?>>
					<span<?php echo sh_tab_style( $on, 'flex: 0 0 auto; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: var(--sh-lime);', 'flex: 0 0 auto; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: var(--sh-sky);' ); // phpcs:ignore ?>><?php echo esc_html( $o['num'] ?? '' ); ?></span>
					<span style="flex: 1 1 auto; min-width: 0px; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16.5px;"><?php echo esc_html( $o['label'] ); ?></span>
					<span aria-hidden="true"<?php echo sh_tab_style( $on, 'flex: 0 0 auto; color: var(--sh-lime);', 'flex: 0 0 auto; color: var(--sh-sky);' ); // phpcs:ignore ?>>←</span>
				</button>
				<?php endforeach; ?>
			</div>
			<?php foreach ( $opps as $i => $o ) : ?>
			<div role="tabpanel" id="<?php echo esc_attr( "$uid-p$i" ); ?>" aria-labelledby="<?php echo esc_attr( "$uid-t$i" ); ?>"<?php echo 0 === $i ? '' : ' hidden'; ?> style="border-radius: 20px; background: linear-gradient(150deg, var(--sh-surface), var(--sh-surface)); padding: clamp(22px, 2.6vw, 32px); display: flex; flex-direction: column; gap: 18px;">
				<div style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: clamp(20px, 2.1vw, 26px); color: rgb(255, 255, 255);"><?php echo esc_html( $o['label'] ); ?></div>
				<?php foreach ( $rows( $o ) as $r ) : ?>
				<div style="padding-top: 16px; border-top: 1px solid rgba(255, 255, 255, 0.1);">
					<div style="font-size: 13px; font-weight: 600; color: <?php echo esc_attr( $r[2] ); ?>;"><?php echo esc_html( $r[0] ); ?></div>
					<div style="font-size: 15.5px; color: var(--sh-crumb-current); margin-top: 8px; max-width: 34em; text-wrap: pretty;"><?php echo esc_html( $r[1] ); ?></div>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endforeach; ?>
		</div>
		<div data-ua-opp-acc data-sh-accordion style="margin-top: 24px;">
			<?php foreach ( $opps as $i => $o ) : ?>
			<div style="border-top: 1px solid rgba(255, 255, 255, 0.12);">
				<button type="button" data-sh-acc aria-expanded="<?php echo 0 === $i ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( "$uid-a$i" ); ?>" style="width: 100%; min-height: 60px; background: none; border: 0px; cursor: pointer; color: var(--sh-text); display: flex; align-items: center; gap: 14px; padding: 12px 0px; text-align: start;">
					<span<?php echo sh_tab_style( 0 === $i, 'flex: 0 0 auto; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: var(--sh-lime);', 'flex: 0 0 auto; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: var(--sh-sky);' ); // phpcs:ignore ?>><?php echo esc_html( $o['num'] ?? '' ); ?></span>
					<span style="flex: 1 1 auto; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16.5px;"><?php echo esc_html( $o['label'] ); ?></span>
					<span aria-hidden="true" data-sign style="flex: 0 0 auto; color: var(--sh-sky); font-size: 19px;"><?php echo 0 === $i ? '−' : '+'; ?></span>
				</button>
				<div id="<?php echo esc_attr( "$uid-a$i" ); ?>"<?php echo 0 === $i ? '' : ' hidden'; ?> style="padding: 0px 0px 18px;">
					<?php foreach ( $rows( $o ) as $r ) : ?>
					<div style="margin-bottom: 12px;">
						<div style="font-size: 13px; font-weight: 600; color: var(--sh-sky);"><?php echo esc_html( $r[0] ); ?></div>
						<div style="font-size: 15px; color: var(--sh-muted); margin-top: 6px;"><?php echo esc_html( $r[1] ); ?></div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
</section>
