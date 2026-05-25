<?php
/**
 * Template Name: Service — SEO Main
 */
get_header();

$hero_tag   = sh_field( 'service_hero_tag' ) ?: 'تحسين محركات البحث';
$hero_title = sh_field( 'service_hero_title' ) ?: 'شركة <em>تحسين محركات البحث</em><br>التي تُترجم البحث إلى مبيعات';
$hero_desc  = sh_field( 'service_hero_desc' ) ?: 'عملاؤك يبحثون عنك على جوجل اليوم — السؤال هو: هل يجدونك؟ نُحوّل الزيارات العضوية إلى ليدز ومبيعات حقيقية.';
$features   = sh_field( 'service_features' );
$steps      = sh_field( 'service_steps' );
$faqs       = sh_field( 'service_faqs' );
?>

<!-- Hero -->
<section class="svc-hero">
  <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.04) 1px,transparent 1px);background-size:36px 36px;pointer-events:none"></div>
  <div style="position:absolute;border-radius:50%;filter:blur(60px);pointer-events:none;inset-inline-start:-200px;bottom:-100px;width:600px;height:600px;background:radial-gradient(circle,rgba(30,46,245,.22),transparent 65%)"></div>
  <div style="position:absolute;border-radius:50%;filter:blur(60px);pointer-events:none;inset-inline-end:-100px;top:0;width:480px;height:480px;background:radial-gradient(circle,rgba(30,46,245,.12),transparent 65%)"></div>
  <div class="wrap">
    <div class="svc-hero-inner">
      <div class="breadcrumb" style="justify-content:center;margin-bottom:22px">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">الرئيسية</a>
        <svg class="bc-sep" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        <span style="color:rgba(255,255,255,.55)"><?php echo esc_html( $hero_tag ); ?></span>
      </div>
      <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(30,46,245,.16);border:1px solid rgba(30,46,245,.28);border-radius:50px;padding:7px 16px;font-size:11.5px;font-weight:700;color:rgba(255,255,255,.7);margin-bottom:24px">
        <span style="width:6px;height:6px;border-radius:50%;background:var(--green);animation:lp 2s ease-in-out infinite;flex-shrink:0"></span>
        <?php echo esc_html( $hero_tag ); ?>
      </div>
      <h1 style="font-size:clamp(34px,5.2vw,64px);font-weight:900;line-height:1.06;letter-spacing:-.03em;color:#fff;margin-bottom:18px"><?php echo wp_kses_post( $hero_title ); ?></h1>
      <p style="font-size:clamp(15px,1.55vw,18px);line-height:1.9;color:rgba(255,255,255,.55);max-width:680px;margin-inline:auto;margin-bottom:32px"><?php echo esc_html( $hero_desc ); ?></p>
      <div style="display:flex;gap:11px;justify-content:center;flex-wrap:wrap">
        <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-p lg">احجز استشارة مجانية — 30 دقيقة</a>
        <a href="#sub-services" class="btn btn-g lg">خدماتنا</a>
      </div>
      <div class="h-stats">
        <div class="h-stat"><div class="h-stat-n"><em>+200%</em></div><div class="h-stat-l">نمو متوسط في الزيارات</div></div>
        <div class="h-stat"><div class="h-stat-n">90<em>يوم</em></div><div class="h-stat-l">أول مؤشرات النمو</div></div>
        <div class="h-stat"><div class="h-stat-n">+100</div><div class="h-stat-l">عميل في السعودية والخليج</div></div>
        <div class="h-stat"><div class="h-stat-n">100%</div><div class="h-stat-l">تقارير شفافة شهرياً</div></div>
      </div>
    </div>
  </div>
</section>

