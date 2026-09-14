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
 *
 * Metadata (SEO title & meta description) must be set per-page through
 * Rank Math. Approved values per market are stored as 'meta_title' and
 * 'meta_desc' in the config below for reference — do NOT output them as
 * a second <title> tag while Rank Math is active.
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
        'name'             => 'مصر',
        'hero_tag'         => 'تحسين محركات البحث في مصر',

        // Metadata — set these values in Rank Math per-page; do not output as a second <title>.
        'meta_title' => 'شركة سيو في مصر | خدمات تحسين محركات البحث | سيو هاوس',
        'meta_desc'  => 'شركة سيو في مصر تساعدك على تحسين ظهور موقعك وجذب زيارات تتحول إلى مكالمات ورسائل وطلبات. خدمات سيو متكاملة للشركات والمتاجر.',

        // Hero
        'hero_title' => 'شركة سيو في مصر لتحسين ظهور موقعك وزيادة العملاء',
        'hero_desc'  => 'نحسّن موقعك ليظهر في الكلمات التي يبحث عنها عملاؤك، ثم نتابع ما تحققه هذه الزيارات من مكالمات ورسائل وطلبات. يشمل العمل مراجعة الموقع، وتحسين الصفحات والمحتوى، ومعالجة المشكلات التقنية، وقياس النتائج بصورة واضحة.',

        // Five services — per-market descriptions
        'services_h2'    => 'خمس خدمات سيو تعالج كل جانب من معادلة الظهور',
        'services_intro' => 'نجاح الموقع في نتائج البحث يحتاج إلى أكثر من نشر المقالات. قد تكون المشكلة في فهرسة الصفحات، أو ضعف صفحات الخدمات، أو استهداف كلمات غير مناسبة، أو غياب الروابط التي تساعد جوجل والزائر على الوصول إلى المحتوى المهم.',
        'services' => [
            [ 'title' => 'السيو التقني',           'desc' => 'نراجع سرعة الموقع وفهرسة الصفحات وتجربة الاستخدام على الهاتف والروابط المعطلة والصفحات المكررة، ونعالج المشكلات التي تحد من ظهور الموقع في جوجل.',           'url_key' => 'services/seo/technical',  'svg' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>' ],
            [ 'title' => 'السيو الداخلي',          'desc' => 'نحسّن عناوين الصفحات ومحتواها وروابطها الداخلية، ونوزع الكلمات على الصفحات المناسبة حتى تخدم كل صفحة هدفًا واضحًا.',                                           'url_key' => 'services/seo/on-page',    'svg' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>' ],
            [ 'title' => 'محتوى السيو',            'desc' => 'نكتب صفحات خدمات ومقالات مبنية على أسئلة واحتياجات حقيقية، مع ربط المحتوى بالخدمة أو المنتج الذي يمكن أن يستفيد منه القارئ.',                                     'url_key' => 'services/seo/content',    'svg' => '<path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>' ],
            [ 'title' => 'بناء الروابط الخارجية', 'desc' => 'نعمل على تقوية موثوقية الموقع من خلال روابط مناسبة من مواقع حقيقية ومرتبطة بمجال النشاط، مع مراجعة الروابط الحالية واستبعاد المصادر الضعيفة.',                  'url_key' => 'services/seo/backlinks',  'svg' => '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>' ],
            [ 'title' => 'استشارات السيو',         'desc' => 'نساعد فرق التسويق والتطوير على اتخاذ القرارات الصحيحة، وترتيب أولويات التنفيذ، ومراجعة التعديلات قبل وبعد إطلاقها.',                                                 'url_key' => 'services/seo/consulting', 'svg' => '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>' ],
        ],

        // Country-specific sections
        'country_sections' => [
            [
                'h2'    => 'زيادة الزيارات ليست الهدف الوحيد',
                'paras' => [
                    'يمكن أن يستقبل الموقع زيارات كثيرة من جوجل من دون أن تحقق هذه الزيارات أي قيمة للنشاط. يحدث ذلك عندما يستهدف الموقع كلمات بعيدة عن خدماته، أو عندما يصل الزائر إلى صفحة لا تجيب عن احتياجه ولا تساعده على اتخاذ الخطوة التالية.',
                    'نبدأ بمراجعة الكلمات والصفحات التي تجلب الزيارات حاليًا. نحدد ما إذا كان الزائر يبحث عن معلومة، أو يقارن بين خيارات، أو يريد التواصل وطلب الخدمة. بعد ذلك نحسّن الصفحة بما يناسب هدف البحث، ونربطها بالصفحات والخدمات ذات الصلة.',
                    'كما نراجع طريقة انتقال الزائر داخل الموقع، ووضوح أزرار التواصل، وسهولة استخدام الموقع على الهاتف، لأن الحصول على ترتيب جيد لا يكفي إذا كانت الصفحة لا تساعد الزائر على الاتصال أو إرسال طلب.',
                ],
            ],
            [
                'h2'       => 'كيف نخصص العمل للسوق المصري؟',
                'paras'    => [
                    'يستخدم الجمهور في مصر الفصحى وتعبيرات محلية مختلفة عند البحث. نعتمد على بيانات الكلمات الفعلية لمعرفة الصيغ التي يستخدمها العملاء، لكننا لا نحشو النصوص بعبارات عامية أو كلمات غير طبيعية لمجرد أنها مسجلة في أدوات البحث.',
                    'إذا كان النشاط يخدم القاهرة أو الإسكندرية أو محافظة محددة، نستخدم الاستهداف المحلي عندما تكون هناك خدمة وتغطية حقيقية في هذه المنطقة. ولا ننشئ عشرات الصفحات المتشابهة بأسماء المحافظات من دون محتوى أو اختلاف فعلي.',
                    'وبالنسبة إلى المتاجر الإلكترونية، نراجع صفحات الأقسام والمنتجات والفلاتر والمنتجات غير المتاحة، ونتأكد من وصول جوجل إلى الصفحات التي يمكن أن تحقق مبيعات.',
                ],
                'ecom_link' => true,
            ],
        ],

        // Work scope
        'scope_h2'    => 'ماذا يشمل العمل على موقعك؟',
        'scope_items' => [
            [ 'n' => '01', 'title' => 'مراجعة الوضع الحالي',                 'desc' => 'نفحص الموقع وبيانات جوجل والكلمات والصفحات المنافسة، ونحدد المشكلات التي تؤثر فعلًا في الظهور أو التحويل.' ],
            [ 'n' => '02', 'title' => 'توزيع الكلمات على الصفحات',           'desc' => 'نحدد الصفحة المناسبة لكل كلمة، ونمنع تنافس أكثر من صفحة داخل الموقع على البحث نفسه.' ],
            [ 'n' => '03', 'title' => 'تحسين صفحات الخدمات والمنتجات',       'desc' => 'نراجع العنوان والمحتوى والأسئلة الشائعة والروابط الداخلية ووسائل التواصل، ونقترح ما تحتاجه الصفحة لتصبح أكثر وضوحًا للعميل.' ],
            [ 'n' => '04', 'title' => 'إعداد محتوى يخدم النشاط',              'desc' => 'نختار موضوعات مرتبطة بالخدمات والمنتجات، ونستخدم المقالات للإجابة عن أسئلة العملاء وتوجيههم إلى الصفحات التجارية المناسبة.' ],
            [ 'n' => '05', 'title' => 'متابعة العملاء القادمين من البحث',     'desc' => 'نقيس المكالمات ورسائل واتساب والنماذج وطلبات الشراء، إلى جانب متابعة الكلمات والزيارات والصفحات التي تحقق أفضل أداء.' ],
        ],

        // Process
        'process_h2'    => 'كيف يبدأ العمل مع سيو هاوس؟',
        'process_steps' => [
            [ 'n' => '01', 'title' => 'نفهم النشاط وأهدافه',   'desc' => 'نتعرف على الخدمات أو المنتجات والمناطق المستهدفة ونوع العميل الذي تريد الوصول إليه.' ],
            [ 'n' => '02', 'title' => 'نراجع الموقع والبيانات', 'desc' => 'نفحص الجوانب التقنية والمحتوى والكلمات والصفحات الحالية، ونقارنها بالمواقع المنافسة.' ],
            [ 'n' => '03', 'title' => 'نرتب الأولويات',         'desc' => 'نحدد المهام التي يجب تنفيذها أولًا، والصفحات التي تحتاج إلى تحسين، والمحتوى المطلوب خلال المرحلة المقبلة.' ],
            [ 'n' => '04', 'title' => 'ننفذ ونتابع',            'desc' => 'ننشر التحسينات المتفق عليها أو ننسق تنفيذها مع فريقك، ثم نتابع أثرها ونطور الخطة حسب النتائج.' ],
        ],

        // Team
        'team_desc' => 'يعمل على مشروعك متخصصون في السيو التقني وتحليل الكلمات والمحتوى وقياس الأداء. يتولى كل فرد الجزء المرتبط بخبرته، مع إدارة واضحة للمشروع ومتابعة دورية لما تم تنفيذه.',

        // FAQs
        'faq_h2' => 'أسئلة شائعة عن السيو في مصر',
        'faqs'   => [
            [ 'question' => 'كم يستغرق ظهور نتائج السيو؟',           'answer' => 'تختلف المدة حسب عمر الموقع وحالته وحجم المنافسة. قد تظهر بعض التحسينات خلال أسابيع، بينما تحتاج الكلمات الأكثر تنافسًا إلى عدة أشهر من العمل المستمر.' ],
            [ 'question' => 'هل زيادة الزيارات تعني زيادة العملاء؟',  'answer' => 'ليس بالضرورة. يجب أن تأتي الزيارات من كلمات مناسبة، وأن تصل إلى صفحات تخدم احتياج الزائر وتحتوي على خطوة تواصل واضحة.' ],
            [ 'question' => 'هل تستخدمون الكلمات العامية في المحتوى؟','answer' => 'نراجع الكلمات التي يستخدمها الجمهور بالفعل، لكننا نحافظ على لغة واضحة ومهنية. يمكن استهداف بعض الصيغ المحلية بصورة طبيعية من دون إضعاف جودة المحتوى.' ],
            [ 'question' => 'هل تقدمون السيو للأنشطة المحلية؟',       'answer' => 'نعم. يمكن تحسين ظهور الأنشطة التي تخدم مناطق محددة من خلال صفحات الخدمات وبيانات النشاط والاستهداف المحلي، بشرط وجود خدمة وتغطية فعلية في المنطقة المستهدفة.' ],
            [ 'question' => 'كيف أعرف أن السيو يحقق نتيجة؟',         'answer' => 'نربط الزيارات بالمكالمات والرسائل والنماذج والطلبات قدر الإمكان، ونوضح الصفحات والكلمات التي ساهمت في الوصول إلى العملاء.' ],
        ],

        // CTA
        'cta_heading'   => 'اعرف أين يفقد موقعك فرص الظهور والعملاء',
        'cta_desc'      => 'نراجع موقعك والكلمات التي يظهر فيها والصفحات المنافسة، ثم نوضح لك أهم المشكلات والفرص والخطوة المناسبة للبدء.',
    ],

    // ── Saudi Arabia ─────────────────────────────────────────────────────────
    'saudi-arabia' => [
        'area_served_en'   => 'Saudi Arabia',
        'area_served_wiki' => 'https://en.wikipedia.org/wiki/Saudi_Arabia',
        'name'             => 'السعودية',
        'hero_tag'         => 'تحسين محركات البحث في السعودية',

        // Metadata — set in Rank Math per-page.
        'meta_title' => 'شركة سيو في السعودية | خدمات تحسين محركات البحث | سيو هاوس',
        'meta_desc'  => 'شركة سيو في السعودية تساعدك على تحسين ظهور موقعك وجذب عملاء جدد من جوجل. خدمات متكاملة تشمل السيو التقني والمحتوى والروابط والتحليل.',

        // Hero
        'hero_title' => 'شركة سيو في السعودية لزيادة ظهور موقعك وجذب عملاء جدد',
        'hero_desc'  => 'نساعدك على الوصول إلى الأشخاص الذين يبحثون بالفعل عن خدماتك أو منتجاتك داخل السعودية. يبدأ العمل بمراجعة الموقع واختيار الكلمات المناسبة، ثم تحسين الصفحات والمحتوى وقياس المكالمات والطلبات الناتجة من البحث.',

        // Five services — per-market descriptions
        'services_h2'    => 'خمس خدمات سيو تعالج كل جانب من معادلة الظهور',
        'services_intro' => 'تحسين نتائج الموقع لا يعتمد على تعديل العناوين أو نشر المقالات فقط. قد يحتاج موقعك إلى معالجة مشكلة تقنية، أو تحسين صفحات الخدمات، أو إعداد محتوى جديد، أو تقوية الروابط الداخلية والخارجية. لذلك نجمع الخدمات الأساسية في خطة واحدة مترابطة.',
        'services' => [
            [ 'title' => 'السيو التقني',           'desc' => 'نراجع فهرسة الموقع وسرعته وبنية الروابط والصفحات المكررة وأخطاء الزحف، ونعالج المشكلات التي تمنع جوجل من الوصول إلى الصفحات المهمة أو فهمها بصورة صحيحة.',      'url_key' => 'services/seo/technical',  'svg' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>' ],
            [ 'title' => 'السيو الداخلي',          'desc' => 'نحسّن عناوين الصفحات والمحتوى والعناوين الفرعية والروابط الداخلية، ونتأكد من أن كل صفحة تستهدف كلمة مناسبة وتقدم إجابة واضحة للزائر.',                          'url_key' => 'services/seo/on-page',    'svg' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>' ],
            [ 'title' => 'محتوى السيو',            'desc' => 'نكتب صفحات خدمات ومقالات تجيب عن الأسئلة التي يبحث عنها العملاء، وتساعدهم على فهم الخدمة والانتقال إلى خطوة التواصل أو الشراء.',                                  'url_key' => 'services/seo/content',    'svg' => '<path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>' ],
            [ 'title' => 'بناء الروابط الخارجية', 'desc' => 'نعمل على الحصول على روابط من مواقع ذات صلة ومصداقية، مع مراجعة جودة الروابط الحالية وتجنب المصادر التي قد تضر الموقع.',                                            'url_key' => 'services/seo/backlinks',  'svg' => '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>' ],
            [ 'title' => 'استشارات السيو',         'desc' => 'نقدم مراجعة وخطة تنفيذ واضحة للشركات التي تمتلك فريق تسويق أو تطوير داخليًا وتحتاج إلى توجيه متخصص ومتابعة للنتائج.',                                              'url_key' => 'services/seo/consulting', 'svg' => '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>' ],
        ],

        // Country-specific section
        'country_sections' => [
            [
                'h2'       => 'خدمة سيو تناسب نشاطك داخل السعودية',
                'paras'    => [
                    'طريقة البحث عن مكتب محاماة تختلف عن طريقة البحث عن متجر إلكتروني أو شركة تقدم خدماتها للشركات. لذلك لا نستخدم الخطة نفسها مع كل المواقع، ولا ننشئ صفحات لمجرد استهداف أكبر عدد من الكلمات.',
                    'إذا كان نشاطك يخدم مدينة محددة، نراجع عمليات البحث المرتبطة بالمدينة والخدمات التي تقدمها فيها. وإذا كنت تعمل في جميع أنحاء السعودية، ننظم الموقع حول الخدمات أو المنتجات التي يبحث عنها العملاء، بدل إنشاء صفحات متشابهة بأسماء مدن مختلفة.',
                    'أما المتاجر الإلكترونية، فتحتاج إلى تحسين صفحات الأقسام والمنتجات والفلاتر، إلى جانب معالجة الصفحات المكررة وسهولة وصول جوجل إلى المنتجات المهمة. وينطبق ذلك على المتاجر المبنية على سلة أو زد وغيرها من منصات التجارة الإلكترونية.',
                ],
                'ecom_link' => true,
            ],
        ],

        // Work scope
        'scope_h2'    => 'ماذا نعمل عليه داخل موقعك؟',
        'scope_items' => [
            [ 'n' => '01', 'title' => 'مراجعة الموقع والمنافسين',      'desc' => 'نفحص الوضع الحالي للموقع، والكلمات التي يظهر فيها، والصفحات التي تستقبل الزيارات، والمواقع المنافسة التي تتقدم عليه في نتائج البحث.' ],
            [ 'n' => '02', 'title' => 'اختيار الكلمات والصفحات المناسبة','desc' => 'نختار الكلمات وفقًا لطبيعة الخدمة وقربها من قرار التواصل أو الشراء، ثم نحدد الصفحة التي يجب أن تستهدف كل كلمة حتى لا تتنافس صفحات الموقع مع بعضها.' ],
            [ 'n' => '03', 'title' => 'تحسين الصفحات الحالية',           'desc' => 'نراجع المحتوى والعناوين والروابط الداخلية وعناصر الصفحة، ونوضح ما يحتاج إلى تعديل وما يحتاج إلى إعادة كتابة أو إنشاء صفحة مستقلة.' ],
            [ 'n' => '04', 'title' => 'إعداد المحتوى المطلوب',           'desc' => 'نحدد صفحات الخدمات والمقالات التي يحتاجها الموقع فعلًا. لا ننشر محتوى لمجرد زيادة عدد الصفحات، بل نربط كل موضوع بخدمة أو منتج أو سؤال يبحث عنه العميل.' ],
            [ 'n' => '05', 'title' => 'متابعة النتائج',                  'desc' => 'نتابع الظهور والنقرات والزيارات، لكننا لا نتوقف عند هذه الأرقام. نراجع أيضًا المكالمات ورسائل واتساب والنماذج وطلبات الشراء القادمة من البحث لمعرفة الصفحات التي تحقق قيمة فعلية.' ],
        ],

        // Process
        'process_h2'    => 'كيف يبدأ العمل مع سيو هاوس؟',
        'process_steps' => [
            [ 'n' => '01', 'title' => 'مراجعة الموقع',              'desc' => 'نبدأ بفحص الموقع وبيانات أدوات التحليل والبحث، ونحدد المشكلات والفرص التي تستحق الأولوية.' ],
            [ 'n' => '02', 'title' => 'إعداد خطة التنفيذ',          'desc' => 'نرتب المهام وفقًا لتأثيرها، ونحدد الصفحات والكلمات والمحتوى المطلوب خلال المرحلة المقبلة.' ],
            [ 'n' => '03', 'title' => 'تنفيذ التحسينات',            'desc' => 'ينفذ فريقنا التحسينات المتفق عليها، أو ينسق مع فريق التطوير والمحتوى لدى شركتك إذا كانت التعديلات تحتاج إلى تنفيذ داخلي.' ],
            [ 'n' => '04', 'title' => 'قياس النتائج وتطوير الخطة', 'desc' => 'نراجع النتائج شهريًا، ونوضح ما تم تنفيذه وما تغير وما سنعمل عليه بعد ذلك. وتتطور الخطة بناءً على البيانات الفعلية، وليس على قائمة مهام ثابتة.' ],
        ],

        // Team
        'team_desc' => 'يعمل على مشروعك فريق متخصص في السيو التقني وتحليل الكلمات وتحسين المحتوى وقياس الأداء. كل جزء من العمل يتولاه الشخص المناسب له، مع متابعة واحدة واضحة للمشروع والنتائج.',

        // FAQs
        'faq_h2' => 'أسئلة شائعة عن السيو في السعودية',
        'faqs'   => [
            [ 'question' => 'كم يحتاج السيو حتى تظهر نتائجه؟',            'answer' => 'تختلف المدة حسب حالة الموقع والمنافسة وحجم التعديلات المطلوبة. قد تبدأ بعض التحسينات في الظهور خلال أسابيع، بينما تحتاج الكلمات الأكثر تنافسًا إلى عدة أشهر من العمل المستمر.' ],
            [ 'question' => 'هل تضمنون ظهور الموقع في النتيجة الأولى؟',  'answer' => 'لا توجد شركة تستطيع ضمان ترتيب محدد في جوجل. ما يمكننا الالتزام به هو تنفيذ العمل وفقًا للبيانات وأفضل الممارسات، وقياس تطور الظهور والزيارات والعملاء المحتملين بوضوح.' ],
            [ 'question' => 'هل تستهدفون الكلمات العربية فقط؟',            'answer' => 'نبدأ باللغة التي يستخدمها جمهورك. إذا أثبت البحث وجود طلب مناسب باللغة الإنجليزية، نحدد الصفحات التي تستحق الاستهداف دون إنشاء نسخة إنجليزية كاملة بلا حاجة.' ],
            [ 'question' => 'هل تقدمون خدمات السيو للمتاجر الإلكترونية؟', 'answer' => 'نعم. نعمل على صفحات الأقسام والمنتجات والمحتوى التجاري والمشكلات التقنية والصفحات المكررة، مع متابعة الزيارات وطلبات الشراء القادمة من البحث.' ],
            [ 'question' => 'كيف تقيسون نجاح العمل؟',                      'answer' => 'نعتمد على مجموعة من المؤشرات، منها تحسن الكلمات والصفحات، وزيادة الزيارات المناسبة، وعدد المكالمات والرسائل والنماذج والمبيعات التي يمكن تتبعها من البحث المجاني.' ],
        ],

        // CTA
        'cta_heading'   => 'ابدأ بمراجعة واضحة لموقعك',
        'cta_desc'      => 'نراجع موقعك والكلمات التي يظهر فيها والمنافسين المتقدمين عليه، ثم نوضح لك أهم فرص التحسين والخطوة المناسبة للبدء.',
    ],

    // ── UAE ──────────────────────────────────────────────────────────────────
    'uae' => [
        'area_served_en'   => 'United Arab Emirates',
        'area_served_wiki' => 'https://en.wikipedia.org/wiki/United_Arab_Emirates',
        'name'             => 'الإمارات',
        'hero_tag'         => 'تحسين محركات البحث في الإمارات',

        // Metadata — set in Rank Math per-page.
        'meta_title' => 'شركة سيو في الإمارات | خدمات تحسين محركات البحث | سيو هاوس',
        'meta_desc'  => 'شركة سيو في الإمارات تساعدك على تحسين ظهور موقعك أمام العملاء بالعربية أو الإنجليزية، مع خدمات متكاملة للشركات والمتاجر.',

        // Hero
        'hero_title' => 'شركة سيو في الإمارات لتحسين ظهور موقعك أمام عملائك',
        'hero_desc'  => 'نساعدك على الوصول إلى الأشخاص الذين يبحثون عن خدماتك أو منتجاتك داخل الإمارات. نحدد الكلمات والصفحات المناسبة لكل جمهور، ثم نحسّن الموقع والمحتوى والجوانب التقنية ونقيس المكالمات والطلبات الناتجة من البحث.',

        // Five services — per-market descriptions
        'services_h2'    => 'خمس خدمات سيو تعمل معًا لتحسين موقعك',
        'services_intro' => 'لا تعتمد النتائج على إجراء واحد. قد يحتاج الموقع إلى إصلاحات تقنية، أو صفحات أقوى للخدمات، أو محتوى جديد، أو روابط تعزز موثوقيته. لذلك نحدد احتياج الموقع أولًا، ثم نجمع الخدمات المناسبة في خطة تنفيذ واحدة.',
        'services' => [
            [ 'title' => 'السيو التقني',           'desc' => 'نراجع فهرسة الصفحات وسرعة الموقع وبنية الروابط والصفحات المكررة، ونتأكد من سهولة وصول جوجل إلى النسخ العربية والإنجليزية.',                                            'url_key' => 'services/seo/technical',  'svg' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>' ],
            [ 'title' => 'السيو الداخلي',          'desc' => 'نحسّن عناوين الصفحات ومحتواها وروابطها الداخلية، ونحدد الصفحة التي يجب أن تستهدف كل خدمة أو كلمة.',                                                                    'url_key' => 'services/seo/on-page',    'svg' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>' ],
            [ 'title' => 'محتوى السيو',            'desc' => 'نعد صفحات ومقالات تناسب ما يبحث عنه الجمهور، مع الحفاظ على رسالة واضحة للنشاط وربط المحتوى بالخدمات أو المنتجات.',                                                     'url_key' => 'services/seo/content',    'svg' => '<path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>' ],
            [ 'title' => 'بناء الروابط الخارجية', 'desc' => 'نختار فرص روابط مناسبة لطبيعة النشاط والسوق، ونركز على جودة المواقع وصلتها بالمجال بدل زيادة عدد الروابط فقط.',                                                         'url_key' => 'services/seo/backlinks',  'svg' => '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>' ],
            [ 'title' => 'استشارات السيو',         'desc' => 'نراجع قرارات المحتوى والتطوير وتوسعات الموقع، ونساعد فريقك على تنفيذ التوصيات بصورة صحيحة ومتابعة أثرها.',                                                              'url_key' => 'services/seo/consulting', 'svg' => '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>' ],
        ],

        // Country-specific sections
        'country_sections' => [
            [
                'h2'    => 'استهداف واضح لكل لغة ومنطقة',
                'paras' => [
                    'بعض الشركات في الإمارات تخاطب جمهورًا عربيًا، وبعضها يعتمد بصورة أكبر على الباحثين باللغة الإنجليزية، بينما تحتاج أنشطة أخرى إلى اللغتين. القرار لا يُبنى على افتراض عام، بل على طبيعة النشاط والعملاء وبيانات البحث.',
                    'عندما يحتاج الموقع إلى العربية والإنجليزية، نحدد الكلمات والصفحات المطلوبة لكل لغة بصورة مستقلة. لا نكتفي بترجمة النص نفسه، لأن طريقة البحث وصياغة الخدمات قد تختلف بين الجمهورين.',
                    'كذلك لا نستخدم اسم دبي أو أبوظبي أو أي إمارة أخرى في صفحات متكررة لمجرد استهداف المدينة. ننشئ صفحة محلية فقط عندما تكون هناك خدمة فعلية أو فرع أو معلومات تختلف وتفيد العميل في هذه المنطقة.',
                ],
            ],
            [
                'h2'       => 'منافسة محلية ودولية داخل السوق الإماراتي',
                'paras'    => [
                    'قد ينافس موقعك شركات محلية، أو علامات دولية، أو أدلة ومنصات كبيرة تظهر في نتائج البحث. لذلك نراجع من يتصدر كل مجموعة من الكلمات، ونحدد نوع الصفحات والمحتوى الذي يحتاجه موقعك للمنافسة.',
                    'بالنسبة إلى الشركات التي تقدم خدمات مهنية، نهتم بوضوح التخصص والخبرة والقطاعات التي تخدمها وطرق التواصل. وبالنسبة إلى المتاجر، نركز على صفحات الأقسام والمنتجات وسهولة التصفح والفهرسة والمحتوى الذي يساعد العميل على اتخاذ قرار الشراء.',
                ],
                'ecom_link' => true,
            ],
        ],

        // Work scope
        'scope_h2'    => 'ماذا نعمل عليه داخل موقعك؟',
        'scope_items' => [
            [ 'n' => '01', 'title' => 'مراجعة النسختين العربية والإنجليزية', 'desc' => 'نفحص الصفحات الموجودة في كل لغة، ونحدد الصفحات الناقصة أو المكررة، ونتأكد من الربط الصحيح بين الصفحات المتقابلة.' ],
            [ 'n' => '02', 'title' => 'تحديد الكلمات حسب الجمهور',           'desc' => 'نوزع الكلمات وفقًا للغة والمنطقة ونوع العميل، ونمنع استخدام الصفحة نفسها لاستهداف موضوعات أو جماهير مختلفة.' ],
            [ 'n' => '03', 'title' => 'تحسين صفحات الخدمات والمنتجات',       'desc' => 'نراجع المحتوى والعناوين والأسئلة الشائعة والروابط الداخلية وعناصر التواصل، ونعالج النقاط التي تمنع الزائر من فهم العرض أو الانتقال إلى الخطوة التالية.' ],
            [ 'n' => '04', 'title' => 'تحسين الظهور المحلي',                  'desc' => 'إذا كان النشاط يخدم منطقة محددة، نراجع صفحات الموقع وبيانات النشاط والمعلومات المحلية التي تساعد العميل وجوجل على فهم نطاق الخدمة.' ],
            [ 'n' => '05', 'title' => 'قياس المكالمات والطلبات',              'desc' => 'نتابع المكالمات والرسائل والنماذج وعمليات الشراء القادمة من البحث، ونستخدم البيانات لمعرفة الصفحات والكلمات التي تحقق أفضل عائد.' ],
        ],

        // Process
        'process_h2'    => 'كيف يبدأ العمل مع سيو هاوس؟',
        'process_steps' => [
            [ 'n' => '01', 'title' => 'نحدد الجمهور المستهدف',   'desc' => 'نتعرف على المناطق واللغات والخدمات والمنتجات التي تمثل أولوية للنشاط.' ],
            [ 'n' => '02', 'title' => 'نراجع الموقع والمنافسين', 'desc' => 'نفحص الموقع من الناحية التقنية والمحتوى والظهور الحالي، ونحلل الصفحات والمواقع التي تتقدم عليه.' ],
            [ 'n' => '03', 'title' => 'نعد خطة واضحة',           'desc' => 'نحدد الصفحات المطلوب تحسينها أو إنشاؤها، والمشكلات التقنية، والمحتوى والروابط المطلوبة، وترتيب تنفيذ كل مهمة.' ],
            [ 'n' => '04', 'title' => 'ننفذ ونقيس',              'desc' => 'ننشر التحسينات أو ننسقها مع فريقك، ثم نتابع الظهور والزيارات والعملاء المحتملين ونعدل الخطة بناءً على النتائج.' ],
        ],

        // Team
        'team_desc' => 'يتولى المشروع فريق متخصص في الجوانب التقنية وتحليل الكلمات والمحتوى وقياس الأداء. نوزع العمل حسب التخصص، مع مسؤول واضح عن متابعة التنفيذ والتواصل والنتائج.',

        // FAQs
        'faq_h2' => 'أسئلة شائعة عن السيو في الإمارات',
        'faqs'   => [
            [ 'question' => 'هل يجب أن يكون الموقع بالعربية والإنجليزية؟', 'answer' => 'ليس دائمًا. يعتمد القرار على جمهورك وطبيعة نشاطك. نراجع بيانات البحث والفرص المتاحة في كل لغة قبل تحديد الصفحات التي تحتاج إليها.' ],
            [ 'question' => 'هل يمكن استهداف دبي وأبوظبي في الموقع نفسه؟', 'answer' => 'نعم، إذا كان نشاطك يقدم خدماته فعلًا في المنطقتين. يجب أن تقدم الصفحات معلومات مفيدة ومختلفة، وليس النص نفسه مع تغيير اسم المدينة.' ],
            [ 'question' => 'هل تحتاج كل لغة إلى محتوى مستقل؟',            'answer' => 'في أغلب الحالات نعم. يمكن أن تتشابه معلومات الخدمة، لكن الكلمات وطريقة البحث وصياغة الرسالة تختلف، لذلك تحتاج كل لغة إلى مراجعة وتحسين مستقلين.' ],
            [ 'question' => 'هل تقدمون خدمات السيو المحلي؟',                'answer' => 'نعم. نراجع صفحات المناطق وبيانات النشاط ووسائل التواصل والمعلومات المحلية، ونساعد الموقع على الظهور بصورة أوضح أمام الباحثين داخل المنطقة المستهدفة.' ],
            [ 'question' => 'كيف تقيسون نتائج المشروع؟',                     'answer' => 'نتابع الكلمات والزيارات والصفحات، إلى جانب المكالمات والرسائل والنماذج والمبيعات القادمة من البحث، حتى يكون قياس النجاح مرتبطًا بهدف النشاط.' ],
        ],

        // CTA
        'cta_heading'   => 'ابدأ بخطة سيو تناسب جمهورك في الإمارات',
        'cta_desc'      => 'نراجع موقعك واللغات والمناطق التي تستهدفها والمنافسين المتقدمين عليك، ثم نوضح لك فرص التحسين وأولويات التنفيذ.',
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

// Allow ACF field overrides on hero text only
$hero_tag   = sh_field( 'service_hero_tag' )  ?: $c['hero_tag'];
$hero_title = sh_field( 'service_hero_title' ) ?: $c['hero_title'];
$hero_desc  = sh_field( 'service_hero_desc' )  ?: $c['hero_desc'];
$faqs       = sh_field( 'service_faqs' );
$faq_data   = is_array( $faqs ) && count( array_filter( $faqs, fn( $r ) => ! empty( $r['question'] ) ) ) ? $faqs : $c['faqs'];

// URL helpers
$services_url   = sh_page_url( 'services' );
$seo_url        = sh_page_url( 'services/seo' );
$ecommerce_url  = sh_page_url( 'sectors/ecommerce' );
$contact_url    = sh_page_url( 'contact' );

// Team members — full roster, all published, visibility-aware
$team_query = new WP_Query( [
    'post_type'      => 'team_member',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'post_status'    => 'publish',
    'meta_query'     => [
        'relation' => 'OR',
        [ 'key' => 'is_visible', 'value' => '1', 'compare' => '=' ],
        [ 'key' => 'is_visible', 'compare' => 'NOT EXISTS' ],
    ],
] );

$reviews_sc = sh_option( 'reviews_shortcode' );
?>

<main class="svc-seo-country">

<!-- ① Hero -->
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
        <a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-p lg">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          اطلب مراجعة موقعك
        </a>
        <a href="#country-services" class="btn btn-g lg">تعرّف على خدماتنا</a>
      </div>
    </div>
  </div>
</section>

<?php
// Client strip — only when actual approved logos exist.
$_clients_check = sh_get_clients();
if ( $_clients_check->have_posts() ) :
    get_template_part( 'template-parts/sections/clients' );
endif;
wp_reset_postdata();
?>

<!-- ② Five linked SEO services — all markets -->
<section id="country-services" class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr">
      <h2 class="h2"><?php echo esc_html( $c['services_h2'] ); ?></h2>
      <p class="bod" style="margin-top:12px;max-width:660px;margin-inline:auto"><?php echo esc_html( $c['services_intro'] ); ?></p>
    </div>
    <div class="lp-five-grid sr d1">
      <?php foreach ( $c['services'] as $svc ) :
          $svc_url = sh_page_url( $svc['url_key'] );
          $is_link = ! empty( $svc_url );
          $ftag    = $is_link ? 'a' : 'div';
          $fhref   = $is_link ? ' href="' . esc_url( $svc_url ) . '"' : '';
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

<!-- ③ Country-specific sections -->
<?php foreach ( $c['country_sections'] as $cs_idx => $cs ) :
    $bg = ( $cs_idx % 2 === 0 ) ? 'sec-white' : 'sec-off';
?>
<section class="sec <?php echo $bg; ?>">
  <div class="wrap">
    <div class="sh sr" style="max-width:720px">
      <h2 class="h2"><?php echo esc_html( $cs['h2'] ); ?></h2>
      <?php foreach ( $cs['paras'] as $para ) : ?>
      <p class="bod" style="margin-top:16px"><?php echo esc_html( $para ); ?></p>
      <?php endforeach; ?>
      <?php if ( ! empty( $cs['ecom_link'] ) ) : ?>
      <p style="margin-top:20px">
        <a href="<?php echo esc_url( $ecommerce_url ); ?>" class="inline-link">
          تعرّف على خدمة سيو المتاجر الإلكترونية
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="vertical-align:middle;margin-inline-start:4px"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
      </p>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endforeach; ?>

<!-- ④ Work scope -->
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr">
      <h2 class="h2"><?php echo esc_html( $c['scope_h2'] ); ?></h2>
    </div>
    <div class="steps-list sr d1" style="margin-top:32px;max-width:800px;margin-inline:auto">
      <?php foreach ( $c['scope_items'] as $si ) : ?>
      <div class="step-item">
        <div class="step-num"><?php echo esc_html( $si['n'] ); ?></div>
        <div class="step-body">
          <h3><?php echo esc_html( $si['title'] ); ?></h3>
          <p><?php echo esc_html( $si['desc'] ); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ⑤ Process -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="sh c sr">
      <h2 class="h2"><?php echo esc_html( $c['process_h2'] ); ?></h2>
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
  </div>
</section>

<!-- ⑥ Team — complete roster, no carousel -->
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh c sr">
      <span class="tag">الفريق</span>
      <h2 class="h2">فريق سيو هاوس</h2>
      <p class="bod" style="margin-top:12px;max-width:600px;margin-inline:auto"><?php echo esc_html( $c['team_desc'] ); ?></p>
    </div>
    <?php if ( $team_query->have_posts() ) : ?>
    <div class="lp-team-grid sr d1">
      <?php
      $delay_classes = [ '', ' d1', ' d2', ' d3', ' d4', ' d5' ];
      $tm_idx = 0;
      while ( $team_query->have_posts() ) :
          $team_query->the_post();
          $delay = $delay_classes[ $tm_idx % count( $delay_classes ) ];
          get_template_part( 'template-parts/cards/team-card', null, [ 'delay_class' => ltrim( $delay ) ] );
          $tm_idx++;
      endwhile;
      wp_reset_postdata();
      ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php // Reviews — conditional ?>
<?php if ( ! empty( $reviews_sc ) ) : ?>
<section class="sec sec-white">
  <div class="wrap">
    <?php echo do_shortcode( $reviews_sc ); ?>
  </div>
</section>
<?php endif; ?>

<!-- ⑦ FAQ -->
<section id="country-faq" class="sec sec-white">
  <div class="wrap">
    <div class="sh sr">
      <h2 class="h2"><?php echo esc_html( $c['faq_h2'] ); ?></h2>
    </div>
    <div class="faq-list sr d1">
      <?php foreach ( $faq_data as $faq ) : ?>
      <div class="faq-item">
        <div class="faq-q">
          <span><?php echo esc_html( $faq['question'] ?? '' ); ?></span>
          <div class="faq-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></div>
        </div>
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

<!-- ⑧ CTA Banner -->
<?php
get_template_part( 'template-parts/layout/cta-banner', null, [
    'title'       => $c['cta_heading'],
    'description' => $c['cta_desc'],
    'buttons'     => [
        [ 'text' => 'اطلب مراجعة موقعك', 'url' => $contact_url, 'class' => 'btn-w lg' ],
        [ 'text' => 'تواصل معنا',         'url' => $contact_url, 'class' => 'btn-g lg' ],
    ],
] );
?>

</main><!-- /.svc-seo-country -->

<?php get_footer(); ?>
