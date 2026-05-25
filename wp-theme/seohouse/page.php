<?php
/**
 * Default page template
 */
get_header();
?>

<?php
get_template_part( 'template-parts/layout/page-hero', null, [
    'title'       => get_the_title(),
    'breadcrumb'  => [ get_the_title() => '' ],
] );
?>

<section class="sec sec-white">
  <div class="wrap">
    <div style="max-width:780px">
      <?php
      while ( have_posts() ) : the_post();
          the_content();
      endwhile;
      ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
