<?php
/**
 * Case study archive — renders the full results page UI.
 * No redirect: both this archive and template-results.php render the same content.
 * Redirecting to get_permalink('results') would loop back to this same URL.
 */

get_header();

$sectors_list = get_terms( [
    'taxonomy'   => 'case_study_sector',
    'hide_empty' => true,
] );
?>

<?php
$ra_em    = sh_option( 'ra_hero_em',    'مشاريع حقيقية' );
$ra_raw   = sh_option( 'ra_hero_title', "نتائج من\nمشاريع حقيقية" );
$ra_desc  = sh_option( 'ra_hero_desc',  'لا نتحدث عن نتائج افتراضية — هذه مشاريع نفّذناها لعملاء حقيقيين في قطاعات متنوعة. الأرقام الدقيقة تُضاف بعد موافقة العملاء.' );
$ra_title = $ra_em
    ? str_replace( $ra_em, '<em>' . esc_html( $ra_em ) . '</em>', esc_html( $ra_raw ) )
    : esc_html( $ra_raw );
$ra_title = nl2br( $ra_title );

get_template_part( 'template-parts/layout/page-hero', null, [
    'tag'         => sh_option( 'ra_hero_tag', 'نتائج فعلية' ),
    'title'       => $ra_title,
    'description' => $ra_desc,
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

<?php
get_template_part( 'template-parts/layout/cta-banner', null, [
    'tag'         => sh_option( 'ra_cta_tag',   'هل موقعك التالي؟' ),
    'title'       => sh_option( 'ra_cta_title', 'لنبني نتائجك معاً' ),
    'description' => sh_option( 'ra_cta_desc',  'احجز استشارة مجانية ونضع الأساس لنتيجة تستحق أن تُعرض هنا.' ),
    'buttons'     => [
        [ 'text' => 'احجز استشارة مجانية',                    'url' => sh_page_url( 'contact' ),       'class' => 'btn-w lg' ],
        [ 'text' => sh_option( 'ra_btn2_txt', 'تعرّف على السيو' ), 'url' => sh_page_url( 'services/seo' ), 'class' => 'btn-g lg' ],
    ],
] );
?>

<?php get_footer(); ?>