<!-- Why SEO matters commercially -->
<section class="sec sec-surface">
  <div class="wrap">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center">
      <div>
        <div class="sh sr"><span class="tag">لماذا السيو يهم تجارياً</span><h2 class="h2">السيو ليس بنداً في الميزانية —<br>هو خفض في تكلفة العميل</h2><p class="bod" style="margin-top:12px">كل عميل يأتي عبر البحث العضوي يكلّفك أقل من نظيره عبر الإعلان. والأهم — يبقى يأتي حتى بعد توقفك عن الإنفاق.</p></div>
        <div class="chklist sr d1" style="margin-bottom:24px">
          <div class="chk-item"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div><strong>تكلفة عميل أقل</strong> — كلما نمت الزيارات العضوية، انخفض اعتمادك على الإعلانات</div>
          <div class="chk-item"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div><strong>عملاء بنية شراء أعلى</strong> — الباحث في جوجل لديه احتياج</div>
          <div class="chk-item"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div><strong>أصل تجاري متراكم</strong> — كل شهر يبني على السابق</div>
          <div class="chk-item"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div><strong>مصداقية تلقائية</strong> — الظهور العضوي يبني ثقة لا يبنيها الإعلان</div>
        </div>
        <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-p sr d2">احجز استشارة مجانية</a>
      </div>
      <div class="sr d2">
        <div style="background:var(--navy-2);border-radius:var(--r4);padding:32px 28px;position:relative;overflow:hidden">
          <div style="position:absolute;inset-inline-end:-50px;top:-50px;width:240px;height:240px;border-radius:50%;background:radial-gradient(circle,rgba(30,46,245,.28),transparent 70%)"></div>
          <div style="font-size:9.5px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.22);margin-bottom:20px;position:relative;z-index:1">تأثير السيو على مؤشرات عملك</div>
          <div style="position:relative;z-index:1;display:flex;flex-direction:column;gap:14px">
            <?php
            $impacts = [
                [ 'label' => 'تكلفة العميل (CAC)', 'from' => 'ر.س عالية', 'to' => '↓ 40%' ],
                [ 'label' => 'الليدز الشهرية', 'from' => 'قليلة', 'to' => '↑ 3x' ],
                [ 'label' => 'الاعتماد على Ads', 'from' => '100%', 'to' => '↓ 50%' ],
            ];
            foreach ( $impacts as $imp ) :
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

<!-- What we do (features grid) -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">ماذا نفعل بالضبط</span><h2 class="h2">ستة محاور تنفيذية تشكّل خدمة السيو</h2></div>
    <div class="features-grid">
      <?php if ( ! empty( $features ) ) :
          foreach ( $features as $idx => $feat ) :
              $dc = [ '', 'd1', 'd2', 'd1', 'd2', 'd3' ][ $idx % 6 ];
      ?>
          <div class="feat-card sr <?php echo esc_attr( $dc ); ?>">
            <div class="ico-box lg" style="margin-bottom:16px">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="22" height="22"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <h3><?php echo esc_html( $feat['title'] ?? '' ); ?></h3>
            <p><?php echo esc_html( $feat['desc'] ?? '' ); ?></p>
          </div>
      <?php endforeach;
      else : ?>
        <?php
        $default_features = [
            [ 'n' => '01', 'title' => 'تحليل تقني عميق',     'desc' => 'فحص بنية الموقع، السرعة، الزحف، Core Web Vitals.' ],
            [ 'n' => '02', 'title' => 'بحث الكلمات الذكي',   'desc' => 'الكلمات التي يبحث بها عملاؤك الجادون.' ],
            [ 'n' => '03', 'title' => 'تحسين الصفحات',        'desc' => 'عناوين، أوصاف، روابط داخلية، بيانات هيكلية.' ],
            [ 'n' => '04', 'title' => 'محتوى يستحق الترتيب', 'desc' => 'محتوى يستهدف نية البحث الصحيحة.' ],
            [ 'n' => '05', 'title' => 'روابط موثوقة',          'desc' => 'روابط طبيعية من مصادر ذات سلطة حقيقية.' ],
            [ 'n' => '06', 'title' => 'تقارير تربط بعملك',   'desc' => 'تقرير شهري يربط الزيارات بالليدز والمبيعات.' ],
        ];
        $dcs = [ '', 'd1', 'd2', 'd1', 'd2', 'd3' ];
        foreach ( $default_features as $fi => $f ) :
        ?>
          <div class="feat-card sr <?php echo esc_attr( $dcs[ $fi ] ); ?>">
            <div style="font-size:11px;font-weight:800;color:rgba(30,46,245,.14);margin-bottom:14px;letter-spacing:.04em"><?php echo esc_html( $f['n'] ); ?></div>
            <div class="ico-box" style="margin-bottom:14px"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="20" height="20"><circle cx="12" cy="12" r="10"/></svg></div>
            <h3><?php echo esc_html( $f['title'] ); ?></h3>
            <p><?php echo esc_html( $f['desc'] ); ?></p>
          </div>
        <?php endforeach;
      endif; ?>
    </div>
  </div>
