<?php
/**
 * Template Name: SEO Pricing V2
 *
 * Temporary review page — /seo-pricing-v2/
 * noindex enforced until promoted to main navigation.
 * Do NOT add to nav menus or sitemap until approved.
 */
defined( 'ABSPATH' ) || exit;

// ── noindex (review phase) ────────────────────────────────────────
// WordPress native (WP 5.7+, theme requires WP 6.0)
add_filter( 'wp_robots', function ( $robots ) {
    $robots['noindex'] = true;
    return $robots;
} );
// Rank Math compatibility
add_filter( 'rank_math/frontend/robots', function ( $robots ) {
    $robots['index'] = 'noindex';
    return $robots;
} );
// Yoast SEO compatibility
add_filter( 'wpseo_robots', fn() => 'noindex, follow' );

// ── Meta description (only when no SEO plugin handles it) ─────────
add_action( 'wp_head', function () {
    if ( ! class_exists( 'WPSEO_Frontend' ) && ! class_exists( 'RankMath' ) && ! class_exists( 'The_SEO_Framework_Loader' ) ) {
        echo '<meta name="description" content="تعرف على أسعار خدمات SEO في السعودية. تتراوح تكلفة معظم المشاريع في SEO House عادةً من 1,500 إلى 7,000 ريال شهريًا حسب حجم الموقع والمنافسة ونطاق العمل.">' . "\n";
    }
}, 2 );

// ── FAQ schema (JSON-LD) ──────────────────────────────────────────
add_action( 'wp_head', function () {
    $faqs = [
        [
            'q' => 'لماذا لا تقدم SEO House باقات SEO ثابتة؟',
            'a' => 'لأن حجم العمل المطلوب يختلف من موقع إلى آخر. نفضل تحديد نطاق العمل حسب الموقع والمنافسة والأهداف بدل إجبار جميع العملاء على الباقة نفسها.',
        ],
        [
            'q' => 'كم تبدأ أسعار خدمات SEO؟',
            'a' => 'تتراوح معظم المشاريع عادةً بين 1,500 و7,000 ريال شهريًا حسب حجم الموقع والمنافسة ونطاق التنفيذ المطلوب. وقد تحتاج المشاريع الكبيرة أو شديدة التعقيد إلى تسعير مخصص.',
        ],
        [
            'q' => 'كيف أعرف السعر المناسب لموقعي؟',
            'a' => 'نراجع الموقع والمنافسين وفرص البحث أولًا، ثم نحدد نطاق العمل والسعر الشهري المناسب قبل بدء المشروع.',
        ],
        [
            'q' => 'هل السعر يختلف للمتاجر الإلكترونية؟',
            'a' => 'نعم. عدد المنتجات والتصنيفات وحجم المتجر والمشاكل التقنية والمنافسة عوامل تؤثر في حجم العمل المطلوب.',
        ],
        [
            'q' => 'هل يشمل العمل حل المشاكل التقنية؟',
            'a' => 'لدينا دعم تقني ومطور يعمل مع فريق SEO لتنفيذ الحلول المطلوبة ضمن نطاق المشروع المتفق عليه. أما أعمال التطوير الكبيرة أو الخارجة عن النطاق، فيتم الاتفاق عليها بصورة منفصلة.',
        ],
        [
            'q' => 'هل تكاليف نشر Guest Posts والروابط الخارجية ضمن السعر؟',
            'a' => 'نتولى البحث عن فرص الروابط وتجهيز استراتيجية Off-Page SEO، لكن رسوم النشر التي تفرضها المواقع الخارجية ليست ضمن الاشتراك الشهري. يتم عرض تكلفة كل فرصة على العميل والحصول على موافقته قبل النشر.',
        ],
    ];

    $schema_items = array_map( function ( $faq ) {
        return [
            '@type'          => 'Question',
            'name'           => $faq['q'],
            'acceptedAnswer' => [ '@type' => 'Answer', 'text' => $faq['a'] ],
        ];
    }, $faqs );

    $schema = [
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => $schema_items,
    ];

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}, 5 );

