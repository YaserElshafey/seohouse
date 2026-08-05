<?php
/**
 * Single sector — ecommerce (التجارة الإلكترونية)
 *
 * Canonical page for /sectors/ecommerce/
 * Uses the exact same design and content as the former seo-stores service page.
 * Breadcrumb: الرئيسية → القطاعات → التجارة الإلكترونية
 *
 * WordPress template hierarchy: single-sector-ecommerce.php
 * Applies to: CPT 'sector' post with slug 'ecommerce'
 */

get_header();

while ( have_posts() ) : the_post();

// ── Page data — exact seo-stores content ──────────────────────────────────
$def = [
    'hero_badge'     => 'قطاع التجارة الإلكترونية',
    'hero_tag'       => 'سيو المتاجر',
    'hero_pre_em'    => 'سيو ',
    'hero_em_inline' => 'المتاجر الإلكترونية',
    'hero_line2'     => 'الذي يُترجم إلى مبيعات',
    'hero_desc'      => 'رفع ترتيب منتجاتك وفئاتك في جوجل ليجدك العملاء قبل أن يجدوا منافسيك — بمنهجية مخصصة لكل منصة تجارة إلكترونية وكل قطاع.',
    'has_plat_badges' => true,
    'why_tag'        => 'لماذا سيو المتاجر مختلف',
    'why_h2'         => "موقع الخدمات يختلف\nعن المتجر الإلكتروني",
    'why_p'          => 'المتاجر الإلكترونية تملك عشرات أو مئات الصفحات — كل منتج وكل فئة فرصة للترتيب على كلمة مفتاحية شرائية. هذا يعني أن السيو للمتاجر يحتاج منهجية مختلفة تماماً.',
    'why_stores'     => true,
    'why_checks'     => [
        [ 'b' => 'تحسين مئات صفحات المنتجات والفئات',           't' => '' ],
        [ 'b' => 'كلمات شرائية تجلب عملاء جاهزين للدفع',       't' => '' ],
        [ 'b' => 'تجنّب المحتوى المتكرر الذي يُضعف المتاجر',    't' => '' ],
        [ 'b' => 'تحسين سرعة التحميل التي تُقلل الارتداد',      't' => '' ],
        [ 'b' => 'تقارير مبيعات عضوية مرتبطة بكلمات محددة',     't' => '' ],
    ],
    'why_card_type'  => 'seo-stores',
    'has_pillars'    => true,
    'tactics_tag'    => 'ماذا نفعل',
    'tactics_h2'     => 'ستة محاور لسيو المتاجر',
    'tactics_desc'   => 'كل محور يعالج جانباً مختلفاً من ظهور متجرك في البحث.',
    'tactics'        => [
        [ 'n' => '01', 'h' => 'بحث الكلمات الشرائية',   'd' => 'الكلمات التي يستخدمها المشترون الجادون — لا المتصفحون فقط. الفرق يصنع المبيعات.',                                                                           'svg' => '<circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>' ],
        [ 'n' => '02', 'h' => 'تحسين صفحات المنتجات',   'd' => 'عناوين، أوصاف فريدة، بيانات هيكلية، وصور محسّنة — كل منتج يُعامَل كصفحة مستقلة.',                                                                         'svg' => '<path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>' ],
        [ 'n' => '03', 'h' => 'تحسين بنية الفئات',      'd' => 'هيكل تنقل منطقي يساعد جوجل على فهم كتالوجك وفهرسة كل صفحة بالشكل الصحيح.',                                                                                'svg' => '<path d="M3 7h18M3 12h18M3 17h18"/>' ],
        [ 'n' => '04', 'h' => 'تحسين سرعة المتجر',      'd' => 'Core Web Vitals محسّنة — المتجر البطيء يفقد الزوار قبل أن يُكملوا الشراء.',                                                                                  'svg' => '<path d="M13 10V3L4 14h7v7l9-11h-7z"/>' ],
        [ 'n' => '05', 'h' => 'بناء روابط للمتجر',      'd' => 'روابط موثوقة تُعزز سلطة متجرك وتسرّع صعود صفحات منتجاتك في نتائج البحث.',                                                                                   'svg' => '<path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/>' ],
        [ 'n' => '06', 'h' => 'تقارير المبيعات العضوية', 'd' => 'تقرير شهري يُظهر نمو الزيارات العضوية والمبيعات المنسوبة لتحسين محركات البحث.',                                                                             'svg' => '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>' ],
    ],
    'has_plat_cards' => true,
    'plat_cards'     => [
        [ 'lbl' => 'سلة',  'h' => 'سيو متاجر سلة',     'p' => 'تحسين مخصص لقيود وإمكانيات منصة سلة — الأكثر انتشاراً في السوق السعودي.' ],
        [ 'lbl' => 'زد',   'h' => 'سيو متاجر زد',      'p' => 'استغلال كامل لإمكانيات زد التقنية في بناء هيكل سيو متين من البداية.' ],
        [ 'lbl' => 'SF',   'h' => 'سيو متاجر شوبيفاي', 'p' => 'تحسين تقني عميق لشوبيفاي مع إدارة التطبيقات المؤثرة على الأداء.' ],
        [ 'lbl' => 'WC',   'h' => 'سيو ووكومرس',       'p' => 'تحسين ووردبريس وووكومرس معاً — مرونة كاملة في التحسين التقني والمحتوى.' ],
    ],
    'skip_proc'  => true,
    'faq_tag'    => 'الأسئلة الشائعة',
    'faq_h2'     => 'أسئلة عن سيو المتاجر',
    'faq_bg'     => 'sec-off',
    'faqs'       => [
        [ 'q' => 'هل يختلف سيو المتجر عن سيو الموقع العادي؟',  'a' => 'نعم بشكل كبير. المتاجر تحتوي عشرات أو مئات الصفحات المتكررة وتحتاج معالجة خاصة لتفادي المحتوى المتكرر، بنية الفئات، وسرعة التحميل على كميات كبيرة من المنتجات.' ],
        [ 'q' => 'كم من الوقت حتى أرى مبيعات من السيو؟',         'a' => 'في الغالب 3–5 أشهر لبدء ظهور نتائج ملموسة في المبيعات العضوية. الكلمات الطويلة (long-tail) تظهر أسرع من الكلمات التنافسية العامة.' ],
        [ 'q' => 'هل يمكنكم تحسين آلاف المنتجات؟',               'a' => 'نعم. نضع استراتيجية قابلة للتوسع تُعالج المنتجات في مجموعات حسب الأولوية والأثر المتوقع — بدءاً بالأكثر مبيعاً.' ],
        [ 'q' => 'هل تعملون مع متاجر سلة المحدودة تقنياً؟',       'a' => 'نعم، نفهم القيود التقنية لسلة ونعمل ضمنها لتحقيق أقصى تحسين ممكن — المحتوى والهيكل وليس فقط الجانب التقني.' ],
        [ 'q' => 'ما المنصات التي تدعمونها؟',                     'a' => 'سلة، زد، شوبيفاي، ووكومرس، ماجنتو، وأي منصة أخرى. لكل منصة خصائصها التقنية ونفهمها جميعاً بعمق.' ],
        [ 'q' => 'هل تشمل الخدمة كتابة أوصاف المنتجات؟',         'a' => 'نعم — يمكن إضافة كتابة أوصاف فريدة لكل منتج كجزء من الباقة، خاصة للمنتجات الأكثر تأثيراً على المبيعات.' ],
    ],
    'cta_h3'     => "هل متجرك يستحق\nالمرتبة الأولى؟",
    'cta_desc'   => 'نحلل متجرك مجاناً خلال 30 دقيقة ونضع معاً خطة السيو.',
    'cta_checks' => [ 'تحليل مجاني لمتجرك', 'توصيات عملية فورية', 'بدون أي التزام' ],
    'cta_btn'    => 'احجز استشارة مجانية',
    'bottom_h2'  => 'هل متجرك يستحق مبيعات أكثر؟',
    'bottom_p'   => 'احجز استشارة مجانية ونحلل معاً فرص نموّه في جوجل.',
    'btn2_txt'   => 'جميع القطاعات',
];

