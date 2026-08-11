<?php
/**
 * Template Name: Service — SEO Main
 *
 * TODO before publishing:
 *   Replace every [مجال العميل], [مدة العمل], [السوق المستهدف],
 *   [الخدمة أو المنتج], [المدينة أو المنطقة] placeholder below
 *   with the real client data.
 */
get_header();

// ── Editable placeholders — replace before publishing ──────────────────────
$cs1_sector  = '[مجال العميل]';
$cs1_period  = '[مدة العمل]';
$cs1_market  = '[السوق المستهدف]';

$cs2_period  = '[مدة العمل]';
$cs2_service = '[الخدمة أو المنتج]';
$cs2_market  = '[السوق المستهدف]';

$cs3_sector  = '[مجال العميل]';
$cs3_city    = '[المدينة أو المنطقة]';

// ── Pricing page URL — link once published, leave empty to hide ─────────────
$pricing_url = sh_page_url( 'seo-pricing' ) ?: '';

// ── Image base path ─────────────────────────────────────────────────────────
$img = get_template_directory_uri() . '/assets/images/seo-service';
?>
<div class="svc-seo">

<!-- ═══════════════════════════════════════════════════════
     1. HERO
═══════════════════════════════════════════════════════ -->
<section class="ss-hero sec-navy">
  <span class="ss-hero-glow" aria-hidden="true"></span>
  <div class="sh">
    <div class="ss-hero-grid">

      <!-- Text column -->
      <div class="ss-hero-text">
        <span class="ss-hero-eyebrow">
          <span class="ss-hero-dot" aria-hidden="true"></span>
          خدمات تحسين محركات البحث
        </span>
        <h1 class="ss-hero-h1">شركة سيو لا تكتفي بإرسال التوصيات</h1>
        <p class="ss-hero-p">
          نحوّل بيانات البحث إلى خطة تنفيذ واضحة تشمل الجوانب التقنية والمحتوى والصفحات والقياس، ثم نتابع تنفيذها مع فريقك أو ننفذها من خلال فريق SEO House.
        </p>
        <p class="ss-hero-p">
          هدفنا ليس زيادة الزيارات بأي كلمات، بل تحسين ظهورك أمام الأشخاص الذين يبحثون فعلًا عن خدماتك أو منتجاتك، وربط هذا الظهور بالطلبات والعملاء المحتملين والمبيعات.
        </p>
        <div class="ss-hero-ctas">
          <a href="#seoSvcFormWrap" class="btn btn-primary">اطلب مراجعة موقعك</a>
          <a href="#ss-journey" class="btn btn-ghost">شاهد كيف نعمل</a>
        </div>
        <ul class="ss-hero-trust" aria-label="مميزات الخدمة">
          <li>
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true"><circle cx="8" cy="8" r="7" stroke="rgba(30,46,245,.6)" stroke-width="1.5"/><path d="M5 8l2 2 4-4" stroke="#7b90ff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            خطة مبنية على بيانات موقعك
          </li>
          <li>
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true"><circle cx="8" cy="8" r="7" stroke="rgba(30,46,245,.6)" stroke-width="1.5"/><path d="M5 8l2 2 4-4" stroke="#7b90ff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            دعم تقني ومتابعة للتنفيذ
          </li>
          <li>
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true"><circle cx="8" cy="8" r="7" stroke="rgba(30,46,245,.6)" stroke-width="1.5"/><path d="M5 8l2 2 4-4" stroke="#7b90ff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            لوحة أداء محدثة باستمرار
          </li>
        </ul>
      </div>

      <!-- Photo column -->
      <div class="ss-hero-photo-col" aria-hidden="true">
        <div class="ss-hero-photo-wrap">
          <img
            src="<?php echo esc_url( $img . '/office-laptop-dashboard.png' ); ?>"
            alt="فريق SEO House أمام لوحة أداء Looker Studio على لابتوب"
            width="640"
            height="480"
            loading="eager"
            fetchpriority="high"
            class="ss-hero-photo"
          >
          <div class="ss-hero-stat-badge" aria-hidden="true">
            <span class="ss-hero-stat-num">106,274</span>
            <span class="ss-hero-stat-label">إيرادات عضوية — ريال سعودي</span>
          </div>
        </div>
      </div>

    </div><!-- /ss-hero-grid -->
  </div><!-- /sh -->
</section>

<!-- ═══════════════════════════════════════════════════════
     2. CLIENTS
═══════════════════════════════════════════════════════ -->
<?php
$clients = sh_get_clients();
if ( $clients && $clients->have_posts() ) :
?>
<section class="sec-white ss-clients-section">
  <div class="sh c sr">
    <p class="ss-clients-label">جهات وثقت في SEO House</p>
  </div>
  <div class="cl-marquee" aria-hidden="true">
    <div class="cl-marquee-track">
      <?php
      while ( $clients->have_posts() ) :
        $clients->the_post();
        $logo = get_field( 'client_logo' );
        $url  = get_field( 'client_url' ) ?: '';
        if ( ! $logo ) continue;
        $img_tag = '<img src="' . esc_url( $logo['url'] ) . '" alt="' . esc_attr( get_the_title() ) . '" width="' . esc_attr( $logo['width'] ?? 120 ) . '" height="' . esc_attr( $logo['height'] ?? 40 ) . '" loading="lazy">';
        if ( $url ) {
          echo '<a href="' . esc_url( $url ) . '" class="cl-logo" target="_blank" rel="noopener noreferrer">' . $img_tag . '</a>';
        } else {
          echo '<span class="cl-logo">' . $img_tag . '</span>';
        }
      endwhile;
      wp_reset_postdata();
      // duplicate for seamless loop
      rewind_posts();
      $clients_dup = sh_get_clients();
      while ( $clients_dup->have_posts() ) :
        $clients_dup->the_post();
        $logo = get_field( 'client_logo' );
        $url  = get_field( 'client_url' ) ?: '';
        if ( ! $logo ) continue;
        $img_tag = '<img src="' . esc_url( $logo['url'] ) . '" alt="' . esc_attr( get_the_title() ) . '" width="' . esc_attr( $logo['width'] ?? 120 ) . '" height="' . esc_attr( $logo['height'] ?? 40 ) . '" loading="lazy">';
        if ( $url ) {
          echo '<a href="' . esc_url( $url ) . '" class="cl-logo" target="_blank" rel="noopener noreferrer">' . $img_tag . '</a>';
        } else {
          echo '<span class="cl-logo">' . $img_tag . '</span>';
        }
      endwhile;
      wp_reset_postdata();
      ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════
     3. INTRO — النتيجة التي نهتم بها
