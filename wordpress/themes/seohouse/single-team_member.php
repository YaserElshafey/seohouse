<?php
/**
 * Team member profile (design "Team Member"): photo, name, role, experience, specialties,
 * links, articles written by the member (linked author account or article field), colleagues.
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

get_header();
while ( have_posts() ) :
	the_post();
	$id    = get_the_ID();
	$f     = function_exists( 'get_fields' ) ? (array) get_fields( $id ) : array();
	$name  = get_the_title();
	$tags  = array_filter( wp_list_pluck( (array) ( $f['specialties'] ?? array() ), 'label' ) );
	$links = array_filter( (array) ( $f['links'] ?? array() ), static fn( $l ) => ! empty( $l['url'] ) && ! empty( $l['label'] ) );
	$team  = get_page_by_path( 'team' );

	// Articles: posts whose "author_member" is this profile, or written by the linked WordPress account.
	$meta_q = array( array( 'key' => 'author_member', 'value' => $id ) );
	$q_args = array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 6, 'no_found_rows' => true );
	$articles  = get_posts( $q_args + array( 'meta_query' => $meta_q ) ); // phpcs:ignore WordPress.DB.SlowDBQuery
	if ( ! empty( $f['author'] ) ) {
		$articles = array_merge( $articles, get_posts( $q_args + array( 'author' => (int) $f['author'] ) ) );
		$articles = array_values( array_unique( $articles, SORT_REGULAR ) );
	}
	// design: the next three members in team order (wrapping around)
	$all   = array_values( sh_team_members() );
	$pos   = (int) array_search( $id, wp_list_pluck( $all, 'ID' ), true );
	$mates = array();
	for ( $k = 1; $k <= min( 3, count( $all ) - 1 ); $k++ ) {
		$mates[] = $all[ ( $pos + $k ) % count( $all ) ];
	}
	?>
<main id="main" class="sh-main">
	<section data-screen-label="Profile" style="position: relative; border-bottom: 1px solid var(--sh-line);">
		<div class="sh-crumbs-light" style="max-width: 1100px; margin: 0px auto; padding: 18px 20px 0px;"><?php sh_breadcrumbs(); ?></div>
		<div data-pf style="max-width: 1100px; margin: 0px auto; padding: clamp(22px, 3vw, 40px) 20px clamp(32px, 4.4vw, 56px);">
			<?php if ( has_post_thumbnail() ) : ?>
			<div data-pf-ph style="border-radius: 16px; overflow: hidden; background: var(--sh-surface);">
				<?php the_post_thumbnail( 'medium_large', array( 'alt' => $name, 'loading' => 'eager', 'fetchpriority' => 'high', 'data-tm-img' => '', 'style' => 'width: 100%; height: 100%; object-fit: cover; object-position: center top; display: block;' ) ); ?>
			</div>
			<?php endif; ?>
			<div style="min-width: 0px;">
				<div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?php echo esc_html( sprintf( /* translators: %s: company */ __( 'فريق %s', 'seohouse' ), sh_site_name() ) ); ?></div>
				<h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(26px, 3vw, 38px); line-height: 1.3; margin: 10px 0px 0px;"><?php echo esc_html( $name ); ?></h1>
				<?php if ( ! empty( $f['role'] ) ) : ?>
				<p style="font-size: 17px; color: var(--sh-ink); margin: 8px 0px 0px;"><?php echo esc_html( $f['role'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $f['experience'] ) ) : ?>
				<p style="font-size: 14.5px; color: var(--sh-text); margin: 6px 0px 0px;"><?php echo esc_html( $f['experience'] ); ?></p>
				<?php endif; ?>
				<?php if ( $tags ) : ?>
				<div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 16px;">
					<?php foreach ( $tags as $t ) : ?>
					<span style="font-size: 13.5px; color: var(--sh-ink); background: var(--sh-bg); border-radius: 999px; padding: 7px 13px;"><?php echo esc_html( $t ); ?></span>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
				<?php if ( ! empty( $f['bio'] ) ) : ?>
				<div class="sh-prose" style="margin-top: 18px; font-size: 16px;"><?php echo wp_kses_post( $f['bio'] ); ?></div>
				<?php endif; ?>
				<div style="display: flex; flex-wrap: wrap; align-items: center; gap: 12px 20px; margin-top: 22px;">
					<?php if ( ! empty( $f['linkedin'] ) ) : ?>
					<a href="<?php echo esc_url( $f['linkedin'] ); ?>" target="_blank" rel="noopener me" class="sh-hv-outline" style="display: inline-flex; align-items: center; min-height: 46px; padding: 0px 18px; border-radius: 12px; border: 1px solid var(--sh-line); color: var(--sh-ink); font-size: 14.5px; font-weight: 600;">LinkedIn <span aria-hidden="true">↗</span></a>
					<?php endif; ?>
					<?php foreach ( $links as $l ) : ?>
					<a href="<?php echo esc_url( $l['url'] ); ?>" target="_blank" rel="noopener me" class="sh-hv-outline" style="display: inline-flex; align-items: center; min-height: 46px; padding: 0px 18px; border-radius: 12px; border: 1px solid var(--sh-line); color: var(--sh-ink); font-size: 14.5px; font-weight: 600;"><?php echo esc_html( $l['label'] ); ?> <span aria-hidden="true">↗</span></a>
					<?php endforeach; ?>
					<?php if ( $team ) : ?>
					<a href="<?php echo esc_url( get_permalink( $team ) ); ?>" class="sh-hv-link" style="font-size: 14.5px; font-weight: 600; color: var(--sh-link);"><?php esc_html_e( 'كل أعضاء الفريق', 'seohouse' ); ?> <span aria-hidden="true">←</span></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<section id="articles" data-screen-label="Articles" style="position: relative; scroll-margin-top: 88px; border-bottom: 1px solid var(--sh-line);">
		<div style="max-width: 1100px; margin: 0px auto; padding: clamp(32px, 4.4vw, 56px) 20px;">
			<h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); line-height: 1.35; margin: 0px;"><?php echo esc_html( sprintf( /* translators: %s: name */ __( 'مقالات %s', 'seohouse' ), $name ) ); ?></h2>
			<?php if ( $articles ) : ?>
			<div data-pf-mates style="margin-top: 18px;">
				<?php foreach ( $articles as $p ) : ?>
				<a href="<?php echo esc_url( get_permalink( $p ) ); ?>" data-hcard class="sh-hv-mate" style="display: flex; flex-direction: column; gap: 4px; border-radius: 14px; background: rgba(255, 255, 255, 0.035); padding: 14px 16px; color: var(--sh-ink);">
					<span style="display: block; font-weight: 600; font-size: 15.5px; line-height: 1.6;"><?php echo esc_html( get_the_title( $p ) ); ?></span>
					<span style="display: block; font-size: 13.5px; color: var(--sh-text);"><?php echo esc_html( sh_post_date( $p ) ); ?></span>
				</a>
				<?php endforeach; ?>
			</div>
			<?php else : ?>
			<p style="font-size: 15.5px; color: var(--sh-ink); margin: 12px 0px 0px;"><?php esc_html_e( 'لا توجد مقالات منشورة لهذا العضو حتى الآن.', 'seohouse' ); ?></p>
			<?php endif; ?>
			<a href="<?php echo esc_url( get_permalink( (int) get_option( 'page_for_posts' ) ) ); ?>" class="sh-hv-link" style="display: inline-block; margin-top: 12px; font-size: 14.5px; font-weight: 600; color: var(--sh-link);"><?php esc_html_e( 'تصفح المدونة', 'seohouse' ); ?> <span aria-hidden="true">←</span></a>
		</div>
	</section>

	<?php if ( $mates ) : ?>
	<section data-screen-label="Colleagues" style="position: relative; border-bottom: 1px solid var(--sh-line);">
		<div style="max-width: 1100px; margin: 0px auto; padding: clamp(32px, 4.4vw, 56px) 20px;">
			<h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); line-height: 1.35; margin: 0px;"><?php esc_html_e( 'زملاء في الفريق', 'seohouse' ); ?></h2>
			<div data-pf-mates style="margin-top: 18px;">
				<?php foreach ( $mates as $m ) : ?>
				<a href="<?php echo esc_url( get_permalink( $m ) ); ?>" data-hcard class="sh-hv-mate" style="display: flex; align-items: center; gap: 12px; border-radius: 14px; background: rgba(255, 255, 255, 0.035); padding: 10px; color: var(--sh-ink);">
					<?php echo get_the_post_thumbnail( $m, 'thumbnail', array( 'alt' => '', 'loading' => 'lazy', 'data-tm-img' => '', 'style' => 'flex: 0 0 auto; width: 64px; height: 64px; border-radius: 10px; object-fit: cover; object-position: center top;' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<span style="min-width: 0px;"><span style="display: block; font-weight: 600; font-size: 15.5px;"><?php echo esc_html( get_the_title( $m ) ); ?></span><span style="display: block; font-size: 13.5px; color: var(--sh-text); margin-top: 3px;"><?php echo esc_html( (string) sh_field( 'role', $m->ID, '' ) ); ?></span></span>
				</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<?php get_template_part( 'sections/shared/booking', null, array( 'f' => array( 'service' => '' ) ) ); ?>
</main>
	<?php
endwhile;
get_footer();
