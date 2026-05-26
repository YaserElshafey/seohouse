<?php
/**
 * Template Name: Service — Platform Page
 * Used for: Salla, Zid, Shopify, WooCommerce, WordPress, Webflow, React/Next, Custom Dev
 */
get_header();

$hero_tag      = sh_field( 'service_hero_tag' );
$hero_title    = sh_field( 'service_hero_title' );
$hero_em       = sh_field( 'service_hero_em' );
$hero_desc     = sh_field( 'service_hero_desc' );
$platform_logo = sh_field( 'service_platform_logo' );
$dot_color     = sh_field( 'service_platform_color' ) ?: '#1e2ef5';
$emoji         = sh_field( 'service_platform_emoji' )  ?: '🚀';
$why_title     = sh_field( 'service_why_title' );
$why_desc      = sh_field( 'service_why_desc' );
$why_quote     = sh_field( 'service_why_quote' );
$features      = sh_field( 'service_features' );
$faqs          = sh_field( 'service_faqs' );

$display_title = $hero_title ?: get_the_title();
$display_tag   = $hero_tag   ?: get_the_title();
$display_desc  = $hero_desc  ?: get_the_excerpt();

// Detect parent section for breadcrumb
$is_stores = in_array( strtolower( $display_tag ), [
    'شوبيفاي', 'سلة', 'زد', 'ووكومرس', 'shopify', 'salla', 'zid', 'woocommerce',
], true );
$parent_label = $is_stores ? 'إنشاء وتصميم متاجر' : 'إنشاء وتصميم مواقع';
$parent_url   = $is_stores ? sh_page_url( 'services/stores' ) : sh_page_url( 'services/web-design' );

// Fallback when-to-use items
$default_when = [
    [ 'title' => 'مشروعك يستهدف العملاء المحليين',        'desc' => 'هذه المنصة مصمّمة لخدمة السوق المحلي بكل متطلباته.' ],
    [ 'title' => 'تريد إطلاقاً سريعاً بتكلفة محسوبة',     'desc' => 'باقات مناسبة للمشاريع الناشئة والأعمال المتوسطة على حد سواء.' ],
    [ 'title' => 'تحتاج دعماً متخصصاً ومتجاوباً',          'desc' => 'فريق متخصص يتحدث لغتك ويفهم بيئة عملك المحلية.' ],
    [ 'title' => 'تبحث عن تكامل مع أدوات العمل الحالية',   'desc' => 'ربط سلس مع بوابات الدفع والشحن وأنظمة المحاسبة المحلية.' ],
];
$when_items = ! empty( $features ) ? $features : $default_when;

// Default deliverables
$deliverables = [
    [
        'title' => 'إعداد المنصة الكامل',
        'desc'  => 'تهيئة المنصة من الصفر — اشتراكات، نطاقات، SSL، والإعدادات الأساسية.',
        'icon'  => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>',
    ],
    [
        'title' => 'تصميم احترافي مخصص',
        'desc'  => 'هوية بصرية مميزة، تخصيص كامل للقالب، وتجربة مستخدم سلسة.',
        'icon'  => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>',
    ],
    [
        'title' => 'ربط بوابات الدفع',
        'desc'  => 'مدى، Apple Pay، Tabby، Tamara، STC Pay — كل طرق الدفع التي يستخدمها عملاؤك.',
        'icon'  => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>',
    ],
    [
        'title' => 'تحسين السرعة والأداء',
        'desc'  => 'أوقات تحميل سريعة تُحسّن تجربة المستخدم وترفع ترتيبك في محركات البحث.',
        'icon'  => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>',
    ],
    [
        'title' => 'تهيئة السيو التقني',
        'desc'  => 'عناوين، أوصاف، Schema، روابط داخلية — كل ما تحتاجه لبدء الظهور في جوجل.',
        'icon'  => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>',
    ],
    [
        'title' => 'تدريب وتسليم كامل',
        'desc'  => 'تدريب فريقك على إدارة المنصة — إضافة محتوى، متابعة الطلبات، والتقارير.',
        'icon'  => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>',
    ],
];

