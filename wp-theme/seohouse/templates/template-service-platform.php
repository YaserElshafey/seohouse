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

// ─── Per-slug hardcoded content (overrides generic fallbacks) ─────────────
$slug            = get_post_field( 'post_name', get_the_ID() );
$bottom_cta_title = 'جاهز لبناء مشروعك على ' . $display_tag . '؟';

switch ( $slug ) {

    case 'wordpress':
        $dot_color     = '#0073aa';
        $emoji         = '🚀';
        $display_tag   = 'ووردبريس';
        $hero_em       = 'ووردبريس';
        $display_title = 'تصميم مواقع';
        $display_desc  = 'ووردبريس يدير 43% من مواقع الإنترنت. لو احتجت موقعاً تتحكّم به بنفسك، تُحدّث محتواه بسهولة، وتُوسّعه مع نمو عملك — هذا اختيارك الأمثل.';
        $why_h2        = 'لماذا ووردبريس مناسب لعملك؟';
        $why_p         = 'لو احتجت موقعاً مرناً يُمكنك تطويره مع نمو عملك بدون اعتماد كامل على فريق تقني، ووردبريس هو الخيار الأذكى. مفتوح المصدر، مرن، ومدعوم بمكتبة هائلة من الإضافات والقوالب.';
        $why_q         = '"الأداة الصحيحة في يد فريق محترف = نتائج تستحق الاستثمار"';
        $bottom_cta_title = 'ابدأ مشروعك على ووردبريس اليوم';
        $when_items    = [
            [ 'title' => 'لديك محتوى يحتاج تحديثاً متكرراً',        'desc' => 'مدونة، مقالات، أخبار، صفحات خدمات — تستطيع التحديث بنفسك بدون مطوّر.' ],
            [ 'title' => 'تحتاج موقعاً يكبر مع عملك',               'desc' => 'تبدأ بصفحات قليلة وتضيف أقساماً جديدة لاحقاً (متجر، أعضاء، حجوزات) بدون إعادة بناء.' ],
            [ 'title' => 'تريد سيو قوياً منذ اليوم الأول',          'desc' => 'ووردبريس + Yoast/RankMath = أفضل بنية تقنية للسيو في السوق.' ],
            [ 'title' => 'تخدم قطاعاً يعتمد على التسويق بالمحتوى', 'desc' => 'مكاتب محاماة، عيادات، شركات استشارات، عقارات — كلها تستفيد من مرونة ووردبريس.' ],
        ];
        $deliverables  = [
            [ 'title' => 'تطوير قالب مخصّص',       'desc' => 'لا نستخدم قوالب جاهزة منتشرة — نُطوّر قالباً حصرياً يعكس هويتك.', 'icon' => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>' ],
            [ 'title' => 'إعداد سيو تقني كامل',     'desc' => 'Schema، Sitemap، Core Web Vitals، روابط داخلية — جاهز لجوجل قبل الإطلاق.', 'icon' => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>' ],
            [ 'title' => 'تكاملات مع أدواتك',       'desc' => 'CRM، خدمات الإيميل، WhatsApp، نظام الحجز — ربط كامل بسير عملك.', 'icon' => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>' ],
            [ 'title' => 'أمان واستضافة محسّنة',    'desc' => 'شهادة SSL، حماية WAF، نسخ احتياطية تلقائية، وأداء عالي.', 'icon' => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>' ],
            [ 'title' => 'تدريب فريقك',              'desc' => 'جلسات تدريب على لوحة التحكم — أنت من يدير الموقع بعد التسليم.', 'icon' => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>' ],
            [ 'title' => 'دعم وصيانة دورية',         'desc' => 'باقات صيانة شهرية تضمن سلامة موقعك وتحديثاته.', 'icon' => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>' ],
        ];
        $faq_items     = [
            [ 'question' => 'متى يكون ووردبريس الخيار الأنسب؟',  'answer' => 'حين تحتاج موقعاً تُحدّثه بنفسك بسهولة، أو تخطّط للنشر المستمر (مدونة، أخبار، خدمات تتطوّر)، أو تريد بناء سيو طويل المدى.' ],
            [ 'question' => 'هل ووردبريس آمن؟',                   'answer' => 'نعم، حين يُبنى بشكل صحيح. نستخدم استضافات محسّنة، WAF، تحديثات منتظمة، ومكتبة إضافات موثوقة — لا توسّع عشوائي يفتح ثغرات.' ],
            [ 'question' => 'هل ووردبريس بطيء؟',                  'answer' => 'هذه مغالطة شائعة. ووردبريس بطيء حين يُبنى بقوالب ثقيلة وإضافات مفرطة. حين يُبنى بشكل احترافي، أداؤه ممتاز ويتفوّق على كثير من البدائل.' ],
            [ 'question' => 'كم يستغرق تطوير الموقع؟',            'answer' => 'من 3 إلى 8 أسابيع حسب التعقيد. موقع شركة عادي: 4 أسابيع. موقع متعدد اللغات بتكاملات معقدة: 8 أسابيع.' ],
        ];
        break;

    case 'webflow':
        $dot_color     = '#4353ff';
        $emoji         = '✨';
        $display_tag   = 'ويب فلو';
        $hero_em       = 'ويب فلو';
        $display_title = 'تصميم مواقع';
        $display_desc  = 'حين تريد موقعاً يصرخ "احترافية" من أول ثانية — ويب فلو هو الخيار. تصاميم لا تستطيع القوالب التقليدية تنفيذها، بأداء يصعب على التطوير المخصّص مجاراته.';
        $why_h2        = 'لماذا ويب فلو وليس غيره؟';
        $why_p         = 'ويب فلو ليس "بناء مواقع بدون كود" — هو أداة تصميم احترافية تمنح المصمم تحكّماً كاملاً مثل كتابة الكود يدوياً، بسرعة إطلاق أعلى وجودة بصرية لا تتنازل عن شيء.';
        $why_q         = '"الأداة الصحيحة في يد فريق محترف = نتائج تستحق الاستثمار"';
        $bottom_cta_title = 'ابدأ مشروعك على ويب فلو اليوم';
        $when_items    = [
            [ 'title' => 'علامتك التجارية تعتمد على الانطباع البصري', 'desc' => 'استوديوهات تصميم، مكاتب هندسة، شركات تقنية ناشئة، علامات Premium — موقع يعكس مستواك.' ],
            [ 'title' => 'تحتاج إطلاقاً سريعاً بدون تنازل عن الجودة', 'desc' => 'موقع كامل خلال أسابيع، لا أشهر — بنفس جودة التطوير المخصّص.' ],
            [ 'title' => 'لا تحتاج CMS معقّداً',                        'desc' => 'موقع فيه 5-50 صفحة، تحديثات بسيطة، بدون مدوّنة ضخمة — ويب فلو يتفوّق هنا.' ],
            [ 'title' => 'تريد رسوم متحرّكة وتفاعلات متقدّمة',          'desc' => 'تأثيرات بصرية احترافية بدون كتابة JavaScript — لا يقدر عليها ووردبريس بسهولة.' ],
        ];
        $deliverables  = [
            [ 'title' => 'تصميم بصري مميّز',            'desc' => 'كل صفحة مصمّمة من الصفر بأسلوب فريد — لا قوالب جاهزة.', 'icon' => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>' ],
            [ 'title' => 'تفاعلات وحركة احترافية',       'desc' => 'تأثيرات Scroll، Hover، Page Transitions تجعل موقعك يبدو حياً.', 'icon' => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>' ],
            [ 'title' => 'استضافة Webflow عالية الأداء', 'desc' => 'CDN عالمي، شهادة SSL، Core Web Vitals مثالية افتراضياً.', 'icon' => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>' ],
            [ 'title' => 'CMS مرن للمحتوى',              'desc' => 'إذا احتجت مدوّنة أو قائمة مشاريع — نُعدّ Webflow CMS بدقّة.', 'icon' => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>' ],
            [ 'title' => 'سيو تقني كامل',                 'desc' => 'Schema، Meta، Sitemap، Open Graph — كل عناصر السيو محسّنة.', 'icon' => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>' ],
            [ 'title' => 'دعم متواصل بعد الإطلاق',        'desc' => 'نُدرّب فريقك ونتولّى التحديثات المتقدّمة عند الحاجة.', 'icon' => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>' ],
        ];
        $faq_items     = [
            [ 'question' => 'ما الفرق بين ويب فلو وووردبريس؟',     'answer' => 'ووردبريس مرن للنشر المتكرّر وإدارة المحتوى الكثيف. ويب فلو يتفوّق في التصاميم البصرية المتقدّمة والأداء بدون مكتبة إضافات.' ],
            [ 'question' => 'هل ويب فلو مناسب للسيو؟',             'answer' => 'نعم — في الحقيقة، Core Web Vitals الافتراضية في ويب فلو ممتازة، والـ Schema سهل الإعداد. ميزة كبيرة للسيو التقني.' ],
            [ 'question' => 'هل يمكن نقل موقع موجود إلى ويب فلو؟', 'answer' => 'نعم. نقوم بنقل المحتوى، إعادة تصميم الواجهة بأسلوب ويب فلو، والحفاظ على ترتيبك في جوجل عبر إعدادات Redirects صحيحة.' ],
            [ 'question' => 'كم تستغرق المشاريع؟',                   'answer' => 'من 3 إلى 6 أسابيع — أسرع بكثير من المشاريع المخصّصة بنفس المستوى البصري.' ],
        ];
        break;

    case 'react-next':
    case 'react':
    case 'nextjs':
    case 'next-js':
        $dot_color     = '#61dafb';
        $emoji         = '⚡';
        $display_tag   = 'رياكت ونكست';
        $hero_em       = 'رياكت ونكست';
        $display_title = 'مواقع';
        $display_desc  = 'حين موقعك ليس مجرد موقع — هو منتج رقمي. منصّة، تطبيق ويب، أو SaaS يحتاج أداءً استثنائياً وتجربة مستخدم بمستوى التطبيقات الأصلية.';
        $why_h2        = 'لماذا React & Next.js؟';
        $why_p         = 'هما أحدث ما توصّلت إليه صناعة الويب. تستخدمهما Netflix، TikTok، Notion، Vercel، وآلاف الشركات التقنية الرائدة. حين تحتاج أداءً، قابلية توسّع، وتجربة تفاعلية متقدّمة — هذا اختيارك.';
        $why_q         = '"الأداة الصحيحة في يد فريق محترف = نتائج تستحق الاستثمار"';
        $bottom_cta_title = 'ابدأ مشروعك على رياكت ونكست اليوم';
        $when_items    = [
            [ 'title' => 'تبني منتجاً رقمياً وليس موقعاً عادياً',       'desc' => 'SaaS، تطبيق ويب، منصّة، أو مكوّن تفاعلي معقّد ضمن موقع.' ],
            [ 'title' => 'تحتاج أداءً استثنائياً تحت ضغط الزيارات',    'desc' => 'Next.js يقدّم Server Rendering وStatic Generation بأداء يفوق المنافسين.' ],
            [ 'title' => 'موقعك سيكبر تقنياً مع الوقت',               'desc' => 'إضافة مزايا جديدة، تكاملات API، لوحات تحكّم — كل ذلك بدون إعادة بناء.' ],
            [ 'title' => 'تستهدف تجربة بمستوى التطبيقات',               'desc' => 'انتقالات سلسة بين الصفحات، تحميل فوري، تفاعلية متقدّمة — تجربة "App-like".' ],
        ];
        $deliverables  = [
            [ 'title' => 'تطوير Frontend بريأكت',        'desc' => 'مكوّنات قابلة لإعادة الاستخدام، State Management احترافي، كود نظيف وقابل للصيانة.', 'icon' => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>' ],
            [ 'title' => 'Next.js للأداء والسيو',         'desc' => 'SSR/SSG/ISR لأقصى أداء وأفضل سيو — جوجل يحبّ Next.js.', 'icon' => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>' ],
            [ 'title' => 'تكاملات API متقدّمة',           'desc' => 'ربط Headless CMS، Stripe، Auth، Firebase، Supabase — أي خدمة تحتاجها.', 'icon' => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>' ],
            [ 'title' => 'نشر على Vercel/AWS',            'desc' => 'CDN عالمي، Edge Functions، نشر تلقائي عبر Git — بنية تحتية عالمية.', 'icon' => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>' ],
            [ 'title' => 'تصميم نظام مكوّنات',            'desc' => 'Design System قابل للتوسّع — مكوّنات موحّدة تنمو مع منتجك.', 'icon' => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>' ],
            [ 'title' => 'دعم تقني متواصل',               'desc' => 'مراقبة، تحسين أداء، تطوير مزايا جديدة، وحلّ المشاكل التقنية.', 'icon' => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>' ],
        ];
        $faq_items     = [
            [ 'question' => 'متى أحتاج React/Next بدلاً من ووردبريس؟',  'answer' => 'عندما تبني منتجاً رقمياً (SaaS، منصة، تطبيق ويب) — أو موقعاً معقّداً يحتاج تفاعلية بمستوى التطبيق. للمدوّنات وموقع الشركة العادي، ووردبريس أبسط.' ],
            [ 'question' => 'هل Next.js جيد للسيو؟',                     'answer' => 'ممتاز. SSR (Server-Side Rendering) يجعل المحتوى متاحاً لجوجل فوراً — أفضل من React الكلاسيكي. كثير من المواقع الكبرى تستخدم Next.js لميزة السيو.' ],
            [ 'question' => 'كم يستغرق بناء منتج Next.js؟',             'answer' => 'منتج MVP بسيط: 6-10 أسابيع. منتج SaaS كامل: 3-6 أشهر. نُقسّم المشروع لمراحل قابلة للتسليم.' ],
            [ 'question' => 'من يصيِّن الموقع بعد التسليم؟',            'answer' => 'يمكن لفريقك التقني تولّي ذلك (الكود نظيف وموثّق)، أو نُقدّم باقة دعم شهرية تشمل الصيانة وتطوير المزايا الجديدة.' ],
        ];
        break;

    case 'custom-dev':
    case 'custom':
    case 'custom-development':
        $dot_color     = '#0066ff';
        $emoji         = '🛠️';
        $display_tag   = 'مواقع خاصة';
        $hero_em       = 'مواقع خاصة';
        $display_title = 'برمجة';
        $display_desc  = 'حين لا توجد منصّة جاهزة تخدم احتياجك — نبني الحلّ من الصفر. أنظمة مخصّصة، تكاملات معقّدة، ومنطق برمجي خاص بعملك.';
        $why_h2        = 'لماذا التطوير المخصّص؟';
        $why_p         = 'الحلول الجاهزة (ووردبريس، شوبيفاي، Webflow) ممتازة في 80% من الحالات. لكن لو احتجت ميزة فريدة، نظام داخلي معقّد، أو تكاملاً لا تدعمه أي منصّة — التطوير المخصّص هو الحلّ الوحيد.';
        $why_q         = '"الأداة الصحيحة في يد فريق محترف = نتائج تستحق الاستثمار"';
        $bottom_cta_title = 'ابدأ مشروعك على مواقع خاصة اليوم';
        $when_items    = [
            [ 'title' => 'منطق عملك معقّد ولا تدعمه منصّة جاهزة', 'desc' => 'حساب أسعار ديناميكي، نظام حجز متعدّد المراحل، إدارة طلبات معقّدة.' ],
            [ 'title' => 'تحتاج نظاماً داخلياً (Internal Tool)',    'desc' => 'لوحة إدارة، نظام موظّفين، CRM داخلي مخصّص، أداة تقرير.' ],
            [ 'title' => 'تكاملات API متعدّدة وحرجة',               'desc' => 'ربط مع ERP، أنظمة محاسبة، خدمات شحن، أنظمة الحكومة، WhatsApp Business API.' ],
            [ 'title' => 'تخطّط لمنتج SaaS كامل',                   'desc' => 'منصّة بنموذج اشتراك، حسابات مستخدمين، دفع متكرّر، تحليلات.' ],
        ];
        $deliverables  = [
            [ 'title' => 'تحليل متطلبات احترافي',    'desc' => 'نفهم عملك أولاً، نوثّق المتطلبات بدقّة، ثم نُصمّم الحلّ — لا قفز للبرمجة.', 'icon' => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>' ],
            [ 'title' => 'تصميم معمارية النظام',      'desc' => 'قاعدة بيانات، APIs، الأمان، قابلية التوسّع — كل القرارات التقنية مدروسة من البداية.', 'icon' => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>' ],
            [ 'title' => 'تطوير Backend احترافي',     'desc' => 'Node.js، Laravel، Django — حسب احتياج المشروع. كود نظيف، اختبارات، توثيق كامل.', 'icon' => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>' ],
            [ 'title' => 'تطوير Frontend متقدّم',     'desc' => 'React/Next/Vue حسب المشروع — تجربة استخدام بمستوى احترافي.', 'icon' => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>' ],
            [ 'title' => 'DevOps ونشر آمن',           'desc' => 'AWS، Docker، CI/CD، مراقبة، نسخ احتياطية — البنية التحتية احترافية.', 'icon' => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>' ],
            [ 'title' => 'دعم وتطوير مستمر',          'desc' => 'بعد الإطلاق نتولّى الصيانة، إضافة المزايا، وتطوير النظام مع نمو عملك.', 'icon' => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>' ],
        ];
        $faq_items     = [
            [ 'question' => 'متى أحتاج تطويراً مخصّصاً وليس منصّة جاهزة؟', 'answer' => 'حين لا تستطيع المنصّات الجاهزة تنفيذ احتياجك بشكل كامل، أو تتطلّب تنازلات كبيرة. عادة: منطق عمل معقّد، تكاملات حرجة، أو منتج SaaS كامل.' ],
            [ 'question' => 'كم تكلفة التطوير المخصّص؟',                    'answer' => 'أكثر من المنصّات الجاهزة بالطبع — لأنه يستثمر في حلّ مفصّل عليك. نُقدّم تقدير تكلفة بعد جلسة تحليل المتطلّبات.' ],
            [ 'question' => 'كم يستغرق المشروع؟',                           'answer' => 'MVP بسيط: 8-12 أسبوع. منتج كامل: 4-9 أشهر. نُقسّم المشروع لمراحل قابلة للتسليم لتبدأ الاستفادة باكراً.' ],
            [ 'question' => 'من يملك الكود بعد التسليم؟',                  'answer' => 'أنت بالكامل. كود نظيف، موثّق، على Git خاص بك — تستطيع التعاقد مع أي فريق آخر لتطويره لاحقاً.' ],
        ];
        break;
}
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
    'title' => $bottom_cta_title,
] );

get_footer();