═══════════════════════════════════════════════════════ -->
<section class="sec-surface ss-intro">
  <div class="sh c sr">
    <span class="tag">النتيجة التي نهتم بها</span>
    <h2 class="h2">الظهور مهم، لكن أثره على النشاط هو الأهم</h2>
    <div class="ss-intro-body">
      <p>لا نتعامل مع الزيارات باعتبارها النتيجة النهائية. نراجع الكلمات والصفحات والتحويلات والإيرادات لفهم ما إذا كان النمو العضوي يصل إلى الجمهور الصحيح ويحقق قيمة فعلية للنشاط.</p>
      <p>لذلك قد تكون النتيجة الأقوى أحيانًا زيادة المبيعات أو العملاء المحتملين، حتى لو لم تكن الزيادة في عدد المستخدمين هي الرقم الأكبر داخل التقرير.</p>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     4. MAIN CASE STUDY — نمو المبيعات العضوية
═══════════════════════════════════════════════════════ -->
<section class="sec-white ss-case">
  <div class="sh">
    <p class="ss-case-label"><!-- TODO: Replace <?php echo esc_html( $cs1_sector ); ?> before publishing -->
      دراسة حالة — <?php echo esc_html( $cs1_sector ); ?>
    </p>
    <div class="ss-case-grid">

      <!-- Content -->
      <div class="ss-case-content">
        <h2 class="ss-case-h2">عندما أصبح البحث العضوي قناة مبيعات، لا مجرد مصدر زيارات</h2>
        <p class="ss-case-p">بدأنا بتحليل الصفحات والكلمات التي تجذب مستخدمين لديهم نية حقيقية للشراء، ثم أعدنا ترتيب الأولويات حول الأقسام والمنتجات والصفحات القادرة على تحقيق إيرادات، بدل التركيز على زيادة الترافيك فقط.</p>
        <p class="ss-case-p">
          خلال <?php echo esc_html( $cs1_period ); ?> في سوق <?php echo esc_html( $cs1_market ); ?>، ارتفعت الإيرادات المنسوبة إلى البحث العضوي من <strong>19,956</strong> إلى <strong>106,274 ريال</strong> وفق بيانات القياس الظاهرة في الصورة.
        </p>

        <div class="ss-stat-highlight">
          <span class="ss-stat-from">19,956 ﷼</span>
          <svg width="24" height="14" viewBox="0 0 24 14" fill="none" aria-hidden="true"><path d="M0 7h22M16 1l6 6-6 6" stroke="var(--blue)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          <span class="ss-stat-to">106,274 ﷼</span>
        </div>

        <ul class="ss-what-list">
          <li class="ss-what-item">
            <!-- TODO: Replace with real deliverable -->
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true"><circle cx="8" cy="8" r="7.25" stroke="var(--blue)" stroke-width="1.5"/><path d="M5 8l2 2 4-4" stroke="var(--blue)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            [تحسين الأقسام أو صفحات المنتجات ذات الأولوية]
          </li>
          <li class="ss-what-item">
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true"><circle cx="8" cy="8" r="7.25" stroke="var(--blue)" stroke-width="1.5"/><path d="M5 8l2 2 4-4" stroke="var(--blue)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            [معالجة مشكلات تقنية وفهرسة مؤثرة]
          </li>
          <li class="ss-what-item">
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true"><circle cx="8" cy="8" r="7.25" stroke="var(--blue)" stroke-width="1.5"/><path d="M5 8l2 2 4-4" stroke="var(--blue)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            [تطوير المحتوى والربط الداخلي]
          </li>
          <li class="ss-what-item">
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true"><circle cx="8" cy="8" r="7.25" stroke="var(--blue)" stroke-width="1.5"/><path d="M5 8l2 2 4-4" stroke="var(--blue)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            [تحسين القياس ومتابعة مسار الشراء]
          </li>
        </ul>
        <p class="ss-privacy-note">الأرقام المعروضة مأخوذة من بيانات العميل الفعلية، وقد تم إخفاء المعلومات الحساسة حفاظًا على الخصوصية.</p>
      </div>

      <!-- Screenshot -->
      <div class="ss-case-screenshot-col">
        <figure class="ss-screenshot">
          <img
            src="<?php echo esc_url( $img . '/result-revenue-organic-106k.png' ); ?>"
            alt="لوحة Looker Studio تُظهر ارتفاع الإيرادات العضوية من 19,956 إلى 106,274 ريال"
            width="760"
            height="500"
            loading="lazy"
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
<section class="sec-surface ss-results">
  <div class="sh">
    <div class="ss-results-pair">

      <!-- Case 2: GSC clicks growth -->
      <article class="ss-result-item">
        <div class="ss-result-body">
          <span class="tag">نمو الظهور والنقرات</span>
          <h2 class="h3 ss-result-h">من ظهور محدود إلى نمو عضوي يمكن تتبعه</h2>
          <p>عملنا على توسيع تغطية الكلمات وتحسين الصفحات الحالية وإنشاء الصفحات المطلوبة وربط المحتوى ببعضه، مع متابعة الفهرسة والأداء من خلال Search Console.</p>
          <p>
            خلال <?php echo esc_html( $cs2_period ); ?>، ارتفعت النقرات العضوية من <strong>825</strong> إلى <strong>12.9 ألف نقرة</strong>، مع تحسن واضح في وصول الصفحات إلى الباحثين عن <?php echo esc_html( $cs2_service ); ?> داخل <?php echo esc_html( $cs2_market ); ?>.
          </p>
        </div>
        <figure class="ss-result-screenshot">
          <img
            src="<?php echo esc_url( $img . '/result-gsc-clicks-12k.png' ); ?>"
            alt="Google Search Console تُظهر نمو النقرات من 825 إلى 12.9 ألف نقرة"
            width="680"
            height="400"
            loading="lazy"
          >
          <figcaption class="ss-screenshot-caption">Google Search Console — نمو النقرات</figcaption>
        </figure>
      </article>

      <!-- Case 3: Local SEO -->
      <article class="ss-result-item ss-result-item--compact">
        <div class="ss-result-body">
          <span class="tag">Local SEO</span>
          <h2 class="h3 ss-result-h">ظهور أقرب للعميل في نتائج البحث المحلية</h2>
          <p>ركز العمل على ربط الخدمات بالمناطق المستهدفة، وتحسين إشارات الموقع والملف التجاري والمحتوى المحلي، ومتابعة الكلمات التي يستخدمها العميل عند البحث عن خدمة قريبة منه.</p>
          <p>
            ساعد ذلك <?php echo esc_html( $cs3_sector ); ?> على تحسين ظهوره أمام الباحثين في <?php echo esc_html( $cs3_city ); ?> وزيادة فرص الوصول من نتائج البحث المحلية.
          </p>
        </div>
        <figure class="ss-result-screenshot">
          <img
            src="<?php echo esc_url( $img . '/result-local-seo-lawfirm.png' ); ?>"
            alt="نتائج Google المحلية لمكتب المحاماة تُظهر ظهور قوي في خرائط Google"
            width="680"
            height="400"
            loading="lazy"
          >
          <figcaption class="ss-screenshot-caption">Google Maps — ظهور محلي لمكتب محاماة</figcaption>
        </figure>
      </article>

    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     6. WORK JOURNEY TIMELINE
