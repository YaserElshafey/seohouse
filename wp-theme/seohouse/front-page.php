<?php
/**
 * Homepage template
 */
get_header();

// ── Options ────────────────────────────────────────────────────
$hero_headline    = sh_option( 'hero_headline', "كن آخر نقرة\nيبحث عنها عميلك" );
$hero_emphasis    = sh_option( 'hero_emphasis', 'آخر نقرة' );
$hero_subtext     = sh_option( 'hero_subtext', 'نضع موقعك أمام العملاء الذين يبحثون عمّا تقدّمه — بدقة، بأدلة، وبنتائج قابلة للقياس.' );
$hero_cta_primary = sh_option( 'hero_cta_primary', 'احجز استشارة مجانية 30 دقيقة' );
$why_title        = sh_option( 'why_title', "نعمل بمنطق الأداء،\nلا بمنطق الوعود" );
$why_text         = sh_option( 'why_text', 'لسنا وكالة تسويق عامة. نحن متخصصون في محركات البحث ونعرف ما يحتاجه موقعك للوصول إلى العملاء الصحيحين.' );
$process_steps    = sh_option( 'process_steps', [] );
$services_section_title   = sh_option( 'services_section_title',   'خدمات تبني وجودك الرقمي' );
$services_section_desc    = sh_option( 'services_section_desc',    'من تحسين محركات البحث إلى إنشاء المتاجر — نغطي كل ما يحتاجه عملك.' );
$industries_section_title = sh_option( 'industries_section_title', 'خبرة عملية في قطاعك' );
$industries_section_desc  = sh_option( 'industries_section_desc',  'استراتيجيتنا مبنية على خصوصية نشاطك — لا على قوالب جاهزة.' );
$process_section_title    = sh_option( 'process_section_title',    'منهجية واضحة من البداية للنتيجة' );
$process_section_desc     = sh_option( 'process_section_desc',     'خطوات محددة لكل مشروع حتى نصل إلى نتائج مقاسة.' );
$hp_cta_title             = sh_option( 'hp_cta_title',             'موقعك يستحق أن يُرى' );
$hp_cta_desc              = sh_option( 'hp_cta_desc',              'خصص 30 دقيقة معنا — وسنخبرك بصدق أين أنت وأين يمكن أن تكون.' );
$hp_hero_live_badge    = sh_option( 'hp_hero_live_badge',    'نعمل الآن على مشاريع تحسين نشطة' );
$hp_hero_cta_secondary = sh_option( 'hp_hero_cta_secondary', 'تعرّف على خدماتنا' );
$hp_cases_title        = sh_option( 'hp_cases_title',        'الأرقام تتكلم' );
$hp_cases_desc         = sh_option( 'hp_cases_desc',         'نماذج من مشاريع نفّذناها — الأرقام الفعلية تُضاف بعد موافقة العملاء.' );
$hp_why_points         = sh_option( 'hp_why_points',         [] );
$contact_url      = sh_page_url( 'contact' );
$seo_url          = sh_page_url( 'services/seo' );
$results_url      = get_post_type_archive_link( 'case_study' ) ?: sh_page_url( 'results' );

// Build display headline with <em> for gradient word
$display_headline = $hero_emphasis
    ? str_replace( $hero_emphasis, '<em>' . esc_html( $hero_emphasis ) . '</em>', esc_html( $hero_headline ) )
    : esc_html( $hero_headline );
$display_headline = nl2br( $display_headline );

// Default why feature points
if ( empty( $hp_why_points ) ) {
    $hp_why_points = [
        [ 'wp_title' => 'تقارير واضحة',       'wp_desc' => 'تقرير شهري تفصيلي بمستوى الأداء' ],
        [ 'wp_title' => 'نتائج قابلة للقياس',  'wp_desc' => 'كل قرار مبني على بيانات حقيقية' ],
        [ 'wp_title' => 'فريق متخصص',          'wp_desc' => 'خبراء في السيو والمحتوى والتقنية' ],
        [ 'wp_title' => 'بدون عقود ملزمة',     'wp_desc' => 'نثبت قيمتنا شهراً بعد شهر' ],
    ];
}

// Default process steps
if ( empty( $process_steps ) ) {
    $process_steps = [
        [ 'step_title' => 'تدقيق وتحليل',       'step_desc' => 'نبدأ بفهم وضعك الحالي — تقني، محتوى، وروابط' ],
        [ 'step_title' => 'وضع الاستراتيجية',    'step_desc' => 'خارطة طريق مخصصة لموقعك وقطاعك وأهدافك' ],
        [ 'step_title' => 'التنفيذ والتحسين',    'step_desc' => 'نطبّق التحسينات بالترتيب الصحيح ونتابع كل خطوة' ],
        [ 'step_title' => 'القياس والتقرير',      'step_desc' => 'تقرير شهري واضح يُظهر ما تحقق وما هو مخطط' ],
    ];
}
?>

