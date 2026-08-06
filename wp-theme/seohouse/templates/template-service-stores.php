<?php
/**
 * Template Name: Service — Stores Overview
 */
get_header();

$stores_hero_pre  = sh_field( 'stores_hero_pre',  null, 'إنشاء وتصميم' );
$stores_hero_em   = sh_field( 'stores_hero_em',   null, 'متجرك الإلكتروني' );
$stores_hero_desc = sh_field( 'stores_hero_desc', null, 'من فكرة إلى متجر جاهز للبيع — هيكل تجاري ذكي، تجربة شراء سلسة، وقابلية للنمو على أي منصة (سلة، زد، شوبيفاي، أو ووكومرس).' );

$stores_why_tag  = sh_field( 'stores_why_tag',  null, 'لماذا يهم إعداد المتجر' );
$stores_why_h2   = sh_field( 'stores_why_h2',   null, 'المتجر الإلكتروني<br>ليس مجرد منتجات على صفحة' );
$stores_why_body = sh_field( 'stores_why_body', null, 'الفرق بين متجر يبيع ومتجر يستهلك ميزانية إعلانك يبدأ من اللحظة الأولى — هيكل الفئات، خطوات الشراء، الثقة، وسرعة التحميل. كلها قرارات تُتّخذ في مرحلة الإعداد لا بعدها.' );

$_why_items_acf   = sh_field( 'stores_why_items' );
$_why_items_acf   = array_values( array_filter( (array) $_why_items_acf,   fn( $r ) => ! empty( $r['wi_bold'] ) || ! empty( $r['wi_tail'] ) ) );
$stores_why_items = ! empty( $_why_items_acf )
	? array_map( static fn( $i ) => [ 'b' => $i['wi_bold'] ?? '', 't' => $i['wi_tail'] ?? '' ], $_why_items_acf )
	: [
		[ 'b' => 'هيكل ذكي',              't' => '— فئات منظّمة تُسهّل الوصول للمنتج المناسب' ],
		[ 'b' => 'تجربة شراء سلسة',       't' => '— أقل خطوات، أقل تخلٍّ عن السلة' ],
		[ 'b' => 'عناصر ثقة واضحة',       't' => '— تقييمات، ضمان، أمان الدفع، توصيل' ],
		[ 'b' => 'أساس تقني سليم',        't' => '— استعداد للسيو والتسويق منذ اليوم الأول' ],
	];

$_faqs_acf    = sh_field( 'stores_faqs' );
$_faqs_acf    = array_values( array_filter( (array) $_faqs_acf,    fn( $r ) => ! empty( $r['question'] ) ) );
$stores_faqs  = ! empty( $_faqs_acf ) ? $_faqs_acf : [
	[ 'question' => 'ما المنصة الأنسب لمتجري؟',           'answer' => 'يعتمد على نوع منتجاتك، حجم عملك، خطط التوسع، والميزانية. في الاستشارة الأولى نناقش هذا معك بصراحة — أحياناً سلة أنسب، وأحياناً Shopify، حسب وضعك.' ],
	[ 'question' => 'كم يستغرق إطلاق المتجر؟',           'answer' => 'بين أسبوعين و6 أسابيع — يعتمد على حجم المتجر، عدد المنتجات، التخصيصات المطلوبة، والمنصة المختارة.' ],
	[ 'question' => 'هل يشمل المشروع رفع المنتجات؟',     'answer' => 'إعداد المتجر يشمل هيكل المتجر وعدد محدود من المنتجات النموذجية. رفع المنتجات خدمة منفصلة يمكن إضافتها للمشروع.' ],
	[ 'question' => 'هل تنقلون متجري من منصة لأخرى؟',   'answer' => 'نعم. ننقل المنتجات، الطلبات، العملاء، وأهم الروابط (مع الحفاظ على السيو) من منصة إلى أخرى — بدون فقدان بياناتك أو ترتيبك.' ],
	[ 'question' => 'هل يمكن البدء بالسيو من اليوم الأول؟', 'answer' => 'نعم — وننصح بذلك. نبني المتجر بأساس سيو سليم منذ البداية، ثم تنطلق خدمة السيو للمتاجر لاستثمار الفترة قبل الإطلاق وبعده.' ],
];

