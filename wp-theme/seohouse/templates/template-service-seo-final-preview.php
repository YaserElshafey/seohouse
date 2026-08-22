<?php
/**
 * Template Name: SEO Service Final Preview
 *
 * Preview-only template for the new Arabic SEO service page.
 * Do not set as live template until approved.
 *
 * Source: seo-service-final.html (SHA-256 f90c171af16a7d3f4660becf0aad2980846bbfd64cdb9b1377b218ef13eda319)
 * Assets scoped under .svc-seo-final
 */

get_header();

// ── Internal URLs ─────────────────────────────────────────────────────
$contact_url  = sh_page_url( 'contact' );
$seo_url      = sh_safe_url( 'services/seo' );
$content_url  = sh_safe_url( 'services/content' );
$backlinks_url = sh_safe_url( 'services/backlinks' );
$consulting_url = sh_safe_url( 'services/consulting' );
$stores_url   = sh_safe_url( 'services/seo/stores-seo' );
$mkt_sa       = sh_market_permalink( 'sa', 'services/seo/sa' );
$mkt_eg       = sh_market_permalink( 'eg', 'services/seo/eg' );
$mkt_ae       = sh_market_permalink( 'ae', 'services/seo/ae' );

// ── Evidence images ───────────────────────────────────────────────────
$img_base     = get_template_directory_uri() . '/assets/images/seo-service/';
$img_revenue  = $img_base . 'case-revenue.png';
$img_clicks   = $img_base . 'case-clicks.png';
$img_lawfirm  = $img_base . 'case-law-firm.png';

// ── Reviews shortcode ─────────────────────────────────────────────────
$_rev_sc = trim( sh_option( 'reviews_shortcode', '' ) );

// ── Team members ──────────────────────────────────────────────────────
$team_q = new WP_Query( [
    'post_type'      => 'team_member',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'meta_query'     => [
        'relation' => 'OR',
        [ 'key' => 'is_visible', 'value' => '1', 'compare' => '=' ],
        [ 'key' => 'is_visible', 'compare' => 'NOT EXISTS' ],
    ],
] );
$col1 = [];
$col2 = [];
if ( $team_q->have_posts() ) {
    $idx = 0;
    while ( $team_q->have_posts() ) {
        $team_q->the_post();
        $pid   = get_the_ID();
        $thumb = has_post_thumbnail() ? get_the_post_thumbnail_url( $pid, 'seohouse-team' ) : '';
        $name  = get_the_title();
        $initials = function_exists( 'sh_initials' ) ? sh_initials( $name ) : mb_substr( $name, 0, 2 );
        $color = sh_field( 'avatar_color', $pid, 'linear-gradient(140deg,#0b1240,rgba(30,46,245,.25))' );
        $item  = compact( 'thumb', 'name', 'initials', 'color' );
        if ( $idx % 2 === 0 ) { $col1[] = $item; } else { $col2[] = $item; }
        $idx++;
    }
    wp_reset_postdata();
}
// Fallback placeholders if no team members
if ( empty( $col1 ) ) {
    $col1 = [
        [ 'thumb' => '', 'name' => 'متخصص سيو', 'initials' => 'م.س', 'color' => 'linear-gradient(140deg,#0b1240,rgba(30,46,245,.25))' ],
        [ 'thumb' => '', 'name' => 'محلل أداء',  'initials' => 'م.أ', 'color' => 'linear-gradient(140deg,#0b1240,rgba(16,185,129,.2))' ],
    ];
    $col2 = [
        [ 'thumb' => '', 'name' => 'كاتب محتوى', 'initials' => 'ك.م', 'color' => 'linear-gradient(140deg,#0e1750,rgba(123,144,255,.2))' ],
        [ 'thumb' => '', 'name' => 'مطور تقني',  'initials' => 'م.ت', 'color' => 'linear-gradient(140deg,#070d32,rgba(30,46,245,.35))' ],
    ];
}

// Helper: render strip items (duplicated for seamless scroll)
function sf_strip_col( array $items ): void {
    $all = array_merge( $items, $items );
    foreach ( $all as $m ) {
        echo '<div class="strip-ph">';
        if ( ! empty( $m['thumb'] ) ) {
            echo '<img src="' . esc_url( $m['thumb'] ) . '" alt="' . esc_attr( $m['name'] ) . '" loading="lazy">';
        } else {
            echo '<div class="strip-initials" style="background:' . esc_attr( $m['color'] ) . '">';
            echo '<span>' . esc_html( $m['initials'] ) . '</span>';
            echo '</div>';
        }
        echo '</div>';
    }
}
?>
<main class="svc-seo-final" dir="rtl">

