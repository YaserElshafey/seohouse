<?php
/**
 * Section "Results" — SEO service page: result highlights as tabs (design caseTabs).
 * One repeater drives the tabs and their panels.
 *
 * @sh-manual
 * @package SEOHouse
 * @var array $args { f: layout values }
 */

defined( 'ABSPATH' ) || exit;
$f     = $args['f'] ?? array();
$cases = array_values( array_filter( (array) ( $f['cases'] ?? array() ), static fn( $c ) => ! empty( $c['tab'] ) ) );
$uid   = 'shcase-' . (int) ( $args['index'] ?? 0 );
$tab_on  = 'flex: 0 0 auto; min-height: 48px; padding: 0px 20px; border-radius: 999px; cursor: pointer; font-size: 15px; font-weight: 600; white-space: nowrap; background: rgb(40, 84, 232); color: rgb(255, 255, 255); border: 1px solid rgb(40, 84, 232); transition: background 0.25s, color 0.25s;';
$tab_off = 'flex: 0 0 auto; min-height: 48px; padding: 0px 20px; border-radius: 999px; cursor: pointer; font-size: 15px; font-weight: 600; white-space: nowrap; background: transparent; color: rgb(37, 43, 51); border: 1px solid rgb(204, 211, 220); transition: background 0.25s, color 0.25s;';
?>
<section id="<?php echo esc_attr( sh_anchor( $f, 'seo-results' ) ); ?>" data-screen-label="Results" data-sh-tabs style="border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
	<div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
		<div style="display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 16px;">
			<div>
				<?php if ( ! empty( $f['eyebrow'] ) ) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?php echo esc_html( $f['eyebrow'] ); ?></div><?php endif; ?>
				<?php if ( ! empty( $f['title'] ) ) : ?><h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); margin: 12px 0px 0px; line-height: 1.3;"><?php echo esc_html( $f['title'] ); ?></h2><?php endif; ?>
			</div>
			<?php if ( ! empty( $f['all_label'] ) && ! empty( $f['all_link'] ) ) : ?>
			<a href="<?php echo esc_url( sh_link( $f['all_link'] ) ); ?>" style="font-size: 15px; font-weight: 600;"><?php echo esc_html( $f['all_label'] ); ?> <span aria-hidden="true">←</span></a>
			<?php endif; ?>
		</div>
		<?php if ( $cases ) : ?>
		<div data-case-tabs role="tablist" style="margin-top: 26px; display: flex; gap: 10px; overflow-x: auto; scrollbar-width: none; padding-bottom: 4px;">
			<?php foreach ( $cases as $i => $c ) : ?>
			<button type="button" role="tab" id="<?php echo esc_attr( "$uid-t$i" ); ?>" aria-controls="<?php echo esc_attr( "$uid-p$i" ); ?>" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>" tabindex="<?php echo 0 === $i ? '0' : '-1'; ?>" data-sh-tab<?php echo sh_tab_style( 0 === $i, $tab_on, $tab_off ); // phpcs:ignore ?>><?php echo esc_html( $c['tab'] ); ?></button>
			<?php endforeach; ?>
		</div>
			<?php foreach ( $cases as $i => $c ) : ?>
		<div data-case role="tabpanel" id="<?php echo esc_attr( "$uid-p$i" ); ?>" aria-labelledby="<?php echo esc_attr( "$uid-t$i" ); ?>"<?php echo 0 === $i ? '' : ' hidden'; ?> style="margin-top: 18px; display: grid; gap: clamp(22px, 2.6vw, 40px); align-items: center; border-radius: 24px; background: rgb(255, 255, 255); padding: clamp(22px, 2.6vw, 34px); box-shadow: rgba(37, 43, 51, 0.35) 0px 14px 34px -26px;">
			<div style="min-width: 0px;">
				<div style="display: flex; flex-wrap: wrap; gap: 8px;">
					<?php foreach ( array( 'sector', 'market' ) as $k ) : ?>
						<?php if ( ! empty( $c[ $k ] ) ) : ?><span style="font-size: 13.5px; color: var(--sh-ink); background: var(--sh-bg); border-radius: 999px; padding: 7px 13px;"><?php echo esc_html( $c[ $k ] ); ?></span><?php endif; ?>
					<?php endforeach; ?>
				</div>
				<?php if ( ! empty( $c['figure'] ) ) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: clamp(34px, 4vw, 54px); color: var(--sh-link); line-height: 1.08; margin-top: 16px;"><bdi><?php echo esc_html( $c['figure'] ); ?></bdi></div><?php endif; ?>
				<?php if ( ! empty( $c['headline'] ) ) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 20px; margin-top: 10px;"><?php echo esc_html( $c['headline'] ); ?></div><?php endif; ?>
				<?php if ( ! empty( $c['desc'] ) ) : ?><p style="font-size: 16px; color: var(--sh-ink); margin: 12px 0px 0px; max-width: 34em; text-wrap: pretty;"><?php echo esc_html( $c['desc'] ); ?></p><?php endif; ?>
				<div style="display: flex; flex-wrap: wrap; gap: 8px 26px; margin-top: 18px; font-size: 13.5px; color: var(--sh-text);">
					<?php if ( ! empty( $c['period'] ) ) : ?><span><?php echo esc_html( __( 'الفترة:', 'seohouse' ) . ' ' . $c['period'] ); ?></span><?php endif; ?>
					<?php if ( ! empty( $c['source'] ) ) : ?><span><?php echo esc_html( __( 'المصدر:', 'seohouse' ) . ' ' . $c['source'] ); ?></span><?php endif; ?>
				</div>
				<?php if ( ! empty( $c['link'] ) && ! empty( $c['link_label'] ) ) : ?>
				<a href="<?php echo esc_url( sh_link( $c['link'] ) ); ?>" style="display: inline-flex; align-items: center; gap: 8px; margin-top: 18px; font-size: 15px; font-weight: 600; color: var(--sh-link); border-bottom: 1px solid rgba(40, 84, 232, 0.45); padding-bottom: 3px;"><?php echo esc_html( $c['link_label'] ); ?> <span aria-hidden="true">←</span></a>
				<?php endif; ?>
			</div>
			<?php if ( ! empty( $c['proof'] ) ) : ?>
			<div style="min-width: 0px; border-radius: 16px; background: var(--sh-surface); padding: 10px;">
				<div style="display: flex; align-items: center; gap: 7px; padding: 4px 6px 10px;">
					<span aria-hidden="true" style="width: 9px; height: 9px; border-radius: 999px; background: var(--sh-line);"></span>
					<span aria-hidden="true" style="width: 9px; height: 9px; border-radius: 999px; background: var(--sh-line);"></span>
					<span aria-hidden="true" style="width: 9px; height: 9px; border-radius: 999px; background: var(--sh-line);"></span>
					<?php if ( ! empty( $c['source'] ) ) : ?><span style="margin-inline-start: auto; font-size: 11.5px; color: var(--sh-text);"><?php echo esc_html( $c['source'] ); ?></span><?php endif; ?>
				</div>
				<?php echo sh_image( $c['proof'], array( 'style' => 'width: 100%; height: 300px; object-fit: contain; display: block; border-radius: 10px; background: rgb(255, 255, 255);' ), (string) ( $c['alt'] ?? '' ) ); // phpcs:ignore ?>
			</div>
			<?php endif; ?>
		</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</section>