$stores_side_cta_h3   = sh_field( 'stores_side_cta_h3',   null, "جاهز لتجهيز\nمتجرك للبيع؟" );
$stores_side_cta_desc = sh_field( 'stores_side_cta_desc', null, 'احجز استشارة مجانية، وسنناقش معاً المنصة الأنسب وخطة الإطلاق.' );
$stores_side_cta_btn  = sh_field( 'stores_side_cta_btn',  null, 'احجز استشارة مجانية' );
$_scta_checks_acf     = sh_field( 'stores_side_cta_checks' );
$_scta_checks_acf     = array_values( array_filter( (array) $_scta_checks_acf, fn( $r ) => ! empty( $r['sc_text'] ) ) );
$stores_side_checks   = ! empty( $_scta_checks_acf )
	? array_map( static fn( $i ) => $i['sc_text'] ?? '', $_scta_checks_acf )
	: [ 'توصية بالمنصة الأنسب', 'عرض سعر شفاف', 'بدون أي التزام' ];

$stores_cta_title = sh_field( 'stores_cta_title', null, 'ابدأ متجرك الإلكتروني اليوم' );
$stores_cta_desc  = sh_field( 'stores_cta_desc',  null, 'من الفكرة إلى أول طلب — نُرافقك في كل خطوة.' );
?>

<!-- Hero -->
<section class="svc-hero">
  <div class="wrap">
    <div class="svc-hero-inner">
      <div class="breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">الرئيسية</a>
        <svg class="bc-sep" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        <span class="bc-current">إنشاء وتصميم متاجر</span>
      </div>
      <span class="tag d" style="margin-bottom:18px">خدمة متخصصة</span>
      <h1 class="svc-hero-h1"><?php echo esc_html( $stores_hero_pre ); ?> <em><?php echo esc_html( $stores_hero_em ); ?></em></h1>
      <p class="page-hero-p"><?php echo esc_html( $stores_hero_desc ); ?></p>
      <div class="pbtns">
        <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-p lg">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          احجز استشارة مجانية
        </a>
        <a href="#platforms" class="btn btn-g lg">المنصات التي نعمل عليها</a>
      </div>
    </div>
  </div>
</section>

