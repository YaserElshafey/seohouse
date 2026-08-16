<?php
/**
 * Template Name: SEO Service V2
 *
 * Main SEO services page — production template (V3 R5 design).
 * Robots: standard theme defaults (index, follow).
 */

// ── Country URLs — primary: ACF seo_market meta, fallback: slug path ─────────
$url_sa  = sh_market_permalink( 'saudi_arabia', 'services/seo/ksa' );
$url_eg  = sh_market_permalink( 'egypt',        'services/seo/egypt' );
$url_uae = sh_market_permalink( 'uae',          'services/seo/uae' );

// ── Service page links — only rendered when page is published ─────────────────
$url_seo_main       = sh_safe_url( 'services/seo' );
$url_seo_content    = sh_safe_url( 'services/seo/content' );
$url_seo_backlinks  = sh_safe_url( 'services/seo/backlinks' );
$url_seo_consulting = sh_safe_url( 'services/seo/consulting' );
$url_ecommerce      = sh_safe_url( 'sectors/ecommerce' );

$contact_url = sh_page_url( 'contact' ) ?: home_url( '/contact/' );

// ── Image asset paths ─────────────────────────────────────────────────────────
$img_dir      = get_template_directory_uri() . '/assets/images/seo-service/';
$img_cropA    = $img_dir . 'case-revenue-organic-dates.png';
$img_cropB    = $img_dir . 'case-revenue-organic-values.png';
$img_sahal_q  = $img_dir . 'case-sahal-query-law-firm.png';
$img_sahal_r  = $img_dir . 'case-sahal-organic-result.png';
$img_rev_full = $img_dir . '02-case-organic-revenue-106274.png';
$img_gsc      = $img_dir . '03-case-gsc-clicks-12900.png';
$img_sahal_full = $img_dir . '05-case-organic-ranking-law-firm-number-1.png';

// ── Reusable inline SVGs ──────────────────────────────────────────────────────
$arrow_svg  = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg>';
$expand_svg = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg>';
$check_svg  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="10" height="10" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>';

get_header();
?>

<div class="svc-seo" dir="rtl">

<!-- ══════════════════════════════════════════════════════════
     1. HERO — copy left, proof rail right
══════════════════════════════════════════════════════════ -->
<section class="ss-hero">
  <div class="wrap ss-hero-inner">

    <div class="ss-hero-copy">
      <div class="ss-tag-d">سيو هاوس</div>
      <h1 class="ss-h1">نحوّل البحث العضوي إلى طلبات ومبيعات</h1>
      <p class="ss-hero-p">خدمات سيو متكاملة تشمل التحسين التقني، دراسة الكلمات، تحسين الصفحات، كتابة المحتوى، بناء الروابط، الاستشارات، وقياس النتائج.</p>
      <div class="ss-hero-btns">
        <a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-p">اطلب مراجعة موقعك</a>
        <a href="#proof" class="btn btn-w ss-hero-btn-sec">شاهد نتائجنا</a>
      </div>
    </div>

    <div class="ss-proof-rail">
      <div class="ss-proof-row">
        <div class="ss-proof-num">
          <span class="metric-sequence" aria-label="من 19,956 إلى 106,274 ريال">
            <span>19,956</span><span aria-hidden="true">→</span><span>106,274</span><span class="metric-unit" dir="rtl">ريال</span>
          </span>
        </div>
        <div class="ss-proof-lbl">إيرادات البحث العضوي</div>
        <div class="ss-proof-src">Looker Studio — Organic Search</div>
      </div>
      <div class="ss-proof-row">
        <div class="ss-proof-num">
          <span class="metric-sequence" aria-label="من 825 إلى 12,900 نقرة">
            <span>825</span><span aria-hidden="true">→</span><span>12,900</span><span class="metric-unit" dir="rtl">نقرة</span>
          </span>
        </div>
        <div class="ss-proof-lbl">نقرات بحث عضوي شهرياً</div>
        <div class="ss-proof-src">Google Search Console</div>
      </div>
      <div class="ss-proof-row">
        <div class="ss-proof-num ss-proof-num-text">المركز الأول لكلمة «مكتب محاماة»</div>
        <div class="ss-proof-lbl">نتيجة عضوية أولى — سهل للمحاماة</div>
        <div class="ss-proof-src">Google Search — Organic Result</div>
      </div>
    </div>

  </div>
