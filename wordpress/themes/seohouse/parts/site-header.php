<?php
/**
 * Header bar: CTA, burger, desktop navigation with mega panels, logo.
 * Markup and inline styles follow the approved design; behaviour lives in assets/js/site.js.
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

$nav   = sh_nav_primary();
$cta   = sh_header_cta();
$icons = sh_nav_icons();
$chev  = '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" data-chev style="transition: transform 0.2s;"><path d="m6 9 6 6 6-6"></path></svg>';
?>
<header data-sh-header style="position: sticky; top: 0px; z-index: 60; background: linear-gradient(rgba(255, 255, 255, 0.94), rgba(255, 255, 255, 0.82)); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);">
	<span aria-hidden="true" style="position: absolute; inset-inline: 0px; bottom: 0px; height: 1px; background: linear-gradient(90deg, transparent, rgba(40, 84, 232, 0.08) 25%, rgba(40, 84, 232, 0.45) 50%, rgba(40, 84, 232, 0.08) 75%, transparent); pointer-events: none;"></span>
	<div data-sh-bar style="position: relative; max-width: 1320px; margin: 0px auto; padding: 0px 20px; height: 74px; display: flex; align-items: center; gap: 24px;">
		<?php if ( $cta['label'] ) : ?>
		<div data-nav="desktop" style="align-items: center; flex: 0 0 auto;">
			<a href="<?php echo esc_url( $cta['url'] ); ?>" class="sh-hv-cta" data-sh-cta style="background: var(--sh-blue); color: rgb(255, 255, 255); font-weight: 700; font-size: 15px; padding: 12px 20px; border-radius: 12px; display: inline-block; white-space: nowrap; transition: background 0.2s;"><?php echo esc_html( $cta['label'] ); ?></a>
		</div>
		<?php endif; ?>
		<button type="button" aria-label="<?php esc_attr_e( 'فتح القائمة', 'seohouse' ); ?>" aria-expanded="false" aria-controls="sh-drawer" data-nav="burger" data-sh-drawer-open style="flex: 0 0 auto; background: none; border: 1px solid var(--sh-line); border-radius: 10px; width: 44px; height: 44px; cursor: pointer; color: var(--sh-ink); align-items: center; justify-content: center; flex-direction: column; gap: 4px;">
			<i style="display: block; width: 18px; height: 1.5px; background: var(--sh-ink);"></i>
			<i style="display: block; width: 18px; height: 1.5px; background: var(--sh-ink);"></i>
			<i style="display: block; width: 18px; height: 1.5px; background: var(--sh-ink);"></i>
		</button>
		<nav aria-label="<?php esc_attr_e( 'القائمة الرئيسية', 'seohouse' ); ?>" data-nav="desktop" style="align-items: center; justify-content: center; gap: 2px; flex: 0 1 auto; min-width: 0px; margin-inline: auto; padding: 5px; background: var(--sh-surface); border: 1px solid var(--sh-line); border-radius: 999px;">
			<?php
			foreach ( $nav as $n ) :
				$color = $n['active'] ? 'var(--sh-link)' : 'rgb(14, 22, 48)';
				if ( ! $n['children'] ) :
					?>
			<div data-sh-menu style="position: relative;">
				<a href="<?php echo esc_url( $n['url'] ); ?>" class="sh-hv-nav"<?php echo $n['active'] && sh_url_path( $n['url'] ) === sh_current_path() ? ' aria-current="page"' : ''; ?> style="display: block; padding: 10px; font-size: 14.5px; font-weight: 500; white-space: nowrap; color: <?php echo esc_attr( $color ); ?>; border-radius: 10px; transition: color 0.2s, background 0.2s;"><?php echo esc_html( $n['label'] ); ?></a>
			</div>
					<?php
				else :
					$has_desc = (bool) array_filter( wp_list_pluck( $n['children'], 'desc' ) );
					$grid     = 'grid2' === $n['layout'] || ( '' === $n['layout'] && ! $has_desc );
					$panel_id = 'sh-panel-' . $n['id'];
					?>
			<div data-sh-menu style="position: relative;">
				<button type="button" id="sh-btn-<?php echo esc_attr( $n['id'] ); ?>" aria-expanded="false" aria-controls="<?php echo esc_attr( $panel_id ); ?>" class="sh-hv-nav" style="display: flex; align-items: center; gap: 6px; background: none; border: 0px; cursor: pointer; padding: 10px; font-size: 14.5px; font-weight: 500; white-space: nowrap; color: <?php echo esc_attr( $color ); ?>; border-radius: 10px; transition: color 0.2s, background 0.2s;"><?php echo esc_html( $n['label'] ); ?><?php echo $chev; // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
				<div id="<?php echo esc_attr( $panel_id ); ?>" data-sh-panel hidden style="position: absolute; top: 100%; inset-inline-start: <?php echo $grid ? '50%' : '0px'; ?>; transform: <?php echo $grid ? 'translateX(50%)' : 'none'; ?>; padding-top: 10px; z-index: 70;">
					<div style="width: <?php echo $grid ? '460px' : '390px'; ?>; max-width: calc(-32px + 100vw); background: rgb(255, 255, 255); border: 1px solid var(--sh-line); border-radius: 16px; box-shadow: rgba(0, 0, 0, 0.85) 0px 26px 60px -24px; padding: 8px; display: grid; grid-template-columns: <?php echo $grid ? '1fr 1fr' : '1fr'; ?>; gap: 2px;">
						<?php foreach ( $n['children'] as $c ) : ?>
						<a href="<?php echo esc_url( $c['url'] ); ?>" class="sh-hv-mega" style="display: flex; align-items: flex-start; gap: 12px; padding: 11px 12px; border-radius: 11px; color: var(--sh-ink); transition: background 0.18s;">
							<?php if ( $c['icon'] && isset( $icons[ $c['icon'] ] ) ) : ?>
							<span aria-hidden="true" style="display: flex; flex: 0 0 auto; width: 34px; height: 34px; border-radius: 10px; background: rgba(171, 182, 194, 0.12); color: var(--sh-link); align-items: center; justify-content: center;"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="<?php echo esc_attr( $icons[ $c['icon'] ]['d'] ); ?>"></path></svg></span>
							<?php endif; ?>
							<span style="min-width: 0px;"><span style="display: block; font-size: 15px; font-weight: 600;"><?php echo esc_html( $c['label'] ); ?></span><?php if ( $c['desc'] ) : ?><span style="display: block; font-size: 13px; color: var(--sh-text); margin-top: 3px; line-height: 1.5;"><?php echo esc_html( $c['desc'] ); ?></span><?php endif; ?></span>
						</a>
						<?php endforeach; ?>
						<?php if ( $n['all_label'] ) : ?>
						<a href="<?php echo esc_url( $n['url'] ); ?>" class="sh-hv-link" style="grid-column: 1 / -1; display: flex; align-items: center; justify-content: space-between; margin-top: 4px; padding: 11px 12px; border-top: 1px solid var(--sh-line); font-size: 14px; font-weight: 600; color: var(--sh-link);"><?php echo esc_html( $n['all_label'] ); ?><span aria-hidden="true">←</span></a>
						<?php endif; ?>
					</div>
				</div>
			</div>
					<?php
				endif;
			endforeach;
			?>
		</nav>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( sh_site_name() . ' — ' . __( 'الرئيسية', 'seohouse' ) ); ?>" style="display: flex; align-items: center; flex: 0 0 auto; margin-inline-start: auto;"><img src="<?php echo esc_url( sh_logo_light_url() ); ?>" alt="<?php echo esc_attr( sh_site_name() ); ?>" width="145" height="34" fetchpriority="high" style="height: 34px; width: auto; display: block;"></a>
	</div>
</header>
