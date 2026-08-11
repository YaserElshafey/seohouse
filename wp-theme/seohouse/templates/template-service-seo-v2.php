<?php
/**
 * Template Name: SEO Service V2 Preview
 *
 * Staging template for the redesigned SEO service page.
 * Assign to a draft page at /seo-service-preview/ for live review.
 * Once approved, swap the template on the real /services/seo/ page.
 *
 * NOTE: This template outputs a noindex meta tag via wp_head so the
 * preview page is excluded from search engines and sitemaps.
 *
 * TODO before going live:
 *   Replace the placeholder strings below with real client data.
 */

// Inject noindex while in preview — remove this block before going live
add_filter( 'wp_robots', function ( $robots ) {
    $robots['noindex']  = true;
    $robots['nofollow'] = true;
    return $robots;
} );

get_header();

// ── Editable placeholders ────────────────────────────────────────────────────
// TODO: Replace these values with real client data before going live
$cs1_sector  = 'متجر إلكتروني في السعودية';
$cs1_period  = 'خلال فترة العمل';
$cs1_market  = 'السوق السعودي';

$cs2_period  = 'خلال فترة العمل';
$cs2_service = 'المنتجات والخدمات المستهدفة';
$cs2_market  = 'السوق السعودي';

$cs3_sector  = 'مكتب محاماة';
$cs3_city    = 'الرياض';

// ── Pricing page URL (link in FAQ only when page is live) ────────────────────
$pricing_url = sh_page_url( 'seo-pricing' ) ?: '';

// ── Image base URI ───────────────────────────────────────────────────────────
$img = get_template_directory_uri() . '/assets/images/seo-service';
?>
<div class="svc-seo">

<!-- ═══════════════════════════════════════════════════════
     1. HERO
═══════════════════════════════════════════════════════ -->
<section class="ss-hero">
  <span class="ss-hero-glow" aria-hidden="true"></span>
  <div class="wrap">
    <div class="ss-hero-grid">

      <div class="ss-hero-text">
        <span class="ss-hero-eyebrow">
          <span class="ss-hero-dot" aria-hidden="true"></span>
          خدمات تحسين محركات البحث
        </span>
        <h1 class="ss-hero-h1">شركة سيو لا تكتفي بإرسال التوصيات</h1>
        <p class="ss-hero-p">نحوّل بيانات البحث إلى خطة تنفيذ واضحة تشمل الجوانب التقنية والمحتوى والصفحات والقياس، ثم نتابع تنفيذها مع فريقك أو ننفذها من خلال فريق SEO House.</p>
        <p class="ss-hero-p">هدفنا ليس زيادة الزيارات بأي كلمات، بل تحسين ظهورك أمام الأشخاص الذين يبحثون فعلًا عن خدماتك أو منتجاتك، وربط هذا الظهور بالطلبات والعملاء المحتملين والمبيعات.</p>
        <div class="ss-hero-ctas">
          <a href="#seoSvcFormWrap" class="btn btn-p lg">اطلب مراجعة موقعك</a>
          <a href="#ss-journey" class="btn btn-g lg">شاهد كيف نعمل</a>
        </div>
        <ul class="ss-hero-trust" aria-label="مميزات الخدمة">
          <li class="ss-trust-item">
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true"><circle cx="8" cy="8" r="7.25" stroke="rgba(30,46,245,.7)" stroke-width="1.5"/><path d="M5 8.5l2 2 4-4" stroke="#7b90ff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            خطة مبنية على بيانات موقعك
          </li>
          <li class="ss-trust-item">
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true"><circle cx="8" cy="8" r="7.25" stroke="rgba(30,46,245,.7)" stroke-width="1.5"/><path d="M5 8.5l2 2 4-4" stroke="#7b90ff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            دعم تقني ومتابعة للتنفيذ
          </li>
          <li class="ss-trust-item">
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true"><circle cx="8" cy="8" r="7.25" stroke="rgba(30,46,245,.7)" stroke-width="1.5"/><path d="M5 8.5l2 2 4-4" stroke="#7b90ff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            لوحة أداء محدثة باستمرار
          </li>
        </ul>
      </div>

      <div class="ss-hero-vis" aria-hidden="true">
        <img
          src="<?php echo esc_url( $img . '/office-laptop-dashboard.png' ); ?>"
          alt="فريق SEO House أمام لوحة أداء Looker Studio على لابتوب"
          width="640" height="480"
          loading="eager" fetchpriority="high"
          class="ss-hero-photo"
        >
        <div class="ss-hero-stat-badge">
          <div class="ss-badge-label">إيرادات عضوية — ريال</div>
          <div class="ss-badge-value">106,274</div>
          <div class="ss-badge-sub">↑ نمو موثق بالبيانات</div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     2. CLIENTS
═══════════════════════════════════════════════════════ -->
<?php
$clients = sh_get_clients();
if ( $clients && $clients->have_posts() ) :
?>
<section class="sec sec-white ss-clients-section">
  <div class="wrap">
    <p class="ss-clients-label">جهات وثقت في SEO House</p>
  </div>
  <div class="cl-marquee-outer">
    <div class="cl-marquee-track">
      <?php
      $logo_items = [];
      while ( $clients->have_posts() ) :
        $clients->the_post();
        $logo = get_field( 'client_logo' );
        $url  = get_field( 'client_url' ) ?: '';
        if ( ! $logo ) continue;
        $img_tag = '<img src="' . esc_url( $logo['url'] ) . '" alt="' . esc_attr( get_the_title() ) . '" width="' . esc_attr( $logo['width'] ?? 120 ) . '" height="' . esc_attr( $logo['height'] ?? 40 ) . '" loading="lazy">';
        $logo_items[] = [ 'url' => $url, 'img' => $img_tag ];
      endwhile;
      wp_reset_postdata();
      // Render twice for seamless loop
      for ( $pass = 0; $pass < 2; $pass++ ) {
        foreach ( $logo_items as $item ) {
          if ( $item['url'] ) {
            echo '<a href="' . esc_url( $item['url'] ) . '" class="cl-item" target="_blank" rel="noopener noreferrer">' . $item['img'] . '</a>';
          } else {
            echo '<span class="cl-item">' . $item['img'] . '</span>';
          }
        }
      }
      ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════
     3. INTRO