</section><!-- /.ss-hero -->


<!-- ══════════════════════════════════════════════════════════
     2. CLIENT LOGOS — exact homepage component
══════════════════════════════════════════════════════════ -->
<?php get_template_part( 'template-parts/sections/clients' ); ?>


<!-- ══════════════════════════════════════════════════════════
     3. SERVICES — numbered rows
══════════════════════════════════════════════════════════ -->
<section id="ss-services" class="sec sec-white ss-services">
  <div class="wrap">
    <div class="sh">
      <div class="tag">الخدمات</div>
      <h2 class="h2">منظومة عمل سيو هاوس</h2>
    </div>
    <div class="svc-rows">

      <div class="svc-row">
        <div class="svc-num">01</div>
        <div class="svc-body">
          <h3>الاستراتيجية والاستشارات</h3>
          <p>نبدأ بفهم نشاطك وأهدافك، ثم نضع خطة سيو تحدد الكلمات المستهدفة، الصفحات ذات الأولوية، والإجراءات القابلة للتنفيذ.</p>
          <?php if ( $url_seo_consulting ) : ?>
          <a href="<?php echo esc_url( $url_seo_consulting ); ?>" class="svc-link">استشارات سيو <?php echo $arrow_svg; // phpcs:ignore ?></a>
          <?php endif; ?>
        </div>
      </div>

      <div class="svc-row">
        <div class="svc-num">02</div>
        <div class="svc-body">
          <h3>التحليل التقني والتنفيذ</h3>
          <p>نراجع البنية التقنية وننفّذ التعديلات حسب نطاق التعاقد. يشمل ذلك سرعة التحميل، الفهرسة، البيانات المنظمة، والتحسين الداخلي.</p>
          <?php if ( $url_seo_main ) : ?>
          <a href="<?php echo esc_url( $url_seo_main ); ?>" class="svc-link">خدمة السيو الكاملة <?php echo $arrow_svg; // phpcs:ignore ?></a>
          <?php endif; ?>
        </div>
      </div>

      <div class="svc-row">
        <div class="svc-num">03</div>
        <div class="svc-body">
          <h3>الكلمات والصفحات وتحسين المحتوى</h3>
          <p>نبني خارطة كلمات مفصّلة، نحسّن الصفحات الحالية، وننتج محتوى موجّهاً لنية البحث لتنمية الزيارات العضوية باستمرار.</p>
          <?php if ( $url_seo_content ) : ?>
          <a href="<?php echo esc_url( $url_seo_content ); ?>" class="svc-link">كتابة محتوى سيو <?php echo $arrow_svg; // phpcs:ignore ?></a>
          <?php endif; ?>
          <?php if ( $url_ecommerce ) : ?>
          <a href="<?php echo esc_url( $url_ecommerce ); ?>" class="svc-link svc-link-sec">قطاع التجارة الإلكترونية <?php echo $arrow_svg; // phpcs:ignore ?></a>
          <?php endif; ?>
        </div>
      </div>

      <div class="svc-row">
        <div class="svc-num">04</div>
        <div class="svc-body">
          <h3>بناء الروابط والسلطة الرقمية</h3>
          <p>نبني روابط خارجية من مواقع ذات صلة وجودة عالية، مما يعزز سلطة الموقع وقدرته على التنافس على الكلمات التنافسية.</p>
          <?php if ( $url_seo_backlinks ) : ?>
          <a href="<?php echo esc_url( $url_seo_backlinks ); ?>" class="svc-link">بناء الباك لينك <?php echo $arrow_svg; // phpcs:ignore ?></a>
          <?php endif; ?>
        </div>
      </div>

      <div class="svc-row">
        <div class="svc-num">05</div>
        <div class="svc-body">
          <h3>القياس والتقارير والأولويات المستمرة</h3>
          <p>تقارير شهرية بالمنجز والأولويات القادمة، مع لوحة Looker Studio للأداء اللايف — لتتابع النتائج وتتخذ قرارات مبنية على بيانات حقيقية.</p>
        </div>
      </div>

    </div><!-- /.svc-rows -->
  </div>
</section><!-- /.ss-services -->


