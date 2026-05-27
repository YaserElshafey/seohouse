<?php
/**
 * Template Name: Service — Web Design Overview
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
        <span style="color:rgba(255,255,255,.55)">إنشاء وتصميم مواقع</span>
      </div>
      <span class="tag d" style="display:inline-block;margin-bottom:20px">خدمة متخصصة</span>
      <h1 style="font-size:clamp(32px,5vw,60px);font-weight:900;line-height:1.08;letter-spacing:-.03em;color:#fff;margin-bottom:16px">تصميم مواقع<br><em style="font-style:normal;background:linear-gradient(110deg,#7b90ff,#aab8ff 50%,#7b90ff);background-size:200% auto;-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;animation:sh 5s linear infinite">تبني الثقة وتجلب العملاء</em></h1>
      <p style="font-size:clamp(15px,1.55vw,17.5px);line-height:1.9;color:rgba(255,255,255,.6);max-width:680px;margin-inline:auto;margin-bottom:30px">الموقع الإلكتروني هو مقرّك الرقمي — نُصمّمه ليُمثّلك باحترافية، يُحمّل بسرعة، يُقنع زوّارك، ويُحوّلهم إلى عملاء فعليين.</p>
      <div style="display:flex;gap:11px;justify-content:center;flex-wrap:wrap">
        <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-p lg">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          احجز استشارة مجانية
        </a>
        <a href="#types" class="btn btn-g lg">أنواع المواقع التي نُصمّمها</a>
      </div>
    </div>
  </div>
</section>

<!-- Why websites matter -->
<section class="sec sec-white">
  <div class="wrap">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center">
      <div class="sr">
        <span class="tag" style="margin-bottom:12px">لماذا يهم الموقع</span>
        <h2 class="h2" style="margin-bottom:16px">قبل أن يتّصل بك العميل،<br>هو يبحث عنك</h2>
        <p class="bod" style="margin-bottom:18px">في أول 3 ثوانٍ من زيارة موقعك، يُقرّر زائرك إن كان سيتعامل معك أم لا. الموقع الضعيف يُضيع كل ميزانيتك التسويقية لأنه يفقد الزوار قبل أن يقرأوا عرضك أصلاً.</p>
        <div class="chklist sr d1" style="margin-bottom:24px">
          <div class="chk-item"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div><strong>الانطباع الأول يبني الثقة</strong> — أو يقتلها فوراً</div>
          <div class="chk-item"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div><strong>السرعة قرار شراء</strong> — موقع بطيء = عميل ضائع</div>
          <div class="chk-item"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div><strong>السيو يبدأ من البناء</strong> — لا يُضاف لاحقاً</div>
          <div class="chk-item"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div><strong>التحويل يُصمّم</strong> — لا يحدث صدفة</div>
        </div>
        <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-p sr d2">احجز استشارة مجانية</a>
      </div>
      <div class="sr d1">
        <div style="background:var(--navy-2);border-radius:var(--r4);padding:30px;position:relative;overflow:hidden">
          <div style="position:absolute;inset-inline-end:-50px;top:-50px;width:220px;height:220px;border-radius:50%;background:radial-gradient(circle,rgba(30,46,245,.28),transparent 70%)"></div>
          <div style="font-size:9.5px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.22);margin-bottom:20px;position:relative;z-index:1">ماذا يفقد الموقع الضعيف</div>
          <div style="display:flex;flex-direction:column;gap:12px;position:relative;z-index:1">
            <?php
            foreach ( [
                [ 'label' => 'زوار يغادرون قبل 3 ثوانٍ',            'val' => '53%',  'bad' => true ],
                [ 'label' => 'قرار ثقة من المظهر فقط',              'val' => '75%',  'bad' => true ],
                [ 'label' => 'انخفاض التحويل لكل ثانية تأخير',       'val' => '7%',   'bad' => true ],
                [ 'label' => 'موقع ممتاز يُضاعف الليدز',             'val' => '2-3×', 'bad' => false ],
            ] as $stat ) :
                $bg  = $stat['bad'] ? 'rgba(220,38,38,.08)' : 'rgba(16,185,129,.1)';
                $brd = $stat['bad'] ? 'rgba(220,38,38,.16)' : 'rgba(16,185,129,.2)';
                $col = $stat['bad'] ? '#f87171' : 'var(--green)';
            ?>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:14px 16px;background:<?php echo $bg; ?>;border:1px solid <?php echo $brd; ?>;border-radius:var(--r2)">
              <div style="font-size:13.5px;color:rgba(255,255,255,<?php echo $stat['bad'] ? '.78' : '.85'; ?>);<?php echo $stat['bad'] ? '' : 'font-weight:700'; ?>"><?php echo esc_html( $stat['label'] ); ?></div>
              <div style="font-size:18px;font-weight:900;color:<?php echo $col; ?>;letter-spacing:-.02em"><?php echo esc_html( $stat['val'] ); ?></div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Types CSS — inline after head to guarantee override of any WP-injected styles -->
<style>
#types .types-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px}
#types .type-card{display:block!important;background:#fff;border:1px solid rgba(14,22,80,.09);border-radius:20px;padding:28px 26px;transition:all .26s cubic-bezier(.25,.46,.45,.94);position:relative;overflow:hidden}
#types .type-card::before{content:'';position:absolute;top:0;inset-inline-end:0;width:100px;height:100px;border-radius:50%;background:radial-gradient(circle,rgba(30,46,245,.06),transparent 70%);transform:translate(40%,-40%);transition:transform .35s;pointer-events:none}
#types .type-card:hover{transform:translateY(-4px);box-shadow:0 8px 32px rgba(9,16,46,.10);border-color:rgba(30,46,245,.18)}
#types .type-card:hover::before{transform:translate(20%,-20%) scale(1.4)}
#types .type-tag{display:inline-block!important;font-size:10.5px;font-weight:800;color:#1e2ef5;background:#eef0ff;padding:5px 11px;border-radius:50px;margin-bottom:14px;letter-spacing:.04em;position:relative;line-height:1.4}
#types .type-card h3{font-size:19px!important;font-weight:900!important;color:#09102e;margin-bottom:10px!important;letter-spacing:-.02em;position:relative;line-height:1.2!important}
#types .type-card p{font-size:13.5px!important;color:#7880b0;line-height:1.85!important;margin-bottom:16px!important;position:relative}
#types .type-feats{display:flex!important;flex-direction:column;gap:8px;position:relative}
#types .type-feat{display:flex!important;align-items:flex-start;gap:8px;font-size:12.5px;color:#3d4576;line-height:1.65}
#types .type-feat svg{stroke:#1e2ef5;flex-shrink:0;margin-top:3px;display:block}
@media(max-width:1100px){#types .types-grid{grid-template-columns:1fr}}
@media(max-width:600px){#types .types-grid{gap:10px}}
</style>

<!-- Types of Websites -->
<section id="types" class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">أنواع المواقع</span><h2 class="h2">نُصمّم 6 أنواع من المواقع</h2><p class="bod">لكل نشاط نوع موقع يخدم أهدافه بأفضل شكل ممكن — اختيار النوع الصحيح هو نصف الطريق للنجاح.</p></div>
    <div class="types-grid">
      <?php
      foreach ( [
          [ 'tag' => 'للشركات والمؤسسات', 'title' => 'مواقع الشركات', 'desc' => 'موقع يُمثّل علامتك التجارية أمام السوق — يُبرز خبرتك، عملاءك، خدماتك، ويبني صورة احترافية للشركة.', 'dc' => '',
            'feats' => [ 'صفحات: عن الشركة، الخدمات، فريق العمل، المعرض', 'عناصر بناء الثقة: شهادات، عملاء، إنجازات', 'متعدد اللغات (عربي/إنجليزي) عند الحاجة' ] ],
          [ 'tag' => 'للخدمات الاحترافية', 'title' => 'مواقع الخدمات', 'desc' => 'صفحات خدمة مُحسّنة للسيو والتحويل — كل خدمة لها صفحة مستقلة تُجيب على أسئلة العميل وتقنعه بالتواصل.', 'dc' => 'd1',
            'feats' => [ 'صفحة منفصلة لكل خدمة محسّنة لكلمة محددة', 'تكامل مع نظام حجز/تواصل/استشارات', 'هيكل مدوّنة جاهز للسيو والمحتوى' ] ],
          [ 'tag' => 'للحملات الإعلانية', 'title' => 'صفحات هبوط (Landing Pages)', 'desc' => 'صفحة هدفها واحد فقط: تحويل زائر الإعلان إلى ليد أو عميل. مُصمّمة بمنهجية CRO لتحقيق أعلى نسبة تحويل ممكنة.', 'dc' => 'd2',
            'feats' => [ 'هيكل CRO مُختبر: عرض، فوائد، إثبات، نموذج', 'سرعة تحميل أقل من ثانيتين (Core Web Vitals)', 'ربط بـ Google/Meta/TikTok Analytics وPixel' ] ],
          [ 'tag' => 'للعلامات الشخصية', 'title' => 'مواقع الشخصيات والمستشارين', 'desc' => 'موقع يُبرز خبرتك الشخصية — لمستشار، خبير، طبيب، مدرّب، أو صاحب شركة يبني علامته الشخصية.', 'dc' => 'd3',
            'feats' => [ 'سيرة احترافية، إنجازات، ظهور إعلامي', 'مدوّنة شخصية وموارد مجانية', 'نظام حجز للاستشارات أو الجلسات' ] ],
          [ 'tag' => 'حسب الطلب', 'title' => 'مواقع مخصّصة (Custom)', 'desc' => 'عندك متطلبات خاصة تتجاوز القوالب الجاهزة؟ نُطوّر مواقع برمجية كاملة بحلول مخصّصة وتكاملات معقدة.', 'dc' => '',
            'feats' => [ 'تطوير برمجي كامل (Frontend + Backend)', 'تكاملات API وأنظمة CRM/ERP', 'لوحات تحكم مخصّصة وميزات متقدمة' ] ],
          [ 'tag' => 'للمنشئين والوكالات', 'title' => 'محافظ أعمال (Portfolio)', 'desc' => 'للمصورين، المصممين، الوكالات، والمعماريين — موقع يُعرض أعمالك بأسلوب يليق بها ويفتح أبواب التعاقد.', 'dc' => 'd1',
            'feats' => [ 'عرض بصري متقدم للأعمال والمشاريع', 'دراسات حالة (Case Studies) كاملة', 'نموذج تواصل احترافي مع تأهيل العميل' ] ],
      ] as $tc ) :
      ?>
      <div class="type-card sr <?php echo esc_attr( $tc['dc'] ); ?>">
        <span class="type-tag"><?php echo esc_html( $tc['tag'] ); ?></span>
        <h3><?php echo esc_html( $tc['title'] ); ?></h3>
        <p><?php echo esc_html( $tc['desc'] ); ?></p>
        <div class="type-feats">
          <?php foreach ( $tc['feats'] as $feat ) : ?>
          <div class="type-feat"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg><?php echo esc_html( $feat ); ?></div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Tech Stack -->
<section class="sec sec-navy">
  <div class="wrap">
    <div class="sh c sr"><span class="tag d">التقنيات</span><h2 class="h2 wh">نبني بأحدث التقنيات — ونختار الأنسب لك</h2><p class="bod d">لا نتعصب لتقنية واحدة — نختار الأداة التي تخدم مشروعك على المدى البعيد.</p></div>
    <div class="tech-grid">
      <?php
      foreach ( [
          [ 'abbr' => 'WP',  'title' => 'ووردبريس',    'desc' => 'مرن، SEO-friendly، وأشهر CMS في العالم'    ],
          [ 'abbr' => 'WF',  'title' => 'ويب فلو',      'desc' => 'تصاميم بصرية استثنائية بدون تضحية في الأداء' ],
          [ 'abbr' => 'Rx',  'title' => 'رياكت',         'desc' => 'واجهات ديناميكية ومنتجات رقمية متقدمة'        ],
          [ 'abbr' => 'Nx',  'title' => 'نكست.جي إس',   'desc' => 'أداء استثنائي مع SSR وSEO تلقائي'           ],
          [ 'abbr' => 'TS',  'title' => 'تيل ويند',      'desc' => 'تصميم سريع وقابل للتوسع بدون فوضى CSS'      ],
          [ 'abbr' => 'PG',  'title' => 'بوستجريس',      'desc' => 'قاعدة بيانات موثوقة للتطبيقات المعقدة'       ],
          [ 'abbr' => 'V',   'title' => 'فيرسيل',        'desc' => 'نشر فوري مع CDN عالمي وأداء مثالي'          ],
          [ 'abbr' => 'CF',  'title' => 'كلاود فلير',    'desc' => 'حماية وأمان وسرعة تحميل من كل مكان'         ],
      ] as $tc ) :
      ?>
      <div class="tech-card sr">
        <div class="tech-logo"><?php echo esc_html( $tc['abbr'] ); ?></div>
        <h3><?php echo esc_html( $tc['title'] ); ?></h3>
        <p><?php echo esc_html( $tc['desc'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- What Makes Effective -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">ما يصنع الفرق</span><h2 class="h2">ستة عناصر تحدد إن كان موقعك يبيع أو لا</h2><p class="bod">هذه ليست تفاصيل تقنية — هي قرارات تجارية تُحسم في مرحلة التصميم.</p></div>
    <div class="eff-grid">
      <?php
      foreach ( [
          [ 'title' => 'سرعة تحميل استثنائية',   'desc' => 'أقل من 2 ثانية على الجوال — لأن كل ثانية تأخير تكلفك 7% من التحويل.', 'svg' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>' ],
          [ 'title' => 'تجربة جوال أولاً',         'desc' => 'أكثر من 70% من زيارات السعودية من الجوال — يجب أن يكون الموقع مثالياً عليه.', 'svg' => '<rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/>' ],
          [ 'title' => 'هيكل سيو من الأساس',      'desc' => 'روابط نظيفة، Schema صحيح، سرعة مثالية — السيو يُبنى مع الموقع لا بعده.', 'svg' => '<circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>' ],
          [ 'title' => 'CTAs في الأماكن الصحيحة', 'desc' => 'أزرار الدعوة للتصرف تُحسَّن نفسياً وبصرياً لتُحوّل الزائر المهتم.', 'svg' => '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>' ],
          [ 'title' => 'عناصر بناء الثقة',         'desc' => 'شهادات، شهادات اعتماد، عملاء، وأرقام — تُقنع الزائر قبل أن يُفكر.', 'svg' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>' ],
          [ 'title' => 'محتوى يُحوّل',             'desc' => 'نصوص مُكتوبة لتُقنع، لا فقط لتُعلم — الفرق بين موقع ومتجر.', 'svg' => '<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>' ],
      ] as $idx => $ec ) :
          $dc = [ '', 'd1', 'd2', 'd1', 'd2', 'd3' ][ $idx ];
      ?>
      <div class="eff-card sr <?php echo esc_attr( $dc ); ?>">
        <div class="eff-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="19" height="19"><?php echo $ec['svg']; ?></svg></div>
        <h3><?php echo esc_html( $ec['title'] ); ?></h3>
        <p><?php echo esc_html( $ec['desc'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Process -->
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">كيف نعمل</span><h2 class="h2">5 مراحل من الفكرة إلى الإطلاق</h2><p class="bod">عملية واضحة، جداول زمنية محددة، وتواصل مستمر في كل خطوة.</p></div>
    <div class="proc-list">
      <?php
      foreach ( [
          [ 'title' => 'الاكتشاف والتخطيط', 'desc' => 'نفهم أهدافك، جمهورك، ومنافسيك لنبني على أساس صحيح' ],
          [ 'title' => 'التصميم والبروتوتايب', 'desc' => 'تصميم بصري كامل قبل أي تطوير — ترى الموقع قبل بنائه' ],
          [ 'title' => 'التطوير والبرمجة', 'desc' => 'بناء الموقع بكود نظيف، سريع، وقابل للتوسع' ],
          [ 'title' => 'الاختبار والتحسين', 'desc' => 'اختبار على كل الأجهزة وتحسين الأداء قبل الإطلاق' ],
          [ 'title' => 'الإطلاق والدعم', 'desc' => 'إطلاق مُخطَّط ودعم مستمر للتأكد من نجاح الموقع' ],
      ] as $idx => $ps ) :
      ?>
      <div class="proc-step sr <?php echo esc_attr( [ '', 'd1', 'd2', 'd1', 'd2' ][ $idx ] ); ?>">
        <div class="proc-num"><?php echo esc_html( str_pad( $idx + 1, 2, '0', STR_PAD_LEFT ) ); ?></div>
        <h3><?php echo esc_html( $ps['title'] ); ?></h3>
        <p><?php echo esc_html( $ps['desc'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="faq-cta-layout">
      <div>
        <div class="sh sr"><span class="tag">الأسئلة الشائعة</span><h2 class="h2">أسئلة عن تصميم المواقع</h2></div>
        <div class="faq-list sr d1">
          <?php
          foreach ( [
              [ 'q' => 'كم يستغرق إطلاق الموقع؟', 'a' => 'صفحة هبوط: 1–2 أسبوع. موقع شركة عادي: 3–5 أسابيع. موقع ضخم أو تطوير مخصّص: 6–12 أسبوع. الجدول الزمني الدقيق يُحدَّد بعد فهم متطلبات مشروعك.' ],
              [ 'q' => 'أيّهما أفضل: WordPress أم Webflow أم تطوير مخصّص؟', 'a' => 'يعتمد على المشروع. WordPress الأكثر مرونة لمعظم الحالات. Webflow ممتاز للتصاميم المتقدمة بصرياً. التطوير المخصّص لاحتياجات خاصة. في الاستشارة نناقش الأنسب لك.' ],
              [ 'q' => 'هل الموقع متوافق مع السيو؟', 'a' => 'نعم — كل موقع نبنيه يخرج بأساس سيو سليم: هيكل URL، Schema، Sitemap، Core Web Vitals، عناوين، روابط داخلية. لكن يبقى السيو خدمة مستقلة تُكمل البناء.' ],
              [ 'q' => 'هل تُقدّمون استضافة وصيانة؟', 'a' => 'نعم، نُقدّم باقات استضافة محسّنة للأداء وباقات صيانة شهرية تشمل التحديثات، النسخ الاحتياطية، الأمان، ومتابعة الأداء.' ],
              [ 'q' => 'ماذا لو احتجت تعديلات بعد الإطلاق؟', 'a' => 'نُقدّم فترة دعم مجانية بعد الإطلاق لتعديل أي ملاحظات. التعديلات الكبيرة لاحقاً تكون جزءاً من باقة الصيانة الشهرية.' ],
              [ 'q' => 'هل أملك الموقع بالكامل بعد الإطلاق؟', 'a' => 'نعم، الموقع ملكك بالكامل — الكود، التصميم، البيانات، الدومين. لا قيود ولا التزامات إجبارية معنا.' ],
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
          <h3 style="font-size:clamp(20px,2.5vw,26px);font-weight:900;color:#fff;margin-bottom:12px;line-height:1.2;position:relative;z-index:1">جاهز لبناء<br>موقعك الجديد؟</h3>
          <p style="font-size:13.5px;color:rgba(255,255,255,.44);line-height:1.8;margin-bottom:22px;position:relative;z-index:1">احجز استشارة مجانية، وسنناقش متطلباتك ونوصي بالتقنية الأنسب.</p>
          <div class="chklist" style="margin-bottom:22px">
            <div class="chk-item d"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div>توصية بنوع الموقع والتقنية</div>
            <div class="chk-item d"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div>عرض سعر شفاف</div>
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
    'tag'         => 'ابدأ الآن',
    'title'       => 'ابنِ موقعاً يستحق علامتك التجارية',
    'description' => 'احجز استشارة مجانية ولنبدأ معاً في تصميم موقعك الجديد.',
    'buttons'     => [
        [ 'text' => 'احجز استشارة مجانية', 'url' => sh_page_url( 'contact' ), 'class' => 'btn-w lg' ],
        [ 'text' => 'خدمة السيو', 'url' => sh_page_url( 'services/seo' ), 'class' => 'btn-g lg' ],
    ],
] );
?>

<?php get_footer(); ?>
