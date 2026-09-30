<?php
/**
 * Footer (design: identity column + 4 link columns + legal bar).
 * The front page uses the design's variant (dot pattern, white column titles).
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

$is_home_variant = is_front_page();
$cols            = sh_nav_footer();
$legal           = sh_nav_legal();
$about           = (string) sh_option( 'sh_footer_text', '' );
$cta             = sh_header_cta();
$show_cta        = 'thank-you' !== sh_view_key() && $cta['label'];
$copyright       = (string) sh_option( 'sh_copyright', '' );
$copyright       = $copyright ? str_replace( '{year}', wp_date( 'Y' ), $copyright ) : '© ' . wp_date( 'Y' ) . ' ' . sh_site_name();
$socials         = (array) sh_option( 'sh_socials', array() );
$title_style     = $is_home_variant
	? 'font-family: Alexandria, sans-serif; font-weight: 700; font-size: 14.5px; color: var(--sh-text);'
	: 'font-family: Alexandria, sans-serif; font-weight: 700; font-size: 13px; color: var(--sh-sky);';
?>
<footer style="position: relative; background: var(--sh-ink); border-top: 1px solid rgba(var(--sh-sky-rgb), 0.14); overflow: hidden;">
	<div aria-hidden="true" style="position: absolute; inset-inline: 0px; top: 0px; height: 2px; background: linear-gradient(90deg, transparent, rgba(var(--sh-sky-rgb), 0.5) 25%, rgba(var(--sh-lime-rgb), 0.45) 55%, transparent);"></div>
	<?php if ( $is_home_variant ) : ?>
	<div aria-hidden="true" style="position: absolute; inset: 0px; opacity: 0.06; background-image: radial-gradient(var(--sh-sky) 1px, transparent 1px); background-size: 26px 26px; mask-image: linear-gradient(transparent, rgb(0, 0, 0) 60%); pointer-events: none;"></div>
	<?php endif; ?>
	<div data-grid="footer" style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(40px, 4.4vw, 56px) 20px clamp(28px, 3vw, 40px); display: grid; gap: 32px 40px;">
		<div>
			<img src="<?php echo esc_url( sh_logo_url() ); ?>" alt="<?php echo esc_attr( sh_site_name() ); ?>" width="144" height="36" loading="lazy" style="height: 36px; width: auto; display: block;">
			<?php if ( $about ) : ?>
			<p style="color: var(--sh-muted); font-size: 15px; line-height: 1.85; margin: 16px 0px 0px; max-width: 26em; text-wrap: pretty;"><?php echo esc_html( $about ); ?></p>
			<?php endif; ?>
			<?php if ( $show_cta ) : ?>
			<a href="<?php echo esc_url( $cta['url'] ); ?>" style="display: inline-flex; align-items: center; gap: 8px; margin-top: 20px; font-size: 14.5px; font-weight: 600; color: var(--sh-lime); border-bottom: 1px solid rgba(var(--sh-lime-rgb), 0.45); padding-bottom: 3px;"><?php echo esc_html( $cta['label'] ); ?> <span aria-hidden="true">←</span></a>
			<?php endif; ?>
			<?php if ( $socials ) : ?>
			<div style="display: flex; flex-wrap: wrap; gap: 14px; margin-top: 18px;">
				<?php foreach ( $socials as $s ) : ?>
					<?php if ( ! empty( $s['url'] ) && ! empty( $s['label'] ) ) : ?>
				<a href="<?php echo esc_url( $s['url'] ); ?>" rel="noopener me" target="_blank" class="sh-hv-link" style="color: var(--sh-muted); font-size: 14px;"><?php echo esc_html( $s['label'] ); ?></a>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</div>
		<?php foreach ( $cols as $col ) : ?>
		<div data-fcol style="min-width: 0px;">
			<div style="<?php echo esc_attr( $title_style ); ?>"><?php echo esc_html( $col['label'] ); ?></div>
			<div style="display: flex; flex-direction: column; gap: 2px; margin-top: 14px;">
				<?php foreach ( $col['children'] as $it ) : ?>
				<a href="<?php echo esc_url( $it['url'] ); ?>" class="sh-hv-link" style="color: var(--sh-muted); font-size: 14.5px; padding: 5px 0px; transition: color 0.2s;"><?php echo esc_html( $it['label'] ); ?></a>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
	<div style="position: relative; border-top: 1px solid rgba(255, 255, 255, 0.08);">
		<div style="max-width: 1200px; margin: 0px auto; padding: 18px 20px; display: flex; flex-wrap: wrap; gap: 12px 20px; justify-content: space-between; font-size: 13px; color: var(--sh-crumb);">
			<span><?php echo esc_html( $copyright ); ?></span>
			<?php if ( $legal ) : ?>
			<span style="display: flex; gap: 20px;">
				<?php foreach ( $legal as $l ) : ?>
				<a href="<?php echo esc_url( $l['url'] ); ?>" style="color: var(--sh-crumb);"><?php echo esc_html( $l['label'] ); ?></a>
				<?php endforeach; ?>
			</span>
			<?php endif; ?>
		</div>
	</div>
</footer>