<!-- ══════════════════════════════════════════════════════════
     4. FEATURED CASE STUDY — revenue evidence
══════════════════════════════════════════════════════════ -->
<section class="sec sec-navy ss-case" id="proof">
  <div class="wrap">
    <div class="cs-inner">

      <div class="cs-text">
        <div class="cs-sup">دراسة حالة — نتيجة عضوية موثّقة</div>
        <h2 class="cs-h">من 19,956 إلى 106,274 ريال<br>إيرادات من البحث العضوي</h2>
        <div class="cs-facts">
          <div class="cs-fact">
            <div class="cs-fact-lbl">الفترة السابقة</div>
            <div class="cs-fact-val">SAR 19,956.29</div>
            <div class="cs-fact-dt">Aug 13 – Nov 10, 2025</div>
          </div>
          <div class="cs-fact">
            <div class="cs-fact-lbl">الفترة الجديدة</div>
            <div class="cs-fact-val cs-fact-accent">SAR 106,274.45</div>
            <div class="cs-fact-dt">Nov 12, 2025 – Feb 9, 2026</div>
          </div>
        </div>
        <div class="cs-src">المصدر: Looker Studio — Organic Search</div>
      </div>

      <div class="cs-evidence">
        <div class="rev-frame">
          <div class="rev-frame-head">
            <div class="rev-frame-title">نمو الإيرادات من البحث العضوي</div>
            <div class="rev-frame-sub">مقارنة بين الفترتين — Looker Studio</div>
          </div>
          <div class="rev-crops">
            <div class="rev-crop-a">
              <img
                src="<?php echo esc_url( $img_cropA ); ?>"
                alt="Organic Search channel and comparison dates"
                width="560"
                height="465"
                fetchpriority="high"
              >
            </div>
            <div class="rev-crop-b">
              <img
                src="<?php echo esc_url( $img_cropB ); ?>"
                alt="SAR 19,956.29 vs SAR 106,274.45 — 432.54% increase"
                width="376"
                height="465"
                fetchpriority="high"
              >
            </div>
          </div>
          <div class="rev-frame-foot">
            <span class="rev-caption">أجزاء مكبرة من المصدر الأصلي لسهولة القراءة</span>
            <button type="button" class="rev-src-btn js-lb-open" data-lb="lb-rev">
              <?php echo $expand_svg; // phpcs:ignore ?>عرض المصدر كاملًا
            </button>
          </div>
        </div>
      </div>

    </div><!-- /.cs-inner -->
  </div>
</section><!-- /.ss-case -->


<!-- ══════════════════════════════════════════════════════════
     5. ADDITIONAL PROOF — GSC + Sahal crops