═══════════════════════════════════════════════════════ -->
<section id="ss-journey" class="sec-white ss-journey">
  <div class="sh c sr">
    <span class="tag">تأسيس المشروع</span>
    <h2 class="h2">نعرف أين يبدأ الموقع قبل أن نقرر ماذا ننفذ</h2>
    <p class="bod">كل مشروع يبدأ بفهم النشاط وأهدافه التجارية وتحديد المسؤوليات والصلاحيات ونقطة البداية، ثم تتحول البيانات إلى خريطة كلمات وخطة تنفيذ مرتبة حسب الأولوية.</p>
  </div>
  <div class="sh">
    <ol class="ss-timeline" aria-label="مراحل تأسيس المشروع">

      <li class="ss-phase">
        <div class="ss-phase-num" aria-hidden="true">01</div>
        <div class="ss-phase-body">
          <h3 class="ss-phase-title">الـOnboarding والاجتماع الأول</h3>
          <p>نرسل نموذجًا لجمع المعلومات الأساسية عن النشاط والخدمات أو المنتجات ذات الأولوية والأسواق المستهدفة والمنافسين والعملاء المثاليين وأهداف المشروع.</p>
          <p>بعد ذلك نعقد اجتماعًا لتأكيد نطاق العمل، وتحديد المسؤولين عن الموافقات والتنفيذ، والاتفاق على قنوات التواصل ومواعيد المتابعة.</p>
        </div>
      </li>

      <li class="ss-phase">
        <div class="ss-phase-num" aria-hidden="true">02</div>
        <div class="ss-phase-body">
          <h3 class="ss-phase-title">استلام الصلاحيات وضبط القياس</h3>
          <p>نراجع صلاحيات الموقع وGoogle Search Console وGA4 وGoogle Tag Manager، بالإضافة إلى Google Business Profile أو Merchant Center عندما يكون ذلك مناسبًا للمشروع.</p>
          <p>نتأكد أيضًا من قياس الإجراءات المهمة مثل إرسال النماذج واتصالات الهاتف وضغطات واتساب والطلبات والمبيعات، حتى نقيس أثر SEO على النشاط وليس الزيارات فقط.</p>
        </div>
      </li>

      <li class="ss-phase">
        <div class="ss-phase-num" aria-hidden="true">03</div>
        <div class="ss-phase-body">
          <h3 class="ss-phase-title">تحليل الوضع الحالي والفرص</h3>
          <p>نفحص الجوانب التقنية والفهرسة وبنية الموقع والمحتوى الحالي والكلمات التي يظهر بها الموقع وأداء المنافسين والصفحات التي تمتلك فرصة حقيقية للنمو.</p>
          <p>نسجل نقطة البداية قبل تنفيذ التعديلات، بحيث يمكن مقارنة النتائج اللاحقة بأرقام واضحة وموثقة.</p>
        </div>
      </li>

      <li class="ss-phase">
        <div class="ss-phase-num" aria-hidden="true">04</div>
        <div class="ss-phase-body">
          <h3 class="ss-phase-title">خريطة الكلمات وخطة أول 90 يومًا</h3>
          <p>نربط مجموعات الكلمات بالصفحات المناسبة، ونحدد الصفحات التي تحتاج إلى تحسين والصفحات الجديدة المطلوبة وفرص المحتوى والربط الداخلي.</p>
          <p>بعد ذلك نحوّل النتائج إلى خطة لأول 90 يومًا مرتبة حسب الأولوية والتأثير المتوقع، مع توضيح ما سينفذه فريق SEO House وما يحتاج إلى موافقة أو تعاون من فريق العميل.</p>
        </div>
      </li>

    </ol>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     7. MONTHLY CYCLE (dark section)