get_header();
?>

<div class="seo-pricing-v2">

<!-- ══════════════════════════════════════════════════════════════ -->
<!-- §1 Hero                                                         -->
<!-- ══════════════════════════════════════════════════════════════ -->
<section class="svc-hero">
  <div class="wrap">
    <div class="svc-hero-inner">

      <div class="breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">الرئيسية</a>
        <svg class="bc-sep" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        <span class="bc-current">أسعار SEO</span>
      </div>

      <div class="h-badge" style="margin-bottom:18px">
        <span class="h-bdot"></span>أسعار خدمات تحسين محركات البحث
      </div>

      <h1 class="svc-hero-h1">كم تكلفة SEO<br>لموقعك؟</h1>

      <p class="page-hero-p">
        لا يوجد سعر واحد يناسب جميع المواقع. تختلف تكلفة تحسين محركات البحث حسب حجم موقعك، وقوة المنافسة، والسوق المستهدف، وحجم العمل المطلوب لتحقيق أهدافك.
      </p>

      <!-- Price range visual -->
      <div class="spv2-range-wrap">
        <div class="spv2-range-label">نطاق الاستثمار الشهري المعتاد</div>
        <div class="spv2-range-bar" role="presentation" aria-hidden="true"></div>
        <div class="spv2-range-ends">
          <div class="spv2-range-end">
            <div class="spv2-range-amount">1,500</div>
            <div class="spv2-range-suffix">ريال شهريًا</div>
          </div>
          <div class="spv2-range-end end-max">
            <div class="spv2-range-amount">7,000</div>
            <div class="spv2-range-suffix">ريال شهريًا</div>
          </div>
        </div>
      </div>

      <p class="spv2-range-note">
        تتراوح تكلفة معظم مشاريع SEO لدينا عادةً بين 1,500 و7,000 ريال شهريًا، ويتم تحديد السعر المناسب بعد مراجعة الموقع والمنافسة والفرص المتاحة. أما المشاريع الكبيرة أو شديدة التعقيد، فيتم تقديم تسعير مخصص لها.
      </p>

      <div class="pbtns" style="margin-top:26px">
        <a href="#pricing-review" class="btn btn-p lg">احصل على تسعير لموقعك</a>
        <a href="#how-we-price" class="btn btn-g lg">كيف نحدد السعر؟</a>
      </div>

      <div class="spv2-trust-note">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        مراجعة أولية للموقع قبل تحديد نطاق العمل
      </div>

    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════════════════════════ -->