<!-- Why store setup matters -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="why-grid">
      <div class="sr">
        <span class="tag" style="margin-bottom:12px"><?php echo esc_html( $stores_why_tag ); ?></span>
        <h2 class="h2" style="margin-bottom:16px"><?php echo wp_kses_post( nl2br( esc_html( $stores_why_h2 ) ) ); ?></h2>
        <p class="bod" style="margin-bottom:18px"><?php echo esc_html( $stores_why_body ); ?></p>
        <div class="chklist sr d1" style="margin-bottom:24px">
          <?php foreach ( $stores_why_items as $ck ) : ?>
          <div class="chk-item"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div><span><strong><?php echo esc_html( $ck['b'] ); ?></strong><?php echo $ck['t'] ? ' ' . esc_html( $ck['t'] ) : ''; ?></span></div>
          <?php endforeach; ?>
        </div>
        <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-p sr d2">احجز استشارة مجانية</a>
      </div>
      <div class="sr d1">
        <div style="background:var(--navy-2);border-radius:var(--r4);padding:28px;position:relative;overflow:hidden">
          <div style="position:absolute;inset-inline-end:-50px;top:-50px;width:220px;height:220px;border-radius:50%;background:radial-gradient(circle,rgba(30,46,245,.28),transparent 70%)"></div>
          <div style="font-size:9.5px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.22);margin-bottom:18px;position:relative;z-index:1">رحلة العميل في متجرك</div>
          <div style="display:flex;flex-direction:column;gap:9px;position:relative;z-index:1">
            <div style="display:flex;align-items:center;gap:10px;padding:13px 14px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:var(--r2)">
              <div style="width:26px;height:26px;border-radius:7px;background:rgba(30,46,245,.2);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:900;color:#7b90ff;flex-shrink:0">1</div>
              <div style="font-size:13px;color:rgba(255,255,255,.72)">يصل من البحث / الإعلان</div>
            </div>
            <div style="display:flex;align-items:center;gap:10px;padding:13px 14px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:var(--r2)">
              <div style="width:26px;height:26px;border-radius:7px;background:rgba(30,46,245,.2);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:900;color:#7b90ff;flex-shrink:0">2</div>
              <div style="font-size:13px;color:rgba(255,255,255,.72)">يتصفّح الفئات بسهولة</div>
            </div>
            <div style="display:flex;align-items:center;gap:10px;padding:13px 14px;background:rgba(30,46,245,.14);border:1px solid rgba(30,46,245,.28);border-radius:var(--r2)">
              <div style="width:26px;height:26px;border-radius:7px;background:var(--blue);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:900;color:#fff;flex-shrink:0">3</div>
              <div style="font-size:13px;color:rgba(255,255,255,.85)">يثق بالمتجر ويُضيف للسلة</div>
            </div>
            <div style="display:flex;align-items:center;gap:10px;padding:13px 14px;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.2);border-radius:var(--r2)">
              <div style="width:26px;height:26px;border-radius:7px;background:rgba(16,185,129,.3);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:900;color:var(--green);flex-shrink:0">4</div>
              <div style="font-size:13px;color:rgba(255,255,255,.72)">يُكمل الدفع — ويعود مرة ثانية</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- What We Do -->
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">ماذا نفعل في إعداد المتجر</span><h2 class="h2">من خطة الفئات حتى أول طلب</h2><p class="bod">إعداد المتجر ليس مجرد "تثبيت قالب" — هو سلسلة قرارات تحدّد مدى نجاحه التجاري لاحقاً.</p></div>
    <div class="wd-grid">
      <div class="wd-card sr"><div class="wd-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 7h18M3 12h18M3 17h18"/></svg></div><div class="wd-body"><h3>هيكلة الفئات والأقسام</h3><p>تنظيم منطقي للفئات الرئيسية والفرعية يجعل التصفح بديهياً ويُسرّع وصول العميل للمنتج المناسب.</p></div></div>
      <div class="wd-card sr d1"><div class="wd-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 11-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 11-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 11-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 110-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 112.83-2.83l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 114 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 112.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 110 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg></div><div class="wd-body"><h3>إعداد المنصة كاملاً</h3><p>تثبيت، تخصيص، ربط الدومين، الشحن، الضرائب (الزكاة)، بوابات الدفع المحلية — جاهز للعمل.</p></div></div>
      <div class="wd-card sr d2"><div class="wd-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8m-4-4v4"/></svg></div><div class="wd-body"><h3>تصميم الواجهة وهوية المتجر</h3><p>تخصيص القالب ليعكس علامتك التجارية — ألوان، خطوط، عناصر بصرية، وعناصر ثقة في الأماكن الصحيحة.</p></div></div>
      <div class="wd-card sr d3"><div class="wd-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg></div><div class="wd-body"><h3>صفحات المنتج والسلة</h3><p>تصميم صفحات منتج تبيع — صور، أوصاف، خيارات، تقييمات — وتدفّق سلة وخروج مدروس.</p></div></div>
      <div class="wd-card sr"><div class="wd-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg></div><div class="wd-body"><h3>ربط الشحن والدفع</h3><p>إعداد شركات الشحن المحلية (سمسا، أرامكس، توصيل)، وبوابات الدفع (مدى، Apple Pay، التحويل البنكي).</p></div></div>
      <div class="wd-card sr d1"><div class="wd-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 17v-2m3 2v-4m3 4v-6"/><rect x="3" y="3" width="18" height="18" rx="2"/></svg></div><div class="wd-body"><h3>أساس سيو سليم</h3><p>هيكل URL صحيح، روابط داخلية، صفحات فئات جاهزة للتحسين، خرائط موقع، وبيانات هيكلية للمنتجات.</p></div></div>
      <div class="wd-card sr d2"><div class="wd-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div><div class="wd-body"><h3>ربط أدوات القياس</h3><p>Google Analytics، Tag Manager، Meta Pixel، TikTok — حتى تعرف من أين تأتي مبيعاتك من اليوم الأول.</p></div></div>
      <div class="wd-card sr d3"><div class="wd-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg></div><div class="wd-body"><h3>تدريب وتسليم</h3><p>تدريب فريقك على إدارة المتجر — إضافة منتجات، إدارة الطلبات، الردود، والتقارير.</p></div></div>
    </div>
  </div>
</section>

