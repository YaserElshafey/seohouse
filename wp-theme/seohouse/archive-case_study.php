<?php
/**
 * Case study archive (redirects to Results page template)
 */

$results_page = get_page_by_path( 'results' );
if ( $results_page ) {
    wp_redirect( get_permalink( $results_page->ID ), 301 );
    exit;
}

// Fallback: render inline
get_header();
?>

<?php
get_template_part( 'template-parts/layout/page-hero', null, [
    'tag'         => 'نتائج فعلية',
    'title'       => 'نتائج من <em>مشاريع حقيقية</em>',
    'breadcrumb'  => [ 'نتائج الأعمال' => '' ],
] );
?>

<section class="sec sec-surface">
  <div class="wrap">
    <div class="results-grid">
      <?php if ( have_posts() ) :
          while ( have_posts() ) : the_post();
              get_template_part( 'template-parts/cards/case-study-card' );
          endwhile;
      endif; ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