══════════════════════════════════════════════════════════ -->
<section class="sec sec-white ss-addproof">
  <div class="wrap">
    <div class="sh">
      <div class="tag ss-tag-muted">نتائج موثّقة</div>
      <h2 class="h2">نتائج إضافية</h2>
    </div>
    <div class="proof-stacks">

      <!-- GSC -->
      <div class="proof-split proof-img-r">
        <div class="proof-txt">
          <div class="proof-txt-tag">Google Search Console</div>
          <div class="proof-metric">
            <span class="metric-sequence" aria-label="من 825 إلى 12,900 نقرة">
              <span>825</span><span aria-hidden="true">→</span><span>12,900</span><span class="metric-unit" dir="rtl">نقرة</span>
            </span>
          </div>
          <p>ارتفعت النقرات من 825 في الفترة السابقة إلى 12,900 نقرة خلال آخر 28 يومًا، وفق بيانات Google Search Console.</p>
          <div class="proof-badge">Google Search Console</div>
        </div>
        <div class="proof-img-box js-lb-open" data-lb="lb-gsc"
             role="button" tabindex="0"
             aria-label="عرض لقطة Google Search Console بالحجم الكامل">
          <img
            src="<?php echo esc_url( $img_gsc ); ?>"
            alt="Google Search Console — نقرات من 825 إلى 12,900 نقرة شهرياً"
            loading="lazy"
          >
          <div class="proof-expand" aria-hidden="true">
            <span><?php echo $expand_svg; // phpcs:ignore ?>عرض كاملاً</span>
          </div>
        </div>
      </div>

      <!-- Sahal — two authentic crops stacked -->
      <div class="proof-split proof-img-l">
        <div class="sahal-crops-wrap">
          <div class="sahal-crop-q">
            <img
              src="<?php echo esc_url( $img_sahal_q ); ?>"
              alt="Search query: مكتب محاماة"
              width="1320"
              height="110"
              loading="lazy"
            >
          </div>
          <div class="sahal-crop-r">
            <img
              src="<?php echo esc_url( $img_sahal_r ); ?>"
              alt="سهل للمحاماة — first organic result for مكتب محاماة"
              width="1288"
              height="265"
              loading="lazy"
            >
          </div>
          <div class="sahal-crops-foot">
            <span class="sahal-caption">لقطتان مكبرتان من المصدر الأصلي: عبارة البحث ثم أول نتيجة عضوية بعد قسم الأماكن.</span>
            <button type="button" class="sahal-src-btn js-lb-open" data-lb="lb-sahal">
              <?php echo $expand_svg; // phpcs:ignore ?>عرض المصدر كاملًا
            </button>
          </div>
        </div>
        <div class="proof-txt">
          <div class="proof-txt-tag">Google Search — نتيجة عضوية</div>
          <div class="proof-metric proof-metric-txt">المركز الأول في النتائج العضوية لكلمة «مكتب محاماة»</div>
          <p>ظهر موقع سهل للمحاماة (sahalfirm.com) في المركز الأول من النتائج العضوية — النتيجة عضوية وليست من خرائط Google.</p>
          <div class="proof-badge">Google Search — Organic Result</div>
        </div>
      </div>

    </div><!-- /.proof-stacks -->
  </div>
</section><!-- /.ss-addproof -->


<!-- ══════════════════════════════════════════════════════════
     6. TARGET MARKETS — fully clickable text-link columns
══════════════════════════════════════════════════════════ -->
<section class="sec sec-off ss-markets">
  <div class="wrap">
    <div class="sh">
      <div class="tag">الأسواق</div>
      <h2 class="h2">نعمل في الأسواق الرئيسية</h2>
    </div>
    <div class="market-grid">
      <?php
      $markets = [
          [
              'name' => 'السعودية',
              'desc' => 'نساعد الشركات والمتاجر التي تستهدف السوق السعودي على بناء صفحات وخطة كلمات تناسب نية البحث داخل المملكة، مع ربط الظهور بالطلبات والمبيعات.',
              'url'  => $url_sa,
              'cta'  => 'عرض خدمة سيو السعودية',
          ],
          [
              'name' => 'مصر',
              'desc' => 'نطوّر ظهور المواقع التي تستهدف السوق المصري من خلال تحسين الصفحات الحالية وبناء المحتوى حول الخدمات والمنتجات التي يبحث عنها العملاء.',
              'url'  => $url_eg,
              'cta'  => 'عرض خدمة سيو مصر',
          ],
          [
              'name' => 'الإمارات',
              'desc' => 'نضع خطة SEO تناسب سوق الإمارات التنافسي ومتعدد اللغات، مع فصل واضح بين الكلمات والصفحات المستهدفة لكل خدمة وسوق.',
              'url'  => $url_uae,
              'cta'  => 'عرض خدمة سيو الإمارات',
          ],
      ];
      foreach ( $markets as $mkt ) :
          $has_url = ! empty( $mkt['url'] );
          $tag_open  = $has_url
              ? '<a href="' . esc_url( $mkt['url'] ) . '" class="market-link">'
              : '<div class="market-link market-link-static">';
          $tag_close = $has_url ? '</a>' : '</div>';
      ?>
      <div class="market-col">
        <?php echo $tag_open; // phpcs:ignore ?>
          <div class="market-name"><?php echo esc_html( $mkt['name'] ); ?></div>
          <p class="market-desc"><?php echo esc_html( $mkt['desc'] ); ?></p>
          <?php if ( $has_url ) : ?>
          <div class="market-cta"><?php echo esc_html( $mkt['cta'] ); ?><?php echo $arrow_svg; // phpcs:ignore ?></div>
          <?php endif; ?>
        <?php echo $tag_close; // phpcs:ignore ?>
      </div>
      <?php endforeach; ?>
    </div><!-- /.market-grid -->
  </div>
