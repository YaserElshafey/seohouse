<?php
/**
 * Single case study detail page
 */
get_header();

while ( have_posts() ) : the_post();
    $terms      = get_the_terms( get_the_ID(), 'case_study_sector' );
    $sector     = ( ! is_wp_error( $terms ) && ! empty( $terms ) ) ? $terms[0]->name : '';
    $client          = sh_field( 'client_name' );
    $headline_result = sh_field( 'headline_result' );
    $card_meta       = sh_field( 'card_meta' );
    $challenge       = sh_field( 'challenge' );
    $solution        = sh_field( 'solution' );
    $result          = sh_field( 'result' );
    $metrics         = sh_field( 'metrics' );
    $cta_url         = sh_field( 'cta_url' );
    $client_url      = sh_field( 'client_url' );
    $gallery         = sh_field( 'gallery' );
    $gallery_title   = sh_field( 'gallery_section_title' ) ?: 'نظرة على النتائج';
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

        <!-- Headline result panel -->
        <?php if ( $headline_result ) : ?>
        <div class="cs-detail-section" style="background:var(--navy-2);text-align:center;padding:32px 28px">
          <div class="proof-result <?php echo esc_attr( sh_value_class( $headline_result ) ); ?>" style="margin-bottom:10px"><em><?php echo esc_html( $headline_result ); ?></em></div>
          <?php if ( $card_meta ) : ?>
          <div class="proof-meta" style="justify-content:center;border-top:none;padding-top:0"><div class="proof-dot"></div><?php echo esc_html( $card_meta ); ?></div>
          <?php endif; ?>
        </div>
        <?php endif; ?>

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
              <div class="cs-metric-value <?php echo esc_attr( sh_value_class( $metric['value'] ?? '' ) ); ?>"><?php echo esc_html( $metric['value'] ?? '' ); ?></div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Gallery -->
        <?php if ( ! empty( $gallery ) ) : ?>
        <div class="cs-detail-section">
          <h2><?php echo esc_html( $gallery_title ); ?></h2>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
            <?php $gidx = 0; foreach ( $gallery as $img ) : ?>
              <?php if ( ! empty( $img['url'] ) ) : ?>
                <img src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php echo esc_attr( $img['alt'] ?? '' ); ?>" style="border-radius:var(--r2);width:100%;height:auto;cursor:zoom-in" class="cs-gallery-img" data-idx="<?php echo esc_attr( $gidx ); ?>">
                <?php $gidx++; ?>
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

        <?php if ( $client || $sector || $card_meta ) : ?>
        <div class="cs-sidebar-box">
          <h4>تفاصيل المشروع</h4>
          <?php if ( $client ) : ?>
          <div class="cs-sidebar-stat">
            <span>العميل</span>
            <?php if ( $client_url ) : ?>
            <a href="<?php echo esc_url( $client_url ); ?>" target="_blank" rel="noopener noreferrer" style="color:var(--blue);font-weight:700;font-size:13px"><?php echo esc_html( $client ); ?></a>
            <?php else : ?>
            <strong><?php echo esc_html( $client ); ?></strong>
            <?php endif; ?>
          </div>
          <?php endif; ?>
          <?php if ( $sector ) : ?>
          <div class="cs-sidebar-stat">
            <span>القطاع</span>
            <strong><?php echo esc_html( $sector ); ?></strong>
          </div>
          <?php endif; ?>
          <?php if ( $card_meta ) : ?>
          <div class="cs-sidebar-stat">
            <span>المدة</span>
            <strong><?php echo esc_html( $card_meta ); ?></strong>
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

<?php if ( ! empty( $gallery ) ) : ?>
<div id="cs-lightbox" style="display:none;position:fixed;inset:0;z-index:9000;background:rgba(7,13,50,.96);align-items:center;justify-content:center;gap:16px" role="dialog" aria-modal="true">
  <button id="cs-lb-prev" style="background:rgba(255,255,255,.1);border:none;border-radius:50%;width:44px;height:44px;display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;color:#fff;transition:background .18s" aria-label="السابقة">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
  </button>
  <div style="position:relative;max-width:80vw;max-height:85vh">
    <img id="cs-lb-img" src="" alt="" style="max-width:100%;max-height:85vh;border-radius:var(--r2);display:block">
    <button id="cs-lb-close" style="position:absolute;top:-14px;inset-inline-end:-14px;background:rgba(255,255,255,.15);border:none;border-radius:50%;width:32px;height:32px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#fff;transition:background .18s" aria-label="إغلاق">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
    </button>
  </div>
  <button id="cs-lb-next" style="background:rgba(255,255,255,.1);border:none;border-radius:50%;width:44px;height:44px;display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;color:#fff;transition:background .18s" aria-label="التالية">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
  </button>
</div>
<script>
(function(){
  var imgs = <?php echo wp_json_encode( array_values( array_filter( array_map( function( $img ) { return $img['url'] ?? ''; }, $gallery ) ) ) ); ?>;
  if ( ! imgs.length ) return;
  var lb = document.getElementById('cs-lightbox');
  var lbImg = document.getElementById('cs-lb-img');
  var cur = 0;
  var prevBtn = document.getElementById('cs-lb-prev');
  var nextBtn = document.getElementById('cs-lb-next');
  function show(idx){
    cur = (idx + imgs.length) % imgs.length;
    lbImg.src = imgs[cur];
    lb.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    if ( imgs.length <= 1 ){ prevBtn.style.visibility='hidden'; nextBtn.style.visibility='hidden'; }
  }
  function hide(){ lb.style.display = 'none'; document.body.style.overflow = ''; }
  document.querySelectorAll('.cs-gallery-img').forEach(function(el){
    el.addEventListener('click', function(){ show( parseInt(el.dataset.idx, 10) || 0 ); });
  });
  prevBtn.addEventListener('click', function(){ show(cur - 1); });
  nextBtn.addEventListener('click', function(){ show(cur + 1); });
  document.getElementById('cs-lb-close').addEventListener('click', hide);
  lb.addEventListener('click', function(e){ if (e.target === lb) hide(); });
  document.addEventListener('keydown', function(e){
    if (lb.style.display === 'none') return;
    if (e.key === 'Escape') hide();
    else if (e.key === 'ArrowRight') show(cur - 1);
    else if (e.key === 'ArrowLeft') show(cur + 1);
  });
})();
</script>
<?php endif; ?>

<?php get_footer(); ?>