═══════════════════════════════════════════════════════ -->
<section class="sec-navy ss-cycle-section">
  <div class="sh c sr">
    <span class="tag tag-light">عمل مستمر، لا قائمة ثابتة</span>
    <h2 class="h2 h2-light">كل شهر يبدأ من البيانات وينتهي بخطة أوضح للشهر التالي</h2>
  </div>
  <div class="sh">
    <ol class="ss-cycle-grid" aria-label="دورة التنفيذ الشهرية">

      <li class="ss-cycle-step">
        <span class="ss-cycle-num" aria-hidden="true">01</span>
        <h3 class="ss-cycle-title">تحديد أولويات الشهر</h3>
        <p>نراجع بيانات الأداء وخطة المشروع ونحدد المهام الأعلى تأثيرًا، بدل تنفيذ قائمة ثابتة لا تراعي تغير الموقع أو السوق.</p>
      </li>

      <li class="ss-cycle-step">
        <span class="ss-cycle-num" aria-hidden="true">02</span>
        <h3 class="ss-cycle-title">التنفيذ والتنسيق</h3>
        <p>يبدأ فريق المحتوى في تجهيز الصفحات المعتمدة، بينما يحوّل فريق SEO المشكلات التقنية إلى مهام واضحة وقابلة للتنفيذ.</p>
      </li>

      <li class="ss-cycle-step">
        <span class="ss-cycle-num" aria-hidden="true">03</span>
        <h3 class="ss-cycle-title">المراجعة والـQA والمتابعة</h3>
        <p>نراجع الصفحات والتعديلات بعد التنفيذ، ونتأكد من الفهرسة والتتبع وعمل الصفحات على الديسكتوب والموبايل.</p>
      </li>

      <li class="ss-cycle-step">
        <span class="ss-cycle-num" aria-hidden="true">04</span>
        <h3 class="ss-cycle-title">المراجعة الشهرية وخطة الشهر التالي</h3>
        <p>يحصل العميل على ملخص واضح لما تم تنفيذه وما تغير في الأداء والعوائق، ثم نحدد أولويات الشهر التالي.</p>
      </li>

    </ol>
    <blockquote class="ss-cycle-quote">
      تعرف دائمًا ما الذي نعمل عليه، وما الذي تم تنفيذه، وما الذي تغير، وما الخطوة التالية.
    </blockquote>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     8. DELIVERABLES
═══════════════════════════════════════════════════════ -->
<section class="sec-off ss-deliverables-section">
  <div class="sh c sr">
    <span class="tag">تسليمات واضحة</span>
    <h2 class="h2">لا نرسل توصيات عامة ونتركك أمام ملف طويل</h2>
    <p class="bod">كل تحليل يتحول إلى مرجع أو مهمة أو خطة قابلة للتنفيذ، مع مسؤول واضح وحالة متابعة.</p>
  </div>

  <div class="sh ss-deliverables">

    <!-- 01 Keyword map -->
    <div class="ss-deliverable">
      <div class="ss-deliv-content">
        <span class="ss-deliv-num" aria-hidden="true">01</span>
        <h3 class="ss-deliv-h3">خريطة الكلمات والصفحات</h3>
        <p class="ss-deliv-p">لا نقدم قائمة طويلة من الكلمات المفتاحية فقط؛ بل نربط كل مجموعة كلمات بالصفحة المناسبة، ونحدد نية البحث والصفحات الحالية التي تحتاج إلى تطوير والصفحات الجديدة المطلوبة.</p>
        <p class="ss-deliv-p">تتحول خريطة الكلمات إلى مرجع للمحتوى وتحسين الصفحات والربط الداخلي، وتساعد على منع استهداف الكلمة نفسها بأكثر من صفحة.</p>
      </div>
      <div class="ss-deliv-preview" aria-hidden="true">
        <div class="ss-deliv-preview-inner">
          <div class="ss-kw-row">
            <span class="ss-kw-pill ss-kw-pill--blue">خدمات SEO</span>
            <span class="ss-kw-pill">تحسين محركات البحث</span>
            <span class="ss-kw-pill">شركة سيو</span>
          </div>
          <div class="ss-kw-row">
            <span class="ss-kw-pill">سيو محلي</span>
            <span class="ss-kw-pill ss-kw-pill--blue">Local SEO</span>
            <span class="ss-kw-pill">Google Business</span>
          </div>
          <div class="ss-kw-row">
            <span class="ss-kw-pill ss-kw-pill--blue">SEO تقني</span>
            <span class="ss-kw-pill">Technical SEO</span>
          </div>
          <p class="ss-deliv-preview-note">نموذج مبسّط — التفاصيل داخل ملف العمل</p>
        </div>
      </div>
    </div>

    <!-- 02 Tech audit -->
    <div class="ss-deliverable ss-deliverable--alt">
      <div class="ss-deliv-content">
        <span class="ss-deliv-num" aria-hidden="true">02</span>
        <h3 class="ss-deliv-h3">المراجعة التقنية وخطة التنفيذ</h3>
        <p class="ss-deliv-p">نفحص الفهرسة والزحف وسرعة الموقع وبنية الروابط والصفحات المكررة والتحويلات والـCanonical والمشكلات التي قد تعيق الظهور أو تؤثر في تجربة المستخدم.</p>
        <p class="ss-deliv-p">لا يتوقف العمل عند اكتشاف المشكلة؛ نحولها إلى مهام تنفيذية مرتبة حسب الأولوية والتأثير، ثم ننفذها أو نتابع تنفيذها ونراجع النتيجة.</p>
      </div>
      <div class="ss-deliv-preview" aria-hidden="true">
        <div class="ss-deliv-preview-inner">
          <div class="ss-audit-item">
            <span class="ss-audit-dot ss-audit-dot--high"></span>
            <div>
              <span class="ss-audit-label">Canonical مكررة</span>
              <div class="ss-audit-bar" style="width:78%"></div>
            </div>
          </div>
          <div class="ss-audit-item">
            <span class="ss-audit-dot ss-audit-dot--med"></span>
            <div>
              <span class="ss-audit-label">Core Web Vitals</span>
              <div class="ss-audit-bar" style="width:52%"></div>
            </div>
          </div>
          <div class="ss-audit-item">
            <span class="ss-audit-dot ss-audit-dot--low"></span>
            <div>
              <span class="ss-audit-label">روابط معطوبة</span>
              <div class="ss-audit-bar" style="width:30%"></div>
            </div>
          </div>
          <p class="ss-deliv-preview-note">تمثيل بصري — الأولوية من الأعلى للأدنى</p>
        </div>
      </div>
    </div>

    <!-- 03 Content plan -->
    <div class="ss-deliverable">
      <div class="ss-deliv-content">
        <span class="ss-deliv-num" aria-hidden="true">03</span>
        <h3 class="ss-deliv-h3">خطة المحتوى</h3>
        <p class="ss-deliv-p">نحدد الصفحات والموضوعات التي يحتاج إليها الموقع بناءً على البحث الفعلي وفجوات المنافسين ومراحل رحلة العميل، وليس بهدف نشر عدد ثابت من المقالات كل شهر.</p>
        <p class="ss-deliv-p">توضح الخطة الكلمة المستهدفة وهدف الصفحة ونوع المحتوى والأولوية وحالة الموافقة والكتابة والمراجعة والنشر.</p>
      </div>
      <div class="ss-deliv-preview" aria-hidden="true">
        <div class="ss-deliv-preview-inner">
          <div class="ss-plan-row">
            <span class="ss-plan-title">صفحة خدمة</span>
            <span class="ss-plan-status ss-plan-status--done">منشورة</span>
          </div>
          <div class="ss-plan-row">
            <span class="ss-plan-title">مقالة مقارنة</span>
            <span class="ss-plan-status ss-plan-status--prog">قيد الكتابة</span>
          </div>
          <div class="ss-plan-row">
            <span class="ss-plan-title">صفحة محلية</span>
            <span class="ss-plan-status ss-plan-status--wait">في الانتظار</span>
          </div>
          <p class="ss-deliv-preview-note">نموذج مبسّط — الخطة الكاملة داخل ملف العمل</p>
        </div>
      </div>
    </div>

    <!-- 04 Dashboard -->
    <div class="ss-deliverable ss-deliverable--alt">
      <div class="ss-deliv-content">
        <span class="ss-deliv-num" aria-hidden="true">04</span>
        <h3 class="ss-deliv-h3">لوحة متابعة لحظية</h3>
        <p class="ss-deliv-p">يحصل العميل على لوحة Looker Studio محدثة باستمرار تعرض مؤشرات الأداء المهمة من Search Console وGA4 ومصادر القياس المرتبطة بالمشروع.</p>
        <p class="ss-deliv-p">نركز داخل اللوحة على ما يهم النشاط: الظهور والنقرات والكلمات والصفحات والتحويلات والطلبات أو الإيرادات.</p>
      </div>
      <div class="ss-deliv-preview" aria-hidden="true">
        <div class="ss-deliv-preview-inner">
          <div class="ss-dash-metrics">
            <div class="ss-dash-metric">
              <span class="ss-dash-metric-label">النقرات</span>
              <span class="ss-dash-metric-val">—</span>
            </div>
            <div class="ss-dash-metric">
              <span class="ss-dash-metric-label">الظهور</span>
              <span class="ss-dash-metric-val">—</span>
            </div>
            <div class="ss-dash-metric">
              <span class="ss-dash-metric-label">التحويلات</span>
              <span class="ss-dash-metric-val">—</span>
            </div>
          </div>
          <div class="ss-dash-bar-wrap">
            <div class="ss-dash-bar" style="height:30%"></div>
            <div class="ss-dash-bar" style="height:55%"></div>
            <div class="ss-dash-bar" style="height:45%"></div>
            <div class="ss-dash-bar" style="height:70%"></div>
            <div class="ss-dash-bar" style="height:60%"></div>
            <div class="ss-dash-bar" style="height:80%"></div>
          </div>
          <p class="ss-deliv-preview-note">تمثيل بصري للوحة — البيانات من GA4 و Search Console</p>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     9. SCOPE ACCORDION