<!-- §2 لماذا تختلف تكلفة SEO                                       -->
<!-- ══════════════════════════════════════════════════════════════ -->
<section class="sec sec-white">
  <div class="wrap">

    <div class="sh c sr">
      <span class="tag">أسباب اختلاف التكلفة</span>
      <h2 class="h2">لماذا تختلف تكلفة SEO من موقع لآخر؟</h2>
      <p class="bod spv2-narrow">
        موقع شركة يحتوي على 20 صفحة لا يحتاج إلى حجم العمل نفسه المطلوب لمتجر يحتوي على آلاف المنتجات. لذلك نبني نطاق العمل والسعر حسب احتياجات المشروع الفعلية، وليس حسب باقة ثابتة.
      </p>
    </div>

    <div class="spv2-factor-grid">

      <div class="spv2-factor-card sr">
        <div class="spv2-factor-num">01</div>
        <h3>حجم الموقع</h3>
        <p>عدد الصفحات والخدمات والتصنيفات والمنتجات يؤثر مباشرة في حجم العمل المطلوب.</p>
      </div>

      <div class="spv2-factor-card sr d1">
        <div class="spv2-factor-num">02</div>
        <h3>قوة المنافسة</h3>
        <p>كلما كانت الكلمات والمنافسة أقوى، احتاج المشروع إلى استراتيجية وتنفيذ أكبر للوصول إلى نتائج متقدمة.</p>
      </div>

      <div class="spv2-factor-card sr d2">
        <div class="spv2-factor-num">03</div>
        <h3>السوق المستهدف</h3>
        <p>استهداف مدينة واحدة يختلف عن استهداف السعودية بالكامل أو المنافسة في أكثر من سوق.</p>
      </div>

      <div class="spv2-factor-card sr d1">
        <div class="spv2-factor-num">04</div>
        <h3>المحتوى المطلوب</h3>
        <p>نحدد حجم المحتوى حسب فرص البحث الفعلية، سواء كانت صفحات خدمات أو تصنيفات أو منتجات أو مقالات.</p>
      </div>

      <div class="spv2-factor-card sr d2">
        <div class="spv2-factor-num">05</div>
        <h3>حالة الموقع التقنية</h3>
        <p>بعض المواقع تحتاج إلى تحسينات محدودة، بينما تحتاج مواقع أخرى إلى تدخل تقني أوسع قبل أن تستطيع المنافسة.</p>
      </div>

      <div class="spv2-factor-card sr d3">
        <div class="spv2-factor-num">06</div>
        <h3>مستوى المنافسين</h3>
        <p>نحلل المواقع المتصدرة لتحديد حجم المحتوى والسلطة والروابط المطلوبة لمنافستها.</p>
      </div>

    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════════════════════════ -->
<!-- §3 أين يقع مشروعك على النطاق                                   -->
<!-- ══════════════════════════════════════════════════════════════ -->
<section class="sec sec-surface">
  <div class="wrap">

    <div class="sh c sr">
      <span class="tag">نطاق التسعير</span>
      <h2 class="h2">أين يقع مشروعك ضمن نطاق 1,500–7,000 ريال؟</h2>
    </div>

    <div class="spv2-scale-box sr d1">

      <div class="spv2-scale-ends" aria-hidden="true">
        <div class="spv2-scale-amount">1,500 ريال شهريًا</div>
        <div class="spv2-scale-ends-arrow">←</div>
        <div class="spv2-scale-amount">7,000 ريال شهريًا</div>
      </div>

      <div class="spv2-scale-track" role="presentation" aria-hidden="true">
        <div class="spv2-scale-fill"></div>
      </div>

      <div class="spv2-scale-markers" role="list" aria-label="أمثلة استرشادية على النطاق">
        <div class="spv2-scale-marker" role="listitem">
          <div class="spv2-scale-marker-label">مواقع صغيرة وأنشطة محلية</div>
          <div class="spv2-scale-marker-sub">موقع خدمات أو عيادة أو نشاط محلي بعدد صفحات محدود وسوق غير مكتظ</div>
        </div>
        <div class="spv2-scale-marker" role="listitem">
          <div class="spv2-scale-marker-label">شركات ومواقع تنافسية</div>
          <div class="spv2-scale-marker-sub">موقع شركة أو موقع في قطاع تنافسي يستهدف أسواقًا أوسع أو كلمات أقوى</div>
        </div>
        <div class="spv2-scale-marker" role="listitem">
          <div class="spv2-scale-marker-label">متاجر إلكترونية ومواقع كبيرة</div>
          <div class="spv2-scale-marker-sub">متجر بمئات المنتجات أو موقع كبير يتطلب عملًا تقنيًا ومحتوى موسعًا</div>
        </div>
      </div>

      <div class="spv2-scale-disclaimer" role="note">
        أمثلة استرشادية وليست باقات أو أسعارًا ثابتة
      </div>

    </div>

    <div class="sh c" style="margin-top:36px;margin-bottom:0">
      <p class="bod spv2-narrow" style="margin-bottom:8px">
        السعر لا يعتمد على نوع النشاط فقط. قد يحتاج موقع صغير في سوق شديد المنافسة إلى حجم عمل أكبر من موقع كبير في سوق أقل تنافسية.
      </p>
      <p class="bod spv2-narrow" style="color:var(--ink)">
        بعد مراجعة موقعك، نحدد حجم التنفيذ المطلوب ونقدم لك سعرًا شهريًا واضحًا قبل بدء العمل.
      </p>
    </div>

  </div>
