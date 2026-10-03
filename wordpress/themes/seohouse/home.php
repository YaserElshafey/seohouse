<?php
/**
 * Blog index /blog/ (design "Blog"): hero, category filter, featured latest article, rows, pagination.
 * Title and intro come from the page set as "Posts page".
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

get_header();
$blog_id = (int) get_option( 'page_for_posts' );
$title   = $blog_id ? get_the_title( $blog_id ) : __( 'المدونة', 'seohouse' );
$intro   = $blog_id ? (string) sh_field( 'sh_blog_intro', $blog_id, '' ) : '';
$paged   = max( 1, (int) get_query_var( 'paged' ) );
$cats    = get_categories( array( 'hide_empty' => true ) );
?>
<main id="main" class="sh-main">
	<section data-screen-label="Hero" style="position: relative; overflow: hidden; background: var(--sh-ink); border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
		<div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px); background-size: 72px 72px; mask-image: radial-gradient(80% 110% at 70% 0%, rgb(0, 0, 0), transparent 70%); pointer-events: none;"></div>
		<div aria-hidden="true" style="position: absolute; inset-inline-end: -160px; top: -180px; width: 520px; height: 520px; background: radial-gradient(circle, rgba(var(--sh-blue-rgb), 0.32), transparent 70%); pointer-events: none;"></div>
		<div style="position: relative; max-width: 1100px; margin: 0px auto; padding: 18px 20px clamp(28px, 3.2vw, 40px);">
			<?php sh_breadcrumbs(); ?>
			<h1 style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: clamp(28px, 3vw, 40px); line-height: 1.3; margin: 18px 0px 0px;"><?php echo esc_html( $title ); ?><?php echo $paged > 1 ? ' <span style="font-size:.55em;font-weight:600;color:var(--sh-crumb)">— ' . esc_html( sprintf( /* translators: %d: page */ __( 'صفحة %d', 'seohouse' ), $paged ) ) . '</span>' : ''; ?></h1>
			<?php if ( $intro ) : ?>
			<p style="font-size: 17px; line-height: 1.85; color: var(--sh-muted); margin: 10px 0px 0px; max-width: 34em;"><?php echo esc_html( $intro ); ?></p>
			<?php endif; ?>
		</div>
	</section>
	<section data-screen-label="Posts" style="background: var(--sh-paper-2); color: var(--sh-ink);">
		<div style="max-width: 1100px; margin: 0px auto; padding: clamp(24px, 2.8vw, 36px) 20px clamp(40px, 4.6vw, 60px);">
			<?php if ( count( $cats ) > 1 ) : ?>
			<nav aria-label="<?php esc_attr_e( 'تصنيفات المدونة', 'seohouse' ); ?>" style="display: flex; flex-wrap: wrap; gap: 8px;">
				<a href="<?php echo esc_url( get_permalink( $blog_id ) ); ?>" aria-current="page" style="min-height: 38px; display: inline-flex; align-items: center; padding: 0px 15px; border-radius: 999px; font-size: 14px; font-weight: 600; background: var(--sh-blue); color: rgb(255, 255, 255); box-shadow: var(--sh-blue) 0px 0px 0px 1px inset;"><?php esc_html_e( 'كل المقالات', 'seohouse' ); ?></a>
				<?php foreach ( $cats as $c ) : ?>
				<a href="<?php echo esc_url( get_category_link( $c ) ); ?>" style="min-height: 38px; display: inline-flex; align-items: center; padding: 0px 15px; border-radius: 999px; font-size: 14px; font-weight: 600; background: #fff; color: var(--sh-surface-3); box-shadow: rgba(var(--sh-ink-rgb), 0.12) 0px 0px 0px 1px inset;"><?php echo esc_html( $c->name ); ?></a>
				<?php endforeach; ?>
			</nav>
			<?php endif; ?>
			<?php
			if ( have_posts() ) :
				global $wp_query;
				$lead = 1 === $paged ? $wp_query->posts[0] : null;
				if ( $lead ) :
					$cat    = sh_primary_category( $lead );
					$member = sh_post_author_member( $lead );
					$intro2 = (string) sh_field( 'intro', $lead->ID, '' );
					$intro2 = $intro2 ? $intro2 : get_the_excerpt( $lead );
					?>
			<a href="<?php echo esc_url( get_permalink( $lead ) ); ?>" data-bl-lead class="sh-hv-case" style="margin-top: 22px; display: grid; gap: 14px 32px; align-items: center; background: rgb(255, 255, 255); border-radius: 18px; padding: clamp(18px, 2vw, 26px); box-shadow: rgba(var(--sh-ink-rgb), 0.5) 0px 16px 36px -28px, rgba(var(--sh-ink-rgb), 0.05) 0px 0px 0px 1px; color: var(--sh-ink);">
				<span style="display: block; min-width: 0px;">
					<span style="display: inline-block; font-size: 12.5px; font-weight: 600; color: var(--sh-blue);"><?php echo esc_html( __( 'مقال مميز', 'seohouse' ) . ( $cat ? ' · ' . $cat->name : '' ) ); ?></span>
					<span style="display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(18px, 1.6vw, 21px); line-height: 1.55; margin-top: 8px; max-width: 34em; text-wrap: pretty;"><?php echo esc_html( get_the_title( $lead ) ); ?></span>
					<?php if ( $intro2 ) : ?>
					<span style="display: -webkit-box; font-size: 15px; line-height: 1.8; color: var(--sh-ink-soft); margin-top: 8px; max-width: 40em; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;"><?php echo esc_html( $intro2 ); ?></span>
					<?php endif; ?>
				</span>
				<span data-bl-lead-meta style="display: flex; flex-direction: column; gap: 6px; font-size: 13.5px; color: var(--sh-slate); border-inline-start: 1px solid rgba(var(--sh-ink-rgb), 0.1); padding-inline-start: 22px;">
					<span><?php echo esc_html( sh_post_date( $lead ) ); ?></span><span><?php esc_html_e( 'بقلم', 'seohouse' ); ?> <b style="font-weight: 600; color: var(--sh-surface-3);"><?php echo esc_html( $member ? get_the_title( $member ) : sprintf( /* translators: %s: company */ __( 'فريق %s', 'seohouse' ), sh_site_name() ) ); ?></b></span><span style="font-weight: 600; color: var(--sh-blue); margin-top: 6px;"><?php esc_html_e( 'اقرأ المقال', 'seohouse' ); ?> ←</span>
				</span>
			</a>
					<?php
				endif;
				get_template_part( 'parts/blog/rows', null, array( 'skip_first' => (bool) $lead ) );
			else :
				get_template_part( 'parts/blog/empty' );
			endif;
			?>
		</div>
	</section>
</main>
<?php
get_footer();