═══════════════════════════════════════════════════════ -->
<section class="sec-white ss-scope-section">
  <div class="sh c sr">
    <h2 class="h2">ما الذي يمكن أن يشمله العمل؟</h2>
    <p class="bod">نحدد نطاق العمل بعد مراجعة الموقع وحجم المنافسة والموارد المتاحة، لأن متجرًا يضم مئات المنتجات لا يحتاج إلى الخطة نفسها التي يحتاج إليها موقع خدمات أو منصة تعمل في أكثر من سوق.</p>
  </div>
  <div class="sh">
    <ul class="ss-scope-list" id="ssScopeList" aria-label="محاور خدمة SEO">

      <li class="ss-scope-item">
        <button type="button" class="ss-scope-btn" aria-expanded="false" aria-controls="ssScope1">
          <span class="ss-scope-num" aria-hidden="true">01</span>
          <span class="ss-scope-title">Technical SEO</span>
          <svg class="ss-scope-icon" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M5 7l4 4 4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div id="ssScope1" class="ss-scope-body" role="region">
          <p class="ss-scope-inner">الزحف والفهرسة والسرعة وبنية الموقع والتحويلات والـCanonical والمشكلات التقنية المؤثرة — تتحول كل مشكلة إلى مهمة واضحة مرتبة حسب أولويتها وتأثيرها.</p>
        </div>
      </li>

      <li class="ss-scope-item">
        <button type="button" class="ss-scope-btn" aria-expanded="false" aria-controls="ssScope2">
          <span class="ss-scope-num" aria-hidden="true">02</span>
          <span class="ss-scope-title">Keyword &amp; Page Strategy</span>
          <svg class="ss-scope-icon" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M5 7l4 4 4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div id="ssScope2" class="ss-scope-body" role="region">
          <p class="ss-scope-inner">خريطة الكلمات، نية البحث، هيكلة الصفحات ومنع التنافس بين صفحات الموقع — بحيث تستهدف كل صفحة مجموعتها الخاصة دون تداخل.</p>
        </div>
      </li>

      <li class="ss-scope-item">
        <button type="button" class="ss-scope-btn" aria-expanded="false" aria-controls="ssScope3">
          <span class="ss-scope-num" aria-hidden="true">03</span>
          <span class="ss-scope-title">On-Page SEO</span>
          <svg class="ss-scope-icon" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M5 7l4 4 4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div id="ssScope3" class="ss-scope-body" role="region">
          <p class="ss-scope-inner">العناوين والوصف وH1 والمحتوى والروابط الداخلية وتحسين الصفحات ذات الأولوية — التحسين على مستوى الصفحة يُسرّع الظهور أمام الكلمات الأقرب لنية البحث.</p>
        </div>
      </li>

      <li class="ss-scope-item">
        <button type="button" class="ss-scope-btn" aria-expanded="false" aria-controls="ssScope4">
          <span class="ss-scope-num" aria-hidden="true">04</span>
          <span class="ss-scope-title">Content Strategy</span>
          <svg class="ss-scope-icon" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M5 7l4 4 4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div id="ssScope4" class="ss-scope-body" role="region">
          <p class="ss-scope-inner">صفحات الخدمات والأقسام والمقالات وتحديث المحتوى القائم وفق فرص البحث ورحلة العميل — الموضوعات تُختار وفق الكلمات والفرص ذات الأولوية، لا بهدف رقم ثابت.</p>
        </div>
      </li>

      <li class="ss-scope-item">
        <button type="button" class="ss-scope-btn" aria-expanded="false" aria-controls="ssScope5">
          <span class="ss-scope-num" aria-hidden="true">05</span>
          <span class="ss-scope-title">Authority &amp; Off-Page</span>
          <svg class="ss-scope-icon" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M5 7l4 4 4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div id="ssScope5" class="ss-scope-body" role="region">
          <p class="ss-scope-inner">تحليل الروابط والفرص المناسبة وبناء الإشارات الخارجية وفق نطاق متفق عليه، من دون وعود بعدد عشوائي من الروابط — الجودة والصلة بالموضوع أهم من الكمية.</p>
        </div>
      </li>

      <li class="ss-scope-item">
        <button type="button" class="ss-scope-btn" aria-expanded="false" aria-controls="ssScope6">
          <span class="ss-scope-num" aria-hidden="true">06</span>
          <span class="ss-scope-title">Measurement &amp; Reporting</span>
          <svg class="ss-scope-icon" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M5 7l4 4 4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div id="ssScope6" class="ss-scope-body" role="region">
          <p class="ss-scope-inner">إعداد وطرح مؤشرات القياس، تتبع التحويلات، لوحة الأداء والمراجعة الشهرية — القياس يربط العمل بالنتائج ويُحدد الأولويات للشهر التالي.</p>
        </div>
      </li>

    </ul>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     10. REPORTING — حضور الشركة الحقيقي