</section>

<!-- ══════════════════════════════════════════════════════════════ -->
<!-- §4 ماذا تشمل خطة SEO                                           -->
<!-- ══════════════════════════════════════════════════════════════ -->
<section class="sec sec-white">
  <div class="wrap">

    <div class="sh c sr">
      <span class="tag">نطاق الخدمة</span>
      <h2 class="h2">ماذا يمكن أن تشمل خطة SEO الخاصة بك؟</h2>
      <p class="bod spv2-narrow">نحدد الخدمات ونطاق التنفيذ بناءً على ما يحتاجه موقعك فعليًا لتحقيق النمو، بدل إضافة أعمال لا يحتاج إليها المشروع.</p>
    </div>

    <div class="spv2-scope-groups">

      <!-- Group 1: Strategy -->
      <div class="spv2-scope-group sr">
        <div class="spv2-scope-group-head">
          <div class="spv2-scope-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
          </div>
          <div class="spv2-scope-group-title">الاستراتيجية والفرص</div>
        </div>
        <div class="spv2-scope-items">
          <div class="spv2-scope-item"><span class="spv2-scope-dot" aria-hidden="true"></span>دراسة الكلمات وفرص البحث</div>
          <div class="spv2-scope-item"><span class="spv2-scope-dot" aria-hidden="true"></span>تحليل المنافسين</div>
          <div class="spv2-scope-item"><span class="spv2-scope-dot" aria-hidden="true"></span>استراتيجية نمو مستمرة</div>
        </div>
      </div>

      <!-- Group 2: Content -->
      <div class="spv2-scope-group sr d1">
        <div class="spv2-scope-group-head">
          <div class="spv2-scope-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
          </div>
          <div class="spv2-scope-group-title">الصفحات والمحتوى</div>
        </div>
        <div class="spv2-scope-items">
          <div class="spv2-scope-item"><span class="spv2-scope-dot" aria-hidden="true"></span>تحسين صفحات الموقع</div>
          <div class="spv2-scope-item"><span class="spv2-scope-dot" aria-hidden="true"></span>كتابة وتطوير محتوى SEO</div>
          <div class="spv2-scope-item"><span class="spv2-scope-dot" aria-hidden="true"></span>الربط الداخلي بين الصفحات</div>
          <div class="spv2-scope-item"><span class="spv2-scope-dot" aria-hidden="true"></span>تحسين الخدمات والتصنيفات والمنتجات</div>
        </div>
      </div>

      <!-- Group 3: Technical -->
      <div class="spv2-scope-group sr d1">
        <div class="spv2-scope-group-head">
          <div class="spv2-scope-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
          </div>
          <div class="spv2-scope-group-title">التقني والمتاجر الإلكترونية</div>
        </div>
        <div class="spv2-scope-items">
          <div class="spv2-scope-item"><span class="spv2-scope-dot" aria-hidden="true"></span>مراجعة وتحسين الجوانب التقنية</div>
          <div class="spv2-scope-item"><span class="spv2-scope-dot" aria-hidden="true"></span>تحسين السرعة والفهرسة</div>
          <div class="spv2-scope-item"><span class="spv2-scope-dot" aria-hidden="true"></span>تحسين المتاجر الإلكترونية</div>
          <div class="spv2-scope-item"><span class="spv2-scope-dot" aria-hidden="true"></span>Google Merchant Center</div>
          <div class="spv2-scope-item"><span class="spv2-scope-dot" aria-hidden="true"></span>Google Business Profile عند الحاجة</div>
        </div>
      </div>

      <!-- Group 4: Authority -->
      <div class="spv2-scope-group sr d2">
        <div class="spv2-scope-group-head">
          <div class="spv2-scope-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          </div>
          <div class="spv2-scope-group-title">السلطة والقياس</div>
        </div>
        <div class="spv2-scope-items">
          <div class="spv2-scope-item"><span class="spv2-scope-dot" aria-hidden="true"></span>بناء الروابط والسلطة الخارجية</div>
          <div class="spv2-scope-item"><span class="spv2-scope-dot" aria-hidden="true"></span>متابعة الأداء والكلمات</div>
          <div class="spv2-scope-item"><span class="spv2-scope-dot" aria-hidden="true"></span>تقارير مباشرة عبر Looker Studio</div>
        </div>
      </div>

    </div>

    <p class="spv2-scope-clarify">
      يتم اختيار عناصر الخطة وحجم تنفيذها حسب احتياجات الموقع ونطاق العمل المتفق عليه.
    </p>

  </div>
