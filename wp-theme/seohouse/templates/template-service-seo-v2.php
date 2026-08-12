<?php
/**
 * Template Name: SEO Service V2 Preview
 *
 * Preview / staging template — noindex, not for production.
 * To make live: duplicate this file, rename template, remove noindex filter.
 */

add_filter( 'wp_robots', function ( $robots ) {
    $robots['noindex']  = true;
    $robots['nofollow'] = true;
    return $robots;
} );

// ── Country landing page URLs ─────────────────────────────────────────
// Set these to the market-specific page URLs; leave '' to hide the CTA.
$url_sa  = sh_market_permalink( 'sa' );
$url_eg  = sh_market_permalink( 'eg' );
$url_uae = sh_market_permalink( 'uae' );

// ── Global page links ─────────────────────────────────────────────────
$pricing_url = sh_page_url( 'seo-pricing' ) ?: '';
$contact_url = sh_page_url( 'contact' )     ?: '#';

// ── Case study metadata (leave '' to hide the field entirely) ─────────
$cs1_sector = '';
$cs1_period = '';
$cs1_market = '';

$cs2_sector = '';
$cs2_period = '';

$cs4_sector = '';
$cs4_period = '';

// ── Image asset paths ─────────────────────────────────────────────────
// Three verified results only. GA4 card removed until original file available.
$img_dir  = get_template_directory_uri() . '/assets/images/seo-service/';
$img_hero = $img_dir . '01-hero-seohouse-office.png';
$img_cs1  = $img_dir . '02-case-organic-revenue-106274.png';
$img_cs2  = $img_dir . '03-case-gsc-clicks-12900.png';
$img_cs4  = $img_dir . '05-case-organic-ranking-law-firm-number-1.png';

get_header();
?>

