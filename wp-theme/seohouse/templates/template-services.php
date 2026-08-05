<?php
/**
 * Template Name: Services Hub
 * The main /services/ page — overview of all service categories
 */
get_header();

$hub_hero_badge   = sh_field( 'hub_hero_badge',  null, 'خدماتنا' );
$hub_hero_em      = sh_field( 'hub_hero_em',     null, 'وجودك الرقمي' );
$hub_hero_raw     = sh_field( 'hub_hero_title',  null, "خدمات تبني\nوجودك الرقمي" );
$hub_hero_desc    = sh_field( 'hub_hero_desc',   null, 'من تحسين محركات البحث إلى إنشاء المتاجر — نغطّي كل ما يحتاجه عملك لينمو رقمياً.' );
$hub_hero_title = $hub_hero_em
    ? str_replace( $hub_hero_em, '<em class="grad-em">' . esc_html( $hub_hero_em ) . '</em>', esc_html( $hub_hero_raw ) )
    : esc_html( $hub_hero_raw );
$hub_hero_title = nl2br( $hub_hero_title );
$hub_services_title = sh_field( 'hub_services_title', null, 'اختر الخدمة التي تحتاجها' );
$hub_services_desc  = sh_field( 'hub_services_desc',  null, 'كل خدمة مصمّمة لتحقيق هدف محدد — نوصّلك للنتيجة بأقصر طريق.' );
$hub_seo_sub_title  = sh_field( 'hub_seo_sub_title',  null, 'ابنِ استراتيجيتك من هنا' );
$hub_seo_sub_desc   = sh_field( 'hub_seo_sub_desc',   null, 'كل خدمة تُكمّل الأخرى — اختر ما يناسب وضعك الآن أو ابنِ باقة متكاملة.' );
$hub_why_title      = sh_field( 'hub_why_title',       null, 'ليس مجرد وكالة رقمية' );
$hub_why_desc       = sh_field( 'hub_why_desc',        null, 'نتخصص في السوق السعودي — نفهم اللغة، نعرف المنافسين، ونعمل بشفافية كاملة.' );
$hub_cta_tag        = sh_field( 'hub_cta_tag',         null, 'ابدأ الآن' );
$hub_cta_title      = sh_field( 'hub_cta_title',       null, 'لا تعرف من أين تبدأ؟' );
$hub_cta_desc       = sh_field( 'hub_cta_desc',        null, 'احجز استشارة مجانية ونحدد معاً أفضل خدمة لنمو مشروعك.' );
?>

<!-- Hero ─────────────────────────────────────────────── -->
<?php
$_hub_badge_html = '<div class="h-badge"><span class="h-bdot"></span>' . esc_html( $hub_hero_badge ) . '</div>';
get_template_part( 'template-parts/layout/page-hero', null, [
    'badge_html'  => $_hub_badge_html,
    'title'       => $hub_hero_title,
    'description' => $hub_hero_desc,
    'buttons'     => [
        [ 'text' => 'احجز استشارة مجانية', 'url' => sh_page_url( 'contact' ), 'class' => 'btn-p lg' ],
        [ 'text' => 'شاهد نتائجنا',        'url' => sh_page_url( 'results' ), 'class' => 'btn-g lg' ],
    ],
] );
?>

