<?php
/**
 * Template Name: Service — SEO Country Page
 *
 * Reusable template for market-specific SEO landing pages.
 * Create a child WordPress page under /services/seo/ with one of the
 * following slugs and assign this template:
 *   egypt          → /services/seo/egypt/
 *   saudi-arabia   → /services/seo/saudi-arabia/
 *   uae            → /services/seo/uae/
 *
 * Content is managed via ACF fields (same group as the SEO main template)
 * with rich Arabic-default fallbacks so pages render immediately after
 * creation with no ACF configuration required.
 */

// Canonical tag — output only when no SEO plugin handles it.
add_action( 'wp_head', function () {
    if ( ! class_exists( 'WPSEO_Frontend' ) && ! class_exists( 'RankMath' ) && ! class_exists( 'The_SEO_Framework_Loader' ) ) {
        echo '<link rel="canonical" href="' . esc_url( get_permalink() ) . '">' . "\n";
    }
}, 1 );

get_header();

// ─ Country configuration ─────────────────────────────────────────────────────

$slug = get_post_field( 'post_name', get_the_ID() );

$country_config = [

    'egypt' => [
        'area_served_en'   => 'Egypt',
        'area_served_wiki' => 'https://en.wikipedia.org/wiki/Egypt',
        'name'         => 'مصر',
        'hero_tag'     => 'تحسين محركات البحث في مصر',
        'hero_title'   => 'شركة <em>تحسين محركات البحث</em> في مصر',
        'hero_desc'    => 'السوق المصري يضم أكثر من 50 مليون مستخدم إنترنت — أكبر قاعدة بحث عربية على الإطلاق. من يستثمر في السيو الآن يبني ميزة تنافسية يصعب اللحاق بها لاحقاً.',
        'intro_tag'    => 'السوق المصري',
        'intro_title'  => 'لماذا السيو في مصر مختلف؟',
        'intro_desc'   => 'مصر سوق ضخم وتنافسي في آنٍ — كثافة الطلب على البحث عالية، لكن الاستثمار في السيو لا يزال دون المستوى في كثير من القطاعات. هذا يعني فرصاً حقيقية لمن يتحرك بجدية.',
        'points'       => [
            [ 'label' => 'قاعدة مستخدمين ضخمة',       'desc' => 'أكثر من 50 مليون مستخدم إنترنت يبحثون يومياً بالعربية وباللهجة المصرية' ],
            [ 'label' => 'منافسة قابلة للاختراق',       'desc' => 'معظم القطاعات لم تصل بعد لمستوى تشبّع حقيقي — الفرص موجودة لمن يبادر' ],
            [ 'label' => 'تجارة إلكترونية في تسارع',   'desc' => 'متاجر الإنترنت المصرية تنمو بوتيرة متصاعدة — السيو أداة تنافسية حاسمة' ],
            [ 'label' => 'بحث بالفصحى واللهجة معاً',  'desc' => 'المستخدم المصري يمزج بين الاثنين — نستهدف كليهما في الاستراتيجية' ],
        ],
        'why_tag'      => 'استراتيجية محلية',
        'why_title'    => 'كيف نخدم عملاءنا في مصر؟',
        'why_items'    => [
            [ 'label' => 'تحليل الكلمات المفتاحية المصرية',  'desc' => 'فصحى وعامية وما بينهما — حسب طبيعة جمهور عملك' ],
            [ 'label' => 'تحليل المنافسين المحليين',          'desc' => 'من يتصدر نتائج جوجل في قطاعك المصري وكيف نتجاوزه' ],
            [ 'label' => 'محتوى يعكس ثقافة السوق',           'desc' => 'كُتّاب يفهمون الجمهور المصري — لا ترجمة مباشرة من سياقات أخرى' ],
            [ 'label' => 'تقارير مرتبطة بأهداف عملك',       'desc' => 'الليدز والمبيعات الداخلية — لا مجرد أرقام ترافيك عامة' ],
        ],
        'faqs'         => [
            [ 'question' => 'هل تعملون مع شركات ومتاجر في مصر؟',        'answer' => 'نعم. نخدم عملاء في مصر بشكل منتظم — شركات خدمية ومتاجر إلكترونية ومؤسسات تعليمية. نفهم طبيعة البحث المصري ونستهدف الكلمات التي يستخدمها المستخدم المصري فعلاً وليس كلمات عامة.' ],
            [ 'question' => 'هل تستهدفون البحث بالعامية المصرية؟',       'answer' => 'نعم. تحليل الكلمات المفتاحية لدينا يشمل الفصحى والعامية المصرية معاً — عدد كبير من عمليات البحث يتم باللهجة وخاصة في قطاعات مثل الأغذية والخدمات اليومية والتعليم.' ],
            [ 'question' => 'كيف تختلف استراتيجية السيو للسوق المصري؟', 'answer' => 'تختلف المنافسة قطاعاً بقطاع. التجارة الإلكترونية والصحة منافسة عالية في المدن الكبرى، لكن خارج القاهرة والإسكندرية ثمة فرص سهلة الاختراق نُحدّدها في مرحلة التدقيق.' ],
            [ 'question' => 'هل تعملون عن بُعد مع العملاء المصريين؟',   'answer' => 'نعم تماماً. نعمل بالكامل عن بُعد مع لقاءات متابعة دورية وتقارير شهرية — بغض النظر عن موقع العميل. عملاؤنا في القاهرة وغيرها يحصلون على نفس مستوى الخدمة والتواصل.' ],
            [ 'question' => 'متى أبدأ برؤية نتائج في السوق المصري؟',    'answer' => 'المؤشرات الأولى تظهر بين الشهر الثاني والثالث. النتائج الواضحة في الترافيك والليدز تتراكم بين الشهر الرابع والسادس. السيو في مصر — كأي سوق آخر — يحتاج صبراً وعمل متراكم.' ],
        ],
        'cta_title'    => 'ابدأ بكسب عملاء من جوجل في مصر',
    ],

    'saudi-arabia' => [
        'area_served_en'   => 'Saudi Arabia',
        'area_served_wiki' => 'https://en.wikipedia.org/wiki/Saudi_Arabia',
        'name'         => 'السعودية',
        'hero_tag'     => 'تحسين محركات البحث في السعودية',
        'hero_title'   => 'شركة <em>تحسين محركات البحث</em> في السعودية',
        'hero_desc'    => 'السوق السعودي يجمع بين معدل انتشار إنترنت يتجاوز 95% وقوة شرائية مرتفعة — كل ليد من البحث العضوي هنا يستحق الاستثمار.',
        'intro_tag'    => 'السوق السعودي',
        'intro_title'  => 'لماذا السيو في السعودية يستحق الاستثمار؟',
        'intro_desc'   => 'رؤية 2030 تُسرّع التحول الرقمي في المملكة — الشركات التي تبني حضورها العضوي اليوم ستجني ثمار ذلك لسنوات. المنافسة في السيو تنمو، لكن الفرص للشركات التي تتصرف بذكاء لا تزال قائمة.',
        'points'       => [
            [ 'label' => 'أعلى إنفاق رقمي في المنطقة',  'desc' => 'المستخدم السعودي من أكثر مستخدمي الإنترنت إنفاقاً — عائد الليد من البحث أعلى من كثير من الأسواق' ],
            [ 'label' => 'رؤية 2030 والتحول الرقمي',    'desc' => 'قطاعات جديدة تتشكل رقمياً — من يتصدر نتائج البحث اليوم يحتل مكانة يصعب إزاحتها' ],
            [ 'label' => 'منافسة متصاعدة تستدعي التبكير', 'desc' => 'الوكالات السعودية تنمو — التحرك المبكر يمنحك ميزة متراكمة يصعب على المنافسين تجاوزها' ],
            [ 'label' => 'بيئة البحث العربية الأولى',    'desc' => 'البحث بالعربية هو الأساس — مع حضور الإنجليزية للقطاعات الدولية والمستثمرين الأجانب' ],
        ],
        'why_tag'      => 'استراتيجية محلية',
        'why_title'    => 'كيف نخدم عملاءنا في السعودية؟',
        'why_items'    => [
            [ 'label' => 'كلمات مفتاحية بنية الشراء العالية', 'desc' => 'نستهدف العبارات التي يبحثها العميل السعودي حين يكون جاهزاً للشراء — لا مجرد الاستفسار' ],
            [ 'label' => 'تحليل منافسي الرياض وجدة',         'desc' => 'المنافسة تختلف بين المناطق — نُحلّل محلياً ونضع استراتيجية دقيقة' ],
            [ 'label' => 'سيو متاجر سلة وزد',               'desc' => 'خبرة واسعة في المنصتين الأكثر انتشاراً في السوق السعودي' ],
            [ 'label' => 'تقارير ترتبط بمؤشرات عملك',       'desc' => 'ليدز، مبيعات، وCAC — لا أرقام ترافيك معلقة في الهواء' ],
        ],
        'faqs'         => [
            [ 'question' => 'هل أنتم شركة سيو تخدم السعودية؟',          'answer' => 'نعم. سيو هاوس تخدم السوق السعودي بشكل مستمر — عملاء في الرياض وجدة والدمام وغيرها. نفهم طبيعة البحث السعودي والكلمات التي يستخدمها العميل في قطاعك تحديداً.' ],
            [ 'question' => 'كيف يختلف السيو في السعودية عن باقي الدول؟', 'answer' => 'المنافسة في السعودية أعلى في قطاعات كالعقارات والتجزئة والصحة — لكن قيمة الليد أعلى أيضاً. نُحلّل المنافسين محلياً ونبني استراتيجية تأخذ في الحسبان كثافة الكلمات في كل منطقة.' ],
            [ 'question' => 'هل تعملون مع متاجر سلة وزد في السعودية؟',   'answer' => 'نعم. لدينا خبرة تفصيلية في سيو متاجر سلة وزد — المنصتان الأكثر انتشاراً في السوق السعودي — وعملنا مع عشرات المتاجر في قطاعات الأزياء والإلكترونيات والمستلزمات المنزلية.' ],
            [ 'question' => 'ما العائد المتوقع من السيو في السعودية؟',    'answer' => 'يختلف بحسب القطاع والمنافسة. بشكل عام، العملاء الذين يستمرون 6 أشهر أو أكثر يرون نمواً في الليدز العضوية بمعدل 2–4x مقارنة ببداية الحملة — مع انخفاض في الاعتماد على الإعلانات المدفوعة.' ],
            [ 'question' => 'هل تحتاج عقداً طويلاً؟',                  'answer' => 'لا. نعمل بدون عقود ملزمة — نُثبت قيمتنا شهراً بعد شهر وأنت من يُقرّر الاستمرار. الالتزام الحقيقي يأتي من النتائج، لا من الأوراق.' ],
        ],
        'cta_title'    => 'ابدأ بكسب عملاء من جوجل في السعودية',
    ],

    'uae' => [
        'area_served_en'   => 'United Arab Emirates',
        'area_served_wiki' => 'https://en.wikipedia.org/wiki/United_Arab_Emirates',
        'name'         => 'الإمارات',
        'hero_tag'     => 'تحسين محركات البحث في الإمارات',
        'hero_title'   => 'شركة <em>تحسين محركات البحث</em> في الإمارات',
        'hero_desc'    => 'دبي وأبوظبي من أكثر الأسواق تنافسية في المنطقة — جمهور عربي ودولي يبحث في نفس الوقت، ويحتاج كل منهما استراتيجية سيو مختلفة.',
        'intro_tag'    => 'السوق الإماراتي',
        'intro_title'  => 'ما الذي يجعل السيو في الإمارات تحدياً مختلفاً؟',
        'intro_desc'   => 'الإمارات سوق يجمع مستخدمين من عشرات الجنسيات. جزء من جمهورك يبحث بالعربية وجزء بالإنجليزية — واستراتيجية السيو الناجحة هنا تُعالج هذا التنوع بدقة بدلاً من تجاهله.',
        'points'       => [
            [ 'label' => 'سوق ثنائي اللغة',               'desc' => 'العربية والإنجليزية معاً — نُحدّد لغة جمهورك بالتحليل لا بالافتراض' ],
            [ 'label' => 'منافسة دولية عالية',             'desc' => 'شركات عالمية تتنافس في نفس الفضاء — السيو الاحترافي هو ما يُميّزك عن الحشد' ],
            [ 'label' => 'قطاع العقارات والسياحة',         'desc' => 'من أكثر القطاعات كثافةً في البحث وأعلاها عائداً على الاستثمار في الإمارات' ],
            [ 'label' => 'قوة شرائية بين الأعلى عالمياً', 'desc' => 'المستخدم الإماراتي يبحث وينفق — الليد هنا من أعلى قيمة في المنطقة العربية بأكملها' ],
        ],
        'why_tag'      => 'استراتيجية محلية',
        'why_title'    => 'كيف نخدم عملاءنا في الإمارات؟',
        'why_items'    => [
            [ 'label' => 'تحليل الجمهور بالعربية والإنجليزية', 'desc' => 'نبدأ بتحديد لغة بحث جمهورك الفعلي — ثم نبني الاستراتيجية عليها' ],
            [ 'label' => 'تحليل المنافسين المحليين والدوليين', 'desc' => 'المنافس في دبي ليس فقط الشركة المحلية — بل الوكالة الدولية التي تستهدف نفس الكلمات' ],
            [ 'label' => 'محتوى يناسب ثقافة السوق',           'desc' => 'كتابة لا تنقل أسلوباً من سوق آخر — بل تخاطب الجمهور الإماراتي بطريقة تُقنعه' ],
            [ 'label' => 'تقارير مرتبطة بمؤشرات عملك',       'desc' => 'ليدز ومبيعات وCAC — لا مجرد أرقام زيارات لا تُترجَم إلى نتائج تجارية' ],
        ],
        'faqs'         => [
            [ 'question' => 'هل تعملون مع شركات في دبي وأبوظبي؟',         'answer' => 'نعم. نخدم عملاء في الإمارات بانتظام — في دبي وأبوظبي والشارقة. نفهم التنوع السوقي في الإمارات والفرق بين استهداف جمهور عربي وجمهور دولي في نفس الوقت.' ],
            [ 'question' => 'هل تُحسّنون المواقع بالعربية والإنجليزية؟', 'answer' => 'نعم — لكننا نبدأ دائماً بتحليل الجمهور المستهدف. هل هو عربي أم دولي أم مزيج؟ استراتيجية اللغة مبنية على هذا التحليل وليس على افتراض مسبق.' ],
            [ 'question' => 'ما القطاعات التي تعملون بها في الإمارات؟',   'answer' => 'لدينا خبرة في العقارات والسياحة والضيافة والتجارة الإلكترونية والتعليم والتقنية في الإمارات — وهي القطاعات الأكثر نشاطاً في نتائج جوجل في هذا السوق.' ],
            [ 'question' => 'كيف تتعاملون مع المنافسة الدولية؟',          'answer' => 'المنافسة الدولية تتطلب سيو أعمق — تقنياً ومحتوى وروابط. نُحلّل المنافسين الدوليين في قطاعك ونضع استراتيجية تُمكّنك من المنافسة الفعلية لا مجرد الظهور في الصفحة الثالثة.' ],
            [ 'question' => 'هل أحتاج لعقد طويل الأمد؟',                 'answer' => 'لا. نعمل بدون عقود ملزمة — كل شهر تُقرر بناءً على النتائج. الهدف دائماً هو أن تكون النتائج سبب استمرارك، لا الورقة المُوقَّعة.' ],
        ],
        'cta_title'    => 'ابدأ بكسب عملاء من جوجل في الإمارات',
    ],
];