═══════════════════════════════════════════════════════ -->
<section class="sec sec-surface ss-intro">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag">النتيجة التي نهتم بها</span>
      <h2 class="h2">الظهور مهم، لكن أثره على النشاط هو الأهم</h2>
    </div>
    <div class="ss-intro-body sr">
      <p>لا نتعامل مع الزيارات باعتبارها النتيجة النهائية. نراجع الكلمات والصفحات والتحويلات والإيرادات لفهم ما إذا كان النمو العضوي يصل إلى الجمهور الصحيح ويحقق قيمة فعلية للنشاط.</p>
      <p>لذلك قد تكون النتيجة الأقوى أحيانًا زيادة المبيعات أو العملاء المحتملين، حتى لو لم تكن الزيادة في عدد المستخدمين هي الرقم الأكبر داخل التقرير.</p>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     4. MAIN CASE STUDY
═══════════════════════════════════════════════════════ -->
<section class="sec sec-white ss-case">
  <div class="wrap">
    <p class="ss-case-label">دراسة حالة — <?php echo esc_html( $cs1_sector ); ?></p>
    <div class="ss-case-grid">

      <div class="ss-case-content">
        <h2 class="ss-case-h2">عندما أصبح البحث العضوي قناة مبيعات، لا مجرد مصدر زيارات</h2>
        <p class="ss-case-p">بدأنا بتحليل الصفحات والكلمات التي تجذب مستخدمين لديهم نية حقيقية للشراء، ثم أعدنا ترتيب الأولويات حول الأقسام والمنتجات والصفحات القادرة على تحقيق إيرادات، بدل التركيز على زيادة الترافيك فقط.</p>
        <p class="ss-case-p">خلال <?php echo esc_html( $cs1_period ); ?> في <?php echo esc_html( $cs1_market ); ?>، ارتفعت الإيرادات المنسوبة إلى البحث العضوي من <strong>19,956</strong> إلى <strong>106,274 ريال</strong> وفق بيانات القياس الظاهرة في الصورة.</p>

        <div class="ss-stat-highlight">
          19,956 → 106,274 <span class="sub">ريال</span>
        </div>

        <ul class="ss-what-list">
          <li class="ss-what-item"><span class="ss-what-dot" aria-hidden="true"></span>تحسين صفحات المنتجات والأقسام ذات الأولوية</li>
          <li class="ss-what-item"><span class="ss-what-dot" aria-hidden="true"></span>معالجة مشكلات تقنية وفهرسة مؤثرة</li>
          <li class="ss-what-item"><span class="ss-what-dot" aria-hidden="true"></span>تطوير المحتوى والربط الداخلي</li>
          <li class="ss-what-item"><span class="ss-what-dot" aria-hidden="true"></span>تحسين القياس ومتابعة مسار الشراء</li>
        </ul>

        <p class="ss-privacy-note">الأرقام المعروضة مأخوذة من بيانات العميل الفعلية، وقد تم إخفاء المعلومات الحساسة حفاظًا على الخصوصية.</p>
      </div>

      <div class="ss-case-screenshot-col">
        <figure class="ss-screenshot">
          <img
            src="<?php echo esc_url( $img . '/result-revenue-organic-106k.png' ); ?>"
            alt="لوحة Looker Studio تُظهر ارتفاع الإيرادات العضوية من 19,956 إلى 106,274 ريال"
            width="760" height="500" loading="lazy"
          >
          <figcaption class="ss-screenshot-caption">بيانات Looker Studio — إيرادات البحث العضوي</figcaption>
        </figure>
      </div>

    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     5. TWO ADDITIONAL RESULTS
═══════════════════════════════════════════════════════ -->
<section class="sec sec-surface ss-results">
  <div class="wrap">
    <div class="ss-results-pair">

      <article class="ss-result-item">
        <div class="ss-result-body">
          <span class="tag">نمو الظهور والنقرات</span>
          <h3 class="ss-result-h3">من ظهور محدود إلى نمو عضوي يمكن تتبعه</h3>
          <p class="ss-result-p">عملنا على توسيع تغطية الكلمات وتحسين الصفحات الحالية وإنشاء الصفحات المطلوبة وربط المحتوى ببعضه، مع متابعة الفهرسة والأداء من خلال Search Console.</p>
          <p class="ss-result-p">خلال <?php echo esc_html( $cs2_period ); ?>، ارتفعت النقرات من <strong>825</strong> إلى <strong>12.9 ألف نقرة</strong> مع تحسن واضح في وصول الصفحات إلى الباحثين عن <?php echo esc_html( $cs2_service ); ?> في <?php echo esc_html( $cs2_market ); ?>.</p>
        </div>
        <figure class="ss-result-screenshot">
          <img
            src="<?php echo esc_url( $img . '/result-gsc-clicks-12k.png' ); ?>"
            alt="Google Search Console تُظهر نمو النقرات من 825 إلى 12.9 ألف نقرة"
            width="680" height="400" loading="lazy"
          >
          <figcaption class="ss-screenshot-caption">Google Search Console — نمو النقرات</figcaption>
        </figure>
      </article>

      <article class="ss-result-item">
        <div class="ss-result-body">
          <span class="tag">Local SEO</span>
          <h3 class="ss-result-h3">ظهور أقرب للعميل في نتائج البحث المحلية</h3>
          <p class="ss-result-p">ركز العمل على ربط الخدمات بالمناطق المستهدفة، وتحسين إشارات الموقع والملف التجاري والمحتوى المحلي، ومتابعة الكلمات التي يستخدمها العميل عند البحث عن خدمة قريبة.</p>
          <p class="ss-result-p">ساعد ذلك <?php echo esc_html( $cs3_sector ); ?> على تحسين ظهوره أمام الباحثين في <?php echo esc_html( $cs3_city ); ?> وزيادة فرص الوصول من نتائج البحث المحلية.</p>
        </div>
        <figure class="ss-result-screenshot">
          <img
            src="<?php echo esc_url( $img . '/result-local-seo-lawfirm.png' ); ?>"
            alt="نتائج Google المحلية لمكتب المحاماة تُظهر ظهور قوي في خرائط Google"
            width="680" height="400" loading="lazy"
          >
          <figcaption class="ss-screenshot-caption">Google Maps — ظهور محلي</figcaption>
        </figure>
      </article>

    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     6. WORK JOURNEY TIMELINE
