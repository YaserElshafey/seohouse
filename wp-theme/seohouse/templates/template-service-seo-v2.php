<?php
/**
 * Template Name: SEO Service V2
 *
 * Main SEO services page — production template.
 * Robots: standard theme defaults (index, follow).
 */

// ── Country landing page URLs — primary: ACF seo_market meta, fallback: slug ──
$url_sa  = sh_market_permalink( 'saudi_arabia', 'services/seo/saudi-arabia' );
$url_eg  = sh_market_permalink( 'egypt',        'services/seo/egypt' );
$url_uae = sh_market_permalink( 'uae',          'services/seo/uae' );

// ── Service page links — only rendered when page is published ────────────────
$url_seo_main       = sh_safe_url( 'services/seo' );
$url_seo_content    = sh_safe_url( 'services/seo/content' );
$url_seo_backlinks  = sh_safe_url( 'services/seo/backlinks' );
$url_seo_consulting = sh_safe_url( 'services/seo/consulting' );

$contact_url = sh_page_url( 'contact' ) ?: home_url( '/contact/' );

// ── Image asset paths ────────────────────────────────────────────────────────
$img_dir  = get_template_directory_uri() . '/assets/images/seo-service/';
$img_hero = $img_dir . '01-hero-seohouse-office.png';
$img_cs1  = $img_dir . '02-case-organic-revenue-106274.png';
$img_cs2  = $img_dir . '03-case-gsc-clicks-12900.png';
$img_cs4  = $img_dir . '05-case-organic-ranking-law-firm-number-1.png';

get_header();
?>

<div class="svc-seo" dir="rtl">

<!-- ══════════════════════════════════════════════════════════
     1. HERO — compact, copy-first, editorial image
══════════════════════════════════════════════════════════ -->
<section class="ss-hero">
  <div class="wrap ss-hero-inner">

    <div class="ss-hero-text">
      <h1 class="ss-h1">خدمات سيو متكاملة تساعدك على الظهور وتحقيق نتائج قابلة للقياس</h1>
      <p class="ss-hero-p">نساعد الشركات والمتاجر على تحسين ظهورها في محركات البحث من خلال التحسين التقني، ودراسة الكلمات، وتحسين الصفحات، وكتابة المحتوى، وبناء الروابط، والاستشارات، مع متابعة التنفيذ وقياس النتائج.</p>
      <div class="ss-hero-btns">
        <a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-p">اطلب مراجعة موقعك</a>
        <a href="#ss-services" class="btn btn-w ss-hero-btn-sec">استكشف الخدمات</a>
      </div>
    </div>

    <div class="ss-hero-visual">
      <div class="ss-hero-img-frame">
        <img
          src="<?php echo esc_url( $img_hero ); ?>"
          alt="مكتب SEO House"
          width="420"
          height="320"
          loading="eager"
          fetchpriority="high"
        >
      </div>
    </div>

  </div>
</section><!-- /.ss-hero -->


<!-- ══════════════════════════════════════════════════════════
     2. CLIENT LOGOS — exact homepage component (template part)
══════════════════════════════════════════════════════════ -->
<?php get_template_part( 'template-parts/sections/clients' ); ?>


<!-- ══════════════════════════════════════════════════════════
     3. SEO SERVICES — featured + 3 prominent cards