<style>
/* ── Hero ── */
#hero{position:relative;min-height:100svh;display:flex;align-items:center;background:var(--navy);overflow:hidden}
.h-bg{position:absolute;inset:0;pointer-events:none}
.h-dots{position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.04) 1px,transparent 1px);background-size:36px 36px;mask-image:radial-gradient(ellipse 90% 90% at 50% 50%,#000 10%,transparent 75%)}
.h-g1{position:absolute;inset-inline-start:-200px;bottom:-100px;width:600px;height:600px;border-radius:50%;background:radial-gradient(circle,rgba(30,46,245,.22),transparent 65%);filter:blur(60px)}
.h-g2{position:absolute;inset-inline-end:-100px;top:0;width:480px;height:480px;border-radius:50%;background:radial-gradient(circle,rgba(30,46,245,.12),transparent 65%);filter:blur(70px)}
.h-ring{position:absolute;inset-inline-start:-280px;top:-220px;width:840px;height:840px;border-radius:50%;border:1px solid rgba(30,46,245,.12)}
.h-ring::after{content:'';position:absolute;inset:100px;border-radius:50%;border:1px solid rgba(30,46,245,.07)}
.hero-grid{display:grid;grid-template-columns:1fr 1fr;gap:52px;align-items:center;position:relative;z-index:2;padding-block:clamp(116px,14vh,152px) clamp(56px,7vh,80px)}
.h-live{display:inline-flex;align-items:center;gap:8px;font-size:10.5px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.38);margin-bottom:22px;animation:fu .7s var(--ease) both}
.h-dot{width:7px;height:7px;border-radius:50%;background:var(--green);animation:lp 2s ease-in-out infinite}
.h1{font-size:clamp(36px,5.4vw,68px);font-weight:900;line-height:1.08;letter-spacing:-.03em;color:#fff;margin-bottom:18px;animation:fu .75s .08s var(--ease) both}
.h1 em{font-style:normal;background:linear-gradient(110deg,#7b90ff,#aab8ff 55%,#7b90ff);background-size:200% auto;-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;animation:sh 5s linear infinite}
.h-p{font-size:clamp(14px,1.5vw,16.5px);line-height:1.9;color:rgba(255,255,255,.46);max-width:500px;margin-bottom:32px;animation:fu .75s .16s var(--ease) both}
.h-btns{display:flex;gap:10px;flex-wrap:wrap;animation:fu .75s .24s var(--ease) both}
.h-kicker{margin-top:32px;padding-top:22px;border-top:1px solid rgba(255,255,255,.07);display:flex;align-items:center;flex-wrap:nowrap;animation:fu .75s .32s var(--ease) both}
.k-item{display:flex;align-items:center;gap:8px;font-size:12px;font-weight:600;color:rgba(255,255,255,.38);white-space:nowrap;padding-inline:14px}
.k-item:first-child{padding-inline-start:0}
.k-ico{width:25px;height:25px;border-radius:6px;background:rgba(255,255,255,.06);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.k-ico svg{stroke:rgba(255,255,255,.38);width:12px;height:12px}
.k-sep{width:1px;height:16px;background:rgba(255,255,255,.08);flex-shrink:0}
/* Hero visual */
.h-vis{animation:fi .85s .1s var(--ease) both;display:flex;flex-direction:column;gap:12px}
@keyframes fi{from{opacity:0;transform:translateX(26px)}to{opacity:1;transform:translateX(0)}}
.serp-card{background:rgba(255,255,255,.055);border:1px solid rgba(255,255,255,.09);border-radius:var(--r3);overflow:hidden;backdrop-filter:blur(12px)}
.sc{background:rgba(255,255,255,.05);padding:9px 13px;display:flex;align-items:center;gap:8px;border-block-end:1px solid rgba(255,255,255,.06)}
.sc-dots{display:flex;gap:4px}.sc-dots span{width:7px;height:7px;border-radius:50%}
.sc-dots span:nth-child(1){background:rgba(255,80,80,.4)}.sc-dots span:nth-child(2){background:rgba(255,190,50,.4)}.sc-dots span:nth-child(3){background:rgba(60,200,80,.4)}
.sc-bar{flex:1;margin-inline:10px;height:20px;background:rgba(255,255,255,.05);border-radius:5px;display:flex;align-items:center;padding-inline:9px;gap:6px}
.sc-bar svg{stroke:rgba(255,255,255,.26);flex-shrink:0;width:11px;height:11px}
.sc-bar-t{font-size:10px;color:rgba(255,255,255,.26)}
.sb{padding:15px 17px;display:flex;flex-direction:column;gap:9px}
.sr-r{display:flex;align-items:flex-start;gap:10px;padding:10px 12px;border-radius:var(--r1);border:1px solid transparent}
.sr-r.p1{background:rgba(30,46,245,.14);border-color:rgba(30,46,245,.26)}
.sr-r.p2,.sr-r.p3{background:rgba(255,255,255,.025)}
.sr-n{width:19px;height:19px;border-radius:5px;display:flex;align-items:center;justify-content:center;font-size:9.5px;font-weight:800;flex-shrink:0;margin-top:2px}
.sr-r.p1 .sr-n{background:var(--blue);color:#fff}
.sr-r.p2 .sr-n,.sr-r.p3 .sr-n{background:rgba(255,255,255,.05);color:rgba(255,255,255,.28)}
.sr-url{font-size:10px;color:rgba(255,255,255,.26);margin-bottom:3px}
.sr-t{font-size:12.5px;font-weight:700;color:rgba(255,255,255,.8);margin-bottom:3px;line-height:1.3}
.sr-r.p2 .sr-t,.sr-r.p3 .sr-t{color:rgba(255,255,255,.32)}
.sr-d{font-size:10.5px;color:rgba(255,255,255,.3);line-height:1.5}
.sr-r.p2 .sr-d,.sr-r.p3 .sr-d{color:rgba(255,255,255,.16)}
.vis-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.vm{background:rgba(255,255,255,.045);border:1px solid rgba(255,255,255,.08);border-radius:var(--r2);padding:14px 16px}
.vm-l{font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.26);margin-bottom:8px}
.sparkline{display:flex;align-items:flex-end;gap:3px;height:28px}
.sp{flex:1;border-radius:2px 2px 0 0;background:rgba(30,46,245,.28)}.sp.hi{background:rgba(123,144,255,.7)}
.kw-list{display:flex;flex-direction:column;gap:5px}
.kw-r{display:flex;align-items:center;gap:7px}
.kw-n{width:16px;height:16px;border-radius:4px;background:rgba(30,46,245,.25);display:flex;align-items:center;justify-content:center;font-size:8px;font-weight:800;color:rgba(255,255,255,.52);flex-shrink:0}
.kw-n.r1{background:var(--blue);color:#fff}
.kw-bg{flex:1;height:3.5px;background:rgba(255,255,255,.05);border-radius:2px;overflow:hidden}
.kw-f{height:100%;border-radius:2px;background:rgba(123,144,255,.42)}
.kw-r:first-child .kw-f{background:#7b90ff;width:94%}.kw-r:nth-child(2) .kw-f{width:67%}.kw-r:nth-child(3) .kw-f{width:50%}
/* Clients bar */
#clients{padding-block:20px;background:var(--surface);border-block:1px solid var(--line)}
.cl-lbl{text-align:center;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);margin-bottom:16px}
.cl-row{display:flex;align-items:center;justify-content:center;flex-wrap:wrap;gap:9px}
.cl-ph{height:40px;min-width:96px;padding-inline:16px;border-radius:var(--r1);background:#fff;border:1.5px dashed var(--line);display:flex;align-items:center;justify-content:center;font-size:10.5px;font-weight:600;color:var(--muted);transition:all .2s}
.cl-ph:hover{border-color:rgba(30,46,245,.28);background:var(--blue-50);color:var(--blue)}
/* Why */
.why-grid{display:grid;grid-template-columns:5fr 6fr;gap:60px;align-items:center}
.why-pts{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:24px}
.wpt{display:flex;align-items:flex-start;gap:11px;padding:16px 15px;border:1px solid var(--line);border-radius:var(--r2);background:var(--off);transition:all .24s var(--fast);cursor:default}
.wpt:hover{background:#fff;border-color:rgba(30,46,245,.2);box-shadow:var(--sh-sm);transform:translateY(-2px)}
.wpt-ico{width:34px;height:34px;border-radius:9px;background:var(--blue-50);display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:background .2s}
.wpt:hover .wpt-ico{background:var(--blue)}.wpt:hover .wpt-ico svg{stroke:#fff}
.wpt-ico svg{stroke:var(--blue);transition:stroke .2s;width:17px;height:17px}
.wpt-t strong{display:block;font-size:13.5px;font-weight:700;color:var(--ink);margin-bottom:3px;line-height:1.25}
.wpt-t p{font-size:12px;color:var(--muted);line-height:1.6}
.why-vis{position:relative;background:transparent;padding:0;min-height:auto;overflow:visible;display:block}
.why-vis::before{display:none}
.why-card{background:var(--navy-2);border-radius:var(--r4);padding:30px;overflow:hidden;position:relative}
.why-card::before{content:'';position:absolute;inset-inline-end:-40px;top:-40px;width:200px;height:200px;border-radius:50%;background:radial-gradient(circle,rgba(30,46,245,.28),transparent 70%);pointer-events:none}
.why-head{display:flex;align-items:center;gap:10px;margin-bottom:22px;position:relative;z-index:1}
.why-head-ico{width:36px;height:36px;border-radius:9px;background:rgba(30,46,245,.25);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.why-head-ico svg{stroke:#7b90ff;width:17px;height:17px}
.why-head-t{font-size:13.5px;font-weight:700;color:rgba(255,255,255,.7)}
.why-head-sub{font-size:11px;color:rgba(255,255,255,.3)}
.w-phases{display:flex;flex-direction:column;gap:0;position:relative;z-index:1}
.w-ph{display:flex;align-items:center;gap:12px;padding-block:11px;border-block-end:1px solid rgba(255,255,255,.06)}
.w-ph:last-child{border-block-end:none}
.ph-n{font-size:9.5px;font-weight:800;color:rgba(255,255,255,.14);width:15px;flex-shrink:0}
.ph-bw{flex:1;height:5px;background:rgba(255,255,255,.06);border-radius:3px;overflow:hidden}
.ph-bf{height:100%;border-radius:3px}
.w-ph.done .ph-bf{background:linear-gradient(90deg,var(--blue),#7b90ff)}.w-ph.prog .ph-bf{background:linear-gradient(90deg,var(--blue),rgba(30,46,245,.4))}.w-ph.wait .ph-bf{background:rgba(255,255,255,.1)}
.ph-nm{font-size:12px;font-weight:600;width:88px;text-align:right;flex-shrink:0}
.w-ph.done .ph-nm{color:rgba(255,255,255,.85)}.w-ph.prog .ph-nm{color:rgba(255,255,255,.7)}.w-ph.wait .ph-nm{color:rgba(255,255,255,.28)}
.ph-st{width:8px;height:8px;border-radius:50%;flex-shrink:0}
.w-ph.done .ph-st{background:var(--green);box-shadow:0 0 7px rgba(16,185,129,.5)}.w-ph.prog .ph-st{background:#facc15;box-shadow:0 0 7px rgba(250,204,21,.4)}.w-ph.wait .ph-st{background:rgba(255,255,255,.12)}
.why-badge{position:absolute;bottom:-14px;inset-inline-end:-14px;background:#fff;border-radius:var(--r2);padding:13px 15px;box-shadow:var(--sh-lg);display:flex;align-items:center;gap:10px}
.wb-ico{width:34px;height:34px;border-radius:9px;background:var(--blue-50);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.wb-ico svg{stroke:var(--blue);width:17px;height:17px}
.wb-t strong{display:block;font-size:13px;font-weight:800;color:var(--ink)}
.wb-t span{font-size:11px;color:var(--muted)}
/* Services */
#services{padding-block:var(--sec);background:var(--surface);position:relative;overflow:hidden}
.svc-layout{display:grid;grid-template-columns:1.15fr 1fr;gap:14px;position:relative;z-index:1;align-items:stretch}
.svc-stack{display:grid;grid-template-rows:repeat(3,1fr);gap:14px}
.svc-card{background:#fff;border:1px solid var(--line);border-radius:var(--r3);padding:24px 26px;display:flex;flex-direction:column;position:relative;overflow:hidden;transition:all .3s var(--fast)}
.svc-card::after{content:'';position:absolute;top:0;inset-inline:0;height:3px;background:linear-gradient(90deg,var(--blue-d),var(--blue));transform:scaleX(0);transform-origin:right;transition:transform .3s var(--fast)}
.svc-card:hover{box-shadow:var(--sh-md);border-color:transparent;transform:translateY(-4px)}.svc-card:hover::after{transform:scaleX(1)}
.svc-card.feat{background:linear-gradient(155deg,var(--navy) 0%,var(--navy-2) 50%,#13205a 100%);border-color:transparent;color:#fff;padding:34px 34px 30px;display:flex;flex-direction:column;justify-content:space-between;gap:24px;overflow:hidden;min-height:480px}
.svc-card.feat::before{content:'';position:absolute;inset-inline-end:-80px;top:-80px;width:340px;height:340px;border-radius:50%;background:radial-gradient(circle,rgba(30,46,245,.4),transparent 65%);pointer-events:none}
.svc-card.feat::after{display:none}.svc-card.feat:hover{transform:translateY(-3px);box-shadow:0 24px 60px -16px rgba(30,46,245,.4)}
.feat-body{position:relative;z-index:1}
.feat-tag{display:inline-flex;align-items:center;gap:7px;font-size:10.5px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.55);background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);padding:5px 12px;border-radius:50px;margin-bottom:16px}
.feat-tag-dot{width:5px;height:5px;border-radius:50%;background:#7b90ff}
.svc-card.feat h3{font-size:clamp(22px,2.6vw,28px);font-weight:900;color:#fff;margin-bottom:10px;letter-spacing:-.02em;line-height:1.2}
.svc-card.feat .feat-p{font-size:14.5px;color:rgba(255,255,255,.6);line-height:1.85;margin-bottom:18px}
.feat-cta-block{position:relative;z-index:1;padding:20px 22px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:var(--r2);backdrop-filter:blur(8px);display:flex;align-items:center;gap:16px;flex-wrap:wrap}
.feat-cta-inner{flex:1;min-width:160px}
.feat-cta-lbl{font-size:10.5px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.5);margin-bottom:4px}
.feat-cta-num{font-size:24px;font-weight:900;color:#fff;letter-spacing:-.03em;line-height:1.05;margin-bottom:4px}
.feat-cta-num em{font-style:normal;background:linear-gradient(110deg,#7b90ff,#aab8ff);background-clip:text;-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.feat-cta-desc{font-size:12px;color:rgba(255,255,255,.6);line-height:1.55;margin:0}
.feat-cta-link{display:inline-flex;align-items:center;gap:7px;font-size:13.5px;font-weight:800;color:#fff;background:var(--blue);padding:11px 18px;border-radius:var(--r1);transition:all .22s;text-decoration:none}
.feat-cta-link:hover{background:var(--blue-d);gap:11px}
.svc-stack .svc-card{padding:22px 24px;display:grid;grid-template-columns:auto 1fr;grid-template-rows:auto auto;gap:4px 16px;align-items:center}
.svc-stack .svc-card .svc-ico{grid-row:span 2;align-self:start;margin-top:4px;margin-bottom:0}
.svc-stack .svc-card h3{margin-bottom:0;grid-column:2}
.svc-stack .svc-card p{margin-bottom:0;grid-column:2;font-size:12.5px;line-height:1.65}
.svc-stack .svc-card .svc-link{display:none}
.svc-stack .svc-card::after{display:none}
.svc-stack .svc-card .svc-arrow{position:absolute;top:50%;inset-inline-start:22px;transform:translateY(-50%);opacity:.4;transition:all .22s}
.svc-stack .svc-card:hover .svc-arrow{opacity:1;inset-inline-start:14px;color:var(--blue)}
.svc-stack .svc-card:hover{box-shadow:var(--sh-sm);border-color:rgba(30,46,245,.25);transform:translateX(-3px)}
.svc-ico{width:46px;height:46px;border-radius:12px;background:var(--blue-50);display:flex;align-items:center;justify-content:center;margin-bottom:16px;flex-shrink:0;transition:background .22s}
.svc-card:hover .svc-ico{background:var(--blue)}.svc-card:hover .svc-ico svg{stroke:#fff}
.svc-ico svg{stroke:var(--blue);transition:stroke .22s;width:22px;height:22px}
.svc-card h3{font-size:17px;font-weight:800;color:var(--ink);margin-bottom:8px;letter-spacing:-.01em}
.svc-card p{font-size:13px;color:var(--muted);line-height:1.78;flex:1;margin-bottom:16px}
/* Industries */
.ind-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-top:40px}
.ind-card{background:var(--off);border:1px solid var(--line);border-radius:var(--r3);padding:22px 16px;text-align:center;transition:all .26s var(--fast);cursor:default;position:relative;overflow:hidden;text-decoration:none;display:block}
.ind-card::before{content:'';position:absolute;inset:0;background:var(--blue);opacity:0;transition:opacity .28s}
.ind-card:hover{transform:translateY(-5px);box-shadow:var(--sh-md);border-color:transparent}
.ind-card:hover::before{opacity:1}
.ind-ico{width:46px;height:46px;border-radius:12px;background:var(--blue-50);display:flex;align-items:center;justify-content:center;margin-inline:auto;margin-bottom:12px;position:relative;z-index:1;transition:background .26s,transform .28s var(--ease)}
.ind-ico svg{stroke:var(--blue);transition:stroke .26s;width:21px;height:21px}
.ind-card:hover .ind-ico{background:rgba(255,255,255,.14);transform:scale(1.08)}.ind-card:hover .ind-ico svg{stroke:#fff}
.ind-card h3{font-size:14px;font-weight:700;color:var(--ink);position:relative;z-index:1;transition:color .24s;line-height:1.3}
.ind-card:hover h3{color:#fff}
/* Process */
.proc-wrap{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-top:48px;position:relative;z-index:1}
.proc-line{position:absolute;top:22px;inset-inline-start:calc(12.5% + 18px);width:calc(75% - 36px);height:1px;background:linear-gradient(to left,rgba(30,46,245,.35),rgba(30,46,245,.08));z-index:0}
.p-step{display:flex;flex-direction:column;align-items:center;text-align:center;position:relative;z-index:1}
.p-num{width:46px;height:46px;border-radius:50%;background:rgba(30,46,245,.18);border:1.5px solid rgba(30,46,245,.35);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:900;color:#7b90ff;margin-bottom:16px;transition:all .22s}
.p-step:hover .p-num{background:var(--blue);border-color:var(--blue);color:#fff;box-shadow:var(--sh-b)}
.p-step h3{font-size:14.5px;font-weight:800;color:rgba(255,255,255,.8);margin-bottom:7px}
.p-step p{font-size:12.5px;color:rgba(255,255,255,.36);line-height:1.72;max-width:180px}
/* Reviews */
.rev-hrow{display:flex;align-items:flex-end;justify-content:space-between;gap:20px;margin-bottom:40px;flex-wrap:wrap}
.rev-agg{background:#fff;border:1px solid var(--line);border-radius:var(--r2);padding:13px 18px;display:flex;align-items:center;gap:12px}
.rev-score{font-size:34px;font-weight:900;color:var(--ink);line-height:1}
.rev-stars{display:flex;gap:3px;margin-bottom:3px}
.star-ph{width:13px;height:13px;border-radius:3px;background:var(--blue-50);border:1.5px dashed var(--blue-100)}
.rev-ct{font-size:11px;color:var(--muted)}
.g-badge{display:flex;align-items:center;gap:5px;font-size:10.5px;font-weight:600;color:var(--muted);padding:4px 8px;background:var(--surface);border-radius:5px;border:1px solid var(--line);margin-top:5px}
.rev-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:15px}
.rev-card{background:#fff;border:1.5px dashed rgba(30,46,245,.11);border-radius:var(--r3);padding:22px;display:flex;flex-direction:column;min-height:180px;transition:all .24s var(--fast)}
.rev-card:hover{box-shadow:var(--sh-md);border-color:rgba(30,46,245,.2);transform:translateY(-3px)}
.ph-stars{display:flex;gap:3px;margin-bottom:13px}.ph-star{width:12px;height:12px;border-radius:3px;background:var(--blue-50)}
.ph-lines{flex:1;display:flex;flex-direction:column;gap:5px;margin-bottom:14px}.ph-ln{height:7px;border-radius:4px;background:var(--surface)}
.ph-au{display:flex;align-items:center;gap:8px;border-top:1px solid var(--line);padding-top:12px}
.ph-av{width:34px;height:34px;border-radius:50%;background:var(--surface);border:1.5px dashed var(--line);flex-shrink:0}
.ph-nm-ln{height:7px;width:72px;border-radius:4px;background:var(--surface)}.ph-dt-ln{height:6px;width:50px;border-radius:4px;background:var(--surface)}
.rev-note{text-align:center;font-size:11px;color:var(--muted);margin-top:16px;font-style:italic}
/* Blog */
.blog-hrow{display:flex;align-items:flex-end;justify-content:space-between;gap:20px;margin-bottom:36px;flex-wrap:wrap}
.art-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.art-card{background:#fff;border:1px solid var(--line);border-radius:var(--r3);overflow:hidden;display:flex;flex-direction:column;transition:all .26s var(--fast)}
.art-card:hover{box-shadow:var(--sh-md);border-color:transparent;transform:translateY(-4px)}
.art-thumb{height:170px;background:linear-gradient(140deg,var(--navy),rgba(30,46,245,.5));position:relative;overflow:hidden}
.art-thumb::before{content:'';position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.03) 1px,transparent 1px);background-size:26px 26px}
.art-cat{position:absolute;top:12px;inset-inline-start:12px;font-size:10px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:rgba(255,255,255,.7);background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.13);padding:4px 9px;border-radius:5px;backdrop-filter:blur(8px)}
.art-body{padding:20px 18px;flex:1;display:flex;flex-direction:column}
.art-meta{display:flex;align-items:center;gap:6px;font-size:11.5px;color:var(--muted);margin-bottom:10px}
.art-meta .d{width:3px;height:3px;border-radius:50%;background:var(--muted)}
.art-card h3{font-size:16px;font-weight:800;color:var(--ink);line-height:1.35;margin-bottom:8px;flex:1;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.art-card p{font-size:12.5px;color:var(--muted);line-height:1.7;margin-bottom:14px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.art-read{display:inline-flex;align-items:center;gap:6px;font-size:12.5px;font-weight:700;color:var(--blue);transition:gap .16s}
.art-card:hover .art-read{gap:10px}
/* CTA Final */
#cta-final{padding-block:var(--sec);background:var(--blue);position:relative;overflow:hidden;text-align:center}
.ctar{position:absolute;border-radius:50%;border:1px solid rgba(255,255,255,.07);top:50%;left:50%;transform:translate(-50%,-50%);pointer-events:none}
.ctar1{width:420px;height:420px}.ctar2{width:650px;height:650px;border-color:rgba(255,255,255,.04)}.ctar3{width:880px;height:880px;border-color:rgba(255,255,255,.025)}
.cta-body{position:relative;z-index:1;max-width:560px;margin-inline:auto}
.cta-body .p{font-size:clamp(14px,1.4vw,16.5px);color:rgba(255,255,255,.55);line-height:1.88;margin-bottom:30px}
.cta-btns{display:flex;gap:10px;justify-content:center;flex-wrap:wrap}
.cta-pills{display:flex;gap:14px;justify-content:center;flex-wrap:wrap;margin-top:22px}
.cta-pill{display:flex;align-items:center;gap:5px;font-size:12.5px;color:rgba(255,255,255,.46)}
.cta-pill svg{stroke:rgba(255,255,255,.4);width:13px;height:13px}
/* Baseline fidelity details */
#services::before{content:'';position:absolute;inset-inline-end:-180px;top:-180px;width:500px;height:500px;border-radius:50%;border:1px solid rgba(30,46,245,.05);pointer-events:none}
#process{position:relative;overflow:hidden}
#process::before{content:'';position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.022) 1px,transparent 1px);background-size:38px 38px;pointer-events:none}
.ind-card{text-decoration:none}
/* Responsive */
@media(max-width:1100px){.hero-grid{grid-template-columns:1fr;gap:28px}.serp-card{display:none}.why-grid{grid-template-columns:1fr;gap:40px}.why-vis{display:none}.rev-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:860px){.svc-layout{grid-template-columns:1fr}.svc-card.feat{min-height:auto;padding:28px}.svc-stack{grid-template-rows:auto}.ind-grid{grid-template-columns:repeat(2,1fr)}.proc-wrap{grid-template-columns:repeat(2,1fr)}.proc-line{display:none}.rev-grid{grid-template-columns:1fr}.art-grid{grid-template-columns:1fr}}
@media(max-width:600px){.hero-grid{padding-block:clamp(96px,12vh,120px) 44px}.h1{font-size:clamp(34px,9vw,48px)}.h-btns{flex-direction:column}.h-btns .btn{justify-content:center;width:100%}.h-kicker{display:grid;grid-template-columns:1fr 1fr;gap:8px;padding-top:18px;margin-top:24px;border-top:1px solid rgba(255,255,255,.07)}.k-item{background:rgba(255,255,255,.045);border:1px solid rgba(255,255,255,.07);border-radius:8px;padding:9px 11px;font-size:11.5px;justify-content:center}.k-sep{display:none}.why-pts{grid-template-columns:1fr}.ind-grid{grid-template-columns:repeat(2,1fr);gap:9px}.proc-wrap{grid-template-columns:1fr;gap:0}.p-step{flex-direction:row;text-align:right;gap:16px;padding:16px 0;border-block-end:1px solid rgba(255,255,255,.06)}.p-step:last-child{border-block-end:none}.p-num{flex-shrink:0;margin-bottom:0;width:40px;height:40px;font-size:13px}.p-step h3{font-size:14px;margin-bottom:4px}.p-step p{max-width:100%;font-size:12px}.cta-btns{flex-direction:column;align-items:center}.cta-pills{flex-direction:column;align-items:center;gap:8px}.blog-hrow{flex-direction:column;align-items:flex-start}.rev-hrow{flex-direction:column;align-items:flex-start}}
</style>

<!-- ── HERO ── -->
<section id="hero">
  <div class="h-bg">
    <div class="h-dots"></div>
    <div class="h-g1"></div>
    <div class="h-g2"></div>
    <div class="h-ring"></div>
  </div>
  <div class="wrap">
    <div class="hero-grid">
      <div>
        <div class="h-live"><span class="h-dot"></span><?php echo esc_html( $hp_hero_live_badge ); ?></div>
        <h1 class="h1"><?php echo wp_kses_post( $display_headline ); ?></h1>
        <p class="h-p"><?php echo esc_html( $hero_subtext ); ?></p>
        <div class="h-btns">
          <a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-p lg">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <?php echo esc_html( $hero_cta_primary ); ?>
          </a>
          <a href="<?php echo esc_url( $seo_url ); ?>" class="btn btn-g lg"><?php echo esc_html( $hp_hero_cta_secondary ); ?></a>
        </div>
        <div class="h-kicker">
          <div class="k-item"><div class="k-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>قرارات مبنية على البيانات</div>
          <div class="k-sep"></div>
          <div class="k-item"><div class="k-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>تقارير شهرية شفافة</div>
          <div class="k-sep"></div>
          <div class="k-item"><div class="k-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2s10 4.477 10 10z"/></svg></div>بدون عقود مفتوحة</div>
        </div>
      </div>
      <div class="h-vis">
        <div class="serp-card">
          <div class="sc">
            <div class="sc-dots"><span></span><span></span><span></span></div>
            <div class="sc-bar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg><span class="sc-bar-t">شركة سيو في السعودية</span></div>
          </div>
          <div class="sb">
            <div class="sr-r p1"><div class="sr-n">1</div><div><div class="sr-url"><?php echo esc_html( parse_url( home_url(), PHP_URL_HOST ) ); ?> › الخدمات</div><div class="sr-t"><?php bloginfo( 'name' ); ?> — شركة تحسين محركات البحث</div><div class="sr-d">نتائج حقيقية، تقارير شفافة، بدون عقود ملزمة.</div></div></div>
            <div class="sr-r p2"><div class="sr-n">2</div><div><div class="sr-url">competitor.com</div><div class="sr-t">وكالة تسويق رقمي</div><div class="sr-d">خدمات تسويقية شاملة</div></div></div>
            <div class="sr-r p3"><div class="sr-n">3</div><div><div class="sr-url">agency.sa</div><div class="sr-t">خدمات SEO والإعلانات</div><div class="sr-d">ظهور في نتائج البحث</div></div></div>
          </div>
        </div>
        <div class="vis-row">
          <div class="vm"><div class="vm-l">نمو الزيارات العضوية</div><div class="sparkline"><div class="sp" style="height:28%"></div><div class="sp" style="height:36%"></div><div class="sp" style="height:32%"></div><div class="sp" style="height:50%"></div><div class="sp" style="height:58%"></div><div class="sp hi" style="height:68%"></div><div class="sp hi" style="height:76%"></div><div class="sp hi" style="height:84%"></div><div class="sp hi" style="height:92%"></div><div class="sp hi" style="height:100%"></div></div></div>
          <div class="vm"><div class="vm-l">ترتيب الكلمات المستهدفة</div><div class="kw-list"><div class="kw-r"><div class="kw-n r1">1</div><div class="kw-bg"><div class="kw-f"></div></div></div><div class="kw-r"><div class="kw-n">2</div><div class="kw-bg"><div class="kw-f"></div></div></div><div class="kw-r"><div class="kw-n">4</div><div class="kw-bg"><div class="kw-f"></div></div></div></div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── CLIENTS ── -->
<?php get_template_part( 'template-parts/sections/clients' ); ?>

<!-- ── WHY ── -->
<section id="why" class="sec sec-white">
  <div class="wrap">
    <div class="why-grid">
      <div>
        <div class="sh sr">
          <span class="tag">لماذا سيو هاوس</span>
          <h2 class="h2"><?php echo wp_kses_post( nl2br( $why_title ) ); ?></h2>
          <?php if ( $why_text ) : ?>
            <p class="bod" style="margin-top:12px"><?php echo esc_html( $why_text ); ?></p>
          <?php endif; ?>
        </div>
        <div class="why-pts">
          <?php
          $wpt_icons  = [
              '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>',
              '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
              '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>',
              '<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8m-4-4v4"/>',
          ];
          $wpt_delays = [ 'd1', 'd2', 'd3', 'd4' ];
          foreach ( $hp_why_points as $k => $pt ) :
              $dc  = $wpt_delays[ $k ] ?? 'd4';
              $ico = $wpt_icons[ $k ] ?? $wpt_icons[0];
          ?>
          <div class="wpt sr <?php echo esc_attr( $dc ); ?>">
            <div class="wpt-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?php echo $ico; // phpcs:ignore ?></svg></div>
            <div class="wpt-t"><strong><?php echo esc_html( $pt['wp_title'] ?? '' ); ?></strong><p><?php echo esc_html( $pt['wp_desc'] ?? '' ); ?></p></div>
          </div>
          <?php endforeach; ?>
        </div>
        <div style="margin-top:26px"><a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-o">احجز استشارتك المجانية</a></div>
      </div>
      <!-- Phase tracker infographic (visual only) -->
      <div class="why-vis sr d2">
        <div class="why-card">
          <div class="why-head">
            <div class="why-head-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
            <div><div class="why-head-t">خارطة تحسين موقع نموذجية</div><div class="why-head-sub">تتابع الأداء في الوقت الفعلي</div></div>
          </div>
          <div class="w-phases">
            <div class="w-ph done"><span class="ph-n">01</span><div class="ph-bw"><div class="ph-bf" style="width:100%"></div></div><span class="ph-nm">تدقيق تقني</span><span class="ph-st"></span></div>
            <div class="w-ph done"><span class="ph-n">02</span><div class="ph-bw"><div class="ph-bf" style="width:100%"></div></div><span class="ph-nm">بحث الكلمات</span><span class="ph-st"></span></div>
            <div class="w-ph prog"><span class="ph-n">03</span><div class="ph-bw"><div class="ph-bf" style="width:65%"></div></div><span class="ph-nm">تحسين الصفحات</span><span class="ph-st"></span></div>
            <div class="w-ph wait"><span class="ph-n">04</span><div class="ph-bw"><div class="ph-bf" style="width:22%"></div></div><span class="ph-nm">بناء الروابط</span><span class="ph-st"></span></div>
            <div class="w-ph wait"><span class="ph-n">05</span><div class="ph-bw"><div class="ph-bf" style="width:0%"></div></div><span class="ph-nm">قياس الأداء</span><span class="ph-st"></span></div>
          </div>
        </div>
        <div class="why-badge">
          <div class="wb-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg></div>
          <div class="wb-t"><strong>نتائج مستمرة</strong><span>تحسّن ملموس خلال 90 يوماً</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── SERVICES ── -->
<section id="services" class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">ما نقدمه</span><h2 class="h2"><?php echo esc_html( $services_section_title ); ?></h2><p class="bod"><?php echo esc_html( $services_section_desc ); ?></p></div>
    <div class="svc-layout">
      <a href="<?php echo esc_url( $seo_url ); ?>" class="svc-card feat sr" style="text-decoration:none">
        <div class="feat-body">
          <span class="feat-tag"><span class="feat-tag-dot"></span>الخدمة الأساسية</span>
          <h3>تحسين محركات البحث</h3>
          <p class="feat-p">نضع موقعك في الصفحة الأولى من جوجل بمنهجية شاملة — تدقيق تقني، بحث كلمات، تحسين صفحات، وبناء روابط موثوقة. خدمتنا الأساسية والأكثر طلباً.</p>
          <div class="svc-tags"><span class="chip">سيو المتاجر</span><span class="chip">باك لينك</span><span class="chip">كتابة محتوى</span><span class="chip">استشارات الأداء</span></div>
        </div>
        <div class="feat-cta-block">
          <div class="feat-cta-inner">
            <div class="feat-cta-lbl">أول نتائج خلال</div>
            <div class="feat-cta-num"><em>90</em> يوماً</div>
            <p class="feat-cta-desc">نتائج مقاسة، تقارير شفافة، بدون عقود ملزمة</p>
          </div>
          <span class="feat-cta-link">تعرّف على الخدمة <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
        </div>
      </a>
      <div class="svc-stack">
        <a href="<?php echo esc_url( sh_page_url( 'services/web-design' ) ); ?>" class="svc-card sr d1" style="text-decoration:none">
          <span class="svc-arrow"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg></span>
          <div class="svc-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8m-4-4v4"/></svg></div>
          <h3>إنشاء وتصميم مواقع</h3>
          <p>مواقع احترافية سريعة، مبنية للأداء والتحويل من اليوم الأول.</p>
        </a>
        <a href="<?php echo esc_url( sh_page_url( 'services/stores' ) ); ?>" class="svc-card sr d2" style="text-decoration:none">
          <span class="svc-arrow"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg></span>
          <div class="svc-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg></div>
          <h3>إنشاء وتصميم متاجر</h3>
          <p>سلة، زد، شوبيفاي — تصميم، إعداد، وربط بوابات الدفع المحلية.</p>
        </a>
        <a href="<?php echo esc_url( sh_page_url( 'services/products' ) ); ?>" class="svc-card sr d3" style="text-decoration:none">
          <span class="svc-arrow"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg></span>
          <div class="svc-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg></div>
          <h3>رفع المنتجات للمتاجر</h3>
          <p>أوصاف SEO، صور محسّنة، وتصنيفات دقيقة — متجرك جاهز للبيع.</p>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ── INDUSTRIES (Sectors CPT) ── -->
<section id="industries" class="sec sec-white">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">القطاعات</span><h2 class="h2"><?php echo esc_html( $industries_section_title ); ?></h2><p class="bod"><?php echo esc_html( $industries_section_desc ); ?></p></div>
    <div class="ind-grid">
      <?php
      $sector_icon_paths = [
          'ecommerce'  => '<path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>',
          'health'     => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
          'realestate' => '<path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
          'education'  => '<path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/>',
          'food'       => '<path d="M18 8h1a4 4 0 010 8h-1"/><path d="M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/>',
          'legal'      => '<path d="M3 6l9 6 9-6"/><path d="M3 6v12l9 6 9-6V6"/>',
          'tech'       => '<polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>',
          'tourism'    => '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>',
      ];
      $sectors = new WP_Query( [ 'post_type' => 'sector', 'posts_per_page' => 8, 'orderby' => 'menu_order', 'order' => 'ASC' ] );
      $delays  = [ '', ' d1', ' d2', ' d3', ' d1', ' d2', ' d3', ' d4' ];
      $i       = 0;
      if ( $sectors->have_posts() ) :
          while ( $sectors->have_posts() ) : $sectors->the_post();
              $icon_svg  = sh_field( 'sector_icon_svg' );
              $slug      = get_post_field( 'post_name' );
              $ico_paths = $sector_icon_paths[ $slug ] ?? '<circle cx="12" cy="12" r="10"/>';
              ?>
              <a href="<?php the_permalink(); ?>" class="ind-card sr<?php echo esc_attr( $delays[ $i ] ?? '' ); ?>">
                <div class="ind-ico">
                  <?php if ( $icon_svg ) : ?>
                    <?php echo sh_svg( $icon_svg ); ?>
                  <?php else : ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?php echo $ico_paths; // phpcs:ignore ?></svg>
                  <?php endif; ?>
                </div>
                <h3><?php the_title(); ?></h3>
              </a>
              <?php
              $i++;
          endwhile;
          wp_reset_postdata();
      else :
          // Fallback static sectors
          $fallback = [
              [ 'التجارة الإلكترونية', 'ecommerce', '<path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>' ],
              [ 'الصحة والطب',         'health',    '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>' ],
              [ 'العقارات والبناء',    'realestate','<path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>' ],
              [ 'التعليم والتدريب',    'education', '<path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/>' ],
              [ 'الأغذية والمطاعم',   'food',      '<path d="M18 8h1a4 4 0 010 8h-1"/><path d="M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/>' ],
              [ 'القانون والاستشارات','legal',     '<path d="M3 6l9 6 9-6"/><path d="M3 6v12l9 6 9-6V6"/>' ],
              [ 'التقنية والبرمجيات', 'tech',      '<polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>' ],
              [ 'السياحة والسفر',     'tourism',   '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>' ],
          ];
          foreach ( $fallback as $k => $s ) :
              ?>
              <a href="<?php echo esc_url( sh_page_url( 'sectors/' . $s[1] ) ); ?>" class="ind-card sr<?php echo esc_attr( $delays[ $k ] ?? '' ); ?>">
                <div class="ind-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?php echo $s[2]; ?></svg></div>
                <h3><?php echo esc_html( $s[0] ); ?></h3>
              </a>
              <?php
          endforeach;
      endif;
      ?>
    </div>
  </div>
</section>

<!-- ── PROCESS ── -->
<section id="process" class="sec sec-navy">
  <div class="wrap">
    <div class="sh c sr"><span class="tag d">كيف نعمل</span><h2 class="h2 wh"><?php echo esc_html( $process_section_title ); ?></h2><p class="bod d"><?php echo esc_html( $process_section_desc ); ?></p></div>
    <div class="proc-wrap" style="position:relative">
      <div class="proc-line"></div>
      <?php
      $step_delays = [ '', ' d1', ' d2', ' d3', ' d4' ];
      foreach ( $process_steps as $k => $step ) :
          $num   = str_pad( $k + 1, 2, '0', STR_PAD_LEFT );
          $delay = $step_delays[ $k ] ?? ' d4';
          $title = is_array( $step ) ? ( $step['step_title'] ?? '' ) : $step;
          $desc  = is_array( $step ) ? ( $step['step_desc'] ?? '' ) : '';
          ?>
          <div class="p-step sr<?php echo esc_attr( $delay ); ?>">
            <div class="p-num"><?php echo esc_html( $num ); ?></div>
            <div>
              <h3><?php echo esc_html( $title ); ?></h3>
              <?php if ( $desc ) : ?>
                <p><?php echo esc_html( $desc ); ?></p>
              <?php endif; ?>
            </div>
          </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── CASE STUDIES ── -->
<section id="cases" class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr"><span class="tag">نتائج فعلية</span><h2 class="h2"><?php echo esc_html( $hp_cases_title ); ?></h2><p class="bod"><?php echo esc_html( $hp_cases_desc ); ?></p></div>
    <div class="proof-grid" style="margin-top:40px">
      <?php
      $cases_query = new WP_Query( [
          'post_type'      => 'case_study',
          'posts_per_page' => 3,
          'orderby'        => 'menu_order',
          'order'          => 'ASC',
          'meta_query'     => [
              'relation' => 'OR',
              [ 'key' => 'show_on_homepage', 'value' => '1', 'compare' => '=' ],
              [ 'key' => 'show_on_homepage', 'compare' => 'NOT EXISTS' ],
          ],
      ] );
      $case_delays = [ '', ' d1', ' d2' ];
      $ci = 0;
      if ( $cases_query->have_posts() ) :
          while ( $cases_query->have_posts() ) : $cases_query->the_post();
              $cid          = get_the_ID();
              $cterms       = get_the_terms( $cid, 'case_study_sector' );
              $csector      = ( ! is_wp_error( $cterms ) && ! empty( $cterms ) ) ? $cterms[0]->name : sh_field( 'client_name', $cid, '' );
              $cmetrics  = sh_field( 'metrics', $cid );
              $cheadline = sh_field( 'headline_result', $cid ) ?: ( $cmetrics[0]['value'] ?? '—' );
              $cmeta     = sh_field( 'card_meta', $cid );
              $cdc          = $case_delays[ $ci ] ?? '';
          ?>
          <div class="proof-card sr<?php echo esc_attr( $cdc ); ?>">
            <?php if ( $csector ) : ?>
            <div class="proof-sector"><?php echo esc_html( $csector ); ?></div>
            <?php endif; ?>
            <div class="proof-result"><em><?php echo esc_html( $cheadline ); ?></em></div>
            <div class="proof-desc"><?php echo esc_html( get_the_excerpt() ); ?></div>
            <?php if ( $cmeta ) : ?>
            <div class="proof-meta"><div class="proof-dot"></div><?php echo esc_html( $cmeta ); ?></div>
            <?php endif; ?>
          </div>
          <?php $ci++;
          endwhile;
          wp_reset_postdata();
      else :
          $fallback_cases = [
              [ 'sector' => 'قطاع الصحة والطب',         'result' => '+312%', 'desc' => 'نمو في الزيارات العضوية لعيادة طبية متخصصة — انتقلت من المرتبة الـ47 إلى الأولى على كلماتها الرئيسية.', 'meta' => '8 أشهر تعاون مستمر' ],
              [ 'sector' => 'قطاع التجارة الإلكترونية', 'result' => '+184%', 'desc' => 'زيادة في المبيعات العضوية لمتجر سلة مع خفض الاعتماد على Google Ads بنسبة 45%.', 'meta' => '10 أشهر تعاون مستمر' ],
              [ 'sector' => 'قطاع التعليم والتدريب',    'result' => '+220%', 'desc' => 'نمو في عدد الليدز الشهرية لمنصة تدريب — 14 كلمة مفتاحية تنافسية في الصفحة الأولى.', 'meta' => '6 أشهر تعاون مستمر' ],
          ];
          foreach ( $fallback_cases as $fci => $fc ) : ?>
          <div class="proof-card sr<?php echo esc_attr( $case_delays[ $fci ] ?? '' ); ?>">
            <div class="proof-sector"><?php echo esc_html( $fc['sector'] ); ?></div>
            <div class="proof-result"><em><?php echo esc_html( $fc['result'] ); ?></em></div>
            <div class="proof-desc"><?php echo esc_html( $fc['desc'] ); ?></div>
            <div class="proof-meta"><div class="proof-dot"></div><?php echo esc_html( $fc['meta'] ); ?></div>
          </div>
          <?php endforeach;
      endif; ?>
    </div>
    <div style="text-align:center;margin-top:28px">
      <a href="<?php echo esc_url( $results_url ); ?>" class="btn btn-g">جميع النتائج <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
    </div>
  </div>
</section>

<!-- ── REVIEWS (placeholder — connect to Google Business Profile) ── -->
<section id="reviews" class="sec sec-off">
  <div class="wrap">
    <div class="rev-hrow sr">
      <div class="sh" style="margin-bottom:0">
        <span class="tag">تقييمات العملاء</span>
        <h2 class="h2">آراء من عملوا معنا</h2>
      </div>
      <div class="rev-agg">
        <div class="rev-score"><?php echo esc_html( sh_option( 'reviews_score', '—' ) ); ?></div>
        <div>
          <div class="rev-stars"><div class="star-ph"></div><div class="star-ph"></div><div class="star-ph"></div><div class="star-ph"></div><div class="star-ph"></div></div>
          <div class="rev-ct"><?php echo esc_html( sh_option( 'reviews_count', 'سيُضاف من Google Business Profile' ) ); ?></div>
          <div class="g-badge"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>Google Reviews</div>
        </div>
      </div>
    </div>
    <div class="rev-grid">
      <div class="rev-card sr"><div class="ph-stars"><div class="ph-star"></div><div class="ph-star"></div><div class="ph-star"></div><div class="ph-star"></div><div class="ph-star"></div></div><div class="ph-lines"><div class="ph-ln" style="width:88%"></div><div class="ph-ln" style="width:72%"></div><div class="ph-ln" style="width:58%"></div></div><div class="ph-au"><div class="ph-av"></div><div><div class="ph-nm-ln"></div><div class="ph-dt-ln" style="margin-top:4px"></div></div></div></div>
      <div class="rev-card sr d1"><div class="ph-stars"><div class="ph-star"></div><div class="ph-star"></div><div class="ph-star"></div><div class="ph-star"></div><div class="ph-star"></div></div><div class="ph-lines"><div class="ph-ln" style="width:82%"></div><div class="ph-ln" style="width:68%"></div><div class="ph-ln" style="width:48%"></div></div><div class="ph-au"><div class="ph-av"></div><div><div class="ph-nm-ln"></div><div class="ph-dt-ln" style="margin-top:4px"></div></div></div></div>
      <div class="rev-card sr d2"><div class="ph-stars"><div class="ph-star"></div><div class="ph-star"></div><div class="ph-star"></div><div class="ph-star"></div><div class="ph-star"></div></div><div class="ph-lines"><div class="ph-ln" style="width:90%"></div><div class="ph-ln" style="width:65%"></div><div class="ph-ln" style="width:52%"></div></div><div class="ph-au"><div class="ph-av"></div><div><div class="ph-nm-ln"></div><div class="ph-dt-ln" style="margin-top:4px"></div></div></div></div>
    </div>
    <p class="rev-note">للمطوّر: اربط بـ Google Business Profile API أو أضف مراجعات يدوياً</p>
  </div>
</section>

<!-- ── PLATFORMS MARQUEE ── -->
<?php get_template_part( 'template-parts/sections/platforms-marquee' ); ?>

<!-- ── BLOG PREVIEW ── -->
<section id="blog" class="sec sec-white" style="border-block-start:1px solid var(--line)">
  <div class="wrap">
    <div class="blog-hrow sr">
      <div class="sh" style="margin-bottom:0">
        <span class="tag">المدونة</span>
        <h2 class="h2">من مكتب سيو هاوس</h2>
      </div>
      <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ); ?>" class="btn btn-o">جميع المقالات</a>
    </div>
    <div class="art-grid">
      <?php
      $blog_query = new WP_Query( [ 'post_type' => 'post', 'posts_per_page' => 3, 'ignore_sticky_posts' => true ] );
      $blog_delays = [ '', ' d1', ' d2' ];
      $bi = 0;
      if ( $blog_query->have_posts() ) :
          while ( $blog_query->have_posts() ) : $blog_query->the_post();
              get_template_part( 'template-parts/cards/article-card', null, [ 'delay' => $blog_delays[ $bi ] ?? 'd2' ] );
              $bi++;
          endwhile;
          wp_reset_postdata();
      else :
          for ( $bi = 0; $bi < 3; $bi++ ) : ?>
          <div class="art-card sr<?php echo esc_attr( $blog_delays[ $bi ] ?? '' ); ?>">
            <div class="art-thumb"><span class="art-cat">تحسين محركات البحث</span></div>
            <div class="art-body"><div class="art-meta"><span>للمطوّر: أضف مقالات من لوحة التحكم</span></div><h3>عنوان المقال</h3><p>وصف مختصر للمقال يظهر في الكارد.</p><span class="art-read">اقرأ المقال <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span></div>
          </div>
          <?php endfor;
      endif; ?>
    </div>
  </div>
</section>

<!-- ── FINAL CTA ── -->
<section id="cta-final" class="sec">
  <div class="ctar ctar1"></div>
  <div class="ctar ctar2"></div>
  <div class="ctar ctar3"></div>
  <div class="wrap">
    <div class="cta-body sr">
      <span class="tag d" style="justify-content:center">ابدأ الآن</span>
      <h2 class="h2" style="color:#fff;margin-bottom:12px"><?php echo esc_html( $hp_cta_title ); ?></h2>
      <p class="p"><?php echo esc_html( $hp_cta_desc ); ?></p>
      <div class="cta-btns">
        <a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-w lg">احجز استشارة مجانية</a>
        <a href="<?php echo esc_url( $seo_url ); ?>" class="btn btn-g lg">تعرّف على السيو</a>
      </div>
      <div class="cta-pills">
        <span class="cta-pill"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>بدون التزام</span>
        <span class="cta-pill"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>30 دقيقة فقط</span>
        <span class="cta-pill"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>توصيات حقيقية</span>
      </div>
    </div>
  </div>
</section>

<?php get_footer();
