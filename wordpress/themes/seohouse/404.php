<?php
/**
 * 404 (design "404"). WordPress sends a real 404 status for this template.
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="sh-main">
	<section data-screen-label="Hero" style="position: relative; overflow: hidden; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
		<div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px); background-size: 72px 72px; mask-image: radial-gradient(90% 100% at 82% 0%, rgb(0, 0, 0), transparent 68%); pointer-events: none;"></div>
		<div style="position: relative; max-width: 1200px; margin: 0px auto; padding: 18px 20px 0px;"><?php sh_breadcrumbs(); ?></div>
		<div style="position: relative; max-width: 860px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px; text-align: center;">
			<div style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: clamp(15px, 1.4vw, 17px); color: var(--sh-sky);"><?php esc_html_e( 'خطأ 404', 'seohouse' ); ?></div>
			<h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(26px, 3vw, 40px); line-height: 1.35; margin: 14px auto 0px; max-width: 18em;"><?php esc_html_e( 'الصفحة غير موجودة', 'seohouse' ); ?></h1>
			<div style="margin: 26px auto 0px; display: flex; justify-content: center;"><?php get_search_form( array( 'max' => '560px' ) ); ?></div>
			<div style="margin-top: 24px; display: flex; flex-wrap: wrap; justify-content: center; gap: 10px;">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="sh-hv-cta" style="background: var(--sh-lime); color: var(--sh-ink); font-weight: 700; font-size: 15.5px; min-height: 50px; display: inline-flex; align-items: center; padding: 0px 24px; border-radius: 14px;"><?php esc_html_e( 'العودة للرئيسية', 'seohouse' ); ?></a>
				<?php get_template_part( 'parts/quick-links', null, array( 'style' => 'buttons' ) ); ?>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