<!-- ══ HERO ══ -->
<section id="hero">
  <div class="sf-glow g1"></div>
  <div class="sf-glow g2"></div>
  <div class="dots"></div>
  <div class="sf-wrap">
    <div class="hero-grid">
      <div>
        <span class="hero-eyebrow"><span class="d"></span>خدمات تحسين محركات البحث</span>
        <h1 class="hero-h1">نضع موقعك أمام العملاء الذين <em>يبحثون عمّا تقدمه</em></h1>
        <p class="hero-lead">نراجع موقعك، نصلح المشاكل التقنية، ونحسّن الصفحات والمحتوى والروابط بحسب ما يبحث عنه عملاؤك.<br><br>وتتابع معنا ما يتم تنفيذه والنتائج التي تظهر خطوة بخطوة.</p>
        <div class="hero-btns">
          <a href="<?php echo esc_url( $contact_url ); ?>" class="sf-btn sf-btn-p lg">اطلب مراجعة موقعك</a>
          <a href="#pillars" class="sf-btn sf-btn-gh lg">شاهد كيف نعمل</a>
        </div>
      </div>
      <!-- Search→Growth visual -->
      <div class="sg">
        <div class="sg-stage">
          <div class="sg-connect"></div>
          <!-- Step 1: Search -->
          <div class="sg-step">
            <div class="sg-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg></div>
            <div class="sg-card">
              <div class="sg-lbl">١ — العميل يبحث</div>
              <div class="sg-searchbar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg>
                <span class="sg-typed" id="sfTyped"></span><span class="sg-caret"></span>
              </div>
            </div>
          </div>
          <!-- Step 2: Result -->
          <div class="sg-step">
            <div class="sg-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h10"/></svg></div>
            <div class="sg-card" style="position:relative">
              <div class="sg-lbl">٢ — موقعك يظهر في النتائج</div>
              <div class="sr-result">
                <span class="badge">نتيجة عضوية</span>
                <span class="u">yoursite.com ›</span>
                <span class="t">اسم شركتك — الحل الذي يبحث عنه</span>
              </div>
            </div>
          </div>
          <!-- Step 3: Interaction -->
          <div class="sg-step">
            <div class="sg-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg></div>
            <div class="sg-card">
              <div class="sg-lbl">٣ — يزور ويتفاعل</div>
              <div class="sg-outcome">
                <div class="sg-out-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.8 19.8 0 01-8.63-3.07 19.5 19.5 0 01-6-6A19.8 19.8 0 012.12 4.18 2 2 0 014.11 2h3a2 2 0 012 1.72c.13.96.36 1.9.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0122 16.92z"/></svg><span>مكالمة</span></div>
                <div class="sg-out-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v16H4z"/><path d="M4 8l8 5 8-5"/></svg><span>طلب</span></div>
              </div>
            </div>
          </div>
          <!-- Step 4: Result -->
          <div class="sg-step">
            <div class="sg-ico" style="background:rgba(16,185,129,.2);border-color:rgba(16,185,129,.4)"><svg viewBox="0 0 24 24" fill="none" stroke="#5eead4" stroke-width="2"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></div>
            <div class="sg-card" style="background:rgba(16,185,129,.1);border-color:rgba(16,185,129,.28)">
              <div class="sg-lbl" style="color:#7fe6c8">٤ — النتيجة</div>
              <div style="font-size:14px;font-weight:800;color:#c9f5e5">طلب أو عملية بيع فعلية</div>
            </div>
          </div>
        </div>
      </div><!-- /.sg -->
    </div><!-- /.hero-grid -->
  </div><!-- /.sf-wrap -->
  <div class="hero-fade"></div>
</section><!-- /#hero -->

