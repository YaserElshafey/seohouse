<?php
/**
 * Section "Sectors" — Egypt SEO page: sector list + active sector panel (desktop, hover/focus/click)
 * and an accordion (mobile), all from one "sectors" repeater.
 *
 * @sh-manual
 * @package SEOHouse
 * @var array $args { f: layout values }
 */

defined( 'ABSPATH' ) || exit;
$f       = $args['f'] ?? array();
$sectors = array_values( array_filter( (array) ( $f['sectors'] ?? array() ), static fn( $x ) => ! empty( $x['label'] ) ) );
$uid     = 'sheg-' . (int) ( $args['index'] ?? 0 );
$chip    = 'font-size: 14px; color: var(--sh-text); background: rgba(255, 255, 255, 0.06); border-radius: 10px; padding: 8px 13px;';
?>
<section data-screen-label="Sectors" data-sh-tabs="hover" style="background: var(--sh-surface);">
	<div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
		<div style="display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 16px;">
			<div data-sec-head>
				<?php if ( ! empty( $f['eyebrow'] ) ) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?php echo esc_html( $f['eyebrow'] ); ?></div><?php endif; ?>
				<?php if ( ! empty( $f['title'] ) ) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?php echo esc_html( $f['title'] ); ?></h2><?php endif; ?>
			</div>
			<?php if ( ! empty( $f['all_link'] ) && ! empty( $f['all_label'] ) ) : ?>
			<a href="<?php echo esc_url( sh_link( $f['all_link'] ) ); ?>" style="font-size: 15px; font-weight: 600;"><?php echo esc_html( $f['all_label'] ); ?> <span aria-hidden="true">←</span></a>
			<?php endif; ?>
		</div>
		<?php if ( $sectors ) : ?>
		<div data-eg-split style="margin-top: clamp(24px, 2.8vw, 38px); display: grid; gap: clamp(24px, 3vw, 46px); align-items: stretch;">
			<div role="tablist" aria-orientation="vertical" style="display: flex; flex-direction: column;">
				<?php
				foreach ( $sectors as $i => $x ) :
					$on = 0 === $i;
					?>
				<button type="button" role="tab" id="<?php echo esc_attr( "$uid-t$i" ); ?>" aria-controls="<?php echo esc_attr( "$uid-p$i" ); ?>" aria-selected="<?php echo $on ? 'true' : 'false'; ?>" tabindex="<?php echo $on ? '0' : '-1'; ?>" data-sh-tab<?php echo sh_tab_style( $on, 'text-align: start; cursor: pointer; background: none; border-width: 1px 0px 0px; border-style: solid none none; border-color: rgba(255, 255, 255, 0.12); display: flex; align-items: center; gap: 14px; padding: 16px 4px; color: var(--sh-lime); transition: color 0.25s, padding 0.25s; padding-inline-start: 10px;', 'text-align: start; cursor: pointer; background: none; border-width: 1px 0px 0px; border-style: solid none none; border-color: rgba(255, 255, 255, 0.12); display: flex; align-items: center; gap: 14px; padding: 16px 4px; color: var(--sh-text); transition: color 0.25s, padding 0.25s; padding-inline-start: 4px;' ); // phpcs:ignore ?>>
					<span style="flex: 1 1 auto; font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(17px, 1.7vw, 20px);"><?php echo esc_html( $x['label'] ); ?></span>
					<span aria-hidden="true"<?php echo sh_tab_style( $on, 'flex: 0 0 auto; color: var(--sh-lime);', 'flex: 0 0 auto; color: var(--sh-sky);' ); // phpcs:ignore ?>>←</span>
				</button>
				<?php endforeach; ?>
			</div>
			<?php foreach ( $sectors as $i => $x ) : ?>
			<div role="tabpanel" id="<?php echo esc_attr( "$uid-p$i" ); ?>" aria-labelledby="<?php echo esc_attr( "$uid-t$i" ); ?>"<?php echo 0 === $i ? '' : ' hidden'; ?> style="border-radius: 20px; background: linear-gradient(150deg, var(--sh-surface), var(--sh-surface)); padding: clamp(22px, 2.6vw, 32px); display: flex; flex-direction: column; gap: 14px;">
				<div style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: clamp(20px, 2.1vw, 26px); color: rgb(255, 255, 255);"><?php echo esc_html( $x['label'] ); ?></div>
				<?php if ( ! empty( $x['desc'] ) ) : ?><p style="font-size: 16px; color: var(--sh-crumb-current); margin: 0px; max-width: 34em; text-wrap: pretty;"><?php echo esc_html( $x['desc'] ); ?></p><?php endif; ?>
				<?php if ( ! empty( $x['path'] ) ) : ?>
					<?php if ( ! empty( $f['path_label'] ) ) : ?><div style="font-size: 13px; font-weight: 600; color: var(--sh-sky); margin-top: 6px;"><?php echo esc_html( $f['path_label'] ); ?></div><?php endif; ?>
				<div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px 10px;">
					<?php foreach ( (array) $x['path'] as $p ) : ?>
						<?php if ( ! empty( $p['step'] ) ) : ?><span style="<?php echo esc_attr( $chip ); ?>"><?php echo esc_html( $p['step'] ); ?></span><?php endif; ?>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
				<?php if ( ! empty( $x['link'] ) && ! empty( $f['link_label'] ) ) : ?>
				<a href="<?php echo esc_url( sh_link( $x['link'] ) ); ?>" style="margin-top: auto; align-self: flex-start; font-size: 15px; font-weight: 600; color: var(--sh-lime); border-bottom: 1px solid rgba(var(--sh-lime-rgb), 0.45); padding-bottom: 3px;"><?php echo esc_html( $f['link_label'] ); ?> <span aria-hidden="true">←</span></a>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>
		<div data-eg-acc data-sh-accordion style="margin-top: 24px;">
			<?php foreach ( $sectors as $i => $x ) : ?>
			<div style="border-top: 1px solid rgba(255, 255, 255, 0.12);">
				<button type="button" data-sh-acc aria-expanded="<?php echo 0 === $i ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( "$uid-a$i" ); ?>" style="width: 100%; min-height: 58px; background: none; border: 0px; cursor: pointer; color: var(--sh-text); display: flex; align-items: center; justify-content: space-between; gap: 14px; padding: 12px 0px; text-align: start;">
					<span style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17px;"><?php echo esc_html( $x['label'] ); ?></span>
					<span aria-hidden="true" data-sign style="flex: 0 0 auto; color: var(--sh-sky); font-size: 19px;"><?php echo 0 === $i ? '−' : '+'; ?></span>
				</button>
				<div id="<?php echo esc_attr( "$uid-a$i" ); ?>"<?php echo 0 === $i ? '' : ' hidden'; ?> style="padding: 0px 0px 18px;">
					<?php if ( ! empty( $x['desc'] ) ) : ?><p style="font-size: 15.5px; color: var(--sh-muted); margin: 0px 0px 12px;"><?php echo esc_html( $x['desc'] ); ?></p><?php endif; ?>
					<div style="display: flex; flex-wrap: wrap; gap: 8px;">
						<?php foreach ( (array) ( $x['path'] ?? array() ) as $p ) : ?>
							<?php if ( ! empty( $p['step'] ) ) : ?><span style="font-size: 14px; color: var(--sh-crumb-current); background: rgba(255, 255, 255, 0.06); border-radius: 10px; padding: 7px 12px;"><?php echo esc_html( $p['step'] ); ?></span><?php endif; ?>
						<?php endforeach; ?>
					</div>
					<?php if ( ! empty( $x['link'] ) && ! empty( $f['link_label'] ) ) : ?>
					<a href="<?php echo esc_url( sh_link( $x['link'] ) ); ?>" style="display: inline-flex; align-items: center; gap: 8px; margin-top: 14px; font-size: 14.5px; font-weight: 600;"><?php echo esc_html( $f['link_label'] ); ?> <span aria-hidden="true">←</span></a>
					<?php endif; ?>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
</section>