</section>

<!-- ══════════════════════════════════════════════════════════════ -->
<!-- §5 لا نكتفي باكتشاف المشاكل التقنية                           -->
<!-- ══════════════════════════════════════════════════════════════ -->
<section class="sec sec-navy">
  <div class="wrap">

    <div class="sh c sr">
      <span class="tag d">الدعم التقني</span>
      <h2 class="h2 wh">لا نكتفي باكتشاف المشاكل التقنية</h2>
      <p class="bod d spv2-narrow">
        بعض شركات SEO ترسل تقريرًا بالمشاكل، ثم تطلب منك البحث عن مطور لتنفيذها.
      </p>
      <p class="bod d spv2-narrow" style="margin-top:10px">
        في SEO House يعمل فريق SEO مع المطور لدينا لتحديد الحلول التقنية وتنفيذ التعديلات المطلوبة ضمن نطاق المشروع، حتى لا تتوقف استراتيجية النمو بسبب مشكلة تقنية في الموقع.
      </p>
    </div>

    <div class="spv2-tech-eq sr d1" role="img" aria-label="فريق SEO مع مطور متخصص يساوي حلولاً قابلة للتنفيذ">
      <div class="spv2-tech-node">فريق SEO</div>
      <div class="spv2-tech-op" aria-hidden="true">+</div>
      <div class="spv2-tech-node">مطور متخصص</div>
      <div class="spv2-tech-op" aria-hidden="true">=</div>
      <div class="spv2-tech-result">حلول قابلة للتنفيذ</div>
    </div>

    <p class="spv2-tech-disclaimer" role="note">
      يعتمد حجم التنفيذ التقني على نطاق العمل المتفق عليه، وقد تحتاج أعمال التطوير الكبيرة أو الخارجة عن النطاق إلى تسعير منفصل.
    </p>

  </div>
</section>