<!-- ══ TRUST STRIP ══ -->
<section id="trust">
  <p class="trust-lbl">نعمل بأدوات ومنصّات موثوقة لقياس كل خطوة</p>
  <div class="trust-marquee">
    <div class="trust-track">
      <div class="trust-chip"><svg viewBox="0 0 24 24"><path fill="#4285F4" d="M22 12c0-.7-.06-1.35-.17-2H12v3.8h5.6a4.8 4.8 0 01-2.08 3.15v2.6h3.36C20.8 17.7 22 15.1 22 12z"/><path fill="#34A853" d="M12 22c2.7 0 4.96-.9 6.62-2.42l-3.36-2.6c-.93.62-2.12.99-3.26.99-2.5 0-4.62-1.7-5.38-3.97H3.15v2.68A10 10 0 0012 22z"/><path fill="#FBBC05" d="M6.62 14c-.2-.6-.31-1.24-.31-1.9s.11-1.3.31-1.9V7.52H3.15A10 10 0 002 12c0 1.62.39 3.15 1.15 4.48z"/><path fill="#EA4335" d="M12 6.13c1.47 0 2.78.5 3.82 1.5l2.86-2.86A10 10 0 003.15 7.52l3.47 2.68C7.38 7.83 9.5 6.13 12 6.13z"/></svg><span>Google Search</span></div>
      <div class="trust-chip"><svg viewBox="0 0 24 24" fill="none" stroke="#458CF7" stroke-width="2"><path d="M4 18V9M9 18V5M14 18v-6M19 18v-9"/></svg><span>Search Console</span></div>
      <div class="trust-chip"><svg viewBox="0 0 24 24" fill="none" stroke="#F9AB00" stroke-width="2"><rect x="3" y="12" width="4" height="8" rx="1"/><rect x="10" y="7" width="4" height="13" rx="1"/><rect x="17" y="3" width="4" height="17" rx="1"/></svg><span>Looker Studio</span></div>
      <div class="trust-chip"><svg viewBox="0 0 24 24" fill="none" stroke="#E8710A" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg><span>Google Analytics 4</span></div>
      <div class="trust-chip"><svg viewBox="0 0 24 24" fill="none" stroke="#1e2ef5" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><path d="M3 6h18M16 10a4 4 0 01-8 0"/></svg><span>سلة</span></div>
      <div class="trust-chip"><svg viewBox="0 0 24 24" fill="none" stroke="#1e2ef5" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><path d="M9 22V12h6v10"/></svg><span>زد</span></div>
      <div class="trust-chip"><svg viewBox="0 0 24 24" fill="none" stroke="#7F54B3" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M6 9h4M6 13h8"/></svg><span>WooCommerce</span></div>
      <div class="trust-chip"><svg viewBox="0 0 24 24" fill="none" stroke="#95BF47" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/></svg><span>Shopify</span></div>
      <!-- Duplicate for seamless loop -->
      <div class="trust-chip"><svg viewBox="0 0 24 24"><path fill="#4285F4" d="M22 12c0-.7-.06-1.35-.17-2H12v3.8h5.6a4.8 4.8 0 01-2.08 3.15v2.6h3.36C20.8 17.7 22 15.1 22 12z"/><path fill="#34A853" d="M12 22c2.7 0 4.96-.9 6.62-2.42l-3.36-2.6c-.93.62-2.12.99-3.26.99-2.5 0-4.62-1.7-5.38-3.97H3.15v2.68A10 10 0 0012 22z"/><path fill="#FBBC05" d="M6.62 14c-.2-.6-.31-1.24-.31-1.9s.11-1.3.31-1.9V7.52H3.15A10 10 0 002 12c0 1.62.39 3.15 1.15 4.48z"/><path fill="#EA4335" d="M12 6.13c1.47 0 2.78.5 3.82 1.5l2.86-2.86A10 10 0 003.15 7.52l3.47 2.68C7.38 7.83 9.5 6.13 12 6.13z"/></svg><span>Google Search</span></div>
      <div class="trust-chip"><svg viewBox="0 0 24 24" fill="none" stroke="#458CF7" stroke-width="2"><path d="M4 18V9M9 18V5M14 18v-6M19 18v-9"/></svg><span>Search Console</span></div>
      <div class="trust-chip"><svg viewBox="0 0 24 24" fill="none" stroke="#F9AB00" stroke-width="2"><rect x="3" y="12" width="4" height="8" rx="1"/><rect x="10" y="7" width="4" height="13" rx="1"/><rect x="17" y="3" width="4" height="17" rx="1"/></svg><span>Looker Studio</span></div>
      <div class="trust-chip"><svg viewBox="0 0 24 24" fill="none" stroke="#E8710A" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg><span>Google Analytics 4</span></div>
      <div class="trust-chip"><svg viewBox="0 0 24 24" fill="none" stroke="#1e2ef5" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><path d="M3 6h18M16 10a4 4 0 01-8 0"/></svg><span>سلة</span></div>
      <div class="trust-chip"><svg viewBox="0 0 24 24" fill="none" stroke="#1e2ef5" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><path d="M9 22V12h6v10"/></svg><span>زد</span></div>
      <div class="trust-chip"><svg viewBox="0 0 24 24" fill="none" stroke="#7F54B3" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M6 9h4M6 13h8"/></svg><span>WooCommerce</span></div>
      <div class="trust-chip"><svg viewBox="0 0 24 24" fill="none" stroke="#95BF47" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/></svg><span>Shopify</span></div>
    </div>
  </div>
  <p class="trust-note">كل نتيجة نعرضها لاحقًا مصدرها هذه الأدوات — بدون أرقام مُصطنعة</p>
</section><!-- /#trust -->