<!-- Platform Cards -->
<section id="platforms" class="sec sec-white">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">المنصات</span><h2 class="h2">نعمل على المنصات الأربع الرائدة</h2><p class="bod">لكل منصة طبيعتها — نختار معك المنصة الأنسب لمنتجك، حجم عملك، وخطط نموّك.</p></div>
    <div class="ep-plat-grid">
      <?php
      foreach ( [
          [
              'name' => 'سلة', 'abbr' => 'سل', 'color' => '#00b286', 'flag' => 'سعودية',
              'tag'  => 'الأكثر انتشاراً في السوق السعودي',
              'desc' => 'منصة سعودية متكاملة بتركيز قوي على السوق المحلي — بوابات دفع جاهزة، شركات شحن، وتطبيقات محلية. مثالية للتجّار الذين يريدون البدء سريعاً.',
              'key'  => 'salla',
              'path' => 'services/salla',
              'feats' => [ 'إعداد كامل وتخصيص القالب', 'ربط بوابات الدفع وشركات الشحن المحلية', 'تثبيت تطبيقات سلة الإضافية حسب الحاجة' ],
          ],
          [
              'name' => 'زد', 'abbr' => 'زد', 'color' => '#5b4fcf', 'flag' => 'سعودية',
              'tag'  => 'مرونة تقنية أعلى للتجّار المتقدمين',
              'desc' => 'منصة سعودية تمنح التاجر مرونة أكبر في التخصيص والتحكم — مناسبة للمتاجر التي تنوي التوسع وبناء هوية متجر مميّزة.',
              'key'  => 'zid',
              'path' => 'services/zid',
              'feats' => [ 'إعداد المتجر بهيكل قابل للتوسع', 'تخصيص متقدم للقالب وتجربة المستخدم', 'دعم التكاملات المتقدمة مع الأنظمة المحاسبية' ],
          ],
          [
              'name' => 'شوبيفاي', 'abbr' => 'Sh', 'color' => '#95bf47', 'flag' => 'عالمية',
              'tag'  => 'للتجار الذين يستهدفون السوق الخليجي والعالمي',
              'desc' => 'منصة عالمية بتطبيقات لا حصر لها وأدوات تسويق متقدمة. مثالية للعلامات التجارية التي تستهدف السعودية والخليج معاً، أو تخطّط للتوسع دولياً.',
              'key'  => 'shopify',
              'path' => 'services/shopify',
              'feats' => [ 'تخصيص Liquid وتطوير القالب', 'ربط بوابات الدفع المحلية والعالمية', 'إدارة التطبيقات وتحسين الأداء' ],
          ],
          [
              'name' => 'ووكومرس', 'abbr' => 'Wc', 'color' => '#7f54b3', 'flag' => 'مفتوح المصدر',
              'tag'  => 'حرية كاملة على ووردبريس — لمن يريد التحكم الكامل',
              'desc' => 'للأعمال التي تريد تحكماً كاملاً في كل تفصيل — التصميم، التقنية، السيو. مناسبة للمتاجر ذات المتطلبات الخاصة أو التكاملات المعقدة.',
              'key'  => 'woocommerce',
              'path' => 'services/woocommerce',
              'feats' => [ 'تطوير ووردبريس وووكومرس مخصص', 'أمان متقدم واستضافة محسّنة للأداء', 'تكاملات مخصصة مع أنظمة ERP/CRM' ],
          ],
      ] as $plat ) :
          $plat_result  = sh_service_permalink( $plat['key'], $plat['path'] );
          $plat_url     = $plat_result['url'];
          $plat_status  = $plat_result['status'];
          $plat_has_url = $plat_url !== '';
          $plat_tag     = $plat_has_url ? 'a' : 'div';
          $plat_href    = $plat_has_url ? ' href="' . esc_url( $plat_url ) . '"' : '';
      ?>
      <<?php echo $plat_tag; ?><?php echo $plat_href; ?> class="ep-plat-card sr" style="<?php echo $plat_has_url ? 'text-decoration:none;color:inherit' : ''; ?>">
        <div class="ep-plat-head">
          <div class="ep-plat-head-l">
            <div class="ep-plat-logo" style="background:<?php echo esc_attr( $plat['color'] ); ?>"><?php echo esc_html( $plat['abbr'] ); ?></div>
            <div class="ep-plat-name"><?php echo esc_html( $plat['name'] ); ?></div>
          </div>
          <span class="ep-plat-flag"><?php echo esc_html( $plat['flag'] ); ?></span>
        </div>
        <div class="ep-plat-body">
          <div class="ep-plat-tag"><?php echo esc_html( $plat['tag'] ); ?></div>
          <div class="ep-plat-desc"><?php echo esc_html( $plat['desc'] ); ?></div>
          <div class="ep-plat-feats">
            <?php foreach ( $plat['feats'] as $feat ) : ?>
            <div class="ep-plat-feat"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg><?php echo esc_html( $feat ); ?></div>
            <?php endforeach; ?>
          </div>
          <?php if ( $plat_has_url ) : ?>
          <span style="display:inline-flex;align-items:center;gap:6px;margin-top:18px;font-size:13.5px;font-weight:700;color:var(--blue)">اعرف المزيد <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
          <?php elseif ( current_user_can( 'manage_options' ) ) : ?>
          <?php if ( 'duplicate' === $plat_status ) : ?>
          <div style="margin-top:14px;font-size:11px;font-weight:700;color:#dc2626;background:rgba(220,38,38,.08);border:1px solid rgba(220,38,38,.4);border-radius:4px;padding:4px 8px;direction:ltr">Admin: duplicate service_card_key "<?php echo esc_html( $plat['key'] ); ?>" — assign this key to exactly one page</div>
          <?php else : ?>
          <div style="margin-top:14px;font-size:11px;font-weight:700;color:#e6a817;background:rgba(230,168,23,.12);border:1px solid rgba(230,168,23,.4);border-radius:4px;padding:4px 8px;direction:ltr">Admin: no page with service_card_key "<?php echo esc_html( $plat['key'] ); ?>" — assign this key in the page editor</div>
          <?php endif; ?>
          <?php endif; ?>
        </div>
      </<?php echo $plat_tag; ?>>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Sales-ready -->
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">جاهز للبيع</span><h2 class="h2">ستة معايير تجعل متجرنا يبيع من اليوم الأول</h2></div>
    <div class="sr-grid">
      <div class="sr-card sr"><div class="sr-n">01</div><h3>سرعة تحميل عالية</h3><p>المتجر البطيء يفقد 40% من الزوار قبل أن يتصفّحوا منتجاً واحداً. نُحسّن السرعة من البداية.</p></div>
      <div class="sr-card sr d1"><div class="sr-n">02</div><h3>تجربة جوال متكاملة</h3><p>أكثر من 70% من الطلبات في السعودية تأتي من الجوال — التصميم يبدأ من الموبايل لا ينتهي إليه.</p></div>
      <div class="sr-card sr d2"><div class="sr-n">03</div><h3>عناصر ثقة في كل صفحة</h3><p>تقييمات، شعارات الدفع، سياسة استرجاع واضحة، ضمان — تطمئن المشتري قبل أن يدفع.</p></div>
      <div class="sr-card sr"><div class="sr-n">04</div><h3>صفحة منتج تبيع</h3><p>صور متعددة وعالية الجودة، أوصاف واضحة، تقييمات، خيارات، وزر شراء بارز — كل ما يحتاجه قرار الشراء.</p></div>
      <div class="sr-card sr d1"><div class="sr-n">05</div><h3>سلة وخروج بأقل احتكاك</h3><p>أقل عدد ممكن من الخطوات، طرق دفع متعددة، وعدم إجبار التسجيل — لتقليل التخلّي عن السلة.</p></div>
      <div class="sr-card sr d2"><div class="sr-n">06</div><h3>قابلية للنمو والتسويق</h3><p>هيكل تقني يدعم السيو، الإعلانات، الإيميل ماركتنغ، والريتارجيتنغ — عملك ينمو معك.</p></div>
    </div>
  </div>