<!-- ══════════════════════════════════════════════════════════════ -->
<!-- §6 هل تختلف تكلفة SEO للمتاجر الإلكترونية                    -->
<!-- ══════════════════════════════════════════════════════════════ -->
<section class="sec sec-surface">
  <div class="wrap">

    <div class="sh c sr">
      <span class="tag">سيو المتاجر</span>
      <h2 class="h2">هل تختلف تكلفة SEO للمتاجر الإلكترونية؟</h2>
    </div>

    <div style="max-width:680px;margin-inline:auto">
      <p class="bod" style="margin-bottom:6px"><strong style="color:var(--ink)">نعم، لأن حجم المتاجر واحتياجاتها يختلفان بشكل كبير.</strong></p>
      <p class="bod">متجر يحتوي على 100 منتج لا يحتاج إلى حجم العمل نفسه المطلوب لمتجر يحتوي على آلاف المنتجات والتصنيفات.</p>

      <p class="bod" style="margin-top:20px;font-weight:700;color:var(--ink-2)">قد يتأثر تسعير SEO للمتاجر بـ:</p>

      <div class="spv2-eco-factors">
        <div class="spv2-eco-factor"><span class="spv2-eco-dot" aria-hidden="true"></span>عدد المنتجات</div>
        <div class="spv2-eco-factor"><span class="spv2-eco-dot" aria-hidden="true"></span>Google Merchant Center</div>
        <div class="spv2-eco-factor"><span class="spv2-eco-dot" aria-hidden="true"></span>عدد التصنيفات</div>
        <div class="spv2-eco-factor"><span class="spv2-eco-dot" aria-hidden="true"></span>قوة المنافسة</div>
        <div class="spv2-eco-factor"><span class="spv2-eco-dot" aria-hidden="true"></span>بنية المتجر</div>
        <div class="spv2-eco-factor"><span class="spv2-eco-dot" aria-hidden="true"></span>حجم العمل التقني</div>
        <div class="spv2-eco-factor"><span class="spv2-eco-dot" aria-hidden="true"></span>مشاكل الفهرسة</div>
        <div class="spv2-eco-factor"><span class="spv2-eco-dot" aria-hidden="true"></span>المحتوى المطلوب</div>
        <div class="spv2-eco-factor"><span class="spv2-eco-dot" aria-hidden="true"></span>صفحات المنتجات والتصنيفات التي تحتاج إلى تحسين</div>
      </div>

      <p class="bod" style="margin-top:18px">
        لهذا يتم تسعير SEO للمتاجر بعد مراجعة المتجر وحجم الكتالوج والفرص المتاحة، وليس حسب باقة ثابتة.
      </p>
    </div>

  </div>
</section>

<!-- ══════════════════════════════════════════════════════════════ -->
<!-- §7 كيف نحدد تكلفة مشروعك                                      -->
<!-- ══════════════════════════════════════════════════════════════ -->
<section id="how-we-price" class="sec sec-white">
  <div class="wrap">

    <div class="sh c sr">
      <span class="tag">منهجية التسعير</span>
      <h2 class="h2">كيف نحدد تكلفة مشروعك؟</h2>
    </div>

    <div class="steps-list spv2-narrow">

      <div class="step-item sr">
        <div class="step-num" aria-hidden="true">01</div>
        <div class="step-body">
          <h3>نراجع موقعك</h3>
          <p>نراجع الوضع الحالي للموقع والصفحات والأداء التقني والظهور في Google.</p>
        </div>
      </div>

      <div class="step-item sr d1">
        <div class="step-num" aria-hidden="true">02</div>
        <div class="step-body">
          <h3>نحلل المنافسة</h3>
          <p>ندرس الكلمات والمنافسين وحجم الفرصة في السوق الذي تستهدفه.</p>
        </div>
      </div>

      <div class="step-item sr d1">
        <div class="step-num" aria-hidden="true">03</div>
        <div class="step-body">
          <h3>نحدد حجم العمل</h3>
          <p>نحدد الصفحات والمحتوى والعمل التقني وOff-Page SEO الذي يحتاج إليه المشروع.</p>
        </div>
      </div>

      <div class="step-item sr d2">
        <div class="step-num" aria-hidden="true">04</div>
        <div class="step-body">
          <h3>تحصل على خطة وسعر واضح</h3>
          <p>نقدم لك نطاق عمل شهريًا واضحًا وتكلفة مناسبة للمشروع قبل بدء التنفيذ.</p>
        </div>
      </div>

    </div>

  </div>
</section>