═══════════════════════════════════════════════════════ -->
<section id="ss-journey" class="sec sec-white ss-journey">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag">تأسيس المشروع</span>
      <h2 class="h2">نعرف أين يبدأ الموقع قبل أن نقرر ماذا ننفذ</h2>
      <p class="bod">كل مشروع يبدأ بفهم النشاط وأهدافه التجارية وتحديد المسؤوليات والصلاحيات ونقطة البداية، ثم تتحول البيانات إلى خريطة كلمات وخطة تنفيذ مرتبة حسب الأولوية.</p>
    </div>
    <ol class="ss-timeline sr" aria-label="مراحل تأسيس المشروع">

      <li class="ss-phase">
        <div class="ss-phase-num" aria-hidden="true">01</div>
        <h3 class="ss-phase-title">الـOnboarding والاجتماع الأول</h3>
        <p class="ss-phase-body">نرسل نموذجًا لجمع المعلومات الأساسية عن النشاط والخدمات أو المنتجات ذات الأولوية والأسواق المستهدفة والمنافسين والعملاء المثاليين وأهداف المشروع.</p>
        <p class="ss-phase-body">بعد ذلك نعقد اجتماعًا لتأكيد نطاق العمل، وتحديد المسؤولين عن الموافقات والتنفيذ، والاتفاق على قنوات التواصل ومواعيد المتابعة.</p>
      </li>

      <li class="ss-phase">
        <div class="ss-phase-num" aria-hidden="true">02</div>
        <h3 class="ss-phase-title">استلام الصلاحيات وضبط القياس</h3>
        <p class="ss-phase-body">نراجع صلاحيات الموقع وGoogle Search Console وGA4 وGoogle Tag Manager، بالإضافة إلى Google Business Profile أو Merchant Center عندما يكون ذلك مناسبًا.</p>
        <p class="ss-phase-body">نتأكد أيضًا من قياس الإجراءات المهمة مثل إرسال النماذج واتصالات الهاتف وضغطات واتساب والطلبات والمبيعات، حتى نقيس أثر SEO على النشاط وليس الزيارات فقط.</p>
      </li>

      <li class="ss-phase">
        <div class="ss-phase-num" aria-hidden="true">03</div>
        <h3 class="ss-phase-title">تحليل الوضع الحالي والفرص</h3>
        <p class="ss-phase-body">نفحص الجوانب التقنية والفهرسة وبنية الموقع والمحتوى الحالي والكلمات التي يظهر بها الموقع وأداء المنافسين والصفحات التي تمتلك فرصة حقيقية للنمو.</p>
        <p class="ss-phase-body">نسجل نقطة البداية قبل تنفيذ التعديلات، بحيث يمكن مقارنة النتائج اللاحقة بأرقام واضحة وموثقة.</p>
      </li>

      <li class="ss-phase">
        <div class="ss-phase-num" aria-hidden="true">04</div>
        <h3 class="ss-phase-title">خريطة الكلمات وخطة أول 90 يومًا</h3>
        <p class="ss-phase-body">نربط مجموعات الكلمات بالصفحات المناسبة، ونحدد الصفحات التي تحتاج إلى تحسين والصفحات الجديدة المطلوبة وفرص المحتوى والربط الداخلي.</p>
        <p class="ss-phase-body">بعد ذلك نحوّل النتائج إلى خطة لأول 90 يومًا مرتبة حسب الأولوية والتأثير المتوقع، مع توضيح ما سينفذه فريق SEO House وما يحتاج إلى موافقة أو تعاون من فريق العميل.</p>
      </li>

    </ol>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     7. MONTHLY CYCLE
═══════════════════════════════════════════════════════ -->
<section class="sec ss-cycle-section" style="background:var(--navy);overflow:hidden;position:relative">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag d">عمل مستمر، لا قائمة ثابتة</span>
      <h2 class="h2 wh">كل شهر يبدأ من البيانات وينتهي بخطة أوضح للشهر التالي</h2>
    </div>
    <ol class="ss-cycle-grid sr" aria-label="دورة التنفيذ الشهرية">
      <li class="ss-cycle-step">
        <span class="ss-cycle-num" aria-hidden="true">01</span>
        <h3>تحديد أولويات الشهر</h3>
        <p>نراجع بيانات الأداء وخطة المشروع ونحدد المهام الأعلى تأثيرًا، بدل تنفيذ قائمة ثابتة لا تراعي تغير الموقع أو السوق.</p>
      </li>
      <li class="ss-cycle-step">
        <span class="ss-cycle-num" aria-hidden="true">02</span>
        <h3>التنفيذ والتنسيق</h3>
        <p>يبدأ فريق المحتوى في تجهيز الصفحات المعتمدة، بينما يحوّل فريق SEO المشكلات التقنية إلى مهام واضحة وقابلة للتنفيذ.</p>
      </li>
      <li class="ss-cycle-step">
        <span class="ss-cycle-num" aria-hidden="true">03</span>
        <h3>المراجعة والـQA والمتابعة</h3>
        <p>نراجع الصفحات والتعديلات بعد التنفيذ، ونتأكد من الفهرسة والتتبع وعمل الصفحات على الديسكتوب والموبايل.</p>
      </li>
      <li class="ss-cycle-step">
        <span class="ss-cycle-num" aria-hidden="true">04</span>
        <h3>المراجعة الشهرية وخطة الشهر التالي</h3>
        <p>يحصل العميل على ملخص واضح لما تم تنفيذه وما تغير في الأداء والعوائق، ثم نحدد أولويات الشهر التالي.</p>
      </li>
    </ol>
    <blockquote class="ss-cycle-quote sr">تعرف دائمًا ما الذي نعمل عليه، وما الذي تم تنفيذه، وما الذي تغير، وما الخطوة التالية.</blockquote>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     8. DELIVERABLES
