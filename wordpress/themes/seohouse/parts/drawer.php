<?php
/**
 * Mobile drawer (design: full-screen dialog with accordion groups).
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

$nav = sh_nav_primary();
?>
<div id="sh-drawer" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'القائمة', 'seohouse' ); ?>" hidden style="position: fixed; inset: 0px; z-index: 80; background: rgba(var(--sh-ink-rgb), 0.98); overflow-y: auto; padding: 18px 20px 40px;">
	<div style="display: flex; align-items: center; justify-content: space-between; height: 58px;">
		<img src="<?php echo esc_url( sh_logo_url() ); ?>" alt="<?php echo esc_attr( sh_site_name() ); ?>" width="120" height="30" style="height: 30px; width: auto;">
		<button type="button" data-sh-drawer-close aria-label="<?php esc_attr_e( 'إغلاق القائمة', 'seohouse' ); ?>" style="background: none; border: 1px solid rgba(255, 255, 255, 0.22); color: var(--sh-text); width: 44px; height: 44px; border-radius: 10px; font-size: 20px; cursor: pointer;">✕</button>
	</div>
	<nav aria-label="<?php esc_attr_e( 'قائمة الجوال', 'seohouse' ); ?>" style="margin-top: 18px; border-top: 1px solid rgba(255, 255, 255, 0.12);">
		<?php foreach ( $nav as $n ) : ?>
		<div style="border-bottom: 1px solid rgba(255, 255, 255, 0.12);">
			<?php if ( ! $n['children'] ) : ?>
			<a href="<?php echo esc_url( $n['url'] ); ?>" style="min-height: 56px; display: flex; align-items: center; color: var(--sh-text); font-family: Alexandria, sans-serif; font-weight: 600; font-size: 17px;"><?php echo esc_html( $n['label'] ); ?></a>
			<?php else : ?>
			<button type="button" aria-expanded="false" aria-controls="sh-dg-<?php echo esc_attr( $n['id'] ); ?>" data-sh-group style="width: 100%; min-height: 56px; background: none; border: 0px; color: var(--sh-text); font-family: Alexandria, sans-serif; font-weight: 600; font-size: 17px; display: flex; align-items: center; justify-content: space-between; cursor: pointer; padding: 0px;">
				<span><?php echo esc_html( $n['label'] ); ?></span><span aria-hidden="true" data-sign style="color: var(--sh-sky); font-size: 15px;">+</span>
			</button>
			<div id="sh-dg-<?php echo esc_attr( $n['id'] ); ?>" hidden style="flex-direction: column; padding-bottom: 8px;">
				<?php foreach ( $n['children'] as $c ) : ?>
				<a href="<?php echo esc_url( $c['url'] ); ?>" style="color: var(--sh-muted); font-size: 15px; min-height: 44px; display: flex; align-items: center; padding-inline-start: 10px;"><?php echo esc_html( $c['label'] ); ?></a>
				<?php endforeach; ?>
				<?php if ( $n['all_label'] ) : ?>
				<a href="<?php echo esc_url( $n['url'] ); ?>" style="color: var(--sh-sky); font-size: 15px; font-weight: 600; min-height: 44px; display: flex; align-items: center; padding-inline-start: 10px;"><?php echo esc_html( $n['all_label'] ); ?> ←</a>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>
		<?php endforeach; ?>
	</nav>
</div>
