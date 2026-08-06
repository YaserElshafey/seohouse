<?php
/**
 * Template Name: Service — SEO Main
 */
get_header();

$hero_tag  = sh_field( 'service_hero_tag' )  ?: 'تحسين محركات البحث';
$hero_title = sh_field( 'service_hero_title' ) ?: 'شركة <em>تحسين محركات البحث</em> التي تُترجم البحث إلى مبيعات';
$hero_desc  = sh_field( 'service_hero_desc' )  ?: 'عملاؤك يبحثون عنك على جوجل اليوم — السؤال هو: هل يجدونك؟ نُحوّل الزيارات العضوية إلى ليدز ومبيعات حقيقية.';
$features   = sh_field( 'service_features' );
$steps      = sh_field( 'service_steps' );
$faqs       = sh_field( 'service_faqs' );

$seo_lost_title     = sh_field( 'seo_lost_title',      null, 'كل يوم يبحث عملاؤك — وكل يوم تخسر أنت' );
$seo_lost_desc      = sh_field( 'seo_lost_desc',       null, 'إذا كان موقعك لا يظهر في الصفحة الأولى، فأنت لا تخسر زيارة فقط — أنت تخسر طلباً وعرض سعر وعميلاً يذهب لمنافسك.' );
$seo_why_raw        = sh_field( 'seo_why_title',       null, "السيو ليس بنداً في الميزانية —\nهو خفض في تكلفة العميل" );
$seo_why_desc       = sh_field( 'seo_why_desc',        null, 'كل عميل يأتي عبر البحث العضوي يكلّفك أقل من نظيره عبر الإعلان. والأهم — يبقى يأتي حتى بعد توقفك عن الإنفاق.' );
$seo_why_title      = nl2br( esc_html( $seo_why_raw ) );
$seo_wwd_title      = sh_field( 'seo_wwd_title',       null, 'ستة محاور تنفيذية تشكّل خدمة السيو' );
$seo_wwd_desc       = sh_field( 'seo_wwd_desc',        null, 'السيو ليس مهمة واحدة — هو منظومة من ستة محاور كل واحد منها يدعم الآخر.' );
$seo_sub_title      = sh_field( 'seo_sub_title',       null, 'اختر الخدمة التي يحتاجها عملك' );
$seo_reporting_raw  = sh_field( 'seo_reporting_title', null, "تقارير تتكلم لغة عملك\n— لا لغة المطورين" );
$seo_reporting_title = nl2br( esc_html( $seo_reporting_raw ) );
$seo_reporting_desc  = sh_field( 'seo_reporting_desc', null, 'في معظم الوكالات تأتيك تقارير ممتلئة بمصطلحات جوجل لا تفهم منها شيئاً. عندنا التقرير يُظهر لك بوضوح: كم ليد جاء من البحث، كم بيع، وأين تذهب ميزانيتك.' );
$seo_proof_title    = sh_field( 'seo_proof_title',     null, 'نتائج حقيقية لعملاء حقيقيين' );
$seo_cta_title      = sh_field( 'seo_cta_title',       null, 'ابدأ بكسب عملاء يبحثون عنك فعلاً' );

$seo_hero_cta1    = sh_field( 'seo_hero_cta1',    null, 'احجز استشارة مجانية — 30 دقيقة' );
$seo_hero_cta2    = sh_field( 'seo_hero_cta2',    null, 'خدماتنا' );
$seo_hero_stats   = sh_field( 'seo_hero_stats',   null, [] );
$seo_lost_cards   = sh_field( 'seo_lost_cards',   null, [] );
$seo_why_items    = sh_field( 'seo_why_items',    null, [] );
$seo_impact_rows  = sh_field( 'seo_impact_rows',  null, [] );
$seo_sub_desc     = sh_field( 'seo_sub_desc',     null, 'كل خدمة يمكن تنفيذها مستقلة أو ضمن باقة سيو متكاملة — حسب وضع موقعك ومرحلتك.' );
$seo_method_title = sh_field( 'seo_method_title', null, 'منهجية شفافة من اليوم الأول' );
$seo_method_desc  = sh_field( 'seo_method_desc',  null, 'كل مرحلة لها هدف واضح ومؤشر قياس — لا خطوات غامضة، لا وعود بدون تنفيذ.' );
$seo_proof_desc   = sh_field( 'seo_proof_desc',   null, 'نماذج من نتائج عملاء سابقين — أرقام موثّقة من Search Console وAnalytics، لا ادعاءات.' );
$seo_proof_cards  = sh_field( 'seo_proof_cards',  null, [] );
$seo_ind_title    = sh_field( 'seo_ind_title',    null, 'السيو يختلف من قطاع إلى آخر — ونحن نفهم ذلك' );
$seo_ind_desc     = sh_field( 'seo_ind_desc',     null, 'لكل قطاع جمهوره، كلماته، وقواعد ترتيبه. نُخصّص الاستراتيجية حسب طبيعة عملك.' );
$seo_faq_title    = sh_field( 'seo_faq_title',    null, 'أسئلة يسألها كل صاحب عمل' );