═══════════════════════════════════════════════════════ -->
<section class="sec sec-off ss-deliverables-section">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag">تسليمات واضحة</span>
      <h2 class="h2">لا نرسل توصيات عامة ونتركك أمام ملف طويل</h2>
      <p class="bod">كل تحليل يتحول إلى مرجع أو مهمة أو خطة قابلة للتنفيذ، مع مسؤول واضح وحالة متابعة.</p>
    </div>
    <div class="ss-deliverables">

      <!-- 01 keyword map -->
      <div class="ss-deliverable">
        <div class="ss-deliv-text">
          <div class="ss-deliv-num">01</div>
          <h3 class="ss-deliv-h3">خريطة الكلمات والصفحات</h3>
          <p class="ss-deliv-p">لا نقدم قائمة طويلة من الكلمات المفتاحية فقط؛ بل نربط كل مجموعة كلمات بالصفحة المناسبة، ونحدد نية البحث والصفحات التي تحتاج إلى تطوير والصفحات الجديدة المطلوبة.</p>
          <p class="ss-deliv-p">تتحول خريطة الكلمات إلى مرجع للمحتوى وتحسين الصفحات والربط الداخلي، وتساعد على منع استهداف الكلمة نفسها بأكثر من صفحة.</p>
        </div>
        <div class="ss-deliv-preview" aria-hidden="true">
          <div class="ss-deliv-abstract">
            <div class="ss-kw-row"><span class="ss-kw-pill wide"></span><span class="ss-kw-pill mid"></span><span class="ss-kw-tag"></span></div>
            <div class="ss-kw-row"><span class="ss-kw-pill mid"></span><span class="ss-kw-pill short"></span><span class="ss-kw-tag"></span></div>
            <div class="ss-kw-row"><span class="ss-kw-pill wide"></span><span class="ss-kw-tag"></span></div>
            <div class="ss-kw-row"><span class="ss-kw-pill short"></span><span class="ss-kw-pill mid"></span><span class="ss-kw-tag"></span></div>
          </div>
        </div>
      </div>

      <!-- 02 tech audit -->
      <div class="ss-deliverable">
        <div class="ss-deliv-text">
          <div class="ss-deliv-num">02</div>
          <h3 class="ss-deliv-h3">المراجعة التقنية وخطة التنفيذ</h3>
          <p class="ss-deliv-p">نفحص الفهرسة والزحف وسرعة الموقع وبنية الروابط والصفحات المكررة والتحويلات والـCanonical والمشكلات التي قد تعيق الظهور أو تؤثر في تجربة المستخدم.</p>
          <p class="ss-deliv-p">لا يتوقف العمل عند اكتشاف المشكلة؛ نحولها إلى مهام تنفيذية مرتبة حسب الأولوية والتأثير، ثم ننفذها أو نتابع تنفيذها ونراجع النتيجة.</p>
        </div>
        <div class="ss-deliv-preview" aria-hidden="true">
          <div class="ss-deliv-abstract">
            <div class="ss-audit-item"><span class="ss-audit-dot red"></span><div class="ss-audit-bar"><div class="ss-audit-fill crit"></div></div></div>
            <div class="ss-audit-item"><span class="ss-audit-dot amber"></span><div class="ss-audit-bar"><div class="ss-audit-fill warn"></div></div></div>
            <div class="ss-audit-item"><span class="ss-audit-dot green"></span><div class="ss-audit-bar"><div class="ss-audit-fill good"></div></div></div>
            <div class="ss-audit-item"><span class="ss-audit-dot amber"></span><div class="ss-audit-bar"><div class="ss-audit-fill warn"></div></div></div>
            <div class="ss-audit-item"><span class="ss-audit-dot green"></span><div class="ss-audit-bar"><div class="ss-audit-fill good"></div></div></div>
          </div>
        </div>
      </div>

      <!-- 03 content plan -->
      <div class="ss-deliverable">
        <div class="ss-deliv-text">
          <div class="ss-deliv-num">03</div>
          <h3 class="ss-deliv-h3">خطة المحتوى</h3>
          <p class="ss-deliv-p">نحدد الصفحات والموضوعات التي يحتاج إليها الموقع بناءً على البحث الفعلي وفجوات المنافسين ومراحل رحلة العميل، وليس بهدف نشر عدد ثابت من المقالات كل شهر.</p>
          <p class="ss-deliv-p">توضح الخطة الكلمة المستهدفة وهدف الصفحة ونوع المحتوى والأولوية وحالة الموافقة والكتابة والمراجعة والنشر.</p>
        </div>
        <div class="ss-deliv-preview" aria-hidden="true">
          <div class="ss-deliv-abstract">
            <div class="ss-plan-row"><span class="ss-plan-bar title"></span><span class="ss-plan-badge"></span><span class="ss-plan-badge"></span></div>
            <div class="ss-plan-row"><span class="ss-plan-bar title"></span><span class="ss-plan-badge"></span><span class="ss-plan-badge"></span></div>
            <div class="ss-plan-row"><span class="ss-plan-bar"></span><span class="ss-plan-badge"></span><span class="ss-plan-badge"></span></div>
            <div class="ss-plan-row"><span class="ss-plan-bar title"></span><span class="ss-plan-badge"></span><span class="ss-plan-badge"></span></div>
            <div class="ss-plan-row"><span class="ss-plan-bar"></span><span class="ss-plan-badge"></span><span class="ss-plan-badge"></span></div>
          </div>
        </div>
      </div>

      <!-- 04 dashboard -->
      <div class="ss-deliverable">
        <div class="ss-deliv-text">
          <div class="ss-deliv-num">04</div>
          <h3 class="ss-deliv-h3">لوحة متابعة لحظية</h3>
          <p class="ss-deliv-p">يحصل العميل على لوحة Looker Studio محدثة باستمرار تعرض مؤشرات الأداء المهمة من Search Console وGA4 ومصادر القياس المرتبطة بالمشروع.</p>
          <p class="ss-deliv-p">نركز داخل اللوحة على ما يهم النشاط: الظهور والنقرات والكلمات والصفحات والتحويلات والطلبات أو الإيرادات.</p>
        </div>
        <div class="ss-deliv-preview" aria-hidden="true">
          <div class="ss-deliv-abstract">
            <div class="ss-dash-metrics">
              <div class="ss-dash-metric"><div class="ss-dash-label"></div><div class="ss-dash-value"></div></div>
              <div class="ss-dash-metric"><div class="ss-dash-label"></div><div class="ss-dash-value"></div></div>
            </div>
            <div class="ss-dash-bars">
              <div class="ss-dash-bar" style="height:35%"></div>
              <div class="ss-dash-bar" style="height:55%"></div>
              <div class="ss-dash-bar" style="height:45%"></div>
              <div class="ss-dash-bar" style="height:75%"></div>
              <div class="ss-dash-bar" style="height:60%"></div>
              <div class="ss-dash-bar" style="height:85%"></div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     9. SCOPE ACCORDION