<!-- ══ FIVE PILLARS ══ -->
<section id="pillars" class="sf-sec">
  <div class="sf-wrap">
    <div class="pil-layout">
      <div class="pil-intro">
        <div class="num">خدمات السيو</div>
        <h2 class="sf-h2">خمس خدمات تدفع نمو موقعك من البحث</h2>
        <p class="sf-bod" style="margin-top:14px">من تهيئة الموقع تقنيًا إلى بناء المحتوى والسلطة وقياس الأداء — كل جزء له دور واضح في النتيجة، ويعمل مع البقية كمنظومة واحدة.</p>
        <div class="pil-count" id="pilCount">
          <div class="pil-count-item" data-i="0"><span class="n">١</span><span>السيو التقني</span></div>
          <div class="pil-count-item" data-i="1"><span class="n">٢</span><span>السيو الداخلي</span></div>
          <div class="pil-count-item" data-i="2"><span class="n">٣</span><span>كتابة المحتوى</span></div>
          <div class="pil-count-item" data-i="3"><span class="n">٤</span><span>السيو الخارجي</span></div>
          <div class="pil-count-item" data-i="4"><span class="n">٥</span><span>الاستشارات وتحليل الأداء</span></div>
        </div>
      </div>
      <div class="pil-scenes">
        <!-- Technical SEO -->
        <div class="scene" id="scene0" data-i="0">
          <div class="scene-n">٠١ — السيو التقني</div>
          <h3>نعالج المشاكل التقنية التي تعطل ظهور موقعك</h3>
          <p>نراجع الزحف والفهرسة والسرعة وبنية الموقع. ثم ننفّذ الإصلاحات المطلوبة بالتعاون مع المطور عند الحاجة.</p>
          <div class="viz">
            <div class="viz-row"><div class="viz-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h10"/></svg></div><div class="lab">الزحف والفهرسة</div><div class="viz-check">✓</div></div>
            <div class="viz-row"><div class="viz-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h7v8l10-12h-7z"/></svg></div><div class="lab">سرعة التحميل</div><div class="viz-bar"><i style="width:82%"></i></div></div>
            <div class="viz-row"><div class="viz-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg></div><div class="lab">البيانات المنظّمة Schema</div><div class="viz-check">✓</div></div>
            <div class="viz-row"><div class="viz-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div><div class="lab">الأمان والبنية السليمة</div><div class="viz-check">✓</div></div>
          </div>
          <div class="scene-do"><span>تدقيق تقني</span><span>Core Web Vitals</span><span>Sitemap &amp; Robots</span><span>معالجة أخطاء الفهرسة</span></div>
          <?php if ( $seo_url ) : ?>
          <a href="<?php echo esc_url( $seo_url ); ?>" class="scene-link">تفاصيل السيو التقني <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg></a>
          <?php endif; ?>
        </div>
        <!-- On-page SEO -->
        <div class="scene" id="scene1" data-i="1">
          <div class="scene-n">٠٢ — السيو الداخلي</div>
          <h3>نحسّن صفحاتك لتناسب ما يبحث عنه العميل</h3>
          <p>نراجع العناوين والمحتوى والروابط الداخلية. ونرتب كل صفحة حول نية البحث والخطوة التي نريد من الزائر اتخاذها.</p>
          <div class="viz">
            <div class="viz-page">
              <div class="viz-line title"></div>
              <div class="viz-tags"><b>H1</b><b>نية البحث</b></div>
              <div class="viz-line h"></div>
              <div class="viz-line txt"></div>
              <div class="viz-line txt2"></div>
              <div class="viz-tags"><b>رابط داخلي</b><b>رابط داخلي</b><b>CTA — تحويل</b></div>
            </div>
          </div>
          <div class="scene-do"><span>هيكلة العناوين</span><span>الروابط الداخلية</span><span>مطابقة النية</span><span>عناصر التحويل</span></div>
          <?php if ( $seo_url ) : ?>
          <a href="<?php echo esc_url( $seo_url ); ?>" class="scene-link">تفاصيل السيو الداخلي <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg></a>
          <?php endif; ?>
        </div>
        <!-- Content writing -->
        <div class="scene" id="scene2" data-i="2">
          <div class="scene-n">٠٣ — كتابة المحتوى</div>
          <h3>نخطط للمحتوى ونكتبه ونراجعه قبل النشر</h3>
          <p>نحدد الموضوعات والكلمات التي يحتاجها الموقع. ثم نعدّ المحتوى ونراجعه حتى يكون مفيدًا للقارئ وقابلًا للمنافسة في البحث.</p>
          <div class="viz">
            <div class="viz-flow">
              <div class="st"><div class="c"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg></div><small>خريطة كلمات</small></div>
              <div class="arw">←</div>
              <div class="st"><div class="c"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg></div><small>موجز</small></div>
              <div class="arw">←</div>
              <div class="st"><div class="c"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4z"/></svg></div><small>كتابة</small></div>
              <div class="arw">←</div>
              <div class="st"><div class="c"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg></div><small>مراجعة</small></div>
              <div class="arw">←</div>
              <div class="st"><div class="c"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4z"/></svg></div><small>نشر</small></div>
            </div>
          </div>
          <div class="scene-do"><span>بحث الكلمات</span><span>موجز المحتوى</span><span>كتابة متخصّصة</span><span>تحرير وتدقيق</span></div>
          <?php if ( $content_url ) : ?>
          <a href="<?php echo esc_url( $content_url ); ?>" class="scene-link">تفاصيل كتابة المحتوى <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg></a>
          <?php endif; ?>
        </div>
        <!-- Off-page SEO -->
        <div class="scene" id="scene3" data-i="3">
          <div class="scene-n">٠٤ — السيو الخارجي</div>
          <h3>نبني روابط خارجية من مواقع موثوقة ومرتبطة بمجالك</h3>
          <p>نراجع روابط الموقع الحالية والمنافسين، ثم ننفّذ خطة روابط واضحة بدل شراء روابط عشوائية لا تخدم الموقع.</p>
          <div class="viz">
            <div class="viz-net">
              <div class="viz-node"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 5h16M4 10h16M4 15h10M4 19h7"/></svg></div>
              <div class="viz-node"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.7 2.5 15.3 0 18M12 3c-2.5 2.7-2.5 15.3 0 18"/></svg></div>
              <div class="viz-node hub"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 007.5.5l3-3a5 5 0 00-7-7l-1.7 1.7"/><path d="M14 11a5 5 0 00-7.5-.5l-3 3a5 5 0 007 7l1.7-1.7"/></svg></div>
              <div class="viz-node"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3l7 3v5c0 4.4-3 8.3-7 10-4-1.7-7-5.6-7-10V6z"/><path d="M9 12l2 2 4-4"/></svg></div>
              <div class="viz-node"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg></div>
              <div class="viz-node"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 17l5-5 4 3 8-8"/><path d="M16 4h5v5"/></svg></div>
              <div class="viz-node"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.5 8.5 0 01-11.9 7.8L3 21l1.7-6.1A8.5 8.5 0 1121 11.5z"/></svg></div>
              <div class="viz-node"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 17l6-6 4 4 8-8"/><path d="M15 3h6v6"/></svg></div>
            </div>
          </div>
          <div class="scene-do"><span>روابط تحريرية</span><span>مواقع ذات صلة</span><span>مراجعة ملف الروابط</span><span>بناء السلطة</span></div>
          <?php if ( $backlinks_url ) : ?>
          <a href="<?php echo esc_url( $backlinks_url ); ?>" class="scene-link">تفاصيل السيو الخارجي <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg></a>
          <?php endif; ?>
        </div>
        <!-- Consulting -->
        <div class="scene" id="scene4" data-i="4">
          <div class="scene-n">٠٥ — الاستشارات وتحليل الأداء</div>
          <h3>نراجع وضع موقعك ونحدد ما يحتاج إلى تنفيذ أولًا</h3>
          <p>نحلل الموقع والبيانات وعمل الفريق أو الوكالة الحالية. ثم نرتب المشاكل والفرص حسب الأولوية ونوضح لك الخطوة التالية.</p>
          <div class="viz">
            <div class="viz-map">
              <div class="mstep done"><span class="mn">✓</span><span>الوضع الحالي</span></div>
              <div class="mstep done"><span class="mn">✓</span><span>الفرص المتاحة</span></div>
              <div class="mstep"><span class="mn">٣</span><span>الأولويات</span></div>
              <div class="mstep"><span class="mn">٤</span><span>خطة التنفيذ</span></div>
              <div class="mstep"><span class="mn">٥</span><span>القياس والتطوير</span></div>
            </div>
          </div>
          <div class="scene-do"><span>تدقيق شامل</span><span>تحليل المنافسين</span><span>خارطة طريق</span><span>لوحة أداء</span></div>
          <?php if ( $consulting_url ) : ?>
          <a href="<?php echo esc_url( $consulting_url ); ?>" class="scene-link">تفاصيل الاستشارات <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg></a>
          <?php endif; ?>
        </div>
      </div><!-- /.pil-scenes -->
    </div><!-- /.pil-layout -->
    <!-- E-commerce strip -->
    <?php if ( $stores_url ) : ?>
    <div class="spec-strip sr">
      <div>
        <h4>تدير متجرًا إلكترونيًا؟</h4>
        <p>لدينا مسار سيو متخصّص للمتاجر يركّز على صفحات المنتجات والفئات والمبيعات القادمة من محركات البحث.</p>
      </div>
      <a href="<?php echo esc_url( $stores_url ); ?>" class="sf-btn sf-btn-p">سيو المتاجر الإلكترونية</a>
    </div>
    <?php endif; ?>
  </div><!-- /.sf-wrap -->