// ACF repeater fields may return an array of rows with all sub-fields empty when
// the admin saves the page without filling in the repeater. Filter those out so
// the empty() check below correctly falls back to the hardcoded defaults.
$seo_hero_stats  = array_values( array_filter( (array) $seo_hero_stats,  fn( $r ) => ! empty( $r['stat_label'] ) || ! empty( $r['stat_em'] ) || ! empty( $r['stat_n'] ) ) );
$seo_lost_cards  = array_values( array_filter( (array) $seo_lost_cards,  fn( $r ) => ! empty( $r['lc_title'] ) ) );
$seo_why_items   = array_values( array_filter( (array) $seo_why_items,   fn( $r ) => ! empty( $r['wi_label'] ) ) );
$seo_impact_rows = array_values( array_filter( (array) $seo_impact_rows, fn( $r ) => ! empty( $r['ir_label'] ) ) );
$seo_proof_cards = array_values( array_filter( (array) $seo_proof_cards, fn( $r ) => ! empty( $r['pc_result'] ) || ! empty( $r['pc_sector'] ) ) );

if ( empty( $seo_hero_stats ) ) {
    $seo_hero_stats = [
        [ 'stat_n' => '',     'stat_em' => '+200%', 'stat_label' => 'نمو متوسط في الزيارات' ],
        [ 'stat_n' => '90',   'stat_em' => 'يوم',   'stat_label' => 'أول مؤشرات النمو' ],
        [ 'stat_n' => '+100', 'stat_em' => '',      'stat_label' => 'عميل في السعودية والخليج' ],
        [ 'stat_n' => '100%', 'stat_em' => '',      'stat_label' => 'تقارير شفافة شهرياً' ],
    ];
}
if ( empty( $seo_lost_cards ) ) {
    $seo_lost_cards = [
        [ 'lc_title' => 'طلب بحث لا يصل إليك',  'lc_body' => 'كل شهر آلاف الأشخاص يبحثون عن خدمتك في جوجل. إذا لم يكن موقعك في النتائج الأولى، ذهب الطلب إلى منافس آخر.' ],
        [ 'lc_title' => 'ميزانية إعلانية تتبخّر', 'lc_body' => 'تنفق على Google Ads وميتا — وحين تتوقف عن الدفع، تتوقف الزيارات. السيو يبني أصلاً يستمر في جلب العملاء بدون نزيف يومي.' ],
        [ 'lc_title' => 'ليدز قليلة الجودة',      'lc_body' => 'الزيارات العضوية تأتي من أشخاص يبحثون فعلاً عن خدمتك — عملاء جاهزون بنية شراء حقيقية.' ],
        [ 'lc_title' => 'قرارات مبنية على الحدس', 'lc_body' => 'بدون تقارير ذكية تتخذ قرارات بناءً على شعور لا أرقام. تقاريرنا تربط البحث بالليدز والمبيعات — لتقرر بعقل.' ],
    ];
}
if ( empty( $seo_why_items ) ) {
    $seo_why_items = [
        [ 'wi_label' => 'تكلفة عميل أقل',        'wi_desc' => 'كلما نمت الزيارات العضوية، انخفض اعتمادك على الإعلانات' ],
        [ 'wi_label' => 'عملاء بنية شراء أعلى',  'wi_desc' => 'الباحث في جوجل لديه احتياج حقيقي' ],
        [ 'wi_label' => 'أصل تجاري متراكم',       'wi_desc' => 'كل شهر يبني على السابق، الإعلان يعود للصفر' ],
        [ 'wi_label' => 'مصداقية تلقائية',         'wi_desc' => 'الظهور العضوي يبني ثقة لا يبنيها الإعلان' ],
    ];
}
if ( empty( $seo_impact_rows ) ) {
    $seo_impact_rows = [
        [ 'ir_label' => 'تكلفة العميل (CAC)', 'ir_from' => 'ر.س عالية', 'ir_to' => '↓ 40%' ],
        [ 'ir_label' => 'الليدز الشهرية',     'ir_from' => 'قليلة',      'ir_to' => '↑ 3x'  ],
        [ 'ir_label' => 'الاعتماد على Ads',   'ir_from' => '100%',       'ir_to' => '↓ 50%' ],
        [ 'ir_label' => 'قيمة العميل (LTV)',  'ir_from' => 'عادية',      'ir_to' => '↑ 2x'  ],
    ];
}
if ( empty( $seo_proof_cards ) ) {
    $seo_proof_cards = [
        [ 'pc_sector' => 'قطاع الصحة والطب',        'pc_result' => '+312%', 'pc_desc' => 'نمو في الزيارات العضوية لعيادة طبية متخصصة خلال 8 أشهر — انتقلت من المرتبة الـ47 إلى الأولى على كلماتها الرئيسية.', 'pc_meta' => '8 أشهر تعاون مستمر' ],
        [ 'pc_sector' => 'قطاع التجارة الإلكترونية', 'pc_result' => '+184%', 'pc_desc' => 'زيادة في المبيعات العضوية لمتجر سلة في قطاع الأزياء مع خفض اعتماده على Google Ads بنسبة 45%.', 'pc_meta' => '10 أشهر تعاون مستمر' ],
        [ 'pc_sector' => 'قطاع التعليم والتدريب',    'pc_result' => '+220%', 'pc_desc' => 'نمو في عدد الليدز الشهرية لمنصة تدريب مع وصول 14 كلمة مفتاحية تنافسية إلى الصفحة الأولى.', 'pc_meta' => '6 أشهر تعاون مستمر' ],
    ];
}
?>