// Fallback to Saudi Arabia if an unrecognised slug is used
$c = $country_config[ $slug ] ?? $country_config['saudi-arabia'];

// Allow ACF overrides on specific fields
$hero_tag   = sh_field( 'service_hero_tag' )  ?: $c['hero_tag'];
$hero_title = sh_field( 'service_hero_title' ) ?: $c['hero_title'];
$hero_desc  = sh_field( 'service_hero_desc' )  ?: $c['hero_desc'];
$faqs       = sh_field( 'service_faqs' );
$faq_data   = is_array( $faqs ) && count( array_filter( $faqs, fn( $r ) => ! empty( $r['question'] ) ) ) ? $faqs : $c['faqs'];

// Services and SEO parent URLs (used in breadcrumb and back-links)
$services_url = sh_page_url( 'services' );
$seo_url      = sh_page_url( 'services/seo' );
?>

<!-- BreadcrumbList JSON-LD schema — 4 levels: Home > Services > SEO > Country -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type": "ListItem", "position": 1, "name": "الرئيسية",              "item": "<?php echo esc_url( home_url( '/' ) ); ?>"},
    {"@type": "ListItem", "position": 2, "name": "الخدمات",               "item": "<?php echo esc_url( $services_url ); ?>"},
    {"@type": "ListItem", "position": 3, "name": "تحسين محركات البحث",    "item": "<?php echo esc_url( $seo_url ); ?>"},
    {"@type": "ListItem", "position": 4, "name": "<?php echo esc_js( $c['hero_tag'] ); ?>", "item": "<?php echo esc_url( get_permalink() ); ?>"}
  ]
}
</script>