══════════════════════════════════════════════════════════ -->
<section id="ss-services" class="sec sec-white ss-services">
  <div class="wrap">
    <div class="sh c">
      <h2 class="h2">خدمات تحسين محركات البحث التي نقدمها</h2>
    </div>

    <!-- Featured: complete SEO service -->
    <div class="ss-svc-feat">
      <div class="ss-svc-ico" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="22" height="22"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      </div>
      <div class="ss-svc-feat-body">
        <h3 class="ss-svc-feat-h">خدمة سيو متكاملة</h3>
        <p class="ss-svc-feat-p">نغطي كل ما يحتاجه موقعك: تحليل تقني، بحث كلمات، تحسين صفحات، كتابة محتوى، بناء روابط، واستشارات — مع تقارير حية ومتابعة مستمرة للتنفيذ.</p>
        <div class="ss-svc-support">
          <span>السيو التقني</span>
          <span>بحث الكلمات وتحسين الصفحات</span>
          <span>سيو المتاجر الإلكترونية</span>
          <span>السيو المحلي</span>
          <span>القياس والتقارير</span>
        </div>
      </div>
      <?php if ( $url_seo_main ) : ?>
      <a href="<?php echo esc_url( $url_seo_main ); ?>" class="btn btn-p ss-svc-feat-cta">عرض الخدمة الكاملة</a>
      <?php endif; ?>
    </div>

    <!-- Three prominent service cards -->
    <div class="ss-svc3-grid">

      <div class="ss-svc3-card">
        <div class="ss-svc-ico" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="22" height="22"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
        </div>
        <h3 class="ss-svc3-h">كتابة المحتوى المتوافق مع SEO</h3>
        <p class="ss-svc3-p">تخطيط وكتابة المقالات وصفحات الخدمة وفق خطة الكلمات المستهدفة، مع مراجعة SEO لكل قطعة قبل النشر.</p>
        <?php if ( $url_seo_content ) : ?>
        <a href="<?php echo esc_url( $url_seo_content ); ?>" class="ss-svc3-link">تفاصيل الخدمة <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="13" height="13"><path d="M15 18l-6-6 6-6"/></svg></a>
        <?php endif; ?>
      </div>

      <div class="ss-svc3-card">
        <div class="ss-svc-ico" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="22" height="22"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
        </div>
        <h3 class="ss-svc3-h">بناء الروابط الخارجية</h3>
        <p class="ss-svc3-p">روابط من مواقع موثوقة ذات صلة بمجالك — بناء سلطة رقمية مستدامة وفق معايير جوجل.</p>
        <?php if ( $url_seo_backlinks ) : ?>
        <a href="<?php echo esc_url( $url_seo_backlinks ); ?>" class="ss-svc3-link">تفاصيل الخدمة <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="13" height="13"><path d="M15 18l-6-6 6-6"/></svg></a>
        <?php endif; ?>
      </div>

      <div class="ss-svc3-card">
        <div class="ss-svc-ico" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="22" height="22"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <h3 class="ss-svc3-h">استشارات تحسين محركات البحث</h3>
        <p class="ss-svc3-p">تدقيق شامل وورش استراتيجية وخارطة طريق واضحة — للفريق الداخلي أو التنفيذ المشترك.</p>
        <?php if ( $url_seo_consulting ) : ?>
        <a href="<?php echo esc_url( $url_seo_consulting ); ?>" class="ss-svc3-link">تفاصيل الخدمة <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="13" height="13"><path d="M15 18l-6-6 6-6"/></svg></a>
        <?php endif; ?>
      </div>

    </div><!-- /.ss-svc3-grid -->
  </div>
</section><!-- /.ss-services -->


<!-- ══════════════════════════════════════════════════════════
     4. TARGET MARKETS — navy, fully clickable cards
══════════════════════════════════════════════════════════ -->
<section class="sec sec-navy ss-markets">
  <div class="wrap">
    <div class="sh c">
      <h2 class="h2 wh">خدمات سيو مخصصة لكل سوق</h2>
    </div>
    <div class="ss-markets-grid">
      <?php
      $markets = [
          [ 'flag' => '🇸🇦', 'name' => 'خدمات سيو في السعودية',  'url' => $url_sa  ],
          [ 'flag' => '🇪🇬', 'name' => 'خدمات سيو في مصر',       'url' => $url_eg  ],
          [ 'flag' => '🇦🇪', 'name' => 'خدمات سيو في الإمارات', 'url' => $url_uae ],
      ];
      foreach ( $markets as $mkt ) :
          $tag  = $mkt['url'] ? 'a' : 'div';
          $href = $mkt['url'] ? sprintf( ' href="%s"', esc_url( $mkt['url'] ) ) : '';
      ?>
      <<?php echo $tag; ?><?php echo $href; ?> class="ss-market-card<?php echo $mkt['url'] ? ' ss-market-link' : ''; ?>">
        <div class="ss-market-flag" aria-hidden="true"><?php echo esc_html( $mkt['flag'] ); ?></div>
        <div class="ss-market-name"><?php echo esc_html( $mkt['name'] ); ?></div>
        <?php if ( $mkt['url'] ) : ?>
        <div class="ss-market-arrow" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16"><path d="M15 18l-6-6 6-6"/></svg>
        </div>
        <?php else : ?>
        <?php /* Admin note: publish a page with seo_market ACF = this market key to show a link */ ?>
        <?php endif; ?>
      </<?php echo $tag; ?>>
      <?php endforeach; ?>
    </div>
  </div>
</section><!-- /.ss-markets -->


<!-- ══════════════════════════════════════════════════════════
     5. RESULTS BENTO — 3 verified screenshots