<!-- Service categories ──────────────────────────────── -->
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag">ما نقدّمه</span>
      <h2 class="h2"><?php echo esc_html( $hub_services_title ); ?></h2>
      <p class="bod"><?php echo esc_html( $hub_services_desc ); ?></p>
    </div>

    <!-- SEO (featured) + Web & Stores (stacked) -->
    <div class="svc-layout" style="margin-bottom:56px">
      <a href="<?php echo esc_url( sh_page_url( 'services/seo' ) ); ?>" class="svc-card feat sr">
        <div class="feat-body">
          <span class="feat-tag"><span class="feat-tag-dot"></span>الخدمة الأساسية</span>
          <h3>تحسين محركات البحث</h3>
          <p class="feat-p">نضع موقعك في الصفحة الأولى من جوجل بمنهجية شاملة — تدقيق تقني، بحث كلمات، تحسين صفحات، وبناء روابط موثوقة. خدمتنا الأساسية والأكثر طلباً.</p>
          <div class="svc-tags">
            <span class="chip">سيو المتاجر</span>
            <span class="chip">باك لينك</span>
            <span class="chip">كتابة محتوى</span>
            <span class="chip">استشارات الأداء</span>
          </div>
        </div>
        <div class="feat-cta-block">
          <div class="feat-cta-inner">
            <div class="feat-cta-lbl">أول نتائج خلال</div>
            <div class="feat-cta-num"><em>90</em> يوماً</div>
            <p class="feat-cta-desc">نتائج مقاسة، تقارير شفافة، بدون عقود ملزمة</p>
          </div>
          <span class="feat-cta-link">تعرّف على الخدمة <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
        </div>
      </a>

      <div class="svc-stack">
        <a href="<?php echo esc_url( sh_page_url( 'services/web-design' ) ); ?>" class="svc-card sr d1">
          <span class="svc-arrow"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg></span>
          <div class="svc-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8m-4-4v4"/></svg></div>
          <h3>إنشاء وتصميم مواقع</h3>
          <p>مواقع احترافية سريعة، مبنية للأداء والتحويل من اليوم الأول.</p>
        </a>
        <a href="<?php echo esc_url( sh_page_url( 'services/stores' ) ); ?>" class="svc-card sr d2">
          <span class="svc-arrow"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg></span>
          <div class="svc-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg></div>
          <h3>إنشاء وتصميم متاجر</h3>
          <p>سلة، زد، شوبيفاي — تصميم، إعداد، وربط بوابات الدفع المحلية.</p>
        </a>
        <a href="<?php echo esc_url( sh_page_url( 'services/products' ) ); ?>" class="svc-card sr d3">
          <span class="svc-arrow"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg></span>
          <div class="svc-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg></div>
          <h3>رفع المنتجات للمتاجر</h3>
          <p>أوصاف SEO، صور محسّنة، وتصنيفات دقيقة — متجرك جاهز للبيع.</p>
        </a>
      </div>
    </div>

    <!-- SEO sub-services hub -->
    <div class="sh c sr">
      <span class="tag">خدمات سيو التخصصية</span>
      <h2 class="h2"><?php echo esc_html( $hub_seo_sub_title ); ?></h2>
      <p class="bod"><?php echo esc_html( $hub_seo_sub_desc ); ?></p>
    </div>
    <div class="svc-hub-grid">
      <a href="<?php echo esc_url( sh_page_url( 'services/seo/backlinks' ) ); ?>" class="svc-hub-card sr">
        <div class="svc-hub-ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
        </div>
        <h3>بناء الروابط الخارجية</h3>
        <p>روابط من مواقع موثوقة ترفع سلطة موقعك وترتيبه في جوجل.</p>
        <ul class="svc-sub-list">
          <li class="svc-sub-item">روابط من مواقع عربية</li>
          <li class="svc-sub-item">Guest posting</li>
          <li class="svc-sub-item">Digital PR</li>
        </ul>
      </a>
      <a href="<?php echo esc_url( sh_page_url( 'services/seo/content' ) ); ?>" class="svc-hub-card sr d1">
        <div class="svc-hub-ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        </div>
        <h3>كتابة المحتوى التسويقي</h3>
        <p>محتوى يستهدف كلماتك البحثية ويُقنع زوّارك بالتحويل.</p>
        <ul class="svc-sub-list">
          <li class="svc-sub-item">مقالات SEO</li>
          <li class="svc-sub-item">صفحات الخدمات</li>
          <li class="svc-sub-item">أوصاف المنتجات</li>
        </ul>
      </a>
      <a href="<?php echo esc_url( sh_page_url( 'services/seo/consulting' ) ); ?>" class="svc-hub-card sr d2">
        <div class="svc-hub-ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
        </div>
        <h3>استشارات السيو</h3>
        <p>استشارة فردية أو جلسات دورية لفريقك الداخلي.</p>
        <ul class="svc-sub-list">
          <li class="svc-sub-item">تدقيق شامل</li>
          <li class="svc-sub-item">تدريب الفريق</li>
          <li class="svc-sub-item">خارطة طريق</li>
        </ul>
      </a>
      <a href="<?php echo esc_url( sh_page_url( 'sectors/ecommerce' ) ); ?>" class="svc-hub-card sr d3">
        <div class="svc-hub-ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
        </div>
        <h3>سيو المتاجر الإلكترونية</h3>
        <p>تحسين منهجي لمتجرك — صفحات فئات، منتجات، وهيكل URL.</p>
        <ul class="svc-sub-list">
          <li class="svc-sub-item">سيو سلة وزد</li>
          <li class="svc-sub-item">تحسين صفحات المنتج</li>
          <li class="svc-sub-item">Schema للمتاجر</li>
        </ul>
      </a>
    </div>
  </div>
</section>

<!-- Why SEO House ───────────────────────────────────── -->
<section class="sec sec-navy">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag d">لماذا سيو هاوس</span>
      <h2 class="h2 wh"><?php echo esc_html( $hub_why_title ); ?></h2>
      <p class="bod d"><?php echo esc_html( $hub_why_desc ); ?></p>
    </div>
    <div class="method-grid">
      <div class="method-item sr">
        <div class="m-num">01</div>
        <h3>تخصص في السوق المحلي</h3>
        <p>خبرة عميقة بالسوق السعودي وتحديات كل قطاع — لا نطبق قوالب عالمية.</p>
      </div>
      <div class="method-item sr d1">
        <div class="m-num">02</div>
        <h3>شفافية كاملة في التقارير</h3>
        <p>تقارير شهرية مفصّلة توضح ما تحقق، ما هو قيد التنفيذ، والخطوات القادمة.</p>
      </div>
      <div class="method-item sr d2">
        <div class="m-num">03</div>
        <h3>نتائج مقاسة لا وعود</h3>
        <p>نحدد مؤشرات النجاح منذ البداية ونعمل لتحقيقها — مع إمكانية الإلغاء في أي وقت.</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA ─────────────────────────────────────────────── -->
<?php
get_template_part( 'template-parts/layout/cta-banner', null, [
    'tag'   => $hub_cta_tag,
    'title' => $hub_cta_title,
    'desc'  => $hub_cta_desc,
] );

get_footer();
