<?php
/**
 * Single case study detail page
 */
get_header();

while ( have_posts() ) : the_post();
    $terms      = get_the_terms( get_the_ID(), 'case_study_sector' );
    $sector     = ( ! is_wp_error( $terms ) && ! empty( $terms ) ) ? $terms[0]->name : '';
    $client     = sh_field( 'client_name' );
    $challenge  = sh_field( 'challenge' );
    $solution   = sh_field( 'solution' );
    $result     = sh_field( 'result' );
    $metrics    = sh_field( 'metrics' );
    $duration   = sh_field( 'project_duration' );
    $cta_url    = sh_field( 'cta_url' );
    $gallery    = sh_field( 'gallery' );
    $bars       = sh_case_chart_bars( get_the_ID() );
?>

<?php
get_template_part( 'template-parts/layout/page-hero', null, [
    'tag'        => $sector ?: 'دراسة حالة',
    'title'      => get_the_title(),
    'description'=> get_the_excerpt(),
    'breadcrumb' => [
        'نتائج الأعمال' => sh_page_url( 'results' ),
        get_the_title() => '',
    ],
] );
?>

<section class="sec sec-surface">
  <div class="wrap">
    <div class="cs-detail-layout">

      <div class="cs-detail-body">

        <!-- Visual chart card -->
        <div class="cs-detail-section" style="background:var(--navy);padding:0;overflow:hidden">
          <div style="height:200px;background:linear-gradient(140deg,rgba(7,13,50,.96),rgba(30,46,245,.12));display:flex;align-items:flex-end;padding:20px 24px 0;position:relative">
            <div style="position:absolute;top:0;inset-inline:0;height:28px;background:rgba(255,255,255,.04);display:flex;align-items:center;padding-inline:12px;gap:6px;border-bottom:1px solid rgba(255,255,255,.05)">
              <span style="width:7px;height:7px;border-radius:50%;background:rgba(255,80,80,.35)"></span>
              <span style="width:7px;height:7px;border-radius:50%;background:rgba(255,190,50,.35)"></span>
              <span style="width:7px;height:7px;border-radius:50%;background:rgba(60,200,80,.35)"></span>
            </div>
            <div style="display:flex;align-items:flex-end;gap:4px;height:130px;width:100%">
              <?php foreach ( $bars as $bar ) : ?>
                <div style="flex:1;border-radius:3px 3px 0 0;background:<?php echo $bar > 60 ? 'rgba(123,144,255,.6)' : 'rgba(30,46,245,.25)'; ?>;height:<?php echo esc_attr( $bar ); ?>%"></div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <!-- Challenge -->
        <?php if ( $challenge ) : ?>
        <div class="cs-detail-section">
          <h2>التحدي</h2>
          <p style="font-size:14.5px;color:var(--ink-2);line-height:1.9"><?php echo wp_kses_post( $challenge ); ?></p>
        </div>
        <?php endif; ?>

        <!-- Solution -->
        <?php if ( $solution ) : ?>
        <div class="cs-detail-section">
          <h2>الحل المُنفَّذ</h2>
          <p style="font-size:14.5px;color:var(--ink-2);line-height:1.9"><?php echo wp_kses_post( $solution ); ?></p>
        </div>
        <?php endif; ?>

        <!-- Results -->
        <?php if ( $result ) : ?>
        <div class="cs-detail-section">
          <h2>النتائج</h2>
          <p style="font-size:14.5px;color:var(--ink-2);line-height:1.9"><?php echo wp_kses_post( $result ); ?></p>
        </div>
        <?php endif; ?>

        <!-- Metrics -->
        <?php if ( ! empty( $metrics ) ) : ?>
        <div class="cs-detail-section">
          <h2>الأرقام</h2>
          <div class="cs-metrics-grid">
            <?php foreach ( $metrics as $metric ) : ?>
            <div class="cs-metric">
              <div class="cs-metric-label"><?php echo esc_html( $metric['label'] ?? '' ); ?></div>
              <div class="cs-metric-value"><?php echo esc_html( $metric['value'] ?? '' ); ?></div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Gallery -->
        <?php if ( ! empty( $gallery ) ) : ?>
        <div class="cs-detail-section">
          <h2>معرض المشروع</h2>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
            <?php foreach ( $gallery as $img ) : ?>
              <?php if ( ! empty( $img['url'] ) ) : ?>
                <img src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php echo esc_attr( $img['alt'] ?? '' ); ?>" style="border-radius:var(--r2);width:100%;height:auto">
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Body content (WP editor) -->
        <?php
        $content = get_the_content();
        if ( $content ) :
        ?>
        <div class="cs-detail-section">
          <div class="post-content"><?php the_content(); ?></div>
        </div>
        <?php endif; ?>

      </div><!-- /.cs-detail-body -->

      <!-- Sidebar -->
      <div class="cs-sidebar">

        <?php if ( $client || $sector || $duration ) : ?>
        <div class="cs-sidebar-box">
          <h4>تفاصيل المشروع</h4>
          <?php if ( $client ) : ?>
          <div class="cs-sidebar-stat">
            <span>العميل</span>
            <strong><?php echo esc_html( $client ); ?></strong>
          </div>
          <?php endif; ?>
          <?php if ( $sector ) : ?>
          <div class="cs-sidebar-stat">
            <span>القطاع</span>
            <strong><?php echo esc_html( $sector ); ?></strong>
          </div>
          <?php endif; ?>
          <?php if ( $duration ) : ?>
          <div class="cs-sidebar-stat">
            <span>المدة</span>
            <strong><?php echo esc_html( $duration ); ?></strong>
          </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="cs-sidebar-box" style="background:var(--blue);border-color:var(--blue)">
          <h4 style="color:rgba(255,255,255,.5)">هل تريد نتائج مشابهة؟</h4>
          <p style="font-size:13px;color:rgba(255,255,255,.6);line-height:1.72;margin-bottom:16px">ابدأ بمحادثة مجانية — وسنرى معاً ما يمكن تحقيقه لموقعك.</p>
          <a href="<?php echo esc_url( $cta_url ?: sh_page_url( 'contact' ) ); ?>" class="btn btn-w" style="width:100%;justify-content:center">استشارة مجانية</a>
        </div>

        <!-- Back to results -->
        <a href="<?php echo esc_url( sh_page_url( 'results' ) ); ?>" style="display:flex;align-items:center;gap:8px;font-size:13.5px;font-weight:700;color:var(--blue);padding:14px 16px;background:var(--blue-50);border-radius:var(--r2);text-decoration:none">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
          عودة لنتائج الأعمال
        </a>

      </div>
    </div>
  </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