// Default FAQs
$default_faqs = [
    [
        'question' => 'هل هذه المنصة تناسب مشروعي؟',
        'answer'   => 'نجري تحليلاً مجانياً لاحتياجاتك قبل أي توصية. احجز استشارة ونحدد معاً الأنسب لك.',
    ],
    [
        'question' => 'كم يستغرق إعداد المشروع؟',
        'answer'   => 'معظم المشاريع تنتهي خلال 2–4 أسابيع حسب الحجم والتعقيد. نُحدد جدولاً واضحاً منذ البداية.',
    ],
    [
        'question' => 'هل يشمل التسليم التدريب على الإدارة؟',
        'answer'   => 'نعم، نُقدّم جلسة تدريبية كاملة لفريقك حتى تتمكن من إدارة المشروع باستقلالية.',
    ],
    [
        'question' => 'ماذا يحدث بعد إطلاق المشروع؟',
        'answer'   => 'نُقدّم دعماً فنياً لمدة شهر بعد الإطلاق، ويمكنك الاشتراك في باقة الصيانة الشهرية.',
    ],
];
$faq_items = ! empty( $faqs ) ? $faqs : $default_faqs;

$why_h2  = $why_title ?: 'لماذا ' . $display_tag . ' هو الخيار الصحيح؟';
$why_p   = $why_desc  ?: 'نساعدك في الاستفادة القصوى من هذه المنصة — من الإعداد الأول حتى النتائج الفعلية.';
$why_q   = $why_quote ?: '"الأداة الصحيحة في يد فريق محترف = نتائج تستحق الاستثمار"';
?>

