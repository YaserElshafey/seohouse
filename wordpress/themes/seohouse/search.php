<?php
/**
 * Search results (design "Search Results"): /?s=… — noindex, real results and an empty state.
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

get_header();
$types = array(
	'page'        => __( 'صفحة', 'seohouse' ),
	'post'        => __( 'مقال', 'seohouse' ),
	'case_study'  => __( 'نتيجة', 'seohouse' ),
	'team_member' => __( 'فريق العمل', 'seohouse' ),
);
?>
<main id="main" class="sh-main">
	<section data-screen-label="Hero" style="position: relative; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
		<div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px); background-size: 72px 72px; mask-image: radial-gradient(90% 100% at 50% 0%, rgb(0, 0, 0), transparent 68%); pointer-events: none;"></div>
		<div style="position: relative; max-width: 1200px; margin: 0px auto; padding: 18px 20px 0px;"><?php sh_breadcrumbs(); ?></div>
		<div style="position: relative; max-width: 1000px; margin: 0px auto; padding: clamp(18px, 2.2vw, 30px) 20px clamp(24px, 2.8vw, 34px);">
			<h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(24px, 2.6vw, 34px); line-height: 1.35; margin: 0px;">
				<?php
				echo get_search_query()
					? esc_html( sprintf( /* translators: %s: search terms */ __( 'نتائج البحث عن «%s»', 'seohouse' ), get_search_query() ) )
					: esc_html__( 'البحث في الموقع', 'seohouse' );
				?>
			</h1>
			<div style="margin-top: 18px;"><?php get_search_form(); ?></div>
		</div>
	</section>
	<section data-screen-label="Results" style="position: relative; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
		<div style="position: relative; max-width: 1000px; margin: 0px auto; padding: clamp(24px, 3vw, 40px) 20px clamp(36px, 4.2vw, 58px);">
			<?php if ( have_posts() ) : ?>
			<div>
				<div style="font-size: 14px; color: var(--sh-crumb);"><?php echo esc_html( sprintf( /* translators: %d: count */ __( 'عدد النتائج: %d', 'seohouse' ), (int) $GLOBALS['wp_query']->found_posts ) ); ?></div>
				<div style="margin-top: 12px;">
					<?php
					while ( have_posts() ) :
						the_post();
						$type    = get_post_type();
						$excerpt = function_exists( 'sh_seo_description' ) ? '' : '';
						$excerpt = (string) sh_field( 'sh_seo_description', get_the_ID(), '' );
						if ( ! $excerpt ) {
							$excerpt = 'case_study' === $type ? (string) sh_field( 'summary', get_the_ID(), '' ) : get_the_excerpt();
						}
						$path = rawurldecode( (string) wp_parse_url( get_permalink(), PHP_URL_PATH ) );
						?>
					<a href="<?php the_permalink(); ?>" class="sh-hv-link" style="display: block; padding: 18px 4px; border-top: 1px solid rgba(255, 255, 255, 0.12); color: var(--sh-text);">
						<span style="display: block; font-size: 12.5px; font-weight: 600; color: var(--sh-sky);"><?php echo esc_html( $types[ $type ] ?? '' ); ?></span>
						<span style="display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18px; margin-top: 6px;"><?php the_title(); ?></span>
						<?php if ( $excerpt ) : ?>
						<span style="display: block; font-size: 15px; color: var(--sh-muted); margin-top: 6px;"><?php echo esc_html( wp_trim_words( wp_strip_all_tags( $excerpt ), 30, '…' ) ); ?></span>
						<?php endif; ?>
						<span dir="ltr" style="display: block; font-size: 13px; color: var(--sh-crumb); margin-top: 6px; text-align: end;"><?php echo esc_html( $path ); ?></span>
					</a>
					<?php endwhile; ?>
				</div>
				<?php
				the_posts_pagination(
					array(
						'mid_size'           => 1,
						'prev_text'          => __( '→ السابق', 'seohouse' ),
						'next_text'          => __( 'التالي ←', 'seohouse' ),
						'screen_reader_text' => __( 'ترقيم الصفحات', 'seohouse' ),
						'class'              => 'sh-pagination',
					)
				);
				?>
			</div>
			<?php else : ?>
			<div>
				<h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28; margin: 0;"><?php esc_html_e( 'لا توجد نتائج مطابقة', 'seohouse' ); ?></h2>
				<p style="font-size: 16px; color: var(--sh-muted); margin: 12px 0 0;"><?php esc_html_e( 'جرّب كلمة أقصر أو مختلفة، أو ابدأ من أحد الأقسام التالية.', 'seohouse' ); ?></p>
				<div style="margin-top: 20px; display: flex; flex-wrap: wrap; gap: 10px;">
					<?php get_template_part( 'parts/quick-links', null, array( 'paths' => array( '/services/', '/services/seo/', '/sectors/', '/blog/', '/results/' ) ) ); ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php
get_footer();
