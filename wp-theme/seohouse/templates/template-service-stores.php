<?php
/**
 * Template Name: Service — Stores Overview
 */
get_header();
?>

<section class="svc-hero">
  <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.04) 1px,transparent 1px);background-size:36px 36px;pointer-events:none"></div>
  <div style="position:absolute;border-radius:50%;filter:blur(60px);pointer-events:none;inset-inline-start:-200px;bottom:-100px;width:600px;height:600px;background:radial-gradient(circle,rgba(30,46,245,.22),transparent 65%)"></div>
  <div class="wrap">
    <div class="svc-hero-inner">
      <div class="breadcrumb" style="justify-content:center;margin-bottom:22px">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">الرئيسية</a>
        <svg class="bc-sep" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        <span style="color:rgba(255,255,255,.55)">إنشاء وتصميم متاجر</span>
      </div>
      <h1 style="font-size:clamp(32px,5vw,60px);font-weight:900;line-height:1.08;letter-spacing:-.03em;color:#fff;margin-bottom:16px">إنشاء وتصميم <em style="font-style:normal;background:linear-gradient(110deg,#7b90ff,#aab8ff 50%,#7b90ff);background-size:200% auto;-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;animation:sh 5s linear infinite">متاجر إلكترونية</em></h1>
      <p style="font-size:clamp(15px,1.55vw,17.5px);line-height:1.9;color:rgba(255,255,255,.6);max-width:680px;margin-inline:auto;margin-bottom:30px">نبني متاجر تبيع — بمنصات سلة وزد وشوبيفاي وووكومرس. كل متجر مُهيَّأ للسيو من اليوم الأول.</p>
      <div style="display:flex;gap:11px;justify-content:center;flex-wrap:wrap">
        <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-p lg">احجز استشارة مجانية</a>
      </div>
    </div>
  </div>
</section>

<!-- Platforms grid -->
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">المنصات</span><h2 class="h2">اختر منصّة متجرك</h2></div>
    <div class="features-grid">
      <?php
      $platforms = [
          [ 'title' => 'متاجر شوبيفاي',  'desc' => 'تجارة إلكترونية مرنة بمعايير دولية',         'url' => sh_page_url( 'services/shopify' ) ],
          [ 'title' => 'متاجر سلة',       'desc' => 'منصّة سعودية جاهزة للسوق المحلي',            'url' => sh_page_url( 'services/salla' ) ],
          [ 'title' => 'متاجر زد',        'desc' => 'بنية محلية قوية لانطلاقة احترافية',           'url' => sh_page_url( 'services/zid' ) ],
          [ 'title' => 'متاجر ووكومرس',   'desc' => 'تحكّم كامل على ووردبريس',                    'url' => sh_page_url( 'services/woocommerce' ) ],
      ];
      $dcs = [ '', 'd1', 'd2', 'd1' ];
      foreach ( $platforms as $idx => $plat ) :
      ?>
      <div class="feat-card sr <?php echo esc_attr( $dcs[ $idx ] ); ?>">
        <div class="ico-box" style="margin-bottom:14px">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="20" height="20"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
        </div>
        <h3><?php echo esc_html( $plat['title'] ); ?></h3>
        <p><?php echo esc_html( $plat['desc'] ); ?></p>
        <a href="<?php echo esc_url( $plat['url'] ); ?>" style="display:inline-flex;align-items:center;gap:6px;margin-top:14px;font-size:13px;font-weight:700;color:var(--blue)">تفاصيل <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- WP editor content -->
<?php
while ( have_posts() ) : the_post();
    $ec = get_the_content();
    if ( trim( wp_strip_all_tags( $ec ) ) ) :
?>
<section class="sec sec-white">
  <div class="wrap"><div class="post-content"><?php the_content(); ?></div></div>
</section>
<?php endif; endwhile; ?>

<?php
get_template_part( 'template-parts/layout/cta-banner', null, [
    'tag'   => 'ابدأ الآن',
    'title' => 'جاهز لبناء متجرك؟',
] );
?>

<?php get_footer(); ?>