══════════════════════════════════════════════════════════ -->
<section class="sec sec-white ss-results">
  <div class="wrap">
    <div class="sh c">
      <h2 class="h2">نتائج حققناها من البحث العضوي</h2>
    </div>

    <div class="ss-bento">

      <!-- Featured large card -->
      <div class="ss-bento-feat">
        <div class="ss-bento-img-wrap">
          <img
            src="<?php echo esc_url( $img_cs1 ); ?>"
            alt="تقرير يُظهر نمو الإيرادات العضوية من 19,956 ريال إلى 106,274 ريال"
            loading="lazy"
          >
        </div>
        <div class="ss-bento-info">
          <div class="ss-metric" dir="ltr">
            <span class="ss-metric-from">SAR 19,956</span>
            <span class="ss-metric-arrow">→</span>
            <span class="ss-metric-to">SAR 106,274</span>
          </div>
          <p class="ss-bento-desc">نمو الإيرادات العضوية لمتجر إلكتروني</p>
        </div>
      </div><!-- /.ss-bento-feat -->

      <!-- Supporting cards -->
      <div class="ss-bento-side">

        <div class="ss-bento-card">
          <div class="ss-bento-img-wrap ss-bento-img-sm">
            <img
              src="<?php echo esc_url( $img_cs2 ); ?>"
              alt="Google Search Console يُظهر نمو النقرات من 825 إلى 12,900 نقرة شهرياً"
              loading="lazy"
            >
          </div>
          <div class="ss-bento-info">
            <div class="ss-metric" dir="ltr">
              <span class="ss-metric-from">825</span>
              <span class="ss-metric-arrow">→</span>
              <span class="ss-metric-to">12,900</span>
            </div>
            <p class="ss-bento-desc">نقرات عضوية شهرية</p>
          </div>
        </div>

        <div class="ss-bento-card">
          <div class="ss-bento-img-wrap ss-bento-img-sm">
            <img
              src="<?php echo esc_url( $img_cs4 ); ?>"
              alt="نتائج Google تُظهر مكتب المحاماة في المركز الأول لكلمة مكتب محاماة"
              loading="lazy"
            >
          </div>
          <div class="ss-bento-info">
            <p class="ss-bento-desc">المركز الأول في النتائج العضوية على كلمة «مكتب محاماة»</p>
          </div>
        </div>

      </div><!-- /.ss-bento-side -->

    </div><!-- /.ss-bento -->
  </div>
</section><!-- /.ss-results -->


<!-- ══════════════════════════════════════════════════════════
     6. WHY SEO HOUSE — navy, 2-column layout
══════════════════════════════════════════════════════════ -->
<section class="sec sec-navy ss-why">
  <div class="wrap ss-why-inner">

    <div class="ss-why-lead">
      <h2 class="h2 wh">لماذا SEO House؟</h2>
      <p class="ss-why-sub">ننفذ ونقيس — لا نكتفي بتسليم تقرير.</p>
    </div>

    <div class="ss-why-pts">
      <div class="ss-why-pt">
        <div class="ss-why-ico" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="18" height="18"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        </div>
        <div>
          <strong class="ss-why-h">تنفيذ تقني مباشر</strong>
          <p class="ss-why-p">إصلاحات الزحف والسرعة والبنية تُنفَّذ فعلياً — بمطوّرنا أو بالتنسيق مع فريقك وفق نطاق الخدمة.</p>
        </div>
      </div>
      <div class="ss-why-pt">
        <div class="ss-why-ico" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="18" height="18"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <div>
          <strong class="ss-why-h">محتوى مراجَع بشرياً</strong>
          <p class="ss-why-p">كتّاب متخصصون ومراجعة SEO لكل قطعة — لا محتوى مولّداً بدون إشراف.</p>
        </div>
      </div>
      <div class="ss-why-pt">
        <div class="ss-why-ico" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="18" height="18"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
        </div>
        <div>
          <strong class="ss-why-h">تقارير حية دائماً</strong>
          <p class="ss-why-p">لوحة Looker Studio متاحة في أي وقت — مراكز ونقرات وإيرادات محدّثة تلقائياً.</p>
        </div>
      </div>
      <div class="ss-why-pt">
        <div class="ss-why-ico" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="18" height="18"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8m-4-4v4"/></svg>
        </div>
        <div>
          <strong class="ss-why-h">أسواق السعودية ومصر والإمارات</strong>
          <p class="ss-why-p">خبرة عملية في الأسواق الثلاثة — مع فهم خصوصية كل سوق وسلوك المستخدم فيه.</p>
        </div>
      </div>
    </div>

  </div>