═══════════════════════════════════════════════════════ -->
<section class="sec sec-white ss-scope-section">
  <div class="wrap">
    <div class="sh c sr">
      <h2 class="h2">ما الذي يمكن أن يشمله العمل؟</h2>
      <p class="bod">نحدد نطاق العمل بعد مراجعة الموقع وحجم المنافسة والموارد المتاحة، لأن متجرًا يضم مئات المنتجات لا يحتاج إلى الخطة نفسها التي يحتاج إليها موقع خدمات.</p>
    </div>
    <ul class="ss-scope-list sr" id="ssScopeList" aria-label="محاور خدمة SEO">

      <li class="ss-scope-item">
        <button type="button" class="ss-scope-btn" aria-expanded="false" aria-controls="ssScope1">
          <span class="ss-scope-num">01</span>
          <span class="ss-scope-title">Technical SEO</span>
          <span class="ss-scope-icon"><svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2 4.5l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        </button>
        <div id="ssScope1" class="ss-scope-body" role="region">
          <p class="ss-scope-inner">الزحف والفهرسة والسرعة وبنية الموقع والتحويلات والـCanonical والمشكلات التقنية المؤثرة — تتحول كل مشكلة إلى مهمة واضحة مرتبة حسب أولويتها وتأثيرها.</p>
        </div>
      </li>

      <li class="ss-scope-item">
        <button type="button" class="ss-scope-btn" aria-expanded="false" aria-controls="ssScope2">
          <span class="ss-scope-num">02</span>
          <span class="ss-scope-title">Keyword &amp; Page Strategy</span>
          <span class="ss-scope-icon"><svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2 4.5l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        </button>
        <div id="ssScope2" class="ss-scope-body" role="region">
          <p class="ss-scope-inner">خريطة الكلمات، نية البحث، هيكلة الصفحات ومنع التنافس بين صفحات الموقع — بحيث تستهدف كل صفحة مجموعتها الخاصة دون تداخل.</p>
        </div>
      </li>

      <li class="ss-scope-item">
        <button type="button" class="ss-scope-btn" aria-expanded="false" aria-controls="ssScope3">
          <span class="ss-scope-num">03</span>
          <span class="ss-scope-title">On-Page SEO</span>
          <span class="ss-scope-icon"><svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2 4.5l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        </button>
        <div id="ssScope3" class="ss-scope-body" role="region">
          <p class="ss-scope-inner">العناوين والوصف وH1 والمحتوى والروابط الداخلية وتحسين الصفحات ذات الأولوية — التحسين على مستوى الصفحة يُسرّع الظهور أمام الكلمات الأقرب لنية البحث.</p>
        </div>
      </li>

      <li class="ss-scope-item">
        <button type="button" class="ss-scope-btn" aria-expanded="false" aria-controls="ssScope4">
          <span class="ss-scope-num">04</span>
          <span class="ss-scope-title">Content Strategy</span>
          <span class="ss-scope-icon"><svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2 4.5l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        </button>
        <div id="ssScope4" class="ss-scope-body" role="region">
          <p class="ss-scope-inner">صفحات الخدمات والأقسام والمقالات وتحديث المحتوى القائم وفق فرص البحث ورحلة العميل — الموضوعات تُختار وفق الكلمات والفرص ذات الأولوية، لا بهدف رقم ثابت.</p>
        </div>
      </li>

      <li class="ss-scope-item">
        <button type="button" class="ss-scope-btn" aria-expanded="false" aria-controls="ssScope5">
          <span class="ss-scope-num">05</span>
          <span class="ss-scope-title">Authority &amp; Off-Page</span>
          <span class="ss-scope-icon"><svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2 4.5l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        </button>
        <div id="ssScope5" class="ss-scope-body" role="region">
          <p class="ss-scope-inner">تحليل الروابط والفرص المناسبة وبناء الإشارات الخارجية وفق نطاق متفق عليه، من دون وعود بعدد عشوائي من الروابط — الجودة والصلة بالموضوع أهم من الكمية.</p>
        </div>
      </li>

      <li class="ss-scope-item">
        <button type="button" class="ss-scope-btn" aria-expanded="false" aria-controls="ssScope6">
          <span class="ss-scope-num">06</span>
          <span class="ss-scope-title">Measurement &amp; Reporting</span>
          <span class="ss-scope-icon"><svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2 4.5l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        </button>
        <div id="ssScope6" class="ss-scope-body" role="region">
          <p class="ss-scope-inner">إعداد وطرح مؤشرات القياس، تتبع التحويلات، لوحة الأداء والمراجعة الشهرية — القياس يربط العمل بالنتائج ويُحدد الأولويات للشهر التالي.</p>
        </div>
      </li>

    </ul>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     10. REPORTING