</section>

<!-- UX Considerations -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">قرارات تجربة المستخدم</span><h2 class="h2">تفاصيل صغيرة تصنع فرقاً كبيراً في المبيعات</h2><p class="bod">في كل مشروع متجر، ندرس هذه القرارات معك بعناية — لأن تأثيرها على المبيعات يفوق تأثير التصميم نفسه.</p></div>
    <div class="ux-list sr d1">
      <div class="ux-item"><div class="ux-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 7h18M3 12h18M3 17h18"/></svg></div><div class="ux-body"><strong>هيكل التنقل والفئات</strong><p>كم فئة، كم مستوى، أين تظهر القائمة، وما الفلاتر المهمة في كل فئة</p></div></div>
      <div class="ux-item"><div class="ux-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg></div><div class="ux-body"><strong>البحث الذكي والفلاتر</strong><p>بحث يفهم الكلمات الشائعة، فلاتر السعر/الماركة/المقاس بشكل واضح</p></div></div>
      <div class="ux-item"><div class="ux-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="3" x2="9" y2="21"/></svg></div><div class="ux-body"><strong>صفحة المنتج</strong><p>عدد الصور، طريقة عرض الخيارات، مكان زر الشراء، التقييمات</p></div></div>
      <div class="ux-item"><div class="ux-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17"/></svg></div><div class="ux-body"><strong>السلة والخروج</strong><p>كم خطوة، إجبار التسجيل أم لا، طرق الدفع، حقول الشحن</p></div></div>
      <div class="ux-item"><div class="ux-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 12l2 2 4-4M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2s10 4.477 10 10z"/></svg></div><div class="ux-body"><strong>عناصر بناء الثقة</strong><p>أين تظهر التقييمات، شعارات الدفع، ضمانات، سياسة الاسترجاع</p></div></div>
      <div class="ux-item"><div class="ux-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8m-4-4v4"/></svg></div><div class="ux-body"><strong>تجربة الجوال خصيصاً</strong><p>أزرار بحجم الإصبع، نموذج خروج مبسّط، صور سريعة التحميل</p></div></div>
    </div>
  </div>