<!-- ══════════════════════════════════════════════════════════════ -->
<!-- §8 شاهد نتائج SEO لحظة بلحظة                                  -->
<!-- ══════════════════════════════════════════════════════════════ -->
<section class="sec sec-navy">
  <div class="wrap">

    <div class="sh c sr">
      <span class="tag d">التقارير والمتابعة</span>
      <h2 class="h2 wh">شاهد نتائج SEO لحظة بلحظة</h2>
      <p class="bod d spv2-narrow">تقاريرنا ليست ملف PDF يتم إرساله مرة واحدة كل شهر.</p>
      <p class="bod d spv2-narrow" style="margin-top:10px">يحصل عملاؤنا على لوحة Looker Studio مباشرة تجمع أهم بيانات SEO والأداء في مكان واحد، مع متابعة النتائج والتغيرات باستمرار.</p>
    </div>

    <div class="spv2-report-grid">
      <div class="spv2-report-item sr">
        <div class="spv2-report-dot" aria-hidden="true"></div>
        <span>الزيارات القادمة من نتائج البحث</span>
      </div>
      <div class="spv2-report-item sr d1">
        <div class="spv2-report-dot" aria-hidden="true"></div>
        <span>بيانات Google Search Console</span>
      </div>
      <div class="spv2-report-item sr d2">
        <div class="spv2-report-dot" aria-hidden="true"></div>
        <span>تطور ترتيب الكلمات</span>
      </div>
      <div class="spv2-report-item sr d1">
        <div class="spv2-report-dot" aria-hidden="true"></div>
        <span>التحويلات والطلبات عند توفر التتبع</span>
      </div>
      <div class="spv2-report-item sr d2">
        <div class="spv2-report-dot" aria-hidden="true"></div>
        <span>اتجاهات الأداء بمرور الوقت</span>
      </div>
    </div>

  </div>
</section>

<!-- ══════════════════════════════════════════════════════════════ -->
<!-- §9 FAQ                                                          -->
<!-- ══════════════════════════════════════════════════════════════ -->
<section class="sec sec-white">
  <div class="wrap">

    <div class="sh c sr">
      <span class="tag">أسئلة شائعة</span>
      <h2 class="h2">أسئلة عن أسعار SEO</h2>
    </div>

    <div class="faq-list spv2-narrow" role="list">

      <div class="faq-item" role="listitem">
        <div class="faq-q" tabindex="0" role="button" aria-expanded="false">
          <span>لماذا لا تقدم SEO House باقات SEO ثابتة؟</span>
          <div class="faq-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          </div>
        </div>
        <div class="faq-a" role="region">
          <div class="faq-a-inner">لأن حجم العمل المطلوب يختلف من موقع إلى آخر. نفضل تحديد نطاق العمل حسب الموقع والمنافسة والأهداف بدل إجبار جميع العملاء على الباقة نفسها.</div>
        </div>
      </div>

      <div class="faq-item" role="listitem">
        <div class="faq-q" tabindex="0" role="button" aria-expanded="false">
          <span>كم تبدأ أسعار خدمات SEO؟</span>
          <div class="faq-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          </div>
        </div>
        <div class="faq-a" role="region">
          <div class="faq-a-inner">تتراوح معظم المشاريع عادةً بين 1,500 و7,000 ريال شهريًا حسب حجم الموقع والمنافسة ونطاق التنفيذ المطلوب. وقد تحتاج المشاريع الكبيرة أو شديدة التعقيد إلى تسعير مخصص.</div>
        </div>
      </div>

      <div class="faq-item" role="listitem">
        <div class="faq-q" tabindex="0" role="button" aria-expanded="false">
          <span>كيف أعرف السعر المناسب لموقعي؟</span>
          <div class="faq-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          </div>
        </div>
        <div class="faq-a" role="region">
          <div class="faq-a-inner">نراجع الموقع والمنافسين وفرص البحث أولًا، ثم نحدد نطاق العمل والسعر الشهري المناسب قبل بدء المشروع.</div>
        </div>
      </div>

      <div class="faq-item" role="listitem">
        <div class="faq-q" tabindex="0" role="button" aria-expanded="false">
          <span>هل السعر يختلف للمتاجر الإلكترونية؟</span>
          <div class="faq-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          </div>
        </div>
        <div class="faq-a" role="region">
          <div class="faq-a-inner">نعم. عدد المنتجات والتصنيفات وحجم المتجر والمشاكل التقنية والمنافسة عوامل تؤثر في حجم العمل المطلوب.</div>
        </div>
      </div>

      <div class="faq-item" role="listitem">
        <div class="faq-q" tabindex="0" role="button" aria-expanded="false">
          <span>هل يشمل العمل حل المشاكل التقنية؟</span>
          <div class="faq-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          </div>
        </div>
        <div class="faq-a" role="region">
          <div class="faq-a-inner">لدينا دعم تقني ومطور يعمل مع فريق SEO لتنفيذ الحلول المطلوبة ضمن نطاق المشروع المتفق عليه. أما أعمال التطوير الكبيرة أو الخارجة عن النطاق، فيتم الاتفاق عليها بصورة منفصلة.</div>
        </div>
      </div>

      <div class="faq-item" role="listitem">
        <div class="faq-q" tabindex="0" role="button" aria-expanded="false">
          <span>هل تكاليف نشر Guest Posts والروابط الخارجية ضمن السعر؟</span>
          <div class="faq-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          </div>
        </div>
        <div class="faq-a" role="region">
          <div class="faq-a-inner">نتولى البحث عن فرص الروابط وتجهيز استراتيجية Off-Page SEO، لكن رسوم النشر التي تفرضها المواقع الخارجية ليست ضمن الاشتراك الشهري. يتم عرض تكلفة كل فرصة على العميل والحصول على موافقته قبل النشر.</div>
        </div>
      </div>

    </div>

  </div>