<div class="svc-seo" dir="rtl">

  <!-- ══════════════════════════════════════════════════════════
       1. HERO
  ══════════════════════════════════════════════════════════ -->
  <section class="ss-hero">
    <div class="wrap ss-hero-inner">

      <div class="ss-hero-text">
        <span class="ss-eyebrow">خدمات تحسين محركات البحث للشركات والمتاجر</span>
        <h1 class="ss-h1">خدمات سيو متكاملة تحقق نموًا يمكن قياسه</h1>
        <p class="ss-hero-p">من السيو التقني وبحث الكلمات وكتابة المحتوى إلى بناء الروابط والاستشارات والدعم التقني — خدمة متكاملة لمواقع الشركات والمتاجر في السعودية ومصر والإمارات.</p>
        <div class="ss-hero-btns">
          <a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-p">اطلب مراجعة موقعك</a>
          <a href="#ss-services" class="btn btn-o">استكشف خدماتنا</a>
        </div>
      </div>

      <div class="ss-hero-visual">
        <img
          src="<?php echo esc_url( $img_hero ); ?>"
          alt="فريق SEO House يعمل على لوحة بيانات Looker Studio"
          width="640"
          height="480"
          loading="eager"
          fetchpriority="high"
        >
      </div>

    </div>
  </section><!-- /.ss-hero -->


  <!-- ══════════════════════════════════════════════════════════
       2. CLIENT LOGOS
  ══════════════════════════════════════════════════════════ -->
  <?php $clients = sh_get_clients(); if ( ! empty( $clients ) ) : ?>
  <section class="ss-clients">
    <div class="wrap">
      <p class="ss-clients-label">جهات وثقت في SEO House</p>
    </div>
    <div class="cl-marquee-outer">
      <div class="cl-marquee-track">
        <?php foreach ( $clients as $cl ) : ?>
          <div class="cl-item">
            <?php if ( ! empty( $cl['logo'] ) ) : ?>
              <img src="<?php echo esc_url( $cl['logo'] ); ?>" alt="<?php echo esc_attr( $cl['name'] ?? '' ); ?>" loading="lazy">
            <?php else : ?>
              <span><?php echo esc_html( $cl['name'] ?? '' ); ?></span>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
        <?php foreach ( $clients as $cl ) : ?>
          <div class="cl-item" aria-hidden="true">
            <?php if ( ! empty( $cl['logo'] ) ) : ?>
              <img src="<?php echo esc_url( $cl['logo'] ); ?>" alt="" loading="lazy">
            <?php else : ?>
              <span><?php echo esc_html( $cl['name'] ?? '' ); ?></span>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section><!-- /.ss-clients -->
  <?php endif; ?>


  <!-- ══════════════════════════════════════════════════════════
       3. SEO SERVICES — 8 cards, NO accordion
  ══════════════════════════════════════════════════════════ -->
  <section id="ss-services" class="sec sec-surface ss-services">
    <div class="wrap">
      <div class="sh c">
        <h2 class="h2">كل ما يحتاجه موقعك للنمو من البحث</h2>
      </div>

      <div class="ss-svc-grid">

        <div class="ss-svc-card">
          <div class="ss-svc-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="22" height="22"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
          </div>
          <h3 class="ss-svc-h">السيو التقني وإصلاح مشكلات الموقع</h3>
          <p class="ss-svc-p">تحليل الزحف والفهرسة وسرعة التحميل والبنية التقنية — وتنفيذ الإصلاحات فعلياً، لا مجرد تقارير.</p>
        </div>

        <div class="ss-svc-card">
          <div class="ss-svc-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="22" height="22"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
          </div>
          <h3 class="ss-svc-h">بحث الكلمات وتحسين الصفحات</h3>
          <p class="ss-svc-p">خرائط كلمات دقيقة مرتبطة ببنية موقعك، وتحسين العناوين والوصف والمحتوى لكل صفحة استراتيجية.</p>
        </div>

        <div class="ss-svc-card">
          <div class="ss-svc-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="22" height="22"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
          </div>
          <h3 class="ss-svc-h">كتابة المحتوى المتوافق مع SEO</h3>
          <p class="ss-svc-p">فريق كتابة متخصص ينتج محتوى مراجَعاً يستهدف نية البحث — ليس محتوى مولَّداً بلا إشراف.</p>
        </div>

        <div class="ss-svc-card">
          <div class="ss-svc-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="22" height="22"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
          </div>
          <h3 class="ss-svc-h">بناء الروابط الخارجية والسلطة الرقمية</h3>
          <p class="ss-svc-p">روابط من مواقع موثوقة ذات صلة بمجالك — تعزيز السلطة بطرق مستدامة تحترم معايير جوجل.</p>
        </div>

        <div class="ss-svc-card">
          <div class="ss-svc-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="22" height="22"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
          </div>
          <h3 class="ss-svc-h">استشارات SEO وبناء الاستراتيجية</h3>
          <p class="ss-svc-p">ورش استراتيجية وتدقيق شامل للوضع الراهن، وخارطة طريق واضحة لفريقك أو لتنفيذنا المشترك.</p>
        </div>

        <div class="ss-svc-card">
          <div class="ss-svc-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="22" height="22"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          </div>
          <h3 class="ss-svc-h">تحسين الظهور المحلي وGoogle Maps</h3>
          <p class="ss-svc-p">تهيئة ملف نشاطك في Google وإدارة الاتساق المحلي وبناء سلطة البحث الجغرافي في منطقتك.</p>
        </div>

        <div class="ss-svc-card">
          <div class="ss-svc-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="22" height="22"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
          </div>
          <h3 class="ss-svc-h">سيو المتاجر الإلكترونية</h3>
          <p class="ss-svc-p">تحسين صفحات المنتجات والفئات والبنية التقنية للمتاجر بهدف زيادة الزيارات العضوية والتحويلات.</p>
        </div>

        <div class="ss-svc-card">
          <div class="ss-svc-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="22" height="22"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
          </div>
          <h3 class="ss-svc-h">القياس والتقارير وتحليل التحويلات</h3>
          <p class="ss-svc-p">إعداد GA4 وSearch Console ولوحة Looker Studio — تعرف كيف تحوّل الزيارات العضوية إلى عملاء.</p>
        </div>

      </div><!-- /.ss-svc-grid -->
    </div>
  </section><!-- /.ss-services -->


  <!-- ══════════════════════════════════════════════════════════
       4. TARGET MARKETS
  ══════════════════════════════════════════════════════════ -->
  <section class="sec sec-white ss-markets">
    <div class="wrap">
      <div class="sh c">
        <h2 class="h2">خدمات سيو بخبرة في أسواق السعودية ومصر والإمارات</h2>
      </div>

      <div class="ss-markets-grid">

        <div class="ss-market-card">
          <div class="ss-market-flag">🇸🇦</div>
          <div class="ss-market-body">
            <h3 class="ss-market-h">المملكة العربية السعودية</h3>
            <p class="ss-market-p">خبرة في قطاعات التجزئة والعقار والتعليم والرعاية الصحية — مع فهم خصوصية السوق المحلي وسلوك المستخدم السعودي.</p>
          </div>
          <?php if ( $url_sa ) : ?>
            <a href="<?php echo esc_url( $url_sa ); ?>" class="ss-market-cta">
              استعرض خدمة السيو في السعودية
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="13" height="13"><path d="M15 18l-6-6 6-6"/></svg>
            </a>
          <?php endif; ?>
        </div>

        <div class="ss-market-card">
          <div class="ss-market-flag">🇪🇬</div>
          <div class="ss-market-body">
            <h3 class="ss-market-h">جمهورية مصر العربية</h3>
            <p class="ss-market-p">تجربة في التجارة الإلكترونية والخدمات والسوق المصري المتنامي — باستراتيجيات تراعي سلوك البحث باللهجة والفصحى.</p>
          </div>
          <?php if ( $url_eg ) : ?>
            <a href="<?php echo esc_url( $url_eg ); ?>" class="ss-market-cta">
              استعرض خدمة السيو في مصر
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="13" height="13"><path d="M15 18l-6-6 6-6"/></svg>
            </a>
          <?php endif; ?>
        </div>

        <div class="ss-market-card">
          <div class="ss-market-flag">🇦🇪</div>
          <div class="ss-market-body">
            <h3 class="ss-market-h">الإمارات العربية المتحدة</h3>
            <p class="ss-market-p">فهم بيئة البحث التنافسية في دبي وأبوظبي — خصوصاً في الضيافة والعقار والخدمات المهنية.</p>
          </div>
          <?php if ( $url_uae ) : ?>
            <a href="<?php echo esc_url( $url_uae ); ?>" class="ss-market-cta">
              استعرض خدمة السيو في الإمارات
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="13" height="13"><path d="M15 18l-6-6 6-6"/></svg>
            </a>
          <?php endif; ?>
        </div>

      </div><!-- /.ss-markets-grid -->
    </div>
  </section><!-- /.ss-markets -->


  <!-- ══════════════════════════════════════════════════════════
       5. RESULTS BENTO
  ══════════════════════════════════════════════════════════ -->
  <section class="sec sec-off ss-results">
    <div class="wrap">
      <div class="sh c">
        <h2 class="h2">نتائج حقيقية من مشروعات SEO House</h2>
      </div>

      <div class="ss-bento">

        <!-- ── Featured large card ── -->
        <div class="ss-bento-feat">
          <div class="ss-bento-img-wrap">
            <img
              src="<?php echo esc_url( $img_cs1 ); ?>"
              alt="تقرير Looker Studio يُظهر نمو الإيرادات العضوية من 19,956 ريال إلى 106,274 ريال"
              loading="lazy"
            >
          </div>
          <div class="ss-bento-info">
            <div class="ss-metric" dir="ltr">
              <span class="ss-metric-from">SAR 19,956</span>
              <span class="ss-metric-arrow">→</span>
              <span class="ss-metric-to">SAR 106,274</span>
            </div>
            <p class="ss-bento-desc">نمو الإيرادات العضوية <strong dir="ltr">+432%</strong></p>
            <?php if ( $cs1_sector || $cs1_market || $cs1_period ) : ?>
            <div class="ss-bento-tags">
              <?php if ( $cs1_sector ) : ?><span><?php echo esc_html( $cs1_sector ); ?></span><?php endif; ?>
              <?php if ( $cs1_market ) : ?><span><?php echo esc_html( $cs1_market ); ?></span><?php endif; ?>
              <?php if ( $cs1_period ) : ?><span><?php echo esc_html( $cs1_period ); ?></span><?php endif; ?>
            </div>
            <?php endif; ?>
          </div>
        </div><!-- /.ss-bento-feat -->

        <!-- ── Three supporting cards ── -->
        <div class="ss-bento-side">

          <div class="ss-bento-card">
            <div class="ss-bento-img-wrap ss-bento-img-sm">
              <img
                src="<?php echo esc_url( $img_cs2 ); ?>"
                alt="Google Search Console يُظهر نمو النقرات العضوية من 825 إلى 12,900 نقرة شهرياً"
                loading="lazy"
              >
            </div>
            <div class="ss-bento-info">
              <div class="ss-metric" dir="ltr">
                <span class="ss-metric-from">825</span>
                <span class="ss-metric-arrow">→</span>
                <span class="ss-metric-to">12,900</span>
              </div>
              <p class="ss-bento-desc">نمو النقرات العضوية الشهرية</p>
              <?php if ( $cs2_sector || $cs2_period ) : ?>
              <div class="ss-bento-tags">
                <?php if ( $cs2_sector ) : ?><span><?php echo esc_html( $cs2_sector ); ?></span><?php endif; ?>
                <?php if ( $cs2_period ) : ?><span><?php echo esc_html( $cs2_period ); ?></span><?php endif; ?>
              </div>
              <?php endif; ?>
            </div>
          </div><!-- /.ss-bento-card -->

          <div class="ss-bento-card">
            <div class="ss-bento-img-wrap ss-bento-img-sm">
              <img
                src="<?php echo esc_url( $img_cs4 ); ?>"
                alt="نتائج البحث العضوي على Google تُظهر مكتب المحاماة في المركز الأول لكلمة «مكتب محاماة»"
                loading="lazy"
              >
            </div>
            <div class="ss-bento-info">
              <p class="ss-bento-desc ss-bento-ranking">الوصول إلى المركز الأول في النتائج العضوية على كلمة «مكتب محاماة»</p>
              <?php if ( $cs4_sector || $cs4_period ) : ?>
              <div class="ss-bento-tags">
                <?php if ( $cs4_sector ) : ?><span><?php echo esc_html( $cs4_sector ); ?></span><?php endif; ?>
                <?php if ( $cs4_period ) : ?><span><?php echo esc_html( $cs4_period ); ?></span><?php endif; ?>
              </div>
              <?php endif; ?>
            </div>
          </div><!-- /.ss-bento-card -->

        </div><!-- /.ss-bento-side -->

      </div><!-- /.ss-bento -->
    </div>
  </section><!-- /.ss-results -->


  <!-- ══════════════════════════════════════════════════════════
       6. WHY SEO HOUSE
  ══════════════════════════════════════════════════════════ -->
  <section class="sec sec-navy ss-why">
    <div class="wrap">
      <div class="sh c">
        <h2 class="h2 wh">لماذا تعمل الشركات مع SEO House؟</h2>
      </div>

      <div class="ss-why-grid">

        <div class="ss-why-card">
          <div class="ss-why-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="22" height="22"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
          </div>
          <h3 class="ss-why-h">تنفيذ لا توصيات فقط</h3>
          <p class="ss-why-p">فريقنا ينفّذ الإصلاحات التقنية والمحتوى والروابط — لا نكتفي بتسليم تقرير وانتهى الأمر.</p>
        </div>

        <div class="ss-why-card">
          <div class="ss-why-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="22" height="22"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
          </div>
          <h3 class="ss-why-h">كتابة محتوى متخصصة ومراجعة دقيقة</h3>
          <p class="ss-why-p">كتّاب متخصصون في المحتوى العربي مع مراجعة SEO لكل قطعة — لا محتوى مولّداً بدون إشراف بشري.</p>
        </div>

        <div class="ss-why-card">
          <div class="ss-why-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="22" height="22"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8m-4-4v4"/></svg>
          </div>
          <h3 class="ss-why-h">دعم تقني ومطوّر مدمج</h3>
          <p class="ss-why-p">لا تحتاج فريقاً تقنياً منفصلاً — مهندسونا ينفّذون إصلاحات الزحف والسرعة والبنية مباشرة على موقعك.</p>
        </div>

        <div class="ss-why-card">
          <div class="ss-why-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="22" height="22"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
          </div>
          <h3 class="ss-why-h">تقارير حية وأولويات شفافة</h3>
          <p class="ss-why-p">لوحة Looker Studio متاحة دائماً — تعرف ما تم شهرياً وما هو مجدول للشهر القادم بدون غموض.</p>
        </div>

      </div><!-- /.ss-why-grid -->
    </div>
  </section><!-- /.ss-why -->


  <!-- ══════════════════════════════════════════════════════════
       7. HOW WE WORK — compact 4-step timeline
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
            <h3 class="ss-step-h">فهم النشاط وتثبيت القياس</h3>
            <p class="ss-step-p">جلسة استراتيجية لفهم أهدافك، ثم نهيّئ GA4 وSearch Console ولوحة Looker Studio لتتبع النتائج منذ اليوم الأول.</p>
          </div>
        </div>

        <div class="ss-step">
          <div class="ss-step-num" aria-hidden="true">02</div>
          <div class="ss-step-body">
            <h3 class="ss-step-h">التدقيق وخرائط الكلمات وخطة الـ 90 يوماً</h3>
            <p class="ss-step-p">تدقيق تقني وتنافسي شامل، تليه خريطة كلمات مرتبطة بصفحاتك، وخطة عمل واضحة للربع الأول.</p>
          </div>
        </div>

        <div class="ss-step">
          <div class="ss-step-num" aria-hidden="true">03</div>
          <div class="ss-step-body">
            <h3 class="ss-step-h">التنفيذ التقني والمحتوى والصفحات والروابط</h3>
            <p class="ss-step-p">فريق متكامل ينفّذ الإصلاحات التقنية وينتج المحتوى ويُحسّن الصفحات ويبني الروابط في آن واحد.</p>
          </div>
        </div>

        <div class="ss-step">
          <div class="ss-step-num" aria-hidden="true">04</div>
          <div class="ss-step-body">
            <h3 class="ss-step-h">المراجعة والأداء وأولويات الشهر القادم</h3>
            <p class="ss-step-p">مراجعة شهرية لما تحقق مقابل الهدف، مع قائمة أولويات معدّلة للشهر التالي بناءً على البيانات.</p>
          </div>
        </div>

      </div><!-- /.ss-steps -->
    </div>
  </section><!-- /.ss-process -->


  <!-- ══════════════════════════════════════════════════════════
       8. WHAT CLIENT RECEIVES — 4 compact cards
  ══════════════════════════════════════════════════════════ -->
  <section class="sec sec-surface ss-deliverables">
    <div class="wrap">
      <div class="sh c">
        <h2 class="h2">ماذا يتلقى العميل؟</h2>
      </div>

      <div class="ss-deliv-grid">

        <div class="ss-deliv-card">
          <div class="ss-deliv-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" width="22" height="22"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="13" y2="16"/></svg>
          </div>
          <h3 class="ss-deliv-h">خريطة الكلمات والصفحات</h3>
          <p class="ss-deliv-p">ربط كل كلمة مستهدفة بالصفحة الصحيحة — الأساس الذي يبنى عليه التحسين التقني والمحتوى.</p>
        </div>

        <div class="ss-deliv-card">
          <div class="ss-deliv-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" width="22" height="22"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
          </div>
          <h3 class="ss-deliv-h">التدقيق التقني وخطة التنفيذ</h3>
          <p class="ss-deliv-p">تقرير مفصّل بكل مشكلة وخطة عمل مرتّبة بالأولوية مع جدول تسليم واضح.</p>
        </div>

        <div class="ss-deliv-card">
          <div class="ss-deliv-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" width="22" height="22"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          </div>
          <h3 class="ss-deliv-h">خطة المحتوى</h3>
          <p class="ss-deliv-p">جدول مقالات وصفحات مبني على الكلمات المستهدفة وفجوات المحتوى الحالية في موقعك.</p>
        </div>

        <div class="ss-deliv-card">
          <div class="ss-deliv-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" width="22" height="22"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
          </div>
          <h3 class="ss-deliv-h">لوحة Looker Studio الحية</h3>
          <p class="ss-deliv-p">وصول دائم لتقرير التحليلات — المراكز والنقرات والإيرادات محدّثة تلقائياً في الوقت الفعلي.</p>
        </div>

      </div><!-- /.ss-deliv-grid -->
    </div>
  </section><!-- /.ss-deliverables -->


  <!-- ══════════════════════════════════════════════════════════
       9. GOOGLE REVIEWS
  ══════════════════════════════════════════════════════════ -->
  <?php $reviews_sc = sh_option( 'reviews_shortcode' ); if ( $reviews_sc ) : ?>
  <section class="sec sec-white ss-reviews">
    <div class="wrap">
      <div class="sh c">
        <h2 class="h2">ماذا يقول عملاؤنا عن العمل معنا؟</h2>
      </div>
      <div class="ss-reviews-inner">
        <?php echo do_shortcode( $reviews_sc ); ?>
      </div>
    </div>
  </section><!-- /.ss-reviews -->
  <?php endif; ?>


  <!-- ══════════════════════════════════════════════════════════
       10. FAQ
  ══════════════════════════════════════════════════════════ -->
  <section class="sec sec-surface ss-faq">
    <div class="wrap">
      <div class="sh c">
        <h2 class="h2">أسئلة شائعة</h2>
      </div>

      <div class="ss-faq-list">

        <div class="faq-item">
          <button type="button" class="faq-q">
            <span>متى تبدأ النتائج تظهر؟</span>
            <span class="faq-icon" aria-hidden="true"><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><line x1="6" y1="1" x2="6" y2="11"/><line x1="1" y1="6" x2="11" y2="6"/></svg></span>
          </button>
          <div class="faq-a">
            <p>السيو استثمار متراكم — التحسينات التقنية والمحتوى تبدأ في التأثير خلال 60–90 يوماً، بينما تتضح النتائج الكاملة في 4–9 أشهر حسب تنافسية القطاع وعمر الموقع.</p>
          </div>
        </div>

        <div class="faq-item">
          <button type="button" class="faq-q">
            <span>هل تُنفّذون الإصلاحات التقنية أم تكتفون بتقديم التقرير؟</span>
            <span class="faq-icon" aria-hidden="true"><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><line x1="6" y1="1" x2="6" y2="11"/><line x1="1" y1="6" x2="11" y2="6"/></svg></span>
          </button>
          <div class="faq-a">
            <p>ننفّذ الإصلاحات مباشرة بالتنسيق مع فريقك التقني أو عبر مطوّرنا الداخلي — لا نكتفي بتسليم قائمة المشكلات.</p>
          </div>
        </div>

        <div class="faq-item">
          <button type="button" class="faq-q">
            <span>هل تشمل الخدمة كتابة محتوى؟</span>
            <span class="faq-icon" aria-hidden="true"><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><line x1="6" y1="1" x2="6" y2="11"/><line x1="1" y1="6" x2="11" y2="6"/></svg></span>
          </button>
          <div class="faq-a">
            <p>نعم، الخدمة المتكاملة تشمل فريق كتابة متخصصاً ينتج المقالات وصفحات الخدمة وفق خطة الكلمات المستهدفة، مع مراجعة SEO كاملة قبل النشر.</p>
          </div>
        </div>

        <div class="faq-item">
          <button type="button" class="faq-q">
            <span>كيف تتعاملون مع بناء الروابط؟</span>
            <span class="faq-icon" aria-hidden="true"><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><line x1="6" y1="1" x2="6" y2="11"/><line x1="1" y1="6" x2="11" y2="6"/></svg></span>
          </button>
          <div class="faq-a">
            <p>نبني روابط من مواقع عربية موثوقة ذات صلة بمجالك — من خلال محتوى قابل للمشاركة والتواصل مع المواقع ذات الصلة، مع تجنب الروابط المدفوعة التي تُخالف معايير جوجل.</p>
          </div>
        </div>

        <div class="faq-item">
          <button type="button" class="faq-q">
            <span>هل الخدمة متاحة في السعودية ومصر والإمارات؟</span>
            <span class="faq-icon" aria-hidden="true"><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><line x1="6" y1="1" x2="6" y2="11"/><line x1="1" y1="6" x2="11" y2="6"/></svg></span>
          </button>
          <div class="faq-a">
            <p>نعم، لدينا خبرة في الأسواق الثلاثة — نخدم شركات في المملكة العربية السعودية ومصر والإمارات، مع معرفة بخصوصية كل سوق وسلوك المستخدم المحلي.</p>
          </div>
        </div>

        <div class="faq-item">
          <button type="button" class="faq-q">
            <span>ما نطاق أسعار خدمات السيو؟</span>
            <span class="faq-icon" aria-hidden="true"><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><line x1="6" y1="1" x2="6" y2="11"/><line x1="1" y1="6" x2="11" y2="6"/></svg></span>
          </button>
          <div class="faq-a">
            <p>تتراوح باقات السيو الشهرية بين 1,500 و7,000 ريال سعودي حسب نطاق الخدمة وحجم الموقع. نُرشّحك إلى الباقة الأنسب بعد مراجعة موقعك مجاناً.</p>
          </div>
        </div>

      </div><!-- /.ss-faq-list -->
    </div>
  </section><!-- /.ss-faq -->


  <!-- ══════════════════════════════════════════════════════════
       11. FINAL CTA — AJAX contact form
  ══════════════════════════════════════════════════════════ -->
  <section class="sec sec-navy ss-cta">
    <div class="wrap">
      <div class="ss-cta-layout">

        <div class="ss-cta-copy">
          <h2 class="ss-cta-h">ابدأ بمراجعة موقعك وتحديد فرص النمو</h2>
          <p class="ss-cta-p">أخبرنا عن موقعك وهدفك — وسنعود بمراجعة أولية مجانية خلال يوم عمل.</p>
        </div>

        <div class="form-card ss-form-card" id="ss-form-wrap">
          <input type="hidden" id="seoSvcAjaxUrl" value="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
          <input type="hidden" id="seoSvcNonce"   value="<?php echo esc_attr( wp_create_nonce( 'seohouse_nonce' ) ); ?>">

          <form id="ss-contact-form" class="ss-form" novalidate>
            <div class="form-row">
              <div class="form-group">
                <label for="ss-name">الاسم الكامل</label>
                <input type="text"  id="ss-name"    name="name"    class="form-input" required placeholder="أحمد العمري">
              </div>
              <div class="form-group">
                <label for="ss-email">البريد الإلكتروني</label>
                <input type="email" id="ss-email"   name="email"   class="form-input" required placeholder="ahmed@company.com">
              </div>
            </div>
            <div class="form-group">
              <label for="ss-website">رابط الموقع</label>
              <input type="url"  id="ss-website"  name="website"  class="form-input" placeholder="https://yoursite.com">
            </div>
            <div class="form-group">
              <label for="ss-msg">هدفك من السيو</label>
              <textarea id="ss-msg" name="message" class="form-textarea" rows="3" placeholder="مثال: زيادة المبيعات من البحث العضوي في السعودية"></textarea>
            </div>
            <div class="form-submit">
              <button type="submit" class="btn btn-p ss-submit-btn">
                <span class="ss-btn-text">اطلب المراجعة المجانية</span>
              </button>
            </div>
            <div class="ss-form-feedback" role="alert" aria-live="polite"></div>
          </form>

          <div class="ss-form-success" id="ss-form-success" style="display:none" role="status">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="40" height="40"><circle cx="12" cy="12" r="10"/><polyline points="16 8 10 14 7 11"/></svg>
            <p>شكراً! سنتواصل معك خلال يوم عمل.</p>
          </div>
        </div><!-- /.form-card -->

      </div><!-- /.ss-cta-layout -->
    </div>
  </section><!-- /.ss-cta -->