</section>

<!-- Sub-services -->
<section id="sub-services" class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">خدمات السيو</span><h2 class="h2">اختر الخدمة التي يحتاجها عملك</h2></div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px" class="sr d1">
      <?php
      $sub_services = [
          [ 'title' => 'بناء الباك لينك',          'desc' => 'روابط موثوقة من مواقع ذات سلطة.', 'url' => sh_page_url( 'services/backlinks' ), 'items' => [ 'تحليل بروفايل الروابط', 'روابط تحريرية موثوقة', 'Guest Posting', 'تقرير شهري مفصّل' ] ],
          [ 'title' => 'كتابة المحتوى',             'desc' => 'محتوى عربي مدروس استراتيجياً.', 'url' => sh_page_url( 'services/content' ),   'items' => [ 'استراتيجية مبنية على البيانات', 'مقالات وصفحات خدمات', 'كتّاب بشريون متخصصون', 'محتوى لكل قطاع' ] ],
          [ 'title' => 'استشارات تحليل الأداء',    'desc' => 'نُفسّر أرقام موقعك بلغة تجارية.', 'url' => sh_page_url( 'services/consulting' ), 'items' => [ 'تدقيق سيو شامل', 'قراءة Search Console', 'تحليل الوكالة الحالية', 'خطة تحسين قابلة للتنفيذ' ] ],
          [ 'title' => 'سيو المتاجر الإلكترونية', 'desc' => 'سيو متخصص للمتاجر — سلة، زد، شوبيفاي.', 'url' => sh_page_url( 'services/seo-stores' ), 'items' => [ 'تحسين صفحات المنتجات', 'كلمات شرائية عالية النية', 'معالجة المحتوى المتكرر', 'ربط الترافيك بالمبيعات' ] ],
      ];
      foreach ( $sub_services as $ss ) :
      ?>
      <div style="border:1.5px solid var(--line);border-radius:var(--r3);overflow:hidden;transition:all .26s var(--fast);background:#fff">
        <div style="padding:22px 24px;background:var(--navy-2);position:relative;overflow:hidden">
          <div style="position:absolute;inset-inline-end:-30px;top:-30px;width:140px;height:140px;border-radius:50%;background:radial-gradient(circle,rgba(30,46,245,.28),transparent 70%)"></div>
          <div style="width:42px;height:42px;border-radius:11px;background:rgba(30,46,245,.25);display:flex;align-items:center;justify-content:center;margin-bottom:13px;position:relative;z-index:1">
            <svg viewBox="0 0 24 24" fill="none" stroke="#7b90ff" stroke-width="1.8" width="19" height="19"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
          </div>
          <h3 style="font-size:18px;font-weight:800;color:#fff;position:relative;z-index:1;margin-bottom:6px"><?php echo esc_html( $ss['title'] ); ?></h3>
          <p style="font-size:13px;color:rgba(255,255,255,.45);position:relative;z-index:1;line-height:1.7"><?php echo esc_html( $ss['desc'] ); ?></p>
        </div>
        <div style="padding:20px 24px">
          <div style="display:flex;flex-direction:column;gap:0">
            <?php foreach ( $ss['items'] as $item ) : ?>
            <div style="display:flex;align-items:flex-start;gap:10px;font-size:13px;color:var(--ink-2);padding:11px 0;border-bottom:1px solid var(--line);line-height:1.65">
              <span style="width:5px;height:5px;border-radius:50%;background:rgba(30,46,245,.3);flex-shrink:0;margin-top:7px"></span>
              <?php echo esc_html( $item ); ?>
            </div>
            <?php endforeach; ?>
          </div>
          <a href="<?php echo esc_url( $ss['url'] ); ?>" style="display:inline-flex;align-items:center;gap:6px;margin-top:14px;font-size:13.5px;font-weight:700;color:var(--blue)">
            تفاصيل الخدمة <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Process steps -->
