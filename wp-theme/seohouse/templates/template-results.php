<?php
/**
 * Template Name: Results Page
 */
get_header();

$sectors_list = get_terms( [
    'taxonomy'   => 'case_study_sector',
    'hide_empty' => true,
] );
?>

<?php
$res_em    = sh_field( 'results_hero_em',    null, 'مشاريع حقيقية' );
$res_raw   = sh_field( 'results_hero_title', null, "نتائج من\nمشاريع حقيقية" );
$res_desc  = sh_field( 'results_hero_desc',  null, 'لا نتحدث عن نتائج افتراضية — هذه مشاريع نفّذناها لعملاء حقيقيين في قطاعات متنوعة. الأرقام الدقيقة تُضاف بعد موافقة العملاء.' );
$res_title = $res_em
    ? str_replace( $res_em, '<em>' . esc_html( $res_em ) . '</em>', esc_html( $res_raw ) )
    : esc_html( $res_raw );
$res_title = nl2br( $res_title );

get_template_part( 'template-parts/layout/page-hero', null, [
    'tag'         => sh_field( 'results_hero_tag', null, 'نتائج فعلية' ),
    'title'       => $res_title,
    'description' => $res_desc,
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
    <div class="proof-grid" style="gap:18px">
      <?php
      $delays = [ '', 'd1', 'd2', 'd1', 'd2', 'd3' ];
      $ci     = 0;
      while ( $cases->have_posts() ) : $cases->the_post();
          $terms       = get_the_terms( get_the_ID(), 'case_study_sector' );
          $sector      = ( ! is_wp_error( $terms ) && ! empty( $terms ) ) ? $terms[0]->name : '';
          $sector_slug = ( ! is_wp_error( $terms ) && ! empty( $terms ) ) ? $terms[0]->slug : '';
          $headline    = sh_field( 'headline_result' ) ?: ( sh_field( 'metrics' )[0]['value'] ?? '—' );
          $meta_text   = sh_field( 'card_meta' );
          $dc          = $delays[ $ci % count( $delays ) ];
          $ci++;
      ?>
      <div class="proof-card r-card sr <?php echo esc_attr( $dc ); ?>" data-sector="<?php echo esc_attr( $sector_slug ); ?>">
        <?php if ( $sector ) : ?>
        <div class="proof-sector"><?php echo esc_html( $sector ); ?></div>
        <?php endif; ?>
        <div class="proof-result"><em><?php echo esc_html( $headline ); ?></em></div>
        <div class="proof-desc"><?php echo esc_html( get_the_excerpt() ); ?></div>
        <?php if ( $meta_text ) : ?>
        <div class="proof-meta"><div class="proof-dot"></div><?php echo esc_html( $meta_text ); ?></div>
        <?php endif; ?>
        <a href="<?php the_permalink(); ?>" class="proof-cta">اقرأ القصة كاملاً <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
      </div>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>

    <?php else : ?>
    <!-- Fallback placeholder cards -->
    <div class="results-grid">
      <?php
      $placeholders = [
          [ 'sector' => 'التجارة الإلكترونية', 'slug' => 'ecommerce', 'title' => 'متجر سلة للأزياء — نمو الزيارات العضوية', 'desc' => 'موقع جديد في سوق تنافسي — بناء حضور عضوي من الصفر.', 'bars' => [20,28,24,42,50,62,72,82,90,98], 'label1' => 'نمو الزيارات', 'label2' => 'المدة', 'val2' => '6 أشهر', 'dc' => '' ],
          [ 'sector' => 'الصحة والطب',         'slug' => 'health',    'title' => 'عيادة تخصصية — ظهور على كلمات طبية',         'desc' => 'منافسة عالية في قطاع الرعاية الصحية.', 'bars' => [35,40,36,48,60,70,76,84,94,100], 'label1' => 'ترتيب الكلمات', 'label2' => 'المدة', 'val2' => '4 أشهر', 'dc' => 'd1' ],
          [ 'sector' => 'العقارات',             'slug' => 'realestate','title' => 'شركة عقارية — طلبات من البحث العضوي',         'desc' => 'تقليل الاعتماد على الإعلانات المدفوعة.', 'bars' => [16,22,30,40,50,60,72,82,92,100], 'label1' => 'طلبات عضوية', 'label2' => 'المدة', 'val2' => '8 أشهر', 'dc' => 'd2' ],
          [ 'sector' => 'التعليم والتدريب',     'slug' => 'education', 'title' => 'مركز تدريبي — تسجيلات عضوية',                'desc' => 'استقطاب متدربين جدد من البحث العضوي.', 'bars' => [25,30,28,45,55,65,74,82,88,96], 'label1' => 'نمو التسجيلات', 'label2' => 'المدة', 'val2' => '5 أشهر', 'dc' => 'd1' ],
          [ 'sector' => 'المطاعم والأغذية',     'slug' => 'food',      'title' => 'سلسلة مطاعم — ظهور محلي في جوجل',            'desc' => 'تحسين الظهور المحلي لكل فرع.', 'bars' => [18,26,22,38,48,58,70,80,86,94], 'label1' => 'نمو الزيارات', 'label2' => 'المدة', 'val2' => '3 أشهر', 'dc' => 'd2' ],
          [ 'sector' => 'التقنية والبرمجيات',   'slug' => 'tech',      'title' => 'شركة SaaS — زيارات عضوية تجارية',            'desc' => 'استهداف كلمات تجارية تقنية.', 'bars' => [30,35,32,50,58,68,76,84,92,100], 'label1' => 'نمو الزيارات', 'label2' => 'المدة', 'val2' => '7 أشهر', 'dc' => 'd3' ],
      ];
      foreach ( $placeholders as $ph ) : ?>
      <div class="r-card sr <?php echo esc_attr( $ph['dc'] ); ?>" data-sector="<?php echo esc_attr( $ph['slug'] ); ?>">
        <div class="r-screen">
          <div class="r-chrome"><div class="r-dots"><span></span><span></span><span></span></div><div class="r-cbar"></div></div>
          <div class="r-img">
            <div class="r-chart">
              <?php foreach ( $ph['bars'] as $idx => $h ) : ?>
                <div class="r-b <?php echo $idx >= 5 ? 'hi' : ''; ?>" style="height:<?php echo esc_attr( $h ); ?>%"></div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <div class="r-body">
          <div class="r-sector"><?php echo esc_html( $ph['sector'] ); ?></div>
          <h3><?php echo esc_html( $ph['title'] ); ?></h3>
          <p><?php echo esc_html( $ph['desc'] ); ?></p>
          <div class="r-meta">
            <div><div class="r-ml"><?php echo esc_html( $ph['label1'] ); ?></div><div class="r-mv">سيُضاف لاحقاً</div></div>
            <div><div class="r-ml"><?php echo esc_html( $ph['label2'] ); ?></div><div class="r-mv"><?php echo esc_html( $ph['val2'] ); ?></div></div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <p style="text-align:center;font-size:11.5px;color:var(--muted);margin-top:22px;font-style:italic">الأرقام الفعلية تُضاف بعد الحصول على موافقة العملاء</p>
    <?php endif; ?>

  </div>
</section>

<?php
$results_cta_tag   = sh_field( 'results_cta_tag',   null, 'هل موقعك التالي؟' );
$results_cta_title = sh_field( 'results_cta_title', null, 'لنبني نتائجك معاً' );
$results_cta_desc  = sh_field( 'results_cta_desc',  null, 'احجز استشارة مجانية ونضع الأساس لنتيجة تستحق أن تُعرض هنا.' );
$results_btn2_txt  = sh_field( 'results_btn2_txt',  null, 'تعرّف على السيو' );

get_template_part( 'template-parts/layout/cta-banner', null, [
    'tag'         => $results_cta_tag,
    'title'       => $results_cta_title,
    'description' => $results_cta_desc,
    'buttons'     => [
        [ 'text' => 'احجز استشارة مجانية', 'url' => sh_page_url( 'contact' ),       'class' => 'btn-w lg' ],
        [ 'text' => $results_btn2_txt,      'url' => sh_page_url( 'services/seo' ), 'class' => 'btn-g lg' ],
    ],
] );
?>

<?php get_footer(); ?>