</section><!-- /#pillars -->

<!-- ══ RESULTS EXPLORER ══ -->
<section id="results" class="sf-sec">
  <div class="glow"></div>
  <div class="sf-wrap">
    <div class="sf-sec-head c" style="max-width:620px">
      <span class="sf-tag d" style="justify-content:center">أدلّة موثّقة</span>
      <h2 class="sf-h2 wh">نتائج موثّقة من حسابات عملائنا</h2>
      <p class="sf-bod d" style="margin-top:12px">لا نقيس نجاح السيو بترتيب الكلمات فقط. الأهم أن يجلب الموقع زيارات أفضل ويساعد على زيادة المبيعات. وهذه أمثلة حقيقية من حسابات عملائنا.</p>
    </div>
    <div class="rx-stage">
      <!-- Tabs -->
      <div class="rx-tabs" id="rxTabs">
        <button class="rx-tab active" data-i="0">
          <span class="k"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>مبيعات من محركات البحث</span>
          <span class="v"><span class="metric-flow"><bdi>19,956</bdi><span class="metric-arrow" aria-hidden="true">←</span><bdi>106,274</bdi><span class="metric-unit">ر.س</span></span></span>
          <span class="s">زيادة تتجاوز خمسة أضعاف</span>
          <span class="prog"></span>
        </button>
        <button class="rx-tab" data-i="1">
          <span class="k"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>زيارات أكثر من Google</span>
          <span class="v"><span class="metric-flow"><bdi>825</bdi><span class="metric-arrow" aria-hidden="true">←</span><bdi>12,900</bdi><span class="metric-unit">نقرة</span></span></span>
          <span class="s">نقرات قادمة من نتائج Google</span>
          <span class="prog"></span>
        </button>
        <button class="rx-tab" data-i="2">
          <span class="k"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg>في مقدمة نتائج Google</span>
          <span class="v">النتيجة الأولى لكلمة «مكتب محاماة»</span>
          <span class="s">موقع سهل للمحاماة</span>
          <span class="prog"></span>
        </button>
      </div>
      <!-- Panels -->
      <div class="rx-screens">
        <!-- Case 1: Revenue -->
        <div class="rx-panel on" data-i="0">
          <div class="rx-screen">
            <div class="rx-sc-head">
              <div class="rx-sc-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M7 14l4-4 3 3 5-6"/></svg>مبيعات أعلى من Google</div>
              <div class="rx-sc-badge">أكثر من 5 أضعاف</div>
            </div>
            <div class="rx-sc-body">
              <p class="rx-explain">متجر إلكتروني ارتفعت مبيعاته القادمة من محركات البحث من <bdi dir="ltr">19,956</bdi> إلى <bdi dir="ltr">106,274</bdi> ريال.</p>
              <img class="rx-hero-img" src="<?php echo esc_url( $img_revenue ); ?>" alt="مبيعات أعلى من محركات البحث من 19,956 إلى 106,274 ريال — Looker Studio" style="object-fit:contain" data-lb="<?php echo esc_url( $img_revenue ); ?>">
            </div>
            <div class="rx-sc-foot">
              <span class="rx-src"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/></svg>المصدر: Looker Studio — Organic Search</span>
              <span class="rx-zoom" data-lb="<?php echo esc_url( $img_revenue ); ?>"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4M11 8v6M8 11h6"/></svg>عرض المصدر كاملًا</span>
            </div>
          </div>
        </div>
        <!-- Case 2: Clicks -->
        <div class="rx-panel" data-i="1">
          <div class="rx-screen">
            <div class="rx-sc-head">
              <div class="rx-sc-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 18V9M9 18V5M14 18v-6M19 18v-9"/></svg>عدد أكبر من العملاء يصل إلى الموقع</div>
              <div class="rx-sc-badge"><bdi dir="ltr">12,900</bdi> نقرة</div>
            </div>
            <div class="rx-sc-body">
              <p class="rx-explain">ارتفع عدد النقرات القادمة إلى الموقع من نتائج Google من <bdi dir="ltr">825</bdi> إلى <bdi dir="ltr">12,900</bdi> نقرة.</p>
              <img class="rx-hero-img" src="<?php echo esc_url( $img_clicks ); ?>" alt="نمو النقرات من 825 إلى 12,900 — Google Search Console" data-lb="<?php echo esc_url( $img_clicks ); ?>">
            </div>
            <div class="rx-sc-foot">
              <span class="rx-src"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg>المصدر: Google Search Console</span>
              <span class="rx-zoom" data-lb="<?php echo esc_url( $img_clicks ); ?>"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4M11 8v6M8 11h6"/></svg>عرض بالحجم الكامل</span>
            </div>
          </div>
        </div>
        <!-- Case 3: Law firm -->
        <div class="rx-panel" data-i="2">
          <div class="rx-screen">
            <div class="rx-sc-head">
              <div class="rx-sc-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg>أول موقع يظهر أمام الباحث</div>
              <div class="rx-sc-badge">النتيجة الأولى</div>
            </div>
            <div class="rx-sc-body">
              <p class="rx-explain">ظهر موقع سهل للمحاماة كأول موقع في نتائج Google عند البحث عن «مكتب محاماة».</p>
              <img class="rx-hero-img" src="<?php echo esc_url( $img_lawfirm ); ?>" alt="سهل للمحاماة — أول موقع في نتائج Google لكلمة مكتب محاماة" style="object-fit:contain" data-lb="<?php echo esc_url( $img_lawfirm ); ?>">
            </div>
            <div class="rx-sc-foot">
              <span class="rx-src"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg>المصدر: نتيجة بحث Google</span>
              <span class="rx-zoom" data-lb="<?php echo esc_url( $img_lawfirm ); ?>"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4M11 8v6M8 11h6"/></svg>عرض المصدر كاملًا</span>
            </div>
          </div>
        </div>
      </div><!-- /.rx-screens -->
    </div><!-- /.rx-stage -->
  </div><!-- /.sf-wrap -->
