<?php
/**
 * Template Name: Service — SEO Country Page
 *
 * Reusable template for market-specific SEO landing pages.
 *
 * COUNTRY DETECTION — content is driven by the "SEO Market" ACF field
 * (field name: seo_market), not by the page slug or URL structure.
 * After creating a country page in WP Admin, open it → Page Attributes
 * (sidebar) → SEO Market → select Egypt, Saudi Arabia, or UAE → Update.
 *
 * Backward compat: if the ACF field is not yet saved, the template falls
 * back to slug-based detection (egypt / saudi-arabia / uae) so existing
 * pages continue to render before the field is configured.
 *
 * If neither ACF field nor slug resolves to a known market, an admin-only
 * warning is shown and the page renders nothing for regular visitors.
 * The template never silently defaults to the wrong country's content.
 */

// Canonical tag — output only when no SEO plugin handles it.
add_action( 'wp_head', function () {
    if ( ! class_exists( 'WPSEO_Frontend' ) && ! class_exists( 'RankMath' ) && ! class_exists( 'The_SEO_Framework_Loader' ) ) {
        echo '<link rel="canonical" href="' . esc_url( get_permalink() ) . '">' . "\n";
    }
}, 1 );

// ─ Country configuration ─────────────────────────────────────────────────────

$country_config = [

    'egypt' => [
        'area_served_en'   => 'Egypt',
        'area_served_wiki' => 'https://en.wikipedia.org/wiki/Egypt',
        'name'         => 'مصر',
        'hero_tag'     => 'تحسين محركات البحث في مصر',
        'hero_title'   => 'شركة <em>تحسين محركات البحث</em> في مصر',
        'hero_desc'    => 'مصر سوق رقمي في طور النضج — الطلب على البحث في ارتفاع مستمر، والمنافسة في كثير من القطاعات لا تزال دون سقفها. من يستثمر في السيو اليوم يبني ميزة تنافسية يصعب اللحاق بها لاحقاً.',
        'intro_tag'    => 'السوق المصري',
        'intro_title'  => 'لماذا السيو في مصر مختلف؟',
        'intro_desc'   => 'مصر سوق ضخم وتنافسي في آنٍ — كثافة الطلب على البحث عالية، لكن الاستثمار في السيو لا يزال دون المستوى في كثير من القطاعات. هذا يعني فرصاً حقيقية لمن يتحرك بجدية.',
        'points'       => [
            [ 'label' => 'قاعدة مستخدمين في نمو',      'desc' => 'السوق المصري يضم ملايين المستخدمين يبحثون يومياً بالعربية الفصحى وباللهجة المصرية' ],
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
            [ 'label' => 'سيو التجارة الإلكترونية',          'desc' => 'تحسين صفحات المنتج والفئة للمتاجر التي تخدم السوق المصري' ],
            [ 'label' => 'السيو التقني الأساسي',              'desc' => 'Core Web Vitals والفهرسة والربط الداخلي — أسس تُمكّن كل باقي الجهود' ],
        ],
        'scope_tag'    => 'نطاق الخدمة',
        'scope_title'  => 'ماذا يشمل السيو في مصر؟',
        'scope_items'  => [
            [ 'n' => '01', 'label' => 'بحث الكلمات المفتاحية',     'desc' => 'فصحى وعامية — نُحدّد الكلمات التي يستخدمها جمهورك المصري فعلاً' ],
            [ 'n' => '02', 'label' => 'التدقيق التقني الشامل',     'desc' => 'تشخيص كامل: السرعة والزحف والفهرسة وكل ما يمنع جوجل من تصنيف موقعك' ],
            [ 'n' => '03', 'label' => 'تحسين صفحات الموقع',        'desc' => 'تعديل الصفحات الموجودة لتتوافق مع نية البحث المصرية وتُقنع الزائر' ],
            [ 'n' => '04', 'label' => 'إنشاء محتوى استراتيجي',    'desc' => 'مقالات وصفحات جديدة مكتوبة لجمهور يفهم السياق المصري — لا مجرد تعبئة للكلمات' ],
            [ 'n' => '05', 'label' => 'بناء الروابط الخارجية',     'desc' => 'روابط من مصادر موثوقة تُعزز سلطة الموقع بصورة طبيعية ومستدامة' ],
            [ 'n' => '06', 'label' => 'سيو التجارة الإلكترونية',  'desc' => 'تحسين صفحات الفئات والمنتجات للمتاجر التي تخدم العميل المصري' ],
            [ 'n' => '07', 'label' => 'التحسين والاختبار المستمر', 'desc' => 'تتبع الأداء وتحديث الاستراتيجية بناءً على البيانات — لا على الافتراض' ],
            [ 'n' => '08', 'label' => 'تقارير الأداء الشهرية',    'desc' => 'تقرير يربط حركة البحث بالليدز والمبيعات ومؤشرات العائد التجاري' ],
        ],
        'process_tag'   => 'منهجية العمل',
        'process_title' => 'كيف نعمل في السوق المصري؟',
        'process_steps' => [
            [ 'n' => '01', 'title' => 'التدقيق والتشخيص الأولي',        'desc' => 'نُحلّل موقعك ومنافسيك في السوق المصري — فرص الكلمات ونقاط الضعف التقنية وما يمنعك من الظهور حالياً.' ],
            [ 'n' => '02', 'title' => 'استراتيجية مخصصة للسوق المصري',  'desc' => 'خطة واضحة: الكلمات المستهدفة وأولويات الصفحات وجدول المحتوى وخطة الروابط — كل شيء موجّه للجمهور المصري تحديداً.' ],
            [ 'n' => '03', 'title' => 'التحسينات التقنية والبنيوية',     'desc' => 'نُصلح ما يمنع جوجل من فهرسة موقعك وتصنيفه: السرعة والبنية والروابط الداخلية والمحتوى المكرر.' ],
            [ 'n' => '04', 'title' => 'المحتوى والروابط والصلاحية',      'desc' => 'نُنشئ محتوى يستهدف الكلمات ذات الأولوية ونبني روابط خارجية تُعزز سلطة الموقع — عمل متراكم شهر بعد شهر.' ],
            [ 'n' => '05', 'title' => 'القياس والتطوير المستمر',         'desc' => 'نُتابع الأداء بانتظام ونُعدّل الاستراتيجية بناءً على البيانات — الهدف نمو مستدام لا نتائج مؤقتة.' ],
        ],
        'report_tag'   => 'الشفافية والقياس',
        'report_title' => 'تقارير تُظهر أثر السيو على عملك',
        'fit_tag'      => 'من نخدم في مصر',
        'fit_title'    => 'القطاعات التي نعمل بها في السوق المصري',
        'fit_sectors'  => [
            [ 'label' => 'التجارة الإلكترونية', 'sub' => 'متاجر إلكترونية تخدم العميل المصري',     'sector' => 'ecommerce',  'svg' => '<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>' ],
            [ 'label' => 'الرعاية الصحية',      'sub' => 'عيادات ومستشفيات وخدمات طبية',           'sector' => 'health',     'svg' => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>' ],
            [ 'label' => 'العقارات والإسكان',   'sub' => 'وكالات ومطورون عقاريون في مصر',           'sector' => 'realestate', 'svg' => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>' ],
            [ 'label' => 'التعليم والتدريب',    'sub' => 'مراكز تعليمية ومنصات تعلم إلكتروني',     'sector' => 'education',  'svg' => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>' ],
            [ 'label' => 'الخدمات المهنية',     'sub' => 'شركات خدمية وب2ب في السوق المصري',        'sector' => 'services',   'svg' => '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>' ],
        ],
        'proof_desc'   => 'نتائج موثقة من أعمالنا في قطاعات التجارة الإلكترونية والصحة والعقارات والخدمات.',
        'faqs'         => [
            [ 'question' => 'هل تعملون مع شركات ومتاجر في مصر؟',           'answer' => 'نعم. نخدم عملاء في مصر بشكل منتظم — شركات خدمية ومتاجر إلكترونية ومؤسسات تعليمية. نفهم طبيعة البحث المصري ونستهدف الكلمات التي يستخدمها المستخدم المصري فعلاً.' ],
            [ 'question' => 'هل تستهدفون البحث بالعامية المصرية؟',          'answer' => 'نعم. تحليل الكلمات المفتاحية لدينا يشمل الفصحى والعامية المصرية — عدد كبير من عمليات البحث يتم باللهجة وخاصة في قطاعات كالأغذية والخدمات اليومية والتعليم.' ],
            [ 'question' => 'كيف تختلف استراتيجية السيو للسوق المصري؟',     'answer' => 'تختلف المنافسة قطاعاً بقطاع. التجارة الإلكترونية والصحة منافسة عالية في المدن الكبرى، لكن خارج القاهرة والإسكندرية ثمة فرص سهلة الاختراق نُحدّدها في مرحلة التدقيق.' ],
            [ 'question' => 'هل تعملون عن بُعد مع العملاء المصريين؟',       'answer' => 'نعم تماماً. نعمل بالكامل عن بُعد مع لقاءات متابعة دورية وتقارير شهرية — بغض النظر عن موقع العميل. عملاؤنا في القاهرة وغيرها يحصلون على نفس مستوى الخدمة.' ],
            [ 'question' => 'متى أبدأ برؤية نتائج في السوق المصري؟',        'answer' => 'المؤشرات الأولى تظهر بين الشهر الثاني والثالث. النتائج الواضحة في الترافيك والليدز تتراكم بين الشهر الرابع والسادس. السيو في مصر — كأي سوق آخر — يحتاج صبراً وعمل متراكم.' ],
            [ 'question' => 'كيف تتعاملون مع اللهجة في المحتوى؟',          'answer' => 'تحديد اللغة المناسبة يبدأ بتحليل الجمهور وطبيعة القطاع. المحتوى المعلوماتي يكتب بالفصحى عموماً، وصفحات الخدمات اليومية قد تستفيد من لمسات عامية مدروسة.' ],
            [ 'question' => 'هل السيو أجدى من الإعلانات المدفوعة في مصر؟', 'answer' => 'ليسا بديلَين — لكنهما بمنطق مختلف. الإعلانات تُولّد ترافيكاً فورياً ينتهي بانتهاء الميزانية. السيو يبني ترافيكاً متراكماً مستداماً. الشركات الذكية تُشغّل الاثنين: الإعلانات قصيرة المدى والسيو استثمار طويل.' ],
            [ 'question' => 'ما الذي يشمله التقرير الشهري؟',               'answer' => 'التقرير يشمل: ترتيب الكلمات المستهدفة وتطوره، حركة البحث العضوي، عدد الليدز من البحث، ملخص العمل المُنجز، والخطة المقترحة للشهر التالي — بلغة مباشرة لا تقنية مبهمة.' ],
        ],
        'cta_title'    => 'ابدأ بكسب عملاء من جوجل في مصر',
    ],

    'saudi-arabia' => [
        'area_served_en'   => 'Saudi Arabia',
        'area_served_wiki' => 'https://en.wikipedia.org/wiki/Saudi_Arabia',
        'name'         => 'السعودية',
        'hero_tag'     => 'تحسين محركات البحث في السعودية',
        'hero_title'   => 'شركة <em>تحسين محركات البحث</em> في السعودية',
        'hero_desc'    => 'السوق السعودي يجمع بين قوة شرائية مرتفعة وجمهور متصل رقمياً بعمق — كل ليد من البحث العضوي هنا يستحق الاستثمار الحقيقي.',
        'intro_tag'    => 'السوق السعودي',
        'intro_title'  => 'لماذا السيو في السعودية يستحق الاستثمار؟',
        'intro_desc'   => 'رؤية 2030 تُسرّع التحول الرقمي في المملكة — الشركات التي تبني حضورها العضوي اليوم ستجني ثمار ذلك لسنوات. المنافسة في السيو تنمو، لكن الفرص للشركات التي تتصرف بذكاء لا تزال قائمة.',
        'points'       => [
            [ 'label' => 'أعلى إنفاق رقمي في المنطقة',    'desc' => 'المستخدم السعودي من أكثر مستخدمي الإنترنت إنفاقاً — عائد الليد من البحث أعلى من كثير من الأسواق' ],
            [ 'label' => 'رؤية 2030 والتحول الرقمي',      'desc' => 'قطاعات جديدة تتشكل رقمياً — من يتصدر نتائج البحث اليوم يحتل مكانة يصعب إزاحتها' ],
            [ 'label' => 'منافسة متصاعدة تستدعي التبكير', 'desc' => 'الوكالات السعودية تنمو — التحرك المبكر يمنحك ميزة متراكمة يصعب على المنافسين تجاوزها' ],
            [ 'label' => 'بيئة البحث العربية الأولى',      'desc' => 'البحث بالعربية هو الأساس — مع حضور الإنجليزية للقطاعات الدولية والمستثمرين الأجانب' ],
        ],
        'why_tag'      => 'استراتيجية محلية',
        'why_title'    => 'كيف نخدم عملاءنا في السعودية؟',
        'why_items'    => [
            [ 'label' => 'كلمات مفتاحية بنية شراء عالية',  'desc' => 'نستهدف العبارات التي يبحثها العميل السعودي حين يكون جاهزاً للشراء — لا مجرد الاستفسار' ],
            [ 'label' => 'تحليل منافسي الرياض وجدة',       'desc' => 'المنافسة تختلف بين المناطق — نُحلّل محلياً ونضع استراتيجية دقيقة بحسب المدينة' ],
            [ 'label' => 'سيو متاجر سلة وزد',               'desc' => 'خبرة واسعة في المنصتين الأكثر انتشاراً في السوق السعودي' ],
            [ 'label' => 'تقارير ترتبط بمؤشرات عملك',     'desc' => 'ليدز، مبيعات، وCAC — لا أرقام ترافيك معلقة في الهواء' ],
            [ 'label' => 'سيو قطاعات رؤية 2030',            'desc' => 'التجزئة والسياحة والترفيه والتقنية — قطاعات في نمو تستحق حضوراً عضوياً قوياً' ],
            [ 'label' => 'استراتيجية على مستوى المدينة',    'desc' => 'الرياض وجدة والدمام لها أنماط بحث مختلفة — نُخصّص التوجيه على مستوى المنطقة' ],
        ],
        'scope_tag'    => 'نطاق الخدمة',
        'scope_title'  => 'ماذا يشمل السيو في السعودية؟',
        'scope_items'  => [
            [ 'n' => '01', 'label' => 'بحث الكلمات التجارية العالية القيمة', 'desc' => 'نُحلّل الكلمات ذات النية الشرائية التي يستخدمها المستخدم السعودي في قطاعك تحديداً' ],
            [ 'n' => '02', 'label' => 'التدقيق التقني للموقع',               'desc' => 'فحص كامل للبنية والسرعة والفهرسة — ما يُبطئ موقعك يُبطئ ظهوره في جوجل' ],
            [ 'n' => '03', 'label' => 'تحسين صفحات الخدمات والمنتجات',      'desc' => 'تعديل الصفحات الموجودة لتتوافق مع نية البحث السعودية وتُحوّل الزائر لعميل' ],
            [ 'n' => '04', 'label' => 'محتوى موجّه للسوق السعودي',           'desc' => 'كتابة صفحات ومقالات تُخاطب الجمهور السعودي بمرجعياته — لا بأسلوب عام مجرد' ],
            [ 'n' => '05', 'label' => 'بناء الروابط الخارجية',               'desc' => 'روابط من مصادر محلية وعالمية موثوقة تبني سلطة الموقع وتُرسّخ مكانته التنافسية' ],
            [ 'n' => '06', 'label' => 'سيو سلة وزد والمتاجر السعودية',      'desc' => 'تحسين صفحات المنتجات والفئات في المنصتين الأبرز بالتجارة الإلكترونية السعودية' ],
            [ 'n' => '07', 'label' => 'التحسين المستمر والتكيّف',            'desc' => 'خوارزميات جوجل تتغير — نُتابع التحديثات وندير الاستراتيجية بمرونة تضمن الاستدامة' ],
            [ 'n' => '08', 'label' => 'تقارير الأداء والعائد',               'desc' => 'تقرير شهري يُظهر كيف تتحول حركة البحث إلى ليدز ومبيعات حقيقية' ],
        ],
        'process_tag'   => 'منهجية العمل',
        'process_title' => 'كيف نعمل في السوق السعودي؟',
        'process_steps' => [
            [ 'n' => '01', 'title' => 'التدقيق والتحليل التفصيلي',         'desc' => 'نبدأ بفهم وضعك الحالي في السوق السعودي — الكلمات التي تظهر بها، ما يمنعك من التقدم، وأين يتفوق منافسوك في الرياض وجدة وغيرها.' ],
            [ 'n' => '02', 'title' => 'خطة استراتيجية بحسب السوق السعودي', 'desc' => 'استراتيجية مُعدّة خصيصاً: الكلمات ذات الأولوية، الصفحات المستهدفة، خارطة المحتوى، وخطة الروابط — مُوجّهة لطبيعة البحث في المملكة.' ],
            [ 'n' => '03', 'title' => 'التحسينات التقنية وعوائق الظهور',   'desc' => 'نُعالج العوائق التقنية التي تمنع جوجل من فهم موقعك وتصنيفه: البنية والسرعة والفهرسة والمحتوى الإشكالي.' ],
            [ 'n' => '04', 'title' => 'المحتوى والروابط شهراً بعد شهر',    'desc' => 'محتوى مُنشأ باستمرار لاستهداف الكلمات الجديدة، وروابط خارجية مدروسة — العمل المتراكم هو ما يُفرّق بين نتائج مؤقتة وحضور دائم.' ],
            [ 'n' => '05', 'title' => 'القياس والتطوير والاستدامة',          'desc' => 'نُراجع الأداء شهرياً ونُحدّث الاستراتيجية بحسب تحديثات جوجل والمتغيرات التنافسية — الهدف تصدّر مستدام لا قفزات مؤقتة.' ],
        ],
        'report_tag'   => 'الشفافية والقياس',
        'report_title' => 'تقارير تُترجم السيو إلى أرقام عمل',
        'fit_tag'      => 'من نخدم في السعودية',
        'fit_title'    => 'القطاعات التي نعمل بها في السوق السعودي',
        'fit_sectors'  => [
            [ 'label' => 'التجارة الإلكترونية', 'sub' => 'متاجر سلة وزد وبيع منتجات في السوق السعودي', 'sector' => 'ecommerce',  'svg' => '<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>' ],
            [ 'label' => 'العقارات',              'sub' => 'شركات وساطة عقارية ومطورون في المملكة',      'sector' => 'realestate', 'svg' => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>' ],
            [ 'label' => 'الرعاية الصحية',       'sub' => 'عيادات ومستشفيات وخدمات طبية خاصة',          'sector' => 'health',     'svg' => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>' ],
            [ 'label' => 'التعليم والتدريب',      'sub' => 'جامعات ومراكز تدريب وتعليم إلكتروني',        'sector' => 'education',  'svg' => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>' ],
            [ 'label' => 'الخدمات المهنية',       'sub' => 'شركات خدمية ومستشارون وب2ب',                 'sector' => 'services',   'svg' => '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>' ],
        ],
        'proof_desc'   => 'نتائج موثقة من أعمالنا في قطاعات التجارة الإلكترونية والصحة والعقارات والخدمات.',
        'faqs'         => [
            [ 'question' => 'هل أنتم شركة سيو تخدم السعودية؟',               'answer' => 'نعم. سيو هاوس تخدم السوق السعودي بشكل مستمر — عملاء في الرياض وجدة والدمام وغيرها. نفهم طبيعة البحث السعودي والكلمات التي يستخدمها العميل في قطاعك تحديداً.' ],
            [ 'question' => 'كيف يختلف السيو في السعودية عن باقي الدول؟',     'answer' => 'المنافسة في السعودية أعلى في قطاعات كالعقارات والتجزئة والصحة — لكن قيمة الليد أعلى أيضاً. نُحلّل المنافسين محلياً ونبني استراتيجية تأخذ في الحسبان كثافة الكلمات في كل منطقة.' ],
            [ 'question' => 'هل تعملون مع متاجر سلة وزد في السعودية؟',        'answer' => 'نعم. لدينا خبرة تفصيلية في سيو متاجر سلة وزد — المنصتان الأكثر انتشاراً في السوق السعودي — وعملنا مع متاجر في قطاعات الأزياء والإلكترونيات والمستلزمات المنزلية.' ],
            [ 'question' => 'ما العائد المتوقع من السيو في السعودية؟',          'answer' => 'يختلف بحسب القطاع والمنافسة. بشكل عام، العملاء الذين يستمرون 6 أشهر أو أكثر يرون نمواً تدريجياً في الليدز العضوية مع انخفاض في الاعتماد على الإعلانات المدفوعة.' ],
            [ 'question' => 'هل تحتاج عقداً طويلاً؟',                         'answer' => 'لا. نعمل بدون عقود ملزمة — نُثبت قيمتنا شهراً بعد شهر وأنت من يُقرّر الاستمرار. الالتزام الحقيقي يأتي من النتائج، لا من الأوراق.' ],
            [ 'question' => 'كم يستغرق السيو قبل ظهور نتائج في السعودية؟',    'answer' => 'المؤشرات الأولى تظهر بين الشهر الثاني والثالث — تحسن في ترتيب بعض الكلمات وارتفاع طفيف في الترافيك. النتائج الملموسة في الليدز تتراكم من الشهر الرابع فصاعداً.' ],
            [ 'question' => 'هل السيو مناسب للشركات الصغيرة في السعودية؟',    'answer' => 'نعم — بل هو أكثر ما يُفيد الشركات الصغيرة والمتوسطة. بدلاً من المنافسة في الإعلانات المدفوعة مع ميزانيات ضخمة، تبني حضوراً عضوياً يُعطيك ترافيكاً متراكماً بتكلفة أقل على المدى البعيد.' ],
            [ 'question' => 'هل تعملون بالعربية والإنجليزية معاً في السعودية؟', 'answer' => 'نعم. بعض القطاعات السعودية لها جمهور مختلط — استثمارات ومقاولات وضيافة يبحث فيها الأجانب بالإنجليزية. إذا كان جمهورك يمتد للغتين نبني الاستراتيجية لكليهما بمنطق منفصل.' ],
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
            [ 'label' => 'سيو ثنائي اللغة عربي إنجليزي',     'desc' => 'بناء بنية سيو تعمل للعربي والإنجليزي بكفاءة — لا نتائج لغة على حساب الأخرى' ],
            [ 'label' => 'الظهور المحلي في دبي وأبوظبي',      'desc' => 'تحسين الحضور الرقمي للشركات التي تستهدف عملاء محليين في المدينتين' ],
        ],
        'scope_tag'    => 'نطاق الخدمة',
        'scope_title'  => 'ماذا يشمل السيو في الإمارات؟',
        'scope_items'  => [
            [ 'n' => '01', 'label' => 'بحث كلمات عربي وإنجليزي',         'desc' => 'تحليل مزدوج للكلمات المفتاحية بلغتين — يُغطي شرائح جمهورك كاملاً في الإمارات' ],
            [ 'n' => '02', 'label' => 'التدقيق التقني متعدد اللغات',     'desc' => 'فحص شامل للبنية والسرعة والفهرسة مع انتباه خاص لمتطلبات hreflang والروابط المتعددة' ],
            [ 'n' => '03', 'label' => 'تحسين الصفحات الحالية',           'desc' => 'مراجعة وتحديث الصفحات الموجودة لتستهدف الكلمات الصحيحة وتُحوّل الزائر لعميل' ],
            [ 'n' => '04', 'label' => 'محتوى لجمهور الإمارات',           'desc' => 'إنشاء محتوى يُعالج أسئلة الجمهور الإماراتي ويُراعي تنوع الثقافات في هذا السوق' ],
            [ 'n' => '05', 'label' => 'بناء الروابط إقليمياً ودولياً',   'desc' => 'روابط من مصادر إقليمية ودولية موثوقة تُعزز موقعك أمام المنافسة الدولية' ],
            [ 'n' => '06', 'label' => 'سيو العقارات والسياحة',           'desc' => 'تحسين متخصص لأكثر القطاعات كثافةً في البحث وأعلاها قيمةً للعميل في الإمارات' ],
            [ 'n' => '07', 'label' => 'التحسين المستمر والتكيف',         'desc' => 'رصد تغيرات المنافسة الدولية وتعديل الاستراتيجية بانتظام للحفاظ على موقعك' ],
            [ 'n' => '08', 'label' => 'تقارير ثنائية اللغة',             'desc' => 'تقارير تُظهر الأداء بالعربية والإنجليزية وترتبط بمؤشراتك التجارية الفعلية' ],
        ],
        'process_tag'   => 'منهجية العمل',
        'process_title' => 'كيف نعمل في السوق الإماراتي؟',
        'process_steps' => [
            [ 'n' => '01', 'title' => 'فهم الوضع الحالي والجمهور',             'desc' => 'نُحلّل موقعك وجمهورك في الإمارات: هل هو عربي أم دولي أم مزيج؟ هذا التحليل يُحدد لغة الاستراتيجية وأولويات الكلمات من البداية.' ],
            [ 'n' => '02', 'title' => 'استراتيجية سيو ثنائية اللغة',           'desc' => 'خطة شاملة بكلمات العربية والإنجليزية والصفحات المستهدفة وجدول المحتوى — مع مراعاة طبيعة المنافسة الدولية في دبي وأبوظبي.' ],
            [ 'n' => '03', 'title' => 'التحسينات التقنية والبنيوية',            'desc' => 'معالجة العوائق التقنية وإصلاح بنية الموقع ونظام اللغات والروابط — أساس يُمكّن كل ما يليه من عمل.' ],
            [ 'n' => '04', 'title' => 'المحتوى والروابط مع التركيز على التميز', 'desc' => 'في بيئة المنافسة الدولية، الجودة تتفوق على الكمية. محتوى عميق وروابط مدروسة يبنيان سلطة الموقع أمام المنافسين الكبار.' ],
            [ 'n' => '05', 'title' => 'القياس والتكيف مع السوق',                'desc' => 'السوق الإماراتي يتحرك بسرعة — نُراجع الأداء شهرياً ونُعدّل الاستراتيجية لمواكبة تغيرات الجمهور والمنافسة الدولية.' ],
        ],
        'report_tag'   => 'الشفافية والقياس',
        'report_title' => 'تقارير واضحة في سوق متعدد اللغات',
        'fit_tag'      => 'من نخدم في الإمارات',
        'fit_title'    => 'القطاعات التي نعمل بها في السوق الإماراتي',
        'fit_sectors'  => [
            [ 'label' => 'العقارات والضيافة',   'sub' => 'تطوير عقاري وفنادق وشقق في دبي وأبوظبي', 'sector' => 'realestate', 'svg' => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>' ],
            [ 'label' => 'التجارة الإلكترونية', 'sub' => 'متاجر تخدم جمهوراً إماراتياً وخليجياً',    'sector' => 'ecommerce',  'svg' => '<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>' ],
            [ 'label' => 'الرعاية الصحية',      'sub' => 'عيادات ومراكز طبية خاصة في الإمارات',      'sector' => 'health',     'svg' => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>' ],
            [ 'label' => 'التعليم والتدريب',    'sub' => 'مؤسسات تعليمية ومنصات تعليم إلكتروني',     'sector' => 'education',  'svg' => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>' ],
            [ 'label' => 'الخدمات المهنية',     'sub' => 'استشارات ومحاسبة وخدمات ب2ب في الإمارات',  'sector' => 'services',   'svg' => '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>' ],
        ],
        'proof_desc'   => 'نتائج موثقة من أعمالنا في قطاعات العقارات والتجارة الإلكترونية والصحة والخدمات.',
        'faqs'         => [
            [ 'question' => 'هل تعملون مع شركات في دبي وأبوظبي؟',           'answer' => 'نعم. نخدم عملاء في الإمارات بانتظام — في دبي وأبوظبي والشارقة. نفهم التنوع السوقي في الإمارات والفرق بين استهداف جمهور عربي وجمهور دولي.' ],
            [ 'question' => 'هل تُحسّنون المواقع بالعربية والإنجليزية؟',    'answer' => 'نعم — لكننا نبدأ دائماً بتحليل الجمهور المستهدف. هل هو عربي أم دولي أم مزيج؟ استراتيجية اللغة مبنية على هذا التحليل وليس على افتراض مسبق.' ],
            [ 'question' => 'ما القطاعات التي تعملون بها في الإمارات؟',      'answer' => 'لدينا خبرة في العقارات والسياحة والضيافة والتجارة الإلكترونية والتعليم والتقنية في الإمارات — وهي القطاعات الأكثر نشاطاً في نتائج جوجل في هذا السوق.' ],
            [ 'question' => 'كيف تتعاملون مع المنافسة الدولية؟',             'answer' => 'المنافسة الدولية تتطلب سيو أعمق — تقنياً ومحتوى وروابط. نُحلّل المنافسين الدوليين في قطاعك ونضع استراتيجية تُمكّنك من المنافسة الفعلية لا مجرد الظهور في الصفحة الثالثة.' ],
            [ 'question' => 'هل أحتاج لعقد طويل الأمد؟',                    'answer' => 'لا. نعمل بدون عقود ملزمة — كل شهر تُقرر بناءً على النتائج. الهدف أن تكون النتائج سبب استمرارك، لا الورقة المُوقَّعة.' ],
            [ 'question' => 'كيف تعملون مع المواقع ثنائية اللغة؟',          'answer' => 'نُبني استراتيجية منفصلة لكل لغة — كلمات مفتاحية ومحتوى وروابط لكل نسخة — مع ضمان أن البنية التقنية لـhreflang صحيحة حتى لا تتأكل الصفحتان من بعضهما في جوجل.' ],
            [ 'question' => 'متى نرى نتائج ملموسة في السوق الإماراتي؟',      'answer' => 'في سوق تنافسي كالإمارات، المؤشرات الأولى تظهر بين الشهر الثاني والثالث، والتأثير الحقيقي على الليدز يتراكم من الشهر الرابع فصاعداً — كلما كانت الأساسيات التقنية سليمة تسارعت النتائج.' ],
            [ 'question' => 'هل يمكنني تتبع عائد الاستثمار من السيو؟',       'answer' => 'نعم. نربط السيو بـ Google Analytics وSearch Console ونُعدّ تتبع التحويلات — ليدز ومبيعات وCAC — حتى يكون القرار مبنياً على بيانات حقيقية لا تقديرات.' ],
        ],
        'cta_title'    => 'ابدأ بكسب عملاء من جوجل في الإمارات',
    ],
];

// ─ Market detection ──────────────────────────────────────────────────────────
// ACF values use underscores; $country_config keys use hyphens for saudi-arabia.
$acf_to_config = [
    'egypt'        => 'egypt',
    'saudi_arabia' => 'saudi-arabia',
    'uae'          => 'uae',
];

$acf_market = sh_field( 'seo_market' );

if ( $acf_market && isset( $acf_to_config[ $acf_market ] ) ) {
    // Primary path: saved ACF value drives content — slug-independent.
    $c = $country_config[ $acf_to_config[ $acf_market ] ];
} else {
    // Backward compat: ACF not yet configured — try the page slug.
    $slug = get_post_field( 'post_name', get_the_ID() );
    $c    = $country_config[ $slug ] ?? null;
}

get_header();

// No market resolved — never silently default to the wrong country.
if ( null === $c ) {
    if ( current_user_can( 'manage_options' ) ) {
        echo '<div style="margin:40px auto;max-width:760px;padding:24px 28px;background:#fff3cd;border:2px solid #e6a817;border-radius:8px;font-family:sans-serif;direction:ltr;line-height:1.6">';
        echo '<strong style="display:block;margin-bottom:8px">&#9888;&#xFE0F; Admin Notice — SEO Market not configured</strong>';
        echo 'This page uses the <em>Service &mdash; SEO Country Page</em> template but no <strong>SEO Market</strong> has been selected.<br>';
        echo 'Fix: WP Admin &rarr; Edit this page &rarr; Page Attributes sidebar &rarr; <strong>SEO Market</strong> &rarr; choose Egypt, Saudi Arabia, or UAE &rarr; Update.<br>';
        echo '<small style="color:#666;margin-top:8px;display:block">This notice is only visible to administrators. Visitors see a blank page until a market is selected.</small>';
        echo '</div>';
    }
    get_footer();
    exit;
}

// Allow ACF overrides on specific fields
$hero_tag   = sh_field( 'service_hero_tag' )  ?: $c['hero_tag'];
$hero_title = sh_field( 'service_hero_title' ) ?: $c['hero_title'];
$hero_desc  = sh_field( 'service_hero_desc' )  ?: $c['hero_desc'];
$faqs       = sh_field( 'service_faqs' );
$faq_data   = is_array( $faqs ) && count( array_filter( $faqs, fn( $r ) => ! empty( $r['question'] ) ) ) ? $faqs : $c['faqs'];

// Services and SEO parent URLs (used in breadcrumb and back-links)
$services_url = sh_page_url( 'services' );
$seo_url      = sh_page_url( 'services/seo' );
$results_url  = sh_page_url( 'results' );

// Animation delay classes for staggered card reveals
$delays = [ '', ' d1', ' d2', ' d3', ' d4', ' d5' ];

// SVG path data for the 8 scope service cards (shared across all three countries)
$scope_svgs = [
    '<circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>',
    '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
    '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>',
    '<path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>',
    '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>',
    '<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>',
    '<polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.89"/>',
    '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>',
];

// Reporting checklist items — consistent across all markets
$report_checklist = [
    'تقرير شهري مُرسَل قبل اليوم الخامس من كل شهر',
    'ترتيب الكلمات المستهدفة وتطوره مقارنةً بالشهر السابق',
    'حركة البحث العضوي وتطورها الشهري',
    'عدد الليدز والتحويلات القادمة من البحث',
    'ملخص العمل المُنجز وخطة الشهر التالي',
    'تواصل مباشر مع مدير الحساب عند الحاجة',
];

// Trust card items — consistent across all markets
$trust_items = [
    'بدون عقود ملزمة — تستمر بناءً على النتائج',
    'فريق تقني متخصص وليس مندوب مبيعات',
    'شفافية كاملة في الاستراتيجية والتقارير',
    'خبرة في الأسواق العربية والمتطلبات المحلية',
    'التزام بالجودة لا بضخامة الأرقام',
];
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

<!-- Full service scope -->
<section class="sec sec-off">
  <div class="wrap">
    <div class="sh c sr"><span class="tag"><?php echo esc_html( $c['scope_tag'] ); ?></span><h2 class="h2"><?php echo esc_html( $c['scope_title'] ); ?></h2></div>
    <div class="wwd-grid sr d1" style="margin-top:36px">
      <?php foreach ( $c['scope_items'] as $idx => $si ) : ?>
      <div class="wwd-card sr<?php echo esc_attr( $delays[ $idx % 6 ] ); ?>">
        <div class="wwd-n" aria-hidden="true"><?php echo esc_html( $si['n'] ); ?></div>
        <div class="wwd-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="21" height="21"><?php echo $scope_svgs[ $idx ]; ?></svg></div>
        <h3><?php echo esc_html( $si['label'] ); ?></h3>
        <p><?php echo esc_html( $si['desc'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Process steps -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="sh c sr"><span class="tag"><?php echo esc_html( $c['process_tag'] ); ?></span><h2 class="h2"><?php echo esc_html( $c['process_title'] ); ?></h2></div>
    <div class="steps-list sr d1" style="margin-top:32px;max-width:800px;margin-inline:auto">
      <?php foreach ( $c['process_steps'] as $ps ) : ?>
      <div class="step-item">
        <div class="step-num"><?php echo esc_html( $ps['n'] ); ?></div>
        <div class="step-body">
          <h3><?php echo esc_html( $ps['title'] ); ?></h3>
          <p><?php echo esc_html( $ps['desc'] ); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Reporting & trust -->
<section class="sec sec-surface">
  <div class="wrap">
    <div class="why-com-grid">
      <div class="sr">
        <div class="sh sr">
          <span class="tag"><?php echo esc_html( $c['report_tag'] ); ?></span>
          <h2 class="h2"><?php echo esc_html( $c['report_title'] ); ?></h2>
        </div>
        <div class="chklist sr d1" style="margin-top:20px">
          <?php foreach ( $report_checklist as $ri ) : ?>
          <div class="chk-item">
            <div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div>
            <span><?php echo esc_html( $ri ); ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="sr d2">
        <div style="background:var(--navy-2);border-radius:var(--r4);padding:32px 28px;position:relative;overflow:hidden">
          <div style="position:absolute;inset-inline-end:-50px;top:-50px;width:220px;height:220px;border-radius:50%;background:radial-gradient(circle,rgba(30,46,245,.22),transparent 70%)"></div>
          <div style="font-size:9.5px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.22);margin-bottom:20px;position:relative;z-index:1">لماذا يختار عملاؤنا الاستمرار</div>
          <div style="position:relative;z-index:1;display:flex;flex-direction:column;gap:11px">
            <?php foreach ( $trust_items as $ti ) : ?>
            <div style="display:flex;align-items:center;gap:10px;padding:12px 14px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:var(--r2)">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#7b90ff" stroke-width="2.5" style="flex-shrink:0"><polyline points="20 6 9 17 4 12"/></svg>
              <span style="font-size:13.5px;font-weight:600;color:rgba(255,255,255,.82)"><?php echo esc_html( $ti ); ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Suitable sectors + proof link -->
<section class="sec sec-off">
  <div class="wrap">
    <div class="sh c sr"><span class="tag"><?php echo esc_html( $c['fit_tag'] ); ?></span><h2 class="h2"><?php echo esc_html( $c['fit_title'] ); ?></h2></div>
    <div class="ind-grid sr d1" style="margin-top:32px">
      <?php foreach ( $c['fit_sectors'] as $fs ) : ?>
      <a href="<?php echo esc_url( add_query_arg( 'sector', $fs['sector'], $results_url ) ); ?>" class="ind-card">
        <div class="ind-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?php echo $fs['svg']; ?></svg></div>
        <div class="ind-card-body"><h3><?php echo esc_html( $fs['label'] ); ?></h3><p><?php echo esc_html( $fs['sub'] ); ?></p></div>
      </a>
      <?php endforeach; ?>
    </div>
    <div style="margin-top:40px;padding:28px 32px;background:#fff;border:1px solid var(--line);border-radius:var(--r3);display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap">
      <div>
        <div style="font-size:11.5px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px">نتائج من أعمالنا</div>
        <p style="font-size:14.5px;font-weight:700;color:var(--ink);margin:0"><?php echo esc_html( $c['proof_desc'] ); ?></p>
      </div>
      <a href="<?php echo esc_url( $results_url ); ?>" class="btn btn-o">اطلع على الحالات الدراسية <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
    </div>
  </div>
</section>

<!-- FAQ -->
<section id="country-faq" class="sec sec-white">
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
        <div style="margin-top:28px;padding:20px 22px;background:var(--off);border:1px solid var(--line);border-radius:var(--r2);display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap">
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
