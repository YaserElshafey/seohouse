<?php
/**
 * Case study archive — renders the full results page.
 * If a static "results" page exists (with template-results.php), redirect to it.
 * Otherwise, render the full results UI directly so /results/ always works.
 */

$results_page = get_page_by_path( 'results' );
if ( $results_page && get_post_meta( $results_page->ID, '_wp_page_template', true ) !== '' ) {
    wp_redirect( get_permalink( $results_page->ID ), 301 );
    exit;
}

get_header();

$sectors_list = get_terms( [
    'taxonomy'   => 'case_study_sector',
    'hide_empty' => true,
] );
?>

<?php
get_template_part( 'template-parts/layout/page-hero', null, [
    'tag'         => 'نتائج فعلية',
    'title'       => 'نتائج من <em>مشاريع حقيقية</em>',
    'description' => 'لا نتحدث عن نتائج افتراضية — هذه مشاريع نفّذناها لعملاء حقيقيين في قطاعات متنوعة. الأرقام الدقيقة تُضاف بعد موافقة العملاء.',
    'breadcrumb'  => [ 'نتائج الأعمال' => '' ],
] );
?>

<section class="sec sec-surface">
  <div class="wrap">

    <!-- Filter buttons -->
    <div class="filter-row sr">
      <button class="filter-btn act" data-filter="all">الكل</button>
      <?php if ( ! is_wp_error( $sectors_list ) && ! empty( $sectors_list ) ) :
          foreach ( $sectors_list as $term ) : ?>
            <button class="filter-btn" data-filter="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></button>
          <?php endforeach;
      else : ?>
        <button class="filter-btn" data-filter="ecommerce">التجارة الإلكترونية</button>
        <button class="filter-btn" data-filter="health">الصحة والطب</button>
        <button class="filter-btn" data-filter="realestate">العقارات</button>
        <button class="filter-btn" data-filter="education">التعليم</button>
        <button class="filter-btn" data-filter="services">الخدمات</button>
      <?php endif; ?>
    </div>

    <?php
    $cases = new WP_Query( [
        'post_type'      => 'case_study',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ] );
    ?>

    <?php if ( $cases->have_posts() ) : ?>
    <div class="results-grid">
      <?php
      $delays = [ '', 'd1', 'd2', 'd1', 'd2', 'd3' ];
      $ci     = 0;
      while ( $cases->have_posts() ) : $cases->the_post();
          $terms       = get_the_terms( get_the_ID(), 'case_study_sector' );
          $sector      = ( ! is_wp_error( $terms ) && ! empty( $terms ) ) ? $terms[0]->name : '';
          $sector_slug = ( ! is_wp_error( $terms ) && ! empty( $terms ) ) ? $terms[0]->slug : '';
          $bars        = sh_case_chart_bars( get_the_ID() );
          $metrics     = sh_field( 'metrics' );
          $dc          = $delays[ $ci % count( $delays ) ];
          $ci++;
      ?>
      <div class="r-card sr <?php echo esc_attr( $dc ); ?>" data-sector="<?php echo esc_attr( $sector_slug ); ?>">
        <div class="r-screen">
          <div class="r-chrome">
            <div class="r-dots"><span></span><span></span><span></span></div>
            <div class="r-cbar"></div>
          </div>
          <div class="r-img">
            <?php if ( has_post_thumbnail() ) : ?>
              <?php the_post_thumbnail( 'seohouse-case-thumb', [ 'style' => 'position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.4' ] ); ?>
            <?php endif; ?>
            <div class="r-chart">
              <?php foreach ( $bars as $bar ) : ?>
                <div class="r-b <?php echo $bar > 60 ? 'hi' : ''; ?>" style="height:<?php echo esc_attr( $bar ); ?>%"></div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <div class="r-body">
          <?php if ( $sector ) : ?>
            <div class="r-sector"><?php echo esc_html( $sector ); ?></div>
          <?php endif; ?>
          <h3><?php the_title(); ?></h3>
          <p><?php echo esc_html( get_the_excerpt() ); ?></p>
          <?php if ( ! empty( $metrics ) ) : ?>
          <div class="r-meta">
            <?php foreach ( array_slice( $metrics, 0, 2 ) as $metric ) : ?>
              <div>
                <div class="r-ml"><?php echo esc_html( $metric['label'] ?? '' ); ?></div>
                <div class="r-mv"><?php echo esc_html( $metric['value'] ?? '' ); ?></div>
              </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
    <?php else : ?>
    <div class="empty-state" style="text-align:center;padding:80px 0">
      <p style="color:var(--muted);font-size:15px">لا توجد دراسات حالة بعد.</p>
    </div>
    <?php endif; ?>

  </div>
</section>

<?php get_footer(); ?>