</section><!-- /#results -->

<!-- ══ MARKET NAVIGATOR ══ -->
<section id="markets" class="sf-sec">
  <div class="sf-wrap">
    <div class="sf-sec-head c" style="max-width:600px">
      <span class="sf-tag d" style="justify-content:center">أسواق متعددة</span>
      <h2 class="sf-h2 wh">استراتيجية مختلفة لكل سوق</h2>
      <p class="sf-bod d" style="margin-top:12px">لكل سوق طبيعته البحثية ومنافسته وسلوك عملائه — نبني الاستراتيجية على أساس السوق المستهدف.</p>
    </div>
    <div class="mkt-band">
      <a class="mkt" href="<?php echo esc_url( $mkt_sa ); ?>">
        <div class="mkt-flag">🇸🇦</div>
        <h3>السعودية</h3>
        <div class="mkt-desc">أكبر أسواق البحث في المنطقة، بمنافسة قوية على الكلمات التجارية وتنوّع في القطاعات — من التجارة الإلكترونية إلى الخدمات المهنية.</div>
        <span class="mkt-go">استراتيجية السوق السعودي <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg></span>
      </a>
      <a class="mkt" href="<?php echo esc_url( $mkt_eg ); ?>">
        <div class="mkt-flag">🇪🇬</div>
        <h3>مصر</h3>
        <div class="mkt-desc">سوق ضخم بحجم بحث مرتفع وفرص كبيرة في المحتوى العربي — يتطلّب استراتيجية تركّز على نية الشراء لتحويل الحجم إلى عملاء.</div>
        <span class="mkt-go">استراتيجية السوق المصري <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg></span>
      </a>
      <a class="mkt" href="<?php echo esc_url( $mkt_ae ); ?>">
        <div class="mkt-flag">🇦🇪</div>
        <h3>الإمارات</h3>
        <div class="mkt-desc">سوق تنافسي بجمهور متعدّد اللغات وقيمة عميل مرتفعة — يحتاج محتوى دقيق واستهدافًا محكمًا للكلمات عالية القيمة.</div>
        <span class="mkt-go">استراتيجية السوق الإماراتي <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg></span>
      </a>
    </div>
  </div>