</section>

<!-- Who is this for -->
<section class="sec sec-navy">
  <div class="wrap">
    <div class="sh c sr"><span class="tag d">لمن هذه الخدمة</span><h2 class="h2 wh">هل أنت من هؤلاء؟</h2><p class="bod d">إذا تعرّفت على نفسك في أيٍّ من هذه الحالات، نحن نُحبّ مساعدتك.</p></div>
    <div class="who-grid">
      <div class="who-card sr"><div class="who-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg></div><h3>تاجر يبدأ من الصفر</h3><p>عندك منتج جاهز وتريد متجراً احترافياً يبيع — لا قالباً عشوائياً تكتشف عيوبه بعد ستة أشهر.</p></div>
      <div class="who-card sr d1"><div class="who-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 3v18h18"/><path d="M18 17V9m-4 8V5m-4 12v-2"/></svg></div><h3>متجر قائم يحتاج إعادة بناء</h3><p>متجرك يبيع لكنه يستهلك إعلانات أكثر مما يبيع، أو لا يستطيع التوسع لأن هيكله الحالي محدود.</p></div>
      <div class="who-card sr d2"><div class="who-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg></div><h3>علامة تجارية تتوسّع</h3><p>عندك علامة تجارية تنمو وتحتاج متجراً يعكس قيمتها — تصميم احترافي، تجربة شراء سلسة، وقابلية للتوسع.</p></div>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="sec sec-off">
  <div class="wrap">
    <div class="faq-cta-layout">
      <div>
        <div class="sh sr"><span class="tag">الأسئلة الشائعة</span><h2 class="h2">أسئلة عن إعداد المتاجر</h2></div>
        <div class="faq-list sr d1">
          <?php foreach ( $stores_faqs as $faq ) :
              $q = $faq['question'] ?? ( $faq['q'] ?? '' );
              $a = $faq['answer']   ?? ( $faq['a'] ?? '' );
          ?>
          <div class="faq-item">
            <div class="faq-q"><span><?php echo esc_html( $q ); ?></span><div class="faq-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></div></div>
            <div class="faq-a"><div class="faq-a-inner"><?php echo esc_html( $a ); ?></div></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="cta-sticky">
        <div class="cta-side-card sr d1">
          <span class="tag d" style="position:relative;z-index:1;margin-bottom:10px">ابدأ الآن</span>
          <h3 style="font-size:clamp(20px,2.5vw,26px);font-weight:900;color:#fff;margin-bottom:12px;line-height:1.2;position:relative;z-index:1"><?php echo nl2br( esc_html( $stores_side_cta_h3 ) ); ?></h3>
          <p style="font-size:13.5px;color:rgba(255,255,255,.44);line-height:1.8;margin-bottom:22px;position:relative;z-index:1"><?php echo esc_html( $stores_side_cta_desc ); ?></p>
          <div class="chklist" style="margin-bottom:22px">
            <?php foreach ( $stores_side_checks as $ck ) : ?>
            <div class="chk-item d"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div><?php echo esc_html( $ck ); ?></div>
            <?php endforeach; ?>
          </div>
          <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-p" style="width:100%;justify-content:center;position:relative;z-index:1"><?php echo esc_html( $stores_side_cta_btn ); ?></a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
get_template_part( 'template-parts/layout/cta-banner', null, [
    'tag'         => 'ابدأ الآن',
    'title'       => $stores_cta_title,
    'description' => $stores_cta_desc,
    'buttons'     => [
        [ 'text' => 'احجز استشارة مجانية', 'url' => sh_page_url( 'contact' ), 'class' => 'btn-w lg' ],
        [ 'text' => 'خدمة رفع المنتجات', 'url' => sh_page_url( 'services/products' ), 'class' => 'btn-g lg' ],
    ],
] );
?>

<?php get_footer(); ?>