</section><!-- /.ss-markets -->


<!-- ══════════════════════════════════════════════════════════
     7. PROCESS + DELIVERABLES — merged section
══════════════════════════════════════════════════════════ -->
<section class="sec sec-white ss-process">
  <div class="wrap">
    <div class="sh">
      <div class="tag">طريقة العمل</div>
      <h2 class="h2">كيف يسير العمل</h2>
    </div>
    <div class="process-inner">

      <div class="stages">
        <?php
        $stages = [
            [ '1', 'فهم النشاط والأهداف وربط القنوات وضبط القياس',
              'نبدأ بجلسة استيعاب، نربط أدوات القياس، ونضع الأساس الذي يجعل كل خطوة قابلة للقياس.' ],
            [ '2', 'التدقيق وخارطة الكلمات والأولويات',
              'تدقيق تقني شامل وخارطة كلمات تحدد الصفحات ذات الأولوية والفجوات القابلة للاستثمار.' ],
            [ '3', 'التنفيذ التقني والمحتوى والصفحات وبناء الروابط',
              'تنفيذ فعلي بالتنسيق مع فريقك حسب نطاق التعاقد — تحسينات تقنية، محتوى موجّه، وبناء روابط خارجية.' ],
            [ '4', 'مراجعة الجودة والتقارير والأولويات التالية',
              'تقرير شهري بما تم إنجازه وما هو قادم، مع لوحة أداء لايف يمكنك الاطلاع عليها في أي وقت.' ],
        ];
        foreach ( $stages as [ $n, $h, $p ] ) : ?>
        <div class="stage">
          <div class="stage-n" aria-hidden="true"><?php echo esc_html( $n ); ?></div>
          <div>
            <h3><?php echo esc_html( $h ); ?></h3>
            <p><?php echo esc_html( $p ); ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div><!-- /.stages -->

      <div class="deliv-box">
        <div class="deliv-box-label">ما يصلك من العمل</div>
        <div class="deliv-items">
          <?php
          $deliverables = [
              'خارطة الكلمات والصفحات',
              'سجل التحسينات التقنية',
              'خطة المحتوى',
              'لوحة Looker Studio للأداء اللايف',
              'تقرير شهري بالمنجز والأولويات القادمة',
          ];
          foreach ( $deliverables as $item ) : ?>
          <div class="deliv-item">
            <div class="deliv-ico" aria-hidden="true"><?php echo $check_svg; // phpcs:ignore ?></div>
            <span><?php echo esc_html( $item ); ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div><!-- /.deliv-box -->

    </div><!-- /.process-inner -->
  </div>
</section><!-- /.ss-process -->


<!-- ══════════════════════════════════════════════════════════
     8. WHY SEO HOUSE
══════════════════════════════════════════════════════════ -->
<section class="sec sec-white ss-why">
  <div class="wrap">
    <div class="sh">
      <div class="tag">لماذا سيو هاوس</div>
      <h2 class="h2">ما يجعل الفارق في التنفيذ</h2>
    </div>
    <div class="why-rows">
      <?php
      $why_rows = [
          [
              'تنفيذ فعلي حسب نطاق التعاقد',
              'في الباقات التنفيذية نطبّق التعديلات التقنية مباشرة عند توفر الصلاحيات، ولدينا مطوّر متخصص في متطلبات السيو.',
          ],
          [
              'دعم مطوّر تقني متخصص في سيو',
              'يدعمنا مطوّر تقني متخصص في متطلبات السيو — مما يتيح تنفيذ التعديلات التقنية بدقة.',
          ],
          [
              'تخطيط وكتابة محتوى سيو داخلياً',
              'المحتوى يُكتب بناءً على دراسة نية البحث والكلمات المستهدفة — ليس نصوصاً عامة.',
          ],
          [
              'تقارير أداء لايف على Looker Studio',
              'لوحة بيانات مباشرة تستطيع الاطلاع عليها في أي وقت — تشمل المقاييس الأهم لنشاطك.',
          ],
      ];
      foreach ( $why_rows as [ $title, $desc ] ) : ?>
      <div class="why-row">
        <div class="why-dot" aria-hidden="true"></div>
        <div>
          <h3><?php echo esc_html( $title ); ?></h3>
          <p><?php echo esc_html( $desc ); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div><!-- /.why-rows -->
  </div>