// ── ACF overrides (only when explicitly set in WP admin) ──────────────────
$tag      = sh_field( 'sub_hero_tag' )   ?: $def['hero_tag'];
$h1       = sh_field( 'sub_hero_title' ) ?: ( $def['hero_h1'] ?? '' );
$em       = sh_field( 'sub_hero_em' )    ?: ( $def['hero_em'] ?? '' );
$desc     = sh_field( 'sub_hero_desc' )  ?: $def['hero_desc'];
$pre_em   = $def['hero_pre_em']     ?? '';
$em_inline= $def['hero_em_inline']  ?? '';
$line2    = $def['hero_line2']      ?? '';
$acf_faqs = sh_field( 'sub_faqs' );
$faqs     = ! empty( $acf_faqs ) ? $acf_faqs : null;

$contact_url = sh_page_url( 'contact' );
$sectors_url = sh_page_url( 'sectors' );
?>

<!-- ═══════════════════════════════════════════════════════════
     HERO
     ═══════════════════════════════════════════════════════════ -->
<section class="svc-hero">
  <div class="wrap">
    <div class="svc-hero-inner">
      <div class="breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">الرئيسية</a>
        <svg class="bc-sep" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        <a href="<?php echo esc_url( $sectors_url ); ?>">القطاعات</a>
        <svg class="bc-sep" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        <span style="color:rgba(255,255,255,.55)">التجارة الإلكترونية</span>
      </div>
      <span class="tag d" style="margin-bottom:20px"><?php echo esc_html( $def['hero_badge'] ); ?></span>
      <?php if ( $em_inline ) : ?>
      <h1 class="svc-hero-h1"><?php echo esc_html( $pre_em ); ?><em><?php echo esc_html( $em_inline ); ?></em><br><?php echo esc_html( $line2 ); ?></h1>
      <?php else : ?>
      <h1 class="svc-hero-h1"><?php echo esc_html( $h1 ); ?><br><em><?php echo esc_html( $em ); ?></em></h1>
      <?php endif; ?>
      <p class="page-hero-p"><?php echo esc_html( $desc ); ?></p>
      <div class="pbtns">
        <a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-p lg">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          احجز استشارة مجانية
        </a>
        <a href="<?php echo esc_url( $sectors_url ); ?>" class="btn btn-g lg"><?php echo esc_html( $def['btn2_txt'] ); ?></a>
      </div>
      <?php if ( ! empty( $def['has_plat_badges'] ) ) : ?>
      <div class="plat-badges">
        <div class="plat-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>سلة</div>
        <div class="plat-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>زد</div>
        <div class="plat-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="3" width="20" height="14" rx="2"/></svg>شوبيفاي</div>
        <div class="plat-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>ووكومرس</div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     WHY — 2-col: checklist + stores journey card
     ═══════════════════════════════════════════════════════════ -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="<?php echo ! empty( $def['why_stores'] ) ? 'why-stores-grid' : 'why-grid'; ?>">

      <!-- Left: text + checklist -->
      <div class="sr">
        <span class="tag" style="margin-bottom:12px"><?php echo esc_html( $def['why_tag'] ); ?></span>
        <h2 class="h2" style="margin-bottom:16px"><?php echo nl2br( esc_html( $def['why_h2'] ) ); ?></h2>
        <p class="bod" style="margin-bottom:18px"><?php echo esc_html( $def['why_p'] ); ?></p>
        <div class="chklist sr d1" style="margin-bottom:24px">
          <?php foreach ( $def['why_checks'] as $ck ) : ?>
          <div class="chk-item">
            <div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div>
            <span><strong><?php echo esc_html( $ck['b'] ); ?></strong><?php echo $ck['t'] ? ' ' . esc_html( $ck['t'] ) : ''; ?></span>
          </div>
          <?php endforeach; ?>
        </div>
        <a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-p sr d2">احجز استشارة مجانية</a>
      </div>

      <!-- Right: stores customer journey card -->
      <div class="sr d1">
        <div style="background:var(--navy-2);border-radius:var(--r4);padding:30px;position:relative;overflow:hidden">
          <div style="position:absolute;inset-inline-end:-50px;top:-50px;width:220px;height:220px;border-radius:50%;background:radial-gradient(circle,rgba(30,46,245,.28),transparent 70%)"></div>
          <div style="font-size:9.5px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.22);margin-bottom:18px;position:relative;z-index:1">رحلة عميل المتجر في جوجل</div>
          <div style="display:flex;flex-direction:column;gap:10px;position:relative;z-index:1">
            <div style="display:flex;align-items:center;gap:10px;padding:12px 14px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:var(--r2)">
              <div style="width:26px;height:26px;border-radius:6px;background:rgba(30,46,245,.2);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:900;color:#7b90ff;flex-shrink:0">1</div>
              <div style="font-size:13px;color:rgba(255,255,255,.72)">يبحث: "شراء منتج في الرياض"</div>
            </div>
            <div style="display:flex;align-items:center;gap:10px;padding:12px 14px;background:rgba(30,46,245,.14);border:1px solid rgba(30,46,245,.28);border-radius:var(--r2)">
              <div style="width:26px;height:26px;border-radius:6px;background:var(--blue);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:900;color:#fff;flex-shrink:0">2</div>
              <div style="font-size:13px;color:rgba(255,255,255,.85)">يجد متجرك في المرتبة الأولى</div>
            </div>
            <div style="display:flex;align-items:center;gap:10px;padding:12px 14px;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.2);border-radius:var(--r2)">
              <div style="width:26px;height:26px;border-radius:6px;background:rgba(16,185,129,.3);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:900;color:var(--green);flex-shrink:0">3</div>
              <div style="font-size:13px;color:rgba(255,255,255,.72)">يشتري — بدون أي تكلفة إعلانية</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     TACTICS — pillars grid (6 محاور)
     ═══════════════════════════════════════════════════════════ -->
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag"><?php echo esc_html( $def['tactics_tag'] ); ?></span>
      <h2 class="h2"><?php echo esc_html( $def['tactics_h2'] ); ?></h2>
      <p class="bod"><?php echo esc_html( $def['tactics_desc'] ); ?></p>
    </div>
    <div class="pillars-grid">
      <?php
      $delays = [ '', 'd1', 'd2', 'd1', 'd2', 'd3' ];
      foreach ( $def['tactics'] as $i => $tc ) :
          $dc = $delays[ $i % 6 ];
      ?>
      <div class="pillar sr<?php echo $dc ? " $dc" : ''; ?>">
        <div class="pillar-n"><?php echo esc_html( $tc['n'] ); ?></div>
        <div class="pillar-ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?php echo $tc['svg']; // phpcs:ignore ?></svg>
        </div>
        <h3><?php echo esc_html( $tc['h'] ); ?></h3>
        <p><?php echo esc_html( $tc['d'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     PLATFORM CARDS — سلة / زد / شوبيفاي / ووكومرس
     ═══════════════════════════════════════════════════════════ -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag">المنصات المدعومة</span>
      <h2 class="h2">نعمل مع جميع منصات التجارة الإلكترونية</h2>
      <p class="bod">لكل منصة خصائصها التقنية — ونفهمها جميعاً بعمق.</p>
    </div>
    <div class="plat-cards sr d1">
      <?php foreach ( $def['plat_cards'] as $pc ) : ?>
      <div class="plat-card-big">
        <div class="plat-logo-box"><?php echo esc_html( $pc['lbl'] ); ?></div>
        <h3><?php echo esc_html( $pc['h'] ); ?></h3>
        <p><?php echo esc_html( $pc['p'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     FAQ + sticky CTA card
     ═══════════════════════════════════════════════════════════ -->
<section class="sec <?php echo esc_attr( $def['faq_bg'] ); ?>">
  <div class="wrap">
    <div class="faq-cta-layout">

      <!-- FAQs -->
      <div>
        <div class="sh sr">
          <span class="tag"><?php echo esc_html( $def['faq_tag'] ); ?></span>
          <h2 class="h2"><?php echo esc_html( $def['faq_h2'] ); ?></h2>
        </div>
        <div class="faq-list sr d1">
          <?php
          $faq_items = $faqs ?? $def['faqs'];
          foreach ( $faq_items as $faq ) :
              $q = $faq['question'] ?? ( $faq['q'] ?? '' );
              $a = $faq['answer']   ?? ( $faq['a'] ?? '' );
          ?>
          <div class="faq-item">
            <div class="faq-q">
              <span><?php echo esc_html( $q ); ?></span>
              <div class="faq-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></div>
            </div>
            <div class="faq-a"><div class="faq-a-inner"><?php echo esc_html( $a ); ?></div></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Sticky CTA card -->
      <div class="cta-sticky">
        <div class="cta-side-card sr d1">
          <span class="tag d" style="position:relative;z-index:1;margin-bottom:10px">ابدأ الآن</span>
          <h3 style="font-size:clamp(20px,2.5vw,26px);font-weight:900;color:#fff;margin-bottom:12px;line-height:1.25;position:relative;z-index:1"><?php echo nl2br( esc_html( $def['cta_h3'] ) ); ?></h3>
          <p style="font-size:13.5px;color:rgba(255,255,255,.44);line-height:1.8;margin-bottom:22px;position:relative;z-index:1"><?php echo esc_html( $def['cta_desc'] ); ?></p>
          <div class="chklist" style="margin-bottom:22px">
            <?php foreach ( $def['cta_checks'] as $ck ) : ?>
            <div class="chk-item d">
              <div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div>
              <?php echo esc_html( $ck ); ?>
            </div>
            <?php endforeach; ?>
          </div>
          <a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-p" style="width:100%;justify-content:center;position:relative;z-index:1"><?php echo esc_html( $def['cta_btn'] ); ?></a>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     CTA BANNER
     ═══════════════════════════════════════════════════════════ -->
<?php
get_template_part( 'template-parts/layout/cta-banner', null, [
    'tag'         => 'ابدأ الآن',
    'title'       => $def['bottom_h2'],
    'description' => $def['bottom_p'],
    'buttons'     => [
        [ 'text' => 'احجز استشارة مجانية', 'url' => $contact_url,  'class' => 'btn-w lg' ],
        [ 'text' => $def['btn2_txt'],        'url' => $sectors_url,  'class' => 'btn-g lg' ],
    ],
] );

endwhile;
get_footer();