</div><!-- /.svc-seo -->

<script>
(function () {
  var form    = document.getElementById('ss-contact-form');
  var wrap    = document.getElementById('ss-form-wrap');
  var success = document.getElementById('ss-form-success');
  var fbk     = form ? form.querySelector('.ss-form-feedback') : null;
  if (!form) return;

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    var btn = form.querySelector('.ss-submit-btn');
    if (btn) { btn.disabled = true; btn.style.opacity = '0.65'; }
    if (fbk) { fbk.textContent = ''; fbk.className = 'ss-form-feedback'; }

    var data = new FormData(form);
    data.append('action', 'sh_contact');
    data.append('nonce',  document.getElementById('seoSvcNonce').value);

    fetch(document.getElementById('seoSvcAjaxUrl').value, { method: 'POST', body: data })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        if (res.success) {
          form.style.display = 'none';
          if (success) success.style.display = 'flex';
        } else {
          var msg = (res.data && res.data.msg) ? res.data.msg : 'حدث خطأ، يرجى المحاولة مرة أخرى';
          if (fbk) { fbk.textContent = msg; fbk.className = 'ss-form-feedback ss-form-err'; }
          if (btn) { btn.disabled = false; btn.style.opacity = ''; }
        }
      })
      .catch(function () {
        if (fbk) { fbk.textContent = 'حدث خطأ في الاتصال، يرجى المحاولة مرة أخرى'; fbk.className = 'ss-form-feedback ss-form-err'; }
        if (btn) { btn.disabled = false; btn.style.opacity = ''; }
      });
  });
})();
</script>

<?php get_footer(); ?>