═══════════════════════════════════════════════════════ -->
<section class="sec-surface ss-report">
  <div class="sh">
    <div class="ss-report-grid">

      <!-- Photo -->
      <div class="ss-report-photo-col">
        <figure class="ss-report-photo">
          <img
            src="<?php echo esc_url( $img . '/office-laptop-dashboard.png' ); ?>"
            alt="فريق SEO House يعمل على لوحة Looker Studio"
            width="640"
            height="480"
            loading="lazy"
          >
        </figure>
      </div>

      <!-- Content -->
      <div class="ss-report-content">
        <span class="tag">متابعة مستمرة</span>
        <h2 class="h2 ss-report-h">ترى الأرقام وقتما تحتاج، ونراجع معناها معك كل شهر</h2>
        <p>لا ننتظر نهاية الشهر لنرسل ملف PDF ثابتًا. يحصل العميل على لوحة Looker Studio محدثة باستمرار، تعرض البيانات الأهم وفق طبيعة مشروعه ومصادر القياس المتاحة.</p>
        <p>وفي المراجعة الشهرية لا نكتفي بقراءة الأرقام؛ نوضح ما تم تنفيذه، وما الذي تغير، وما الذي لم يتحرك بعد، وأين توجد الفرصة أو العائق الذي سيؤثر في خطة الشهر التالي.</p>
        <ul class="ss-report-bullets" aria-label="ما يتضمنه التقرير">
          <li class="ss-report-bullet">
            <span class="ss-report-dot" aria-hidden="true"></span>
            بيانات من Search Console وGA4 والمصادر المرتبطة بالمشروع
          </li>
          <li class="ss-report-bullet">
            <span class="ss-report-dot" aria-hidden="true"></span>
            متابعة للكلمات والصفحات والتحويلات والإيرادات عند توافرها
          </li>
          <li class="ss-report-bullet">
            <span class="ss-report-dot" aria-hidden="true"></span>
            ملخص تنفيذ واضح وأولويات للشهر التالي
          </li>
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
<section class="sec-white ss-reviews">
  <div class="sh c sr">
    <span class="tag">تجربة العمل معنا</span>
    <h2 class="h2">ماذا يقول عملاؤنا عن SEO House؟</h2>
  </div>
  <div class="sh">
    <?php echo do_shortcode( $reviews_sc ); ?>
  </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════
     12. FAQ