<!-- Hero ─────────────────────────────────────────────── -->
<section class="svc-hero" style="text-align:center">
  <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.04) 1px,transparent 1px);background-size:36px 36px;mask-image:radial-gradient(ellipse 90% 80% at 50% 50%,#000 10%,transparent 75%);pointer-events:none"></div>
  <div style="position:absolute;border-radius:50%;filter:blur(60px);pointer-events:none;inset-inline-start:-200px;bottom:-100px;width:600px;height:600px;background:radial-gradient(circle,rgba(30,46,245,.22),transparent 65%)"></div>
  <div style="position:absolute;border-radius:50%;filter:blur(60px);pointer-events:none;inset-inline-end:-100px;top:0;width:480px;height:480px;background:radial-gradient(circle,<?php echo esc_attr( $dot_color ); ?>22,transparent 65%)"></div>
  <div class="wrap">
    <div style="position:relative;z-index:2;max-width:820px;margin-inline:auto">

      <div class="breadcrumb" style="justify-content:center;margin-bottom:22px">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">الرئيسية</a>
        <svg class="bc-sep" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        <a href="<?php echo esc_url( $parent_url ); ?>"><?php echo esc_html( $parent_label ); ?></a>
        <svg class="bc-sep" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        <span style="color:rgba(255,255,255,.55)"><?php echo esc_html( $display_tag ); ?></span>
      </div>

      <div class="acc-badge">
        <span class="dot" style="background:<?php echo esc_attr( $dot_color ); ?>;box-shadow:0 0 8px <?php echo esc_attr( $dot_color ); ?>"></span>
        <?php echo esc_html( $display_tag ); ?>
      </div>

      <?php if ( ! empty( $platform_logo['url'] ) ) : ?>
        <div style="margin-bottom:22px;display:flex;justify-content:center">
          <img src="<?php echo esc_url( $platform_logo['url'] ); ?>" alt="<?php echo esc_attr( $display_tag ); ?>" style="height:52px;width:auto;object-fit:contain;filter:brightness(0) invert(1)">
        </div>
      <?php endif; ?>

      <h1 style="font-size:clamp(32px,5vw,60px);font-weight:900;line-height:1.08;letter-spacing:-.03em;color:#fff;margin-bottom:16px">
        <?php if ( $hero_em ) : ?>
          <?php echo esc_html( $display_title ); ?> <em style="font-style:normal;background:linear-gradient(110deg,#7b90ff,#aab8ff 50%,#7b90ff);background-size:200% auto;-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;animation:sh 5s linear infinite"><?php echo esc_html( $hero_em ); ?></em>
        <?php else : ?>
          <?php echo wp_kses_post( $display_title ); ?>
        <?php endif; ?>
      </h1>

      <p style="font-size:clamp(15px,1.55vw,17.5px);line-height:1.9;color:rgba(255,255,255,.6);max-width:680px;margin-inline:auto;margin-bottom:30px"><?php echo esc_html( $display_desc ); ?></p>

      <div class="pbtns">
        <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-p lg">احجز استشارة مجانية</a>
        <a href="<?php echo esc_url( $parent_url ); ?>" class="btn btn-g lg"><?php echo esc_html( $parent_label ); ?></a>
      </div>
    </div>
  </div>
</section>

<!-- Why ─────────────────────────────────────────────── -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="why-grid">
      <div class="why-side sr">
        <span class="tag" style="margin-bottom:12px">لماذا <?php echo esc_html( $display_tag ); ?></span>
        <h2 style="font-size:clamp(24px,3.2vw,38px);font-weight:900;line-height:1.18;letter-spacing:-.025em;color:var(--ink);margin-bottom:18px"><?php echo esc_html( $why_h2 ); ?></h2>
        <p class="bod" style="margin-bottom:20px"><?php echo esc_html( $why_p ); ?></p>
        <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-p">احجز استشارة مجانية</a>
      </div>
      <div class="why-vis sr d1" style="--accent:<?php echo esc_attr( $dot_color ); ?>">
        <div style="position:absolute;inset:0;background:radial-gradient(circle at 30% 20%,<?php echo esc_attr( $dot_color ); ?>33,transparent 60%);pointer-events:none"></div>
        <div class="why-emoji"><?php echo esc_html( $emoji ); ?></div>
        <div class="why-quote"><?php echo esc_html( $why_q ); ?></div>
      </div>
    </div>
  </div>
</section>

<!-- When to use ─────────────────────────────────────── -->
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag">متى <?php echo esc_html( $display_tag ); ?> هو الخيار</span>
      <h2 class="h2"><?php echo esc_attr( count( $when_items ) ); ?> حالات تتفوّق فيها</h2>
      <p class="bod">لو وجدت نفسك في أيٍّ من هذه الحالات، <?php echo esc_html( $display_tag ); ?> هو الاختيار الأمثل لمشروعك.</p>
    </div>
    <div class="when-grid">
      <?php foreach ( $when_items as $i => $item ) :
          $dc = [ '', 'd1', 'd2', 'd3' ][ $i % 4 ];
      ?>
      <div class="when-card sr <?php echo esc_attr( $dc ); ?>">
        <div class="when-ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="when-body">
          <h3><?php echo esc_html( $item['title'] ?? '' ); ?></h3>
          <p><?php echo esc_html( $item['desc'] ?? '' ); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- What we deliver ─────────────────────────────────── -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag">ماذا نقدّم</span>
      <h2 class="h2">خدمة <?php echo esc_html( $display_tag ); ?> الكاملة</h2>
      <p class="bod">من تخطيط الهيكل حتى التسليم والدعم — كل خطوة بإتقان.</p>
    </div>
    <div class="serv-grid">
      <?php foreach ( $deliverables as $i => $card ) :
          $dc = [ '', 'd1', 'd2', 'd3', 'd1', 'd2' ][ $i % 6 ];
      ?>
      <div class="serv-card sr <?php echo esc_attr( $dc ); ?>">
        <div class="serv-ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?php echo $card['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></svg>
        </div>
        <h3><?php echo esc_html( $card['title'] ); ?></h3>
        <p><?php echo esc_html( $card['desc'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- FAQ ─────────────────────────────────────────────── -->
<section class="sec sec-off">
  <div class="wrap">
    <div class="faq-cta-layout">
      <div>
        <div class="sh sr">
          <span class="tag">الأسئلة الشائعة</span>
          <h2 class="h2">أسئلة عن <?php echo esc_html( $display_tag ); ?></h2>
        </div>
        <div class="faq-list sr d1">
          <?php foreach ( $faq_items as $faq ) : ?>
          <div class="faq-item">
            <div class="faq-q">
              <span><?php echo esc_html( $faq['question'] ?? '' ); ?></span>
              <div class="faq-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              </div>
            </div>
            <div class="faq-a">
              <div class="faq-a-inner"><?php echo esc_html( $faq['answer'] ?? '' ); ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="cta-sticky">
        <div class="cta-side-card sr d1">
          <span class="tag d" style="position:relative;z-index:1;margin-bottom:10px">ابدأ الآن</span>
          <h3 style="font-size:clamp(20px,2.5vw,26px);font-weight:900;color:#fff;margin-bottom:12px;line-height:1.2;position:relative;z-index:1">جاهز لإطلاق مشروعك<br>على <?php echo esc_html( $display_tag ); ?>؟</h3>
          <p style="font-size:13.5px;color:rgba(255,255,255,.5);line-height:1.8;margin-bottom:22px;position:relative;z-index:1">احجز استشارة مجانية ولنناقش متطلباتك وعرض السعر المناسب.</p>
          <div class="chklist" style="margin-bottom:22px">
            <div class="chk-item d"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div>تحليل احتياجاتك بدقّة</div>
            <div class="chk-item d"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div>عرض سعر شفّاف</div>
            <div class="chk-item d"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div>بدون أي التزام</div>
          </div>
          <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-p" style="width:100%;justify-content:center;position:relative;z-index:1">احجز استشارة مجانية</a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
get_template_part( 'template-parts/layout/cta-banner', null, [
    'tag'   => 'ابدأ الآن',
    'title' => 'جاهز لبناء مشروعك على ' . $display_tag . '؟',
] );

get_footer();
