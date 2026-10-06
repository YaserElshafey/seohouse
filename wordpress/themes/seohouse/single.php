<?php
/**
 * Single article (design "Single Article Template"). The article body is native Gutenberg content;
 * the header, table of contents (from H2), latest posts and side CTA follow the design.
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

get_header();
while ( have_posts() ) :
	the_post();
	$id      = get_the_ID();
	$cat     = sh_primary_category();
	$member  = sh_post_author_member();
	$minutes = sh_reading_minutes();
	$content = apply_filters( 'the_content', get_the_content() ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals -- core filter.

	// Table of contents from H2 headings (ids added where missing).
	$toc = array();
	if ( sh_field( 'toc', $id, true ) ) {
		$n       = 0;
		$content = preg_replace_callback(
			'#<h2([^>]*)>(.*?)</h2>#su',
			static function ( $m ) use ( &$toc, &$n ) {
				++$n;
				$attrs = $m[1];
				if ( preg_match( '/\sid="([^"]+)"/', $attrs, $idm ) ) {
					$hid = $idm[1];
				} else {
					$hid    = 's' . $n;
					$attrs .= ' id="' . $hid . '"';
				}
				$toc[] = array( $hid, wp_strip_all_tags( $m[2] ) );
				return '<h2' . $attrs . '>' . $m[2] . '</h2>';
			},
			$content
		);
	}
	$latest = get_posts( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 3, 'post__not_in' => array( $id ), 'no_found_rows' => true ) );
	$cta    = (array) sh_option( 'sh_article_cta', array() );
	$ink    = 'rgb(11, 18, 48)';
	$sep    = '<span aria-hidden="true" style="width: 4px; height: 4px; border-radius: 999px; background: rgba(6, 11, 31, 0.25);"></span>';
	?>
<main id="main" class="sh-main">
	<section data-screen-label="Article" style="background: rgb(247, 249, 252); color: <?php echo esc_attr( $ink ); ?>;">
		<div style="max-width: 1120px; margin: 0px auto; padding: 22px 20px 0px;">
			<div class="sh-crumbs-light"><?php sh_breadcrumbs(); ?></div>
			<header style="max-width: 820px; margin: 26px auto 0px; text-align: center;">
				<?php if ( $cat ) : ?>
				<a href="<?php echo esc_url( get_category_link( $cat ) ); ?>" style="display: inline-block; font-size: 13px; font-weight: 600; color: var(--sh-blue); background: rgba(var(--sh-blue-rgb), 0.08); border-radius: 999px; padding: 5px 12px;"><?php echo esc_html( $cat->name ); ?></a>
				<?php endif; ?>
				<h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(26px, 2.9vw, 38px); line-height: 1.45; margin: 14px 0px 0px; color: rgb(6, 11, 31); text-wrap: pretty;"><?php the_title(); ?></h1>
				<?php $intro = (string) sh_field( 'intro', $id, '' ); ?>
				<?php if ( $intro ) : ?>
				<p style="font-size: 17px; line-height: 1.9; color: var(--sh-text); margin: 12px auto 0; max-width: 40em;"><?php echo esc_html( $intro ); ?></p>
				<?php endif; ?>
				<div style="margin-top: 16px; display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 6px 18px; font-size: 14px; color: var(--sh-slate);">
					<span style="white-space: nowrap;"><?php esc_html_e( 'بقلم', 'seohouse' ); ?>
					<?php if ( $member ) : ?>
						<a href="<?php echo esc_url( get_permalink( $member ) ); ?>" rel="author" style="font-weight: 600; color: <?php echo esc_attr( $ink ); ?>; border-bottom: 1px solid rgba(var(--sh-blue-rgb), 0.4);"><?php echo esc_html( get_the_title( $member ) ); ?></a>
					<?php elseif ( '' !== trim( (string) get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $id ) ) ) ) : ?>
						<?php // no team profile linked: the article's WordPress author, as on the main site ?>
						<span style="font-weight: 600; color: <?php echo esc_attr( $ink ); ?>;"><?php echo esc_html( get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $id ) ) ); ?></span>
					<?php else : ?>
						<?php $team = get_page_by_path( 'team' ); ?>
						<a href="<?php echo esc_url( $team ? get_permalink( $team ) : home_url( '/' ) ); ?>" style="font-weight: 600; color: <?php echo esc_attr( $ink ); ?>; border-bottom: 1px solid rgba(var(--sh-blue-rgb), 0.4);"><?php echo esc_html( sprintf( /* translators: %s: company */ __( 'فريق %s', 'seohouse' ), sh_site_name() ) ); ?></a>
					<?php endif; ?>
					</span><?php echo $sep; // phpcs:ignore ?>
					<span style="white-space: nowrap;"><?php esc_html_e( 'نُشر في', 'seohouse' ); ?> <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( sh_post_date() ); ?></time></span>
					<?php if ( sh_field( 'updated_label', $id, true ) ) : ?>
						<?php echo $sep; // phpcs:ignore ?><span style="white-space: nowrap;"><?php esc_html_e( 'حُدّث في', 'seohouse' ); ?> <time datetime="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>"><?php echo esc_html( sh_post_date( null, 'modified' ) ); ?></time></span>
					<?php endif; ?>
					<?php echo $sep; // phpcs:ignore ?><span style="white-space: nowrap;"><?php echo esc_html( sh_reading_label( $minutes ) ); ?></span>
				</div>
			</header>
			<?php if ( has_post_thumbnail() ) : ?>
			<figure style="margin: 28px auto 0px; max-width: 960px;">
				<?php the_post_thumbnail( 'large', array( 'loading' => 'eager', 'fetchpriority' => 'high', 'style' => 'display: block; width: 100%; height: auto; border-radius: 14px; background: rgb(231, 236, 246);' ) ); ?>
			</figure>
			<?php endif; ?>
		</div>
		<div data-art-layout style="max-width: 1160px; margin: 0px auto; padding: 32px 20px clamp(40px, 5vw, 64px); display: grid; gap: 24px 40px; align-items: start;">
			<article data-art-body style="grid-area: body; min-width: 0px; background: rgb(255, 255, 255); border-radius: 18px; padding: clamp(20px, 3.2vw, 44px); box-shadow: 0 1px 0 var(--sh-line), 0 18px 40px -34px rgba(6,11,31,.35);">
				<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput -- the_content output. ?>
			</article>
			<?php if ( $toc ) : ?>
			<aside data-art-toc aria-label="<?php esc_attr_e( 'محتوى المقال', 'seohouse' ); ?>" style="grid-area: toc; min-width: 0px;">
				<details style="border-radius: 14px; background: transparent; border: 1px solid var(--sh-line); padding: 14px 16px;">
					<summary style="cursor: pointer; font-weight: 700; font-size: 14.5px; color: <?php echo esc_attr( $ink ); ?>;"><?php esc_html_e( 'محتوى المقال', 'seohouse' ); ?></summary>
					<ol style="margin: 10px 0px 0px; padding: 0px 18px 0px 0px; display: flex; flex-direction: column; gap: 7px; font-size: 13.5px; line-height: 1.6;">
						<?php foreach ( $toc as $t ) : ?>
						<li><a href="#<?php echo esc_attr( $t[0] ); ?>" class="sh-hv-blue" style="color: var(--sh-text);"><?php echo esc_html( $t[1] ); ?></a></li>
						<?php endforeach; ?>
					</ol>
				</details>
			</aside>
			<?php endif; ?>
			<aside data-art-extra aria-label="<?php esc_attr_e( 'من المدونة', 'seohouse' ); ?>" style="grid-area: extra; min-width: 0px; display: flex; flex-direction: column; gap: 18px;">
				<?php if ( $latest ) : ?>
				<div style="border-top: 2px solid var(--sh-blue); padding-top: 14px;">
					<div style="font-weight: 700; font-size: 14.5px; color: <?php echo esc_attr( $ink ); ?>;"><?php esc_html_e( 'أحدث المقالات', 'seohouse' ); ?></div>
					<div style="margin-top: 6px; display: flex; flex-direction: column;">
						<?php
						foreach ( $latest as $p ) :
							$pc = sh_primary_category( $p );
							?>
						<a href="<?php echo esc_url( get_permalink( $p ) ); ?>" class="sh-hv-blue" style="display: block; padding: 12px 0px; border-bottom: 1px solid rgba(var(--sh-ink-rgb), 0.08); color: <?php echo esc_attr( $ink ); ?>;">
							<?php if ( $pc ) : ?><span style="display: block; font-size: 12px; font-weight: 600; color: var(--sh-link);"><?php echo esc_html( $pc->name ); ?></span><?php endif; ?>
							<span style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; font-family: Alexandria, sans-serif; font-weight: 600; font-size: 14px; line-height: 1.65; margin-top: 4px;"><?php echo esc_html( get_the_title( $p ) ); ?></span>
							<span style="display: block; font-size: 12px; color: var(--sh-text); margin-top: 4px;"><?php echo esc_html( sh_post_date( $p ) ); ?></span>
						</a>
						<?php endforeach; ?>
					</div>
					<a href="<?php echo esc_url( get_permalink( (int) get_option( 'page_for_posts' ) ) ); ?>" style="display: inline-block; margin-top: 10px; font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?php esc_html_e( 'كل المقالات', 'seohouse' ); ?> <span aria-hidden="true">←</span></a>
				</div>
				<?php endif; ?>
				<?php if ( sh_field( 'sidebar_cta', $id, true ) && ! empty( $cta['title'] ) ) : ?>
				<div data-art-sidecta style="position: relative; overflow: hidden; border-radius: 16px; background: linear-gradient(155deg, rgb(26, 59, 214), rgb(47, 91, 255)); color: rgb(255, 255, 255); padding: 22px 18px; text-align: center; display: flex; flex-direction: column; align-items: center;">
					<span aria-hidden="true" style="width: 40px; height: 40px; border-radius: 12px; background: rgba(255, 255, 255, 0.14); display: flex; align-items: center; justify-content: center; margin-bottom: 12px;"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"></path></svg></span>
					<div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16px; line-height: 1.5;"><?php echo esc_html( $cta['title'] ); ?></div>
					<?php if ( ! empty( $cta['text'] ) ) : ?>
					<p style="margin: 8px 0px 0px; font-size: 13.5px; line-height: 1.8; color: rgb(255, 255, 255); max-width: 22em;"><?php echo esc_html( $cta['text'] ); ?></p>
					<?php endif; ?>
					<a href="<?php echo esc_url( ! empty( $cta['link'] ) ? sh_link( $cta['link'] ) : '#booking' ); ?>" class="sh-hv-onblue" style="display: flex; align-items: center; justify-content: center; width: 100%; max-width: 240px; min-height: 44px; margin-top: 16px; background: rgb(255, 255, 255); color: rgb(33, 72, 216); font-weight: 700; font-size: 14.5px; border-radius: 11px; padding: 0px 16px; transition: background 0.2s;"><?php echo esc_html( $cta['label'] ?? __( 'احجز استشارة', 'seohouse' ) ); ?></a>
				</div>
				<?php endif; ?>
			</aside>
		</div>
	</section>
	<?php get_template_part( 'sections/shared/booking', null, array( 'f' => array( 'service' => 'seo' ) ) ); ?>
</main>
	<?php
endwhile;
get_footer();