<!-- Hero -->
<section class="svc-hero">
  <div class="wrap">
    <div class="svc-hero-inner">
      <div class="breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">الرئيسية</a>
        <svg class="bc-sep" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        <span class="bc-current"><?php echo esc_html( $hero_tag ); ?></span>
      </div>
      <div class="h-badge"><span class="h-bdot"></span><?php echo esc_html( $hero_tag ); ?></div>
      <h1 class="svc-hero-h1"><?php echo wp_kses_post( $hero_title ); ?></h1>
      <p class="page-hero-p"><?php echo esc_html( $hero_desc ); ?></p>
      <div class="pbtns">
        <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-p lg">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          <?php echo esc_html( $seo_hero_cta1 ); ?>
        </a>
        <a href="#sub-services" class="btn btn-g lg"><?php echo esc_html( $seo_hero_cta2 ); ?></a>
      </div>
      <div class="h-stats">
        <?php foreach ( $seo_hero_stats as $st ) : ?>
        <div class="h-stat">
          <div class="h-stat-n">
            <?php echo esc_html( $st['stat_n'] ?? '' ); ?>
            <?php if ( ! empty( $st['stat_em'] ) ) : ?><em><?php echo esc_html( $st['stat_em'] ); ?></em><?php endif; ?>
          </div>
          <div class="h-stat-l"><?php echo esc_html( $st['stat_label'] ?? '' ); ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- Lost Opportunities -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">الفرص الضائعة</span><h2 class="h2"><?php echo esc_html( $seo_lost_title ); ?></h2><p class="bod"><?php echo esc_html( $seo_lost_desc ); ?></p></div>
    <div class="lost-grid">
      <?php
      $lost_icons = [
          '<circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35M8 11h6"/>',
          '<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>',
          '<path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/>',
          '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>',
      ];
      $lost_delays = [ '', ' d1', ' d2', ' d3' ];
      foreach ( $seo_lost_cards as $li => $lc ) :
      ?>
      <div class="lost-card sr<?php echo esc_attr( $lost_delays[ $li ] ?? '' ); ?>">
        <div class="lost-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?php echo $lost_icons[ $li ] ?? $lost_icons[0]; // phpcs:ignore ?></svg></div>
        <h3><?php echo esc_html( $lc['lc_title'] ?? '' ); ?></h3>
        <p><?php echo esc_html( $lc['lc_body'] ?? '' ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Why SEO Matters Commercially -->
<section class="sec sec-surface">
  <div class="wrap">
    <div class="why-com-grid">
      <div>
        <div class="sh sr"><span class="tag">لماذا السيو يهم تجارياً</span><h2 class="h2"><?php echo wp_kses_post( $seo_why_title ); ?></h2><p class="bod" style="margin-top:12px"><?php echo esc_html( $seo_why_desc ); ?></p></div>
        <div class="chklist sr d1" style="margin-bottom:24px">
          <?php foreach ( $seo_why_items as $wi ) : ?>
          <div class="chk-item"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div><span><strong><?php echo esc_html( $wi['wi_label'] ?? '' ); ?></strong><?php if ( ! empty( $wi['wi_desc'] ) ) : ?> — <?php echo esc_html( $wi['wi_desc'] ); ?><?php endif; ?></span></div>
          <?php endforeach; ?>
        </div>
        <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-p sr d2">احجز استشارة مجانية</a>
      </div>
      <div class="sr d2">
        <div style="background:var(--navy-2);border-radius:var(--r4);padding:32px 28px;position:relative;overflow:hidden">
          <div style="position:absolute;inset-inline-end:-50px;top:-50px;width:240px;height:240px;border-radius:50%;background:radial-gradient(circle,rgba(30,46,245,.28),transparent 70%)"></div>
          <div style="font-size:9.5px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.22);margin-bottom:20px;position:relative;z-index:1">تأثير السيو على مؤشرات عملك</div>
          <div style="position:relative;z-index:1;display:flex;flex-direction:column;gap:14px">
            <?php
            foreach ( $seo_impact_rows as $imp ) :
                $imp = [ 'label' => $imp['ir_label'] ?? '', 'from' => $imp['ir_from'] ?? '', 'to' => $imp['ir_to'] ?? '' ];
            ?>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:14px 16px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:var(--r2)">
              <strong style="font-size:13.5px;font-weight:700;color:rgba(255,255,255,.8)"><?php echo esc_html( $imp['label'] ); ?></strong>
              <div style="display:flex;align-items:center;gap:10px">
                <span style="font-size:13px;color:rgba(255,255,255,.36);text-decoration:line-through"><?php echo esc_html( $imp['from'] ); ?></span>
                <span style="font-size:14.5px;font-weight:800;color:var(--green)"><?php echo esc_html( $imp['to'] ); ?></span>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- What We Do -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">ماذا نفعل بالضبط</span><h2 class="h2"><?php echo esc_html( $seo_wwd_title ); ?></h2><p class="bod"><?php echo esc_html( $seo_wwd_desc ); ?></p></div>
    <div class="wwd-grid">
      <?php
      $wwd_defaults = [
          [ 'n' => '01', 'title' => 'تحليل تقني عميق',     'desc' => 'فحص بنية الموقع، السرعة، الزحف، Core Web Vitals — نكشف ما يُعيق ترتيبك.', 'svg' => '<polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>' ],
          [ 'n' => '02', 'title' => 'بحث الكلمات الذكي',   'desc' => 'الكلمات التي يبحث بها عملاؤك الجادون — لا الكلمات العامة الواسعة.', 'svg' => '<circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>' ],
          [ 'n' => '03', 'title' => 'تحسين الصفحات',        'desc' => 'عناوين، أوصاف، روابط داخلية، بيانات هيكلية — كل صفحة تُعامَل كصفحة هبوط.', 'svg' => '<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>' ],
          [ 'n' => '04', 'title' => 'محتوى يستحق الترتيب', 'desc' => 'محتوى يستهدف نية البحث الصحيحة ويُقنع القارئ بالتحرك.', 'svg' => '<path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/>' ],
          [ 'n' => '05', 'title' => 'روابط موثوقة',          'desc' => 'روابط طبيعية من مصادر ذات سلطة حقيقية — تُعزز ترتيبك بدون مخاطر.', 'svg' => '<path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/>' ],
          [ 'n' => '06', 'title' => 'تقارير تربط بعملك',   'desc' => 'تقرير شهري يربط الزيارات بالليدز والمبيعات وقرارات النمو.', 'svg' => '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>' ],
      ];
      $dcs = [ '', 'd1', 'd2', 'd1', 'd2', 'd3' ];
      $items = ! empty( $features ) ? $features : $wwd_defaults;
      foreach ( $items as $idx => $feat ) :
          $dc  = $dcs[ $idx % 6 ];
          $def = $wwd_defaults[ $idx % 6 ];
      ?>
      <div class="wwd-card sr <?php echo esc_attr( $dc ); ?>">
        <div class="wwd-n" aria-hidden="true"><?php echo esc_html( $def['n'] ); ?></div>
        <div class="wwd-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="21" height="21"><?php echo $def['svg']; ?></svg></div>
        <h3><?php echo esc_html( $feat['title'] ?? $def['title'] ); ?></h3>
        <p><?php echo esc_html( $feat['desc'] ?? $def['desc'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Sub-services -->
<section id="sub-services" class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">خدمات السيو</span><h2 class="h2"><?php echo esc_html( $seo_sub_title ); ?></h2><p class="bod"><?php echo esc_html( $seo_sub_desc ); ?></p></div>
    <div class="ss-grid sr d1">
      <?php
      foreach ( [
          [ 'icon' => '<path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/>',
            'title' => 'بناء الباك لينك', 'desc' => 'روابط موثوقة من مواقع ذات سلطة — استراتيجية، ليست شراء روابط عشوائي.',
            'url' => sh_page_url( 'services/seo/backlinks' ),
            'items' => [ 'تحليل بروفايل الروابط الحالي والمنافسين', 'روابط تحريرية من مواقع عربية موثوقة', 'Guest Posting باستراتيجية واضحة', 'تقرير شهري بكل رابط ومصدره' ] ],
          [ 'icon' => '<path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/>',
            'title' => 'كتابة المحتوى', 'desc' => 'محتوى عربي مدروس استراتيجياً — يستهدف نية البحث ويُحوّل القارئ.',
            'url' => sh_page_url( 'services/seo/content' ),
            'items' => [ 'استراتيجية محتوى مبنية على البيانات', 'مقالات مدونة، صفحات خدمات، أوصاف منتجات', 'محتوى متخصص لكل قطاع', 'كتّاب بشريون — لا اعتماد على AI وحده' ] ],
          [ 'icon' => '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>',
            'title' => 'استشارات تحليل الأداء', 'desc' => 'نُفسّر أرقام موقعك بلغة تجارية — لتعرف هل وكالتك الحالية تعمل.',
            'url' => sh_page_url( 'services/seo/consulting' ),
            'items' => [ 'تدقيق سيو شامل وتشخيص الوضع الحالي', 'قراءة Search Console وAnalytics', 'تحليل أداء وكالتك السيو الحالية', 'خطة تحسين قابلة للتنفيذ' ] ],
      ] as $ss ) :
      ?>
      <div class="ss-card">
        <div class="ss-head">
          <div class="ss-hico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="19" height="19"><?php echo $ss['icon']; ?></svg></div>
          <h3><?php echo esc_html( $ss['title'] ); ?></h3>
          <p><?php echo esc_html( $ss['desc'] ); ?></p>
        </div>
        <div class="ss-body">
          <div class="ss-items">
            <?php foreach ( $ss['items'] as $item ) : ?>
            <div class="ss-item"><div class="ss-dot"></div><?php echo esc_html( $item ); ?></div>
            <?php endforeach; ?>
          </div>
          <a href="<?php echo esc_url( $ss['url'] ); ?>" class="ss-link">تفاصيل الخدمة <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Reporting -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="rep-layout">
      <div class="sr">
        <span class="tag" style="margin-bottom:12px">من السيو إلى نتائج العمل</span>
        <h2 class="h2" style="margin-bottom:16px"><?php echo wp_kses_post( $seo_reporting_title ); ?></h2>
        <p class="bod" style="margin-bottom:18px"><?php echo esc_html( $seo_reporting_desc ); ?></p>
        <div class="chklist sr d1" style="margin-bottom:24px">
          <div class="chk-item"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div><span><strong>الترافيك</strong> — مصدره، جودته، وسلوكه على موقعك</span></div>
          <div class="chk-item"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div><span><strong>الليدز</strong> — كم نموذج تُعبّأ، كم مكالمة، وتكلفة كل واحد</span></div>
          <div class="chk-item"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div><span><strong>المبيعات</strong> — للمتاجر نربط البحث بالمنتج المباع وقيمته</span></div>
          <div class="chk-item"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div><span><strong>الأداء اللحظي</strong> — لوحة تحكم حية تتابعها وقتما شئت</span></div>
        </div>
      </div>
      <div class="sr d1">
        <div class="rep-mock">
          <div class="rep-head">
            <div class="rep-title">تقرير سيو شهري — أكتوبر</div>
            <div class="rep-pill">+34% مقارنة بالشهر السابق</div>
          </div>
          <div class="rep-grid">
            <div class="rep-stat"><div class="rep-stat-l">الزيارات العضوية</div><div class="rep-stat-n">42,180 <span class="up">↑ 28%</span></div></div>
            <div class="rep-stat"><div class="rep-stat-l">ليدز عضوية</div><div class="rep-stat-n">312 <span class="up">↑ 41%</span></div></div>
            <div class="rep-stat"><div class="rep-stat-l">كلمات صفحة 1</div><div class="rep-stat-n">186 <span class="up">↑ 22</span></div></div>
            <div class="rep-stat"><div class="rep-stat-l">قيمة المبيعات</div><div class="rep-stat-n">142K <span class="up">↑ 38%</span></div></div>
          </div>
          <div class="rep-chart">
            <div class="rep-bars">
              <?php foreach ( [ 38, 46, 52, 58, 64, 71, 78, 84, 91, 96 ] as $rh ) : ?>
              <div class="rep-bar" style="height:<?php echo esc_attr( $rh ); ?>%<?php echo $rh === 96 ? ';background:linear-gradient(180deg,var(--green),rgba(16,185,129,.3))' : ''; ?>"></div>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="rep-foot">نمو متراكم على 10 أشهر — مثال توضيحي</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Methodology -->
<section class="sec sec-navy">
  <div class="wrap">
    <div class="sh c sr"><span class="tag d">منهجيتنا</span><h2 class="h2 wh"><?php echo esc_html( $seo_method_title ); ?></h2><p class="bod d"><?php echo esc_html( $seo_method_desc ); ?></p></div>
    <div class="method-grid">
      <?php
      $method_items = ! empty( $steps ) ? array_slice( $steps, 0, 6 ) : [
          [ 'title' => 'استكشاف وفهم العمل',      'desc' => 'نتعرف على نشاطك، جمهورك، منافسيك، وأهدافك التجارية الفعلية' ],
          [ 'title' => 'تدقيق سيو شامل',           'desc' => 'تحليل تقني ومحتوى وروابط — نكشف فجوات الأداء والفرص الكامنة' ],
          [ 'title' => 'الاستراتيجية والخطة',      'desc' => 'خارطة طريق مخصصة بأولويات وجداول زمنية — قابلة للتنفيذ والمتابعة' ],
          [ 'title' => 'التنفيذ الدقيق',           'desc' => 'تطبيق التحسينات بالترتيب الصحيح — تقني ثم محتوى ثم روابط' ],
          [ 'title' => 'القياس والربط بالأعمال',  'desc' => 'تقرير شهري يربط السيو بالليدز والمبيعات — لا أرقام مجردة' ],
          [ 'title' => 'التحسين المستمر',          'desc' => 'كل شهر يبني على السابق — السيو لعبة طويلة المدى تتراكم بالوقت' ],
      ];
      $dcs = [ '', 'd1', 'd2', 'd1', 'd2', 'd3' ];
      foreach ( $method_items as $idx => $mi ) :
      ?>
      <div class="method-item sr <?php echo esc_attr( $dcs[ $idx % 6 ] ); ?>">
        <div class="m-num"><?php echo esc_html( str_pad( $idx + 1, 2, '0', STR_PAD_LEFT ) ); ?></div>
        <h3><?php echo esc_html( $mi['title'] ?? '' ); ?></h3>
        <p><?php echo esc_html( $mi['desc'] ?? '' ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Proof / Results -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">دلائل النتائج</span><h2 class="h2"><?php echo esc_html( $seo_proof_title ); ?></h2><p class="bod"><?php echo esc_html( $seo_proof_desc ); ?></p></div>
    <div class="proof-grid">
      <?php
      $proof_delays = [ '', 'd1', 'd2' ];
      foreach ( $seo_proof_cards as $pci => $pc ) :
      ?>
      <div class="proof-card sr <?php echo esc_attr( $proof_delays[ $pci ] ?? '' ); ?>">
        <div class="proof-sector"><?php echo esc_html( $pc['pc_sector'] ?? '' ); ?></div>
        <div class="proof-result"><em><?php echo esc_html( $pc['pc_result'] ?? '' ); ?></em></div>
        <div class="proof-desc"><?php echo esc_html( $pc['pc_desc'] ?? '' ); ?></div>
        <div class="proof-meta"><div class="proof-dot"></div><?php echo esc_html( $pc['pc_meta'] ?? '' ); ?></div>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-top:32px">
      <a href="<?php echo esc_url( sh_page_url( 'results' ) ); ?>" class="btn btn-p lg">جميع نتائج الأعمال <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
    </div>
  </div>
</section>

<!-- Industries -->
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">القطاعات التي نخدمها</span><h2 class="h2"><?php echo esc_html( $seo_ind_title ); ?></h2><p class="bod"><?php echo esc_html( $seo_ind_desc ); ?></p></div>
    <div class="ind-grid sr d1">
      <?php
      foreach ( [
          [ 'label' => 'التجارة الإلكترونية', 'sub' => 'متاجر سلة، زد، شوبيفاي', 'url' => sh_page_url( 'sectors/ecommerce' ), 'svg' => '<path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>' ],
          [ 'label' => 'الصحة والطب',         'sub' => 'عيادات، مستشفيات، صحة',  'url' => sh_page_url( 'sectors/health' ),       'svg' => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>' ],
          [ 'label' => 'التعليم والتدريب',    'sub' => 'منصات، أكاديميات',         'url' => sh_page_url( 'sectors/education' ),    'svg' => '<path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/>' ],
          [ 'label' => 'الأغذية والمطاعم',   'sub' => 'مطاعم، توصيل، أغذية',     'url' => sh_page_url( 'sectors/food' ),         'svg' => '<path d="M18 8h1a4 4 0 010 8h-1"/><path d="M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/>' ],
          [ 'label' => 'القانون والاستشارات', 'sub' => 'محاماة، استشارات، شركات', 'url' => sh_page_url( 'sectors/legal' ),        'svg' => '<path d="M3 6l9 6 9-6"/><path d="M3 6v12l9 6 9-6V6"/>' ],
          [ 'label' => 'التقنية والبرمجيات',  'sub' => 'SaaS، شركات تقنية',       'url' => sh_page_url( 'sectors/tech' ),         'svg' => '<polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>' ],
      ] as $ic ) :
      ?>
      <a href="<?php echo esc_url( $ic['url'] ); ?>" class="ind-card">
        <div class="ind-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?php echo $ic['svg']; ?></svg></div>
        <div class="ind-card-body"><h3><?php echo esc_html( $ic['label'] ); ?></h3><p><?php echo esc_html( $ic['sub'] ); ?></p></div>
      </a>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-top:28px">
      <a href="<?php echo esc_url( sh_page_url( 'sectors' ) ); ?>" class="btn btn-g">جميع القطاعات</a>
    </div>
  </div>
</section>

<!-- Markets we serve -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">أسواقنا</span><h2 class="h2">الأسواق التي نعمل بها</h2><p class="bod">خبرة محلية حقيقية في ثلاثة من أكبر أسواق البحث في المنطقة العربية — استراتيجية مخصّصة لكل سوق، لا نسخة واحدة للجميع.</p></div>
    <div class="markets-grid sr d1">
      <?php
      // Market key (seo_market ACF value) + legacy slug fallback for backward compat.
      foreach ( [
          [ 'flag' => '🇸🇦', 'name' => 'السعودية', 'desc' => 'بيئة رقمية تدفعها رؤية 2030 — قاعدة مستخدمين بقوة شرائية مرتفعة وعائد واضح على كل ليد من البحث العضوي.', 'market' => 'saudi_arabia', 'slug' => 'services/seo/saudi-arabia' ],
          [ 'flag' => '🇦🇪', 'name' => 'الإمارات',  'desc' => 'سوق دولي يجمع جمهوراً عربياً وأجنبياً — المنافسة عالية، لكن الفرص لمن يُحسن الاستهداف بالعربية والإنجليزية معاً.', 'market' => 'uae',          'slug' => 'services/seo/uae' ],
          [ 'flag' => '🇪🇬', 'name' => 'مصر',       'desc' => 'أكبر سوق رقمي عربي بحجم بحث ضخم — كثير من القطاعات لا تزال تفتقر إلى منافسة سيو جادة، وهذه فرصة حقيقية.', 'market' => 'egypt',         'slug' => 'services/seo/egypt' ],
      ] as $mkt ) :
          $mkt_url  = sh_market_permalink( $mkt['market'], $mkt['slug'] );
          $has_url  = $mkt_url !== '';
          $tag      = $has_url ? 'a' : 'div';
          $href     = $has_url ? ' href="' . esc_url( $mkt_url ) . '"' : '';
          $no_click = $has_url ? '' : ' style="cursor:default"';
      ?>
      <<?php echo $tag; ?><?php echo $href . $no_click; ?> class="market-card">
        <?php if ( ! $has_url && current_user_can( 'manage_options' ) ) : ?>
        <div style="font-size:11px;font-weight:700;color:#e6a817;background:rgba(230,168,23,.12);border:1px solid rgba(230,168,23,.4);border-radius:4px;padding:4px 8px;margin-bottom:10px;direction:ltr">
          Admin: no published page found for market "<?php echo esc_html( $mkt['market'] ); ?>"
        </div>
        <?php endif; ?>
        <div class="market-flag" aria-hidden="true"><?php echo esc_html( $mkt['flag'] ); ?></div>
        <h3><?php echo esc_html( $mkt['name'] ); ?></h3>
        <p><?php echo esc_html( $mkt['desc'] ); ?></p>
        <span class="market-cta">اعرف المزيد <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
      </<?php echo $tag; ?>>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="sec sec-off">
  <div class="wrap">
    <div class="faq-cta-layout">
      <div>
        <div class="sh sr"><span class="tag">الأسئلة الشائعة</span><h2 class="h2"><?php echo esc_html( $seo_faq_title ); ?></h2></div>
        <div class="faq-list sr d1">
          <?php
          $faq_data = ! empty( $faqs ) ? $faqs : [
              [ 'question' => 'كم يستغرق السيو حتى أرى نتائج في عملي؟', 'answer' => 'المؤشرات الأولى تظهر خلال 30–60 يوم. الترافيك الفعلي والليدز يبدآن في الظهور بين الشهر الثالث والسادس. أما النتائج الكبيرة فتأتي بعد 6–12 شهر — السيو لعبة تراكم.' ],
              [ 'question' => 'كيف أفرّق بين شركة سيو حقيقية وأخرى تبيع وعوداً؟', 'answer' => 'شركة السيو الحقيقية: تطلب وصولاً لـ Search Console، تُقدّم تدقيقاً تفصيلياً قبل البيع، تربط تقاريرها بنتائج عملك، ولا تَعِد بمراتب محددة في وقت محدد.' ],
              [ 'question' => 'هل السيو يحلّ محل إعلانات Google Ads؟', 'answer' => 'لا — يُكمّلها. الإعلانات تجلب زيارات فورية، السيو يبني أصلاً مستداماً. الاستراتيجية الذكية: الإعلانات للحاجة الفورية، والسيو للمدى البعيد.' ],
              [ 'question' => 'هل أحتاج عقداً طويلاً؟', 'answer' => 'لا. نعمل بدون عقود ملزمة. نُثبت قيمتنا شهراً بعد شهر — وأنت من يُقرّر الاستمرار.' ],
              [ 'question' => 'هل تعملون مع المتاجر الإلكترونية؟', 'answer' => 'نعم وبشكل متخصص. لدينا خدمة سيو المتاجر الإلكترونية لمنصات سلة، زد، شوبيفاي، وووكومرس — بمنهجية مختلفة عن السيو التقليدي.' ],
          ];
          foreach ( $faq_data as $faq ) :
          ?>
          <div class="faq-item">
            <div class="faq-q"><span><?php echo esc_html( $faq['question'] ?? '' ); ?></span><div class="faq-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></div></div>
            <div class="faq-a"><div class="faq-a-inner"><?php echo esc_html( $faq['answer'] ?? '' ); ?></div></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="cta-sticky">
        <div class="cta-side-card sr d1">
          <span class="tag d" style="position:relative;z-index:1;margin-bottom:10px">ابدأ الآن</span>
          <h3 style="font-size:clamp(20px,2.5vw,26px);font-weight:900;color:#fff;margin-bottom:12px;line-height:1.2;position:relative;z-index:1">هل موقعك يحقق<br>أقصى ما يمكن؟</h3>
          <p style="font-size:13.5px;color:rgba(255,255,255,.44);line-height:1.8;margin-bottom:22px;position:relative;z-index:1">استشارة 30 دقيقة مجانية — نُحلّل وضعك، نكشف الفرص الضائعة، ونضع معاً خطة الخطوات الأولى.</p>
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
    'title'   => $seo_cta_title,
    'buttons' => [
        [ 'text' => 'احجز استشارة مجانية', 'url' => sh_page_url( 'contact' ), 'class' => 'btn-w lg' ],
        [ 'text' => 'تعرّف على نتائجنا',    'url' => sh_page_url( 'results' ), 'class' => 'btn-g lg' ],
    ],
] );
?>

<?php get_footer(); ?>
