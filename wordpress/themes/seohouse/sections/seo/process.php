<?php
/**
 * Section "Process" — SEO service page: phases rail + active phase panel (desktop) and an
 * accordion (mobile), all from one "phases" repeater; team strip from the team records.
 *
 * @sh-manual
 * @package SEOHouse
 * @var array $args { f: layout values }
 */

defined( 'ABSPATH' ) || exit;
$f      = $args['f'] ?? array();
$phases = array_values( array_filter( (array) ( $f['phases'] ?? array() ), static fn( $p ) => ! empty( $p['label'] ) ) );
$uid    = 'shph-' . (int) ( $args['index'] ?? 0 );
$check  = '<span aria-hidden="true" style="flex: 0 0 auto; color: var(--sh-link); font-size: 13px; margin-top: 3px;">✓</span>';
?>
<section data-screen-label="Process" data-sh-tabs style="border-top: 1px solid rgba(255, 255, 255, 0.1);">
	<div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
		<?php if ( ! empty( $f['eyebrow'] ) ) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?php echo esc_html( $f['eyebrow'] ); ?></div><?php endif; ?>
		<?php if ( ! empty( $f['title'] ) ) : ?><h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); margin: 12px 0px 0px; line-height: 1.3;"><?php echo esc_html( $f['title'] ); ?></h2><?php endif; ?>

		<?php if ( $phases ) : ?>
		<div data-rail role="tablist" style="margin-top: 30px; display: grid; gap: 14px;">
			<?php
			foreach ( $phases as $i => $p ) :
				$on = 0 === $i;
				?>
			<button type="button" role="tab" id="<?php echo esc_attr( "$uid-t$i" ); ?>" aria-controls="<?php echo esc_attr( "$uid-p$i" ); ?>" aria-selected="<?php echo $on ? 'true' : 'false'; ?>" tabindex="<?php echo $on ? '0' : '-1'; ?>" data-sh-tab<?php echo sh_tab_style( $on, 'text-align: start; cursor: pointer; background: none; border-width: 2px 0px 0px; border-style: solid none none; border-color: var(--sh-link); padding: 18px 0px 0px; color: var(--sh-ink); transition: border-color 0.3s;', 'text-align: start; cursor: pointer; background: none; border-width: 2px 0px 0px; border-style: solid none none; border-color: rgba(40, 84, 232, 0.28); padding: 18px 0px 0px; color: var(--sh-ink); transition: border-color 0.3s;' ); // phpcs:ignore ?>>
				<span<?php echo sh_tab_style( $on, 'display: block; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 13px; color: rgb(33, 72, 216);', 'display: block; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 13px; color: rgb(33, 72, 216);' ); // phpcs:ignore ?>><?php echo esc_html( $p['num'] ?? '' ); ?></span>
				<span<?php echo sh_tab_style( $on, 'display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18px; margin-top: 8px; color: var(--sh-link);', 'display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18px; margin-top: 8px; color: rgb(37, 43, 51);' ); // phpcs:ignore ?>><?php echo esc_html( $p['label'] ); ?></span>
				<?php if ( ! empty( $p['desc'] ) ) : ?><span style="display: block; font-size: 14.5px; color: var(--sh-text); margin-top: 6px;"><?php echo esc_html( $p['desc'] ); ?></span><?php endif; ?>
			</button>
			<?php endforeach; ?>
		</div>

			<?php foreach ( $phases as $i => $p ) : ?>
		<div data-phase-panel role="tabpanel" id="<?php echo esc_attr( "$uid-p$i" ); ?>" aria-labelledby="<?php echo esc_attr( "$uid-t$i" ); ?>"<?php echo 0 === $i ? '' : ' hidden'; ?> style="margin-top: 18px; border-radius: 18px; background: rgba(40, 84, 232, 0.08); padding: clamp(18px, 2vw, 24px); display: grid; gap: 18px 34px; align-items: start;">
			<div>
				<div style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: 13px; color: var(--sh-link);"><?php echo esc_html( $p['num'] ?? '' ); ?></div>
				<div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 19px; margin-top: 6px;"><?php echo esc_html( $p['label'] ); ?></div>
				<?php if ( ! empty( $p['desc'] ) ) : ?><p style="font-size: 15px; color: var(--sh-ink); margin: 8px 0px 0px;"><?php echo esc_html( $p['desc'] ); ?></p><?php endif; ?>
				<?php if ( ! empty( $p['out'] ) ) : ?><div style="margin-top: 12px; font-size: 14.5px; color: var(--sh-link);"><?php echo esc_html( $p['out'] ); ?></div><?php endif; ?>
			</div>
			<div>
				<?php if ( ! empty( $f['deliverables_label'] ) ) : ?><div style="font-size: 13px; font-weight: 600; color: var(--sh-link);"><?php echo esc_html( $f['deliverables_label'] ); ?></div><?php endif; ?>
				<div style="margin-top: 10px; display: flex; flex-direction: column; gap: 8px;">
					<?php foreach ( (array) ( $p['items'] ?? array() ) as $d ) : ?>
						<?php if ( ! empty( $d['text'] ) ) : ?>
					<div style="display: flex; align-items: flex-start; gap: 10px; font-size: 15.5px; color: var(--sh-ink);"><?php echo $check; // phpcs:ignore ?><span><?php echo esc_html( $d['text'] ); ?></span></div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
				<?php if ( ! empty( $p['viz_rows'] ) ) : ?>
			<div data-phase-viz aria-hidden="true" style="border-radius: 14px; background: var(--sh-surface); border: 1px solid rgba(40, 84, 232, 0.18); padding: 14px 16px;">
				<div style="display: flex; align-items: center; gap: 7px; padding-bottom: 10px; border-bottom: 1px solid var(--sh-line);">
					<span style="width: 7px; height: 7px; border-radius: 999px; background: var(--sh-line);"></span>
					<span style="width: 7px; height: 7px; border-radius: 999px; background: var(--sh-line);"></span>
					<span style="margin-inline-start: auto; font-size: 12px; color: var(--sh-text);"><?php echo esc_html( $p['viz_title'] ?? '' ); ?></span>
				</div>
				<div style="margin-top: 12px; display: flex; flex-direction: column; gap: 11px;">
					<?php foreach ( (array) $p['viz_rows'] as $r ) : ?>
					<div>
						<div style="font-size: 12.5px; color: var(--sh-ink);"><?php echo esc_html( $r['label'] ?? '' ); ?></div>
						<div style="margin-top: 6px; height: 6px; border-radius: 999px; background: var(--sh-surface); overflow: hidden;"><div style="height: 100%; width: <?php echo esc_attr( preg_match( '/^\d{1,3}%$/', (string) ( $r['pct'] ?? '' ) ) ? $r['pct'] : '0%' ); ?>; border-radius: 999px; background: linear-gradient(90deg, var(--sh-blue), var(--sh-link));"></div></div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
				<?php endif; ?>
		</div>
			<?php endforeach; ?>

		<div data-phase-acc data-sh-accordion style="margin-top: 18px;">
			<?php foreach ( $phases as $i => $p ) : ?>
			<div style="border-top: 1px solid rgba(255, 255, 255, 0.12);">
				<button type="button" data-sh-acc aria-expanded="<?php echo 0 === $i ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( "$uid-a$i" ); ?>" style="width: 100%; min-height: 60px; background: none; border: 0px; cursor: pointer; color: var(--sh-ink); display: flex; align-items: center; gap: 14px; padding: 12px 0px; text-align: start;">
					<span<?php echo sh_tab_style( 0 === $i, 'font-family: Alexandria, sans-serif; font-weight: 800; font-size: 13px; color: var(--sh-link);', 'font-family: Alexandria, sans-serif; font-weight: 800; font-size: 13px; color: var(--sh-link);' ); // phpcs:ignore ?>><?php echo esc_html( $p['num'] ?? '' ); ?></span>
					<span style="flex: 1 1 auto; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17px;"><?php echo esc_html( $p['label'] ); ?></span>
					<span aria-hidden="true" data-sign style="flex: 0 0 auto; color: var(--sh-link); font-size: 19px;"><?php echo 0 === $i ? '−' : '+'; ?></span>
				</button>
				<div id="<?php echo esc_attr( "$uid-a$i" ); ?>"<?php echo 0 === $i ? '' : ' hidden'; ?> style="padding: 0px 0px 18px;">
					<?php if ( ! empty( $p['desc'] ) ) : ?><p style="font-size: 15px; color: var(--sh-ink); margin: 0px 0px 6px;"><?php echo esc_html( $p['desc'] ); ?></p><?php endif; ?>
					<?php if ( ! empty( $p['out'] ) ) : ?><div style="font-size: 14.5px; color: var(--sh-link); margin-bottom: 12px;"><?php echo esc_html( $p['out'] ); ?></div><?php endif; ?>
					<div style="display: flex; flex-direction: column; gap: 8px;">
						<?php foreach ( (array) ( $p['items'] ?? array() ) as $d ) : ?>
							<?php if ( ! empty( $d['text'] ) ) : ?>
						<div style="display: flex; align-items: flex-start; gap: 10px; font-size: 15.5px; color: var(--sh-ink);"><?php echo $check; // phpcs:ignore ?><span><?php echo esc_html( $d['text'] ); ?></span></div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<?php if ( ! empty( $f['team_title'] ) ) : ?>
		<div style="margin-top: clamp(26px, 3vw, 40px); border-top: 1px solid var(--sh-line); padding-top: 26px;">
			<div style="display: flex; flex-wrap: wrap; align-items: baseline; justify-content: space-between; gap: 12px 20px;">
				<div>
					<div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 20px;"><?php echo esc_html( $f['team_title'] ); ?></div>
					<?php if ( ! empty( $f['team_sub'] ) ) : ?><div style="font-size: 14.5px; color: var(--sh-text); margin-top: 6px;"><?php echo esc_html( $f['team_sub'] ); ?></div><?php endif; ?>
				</div>
				<?php if ( ! empty( $f['team_link'] ) && ! empty( $f['team_link_label'] ) ) : ?>
				<a href="<?php echo esc_url( sh_link( $f['team_link'] ) ); ?>" style="font-size: 14.5px; font-weight: 600;"><?php echo esc_html( $f['team_link_label'] ); ?> <span aria-hidden="true">←</span></a>
				<?php endif; ?>
			</div>
			<div data-team-strip style="margin-top: 18px;">
				<div data-team-track>
					<?php
					foreach ( array( false, true ) as $dup ) :
						foreach ( sh_team_members( true ) as $m ) :
							echo wp_get_attachment_image( get_post_thumbnail_id( $m ), 'thumbnail', false, array( 'alt' => $dup ? '' : get_the_title( $m ), 'aria-hidden' => $dup ? 'true' : 'false', 'data-face' => '', 'loading' => 'lazy', 'decoding' => 'async' ) ); // phpcs:ignore
						endforeach;
					endforeach;
					?>
				</div>
			</div>
		</div>
		<?php endif; ?>
	</div>
</section>