</section><!-- /#markets -->

<!-- ══ PROJECT JOURNEY ══ -->
<section id="journey" class="sf-sec">
  <div class="sf-wrap">
    <div class="sf-sec-head c" style="max-width:640px">
      <span class="sf-tag" style="justify-content:center">خطوات العمل</span>
      <h2 class="sf-h2">من مراجعة موقعك إلى متابعة النتائج</h2>
      <p class="sf-bod" style="margin-top:12px">نقسم العمل إلى أربع مراحل واضحة، وتعرف في كل مرحلة ما الذي ننفذه، وما الذي تم إنجازه، وما الخطوة التالية.</p>
    </div>
    <div class="jr-track">
      <div class="jr-phase p1 sr">
        <div class="jr-n">١</div>
        <h3>نفهم موقعك وهدفك</h3>
        <p class="jr-work">نتعرّف على طبيعة النشاط والعملاء المستهدفين، ونراجع الموقع وحسابات القياس والصلاحيات قبل بدء العمل.</p>
        <div class="jr-del">
          <div class="jr-del-l">ما تستلمه</div>
          <div class="jr-del-tags">
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>تحديد أهداف المشروع</span>
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>مراجعة الأدوات والصلاحيات</span>
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>ملخص الوضع الحالي</span>
          </div>
        </div>
      </div>
      <div class="jr-phase p2 sr d1">
        <div class="jr-n">٢</div>
        <h3>نحلّل ونرتّب الأولويات</h3>
        <p class="jr-work">تدقيق تقني، مراجعة المنافسين، بحث الكلمات، وربط الكلمات بالصفحات.</p>
        <div class="jr-del">
          <div class="jr-del-l">التسليمات</div>
          <div class="jr-del-tags">
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>التدقيق التقني</span>
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>خريطة الكلمات والصفحات</span>
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>أولويات التنفيذ</span>
          </div>
        </div>
      </div>
      <div class="jr-phase p3 sr d2">
        <div class="jr-n">٣</div>
        <h3>نبدأ التنفيذ</h3>
        <p class="jr-work">نعالج المشاكل التقنية، ونحسّن الصفحات والروابط الداخلية، ونجهّز المحتوى وننفّذ السيو الخارجي حسب نطاق المشروع.</p>
        <div class="jr-del">
          <div class="jr-del-l">ما تستلمه</div>
          <div class="jr-del-tags">
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>سجل الأعمال المنفذة</span>
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>المحتوى والصفحات المحدثة</span>
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>تحديثات واضحة أثناء التنفيذ</span>
          </div>
        </div>
      </div>
      <div class="jr-phase p4 sr d3">
        <div class="jr-n">٤</div>
        <h3>نقيس ونطوّر</h3>
        <p class="jr-work">مراجعة الأداء، لوحة Looker Studio، تقرير بما أُنجز، وأولويات المرحلة التالية.</p>
        <div class="jr-del">
          <div class="jr-del-l">التسليمات</div>
          <div class="jr-del-tags">
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>لوحة أداء مباشرة</span>
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>ملخّص شهري</span>
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>أولويات المرحلة التالية</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section><!-- /#journey -->

<!-- ══ WHY / TEAM ══ -->
<section id="why" class="sf-sec">
  <div class="sf-wrap">
    <div class="why-layout">
      <div class="why-text">
        <span class="sf-tag">فريق سيو هاوس</span>
        <h2 class="sf-h2">فريق سيو هاوس يعمل على مشروعك فعليًا</h2>
        <p class="sf-bod">مشروعك لا يديره شخص واحد. يعمل عليه متخصص سيو وفريق محتوى ومطور عند الحاجة، وكل شخص مسؤول عن جزء واضح من التنفيذ.</p>
        <p class="sf-bod" style="margin-top:12px">نتابع العمل والنتائج معك بصورة مباشرة، وتعرف كل شهر ما تم وما الذي سنعمل عليه بعد ذلك.</p>
        <div class="why-roles">
          <div class="why-role"><div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg></div><div><h4>متخصّصو سيو</h4><p>تقني، داخلي، وخارجي — كل مجال له خبير ينفّذه.</p></div></div>
          <div class="why-role"><div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4z"/></svg></div><div><h4>فريق محتوى</h4><p>بحث، كتابة، وتحرير محتوى عربي أصيل يخدم النية والتحويل.</p></div></div>
          <div class="why-role"><div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg></div><div><h4>دعم تطوير تقني</h4><p>مطوّر ينفّذ الإصلاحات التقنية مباشرة على موقعك.</p></div></div>
          <div class="why-role"><div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 3v18h18"/><path d="M7 14l4-4 3 3 5-6"/></svg></div><div><h4>تقارير مباشرة وتواصل واضح</h4><p>لوحة أداء حيّة، ملخّص شهري، وأولويات صريحة للمرحلة القادمة.</p></div></div>
        </div>
      </div>
      <!-- Team filmstrip: dynamic from team_member CPT -->
      <div class="strip">
        <div class="strip-col up">
          <?php sf_strip_col( $col1 ); ?>
        </div>
        <div class="strip-col down">
          <?php sf_strip_col( $col2 ); ?>
        </div>
      </div>
    </div>
  </div>