═══════════════════════════════════════════════════════ -->
<section class="sec-off ss-faq-section">
  <div class="sh">
    <div class="sh c sr" style="margin-bottom:clamp(28px,4vw,48px)">
      <h2 class="h2">الأسئلة الشائعة</h2>
    </div>

    <div class="faq-list" id="svcSeoFaq" role="list">

      <div class="faq-item" role="listitem">
        <button type="button" class="faq-q" aria-expanded="false" aria-controls="svcSeoFaqA-1">
          متى تبدأ نتائج SEO في الظهور؟
          <svg class="faq-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M5 8l5 5 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div id="svcSeoFaqA-1" class="faq-a" role="region" aria-label="الإجابة">
          <p>تختلف المدة حسب حالة الموقع والمنافسة وسرعة تنفيذ التعديلات. قد تظهر مؤشرات أولية بعد معالجة مشكلات واضحة أو تحسين صفحات تمتلك فرصة قائمة، بينما يحتاج بناء نمو مستقر عادةً إلى عمل متراكم ومتابعة مستمرة.</p>
          <p>قبل بدء المشروع نسجل نقطة البداية ونحدد مؤشرات القياس، ثم نراجع التغير شهريًا بدل تقديم وعد بزمن أو ترتيب لا يمكن ضمانه.</p>
        </div>
      </div>

      <div class="faq-item" role="listitem">
        <button type="button" class="faq-q" aria-expanded="false" aria-controls="svcSeoFaqA-2">
          هل تنفذون التعديلات التقنية أم ترسلون التوصيات فقط؟
          <svg class="faq-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M5 8l5 5 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div id="svcSeoFaqA-2" class="faq-a" role="region" aria-label="الإجابة">
          <p>نحوّل المشكلات إلى مهام واضحة مرتبة حسب الأولوية. وبحسب نطاق الاتفاق، يمكن تنفيذ التعديلات من خلال مطور SEO House أو التنسيق مع مطور العميل ومراجعة ما تم تنفيذه.</p>
          <p>لا نعتبر المهمة منتهية بمجرد إرسال الملاحظة؛ نتابع التنفيذ ونراجع النتيجة على الموقع.</p>
        </div>
      </div>

      <div class="faq-item" role="listitem">
        <button type="button" class="faq-q" aria-expanded="false" aria-controls="svcSeoFaqA-3">
          هل يحصل العميل على تقرير شهري؟
          <svg class="faq-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M5 8l5 5 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div id="svcSeoFaqA-3" class="faq-a" role="region" aria-label="الإجابة">
          <p>يحصل العميل على لوحة Looker Studio محدثة باستمرار، إلى جانب مراجعة شهرية توضّح ما تم تنفيذه وما تغير في الأداء والعوائق وخطة الشهر التالي.</p>
          <p>الهدف ليس إرسال ملف ممتلئ بالأرقام، بل ربط البيانات بما تم عمله والقرار التالي داخل المشروع.</p>
        </div>
      </div>

      <div class="faq-item" role="listitem">
        <button type="button" class="faq-q" aria-expanded="false" aria-controls="svcSeoFaqA-4">
          هل تشمل الخدمة كتابة المحتوى؟
          <svg class="faq-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M5 8l5 5 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div id="svcSeoFaqA-4" class="faq-a" role="region" aria-label="الإجابة">
          <p>يمكن أن يشمل نطاق المشروع تخطيط المحتوى وكتابته ومراجعته وتحسينه ونشره، أو التنسيق مع فريق المحتوى لدى العميل، وفق ما يتم الاتفاق عليه قبل بدء العمل.</p>
          <p>لا نحدد موضوعات المحتوى لمجرد الوصول إلى عدد ثابت من المقالات؛ نختارها وفق الكلمات والصفحات والفرص ذات الأولوية.</p>
        </div>
      </div>

      <div class="faq-item" role="listitem">
        <button type="button" class="faq-q" aria-expanded="false" aria-controls="svcSeoFaqA-5">
          هل تضمنون الوصول إلى المركز الأول؟
          <svg class="faq-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M5 8l5 5 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div id="svcSeoFaqA-5" class="faq-a" role="region" aria-label="الإجابة">
          <p>لا توجد شركة يمكنها ضمان ترتيب محدد داخل نتائج Google. ما يمكننا الالتزام به هو تحليل واضح وتنفيذ منظم وقياس مستمر وشفافية في عرض ما تحقق وما يحتاج إلى وقت أو قرار.</p>
          <p>نقيّم النجاح وفق مؤشرات تناسب المشروع، مثل الظهور والنقرات والكلمات المؤثرة والعملاء المحتملين والطلبات والإيرادات عند إمكان قياسها.</p>
        </div>
      </div>

      <div class="faq-item" role="listitem">
        <button type="button" class="faq-q" aria-expanded="false" aria-controls="svcSeoFaqA-6">
          كم تبلغ تكلفة خدمة SEO؟
          <svg class="faq-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M5 8l5 5 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div id="svcSeoFaqA-6" class="faq-a" role="region" aria-label="الإجابة">
          <p>تتراوح أغلب مشاريع SEO House بين <strong>1,500</strong> و<strong>7,000 ريال شهريًا</strong>، وفق حجم الموقع والسوق المستهدف ومستوى المنافسة وحجم المحتوى والدعم التقني المطلوب.</p>
          <p>بعد مراجعة الموقع نحدد نطاق العمل والأولويات والتكلفة المناسبة بوضوح، بدل وضع باقة ثابتة لا تناسب احتياج المشروع.
          <?php if ( $pricing_url ) : ?>
            <a href="<?php echo esc_url( $pricing_url ); ?>" class="ss-faq-link">تفاصيل التكلفة والنطاق</a>
          <?php endif; ?>
          </p>
        </div>
      </div>

    </div><!-- /faq-list -->
  </div><!-- /sh -->
</section>

<!-- ═══════════════════════════════════════════════════════
     13. CTA + INLINE FORM