</section><!-- /.ss-why -->


<!-- ══════════════════════════════════════════════════════════
     7. HOW WE WORK — compact 4-step
══════════════════════════════════════════════════════════ -->
<section class="sec sec-white ss-process">
  <div class="wrap">
    <div class="sh c">
      <h2 class="h2">كيف نعمل؟</h2>
    </div>
    <div class="ss-steps">
      <div class="ss-step">
        <div class="ss-step-num" aria-hidden="true">01</div>
        <div class="ss-step-body">
          <h3 class="ss-step-h">التحليل وتثبيت القياس</h3>
          <p class="ss-step-p">جلسة لفهم أهدافك، وإعداد GA4 وSearch Console ولوحة Looker Studio لتتبع النتائج منذ البداية.</p>
        </div>
      </div>
      <div class="ss-step">
        <div class="ss-step-num" aria-hidden="true">02</div>
        <div class="ss-step-body">
          <h3 class="ss-step-h">التدقيق وخرائط الكلمات</h3>
          <p class="ss-step-p">تدقيق تقني وتنافسي شامل، تليه خريطة كلمات مرتبطة بصفحاتك وخطة عمل واضحة.</p>
        </div>
      </div>
      <div class="ss-step">
        <div class="ss-step-num" aria-hidden="true">03</div>
        <div class="ss-step-body">
          <h3 class="ss-step-h">التنفيذ: تقني، محتوى، روابط</h3>
          <p class="ss-step-p">فريق متكامل ينفّذ الإصلاحات التقنية وينتج المحتوى ويُحسّن الصفحات ويبني الروابط.</p>
        </div>
      </div>
      <div class="ss-step">
        <div class="ss-step-num" aria-hidden="true">04</div>
        <div class="ss-step-body">
          <h3 class="ss-step-h">المراجعة وأولويات الشهر التالي</h3>
          <p class="ss-step-p">مراجعة شهرية للأداء مقابل الهدف، مع قائمة أولويات معدّلة بناءً على البيانات.</p>
        </div>
      </div>
    </div>
  </div>
</section><!-- /.ss-process -->


<!-- ══════════════════════════════════════════════════════════
     8. WHAT CLIENT RECEIVES — navy, 2-column checklist
══════════════════════════════════════════════════════════ -->
<section class="sec sec-navy ss-deliverables">
  <div class="wrap">
    <div class="sh c">
      <h2 class="h2 wh">ماذا يتلقى العميل؟</h2>
    </div>
    <div class="ss-deliv-list">
      <div class="ss-deliv-item">
        <svg class="ss-deliv-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        <span>تحليل تقني وخطة تنفيذ مرتّبة بالأولوية</span>
      </div>
      <div class="ss-deliv-item">
        <svg class="ss-deliv-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        <span>خريطة كلمات مرتبطة بصفحات موقعك</span>
      </div>
      <div class="ss-deliv-item">
        <svg class="ss-deliv-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        <span>خطة محتوى وكتابة ومراجعة SEO (حسب النطاق)</span>
      </div>
      <div class="ss-deliv-item">
        <svg class="ss-deliv-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        <span>بناء روابط خارجية من مواقع موثوقة (حسب النطاق)</span>
      </div>
      <div class="ss-deliv-item">
        <svg class="ss-deliv-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        <span>لوحة Looker Studio حية مع وصول دائم</span>
      </div>
      <div class="ss-deliv-item">
        <svg class="ss-deliv-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        <span>مراجعة شهرية للأداء وأولويات التنفيذ القادم</span>
      </div>
      <div class="ss-deliv-item">
        <svg class="ss-deliv-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        <span>استشارات تقنية وتنسيق مع المطوّر أو تنفيذ عبر SEO House</span>
      </div>
      <div class="ss-deliv-item">
        <svg class="ss-deliv-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        <span>استشارات SEO دورية وإجابة على الأسئلة التقنية</span>
      </div>
    </div>
  </div>
</section><!-- /.ss-deliverables -->


<!-- ══════════════════════════════════════════════════════════
     9. GOOGLE REVIEWS — exact homepage pattern
