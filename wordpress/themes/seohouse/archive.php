<?php
/**
 * Category (and other article archives) — design "Blog Category Template".
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

get_header();
$term    = get_queried_object();
$is_cat  = is_category();
$title   = $is_cat ? single_cat_title( '', false ) : wp_strip_all_tags( get_the_archive_title() );
$desc    = $is_cat ? wp_strip_all_tags( (string) category_description() ) : '';
$blog_id = (int) get_option( 'page_for_posts' );
$cats    = get_categories( array( 'hide_empty' => true ) );
$chip    = 'font-size: 14.5px; padding: 10px 16px; border-radius: 999px;';
?>
<main id="main" class="sh-main">
	<section data-screen-label="Hero" style="position: relative; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
		<div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px); background-size: 72px 72px; mask-image: radial-gradient(90% 100% at 88% 20%, rgb(0, 0, 0), transparent 68%); pointer-events: none;"></div>
		<div style="position: relative; max-width: 1200px; margin: 0px auto; padding: 18px 20px 0px;"><?php sh_breadcrumbs(); ?></div>
		<div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(18px, 2.2vw, 30px) 20px clamp(22px, 2.6vw, 32px);">
			<div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?php echo $is_cat ? esc_html__( 'تصنيف', 'seohouse' ) : esc_html__( 'أرشيف', 'seohouse' ); ?></div>
			<h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(26px, 2.8vw, 38px); line-height: 1.3; margin: 10px 0px 0px;"><?php echo esc_html( $title ); ?></h1>
			<?php if ( $desc ) : ?>
			<p style="font-size: 16px; color: var(--sh-muted); margin: 10px 0px 0px; max-width: 44em;"><?php echo esc_html( $desc ); ?></p>
			<?php endif; ?>
			<?php if ( count( $cats ) > 1 || ! $is_cat ) : ?>
			<div style="margin-top: 20px;">
				<div role="list" style="display: flex; flex-wrap: wrap; gap: 10px;">
					<a role="listitem" href="<?php echo esc_url( get_permalink( $blog_id ) ); ?>" class="sh-hv-chip" style="<?php echo esc_attr( $chip ); ?> color: var(--sh-crumb-current); background: rgba(255, 255, 255, 0.05);"><?php esc_html_e( 'الكل', 'seohouse' ); ?></a>
					<?php foreach ( $cats as $c ) : ?>
						<?php $on = $is_cat && (int) $c->term_id === (int) $term->term_id; ?>
					<a role="listitem" href="<?php echo esc_url( get_category_link( $c ) ); ?>" class="sh-hv-chip"<?php echo $on ? ' aria-current="page"' : ''; ?> style="<?php echo esc_attr( $chip . ( $on ? ' background: var(--sh-lime); color: var(--sh-ink); font-weight: 700;' : ' color: var(--sh-crumb-current); background: rgba(255, 255, 255, 0.05);' ) ); ?>"><?php echo esc_html( $c->name ); ?></a>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</section>
	<section data-screen-label="List" style="background: var(--sh-paper-2); color: var(--sh-ink);">
		<div style="max-width: 1100px; margin: 0px auto; padding: clamp(24px, 2.8vw, 36px) 20px clamp(40px, 4.6vw, 60px);">
			<?php
			if ( have_posts() ) {
				get_template_part( 'parts/blog/rows' );
			} else {
				get_template_part( 'parts/blog/empty', null, array( 'title' => __( 'لا توجد مقالات منشورة في هذا التصنيف بعد.', 'seohouse' ) ) );
			}
			?>
		</div>
	</section>
</main>
<?php
get_footer();
