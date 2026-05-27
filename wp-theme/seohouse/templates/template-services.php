<?php
/**
 * Template Name: Services Hub
 * The main /services/ page — overview of all service categories
 */
get_header();
?>

<!-- Hero ─────────────────────────────────────────────── -->
<section class="svc-hero" style="text-align:center">
  <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.04) 1px,transparent 1px);background-size:36px 36px;mask-image:radial-gradient(ellipse 90% 80% at 50% 50%,#000 10%,transparent 75%);pointer-events:none"></div>
  <div style="position:absolute;border-radius:50%;filter:blur(60px);pointer-events:none;inset-inline-start:-200px;bottom:-100px;width:600px;height:600px;background:radial-gradient(circle,rgba(30,46,245,.22),transparent 65%)"></div>
  <div style="position:absolute;border-radius:50%;filter:blur(60px);pointer-events:none;inset-inline-end:-100px;top:0;width:480px;height:480px;background:radial-gradient(circle,rgba(30,46,245,.14),transparent 65%)"></div>
  <div class="wrap">
    <div style="position:relative;z-index:2;max-width:820px;margin-inline:auto">
      <div class="h-badge">
        <span class="h-bdot"></span>
        خدماتنا
      </div>
      <h1 style="font-size:clamp(32px,5vw,62px);font-weight:900;line-height:1.08;letter-spacing:-.03em;color:#fff;margin-bottom:18px">
        خدمات تبني<br>
        <em style="font-style:normal;background:linear-gradient(110deg,#7b90ff,#aab8ff 50%,#7b90ff);background-size:200% auto;-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;animation:sh 5s linear infinite">وجودك الرقمي</em>
      </h1>
      <p style="font-size:clamp(15px,1.55vw,17.5px);line-height:1.9;color:rgba(255,255,255,.6);max-width:680px;margin-inline:auto;margin-bottom:30px">من تحسين محركات البحث إلى إنشاء المتاجر — نغطّي كل ما يحتاجه عملك لينمو رقمياً.</p>
      <div style="display:flex;gap:11px;justify-content:center;flex-wrap:wrap">
        <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-p lg">احجز استشارة مجانية</a>
        <a href="<?php echo esc_url( sh_page_url( 'results' ) ); ?>" class="btn btn-g lg">شاهد نتائجنا</a>
      </div>
    </div>
  </div>
</section>

<!-- Service categories ──────────────────────────────── -->
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag">ما نقدّمه</span>
      <h2 class="h2">اختر الخدمة التي تحتاجها</h2>
      <p class="bod">كل خدمة مصمّمة لتحقيق هدف محدد — نوصّلك للنتيجة بأقصر طريق.</p>
    </div>

    <!-- SEO (featured) + Web & Stores (stacked) -->
    <div class="svc-layout" style="margin-bottom:56px">
      <a href="<?php echo esc_url( sh_page_url( 'services/seo' ) ); ?>" class="svc-card feat sr" style="text-decoration:none">
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
        <a href="<?php echo esc_url( sh_page_url( 'services/web-design' ) ); ?>" class="svc-card sr d1" style="text-decoration:none">
          <span class="svc-arrow"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg></span>
          <div class="svc-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8m-4-4v4"/></svg></div>
          <h3>إنشاء وتصميم مواقع</h3>
          <p>مواقع احترافية سريعة، مبنية للأداء والتحويل من اليوم الأول.</p>
        </a>
        <a href="<?php echo esc_url( sh_page_url( 'services/stores' ) ); ?>" class="svc-card sr d2" style="text-decoration:none">
          <span class="svc-arrow"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg></span>
          <div class="svc-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg></div>
          <h3>إنشاء وتصميم متاجر</h3>
          <p>سلة، زد، شوبيفاي — تصميم، إعداد، وربط بوابات الدفع المحلية.</p>
        </a>
        <a href="<?php echo esc_url( sh_page_url( 'services/products' ) ); ?>" class="svc-card sr d3" style="text-decoration:none">
          <span class="svc-arrow"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg></span>
          <div class="svc-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg></div>
          <h3>رفع المنتجات للمتاجر</h3>
          <p>أوصاف SEO، صور محسّنة، وتصنيفات دقيقة — متجرك جاهز للبيع.</p>
        </a>
      </div>
    </div>

    <!-- SEO sub-services hub -->
    <div class="sh c sr" style="margin-top:16px">
      <span class="tag">خدمات سيو التخصصية</span>
      <h2 class="h2">ابنِ استراتيجيتك من هنا</h2>
      <p class="bod">كل خدمة تُكمّل الأخرى — اختر ما يناسب وضعك الآن أو ابنِ باقة متكاملة.</p>
    </div>
    <div class="svc-hub-grid">
      <a href="<?php echo esc_url( sh_page_url( 'services/seo/backlinks' ) ); ?>" class="svc-hub-card sr" style="text-decoration:none">
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
      <a href="<?php echo esc_url( sh_page_url( 'services/seo/content' ) ); ?>" class="svc-hub-card sr d1" style="text-decoration:none">
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
      <a href="<?php echo esc_url( sh_page_url( 'services/seo/consulting' ) ); ?>" class="svc-hub-card sr d2" style="text-decoration:none">
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
      <a href="<?php echo esc_url( sh_page_url( 'sectors/ecommerce' ) ); ?>" class="svc-hub-card sr d3" style="text-decoration:none">
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
      <h2 class="h2 wh">ليس مجرد وكالة رقمية</h2>
      <p class="bod d">نتخصص في السوق السعودي — نفهم اللغة، نعرف المنافسين، ونعمل بشفافية كاملة.</p>
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
    'tag'   => 'ابدأ الآن',
    'title' => 'لا تعرف من أين تبدأ؟',
    'desc'  => 'احجز استشارة مجانية ونحدد معاً أفضل خدمة لنمو مشروعك.',
] );

get_footer();