</section><!-- /#why -->

<!-- ══ REVIEWS ══ -->
<?php if ( $_rev_sc ) : ?>
<section id="reviews" class="sf-sec">
  <div class="sf-wrap">
    <div class="sf-sec-head c" style="max-width:560px">
      <span class="sf-tag" style="justify-content:center">آراء العملاء</span>
      <h2 class="sf-h2">تقييمات موثّقة على Google</h2>
    </div>
    <div class="sf-rev-panel">
      <div class="rev-plugin">
        <?php echo do_shortcode( wp_kses_post( $_rev_sc ) ); ?>
      </div>
      <a href="<?php echo esc_url( $contact_url ); ?>" class="sf-btn sf-btn-o" style="margin-top:20px">شاهد تقييماتنا على Google</a>
    </div>
  </div>
</section><!-- /#reviews -->
<?php endif; ?>

<!-- ══ FAQ + CTA ══ -->
<section id="faq" class="sf-sec">
  <div class="sf-wrap">
    <div class="sf-sec-head c" style="max-width:560px">
      <span class="sf-tag" style="justify-content:center">أسئلة شائعة</span>
      <h2 class="sf-h2">أسئلة يطرحها العملاء قبل البدء</h2>
    </div>
    <div class="faq-list" id="sfFaqList">
      <div class="faq-item">
        <div class="faq-q"><span>كم يستغرق ظهور نتائج السيو؟</span><div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></div></div>
        <div class="faq-a"><p>تبدأ المؤشرات الأولى عادةً خلال 60–90 يومًا، وتتضح النتائج الأقوى بين الشهر الرابع والسادس — حسب حالة الموقع الحالية ومستوى المنافسة في قطاعك.</p></div>
      </div>
      <div class="faq-item">
        <div class="faq-q"><span>هل تلتزمون بعقود طويلة؟</span><div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></div></div>
        <div class="faq-a"><p>لا نُلزمك بعقود مفتوحة. نثبت قيمتنا شهرًا بشهر عبر تقارير واضحة بما نفّذناه وما تحقّق — وأنت تقرّر الاستمرار.</p></div>
      </div>
      <div class="faq-item">
        <div class="faq-q"><span>ما الفرق بين السيو والإعلانات المدفوعة؟</span><div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></div></div>
        <div class="faq-a"><p>الإعلانات تجلب زيارات فورية تتوقّف بتوقّف الإنفاق. السيو يبني حضورًا عضويًا يستمر ويتراكم مع الوقت — والاثنان يكملان بعضهما.</p></div>
      </div>
      <div class="faq-item">
        <div class="faq-q"><span>كيف أعرف أن العمل يتحقّق فعلًا؟</span><div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></div></div>
        <div class="faq-a"><p>تستلم لوحة أداء مباشرة على Looker Studio، وملخّصًا شهريًا يربط الزيارات والنقرات بالنتائج — كل رقم مصدره أدوات جوجل الرسمية.</p></div>
      </div>
      <div class="faq-item">
        <div class="faq-q"><span>هل تعملون مع المتاجر الإلكترونية؟</span><div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></div></div>
        <div class="faq-a"><p>نعم، ولدينا مسار متخصّص لسيو المتاجر يركّز على صفحات المنتجات والفئات ونية الشراء لرفع المبيعات القادمة من محركات البحث على سلة وزد وشوبيفاي وووكومرس.</p></div>
      </div>
      <div class="faq-item">
        <div class="faq-q"><span>هل يشمل السيو كتابة المحتوى وبناء الروابط؟</span><div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></div></div>
        <div class="faq-a"><p>حسب النطاق المتّفق عليه. نغطّي المنظومة كاملة — التقني، الداخلي، المحتوى، الخارجي، والاستشارات — ونحدّد معك ما يخدم أولوياتك.</p></div>
      </div>
    </div>
    <!-- CTA Final -->
    <div class="cta-final">
      <div class="cta-final-in">
        <h2 class="sf-h2 wh">لنبدأ بمراجعة موقعك</h2>
        <p>احجز استشارة مجانية — نحلّل وضعك الحالي، نكشف الفرص، ونضع معك أولويات التنفيذ. بدون التزام.</p>
        <div class="cta-btns">
          <a href="<?php echo esc_url( $contact_url ); ?>" class="sf-btn sf-btn-w lg">اطلب مراجعة موقعك</a>
          <a href="<?php echo esc_url( $contact_url ); ?>" class="sf-btn sf-btn-gh lg">تواصل معنا</a>
        </div>
        <?php get_template_part( 'template-parts/layout/contact-form', null, [
            'form_title' => 'احجز استشارتك المجانية',
            'form_sub'   => 'أرسل طلبك وسنتواصل معك خلال 24 ساعة.',
        ] ); ?>
      </div>
    </div>
  </div>
</section><!-- /#faq -->

<!-- Lightbox -->
<div id="sfLb" class="sf-lb" role="dialog" aria-modal="true" aria-label="عرض الصورة">
  <button class="sf-lb-x" aria-label="إغلاق">×</button>
  <img src="" alt="">
</div>

</main><!-- /.svc-seo-final -->

<?php get_footer(); ?>