<?php
/*
 * Service schema — output unconditionally.
 * The canonical suppression above is separate: when RankMath is active it
 * manages canonical tags itself, but it does NOT automatically generate a
 * country-specific Service entity, so we always output this block.
 */
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Service",
  "name": "<?php echo esc_js( $hero_tag ); ?>",
  "description": "<?php echo esc_js( $hero_desc ); ?>",
  "url": "<?php echo esc_url( get_permalink() ); ?>",
  "provider": {
    "@type": "Organization",
    "name": "سيو هاوس",
    "url": "<?php echo esc_url( home_url( '/' ) ); ?>"
  },
  "areaServed": {
    "@type": "Country",
    "name": "<?php echo esc_js( $c['area_served_en'] ); ?>",
    "sameAs": "<?php echo esc_js( $c['area_served_wiki'] ); ?>"
  },
  "serviceType": "Search Engine Optimization"
}
</script>

<!-- Hero -->
<section class="svc-hero">
  <div class="wrap">
    <div class="svc-hero-inner">
      <div class="breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">الرئيسية</a>
        <svg class="bc-sep" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        <a href="<?php echo esc_url( $services_url ); ?>">الخدمات</a>
        <svg class="bc-sep" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        <a href="<?php echo esc_url( $seo_url ); ?>">تحسين محركات البحث</a>
        <svg class="bc-sep" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        <span class="bc-current"><?php echo esc_html( $c['name'] ); ?></span>
      </div>
      <div class="h-badge"><span class="h-bdot"></span><?php echo esc_html( $hero_tag ); ?></div>
      <h1 class="svc-hero-h1"><?php echo wp_kses_post( $hero_title ); ?></h1>
      <p class="page-hero-p"><?php echo esc_html( $hero_desc ); ?></p>
      <div class="pbtns">
        <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-p lg">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          احجز استشارة مجانية — 30 دقيقة
        </a>
        <a href="#country-faq" class="btn btn-g lg">أسئلة شائعة</a>
      </div>
    </div>
  </div>
