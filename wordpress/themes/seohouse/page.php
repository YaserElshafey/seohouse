<?php
/**
 * Default page template (pages without a design template).
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main" class="sh-main">
	<section style="max-width: 880px; margin: 0 auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
		<?php sh_breadcrumbs(); ?>
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(28px, 3.2vw, 42px); line-height: 1.3; margin: 20px 0 0;"><?php the_title(); ?></h1>
			<div class="sh-prose" style="margin-top: 24px;"><?php the_content(); ?></div>
		<?php endwhile; ?>
	</section>
</main>
<?php
get_footer();