</section>

<!-- ══════════════════════════════════════════════════════════════ -->
<!-- §10 CTA + Lead Form                                             -->
<!-- ══════════════════════════════════════════════════════════════ -->
<section id="pricing-review" class="sec sec-surface">
  <div class="wrap">

    <div class="sh c sr">
      <span class="tag">اطلب مراجعة موقعك</span>
      <h2 class="h2">اعرف تكلفة SEO لموقعك</h2>
      <p class="bod">أرسل لنا رابط موقعك، وسنراجع وضعه الحالي والمنافسة لتحديد حجم العمل والسعر المناسب لمشروعك.</p>
      <div class="spv2-highlight-pill">النطاق المعتاد: من 1,500 إلى 7,000 ريال شهريًا</div>
    </div>

    <div class="spv2-form-wrap sr d1">
      <?php
      get_template_part( 'template-parts/layout/contact-form', null, [
          'form_title'    => 'اطلب مراجعة موقعك',
          'form_sub'      => 'سنراجع موقعك والمنافسة، ونوافيك بنطاق العمل والسعر الشهري المناسب قبل بدء أي التزام.',
          'form_note'     => 'أو تواصل معنا على واتساب مباشرةً — سنردّ في أقرب وقت',
          'success_title' => 'تم الإرسال بنجاح!',
          'success_desc'  => 'شكراً على تواصلك — سيتصل بك أحد متخصصينا خلال 24 ساعة لمراجعة موقعك وتحديد نطاق العمل.',
      ] );
      ?>
    </div>

  </div>
</section>

<?php
get_template_part( 'template-parts/layout/cta-banner', null, [
    'tag'     => 'ابدأ الآن',
    'title'   => 'ابدأ بكسب عملاء يبحثون عنك فعلاً',
    'buttons' => [
        [ 'text' => 'اطلب مراجعة موقعك', 'url' => '#pricing-review', 'class' => 'btn-w lg' ],
        [ 'text' => 'تعرّف على خدماتنا',  'url' => sh_page_url( 'services/seo' ), 'class' => 'btn-g lg' ],
    ],
] );
?>

</div><!-- /.seo-pricing-v2 -->

<?php get_footer(); ?>