</section>

<!-- Intro: Why this market -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="why-com-grid">
      <div class="sr">
        <div class="sh">
          <span class="tag"><?php echo esc_html( $c['intro_tag'] ); ?></span>
          <h2 class="h2"><?php echo esc_html( $c['intro_title'] ); ?></h2>
          <p class="bod" style="margin-top:12px"><?php echo esc_html( $c['intro_desc'] ); ?></p>
        </div>
        <div class="chklist sr d1">
          <?php foreach ( $c['points'] as $pt ) : ?>
          <div class="chk-item">
            <div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div>
            <span><strong><?php echo esc_html( $pt['label'] ); ?></strong> — <?php echo esc_html( $pt['desc'] ); ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="sr d2">
        <div style="background:var(--navy-2);border-radius:var(--r4);padding:32px 28px;position:relative;overflow:hidden">
          <div style="position:absolute;inset-inline-end:-50px;top:-50px;width:240px;height:240px;border-radius:50%;background:radial-gradient(circle,rgba(30,46,245,.28),transparent 70%)"></div>
          <div style="font-size:9.5px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.22);margin-bottom:20px;position:relative;z-index:1">ما الذي نقدمه في <?php echo esc_html( $c['name'] ); ?></div>
          <div style="position:relative;z-index:1;display:flex;flex-direction:column;gap:11px">
            <?php
            foreach ( [
                'تحليل تقني وبحث كلمات مخصّص للسوق',
                'تحسين صفحات بحسب نية البحث المحلية',
                'محتوى يخاطب الجمهور بأسلوبه',
                'بناء روابط من مصادر موثوقة محلياً وعالمياً',
                'تقرير شهري يربط السيو بمؤشرات عملك',
            ] as $svc ) :
            ?>
            <div style="display:flex;align-items:center;gap:10px;padding:12px 14px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:var(--r2)">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#7b90ff" stroke-width="2.5" style="flex-shrink:0"><polyline points="20 6 9 17 4 12"/></svg>
              <span style="font-size:13.5px;font-weight:600;color:rgba(255,255,255,.82)"><?php echo esc_html( $svc ); ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Why / How we serve this market -->
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr"><span class="tag"><?php echo esc_html( $c['why_tag'] ); ?></span><h2 class="h2"><?php echo esc_html( $c['why_title'] ); ?></h2></div>
    <div class="features-grid sr d1" style="grid-template-columns:repeat(2,1fr)">
      <?php foreach ( $c['why_items'] as $wi ) : ?>
      <div class="feat-card">
        <div class="ico-box" style="margin-bottom:14px"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="18" height="18"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg></div>
        <h3><?php echo esc_html( $wi['label'] ); ?></h3>
        <p><?php echo esc_html( $wi['desc'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- FAQ -->
<section id="country-faq" class="sec sec-off">
  <div class="wrap">
    <div class="faq-cta-layout">
      <div>
        <div class="sh sr"><span class="tag">الأسئلة الشائعة</span><h2 class="h2">أسئلة عن السيو <?php echo esc_html( $c['name'] === 'مصر' ? 'في مصر' : ( $c['name'] === 'السعودية' ? 'في السعودية' : 'في الإمارات' ) ); ?></h2></div>
        <div class="faq-list sr d1">
          <?php foreach ( $faq_data as $faq ) : ?>
          <div class="faq-item">
            <div class="faq-q"><span><?php echo esc_html( $faq['question'] ?? '' ); ?></span><div class="faq-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></div></div>
            <div class="faq-a"><div class="faq-a-inner"><?php echo esc_html( $faq['answer'] ?? '' ); ?></div></div>
          </div>
          <?php endforeach; ?>
        </div>
        <!-- Back to main SEO service -->
        <div style="margin-top:28px;padding:20px 22px;background:#fff;border:1px solid var(--line);border-radius:var(--r2);display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap">
          <div>
            <div style="font-size:12px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px">الخدمة الرئيسية</div>
            <div style="font-size:14.5px;font-weight:800;color:var(--ink)">تحسين محركات البحث — الصفحة الرئيسية</div>
          </div>
          <a href="<?php echo esc_url( $seo_url ); ?>" class="btn btn-o">عرض الخدمة الرئيسية <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
        </div>
      </div>
      <div class="cta-sticky">
        <div class="cta-side-card sr d1">
          <span class="tag d" style="position:relative;z-index:1;margin-bottom:10px">ابدأ الآن</span>
          <h3 style="font-size:clamp(20px,2.5vw,26px);font-weight:900;color:#fff;margin-bottom:12px;line-height:1.2;position:relative;z-index:1">هل موقعك يحقق<br>أقصى ما يمكن<br><?php echo 'في ' . esc_html( $c['name'] ); ?>؟</h3>
          <p style="font-size:13.5px;color:rgba(255,255,255,.44);line-height:1.8;margin-bottom:22px;position:relative;z-index:1">استشارة 30 دقيقة مجانية — نُحلّل وضعك في السوق، نكشف الفرص الضائعة، ونضع معاً خطة الخطوات الأولى.</p>
          <div class="chklist" style="margin-bottom:22px">
            <div class="chk-item d"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div>تحليل أولي مجاني لموقعك</div>
            <div class="chk-item d"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div>كشف الفرص الضائعة في البحث</div>
            <div class="chk-item d"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div>توصيات قابلة للتنفيذ فوراً</div>
            <div class="chk-item d"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div>بدون أي التزام مسبق</div>
          </div>
          <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-p" style="width:100%;justify-content:center;position:relative;z-index:1">احجز استشارة مجانية</a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
get_template_part( 'template-parts/layout/cta-banner', null, [
    'tag'     => 'ابدأ الآن',
    'title'   => $c['cta_title'],
    'buttons' => [
        [ 'text' => 'احجز استشارة مجانية',    'url' => sh_page_url( 'contact' ), 'class' => 'btn-w lg' ],
        [ 'text' => 'تعرّف على خدمة السيو',   'url' => $seo_url,                 'class' => 'btn-g lg' ],
    ],
] );
?>

<?php get_footer(); ?>