═══════════════════════════════════════════════════════ -->
<section class="sec sec-surface ss-report">
  <div class="wrap">
    <div class="ss-report-grid">
      <div>
        <figure class="ss-report-photo">
          <img
            src="<?php echo esc_url( $img . '/office-laptop-dashboard.png' ); ?>"
            alt="فريق SEO House يعمل على لوحة Looker Studio"
            width="640" height="480" loading="lazy"
          >
        </figure>
      </div>
      <div class="sr">
        <span class="tag">متابعة مستمرة</span>
        <h2 class="h2">ترى الأرقام وقتما تحتاج، ونراجع معناها معك كل شهر</h2>
        <p class="bod" style="margin-top:14px;margin-bottom:10px">لا ننتظر نهاية الشهر لنرسل ملف PDF ثابتًا. يحصل العميل على لوحة Looker Studio محدثة باستمرار، تعرض البيانات الأهم وفق طبيعة مشروعه ومصادر القياس المتاحة.</p>
        <p class="bod" style="margin-bottom:0">وفي المراجعة الشهرية لا نكتفي بقراءة الأرقام؛ نوضح ما تم تنفيذه، وما الذي تغير، وما الذي لم يتحرك بعد، وأين توجد الفرصة أو العائق.</p>
        <ul class="ss-report-bullets">
          <li class="ss-report-bullet"><span class="ss-report-bullet-dot" aria-hidden="true"></span>بيانات من Search Console وGA4 والمصادر المرتبطة بالمشروع</li>
          <li class="ss-report-bullet"><span class="ss-report-bullet-dot" aria-hidden="true"></span>متابعة للكلمات والصفحات والتحويلات والإيرادات عند توافرها</li>
          <li class="ss-report-bullet"><span class="ss-report-bullet-dot" aria-hidden="true"></span>ملخص تنفيذ واضح وأولويات للشهر التالي</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     11. REVIEWS
═══════════════════════════════════════════════════════ -->
<?php
$reviews_sc = sh_option( 'reviews_shortcode' );
if ( $reviews_sc ) :
?>
<section class="sec sec-white ss-reviews">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag">تجربة العمل معنا</span>
      <h2 class="h2">ماذا يقول عملاؤنا عن SEO House؟</h2>
    </div>
    <?php echo do_shortcode( $reviews_sc ); ?>
  </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════
     12. FAQ