</section><!-- /.ss-why -->


<!-- ══════════════════════════════════════════════════════════
     9. GOOGLE REVIEWS — exact homepage shortcode pattern
══════════════════════════════════════════════════════════ -->
<?php $_rev_sc = trim( sh_option( 'reviews_shortcode', '' ) ); if ( $_rev_sc ) : ?>
<section class="sec sec-white ss-reviews">
  <div class="wrap">
    <div class="sh c" style="margin-bottom:36px">
      <div class="tag">تقييمات العملاء</div>
      <h2 class="h2">آراء من عملوا معنا</h2>
    </div>
    <div class="rev-plugin">
      <?php echo do_shortcode( wp_kses_post( $_rev_sc ) ); ?>
    </div>
  </div>
</section><!-- /.ss-reviews -->
<?php endif; ?>


<!-- ══════════════════════════════════════════════════════════
     10. FAQ — 6 items, all closed by default
══════════════════════════════════════════════════════════ -->
<section class="sec sec-<?php echo $_rev_sc ? 'off' : 'white'; ?> ss-faq">
  <div class="wrap">
    <div class="sh c">
      <div class="tag">أسئلة شائعة</div>
      <h2 class="h2">أسئلة يسألها عملاؤنا</h2>
    </div>
    <div class="ss-faq-list">
      <?php
      $faqs = [
          [
              'q' => 'هل تنفذون التعديلات التقنية أم تقدمون التوصيات فقط؟',
              'a' => 'يعتمد ذلك على نطاق التعاقد. في الباقات التنفيذية ينفذ فريقنا التعديلات التقنية مباشرة عند توفر الصلاحيات، ولدينا مطوّر متخصص. وفي الاستشارات نقدم توصيات تنفيذية مرتبة ونتابع تطبيقها مع فريقك.',
          ],
          [
              'q' => 'هل تشمل الخدمة كتابة المحتوى؟',
              'a' => 'نعم، يمكن أن تشمل الخدمة تخطيط وكتابة المقالات وصفحات الخدمات والفئات وأوصاف المنتجات، وفق خطة الكلمات ونطاق التعاقد.',
          ],
          [
              'q' => 'كيف يتم بناء الروابط الخارجية؟',
              'a' => 'نبدأ بتحليل الروابط الحالية وروابط المنافسين، ثم نبني روابط تحريرية من مواقع مناسبة لطبيعة النشاط، مع توثيق الروابط المنفذة ومتابعتها داخل التقرير.',
          ],
          [
              'q' => 'هل يمكن استهداف السعودية ومصر والإمارات؟',
              'a' => 'نعم. نضع لكل سوق خطة كلمات وصفحات منفصلة تراعي طريقة البحث والمنافسة داخله، مع صفحات مخصصة للسعودية ومصر والإمارات.',
          ],
          [
              'q' => 'كيف تتم متابعة النتائج والتقارير؟',
              'a' => 'تتم المتابعة من خلال لوحة Looker Studio للأداء المباشر، إلى جانب تقرير شهري يوضح ما تم تنفيذه، تطور النتائج، والأولويات القادمة.',
          ],
          [
              'q' => 'ما تكلفة خدمات تحسين محركات البحث؟',
              'a' => 'تبدأ خدمات السيو من 1,500 وتصل إلى 7,000 ريال شهريًا، حسب حجم الموقع، السوق المستهدف، المنافسة، ونطاق التنفيذ.',
          ],
      ];
      foreach ( $faqs as $faq ) : ?>
      <div class="faq-item">
        <button type="button" class="faq-q" aria-expanded="false">
          <span><?php echo esc_html( $faq['q'] ); ?></span>
          <span class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="11" height="11"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></span>
        </button>
        <div class="faq-a">
          <div class="faq-a-inner"><?php echo esc_html( $faq['a'] ); ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div><!-- /.ss-faq-list -->
  </div>
</section><!-- /.ss-faq -->


<!-- ══════════════════════════════════════════════════════════
     11. CONTACT — existing theme component