<?php if ( ! empty( $steps ) || true ) :
    $display_steps = ! empty( $steps ) ? $steps : [
        [ 'title' => 'التدقيق والتشخيص',   'desc' => 'نُحلّل موقعك من الداخل — السرعة، البنية، المحتوى، الروابط — ونحدد الأولويات بدقة.' ],
        [ 'title' => 'بناء الاستراتيجية',  'desc' => 'خطة عمل 3-6 أشهر مبنية على بيانات المنافسين وفرص الكلمات المفتاحية في قطاعك.' ],
        [ 'title' => 'التنفيذ المتكامل',   'desc' => 'تقني، محتوى، روابط — كل محور يُنفَّذ بالتوازي من فريق متخصص.' ],
        [ 'title' => 'القياس والتطوير',    'desc' => 'تقرير شهري يُظهر الأداء بالأرقام ويُحدّد الخطوة التالية بوضوح.' ],
    ];
?>
<section class="sec sec-navy">
  <div class="wrap">
    <div class="sh c sr"><span class="tag d">منهجيتنا</span><h2 class="h2 wh">4 خطوات نُطبّقها مع كل عميل</h2></div>
    <div style="--cols:4" class="proc-grid sr">
      <div style="position:absolute;top:22px;inset-inline-start:calc(100%/4/2);width:calc(100% - 100%/4);height:1px;background:linear-gradient(to left,rgba(30,46,245,.35),rgba(30,46,245,.08))"></div>
      <?php foreach ( $display_steps as $step ) : ?>
      <div class="proc-step">
        <div class="proc-num"><?php static $sn = 1; echo str_pad( $sn++, 2, '0', STR_PAD_LEFT ); ?></div>
        <h3><?php echo esc_html( $step['title'] ?? '' ); ?></h3>
        <p><?php echo esc_html( $step['desc'] ?? '' ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- FAQs -->
<?php if ( ! empty( $faqs ) ) : ?>
<section class="sec sec-white">
  <div class="wrap">
    <div style="display:grid;grid-template-columns:5fr 4fr;gap:48px;align-items:start">
      <div>
        <div class="sh sr"><span class="tag">الأسئلة الشائعة</span><h2 class="h2">أسئلة يسألها عملاؤنا دائماً</h2></div>
        <div class="faq-list sr d1">
          <?php foreach ( $faqs as $faq ) : ?>
          <div class="faq-item">
            <div class="faq-q">
              <span><?php echo esc_html( $faq['question'] ?? '' ); ?></span>
              <div class="faq-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></div>
            </div>
            <div class="faq-a">
              <div class="faq-a-inner"><?php echo esc_html( $faq['answer'] ?? '' ); ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="sr d2" style="position:sticky;top:86px">
        <div style="background:var(--navy-2);border-radius:var(--r4);padding:28px;position:relative;overflow:hidden">
          <div style="position:absolute;inset-inline-end:-30px;top:-30px;width:160px;height:160px;border-radius:50%;background:radial-gradient(circle,rgba(30,46,245,.25),transparent 70%)"></div>
          <div style="position:relative;z-index:1">
            <div style="font-size:13px;font-weight:800;color:#fff;margin-bottom:8px">جاهز للبدء؟</div>
            <p style="font-size:13px;color:rgba(255,255,255,.5);line-height:1.75;margin-bottom:18px">احجز 30 دقيقة معنا — وسنخبرك بوضوح ما يمكن تحقيقه لموقعك.</p>
            <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-p" style="width:100%;justify-content:center">استشارة مجانية</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php
get_template_part( 'template-parts/layout/cta-banner', null, [
    'tag'     => 'ابدأ الآن',
    'title'   => 'موقعك يستحق أن يُرى',
    'buttons' => [
        [ 'text' => 'احجز استشارة مجانية', 'url' => sh_page_url( 'contact' ),       'class' => 'btn-w lg' ],
        [ 'text' => 'تعرّف على نتائجنا',    'url' => sh_page_url( 'results' ),       'class' => 'btn-g lg' ],
    ],
] );
?>

<?php get_footer(); ?>
