<?php
/**
 * Template Name: Service — Stores Overview
 */
get_header();
?>

<!-- Hero -->
<section class="svc-hero">
  <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.04) 1px,transparent 1px);background-size:36px 36px;mask-image:radial-gradient(ellipse 90% 80% at 50% 50%,#000 10%,transparent 75%);pointer-events:none"></div>
  <div style="position:absolute;border-radius:50%;filter:blur(60px);pointer-events:none;inset-inline-start:-200px;bottom:-100px;width:600px;height:600px;background:radial-gradient(circle,rgba(30,46,245,.22),transparent 65%)"></div>
  <div style="position:absolute;border-radius:50%;filter:blur(60px);pointer-events:none;inset-inline-end:-100px;top:0;width:480px;height:480px;background:radial-gradient(circle,rgba(30,46,245,.12),transparent 65%)"></div>
  <div class="wrap">
    <div class="svc-hero-inner">
      <div class="breadcrumb" style="justify-content:center;margin-bottom:22px">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">الرئيسية</a>
        <svg class="bc-sep" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        <span style="color:rgba(255,255,255,.55)">إنشاء وتصميم متاجر</span>
      </div>
      <span class="tag d" style="display:inline-block;margin-bottom:20px">خدمة متخصصة</span>
      <h1 style="font-size:clamp(32px,5vw,60px);font-weight:900;line-height:1.08;letter-spacing:-.03em;color:#fff;margin-bottom:16px">إنشاء وتصميم<br><em style="font-style:normal;background:linear-gradient(110deg,#7b90ff,#aab8ff 50%,#7b90ff);background-size:200% auto;-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;animation:sh 5s linear infinite">متجرك الإلكتروني</em></h1>
      <p style="font-size:clamp(15px,1.55vw,17.5px);line-height:1.9;color:rgba(255,255,255,.55);max-width:680px;margin-inline:auto;margin-bottom:30px">من فكرة إلى متجر جاهز للبيع — هيكل تجاري ذكي، تجربة شراء سلسة، وقابلية للنمو على أي منصة.</p>
      <div style="display:flex;gap:11px;justify-content:center;flex-wrap:wrap">
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
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center">
      <div class="sr">
        <span class="tag" style="margin-bottom:12px">لماذا يهم إعداد المتجر</span>
        <h2 class="h2" style="margin-bottom:16px">المتجر الإلكتروني<br>ليس مجرد منتجات على صفحة</h2>
        <p class="bod" style="margin-bottom:18px">الفرق بين متجر يبيع ومتجر يستهلك ميزانية إعلانك يبدأ من اللحظة الأولى — هيكل الفئات، خطوات الشراء، الثقة، وسرعة التحميل. كلها قرارات تُتّخذ في مرحلة الإعداد لا بعدها.</p>
        <div class="chklist sr d1" style="margin-bottom:24px">
          <div class="chk-item"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div><strong>هيكل ذكي</strong> — فئات منظّمة تُسهّل الوصول للمنتج المناسب</div>
          <div class="chk-item"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div><strong>تجربة شراء سلسة</strong> — أقل خطوات، أقل تخلٍّ عن السلة</div>
          <div class="chk-item"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div><strong>عناصر ثقة واضحة</strong> — تقييمات، ضمان، أمان الدفع، توصيل</div>
          <div class="chk-item"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div><strong>سيو من اليوم الأول</strong> — كل صفحة منتج وفئة مُحسَّنة للبحث</div>
        </div>
        <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-p sr d2">احجز استشارة مجانية</a>
      </div>
      <div class="sr d1">
        <div style="background:var(--navy-2);border-radius:var(--r4);padding:30px;position:relative;overflow:hidden">
          <div style="position:absolute;inset-inline-end:-50px;top:-50px;width:220px;height:220px;border-radius:50%;background:radial-gradient(circle,rgba(30,46,245,.28),transparent 70%)"></div>
          <div style="font-size:9.5px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.22);margin-bottom:20px;position:relative;z-index:1">الفرق بين متجر جيد وعادي</div>
          <div style="display:flex;flex-direction:column;gap:12px;position:relative;z-index:1">
            <?php
            foreach ( [
                [ 'label' => 'معدل التحويل — متجر محسَّن',   'val' => '3-5×', 'green' => true ],
                [ 'label' => 'التخلي عن السلة — UX ضعيف',  'val' => '70%',  'green' => false ],
                [ 'label' => 'نمو المبيعات بالسيو التقني',   'val' => '+84%', 'green' => true ],
                [ 'label' => 'كلفة العميل عبر البحث العضوي', 'val' => '↓ 60%', 'green' => true ],
            ] as $stat ) :
                $bg  = $stat['green'] ? 'rgba(16,185,129,.1)' : 'rgba(220,38,38,.08)';
                $brd = $stat['green'] ? 'rgba(16,185,129,.2)' : 'rgba(220,38,38,.16)';
                $col = $stat['green'] ? 'var(--green)' : '#f87171';
            ?>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:14px 16px;background:<?php echo $bg; ?>;border:1px solid <?php echo $brd; ?>;border-radius:var(--r2)">
              <div style="font-size:13px;color:rgba(255,255,255,.78)"><?php echo esc_html( $stat['label'] ); ?></div>
              <div style="font-size:17px;font-weight:900;color:<?php echo $col; ?>"><?php echo esc_html( $stat['val'] ); ?></div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- What We Do -->
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">ماذا نقدّم</span><h2 class="h2">ثمانية محاور لمتجر يبيع فعلاً</h2></div>
    <div class="wd-grid">
      <?php
      foreach ( [
          [ 'title' => 'هيكل فئات محسَّن',        'desc' => 'تنظيم المنتجات بطريقة تُسهّل على العميل الوصول السريع وتُحسّن السيو.',
            'svg' => '<path d="M3 3h18v4H3z"/><path d="M3 10h7v11H3z"/><path d="M13 10h8v5h-8z"/><path d="M13 18h8v3h-8z"/>' ],
          [ 'title' => 'تجربة شراء سلسة',         'desc' => 'من المنتج إلى إتمام الشراء بأقل خطوات ممكنة — لتقليل التخلي عن السلة.',
            'svg' => '<path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>' ],
          [ 'title' => 'تحسين صفحات المنتجات',   'desc' => 'صور عالية الجودة، وصف مُقنع، سيو مُدمج، وعناصر ثقة في كل صفحة.',
            'svg' => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>' ],
          [ 'title' => 'سيو تقني للمتجر',         'desc' => 'معالجة المحتوى المتكرر، Canonical Tags، وتحسين صفحات الفئات للبحث.',
            'svg' => '<circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>' ],
          [ 'title' => 'عناصر الثقة والأمان',     'desc' => 'شهادة SSL، بوابات دفع موثوقة، سياسة إرجاع واضحة، وتقييمات حقيقية.',
            'svg' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>' ],
          [ 'title' => 'ربط مع أدوات التسويق',   'desc' => 'تكامل مع Meta Pixel، Google Analytics، حملات إعادة الاستهداف.',
            'svg' => '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>' ],
          [ 'title' => 'تحسين سرعة التحميل',     'desc' => 'ضغط الصور، CDN، وتحسين الكود لتحقيق Core Web Vitals مثالية.',
            'svg' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>' ],
          [ 'title' => 'تقارير المبيعات والأداء', 'desc' => 'لوحة تحكم تربط الترافيك بالمبيعات — لتعرف ما يعمل وما يحتاج تحسيناً.',
            'svg' => '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>' ],
      ] as $idx => $wdc ) :
          $dc = [ '', 'd1', 'd2', 'd1', 'd2', 'd1', 'd2', 'd1' ][ $idx ];
      ?>
      <div class="wd-card sr <?php echo esc_attr( $dc ); ?>">
        <div class="wd-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="21" height="21"><?php echo $wdc['svg']; ?></svg></div>
        <div class="wd-body">
          <h3><?php echo esc_html( $wdc['title'] ); ?></h3>
          <p><?php echo esc_html( $wdc['desc'] ); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Platform Cards -->
<section id="platforms" class="sec sec-white">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">المنصات</span><h2 class="h2">4 منصات — واحدة تناسبك بالضبط</h2><p class="bod">نتخصص في المنصات الأربع الأكثر استخداماً في السوق السعودي والخليجي.</p></div>
    <div class="ep-plat-grid">
      <?php
      foreach ( [
          [
              'name' => 'سلة', 'abbr' => 'سل', 'color' => '#00b286', 'flag' => 'سعودية ✓',
              'tag' => 'الخيار الأول في السوق السعودي',
              'desc' => 'منصة سعودية أصيلة مُدمجة مع نظام المدفوعات المحلية والشحن — مثالية لأي متجر يستهدف السوق السعودي.',
              'url' => sh_page_url( 'services/salla' ),
              'feats' => [ 'تكامل مدفوعات سعودية فوري (مدى، Apple Pay، تقسيط)', 'لوحة تحكم عربية سهلة الاستخدام', 'تكامل مع شركات الشحن المحلية', 'ملكية كاملة للعلامة التجارية' ],
          ],
          [
              'name' => 'زد', 'abbr' => 'زد', 'color' => '#5b4fcf', 'flag' => 'سعودية ✓',
              'tag' => 'بنية تقنية قوية للنمو',
              'desc' => 'منصة سعودية بهوية قوية وإمكانيات تقنية متقدمة — للتجار الجادين الذين يريدون تحكماً أكبر.',
              'url' => sh_page_url( 'services/zid' ),
              'feats' => [ 'تخصيص متقدم لتجربة المتجر', 'APIs مفتوحة للتكامل مع أنظمة ERP', 'أدوات تسويق ومرونة عالية', 'دعم فني عربي متخصص' ],
          ],
          [
              'name' => 'شوبيفاي', 'abbr' => 'Sh', 'color' => '#95bf47', 'flag' => 'عالمية',
              'tag' => 'المنصة العالمية الأقوى',
              'desc' => 'الأقوى عالمياً في التجارة الإلكترونية — للمتاجر التي تستهدف الأسواق الدولية أو تحتاج قدرات متقدمة.',
              'url' => sh_page_url( 'services/shopify' ),
              'feats' => [ '+8000 تطبيق وإضافة جاهزة', 'نظام دفع شوبيفاي + بوابات متعددة', 'سيو قوي ومتجر متعدد العملات', 'تقارير تحليلية احترافية' ],
          ],
          [
              'name' => 'ووكومرس', 'abbr' => 'Wc', 'color' => '#7f54b3', 'flag' => 'مفتوح المصدر',
              'tag' => 'تحكم كامل على ووردبريس',
              'desc' => 'لمن يريد تحكماً كاملاً في كل تفصيل المتجر — ووردبريس + ووكومرس يعطيك لا حدود.',
              'url' => sh_page_url( 'services/woocommerce' ),
              'feats' => [ 'تحكم كامل في الكود والبيانات', 'آلاف الإضافات المجانية والمدفوعة', 'سيو استثنائي مع Yoast/Rank Math', 'تكامل مع أي نظام تقريباً' ],
          ],
      ] as $plat ) :
      ?>
      <div class="ep-plat-card sr">
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
          <a href="<?php echo esc_url( $plat['url'] ); ?>" style="display:inline-flex;align-items:center;gap:6px;margin-top:18px;font-size:13.5px;font-weight:700;color:var(--blue)">تفاصيل المنصة <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Sales-ready -->
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">جاهز للبيع</span><h2 class="h2">ستة أسباب تجعل متجرنا يبيع من اليوم الأول</h2></div>
    <div class="sales-grid">
      <?php
      foreach ( [
          [ 'n' => '01', 'title' => 'هيكل SEO محسَّن من الأساس',    'desc' => 'كل صفحة منتج وفئة مُهيَّأة للبحث — ليدز عضوية من اليوم الأول.' ],
          [ 'n' => '02', 'title' => 'تجربة جوال مثالية',             'desc' => 'أكثر من 80% من الشراء يتم من الجوال — تجربة سلسة وجذّابة على كل الشاشات.' ],
          [ 'n' => '03', 'title' => 'سرعة تحميل تحت ثانيتين',       'desc' => 'Core Web Vitals مثالية — كل ثانية تأخير تخسّر 7% من المبيعات.' ],
          [ 'n' => '04', 'title' => 'صور وأوصاف مُحسَّنة',          'desc' => 'كل صورة مضغوطة بدون خسارة جودة، وكل وصف مُكتوب للبيع لا للمعلومة فقط.' ],
          [ 'n' => '05', 'title' => 'تكامل أدوات التسويق',          'desc' => 'Google Analytics، Meta Pixel، إعادة الاستهداف — كلها جاهزة من اليوم الأول.' ],
          [ 'n' => '06', 'title' => 'دعم مستمر بعد الإطلاق',        'desc' => 'لسنا نُسلّم ونختفي — نتابع الأداء ونحسّن مع نمو متجرك.' ],
      ] as $idx => $sc ) :
          $dc = [ '', 'd1', 'd2', 'd1', 'd2', 'd3' ][ $idx ];
      ?>
      <div class="sales-card sr <?php echo esc_attr( $dc ); ?>">
        <div class="sales-n"><?php echo esc_html( $sc['n'] ); ?></div>
        <h3><?php echo esc_html( $sc['title'] ); ?></h3>
        <p><?php echo esc_html( $sc['desc'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- UX Considerations -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">تجربة الشراء</span><h2 class="h2">اعتبارات UX تصنع الفرق في المبيعات</h2></div>
    <div class="ux-list">
      <?php
      foreach ( [
          [ 'title' => 'صفحة المنتج المقنعة',   'desc' => 'صور متعددة، وصف واضح، مراجعات، وزر CTA بارز',
            'svg' => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>' ],
          [ 'title' => 'سلة التسوق المُحسَّنة',  'desc' => 'ملخص واضح، تعديل سهل، وزر إتمام الشراء بارز دائماً',
            'svg' => '<path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>' ],
          [ 'title' => 'Checkout في خطوة واحدة', 'desc' => 'أقل حقول ممكنة، حفظ العنوان، وتعدد طرق الدفع',
            'svg' => '<polyline points="20 6 9 17 4 12"/>' ],
          [ 'title' => 'بحث داخلي ذكي',          'desc' => 'اقتراحات فورية، تصحيح أخطاء الكتابة، وفلترة سريعة',
            'svg' => '<circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>' ],
          [ 'title' => 'عناصر ثقة في كل مكان',  'desc' => 'شارات الدفع الآمن، سياسة الإرجاع، وأيقونات الشحن',
            'svg' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>' ],
          [ 'title' => 'منتجات مقترحة ذكية',     'desc' => 'Upsell وCross-sell في الأماكن الصحيحة لزيادة متوسط الطلب',
            'svg' => '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>' ],
      ] as $idx => $ux ) :
          $dc = [ '', 'd1', 'd2', 'd1', 'd2', 'd1' ][ $idx ];
      ?>
      <div class="ux-item sr <?php echo esc_attr( $dc ); ?>">
        <div class="ux-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?php echo $ux['svg']; ?></svg></div>
        <div class="ux-body">
          <strong><?php echo esc_html( $ux['title'] ); ?></strong>
          <p><?php echo esc_html( $ux['desc'] ); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Who is this for -->
<section class="sec sec-navy">
  <div class="wrap">
    <div class="sh c sr"><span class="tag d">لمن هذه الخدمة</span><h2 class="h2 wh">3 أنواع من الأعمال نخدمها بتميّز</h2></div>
    <div class="who-grid">
      <?php
      foreach ( [
          [ 'title' => 'متاجر في طور الإنشاء',    'desc' => 'تبدأ صح من اليوم الأول — هيكل، تصميم، وسيو جاهزين للنمو.', 'svg' => '<path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>' ],
          [ 'title' => 'متاجر تريد إعادة هيكلة',  'desc' => 'متجرك قائم لكن المبيعات لا تعكس حجم الجهد — نُعيد بناءه بشكل صحيح.', 'svg' => '<path d="M21.2 8.4c.5.38.8.97.8 1.6v10a2 2 0 01-2 2H4a2 2 0 01-2-2V10a2 2 0 012-2h3.8"/><path d="M16 4l-4-4-4 4"/><path d="M12 0v13"/>' ],
          [ 'title' => 'متاجر تريد التوسع',        'desc' => 'أعمالك تنمو وتحتاج بنية تقنية وسيو قادرَين على استيعاب هذا النمو.', 'svg' => '<polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/>' ],
      ] as $wc ) :
      ?>
      <div class="who-card sr">
        <div class="who-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="18" height="18"><?php echo $wc['svg']; ?></svg></div>
        <h3><?php echo esc_html( $wc['title'] ); ?></h3>
        <p><?php echo esc_html( $wc['desc'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="sec sec-off">
  <div class="wrap">
    <div class="faq-cta-layout">
      <div>
        <div class="sh sr"><span class="tag">الأسئلة الشائعة</span><h2 class="h2">أسئلة عن إنشاء المتاجر</h2></div>
        <div class="faq-list sr d1">
          <?php
          foreach ( [
              [ 'q' => 'كيف أختار بين سلة وزد وشوبيفاي؟', 'a' => 'إذا كنت تستهدف السوق السعودي فقط وتريد سهولة الإعداد — سلة هي الخيار. إذا كنت تريد مرونة تقنية أعلى — زد. وإذا كنت تستهدف الأسواق الخليجية والدولية — شوبيفاي. نُساعدك في القرار بناءً على وضعك.' ],
              [ 'q' => 'هل يتضمن التسليم سيو المتجر؟', 'a' => 'نعم — كل متجر نُسلّمه يأتي مُحسَّناً للسيو التقني: روابط نظيفة، Schema للمنتجات، Sitemap، وسرعة مثالية. لكن السيو المحتوائي المستمر خدمة منفصلة.' ],
              [ 'q' => 'كم يستغرق بناء المتجر؟', 'a' => 'متجر بسيط (أقل من 50 منتج) من 2-4 أسابيع. متجر متوسط 4-8 أسابيع. متجر كبير مع تكاملات متعددة 2-4 أشهر.' ],
              [ 'q' => 'هل يمكنني نقل متجري من منصة إلى أخرى؟', 'a' => 'نعم — نُنفذ نقل المتاجر مع الحفاظ على المنتجات، الطلبات، وترتيب جوجل. نُعدّ Redirects صحيحة لمنع خسارة السيو.' ],
          ] as $faq ) :
          ?>
          <div class="faq-item">
            <div class="faq-q"><span><?php echo esc_html( $faq['q'] ); ?></span><div class="faq-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></div></div>
            <div class="faq-a"><div class="faq-a-inner"><?php echo esc_html( $faq['a'] ); ?></div></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="cta-sticky">
        <div class="cta-side-card sr d1">
          <span class="tag d" style="position:relative;z-index:1;margin-bottom:10px">ابدأ الآن</span>
          <h3 style="font-size:clamp(20px,2.5vw,26px);font-weight:900;color:#fff;margin-bottom:12px;line-height:1.2;position:relative;z-index:1">جاهز لبناء<br>متجرك الاحترافي؟</h3>
          <p style="font-size:13.5px;color:rgba(255,255,255,.5);line-height:1.8;margin-bottom:22px;position:relative;z-index:1">احجز استشارة مجانية ونحدد معاً المنصة المناسبة وخطة البناء.</p>
          <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-p" style="width:100%;justify-content:center;position:relative;z-index:1">احجز استشارة مجانية</a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
get_template_part( 'template-parts/layout/cta-banner', null, [
    'tag'   => 'ابدأ الآن',
    'title' => 'جاهز لبناء متجرك؟',
] );
?>

<?php get_footer(); ?>