═══════════════════════════════════════════════════════ -->
<section class="sec sec-off ss-faq-section">
  <div class="wrap">
    <div class="sh c sr" style="margin-bottom:clamp(28px,4vw,44px)">
      <h2 class="h2">الأسئلة الشائعة</h2>
    </div>
    <div class="faq-list" id="svcSeoFaq" role="list">

      <div class="faq-item" role="listitem">
        <button type="button" class="faq-q" aria-expanded="false" aria-controls="svcSeoFaqA-1">
          <span>متى تبدأ نتائج SEO في الظهور؟</span>
          <span class="faq-icon" aria-hidden="true"><svg viewBox="0 0 12 12" fill="none"><path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></span>
        </button>
        <div id="svcSeoFaqA-1" class="faq-a"><div class="faq-a-inner">
          <p>تختلف المدة حسب حالة الموقع والمنافسة وسرعة تنفيذ التعديلات. قد تظهر مؤشرات أولية بعد معالجة مشكلات واضحة أو تحسين صفحات تمتلك فرصة قائمة.</p>
          <p style="margin-top:8px">قبل بدء المشروع نسجل نقطة البداية ونحدد مؤشرات القياس، ثم نراجع التغير شهريًا بدل تقديم وعد بزمن أو ترتيب لا يمكن ضمانه.</p>
        </div></div>
      </div>

      <div class="faq-item" role="listitem">
        <button type="button" class="faq-q" aria-expanded="false" aria-controls="svcSeoFaqA-2">
          <span>هل تنفذون التعديلات التقنية أم ترسلون التوصيات فقط؟</span>
          <span class="faq-icon" aria-hidden="true"><svg viewBox="0 0 12 12" fill="none"><path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></span>
        </button>
        <div id="svcSeoFaqA-2" class="faq-a"><div class="faq-a-inner">
          <p>نحوّل المشكلات إلى مهام واضحة مرتبة حسب الأولوية. وبحسب نطاق الاتفاق، يمكن تنفيذ التعديلات من خلال مطور SEO House أو التنسيق مع مطور العميل ومراجعة ما تم تنفيذه.</p>
          <p style="margin-top:8px">لا نعتبر المهمة منتهية بمجرد إرسال الملاحظة؛ نتابع التنفيذ ونراجع النتيجة على الموقع.</p>
        </div></div>
      </div>

      <div class="faq-item" role="listitem">
        <button type="button" class="faq-q" aria-expanded="false" aria-controls="svcSeoFaqA-3">
          <span>هل يحصل العميل على تقرير شهري؟</span>
          <span class="faq-icon" aria-hidden="true"><svg viewBox="0 0 12 12" fill="none"><path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></span>
        </button>
        <div id="svcSeoFaqA-3" class="faq-a"><div class="faq-a-inner">
          <p>يحصل العميل على لوحة Looker Studio محدثة باستمرار، إلى جانب مراجعة شهرية توضّح ما تم تنفيذه وما تغير في الأداء والعوائق وخطة الشهر التالي.</p>
        </div></div>
      </div>

      <div class="faq-item" role="listitem">
        <button type="button" class="faq-q" aria-expanded="false" aria-controls="svcSeoFaqA-4">
          <span>هل تشمل الخدمة كتابة المحتوى؟</span>
          <span class="faq-icon" aria-hidden="true"><svg viewBox="0 0 12 12" fill="none"><path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></span>
        </button>
        <div id="svcSeoFaqA-4" class="faq-a"><div class="faq-a-inner">
          <p>يمكن أن يشمل نطاق المشروع تخطيط المحتوى وكتابته ومراجعته وتحسينه ونشره، أو التنسيق مع فريق المحتوى لدى العميل، وفق ما يتم الاتفاق عليه قبل بدء العمل.</p>
        </div></div>
      </div>

      <div class="faq-item" role="listitem">
        <button type="button" class="faq-q" aria-expanded="false" aria-controls="svcSeoFaqA-5">
          <span>هل تضمنون الوصول إلى المركز الأول؟</span>
          <span class="faq-icon" aria-hidden="true"><svg viewBox="0 0 12 12" fill="none"><path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></span>
        </button>
        <div id="svcSeoFaqA-5" class="faq-a"><div class="faq-a-inner">
          <p>لا توجد شركة يمكنها ضمان ترتيب محدد داخل نتائج Google. ما يمكننا الالتزام به هو تحليل واضح وتنفيذ منظم وقياس مستمر وشفافية في عرض ما تحقق وما يحتاج إلى وقت أو قرار.</p>
        </div></div>
      </div>

      <div class="faq-item" role="listitem">
        <button type="button" class="faq-q" aria-expanded="false" aria-controls="svcSeoFaqA-6">
          <span>كم تبلغ تكلفة خدمة SEO؟</span>
          <span class="faq-icon" aria-hidden="true"><svg viewBox="0 0 12 12" fill="none"><path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></span>
        </button>
        <div id="svcSeoFaqA-6" class="faq-a"><div class="faq-a-inner">
          <p>تتراوح أغلب مشاريع SEO House بين <strong>1,500</strong> و<strong>7,000 ريال شهريًا</strong>، وفق حجم الموقع والسوق المستهدف ومستوى المنافسة وحجم المحتوى والدعم التقني المطلوب.</p>
          <p style="margin-top:8px">بعد مراجعة الموقع نحدد نطاق العمل والأولويات والتكلفة المناسبة بوضوح<?php if ( $pricing_url ) : ?> — <a href="<?php echo esc_url( $pricing_url ); ?>" style="color:var(--blue)">تفاصيل التكلفة والنطاق</a><?php endif; ?>.</p>
        </div></div>
      </div>

    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     13. CTA + FORM
═══════════════════════════════════════════════════════ -->
<section class="sec ss-cta-section" id="seoSvcFormWrap" style="background:var(--navy);overflow:hidden;position:relative">
  <div class="wrap">
    <div class="ss-form-grid">

      <div class="ss-form-intro">
        <h2 class="h2 wh">لنبدأ بمراجعة موقعك وتحديد الفرصة الأقرب</h2>
        <p style="color:rgba(255,255,255,.62);font-size:14.5px;line-height:1.88;margin-top:16px;margin-bottom:10px">أرسل لنا رابط الموقع والخدمة أو السوق الذي تريد التركيز عليه. سنراجع الوضع الحالي ونوضح لك أين توجد الأولويات وما نطاق العمل المناسب قبل بدء المشروع.</p>
        <p style="color:rgba(255,255,255,.62);font-size:14.5px;line-height:1.88">لا نرسل عرضًا عامًا قبل فهم الموقع؛ لأن حجم الصفحات والمنافسة وسرعة التنفيذ المطلوبة هي ما تحدد الخطة والتكلفة.</p>
        <?php
        $wa_num = sh_option( 'whatsapp_number' );
        if ( $wa_num ) :
          $wa_clean = preg_replace( '/\D/', '', $wa_num );
        ?>
        <a href="https://wa.me/<?php echo esc_attr( $wa_clean ); ?>?text=<?php echo rawurlencode( 'مرحبًا، أريد مراجعة موقعي' ); ?>"
           class="ss-wa-btn" target="_blank" rel="noopener noreferrer">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" fill="currentColor"/></svg>
          تواصل عبر واتساب
        </a>
        <?php endif; ?>
      </div>

      <div>
        <div class="form-card" id="seoSvcWrap">
          <form id="seoSvcForm" novalidate>
            <input type="hidden" id="seoSvcAjaxUrl" value="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
            <input type="hidden" id="seoSvcNonce"   value="<?php echo esc_attr( wp_create_nonce( 'seohouse_nonce' ) ); ?>">

            <div class="form-row">
              <div class="form-group">
                <label for="seoSvcName">الاسم <span aria-hidden="true">*</span></label>
                <input class="form-input" type="text" id="seoSvcName" name="name" required autocomplete="name" placeholder="اسمك الكريم">
              </div>
              <div class="form-group">
                <label for="seoSvcCompany">اسم الشركة</label>
                <input class="form-input" type="text" id="seoSvcCompany" name="company" autocomplete="organization" placeholder="اسم الشركة أو النشاط">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="seoSvcPhone">الجوال أو واتساب <span aria-hidden="true">*</span></label>
                <input class="form-input" type="tel" id="seoSvcPhone" name="phone" required autocomplete="tel" placeholder="+966 5x xxx xxxx" dir="ltr">
              </div>
              <div class="form-group">
                <label for="seoSvcEmail">البريد الإلكتروني</label>
                <input class="form-input" type="email" id="seoSvcEmail" name="email" autocomplete="email" placeholder="email@example.com" dir="ltr">
              </div>
            </div>

            <div class="form-group">
              <label for="seoSvcSite">رابط الموقع <span aria-hidden="true">*</span></label>
              <input class="form-input" type="url" id="seoSvcSite" name="website" required autocomplete="url" placeholder="https://yoursite.com" dir="ltr">
            </div>

            <div class="form-group">
              <label for="seoSvcMarket">السوق المستهدف</label>
              <input class="form-input" type="text" id="seoSvcMarket" name="target_market" placeholder="مثال: السعودية — قطاع العقارات">
            </div>

            <div class="form-group">
              <label for="seoSvcMsg">الهدف الأساسي أو رسالة مختصرة</label>
              <textarea class="form-input form-textarea" id="seoSvcMsg" name="message" rows="3" placeholder="ما الذي تريد تحسينه أو ما التحدي الأساسي الذي تواجهه؟"></textarea>
            </div>

            <p id="seoSvcError" class="form-error" role="alert" aria-live="polite"></p>

            <button type="submit" class="form-submit" id="seoSvcSubmit">
              اطلب مراجعة موقعك
            </button>
          </form>
        </div>

        <div class="form-success" id="seoSvcSuccess" style="display:none" role="alert">
          <svg width="48" height="48" viewBox="0 0 48 48" fill="none" aria-hidden="true"><circle cx="24" cy="24" r="22" stroke="var(--green)" stroke-width="2.5"/><path d="M14 24l8 8 13-16" stroke="var(--green)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
          <h3>تم الإرسال بنجاح</h3>
          <p>سنراجع موقعك ونتواصل معك خلال يوم عمل.</p>
        </div>
      </div>

    </div>
  </div>
