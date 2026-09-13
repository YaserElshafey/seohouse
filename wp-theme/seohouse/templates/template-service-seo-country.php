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
 * back to slug-based detection (egypt / saudi-arabia / ksa / uae) so existing
 * pages continue to render before the field is configured.
 * Both 'ksa' and 'saudi-arabia' slugs resolve to the Saudi Arabia config.
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

    // ── Egypt ───────────────────────────────────────────────────────────────
    'egypt' => [
        'area_served_en'   => 'Egypt',
        'area_served_wiki' => 'https://en.wikipedia.org/wiki/Egypt',
        'name'         => 'مصر',
        'hero_tag'     => 'تحسين محركات البحث في مصر',
        'hero_title'   => 'شركة سيو في مصر تربط الظهور بزيارات وفرص بيع حقيقية',
        'hero_desc'    => 'كثير من المواقع تحصل على زيارات لكنها لا تتحول إلى فرص بيع. المشكلة ليست حجم الزيارات في الغالب — بل أي الكلمات تستهدفها وإلى أي صفحة تُرسل الزائر. نبدأ بتحليل مسار البحث قبل تحديد ما يحتاجه موقعك من تحسين.',

        // Traffic quality argument
        'quality_tag'    => 'جودة الزيارات',
        'quality_title'  => 'الظهور في جوجل لا يكفي — المهم من أين يأتي الزائر وإلى أين يذهب',
        'quality_desc'   => 'الكلمة المعلوماتية تجذب قارئًا يبحث عن إجابة، بينما تقرّب الكلمة الشرائية المستخدم من طلب الخدمة أو إتمام الشراء. ونراجع مسار الزائر لمعرفة أين يتوقف قبل الإجراء المطلوب.',
        'quality_points' => [
            [ 'label' => 'كلمات الشراء أولاً',          'desc' => 'الصفحات التي تستهدف نية الشراء هي الأولى بالتحسين — تحليل الكلمات يُحدد أين توجد هذه الفرصة في قطاعك تحديداً' ],
            [ 'label' => 'مطابقة الصفحة للنية',          'desc' => 'زائر يبحث عن "سعر X" ويصل لصفحة وصفية عامة لن يكمل. مطابقة نوع الصفحة لنية البحث تُقلل الخروج وترفع احتمال التحويل' ],
            [ 'label' => 'بحث بالفصحى والعامية معاً',   'desc' => 'بحث المستخدم المصري يتوزع بين الفصحى والعامية — بيانات الكلمات الفعلية تُحدد التوزيع الصحيح في كل قطاع' ],
            [ 'label' => 'المحتوى مدخل للمبيعات',        'desc' => 'مقالة معلوماتية جيدة تُوجّه الزائر إلى صفحة المنتج أو الخدمة — بشرط وجود ربط داخلي واضح يخدم رحلة الشراء' ],
        ],

        // Local + ecommerce execution
        'exec_tag'   => 'التنفيذ المحلي',
        'exec_title' => 'السيو المحلي وسيو المتاجر في السوق المصري',
        'exec_items' => [
            [ 'label' => 'سيو المتاجر الإلكترونية',          'desc' => 'صفحات الفئات والمنتجات في متاجر WooCommerce وMagento وغيرها لها متطلبات تقنية خاصة — بنية الـURL والفهرسة وصفحات التصفية ومخطط Schema للمنتج' ],
            [ 'label' => 'السيو المحلي للأنشطة الجغرافية',   'desc' => 'الأنشطة التي تخدم القاهرة أو الجيزة أو الإسكندرية وتملك ملف Google Business موثقاً قد تستفيد من تحسينات المحلي — التدقيق يُحدد ما يناسب نشاطك' ],
            [ 'label' => 'استهداف العامية المصرية بالبيانات', 'desc' => 'قطاعات مثل الطعام والتجزئة والخدمات المنزلية يكثر فيها البحث بالعامية — التحليل يُحدد التوزيع الفعلي ويبني الاستراتيجية على البيانات' ],
        ],

        // Phased plan
        'process_tag'   => 'مراحل التنفيذ',
        'process_title' => 'خطة مرحلية مبنية على وضع موقعك',
        'process_steps' => [
            [ 'n' => '01', 'title' => 'تدقيق الزيارات والتحويلات',          'desc' => 'نُحلّل أداء موقعك الحالي: من أين تأتي الزيارات، أي الصفحات تُحوّل، وأين تنقطع الرحلة. هذا التشخيص يُحدد أولويات العمل.' ],
            [ 'n' => '02', 'title' => 'خارطة الكلمات ومطابقة الصفحات',     'desc' => 'نُبني خارطة تربط كل كلمة هدف بالصفحة الأنسب — مع تحديد الفجوات التي تحتاج محتوى جديداً أو تعديلاً على صفحات موجودة.' ],
            [ 'n' => '03', 'title' => 'التحسينات التقنية والداخلية',         'desc' => 'معالجة المشكلات التقنية وتحديث الصفحات الأساسية وتحسين الربط الداخلي بما يدعم رحلة الزائر من البحث إلى الإجراء.' ],
            [ 'n' => '04', 'title' => 'محتوى يُغطي نية الشراء والاستفسار',  'desc' => 'إنشاء صفحات خدمات ومقالات مُحسّنة لكلمات الاستفسار والمقارنة مع روابط داخلية واضحة تُوجّه نحو التحويل.' ],
            [ 'n' => '05', 'title' => 'قياس الأداء وتطوير الاستراتيجية',    'desc' => 'متابعة بيانات الظهور والنقرات والتحويلات شهرياً وتعديل الاستراتيجية بحسب ما تكشفه البيانات.' ],
        ],
        'process_note'  => 'الأشهر الثلاثة الأولى: تدقيق وخارطة كلمات وتحسينات تقنية وتحديث صفحات أساسية. الأشهر اللاحقة: توسيع المحتوى وروابط خارجية وقياس التحويلات عند توافر إعداد تتبع موثوق ومختبر.',

        // Sectors
        'fit_tag'     => 'أنواع المواقع والقطاعات',
        'fit_title'   => 'قطاعات يمكن دعمها بخدمات السيو في مصر',
        'fit_sectors' => [
            [ 'label' => 'التجارة الإلكترونية', 'sub' => 'متاجر إلكترونية تخدم العميل المصري',      'sector' => 'ecommerce',  'url' => sh_safe_url( 'sectors/ecommerce' ),  'svg' => '<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>' ],
            [ 'label' => 'الرعاية الصحية',      'sub' => 'عيادات ومستشفيات وخدمات طبية',            'sector' => 'health',     'url' => sh_safe_url( 'sectors/health' ),     'svg' => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>' ],
            [ 'label' => 'العقارات والإسكان',   'sub' => 'وكالات ومطورون عقاريون في مصر',            'sector' => 'realestate', 'url' => sh_safe_url( 'sectors/realestate' ), 'svg' => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>' ],
            [ 'label' => 'التعليم والتدريب',    'sub' => 'مراكز تعليمية ومنصات تعلم إلكتروني',      'sector' => 'education',  'url' => sh_safe_url( 'sectors/education' ),  'svg' => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>' ],
            [ 'label' => 'الخدمات المهنية',     'sub' => 'شركات خدمية وB2B في السوق المصري',         'sector' => 'services',   'url' => '',                                  'svg' => '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>' ],
        ],

        'faqs' => [
            [ 'question' => 'ما الفرق بين الظهور في جوجل والزيارات التي تُحوّل؟',  'answer' => 'الظهور في نتائج البحث خطوة أولى غير كافية وحدها. الزيارة تتحول عندما تتطابق الصفحة مع ما كان يبحث عنه الزائر فعلاً. تحليل الكلمات ونية البحث يُحدد أي الصفحات تحتاج تعديلاً لتحقيق هذه المطابقة.' ],
            [ 'question' => 'هل تعملون مع المتاجر الإلكترونية في مصر؟',             'answer' => 'نعم. المتاجر التي تستهدف السوق المصري لها متطلبات سيو خاصة — بنية صفحات الفئات والمنتجات والتصفية والسرعة. نطاق العمل يُحدد بعد تدقيق المنصة والكلمات والمنافسة.' ],
            [ 'question' => 'هل تستهدفون الكلمات العامية المصرية؟',                 'answer' => 'نعم — لكن التوزيع بين الفصحى والعامية يتفاوت بحسب القطاع والجمهور. بيانات الكلمات الفعلية تُحدد ما يستخدمه جمهورك في قطاعك قبل أي قرار.' ],
            [ 'question' => 'كم يستغرق ظهور نتائج السيو في مصر؟',                  'answer' => 'بعض التحسينات التقنية ومؤشرات الفهرسة تظهر مبكراً. تقدم الترتيب والزيارات والتحويلات يختلف بحسب وضع الموقع والمنافسة وسرعة التنفيذ. السيو يُقيَّم عادةً على أفق 3-6 أشهر.' ],
            [ 'question' => 'هل يمكن الجمع بين السيو والإعلانات في مصر؟',           'answer' => 'نعم — وكثيراً ما يُكمل كل منهما الآخر. الإعلانات تُولّد حركة فورية بينما السيو يبني حضوراً عضوياً. الاختيار بينهما أو الجمع يعتمد على الهدف والميزانية ووضع المنافسة.' ],
            [ 'question' => 'ما تقارير الأداء التي أتلقاها؟',                       'answer' => 'لوحة Looker Studio حية متصلة بـSearch Console وGA4، تشمل: ترتيب الكلمات المستهدفة وتطوره، بيانات الظهور والنقرات والصفحات، ملخص العمل المُنجز وخطة الشهر التالي. يضاف تتبع التحويلات عند توافر إعداد موثوق ومختبر.' ],
        ],
        'cta_title' => 'ابدأ بتحليل مسار البحث إلى البيع في موقعك',
    ],

    // ── Saudi Arabia ─────────────────────────────────────────────────────────
    'saudi-arabia' => [
        'area_served_en'   => 'Saudi Arabia',
        'area_served_wiki' => 'https://en.wikipedia.org/wiki/Saudi_Arabia',
        'name'         => 'السعودية',
        'hero_tag'     => 'تحسين محركات البحث في السعودية',
        'hero_title'   => 'شركة سيو في السعودية تبني ظهورًا يناسب طريقة بحث العميل السعودي',
        'hero_desc'    => 'نبدأ بخريطة بحث عربية، ونضيف الكلمات والصفحات الإنجليزية فقط عندما تثبت بيانات القطاع أن لها طلبًا حقيقيًا. ثم نربط كل مرحلة، من الاستفسار والمقارنة إلى الطلب أو الشراء، بالصفحة المناسبة.',

        // Saudi search decisions
        'search_tag'   => 'طريقة بحث العميل السعودي',
        'search_title' => 'نبدأ من لغة البحث ونصل إلى الصفحة التي تقود للتحويل',
        'search_desc'  => 'نراجع لغة البحث ونيته، ثم نختار الصفحة التي تخدمه والإجراء المتوقع منها. بهذه الطريقة لا نستهدف كل الكلمات بالمحتوى نفسه أو بالصفحة نفسها.',

        // Ecommerce platforms
        'ecom_tag'   => 'منصات التجارة الإلكترونية',
        'ecom_title' => 'سلة وزد وShopify وWooCommerce — لكل منصة متطلبات سيو مختلفة',
        'ecom_desc'  => 'المنصة تُحدد البنية التقنية المتاحة وهذا يؤثر مباشرة على كيفية فهرسة جوجل لصفحات المنتجات والفئات. التدقيق يبدأ بالمنصة قبل الكلمات.',
        'ecom_items' => [
            [ 'label' => 'سلة',         'desc' => 'بنية الـURL وصفحات التصفية والفهرسة المتعددة — نقاط تقنية تؤثر على ظهور الفئات والمنتجات في جوجل' ],
            [ 'label' => 'زد',           'desc' => 'إعداد البيانات المنظمة لصفحات المنتجات والتعامل مع الصفحات المكررة في الكتالوجات الكبيرة' ],
            [ 'label' => 'Shopify',     'desc' => 'إدارة ملفات canonical وصفحات الترقيم والمحتوى المتكرر بين التصنيفات والعلامات' ],
            [ 'label' => 'WooCommerce', 'desc' => 'تحسين صفحات الفئات والمنتجات والربط الداخلي وإعداد Schema للمنتج والمراجعات' ],
        ],

        // Arabic/English strategy
        'bilingual_tag'   => 'استراتيجية اللغة',
        'bilingual_title' => 'متى تستهدف العربية ومتى تستهدف الإنجليزية في السعودية؟',
        'bilingual_items' => [
            [ 'label' => 'خدمات محلية للمستهلك',       'desc' => 'تميل للعربية — جمهور يبحث عن خدمة قريبة ومألوفة ثقافياً في الرياض أو جدة أو الدمام' ],
            [ 'label' => 'منتجات تقنية وبرمجيات',       'desc' => 'قد تكون الإنجليزية أو كلتا اللغتين — تحليل الكلمات الفعلية يُحدد التوزيع لكل منتج' ],
            [ 'label' => 'خدمات مهنية وB2B',             'desc' => 'جزء من الجمهور يبحث بالإنجليزية — خاصة في القطاعات التي لها طابع دولي' ],
            [ 'label' => 'تجارة إلكترونية للمستهلك',    'desc' => 'عموماً العربية أولاً — لكن البيانات تُحدد الاستثناءات في كل قطاع وكل منتج على حدة' ],
        ],

        // Core deliverables (numbered scope)
        'scope_tag'       => 'نطاق الخدمة',
        'scope_title'     => 'ما الذي ينجزه مشروع السيو في السعودية؟',
        'scope_items_ksa' => [
            [ 'n' => '01', 'label' => 'تدقيق تقني يشمل متطلبات المنصة',     'desc' => 'فحص بنية الموقع والفهرسة وسرعة الصفحات مع مراعاة متطلبات منصة التجارة الإلكترونية إن وجدت' ],
            [ 'n' => '02', 'label' => 'خارطة كلمات بالعربية والإنجليزية',    'desc' => 'تحليل الكلمات في القطاع المستهدف بكلتا اللغتين مع تحديد أولويات الاستهداف بحسب البيانات' ],
            [ 'n' => '03', 'label' => 'تحسين صفحات خدمات ومنتجات',           'desc' => 'تعديل الصفحات لتستهدف كلمات الشراء والمقارنة مع تحسين بنية المحتوى وعناصر التحويل' ],
            [ 'n' => '04', 'label' => 'محتوى لمراحل الاستفسار والمقارنة',    'desc' => 'مقالات وصفحات تستهدف الكلمات الاستفسارية وتُرشد الزائر نحو قرار الشراء' ],
            [ 'n' => '05', 'label' => 'روابط خارجية من مصادر ذات صلة',       'desc' => 'مراجعة فرص الروابط من مصادر محلية وإقليمية وفق نطاق العمل المتفق عليه' ],
        ],

        // Sectors
        'fit_tag'     => 'القطاعات',
        'fit_title'   => 'قطاعات يمكن دعمها بخدمات السيو في السعودية',
        'fit_sectors' => [
            [ 'label' => 'التجارة الإلكترونية', 'sub' => 'متاجر سلة وزد وShopify وWooCommerce في السوق السعودي', 'sector' => 'ecommerce',  'url' => sh_safe_url( 'sectors/ecommerce' ),  'svg' => '<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>' ],
            [ 'label' => 'العقارات',              'sub' => 'شركات وساطة عقارية ومطورون في المملكة',              'sector' => 'realestate', 'url' => sh_safe_url( 'sectors/realestate' ), 'svg' => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>' ],
            [ 'label' => 'الرعاية الصحية',       'sub' => 'عيادات ومستشفيات وخدمات طبية خاصة',                  'sector' => 'health',     'url' => sh_safe_url( 'sectors/health' ),     'svg' => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>' ],
            [ 'label' => 'التعليم والتدريب',      'sub' => 'جامعات ومراكز تدريب وتعليم إلكتروني',                'sector' => 'education',  'url' => sh_safe_url( 'sectors/education' ),  'svg' => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>' ],
            [ 'label' => 'الخدمات المهنية',       'sub' => 'شركات خدمية ومستشارون وB2B في المملكة',              'sector' => 'services',   'url' => '',                                  'svg' => '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>' ],
        ],

        // Process
        'process_tag'   => 'منهجية العمل',
        'process_title' => 'كيف نبني ظهورًا يناسب العميل السعودي؟',
        'process_steps' => [
            [ 'n' => '01', 'title' => 'تشخيص وضع الموقع في السوق السعودي',   'desc' => 'نُحلّل الكلمات التي تظهر بها الآن، المنافسين في قطاعك، والمشكلات التقنية التي تحجب صفحاتك عن الفهرسة.' ],
            [ 'n' => '02', 'title' => 'خارطة الكلمات بحسب مراحل الشراء',    'desc' => 'نُصنّف الكلمات بحسب المرحلة — استفساري، مقارنة، شراء — ونربط كل مجموعة بالصفحة المناسبة أو نُحدد ما ينقص.' ],
            [ 'n' => '03', 'title' => 'تحسينات تقنية وتحديث صفحات المنصة',  'desc' => 'معالجة المشكلات التقنية وتحسين الصفحات الأساسية لتستهدف الكلمات الصحيحة وتُراعي متطلبات المنصة.' ],
            [ 'n' => '04', 'title' => 'محتوى الاستفسار وروابط خارجية',       'desc' => 'إنشاء محتوى يستهدف مرحلة الاستفسار والمقارنة، وتنفيذ مبادرات الروابط الخارجية وفق نطاق العمل.' ],
            [ 'n' => '05', 'title' => 'قياس وتطوير شهري',                     'desc' => 'مراجعة بيانات الترتيب والزيارات والتحويلات شهرياً وتحديث الاستراتيجية بحسب ما تكشفه البيانات ومتغيرات المنافسة.' ],
        ],
        'process_note'  => 'الشهر الأول: تدقيق وخارطة كلمات وتحسينات تقنية وإعداد أدوات القياس. الشهر الثاني: تحديث الصفحات الأساسية ومحتوى الاستفسار والربط الداخلي. الشهر الثالث: توسيع المحتوى وروابط خارجية ومراجعة بيانات التحويل عند توافر إعداد تتبع مختبر.',

        'faqs' => [
            [ 'question' => 'هل تُحسّنون المواقع التي تستهدف العميل السعودي عن بُعد؟', 'answer' => 'نعم. إدارة المشروع والتواصل والتقارير تتم عن بُعد بالكامل. العمل يُنجز عبر أدوات التتبع والتقارير الحية دون الحاجة لتواجد مشترك.' ],
            [ 'question' => 'كيف تختلف استراتيجية السيو بين العربية والإنجليزية في السعودية؟', 'answer' => 'لكل لغة بنية كلمات مستقلة — ما يبحث عنه الجمهور بالعربية يختلف في التعبير والنية عما يبحثه بالإنجليزية في نفس القطاع. تحليل البيانات يُحدد أي اللغتين أولى بالاستثمار لموقعك.' ],
            [ 'question' => 'هل يمكن تحسين متجر سلة أو زد لمحركات البحث؟', 'answer' => 'نعم — لكن لكل منصة متطلبات تقنية مختلفة في بنية URL وصفحات التصفية والفهرسة. التدقيق يبدأ بفهم ما تتيحه المنصة قبل تحديد نطاق العمل الممكن.' ],
            [ 'question' => 'متى تظهر نتائج السيو في السعودية؟', 'answer' => 'بعض المؤشرات التقنية تتحسن مبكراً — تقدم الترتيب والزيارات يختلف بحسب وضع الموقع والمنافسة وسرعة التنفيذ. السيو يُقيَّم على أفق 3-6 أشهر في الغالب.' ],
            [ 'question' => 'هل السيو مناسب للشركات الصغيرة في السعودية؟', 'answer' => 'نعم — والسيو يُطبَّق بشكل مُرحَّل يبدأ بالأولويات الأعلى أثراً. الاستراتيجية تُعدّ بحسب وضع الموقع والميزانية المتاحة.' ],
            [ 'question' => 'هل تشمل الخدمة السيو المحلي لمدن كالرياض وجدة والدمام؟', 'answer' => 'يُحدد التدقيق ما إذا كان النشاط يحتاج استهدافاً وطنياً أو محلياً. الأنشطة المؤهلة ذات الملفات التجارية الموثقة على Google قد تستفيد من تحسينات الملف والبيانات والإشارات المحلية.' ],
        ],
        'cta_title' => 'ابدأ ببناء ظهور يناسب طريقة بحث العميل السعودي',
    ],

    // ── UAE ──────────────────────────────────────────────────────────────────
    'uae' => [
        'area_served_en'   => 'United Arab Emirates',
        'area_served_wiki' => 'https://en.wikipedia.org/wiki/United_Arab_Emirates',
        'name'         => 'الإمارات',
        'hero_tag'     => 'تحسين محركات البحث في الإمارات',
        'hero_title'   => 'شركة سيو في الإمارات للبحث بالعربية والإنجليزية',
        'hero_desc'    => 'في الإمارات قد يبحث جمهورك بالعربية أو الإنجليزية، وقد ينافس موقعك شركات محلية وإقليمية ودولية في النتيجة نفسها. لذلك نبدأ بتحديد اللغة والسوق والمدينة والصفحة التي يحتاجها كل جمهور.',

        // Language segmentation
        'segment_tag'    => 'تقسيم السوق',
        'segment_title'  => 'من هو جمهورك في الإمارات؟',
        'segment_desc'   => 'قبل بناء أي استراتيجية سيو نُحدد طبيعة جمهورك: هل يبحث بالعربية أم الإنجليزية أم كليهما؟ وهل هدفه محلي في دبي أو أبوظبي، أم إقليمي، أم دولي؟ الإجابة على هذين السؤالين تُحدد بنية الموقع والكلمات واللغات.',
        'segment_items'  => [
            [ 'label' => 'مواطنون وخليجيون',           'desc' => 'يبحثون غالباً بالعربية — خدمات محلية وعقارات ومرافق يومية في دبي وأبوظبي' ],
            [ 'label' => 'مقيمون عرب من خارج الخليج',  'desc' => 'مزيج من العربية والإنجليزية حسب القطاع — التحليل يُحدد التوزيع الفعلي' ],
            [ 'label' => 'مقيمون أجانب وزوار دوليون',  'desc' => 'الإنجليزية هي لغة البحث الافتراضية خاصة في السياحة والتجزئة والخدمات الدولية' ],
            [ 'label' => 'شركات B2B وقرارات مؤسسية',   'desc' => 'الإنجليزية تُهيمن في معظم القطاعات المؤسسية مع استثناءات في قطاعات حكومية أو محلية' ],
        ],

        // Multilingual technical requirements
        'multilingual_tag'   => 'المتطلبات التقنية',
        'multilingual_title' => 'بنية السيو للمواقع متعددة اللغات في الإمارات',
        'multilingual_items' => [
            [ 'label' => 'هيكل URL',               'desc' => 'الدليل الفرعي (/ar/ و/en/) أو النطاق الفرعي — لكل خيار تأثير على كيفية معالجة جوجل لكل نسخة لغوية' ],
            [ 'label' => 'إعداد hreflang',          'desc' => 'هذا الوسم يُخبر جوجل بلغة ومنطقة كل صفحة — خطأ بسيط في الإعداد يُسبب ظهور النسخة الخاطئة للجمهور الخاطئ' ],
            [ 'label' => 'المحتوى المكرر',           'desc' => 'ترجمة الصفحات لا تعني نسخها — كل نسخة تحتاج محتوى يُعالج نية البحث بلغتها لا مجرد ترجمة آلية' ],
            [ 'label' => 'canonical بين اللغتين',  'desc' => 'لكل لغة رابط مستقل وCanonical ذاتي، ونربط الصفحات المتكافئة فقط بإشارات hreflang متبادلة حتى يصل المستخدم إلى النسخة المناسبة دون دمج اللغتين في صفحة واحدة.' ],
        ],

        // Local vs international competition
        'compete_tag'   => 'طبيعة المنافسة',
        'compete_title' => 'محلي أم دولي — كيف تتنافس في السوق الإماراتي؟',
        'compete_desc'  => 'بعض القطاعات في الإمارات تنافس فيها مواقع دولية كبيرة — وهذا يختلف عن مواجهة منافسين محليين فقط. الاستراتيجية تُبنى بحسب طبيعة المنافسة في قطاعك.',
        'compete_items' => [
            [ 'label' => 'قطاعات بمنافسة محلية',  'desc' => 'العيادات والمطاعم والخدمات المنزلية — المحلي يتصدر عادةً. الفرصة في السيو المحلي والمحتوى الموجّه للمدينة' ],
            [ 'label' => 'قطاعات بمنافسة إقليمية', 'desc' => 'العقارات والتعليم والتجارة الإلكترونية — مزيج من محلي وإقليمي يحتاج تحليل المنافسين بدقة' ],
            [ 'label' => 'قطاعات بمنافسة دولية',   'desc' => 'البرمجيات والضيافة الدولية والاستشارات — التخصص والمحتوى العميق هو مدخل المنافسة مع المواقع ذات السلطة العالية' ],
        ],

        // Deliverables (numbered scope)
        'scope_tag'       => 'نطاق الخدمة',
        'scope_title'     => 'ما الذي ينجزه مشروع السيو في الإمارات؟',
        'scope_items_uae' => [
            [ 'n' => '01', 'label' => 'تدقيق تقني يشمل البنية اللغوية',           'desc' => 'فحص بنية الموقع وإعداد hreflang وصفحات اللغات والـcanonical بين النسخ' ],
            [ 'n' => '02', 'label' => 'خارطة كلمات منفصلة لكل لغة',               'desc' => 'تحليل الكلمات بالعربية والإنجليزية بشكل مستقل — أولويات مختلفة بحسب ما تكشفه بيانات كل لغة' ],
            [ 'n' => '03', 'label' => 'تحسين صفحات الخدمات والمنتجات',             'desc' => 'تحديث الصفحات الموجودة لتستهدف الكلمات الصحيحة في كل لغة مع تحسين بنية المحتوى' ],
            [ 'n' => '04', 'label' => 'محتوى يُعالج تنوع جمهور الإمارات',         'desc' => 'صفحات ومقالات تُخاطب الجمهور بلغته ومرجعياته — لا محتوى عاماً يُفترض فيه معالجة الجمهورين' ],
            [ 'n' => '05', 'label' => 'روابط خارجية من مصادر إقليمية ودولية',    'desc' => 'مراجعة فرص الروابط من مصادر تتناسب مع طبيعة السوق والجمهور وفق نطاق العمل المتفق عليه' ],
        ],

        // Sectors
        'fit_tag'     => 'القطاعات',
        'fit_title'   => 'قطاعات يمكن دعمها بخدمات السيو في الإمارات',
        'fit_sectors' => [
            [ 'label' => 'العقارات والضيافة',   'sub' => 'تطوير عقاري وفنادق وشقق في دبي وأبوظبي',   'sector' => 'realestate', 'url' => sh_safe_url( 'sectors/realestate' ), 'svg' => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>' ],
            [ 'label' => 'التجارة الإلكترونية', 'sub' => 'متاجر تخدم جمهوراً إماراتياً وخليجياً',     'sector' => 'ecommerce',  'url' => sh_safe_url( 'sectors/ecommerce' ),  'svg' => '<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>' ],
            [ 'label' => 'الرعاية الصحية',      'sub' => 'عيادات ومراكز طبية خاصة في الإمارات',       'sector' => 'health',     'url' => sh_safe_url( 'sectors/health' ),     'svg' => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>' ],
            [ 'label' => 'التعليم والتدريب',    'sub' => 'مؤسسات تعليمية ومنصات تعليم إلكتروني',      'sector' => 'education',  'url' => sh_safe_url( 'sectors/education' ),  'svg' => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>' ],
            [ 'label' => 'الخدمات المهنية',     'sub' => 'استشارات ومحاسبة وخدمات B2B في الإمارات',   'sector' => 'services',   'url' => '',                                  'svg' => '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>' ],
        ],

        // Process
        'process_tag'   => 'منهجية العمل',
        'process_title' => 'كيف نُعد استراتيجية سيو للسوق الإماراتي؟',
        'process_steps' => [
            [ 'n' => '01', 'title' => 'تحديد الجمهور واللغة وطبيعة المنافسة', 'desc' => 'نبدأ بسؤالين: من هو جمهورك فعلاً وبأي لغة يبحث؟ ومن هم منافسوك — محليون أم دوليون؟ الإجابة تُحدد نوع الاستراتيجية.' ],
            [ 'n' => '02', 'title' => 'تدقيق تقني يشمل البنية اللغوية',        'desc' => 'فحص الموقع مع انتباه خاص لإعداد hreflang والـcanonical بين النسخ اللغوية وصفحات المناطق الجغرافية.' ],
            [ 'n' => '03', 'title' => 'خارطة كلمات منفصلة لكل لغة وسوق',      'desc' => 'الكلمات العربية والإنجليزية تُحلَّل بشكل مستقل — ثم تُدمج في خارطة واحدة تُحدد الأولويات لكل لغة.' ],
            [ 'n' => '04', 'title' => 'محتوى وروابط بحسب اللغة والجمهور',      'desc' => 'إنشاء محتوى يُخاطب الجمهور بلغته ومرجعياته، وتنفيذ مبادرات روابط تُراعي طبيعة السوق وفق نطاق العمل.' ],
            [ 'n' => '05', 'title' => 'قياس أداء كل لغة ومراجعة شهرية',        'desc' => 'لوحة Looker Studio تُفصل بيانات الأداء بكلتا اللغتين ومراجعة شهرية تُحدّث الأولويات بحسب ما تكشفه البيانات.' ],
        ],
        'process_note'  => 'بناء بنية ثنائية اللغة سليمة تقنياً يأخذ وقتاً في البداية — لكنه يمنع مشكلات الفهرسة والمحتوى المكرر لاحقاً. الاستثمار في الإعداد الصحيح من البداية يوفر إعادة عمل مكلفة.',

        'faqs' => [
            [ 'question' => 'هل يجب أن يكون موقعي بالعربية والإنجليزية في الإمارات؟', 'answer' => 'ليس بالضرورة — يعتمد على جمهورك. إذا أظهرت بيانات الكلمات وجود طلب ذي قيمة في كلتا اللغتين فاللغتان معاً أفضل. إذا كان جمهورك أحادي اللغة فالتخصص في لغة واحدة أجدى.' ],
            [ 'question' => 'ما هو hreflang ولماذا يهم في الإمارات؟', 'answer' => 'hreflang وسم HTML يُخبر جوجل بلغة ومنطقة كل صفحة في موقع متعدد اللغات. بدونه قد يظهر جوجل النسخة العربية لجمهور إنجليزي أو العكس — مما يُخفض معدل النقر ويُرسل إشارات متضاربة.' ],
            [ 'question' => 'كيف تتعاملون مع المنافسة الدولية في الإمارات؟', 'answer' => 'تحليل المنافسين يُحدد ما إذا كانت المنافسة الأساسية محلية أم دولية. في القطاعات التي تنافس فيها مواقع دولية كبيرة، الاستراتيجية تعتمد على التخصص والمحتوى العميق بدلاً من المنافسة على كلمات شاملة.' ],
            [ 'question' => 'هل تشمل الخدمة السيو المحلي لدبي وأبوظبي؟', 'answer' => 'يُحدد التدقيق ما إذا كان النشاط يحتاج استهدافاً وطنياً أو محلياً. الأنشطة المؤهلة ذات الملفات الموثقة على Google قد تستفيد من تحسين الملف والبيانات والمراجعات والإشارات المحلية.' ],
            [ 'question' => 'متى تظهر نتائج السيو في الإمارات؟', 'answer' => 'المؤشرات التقنية والفهرسة قد تتحسن مبكراً. تقدم الترتيب والزيارات يختلف بحسب وضع الموقع والمنافسة ومدى تعقيد البنية اللغوية. السيو ثنائي اللغة يحتاج وقتاً إضافياً لاستقرار إشارات hreflang.' ],
            [ 'question' => 'هل يمكن إضافة لغة جديدة لموقع قائم؟', 'answer' => 'نعم — لكنه يستدعي تخطيطاً مسبقاً لبنية URL والـcanonical وإعداد hreflang. إضافة لغة دون إعداد صحيح قد تُسبب مشكلات فهرسة أو محتوى مكرر يُضر بكلتا النسختين.' ],
        ],
        'cta_title' => 'ابدأ ببناء استراتيجية سيو تُخاطب جمهورك بلغته في الإمارات',
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
    $market = $acf_to_config[ $acf_market ];
    $c      = $country_config[ $market ];
} else {
    $slug = get_post_field( 'post_name', get_the_ID() );
    if ( $slug === 'ksa' ) {
        $slug = 'saudi-arabia';
    }
    $market = isset( $country_config[ $slug ] ) ? $slug : null;
    $c      = $market ? $country_config[ $market ] : null;
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
$hero_tag   = sh_field( 'service_hero_tag' )   ?: $c['hero_tag'];
$hero_title = sh_field( 'service_hero_title' )  ?: $c['hero_title'];
$hero_desc  = sh_field( 'service_hero_desc' )   ?: $c['hero_desc'];
$faqs       = sh_field( 'service_faqs' );
$faq_data   = is_array( $faqs ) && count( array_filter( $faqs, fn( $r ) => ! empty( $r['question'] ) ) ) ? $faqs : $c['faqs'];

// Services and SEO parent URLs (used in breadcrumb and back-links)
$services_url = sh_page_url( 'services' );
$seo_url      = sh_page_url( 'services/seo' );
// Proof box disabled — $results_url intentionally kept empty until Results page reviewed.
$results_url = '';

// Animation delay classes for staggered card reveals
$delays = [ '', ' d1', ' d2', ' d3', ' d4', ' d5' ];

// Reporting checklist items — consistent across all markets
$report_checklist = [
    'لوحة Looker Studio حية — متاحة في أي وقت، مُحدَّثة من Search Console وGA4',
    'ترتيب الكلمات المستهدفة وتطوره مقارنةً بالشهر السابق',
    'حركة البحث العضوي وتطورها — من Search Console وGA4',
    'بيانات التحويلات والإجراءات على الموقع — عند توافر إعداد تتبع موثوق ومُختبَر',
    'ملخص العمل المُنجز وخطة الشهر التالي',
    'قناة تواصل متفق عليها لمتابعة التنفيذ والاستفسارات.',
];

$technical_url = sh_page_url( 'services/seo/technical' );
$on_page_url   = sh_page_url( 'services/seo/on-page' );
?>

<?php
$five_services = [
    [
        'title' => 'السيو التقني',
        'desc'  => 'تشخيص مشكلات الزحف والفهرسة وسرعة الموقع وبنية الروابط الداخلية — الأساس الذي يُمكّن بقية جهود السيو.',
        'url'   => $technical_url,
        'svg'   => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
    ],
    [
        'title' => 'سيو داخل الصفحة',
        'desc'  => 'تحسين العناوين والمحتوى وبنية الصفحات لتتوافق مع نية المستخدم وتعزز فرص الظهور في نتائج البحث.',
        'url'   => $on_page_url,
        'svg'   => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>',
    ],
    [
        'title' => 'محتوى السيو',
        'desc'  => 'كتابة صفحات ومقالات مبنية على بيانات الكلمات — محتوى يستهدف نية البحث بدقة.',
        'url'   => sh_safe_url( 'services/seo/content' ),
        'svg'   => '<path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>',
    ],
    [
        'title' => 'بناء الروابط الخارجية',
        'desc'  => 'روابط من مصادر ذات صلة وسلطة — لتعزيز مكانة الموقع في نتائج البحث.',
        'url'   => sh_safe_url( 'services/seo/backlinks' ),
        'svg'   => '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>',
    ],
    [
        'title' => 'استشارة السيو',
        'desc'  => 'جلسة تحليل لفريقك — تشخيص الوضع الحالي وخارطة أولويات قابلة للتنفيذ.',
        'url'   => sh_safe_url( 'services/seo/consulting' ),
        'svg'   => '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
    ],
];

$team_members = get_posts( [
    'post_type'      => 'team_member',
    'posts_per_page' => 4,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'post_status'    => 'publish',
] );

$reviews_sc = sh_option( 'reviews_shortcode' );
$ev_base    = get_template_directory_uri() . '/assets/images/seo-service/';
?>

<main class="svc-seo-country">

<!-- ① Hero — all markets -->
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
          احجز استشارة
        </a>
        <a href="#country-faq" class="btn btn-g lg">أسئلة شائعة</a>
      </div>
    </div>
  </div>
</section>

<?php
// Only render client strip when actual approved logos exist.
$_clients_check = sh_get_clients();
if ( $_clients_check->have_posts() ) :
    get_template_part( 'template-parts/sections/clients' );
endif;
wp_reset_postdata();
?>

<?php // ─────────────────────── SAUDI ARABIA SECTIONS ──────────────────────────
if ( $market === 'saudi-arabia' ) : ?>

<!-- Saudi: Search decisions intro -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="why-com-grid">
      <div class="sr">
        <div class="sh">
          <span class="tag"><?php echo esc_html( $c['search_tag'] ); ?></span>
          <h2 class="h2"><?php echo esc_html( $c['search_title'] ); ?></h2>
          <p class="bod" style="margin-top:12px"><?php echo esc_html( $c['search_desc'] ); ?></p>
        </div>
      </div>
      <div class="sr d1">
        <!-- Unique Saudi module: اللغة → نوع البحث → الصفحة المطلوبة → التحويل -->
        <div style="background:var(--navy-2);border-radius:var(--r4);padding:28px 24px;overflow:hidden;position:relative">
          <div style="position:absolute;inset-inline-end:-40px;top:-40px;width:180px;height:180px;border-radius:50%;background:radial-gradient(circle,rgba(38,71,199,.3),transparent 70%)"></div>
          <div style="font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.22);margin-bottom:18px;position:relative;z-index:1">مراحل قرار البحث السعودي</div>
          <div style="position:relative;z-index:1;display:flex;flex-direction:column;gap:4px">
            <?php
            $flow_steps = [
                [ 'label' => 'اللغة',           'items' => [ 'عربية', 'إنجليزية' ],                        'color' => '#91A6F4' ],
                [ 'label' => 'نوع البحث',        'items' => [ 'استفساري', 'مقارنة', 'شراء' ],               'color' => '#7EB8F7' ],
                [ 'label' => 'الصفحة المطلوبة',  'items' => [ 'مقالة / دليل', 'صفحة مقارنة', 'منتج / خدمة' ], 'color' => '#72D9A8' ],
                [ 'label' => 'التحويل',           'items' => [ 'قراءة', 'استفسار', 'شراء مباشر' ],           'color' => '#F5A623' ],
            ];
            foreach ( $flow_steps as $idx => $step ) :
            ?>
            <?php if ( $idx > 0 ) : ?>
            <div style="display:flex;justify-content:center;padding:3px 0">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.25)" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
            <?php endif; ?>
            <div style="padding:12px 14px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:var(--r2)">
              <div style="font-size:10px;font-weight:700;color:<?php echo esc_attr( $step['color'] ); ?>;margin-bottom:6px;letter-spacing:.05em"><?php echo esc_html( $step['label'] ); ?></div>
              <div style="display:flex;gap:6px;flex-wrap:wrap">
                <?php foreach ( $step['items'] as $item ) : ?>
                <span style="font-size:11.5px;font-weight:600;color:rgba(255,255,255,.75);background:rgba(255,255,255,.07);padding:3px 9px;border-radius:20px"><?php echo esc_html( $item ); ?></span>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Saudi: Ecommerce platforms module -->
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag"><?php echo esc_html( $c['ecom_tag'] ); ?></span>
      <h2 class="h2"><?php echo esc_html( $c['ecom_title'] ); ?></h2>
      <p class="bod" style="margin-top:12px;max-width:600px;margin-inline:auto"><?php echo esc_html( $c['ecom_desc'] ); ?></p>
    </div>
    <div class="features-grid sr d1" style="grid-template-columns:repeat(2,1fr)">
      <?php foreach ( $c['ecom_items'] as $ei ) : ?>
      <div class="feat-card">
        <div class="ico-box" style="margin-bottom:14px">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="18" height="18"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        </div>
        <h3><?php echo esc_html( $ei['label'] ); ?></h3>
        <p><?php echo esc_html( $ei['desc'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Saudi: Arabic/English strategy -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag"><?php echo esc_html( $c['bilingual_tag'] ); ?></span>
      <h2 class="h2"><?php echo esc_html( $c['bilingual_title'] ); ?></h2>
    </div>
    <div class="features-grid sr d1" style="grid-template-columns:repeat(2,1fr)">
      <?php foreach ( $c['bilingual_items'] as $bi ) : ?>
      <div class="feat-card">
        <div class="ico-box" style="margin-bottom:14px">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="18" height="18"><path d="M5 8l6 6"/><path d="M4 14l6-6 2-3"/><path d="M2 5h12"/><path d="M7 2h1"/><path d="M22 22l-5-10-5 10"/><path d="M14 18h6"/></svg>
        </div>
        <h3><?php echo esc_html( $bi['label'] ); ?></h3>
        <p><?php echo esc_html( $bi['desc'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Saudi: Core deliverables -->
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag"><?php echo esc_html( $c['scope_tag'] ); ?></span>
      <h2 class="h2"><?php echo esc_html( $c['scope_title'] ); ?></h2>
    </div>
    <div class="steps-list sr d1" style="margin-top:32px;max-width:800px;margin-inline:auto">
      <?php foreach ( $c['scope_items_ksa'] as $si ) : ?>
      <div class="step-item">
        <div class="step-num"><?php echo esc_html( $si['n'] ); ?></div>
        <div class="step-body">
          <h3><?php echo esc_html( $si['label'] ); ?></h3>
          <p><?php echo esc_html( $si['desc'] ); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Saudi: Sectors -->
<section class="sec sec-off">
  <div class="wrap">
    <div class="sh c sr"><span class="tag"><?php echo esc_html( $c['fit_tag'] ); ?></span><h2 class="h2"><?php echo esc_html( $c['fit_title'] ); ?></h2></div>
    <div class="lp-sct-grid sr d1">
      <?php foreach ( $c['fit_sectors'] as $fs ) :
          $stag  = ! empty( $fs['url'] ) ? 'a' : 'div';
          $shref = ! empty( $fs['url'] ) ? ' href="' . esc_url( $fs['url'] ) . '"' : '';
      ?>
      <<?php echo $stag; ?><?php echo $shref; ?> class="lp-sct-card">
        <div class="lp-sct-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?php echo $fs['svg']; ?></svg></div>
        <h3><?php echo esc_html( $fs['label'] ); ?></h3>
        <p><?php echo esc_html( $fs['sub'] ); ?></p>
      </<?php echo $stag; ?>>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Saudi: Process -->
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
    <?php if ( ! empty( $c['process_note'] ) ) : ?>
    <p class="bod c sr" style="margin-top:20px;max-width:800px;margin-inline:auto;font-size:13px;color:var(--muted);line-height:1.8;border-top:1px solid var(--line);padding-top:18px"><?php echo esc_html( $c['process_note'] ); ?></p>
    <?php endif; ?>
  </div>
</section>

<?php // ─────────────────────────── EGYPT SECTIONS ─────────────────────────────
elseif ( $market === 'egypt' ) : ?>

<!-- Egypt: Traffic quality argument -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="why-com-grid">
      <div class="sr">
        <div class="sh">
          <span class="tag"><?php echo esc_html( $c['quality_tag'] ); ?></span>
          <h2 class="h2"><?php echo esc_html( $c['quality_title'] ); ?></h2>
          <p class="bod" style="margin-top:12px"><?php echo esc_html( $c['quality_desc'] ); ?></p>
        </div>
        <div class="chklist sr d1" style="margin-top:20px">
          <?php foreach ( $c['quality_points'] as $pt ) : ?>
          <div class="chk-item">
            <div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div>
            <span><strong><?php echo esc_html( $pt['label'] ); ?></strong> — <?php echo esc_html( $pt['desc'] ); ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="sr d1">
        <!-- Unique Egypt module: كلمة البحث → الصفحة المناسبة → الإجراء المطلوب -->
        <div style="font-size:10px;font-weight:700;letter-spacing:.1em;color:var(--muted);text-transform:uppercase;margin-bottom:8px">نماذج توضيحية — ليست بيانات بحث فعلية</div>
        <div style="border:1px solid var(--line);border-radius:var(--r3);overflow:hidden">
          <div style="background:var(--ink);padding:12px 16px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;text-align:center">
            <?php foreach ( [ 'كلمة البحث', 'الصفحة المناسبة', 'الإجراء المطلوب' ] as $mh ) : ?>
            <div style="font-size:10.5px;font-weight:700;color:rgba(255,255,255,.7);letter-spacing:.05em"><?php echo esc_html( $mh ); ?></div>
            <?php endforeach; ?>
          </div>
          <?php
          $conv_rows = [
              [ '"أفضل متجر ملابس أونلاين"',     'صفحة تصنيف أو مدونة',       'قراءة ثم زيارة المنتج' ],
              [ '"سعر ... في مصر"',              'صفحة منتج بتفاصيل السعر',    'إضافة للسلة / استفسار' ],
              [ '"عيادة أسنان في القاهرة"',       'صفحة خدمة بموقع وتقييمات',  'حجز موعد / اتصال' ],
              [ '"أفضل محامي توثيق"',             'صفحة خدمة تفصيلية',          'طلب استشارة' ],
          ];
          foreach ( $conv_rows as $ridx => $row ) :
              $bg = $ridx % 2 === 0 ? 'var(--off)' : '#fff';
          ?>
          <div style="background:<?php echo $bg; ?>;display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;padding:10px 16px;border-bottom:1px solid var(--line)">
            <?php foreach ( $row as $cidx => $cell ) : ?>
            <div style="font-size:12px;<?php echo $cidx === 0 ? 'font-weight:600;color:var(--blue)' : 'color:var(--muted)'; ?>"><?php echo esc_html( $cell ); ?></div>
            <?php endforeach; ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Egypt: Priority services -->
<section class="sec sec-off">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag">خدمات السيو المتخصصة</span>
      <h2 class="h2">خمس خدمات سيو تُعالج كل جانب من معادلة الظهور</h2>
      <p class="bod" style="margin-top:12px;max-width:640px;margin-inline:auto">من البنية التقنية التي تُمكّن الفهرسة، إلى المحتوى الذي يستهدف نية الشراء، إلى الروابط التي تعزز سلطة الصفحة.</p>
    </div>
    <div class="lp-five-grid sr d1">
      <?php foreach ( $five_services as $svc ) :
          $is_link = ! empty( $svc['url'] );
          $ftag    = $is_link ? 'a' : 'div';
          $fhref   = $is_link ? ' href="' . esc_url( $svc['url'] ) . '"' : '';
      ?>
      <<?php echo $ftag; ?><?php echo $fhref; ?> class="lp-five-card<?php echo $is_link ? ' lp-five-link' : ''; ?>">
        <div class="lp-five-ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="20" height="20"><?php echo $svc['svg']; ?></svg>
        </div>
        <h3><?php echo esc_html( $svc['title'] ); ?></h3>
        <p><?php echo esc_html( $svc['desc'] ); ?></p>
        <?php if ( $is_link ) : ?>
        <span class="lp-five-arrow"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
        <?php endif; ?>
      </<?php echo $ftag; ?>>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Egypt: Local + ecommerce execution -->
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag"><?php echo esc_html( $c['exec_tag'] ); ?></span>
      <h2 class="h2"><?php echo esc_html( $c['exec_title'] ); ?></h2>
    </div>
    <div class="features-grid sr d1" style="grid-template-columns:repeat(<?php echo count( $c['exec_items'] ) >= 3 ? '3' : '2'; ?>,1fr)">
      <?php foreach ( $c['exec_items'] as $ei ) : ?>
      <div class="feat-card">
        <div class="ico-box" style="margin-bottom:14px">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="18" height="18"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.89"/></svg>
        </div>
        <h3><?php echo esc_html( $ei['label'] ); ?></h3>
        <p><?php echo esc_html( $ei['desc'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Egypt: Phased plan -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag"><?php echo esc_html( $c['process_tag'] ); ?></span>
      <h2 class="h2"><?php echo esc_html( $c['process_title'] ); ?></h2>
    </div>
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
    <?php if ( ! empty( $c['process_note'] ) ) : ?>
    <p class="bod c sr" style="margin-top:20px;max-width:800px;margin-inline:auto;font-size:13px;color:var(--muted);line-height:1.8;border-top:1px solid var(--line);padding-top:18px"><?php echo esc_html( $c['process_note'] ); ?></p>
    <?php endif; ?>
  </div>
</section>

<!-- Egypt: Sectors -->
<section class="sec sec-off">
  <div class="wrap">
    <div class="sh c sr"><span class="tag"><?php echo esc_html( $c['fit_tag'] ); ?></span><h2 class="h2"><?php echo esc_html( $c['fit_title'] ); ?></h2></div>
    <div class="lp-sct-grid sr d1">
      <?php foreach ( $c['fit_sectors'] as $fs ) :
          $stag  = ! empty( $fs['url'] ) ? 'a' : 'div';
          $shref = ! empty( $fs['url'] ) ? ' href="' . esc_url( $fs['url'] ) . '"' : '';
      ?>
      <<?php echo $stag; ?><?php echo $shref; ?> class="lp-sct-card">
        <div class="lp-sct-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?php echo $fs['svg']; ?></svg></div>
        <h3><?php echo esc_html( $fs['label'] ); ?></h3>
        <p><?php echo esc_html( $fs['sub'] ); ?></p>
      </<?php echo $stag; ?>>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php // ──────────────────────────── UAE SECTIONS ───────────────────────────────
elseif ( $market === 'uae' ) : ?>

<!-- UAE: Language/market segmentation -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="why-com-grid">
      <div class="sr">
        <div class="sh">
          <span class="tag"><?php echo esc_html( $c['segment_tag'] ); ?></span>
          <h2 class="h2"><?php echo esc_html( $c['segment_title'] ); ?></h2>
          <p class="bod" style="margin-top:12px"><?php echo esc_html( $c['segment_desc'] ); ?></p>
        </div>
        <div class="chklist sr d1" style="margin-top:20px">
          <?php foreach ( $c['segment_items'] as $si ) : ?>
          <div class="chk-item">
            <div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div>
            <span><strong><?php echo esc_html( $si['label'] ); ?></strong> — <?php echo esc_html( $si['desc'] ); ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="sr d1">
        <!-- Unique UAE module: Arabic/English × City × Intent × Target page matrix -->
        <div style="border:1px solid var(--line);border-radius:var(--r3);overflow:hidden;overflow-x:auto">
          <table style="width:100%;border-collapse:collapse;min-width:400px;direction:rtl">
            <thead>
              <tr style="background:var(--ink)">
                <th style="padding:10px 12px;text-align:right;font-size:10px;font-weight:700;color:rgba(255,255,255,.55);white-space:nowrap">اللغة / النية</th>
                <th style="padding:10px 12px;text-align:center;font-size:10px;font-weight:700;color:rgba(255,255,255,.55)">دبي</th>
                <th style="padding:10px 12px;text-align:center;font-size:10px;font-weight:700;color:rgba(255,255,255,.55)">أبوظبي</th>
                <th style="padding:10px 12px;text-align:center;font-size:10px;font-weight:700;color:rgba(255,255,255,.55)">الإمارات عموماً</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $matrix = [
                  [ 'lang' => 'عربية — استفسار',  'bg' => 'var(--off)', 'cells' => [ 'مدونة / دليل (عربي)',     'مدونة / دليل (عربي)',     'صفحة قطاع (عربية)' ] ],
                  [ 'lang' => 'عربية — شراء',      'bg' => '#fff',       'cells' => [ 'صفحة خدمة دبي (عربية)',  'صفحة خدمة أبوظبي (عربية)', 'صفحة خدمة رئيسية' ] ],
                  [ 'lang' => 'English — Info',   'bg' => 'var(--off)', 'cells' => [ 'Blog / Guide (en)',       'Blog / Guide (en)',       'Sector page (en)' ] ],
                  [ 'lang' => 'English — Buy',    'bg' => '#fff',       'cells' => [ 'Service — Dubai (en)',   'Service — Abu Dhabi (en)', 'Main service (en)' ] ],
              ];
              foreach ( $matrix as $mrow ) :
              ?>
              <tr style="background:<?php echo $mrow['bg']; ?>;border-bottom:1px solid var(--line)">
                <td style="padding:9px 12px;font-size:11.5px;font-weight:600;color:var(--blue);white-space:nowrap"><?php echo esc_html( $mrow['lang'] ); ?></td>
                <?php foreach ( $mrow['cells'] as $mcell ) : ?>
                <td style="padding:9px 12px;font-size:11px;color:var(--muted);text-align:center"><?php echo esc_html( $mcell ); ?></td>
                <?php endforeach; ?>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <div style="padding:8px 12px;background:var(--off);border-top:1px solid var(--line);font-size:10.5px;color:var(--muted)">الجدول يُبسّط النمط العام — التحليل الفعلي يُحدد أولويات كل موقع بحسب بيانات الكلمات والمنافسة في قطاعه.</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- UAE: Multilingual technical requirements -->
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag"><?php echo esc_html( $c['multilingual_tag'] ); ?></span>
      <h2 class="h2"><?php echo esc_html( $c['multilingual_title'] ); ?></h2>
    </div>
    <div class="features-grid sr d1" style="grid-template-columns:repeat(2,1fr)">
      <?php foreach ( $c['multilingual_items'] as $mi ) : ?>
      <div class="feat-card">
        <div class="ico-box" style="margin-bottom:14px">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="18" height="18"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <h3><?php echo esc_html( $mi['label'] ); ?></h3>
        <p><?php echo esc_html( $mi['desc'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- UAE: Local vs international competition -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag"><?php echo esc_html( $c['compete_tag'] ); ?></span>
      <h2 class="h2"><?php echo esc_html( $c['compete_title'] ); ?></h2>
      <p class="bod" style="margin-top:12px;max-width:640px;margin-inline:auto"><?php echo esc_html( $c['compete_desc'] ); ?></p>
    </div>
    <div class="features-grid sr d1" style="grid-template-columns:repeat(3,1fr)">
      <?php foreach ( $c['compete_items'] as $ci ) : ?>
      <div class="feat-card">
        <div class="ico-box" style="margin-bottom:14px">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="18" height="18"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
        </div>
        <h3><?php echo esc_html( $ci['label'] ); ?></h3>
        <p><?php echo esc_html( $ci['desc'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- UAE: Deliverables -->
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag"><?php echo esc_html( $c['scope_tag'] ); ?></span>
      <h2 class="h2"><?php echo esc_html( $c['scope_title'] ); ?></h2>
    </div>
    <div class="steps-list sr d1" style="margin-top:32px;max-width:800px;margin-inline:auto">
      <?php foreach ( $c['scope_items_uae'] as $si ) : ?>
      <div class="step-item">
        <div class="step-num"><?php echo esc_html( $si['n'] ); ?></div>
        <div class="step-body">
          <h3><?php echo esc_html( $si['label'] ); ?></h3>
          <p><?php echo esc_html( $si['desc'] ); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- UAE: Sectors -->
<section class="sec sec-off">
  <div class="wrap">
    <div class="sh c sr"><span class="tag"><?php echo esc_html( $c['fit_tag'] ); ?></span><h2 class="h2"><?php echo esc_html( $c['fit_title'] ); ?></h2></div>
    <div class="lp-sct-grid sr d1">
      <?php foreach ( $c['fit_sectors'] as $fs ) :
          $stag  = ! empty( $fs['url'] ) ? 'a' : 'div';
          $shref = ! empty( $fs['url'] ) ? ' href="' . esc_url( $fs['url'] ) . '"' : '';
      ?>
      <<?php echo $stag; ?><?php echo $shref; ?> class="lp-sct-card">
        <div class="lp-sct-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?php echo $fs['svg']; ?></svg></div>
        <h3><?php echo esc_html( $fs['label'] ); ?></h3>
        <p><?php echo esc_html( $fs['sub'] ); ?></p>
      </<?php echo $stag; ?>>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- UAE: Process -->
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
    <?php if ( ! empty( $c['process_note'] ) ) : ?>
    <p class="bod c sr" style="margin-top:20px;max-width:800px;margin-inline:auto;font-size:13px;color:var(--muted);line-height:1.8;border-top:1px solid var(--line);padding-top:18px"><?php echo esc_html( $c['process_note'] ); ?></p>
    <?php endif; ?>
  </div>
</section>

<?php endif; // end per-market sections ?>

<!-- ⑦ Evidence — verified figures, no country attribution -->
<section id="country-evidence" class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag">نتائج موثقة</span>
      <h2 class="h2">نتائج من مشروعات SEO House</h2>
      <p class="bod" style="margin-top:12px;max-width:660px;margin-inline:auto">نماذج موثقة من نتائج مشروعات SEO House المتكاملة. تختلف النتائج بحسب الموقع والسوق والمنافسة ونطاق التنفيذ.</p>
    </div>
    <div class="sr d1 ev-stat-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:28px;max-width:760px;margin-inline:auto">
      <div style="text-align:center;padding:24px 16px;background:var(--off);border:1px solid var(--line);border-radius:var(--r2)">
        <div style="font-size:15px;font-weight:900;color:var(--ink);direction:ltr;font-variant-numeric:tabular-nums;line-height:1.3"><?php echo wp_kses( '<bdi dir="ltr">19,956.29 → 106,274.45</bdi> <span>ر.س</span>', [ 'bdi' => [ 'dir' => [] ], 'span' => [] ] ); ?></div>
        <div style="font-size:12px;color:var(--muted);margin-top:8px;line-height:1.5;font-weight:600">مبيعات من محركات البحث</div>
        <div style="font-size:11px;color:var(--muted);margin-top:4px;opacity:.7">Looker Studio — Organic Search</div>
      </div>
      <div style="text-align:center;padding:24px 16px;background:var(--off);border:1px solid var(--line);border-radius:var(--r2)">
        <div style="font-size:15px;font-weight:900;color:var(--ink);direction:ltr;font-variant-numeric:tabular-nums;line-height:1.3"><?php echo wp_kses( '<bdi dir="ltr">825 → 12,900</bdi> <span>نقرة</span>', [ 'bdi' => [ 'dir' => [] ], 'span' => [] ] ); ?></div>
        <div style="font-size:12px;color:var(--muted);margin-top:8px;line-height:1.5;font-weight:600">نقرات من نتائج Google</div>
        <div style="font-size:11px;color:var(--muted);margin-top:4px;opacity:.7">Google Search Console</div>
      </div>
      <div style="text-align:center;padding:24px 16px;background:var(--off);border:1px solid var(--line);border-radius:var(--r2)">
        <div style="font-size:28px;font-weight:900;color:var(--ink);direction:ltr">#1</div>
        <div style="font-size:12px;color:var(--muted);margin-top:8px;line-height:1.5;font-weight:600">النتيجة العضوية لكلمة «مكتب محاماة»</div>
        <div style="font-size:11px;color:var(--muted);margin-top:4px;opacity:.7">Google Search — Organic Result</div>
      </div>
    </div>
  </div>
</section>

<!-- ⑧ Team — all markets (conditional) -->
<?php if ( ! empty( $team_members ) ) : ?>
<section class="sec sec-white">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag">الفريق</span>
      <h2 class="h2">فريق SEO House</h2>
      <p class="bod">متخصّصون في تحسين محركات البحث للسوق العربي — بخبرة عملية وأدوات موثوقة.</p>
    </div>
    <div class="lp-team-grid sr d1">
      <?php foreach ( $team_members as $member ) :
          $img_id = get_post_thumbnail_id( $member->ID );
          $img    = $img_id ? wp_get_attachment_image( $img_id, 'seohouse-team', false, [ 'loading' => 'lazy' ] ) : '';
          $role   = get_post_meta( $member->ID, 'team_role', true );
      ?>
      <div class="lp-team-card">
        <?php if ( $img ) : ?>
        <div class="lp-team-img"><?php echo $img; ?></div>
        <?php endif; ?>
        <div class="lp-team-info">
          <h3><?php echo esc_html( $member->post_title ); ?></h3>
          <?php if ( $role ) : ?><p><?php echo esc_html( $role ); ?></p><?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ⑨ Reviews — all markets (conditional) -->
<?php if ( ! empty( $reviews_sc ) ) : ?>
<section class="sec sec-surface">
  <div class="wrap">
    <?php echo do_shortcode( $reviews_sc ); ?>
  </div>
</section>
<?php endif; ?>

<!-- ⑩ FAQ — all markets -->
<section id="country-faq" class="sec sec-white">
  <div class="wrap">
    <div class="sh sr">
      <span class="tag">الأسئلة الشائعة</span>
      <h2 class="h2">أسئلة عن السيو <?php echo esc_html( $c['name'] === 'مصر' ? 'في مصر' : ( $c['name'] === 'السعودية' ? 'في السعودية' : 'في الإمارات' ) ); ?></h2>
    </div>
    <div class="faq-list sr d1">
      <?php foreach ( $faq_data as $faq ) : ?>
      <div class="faq-item">
        <div class="faq-q"><span><?php echo esc_html( $faq['question'] ?? '' ); ?></span><div class="faq-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></div></div>
        <div class="faq-a"><div class="faq-a-inner"><?php echo esc_html( $faq['answer'] ?? '' ); ?></div></div>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="margin-top:28px;padding:20px 22px;background:var(--off);border:1px solid var(--line);border-radius:var(--r2);display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap">
      <div>
        <div style="font-size:12px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px">الخدمة الرئيسية</div>
        <div style="font-size:14.5px;font-weight:800;color:var(--ink)">تحسين محركات البحث — الصفحة الرئيسية</div>
      </div>
      <a href="<?php echo esc_url( $seo_url ); ?>" class="btn btn-o">عرض الخدمة الرئيسية <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
    </div>
  </div>
</section>

<!-- ⑪ CTA Banner — all markets -->
<?php
get_template_part( 'template-parts/layout/cta-banner', null, [
    'tag'         => 'ابدأ الآن',
    'title'       => $c['cta_title'],
    'description' => 'احجز استشارة أولية لمناقشة وضع الموقع وأولويات التحسين والخطوات الممكنة وفق نطاق العمل.',
    'buttons'     => [
        [ 'text' => 'احجز استشارة',           'url' => sh_page_url( 'contact' ), 'class' => 'btn-w lg' ],
        [ 'text' => 'تعرّف على خدمة السيو', 'url' => $seo_url,                 'class' => 'btn-g lg' ],
    ],
] );
?>

</main><!-- /.svc-seo-country -->

<?php get_footer(); ?>