══════════════════════════════════════════════════════════ -->
<?php $_rev_sc = trim( sh_option( 'reviews_shortcode', '' ) ); if ( $_rev_sc ) : ?>
<section class="sec sec-white ss-reviews">
  <div class="wrap">
    <div class="sh c" style="margin-bottom:36px">
      <span class="tag">تقييمات العملاء</span>
      <h2 class="h2">ماذا يقول عملاؤنا؟</h2>
    </div>
    <div class="rev-plugin">
      <?php echo do_shortcode( wp_kses_post( $_rev_sc ) ); ?>
    </div>
  </div>
</section><!-- /.ss-reviews -->
<?php endif; ?>


<!-- ══════════════════════════════════════════════════════════
     10. FAQ — 6 approved questions, correct accordion HTML
══════════════════════════════════════════════════════════ -->
<section class="sec sec-<?php echo $_rev_sc ? 'surface' : 'white'; ?> ss-faq">
  <div class="wrap">
    <div class="sh c">
      <h2 class="h2">أسئلة شائعة</h2>
    </div>
    <div class="ss-faq-list">
      <?php
      $faqs = [
          [
              'q' => 'هل تنفذون التعديلات التقنية أم تقدمون التوصيات فقط؟',
              'a' => 'ننفذ الإصلاحات التقنية مباشرة بالتنسيق مع فريقك التقني أو عبر مطوّرنا — وفق نطاق الخدمة المتفق عليه. لا نكتفي بتسليم قائمة مشكلات.',
          ],
          [
              'q' => 'هل تشمل الخدمة كتابة المحتوى؟',
              'a' => 'يتضمن النطاق الكامل فريق كتابة متخصصاً ينتج المقالات وصفحات الخدمة وفق خطة الكلمات المستهدفة، مع مراجعة SEO لكل قطعة قبل النشر.',
          ],
          [
              'q' => 'كيف يتم بناء الروابط الخارجية؟',
              'a' => 'نبني روابط من مواقع موثوقة ذات صلة بمجالك — من خلال محتوى قابل للمشاركة والتواصل المباشر مع المواقع المناسبة.',
          ],
          [
              'q' => 'هل يمكن استهداف السعودية ومصر والإمارات؟',
              'a' => 'نعم، لدينا خبرة عملية في الأسواق الثلاثة ونخدم شركات في المملكة العربية السعودية ومصر والإمارات العربية المتحدة.',
          ],
          [
              'q' => 'كيف تتم متابعة النتائج والتقارير؟',
              'a' => 'لوحة Looker Studio حية تُظهر المراكز والنقرات والإيرادات، مع مراجعة شهرية تغطي ما تحقق وما هو مجدول للشهر التالي.',
          ],
          [
              'q' => 'ما تكلفة خدمات تحسين محركات البحث؟',
              'a' => 'تتراوح باقات السيو الشهرية بين 1,500 و7,000 ريال سعودي حسب نطاق الخدمة وحجم الموقع وتنافسية القطاع. نُرشّحك إلى النطاق المناسب بعد مراجعة وضع موقعك.',
          ],
      ];
      foreach ( $faqs as $faq ) : ?>
      <div class="faq-item">
        <button type="button" class="faq-q" aria-expanded="false">
          <span><?php echo esc_html( $faq['q'] ); ?></span>
          <span class="faq-icon" aria-hidden="true"><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><line x1="6" y1="1" x2="6" y2="11"/><line x1="1" y1="6" x2="11" y2="6"/></svg></span>
        </button>
        <div class="faq-a">
          <div class="faq-a-inner"><?php echo esc_html( $faq['a'] ); ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section><!-- /.ss-faq -->


<!-- ══════════════════════════════════════════════════════════
     11. CTA + CONTACT FORM — reuse existing theme component
══════════════════════════════════════════════════════════ -->
<section class="sec sec-navy ss-cta">
  <div class="wrap">
    <div class="ss-cta-layout">

      <div class="ss-cta-copy">
        <h2 class="ss-cta-h">ابدأ بمراجعة موقعك</h2>
        <p class="ss-cta-p">أخبرنا عن موقعك وهدفك — سنتواصل معك لتحديد موعد الاستشارة.</p>
        <div class="ss-cta-pts">
          <div class="ss-cta-pt">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="15" height="15"><polyline points="20 6 9 17 4 12"/></svg>
            تحليل تقني أولي للموقع
          </div>
          <div class="ss-cta-pt">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="15" height="15"><polyline points="20 6 9 17 4 12"/></svg>
            تحديد الفرص وأولويات التحسين
          </div>
          <div class="ss-cta-pt">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="15" height="15"><polyline points="20 6 9 17 4 12"/></svg>
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

</div><!-- /.svc-seo -->

<?php get_footer(); ?>