</section>

</div><!-- /svc-seo -->

<script>
(function () {
  /* ── Scope accordion ────────────────────────────────────────────── */
  var scopeList = document.getElementById('ssScopeList');
  if (scopeList) {
    scopeList.addEventListener('click', function (e) {
      var btn = e.target.closest('.ss-scope-btn');
      if (!btn) return;
      var item   = btn.closest('.ss-scope-item');
      var isOpen = item.classList.contains('open');
      scopeList.querySelectorAll('.ss-scope-item.open').forEach(function (el) {
        el.classList.remove('open');
        el.querySelector('.ss-scope-btn').setAttribute('aria-expanded', 'false');
      });
      if (!isOpen) {
        item.classList.add('open');
        btn.setAttribute('aria-expanded', 'true');
      }
    });
  }

  /* ── FAQ aria-expanded sync (main.js handles .open toggle) ─────── */
  var faqList = document.getElementById('svcSeoFaq');
  if (faqList && typeof MutationObserver !== 'undefined') {
    var obs = new MutationObserver(function (muts) {
      muts.forEach(function (m) {
        if (m.attributeName !== 'class') return;
        var item = m.target;
        var btn  = item.querySelector('.faq-q');
        if (btn) btn.setAttribute('aria-expanded', item.classList.contains('open') ? 'true' : 'false');
      });
    });
    faqList.querySelectorAll('.faq-item').forEach(function (el) {
      obs.observe(el, { attributes: true });
    });
  }

  /* ── Contact form ───────────────────────────────────────────────── */
  var form    = document.getElementById('seoSvcForm');
  var wrap    = document.getElementById('seoSvcWrap');
  var success = document.getElementById('seoSvcSuccess');
  var errBox  = document.getElementById('seoSvcError');
  var submit  = document.getElementById('seoSvcSubmit');
  if (!form) return;

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    errBox.textContent = '';

    var ajaxUrl = document.getElementById('seoSvcAjaxUrl').value;
    var nonce   = document.getElementById('seoSvcNonce').value;
    var company = (document.getElementById('seoSvcCompany').value || '').trim();
    var market  = (document.getElementById('seoSvcMarket').value  || '').trim();
    var baseMsg = (document.getElementById('seoSvcMsg').value     || '').trim();

    var parts = [];
    if (company) parts.push('الشركة: ' + company);
    if (market)  parts.push('السوق: ' + market);
    var fullMsg = parts.length ? parts.join(' | ') + (baseMsg ? '\n\n' + baseMsg : '') : baseMsg;

    var fd = new FormData();
    fd.append('action',  'sh_contact');
    fd.append('nonce',   nonce);
    fd.append('name',    document.getElementById('seoSvcName').value.trim());
    fd.append('phone',   document.getElementById('seoSvcPhone').value.trim());
    fd.append('email',   document.getElementById('seoSvcEmail').value.trim());
    fd.append('website', document.getElementById('seoSvcSite').value.trim());
    fd.append('message', fullMsg);
    fd.append('source',  'seo-service');

    submit.disabled = true;
    submit.textContent = '…';

    fetch(ajaxUrl, { method: 'POST', body: fd })
      .then(function (r) { return r.json(); })
      .then(function (json) {
        if (json && json.success) {
          wrap.style.display    = 'none';
          success.style.display = '';
        } else {
          errBox.textContent = (json && json.data) ? json.data : 'حدث خطأ، يرجى المحاولة مرة أخرى.';
          submit.disabled = false;
          submit.textContent = 'اطلب مراجعة موقعك';
        }
      })
      .catch(function () {
        errBox.textContent = 'حدث خطأ في الاتصال، يرجى المحاولة مرة أخرى.';
        submit.disabled = false;
        submit.textContent = 'اطلب مراجعة موقعك';
      });
  });
}());
</script>

<?php get_footer(); ?>