══════════════════════════════════════════════════════════ -->
<section class="sec sec-navy ss-cta">
  <div class="wrap">
    <div class="ss-cta-layout">

      <div class="ss-cta-copy">
        <h2 class="ss-cta-h">ابدأ بمراجعة موقعك</h2>
        <p class="ss-cta-p">أخبرنا عن موقعك وهدفك — سنتواصل معك لتحديد موعد الاستشارة.</p>
        <div class="ss-cta-pts">
          <div class="ss-cta-pt">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="15" height="15" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
            تحليل تقني أولي للموقع
          </div>
          <div class="ss-cta-pt">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="15" height="15" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
            تحديد الفرص وأولويات التحسين
          </div>
          <div class="ss-cta-pt">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="15" height="15" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
            لا التزام — استشارة لتقييم الوضع
          </div>
        </div>
      </div>

      <div class="ss-cta-form">
        <?php get_template_part( 'template-parts/layout/contact-form', null, [
            'form_title'    => 'اطلب مراجعة موقعك',
            'form_sub'      => 'أرسل لنا تفاصيل موقعك وسنتواصل معك لتحديد الموعد.',
            'form_note'     => 'أو تواصل معنا على واتساب مباشرةً',
            'success_title' => 'تم الإرسال بنجاح!',
            'success_desc'  => 'شكراً على تواصلك — سيتصل بك أحد متخصصينا لتحديد موعد الاستشارة.',
        ] ); ?>
      </div>

    </div>
  </div>
</section><!-- /.ss-cta -->


<!-- ══════════════════════════════════════════════════════════
     LIGHTBOXES — authentic source images
══════════════════════════════════════════════════════════ -->
<div class="ss-lb" id="lb-rev" role="dialog" aria-modal="true" aria-label="تقرير Looker Studio — الإيرادات العضوية">
  <button type="button" class="ss-lb-x" data-lb="lb-rev" aria-label="إغلاق">✕</button>
  <img src="<?php echo esc_url( $img_rev_full ); ?>" alt="تقرير Looker Studio — الإيرادات العضوية الكاملة" loading="lazy">
</div>

<div class="ss-lb" id="lb-gsc" role="dialog" aria-modal="true" aria-label="Google Search Console — النقرات">
  <button type="button" class="ss-lb-x" data-lb="lb-gsc" aria-label="إغلاق">✕</button>
  <img src="<?php echo esc_url( $img_gsc ); ?>" alt="Google Search Console — نقرات من 825 إلى 12,900" loading="lazy">
</div>

<div class="ss-lb" id="lb-sahal" role="dialog" aria-modal="true" aria-label="نتيجة بحث سهل للمحاماة">
  <button type="button" class="ss-lb-x" data-lb="lb-sahal" aria-label="إغلاق">✕</button>
  <img src="<?php echo esc_url( $img_sahal_full ); ?>" alt="نتائج Google — سهل للمحاماة في المركز الأول" loading="lazy">
</div>

</div><!-- /.svc-seo -->

<script>
(function () {
  'use strict';
  function openLB(id) {
    var el = document.getElementById(id);
    if (!el) return;
    el.classList.add('ss-lb--open');
    document.body.style.overflow = 'hidden';
    var closeBtn = el.querySelector('.ss-lb-x');
    if (closeBtn) closeBtn.focus();
  }
  function closeLB(id) {
    var el = document.getElementById(id);
    if (!el) return;
    el.classList.remove('ss-lb--open');
    document.body.style.overflow = '';
  }

  // Backdrop click
  document.querySelectorAll('.ss-lb').forEach(function (lb) {
    lb.addEventListener('click', function (e) {
      if (e.target === lb) closeLB(lb.id);
    });
  });

  // Close button
  document.querySelectorAll('.ss-lb-x').forEach(function (btn) {
    btn.addEventListener('click', function () {
      closeLB(btn.getAttribute('data-lb'));
    });
  });

  // Open triggers
  document.querySelectorAll('.js-lb-open').forEach(function (trigger) {
    trigger.addEventListener('click', function () {
      openLB(trigger.getAttribute('data-lb'));
    });
    trigger.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        openLB(trigger.getAttribute('data-lb'));
      }
    });
  });

  // Escape key
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      document.querySelectorAll('.ss-lb--open').forEach(function (lb) {
        closeLB(lb.id);
      });
    }
  });
}());
</script>

<?php get_footer(); ?>