═══════════════════════════════════════════════════════ -->
<section class="sec-navy ss-cta-section" id="seoSvcFormWrap">
  <div class="sh">
    <div class="ss-form-grid">

      <!-- Intro -->
      <div class="ss-form-intro">
        <h2 class="h2 h2-light">لنبدأ بمراجعة موقعك وتحديد الفرصة الأقرب</h2>
        <p class="ss-form-intro-p">أرسل لنا رابط الموقع والخدمة أو السوق الذي تريد التركيز عليه. سنراجع الوضع الحالي ونوضح لك أين توجد الأولويات وما نطاق العمل المناسب قبل بدء المشروع.</p>
        <p class="ss-form-intro-p">لا نرسل عرضًا عامًا قبل فهم الموقع؛ لأن حجم الصفحات والمنافسة وسرعة التنفيذ المطلوبة هي ما تحدد الخطة والتكلفة.</p>
        <?php
        $wa_num = sh_option( 'whatsapp_number' );
        if ( $wa_num ) :
          $wa_clean = preg_replace( '/\D/', '', $wa_num );
        ?>
        <a
          href="https://wa.me/<?php echo esc_attr( $wa_clean ); ?>?text=<?php echo rawurlencode( 'مرحبًا، أريد مراجعة موقعي' ); ?>"
          class="ss-wa-btn"
          target="_blank"
          rel="noopener noreferrer"
        >
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.532 5.862L.057 23.515a.5.5 0 00.614.618l5.77-1.514A11.93 11.93 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.8 9.8 0 01-5.007-1.374l-.36-.213-3.727.977.997-3.642-.234-.374A9.789 9.789 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/></svg>
          تواصل معنا عبر واتساب
        </a>
        <?php endif; ?>
      </div>

      <!-- Form -->
      <div class="ss-form-col">
        <div id="seoSvcWrap">
          <form id="seoSvcForm" class="sh-form" novalidate>
            <input type="hidden" id="seoSvcAjaxUrl" value="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
            <input type="hidden" id="seoSvcNonce"   value="<?php echo esc_attr( wp_create_nonce( 'seohouse_nonce' ) ); ?>">

            <div class="form-row">
              <label for="seoSvcName" class="form-label">الاسم <span class="req" aria-hidden="true">*</span></label>
              <input type="text" id="seoSvcName" name="name" class="form-input" required autocomplete="name" placeholder="اسمك الكريم">
            </div>

            <div class="form-row">
              <label for="seoSvcCompany" class="form-label">اسم الشركة</label>
              <input type="text" id="seoSvcCompany" name="company" class="form-input" autocomplete="organization" placeholder="اسم شركتك أو نشاطك">
            </div>

            <div class="form-row">
              <label for="seoSvcPhone" class="form-label">رقم الهاتف أو واتساب <span class="req" aria-hidden="true">*</span></label>
              <input type="tel" id="seoSvcPhone" name="phone" class="form-input" required autocomplete="tel" placeholder="+966 5x xxx xxxx" dir="ltr">
            </div>

            <div class="form-row">
              <label for="seoSvcEmail" class="form-label">البريد الإلكتروني</label>
              <input type="email" id="seoSvcEmail" name="email" class="form-input" autocomplete="email" placeholder="email@example.com" dir="ltr">
            </div>

            <div class="form-row">
              <label for="seoSvcSite" class="form-label">رابط الموقع <span class="req" aria-hidden="true">*</span></label>
              <input type="url" id="seoSvcSite" name="website" class="form-input" required autocomplete="url" placeholder="https://yoursite.com" dir="ltr">
            </div>

            <div class="form-row">
              <label for="seoSvcMarket" class="form-label">السوق المستهدف</label>
              <input type="text" id="seoSvcMarket" name="target_market" class="form-input" placeholder="مثال: المملكة العربية السعودية — قطاع العقارات">
            </div>

            <div class="form-row">
              <label for="seoSvcMsg" class="form-label">الهدف الأساسي أو رسالة مختصرة</label>
              <textarea id="seoSvcMsg" name="message" class="form-input" rows="3" placeholder="ما الذي تريد تحسينه أو ما التحدي الأساسي الذي تواجهه؟"></textarea>
            </div>

            <div id="seoSvcError" class="form-error" aria-live="polite" hidden></div>

            <button type="submit" class="btn btn-primary btn-full" id="seoSvcSubmit">
              <span class="btn-label">اطلب مراجعة موقعك</span>
              <span class="btn-spinner" aria-hidden="true" hidden></span>
            </button>
          </form>
        </div>

        <div id="seoSvcSuccess" class="form-success" hidden role="alert">
          <svg width="40" height="40" viewBox="0 0 40 40" fill="none" aria-hidden="true"><circle cx="20" cy="20" r="19" stroke="var(--blue)" stroke-width="2"/><path d="M12 20l6 6 10-12" stroke="var(--blue)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          <p class="form-success-title">تم الإرسال بنجاح</p>
          <p class="form-success-msg">سنراجع موقعك ونتواصل معك خلال يوم عمل.</p>
        </div>
      </div>

    </div>
  </div>
</section>

</div><!-- /svc-seo -->

<script>
(function () {
  // ── Scope accordion ──────────────────────────────────────────────────
  var list = document.getElementById('ssScopeList');
  if (list) {
    list.addEventListener('click', function (e) {
      var btn = e.target.closest('.ss-scope-btn');
      if (!btn) return;
      var item   = btn.closest('.ss-scope-item');
      var isOpen = item.classList.contains('open');
      // close all
      list.querySelectorAll('.ss-scope-item.open').forEach(function (el) {
        el.classList.remove('open');
        el.querySelector('.ss-scope-btn').setAttribute('aria-expanded', 'false');
      });
      if (!isOpen) {
        item.classList.add('open');
        btn.setAttribute('aria-expanded', 'true');
      }
    });
  }

  // ── FAQ aria-expanded sync (main.js toggles .open on .faq-item) ─────
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

  // ── Contact form ─────────────────────────────────────────────────────
  var form    = document.getElementById('seoSvcForm');
  var wrap    = document.getElementById('seoSvcWrap');
  var success = document.getElementById('seoSvcSuccess');
  var errBox  = document.getElementById('seoSvcError');
  var submit  = document.getElementById('seoSvcSubmit');

  if (!form) return;

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    errBox.hidden = true;

    var ajaxUrl = document.getElementById('seoSvcAjaxUrl').value;
    var nonce   = document.getElementById('seoSvcNonce').value;

    var company = (form.querySelector('#seoSvcCompany').value || '').trim();
    var market  = (form.querySelector('#seoSvcMarket').value  || '').trim();
    var baseMsg = (form.querySelector('#seoSvcMsg').value     || '').trim();

    var prefixParts = [];
    if (company) prefixParts.push('الشركة: ' + company);
    if (market)  prefixParts.push('السوق المستهدف: ' + market);
    var fullMsg = prefixParts.length
      ? prefixParts.join(' | ') + (baseMsg ? '\n\n' + baseMsg : '')
      : baseMsg;

    var data = new FormData();
    data.append('action',  'sh_contact');
    data.append('nonce',   nonce);
    data.append('name',    form.querySelector('#seoSvcName').value.trim());
    data.append('phone',   form.querySelector('#seoSvcPhone').value.trim());
    data.append('email',   form.querySelector('#seoSvcEmail').value.trim());
    data.append('website', form.querySelector('#seoSvcSite').value.trim());
    data.append('message', fullMsg);
    data.append('source',  'seo-service');

    var label   = submit.querySelector('.btn-label');
    var spinner = submit.querySelector('.btn-spinner');
    submit.disabled = true;
    if (label)   label.hidden   = true;
    if (spinner) spinner.hidden = false;

    fetch(ajaxUrl, { method: 'POST', body: data })
      .then(function (r) { return r.json(); })
      .then(function (json) {
        if (json && json.success) {
          wrap.hidden    = true;
          success.hidden = false;
        } else {
          var msg = (json && json.data) ? json.data : 'حدث خطأ، يرجى المحاولة مرة أخرى.';
          errBox.textContent = msg;
          errBox.hidden = false;
          submit.disabled = false;
          if (label)   label.hidden   = false;
          if (spinner) spinner.hidden = true;
        }
      })
      .catch(function () {
        errBox.textContent = 'حدث خطأ في الاتصال، يرجى المحاولة مرة أخرى.';
        errBox.hidden = false;
        submit.disabled = false;
        if (label)   label.hidden   = false;
        if (spinner) spinner.hidden = true;
      });
  });
}());
</script>

<?php get_footer(); ?>
