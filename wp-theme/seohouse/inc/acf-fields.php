<?php
defined( 'ABSPATH' ) || exit;

// Only register ACF fields if ACF Pro is active
add_action( 'acf/init', function () {

    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    // ═══════════════════════════════════════════════════════════
    // Theme Options Pages
    // ═══════════════════════════════════════════════════════════
    if ( function_exists( 'acf_add_options_page' ) ) {

        acf_add_options_page( [
            'page_title'  => 'إعدادات الموقع',
            'menu_title'  => 'إعدادات الموقع',
            'menu_slug'   => 'seohouse-options',
            'capability'  => 'manage_options',
            'icon_url'    => 'dashicons-admin-site-alt3',
            'position'    => 3,
            'redirect'    => false,
        ] );

        acf_add_options_sub_page( [
            'page_title'  => 'الهوية والتواصل',
            'menu_title'  => 'الهوية والتواصل',
            'menu_slug'   => 'seohouse-brand',
            'parent_slug' => 'seohouse-options',
        ] );

        acf_add_options_sub_page( [
            'page_title'  => 'إعدادات الصفحة الرئيسية',
            'menu_title'  => 'الصفحة الرئيسية',
            'menu_slug'   => 'seohouse-homepage',
            'parent_slug' => 'seohouse-options',
        ] );

        acf_add_options_sub_page( [
            'page_title'  => 'إعدادات الفوتر',
            'menu_title'  => 'الفوتر',
            'menu_slug'   => 'seohouse-footer',
            'parent_slug' => 'seohouse-options',
        ] );

        acf_add_options_sub_page( [
            'page_title'  => 'إعدادات صفحة النتائج',
            'menu_title'  => 'صفحة النتائج',
            'menu_slug'   => 'seohouse-results',
            'parent_slug' => 'seohouse-options',
        ] );

        acf_add_options_sub_page( [
            'page_title'  => 'إعدادات أرشيف القطاعات',
            'menu_title'  => 'أرشيف القطاعات',
            'menu_slug'   => 'seohouse-sectors-archive',
            'parent_slug' => 'seohouse-options',
        ] );

        acf_add_options_sub_page( [
            'page_title'  => 'إعدادات صفحة التواصل',
            'menu_title'  => 'صفحة التواصل',
            'menu_slug'   => 'seohouse-contact',
            'parent_slug' => 'seohouse-options',
        ] );

    }

    // ═══════════════════════════════════════════════════════════
    // Theme Options — Brand & Contact
    // ═══════════════════════════════════════════════════════════
    acf_add_local_field_group( [
        'key'      => 'group_brand_contact',
        'title'    => 'الهوية والتواصل',
        'location' => [ [ [ 'param' => 'options_page', 'operator' => '==', 'value' => 'seohouse-brand' ] ] ],
        'fields'   => [
            [ 'key' => 'field_logo_white',       'label' => 'الشعار (أبيض / للخلفيات الداكنة)', 'name' => 'logo_white',       'type' => 'image', 'return_format' => 'array' ],
            [ 'key' => 'field_logo_color',       'label' => 'الشعار (ملوّن / للخلفيات الفاتحة)', 'name' => 'logo_color',       'type' => 'image', 'return_format' => 'array' ],
            [ 'key' => 'field_contact_email',    'label' => 'البريد الإلكتروني',               'name' => 'contact_email',    'type' => 'email' ],
            [ 'key' => 'field_contact_phone',    'label' => 'رقم الهاتف / واتساب',             'name' => 'contact_phone',    'type' => 'text' ],
            [ 'key' => 'field_social_twitter',   'label' => 'رابط تويتر / X',                  'name' => 'social_twitter',   'type' => 'url' ],
            [ 'key' => 'field_social_linkedin',  'label' => 'رابط لينكد إن',                   'name' => 'social_linkedin',  'type' => 'url' ],
            [ 'key' => 'field_social_instagram', 'label' => 'رابط إنستغرام',                   'name' => 'social_instagram', 'type' => 'url' ],
            [ 'key' => 'field_footer_desc',      'label' => 'وصف الشركة في الفوتر',            'name' => 'footer_desc',      'type' => 'textarea', 'rows' => 3 ],
            [ 'key' => 'field_footer_copyright', 'label' => 'نص حقوق النشر',                   'name' => 'footer_copyright', 'type' => 'text' ],
            [ 'key' => 'field_global_cta_title', 'label' => 'عنوان CTA العام',                  'name' => 'global_cta_title', 'type' => 'text' ],
            [ 'key' => 'field_global_cta_desc',  'label' => 'وصف CTA العام',                   'name' => 'global_cta_desc',  'type' => 'textarea', 'rows' => 2 ],
            [ 'key' => 'field_consult_duration', 'label' => 'مدة الاستشارة المجانية',           'name' => 'consult_duration', 'type' => 'text', 'default_value' => '30 دقيقة' ],
            [ 'key' => 'field_footer_col1_heading', 'label' => 'عنوان عمود الفوتر 1',  'name' => 'footer_col1_heading', 'type' => 'text', 'default_value' => 'تحسين محركات البحث' ],
            [ 'key' => 'field_footer_col2_heading', 'label' => 'عنوان عمود الفوتر 2',  'name' => 'footer_col2_heading', 'type' => 'text', 'default_value' => 'تصميم المواقع' ],
            [ 'key' => 'field_footer_col3_heading', 'label' => 'عنوان عمود الفوتر 3',  'name' => 'footer_col3_heading', 'type' => 'text', 'default_value' => 'إنشاء المتاجر' ],
            [ 'key' => 'field_footer_col4_heading', 'label' => 'عنوان عمود الفوتر 4',  'name' => 'footer_col4_heading', 'type' => 'text', 'default_value' => 'الشركة' ],
        ],
    ] );

    // ═══════════════════════════════════════════════════════════
    // Homepage Settings
    // ═══════════════════════════════════════════════════════════
    acf_add_local_field_group( [
        'key'      => 'group_homepage',
        'title'    => 'إعدادات الصفحة الرئيسية',
        'location' => [ [ [ 'param' => 'options_page', 'operator' => '==', 'value' => 'seohouse-homepage' ] ] ],
        'fields'   => [
            [ 'key' => 'field_hero_headline',    'label' => 'العنوان الرئيسي للهيرو',    'name' => 'hero_headline',    'type' => 'textarea', 'rows' => 2, 'default_value' => "كن آخر نقرة\nيبحث عنها عميلك" ],
            [ 'key' => 'field_hero_emphasis',    'label' => 'الكلمة المتدرجة (باللون)',  'name' => 'hero_emphasis',    'type' => 'text',     'default_value' => 'آخر نقرة' ],
            [ 'key' => 'field_hero_subtext',     'label' => 'النص التعريفي في الهيرو',   'name' => 'hero_subtext',     'type' => 'textarea', 'rows' => 2, 'default_value' => 'نضع موقعك أمام العملاء الذين يبحثون عمّا تقدّمه — بدقة، بأدلة، وبنتائج قابلة للقياس.' ],
            [ 'key' => 'field_hero_cta_primary', 'label' => 'نص الزر الرئيسي',          'name' => 'hero_cta_primary', 'type' => 'text',     'default_value' => 'احجز استشارة مجانية 30 دقيقة' ],
            [ 'key' => 'field_why_title',        'label' => 'عنوان قسم "لماذا نحن"',    'name' => 'why_title',        'type' => 'textarea', 'rows' => 2, 'default_value' => "نعمل بمنطق الأداء،\nلا بمنطق الوعود" ],
            [ 'key' => 'field_why_text',         'label' => 'وصف قسم "لماذا نحن"',      'name' => 'why_text',         'type' => 'textarea', 'rows' => 3 ],
            [
                'key'          => 'field_process_steps',
                'label'        => 'خطوات العملية',
                'name'         => 'process_steps',
                'type'         => 'repeater',
                'min'          => 4,
                'max'          => 6,
                'button_label' => 'إضافة خطوة',
                'sub_fields'   => [
                    [ 'key' => 'field_step_title', 'label' => 'عنوان الخطوة', 'name' => 'step_title', 'type' => 'text' ],
                    [ 'key' => 'field_step_desc',  'label' => 'وصف الخطوة',  'name' => 'step_desc',  'type' => 'textarea', 'rows' => 2 ],
                ],
            ],
            [ 'key' => 'field_reviews_score', 'label' => 'التقييم الإجمالي (مثال: 4.9)', 'name' => 'reviews_score', 'type' => 'text', 'default_value' => '4.9' ],
            [ 'key' => 'field_reviews_count', 'label' => 'عدد المراجعات (نص مثال: +50 تقييم)', 'name' => 'reviews_count', 'type' => 'text', 'default_value' => '+50 تقييم' ],
            [ 'key' => 'field_services_section_title', 'label' => 'قسم الخدمات — العنوان',   'name' => 'services_section_title', 'type' => 'text', 'default_value' => 'خدمات تبني وجودك الرقمي' ],
            [ 'key' => 'field_services_section_desc',  'label' => 'قسم الخدمات — الوصف',    'name' => 'services_section_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'من تحسين محركات البحث إلى إنشاء المتاجر — نغطي كل ما يحتاجه عملك.' ],
            [ 'key' => 'field_industries_section_title', 'label' => 'قسم القطاعات — العنوان', 'name' => 'industries_section_title', 'type' => 'text', 'default_value' => 'خبرة عملية في قطاعك' ],
            [ 'key' => 'field_industries_section_desc',  'label' => 'قسم القطاعات — الوصف',  'name' => 'industries_section_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'استراتيجيتنا مبنية على خصوصية نشاطك — لا على قوالب جاهزة.' ],
            [ 'key' => 'field_process_section_title', 'label' => 'قسم كيف نعمل — العنوان',   'name' => 'process_section_title', 'type' => 'text', 'default_value' => 'منهجية واضحة من البداية للنتيجة' ],
            [ 'key' => 'field_process_section_desc',  'label' => 'قسم كيف نعمل — الوصف',    'name' => 'process_section_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'خطوات محددة لكل مشروع حتى نصل إلى نتائج مقاسة.' ],
            [ 'key' => 'field_hp_cta_title', 'label' => 'CTA النهائي — العنوان',              'name' => 'hp_cta_title', 'type' => 'text', 'default_value' => 'موقعك يستحق أن يُرى' ],
            [ 'key' => 'field_hp_cta_desc',  'label' => 'CTA النهائي — الوصف',               'name' => 'hp_cta_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'خصص 30 دقيقة معنا — وسنخبرك بصدق أين أنت وأين يمكن أن تكون.' ],
            [ 'key' => 'field_hp_hero_live_badge',    'label' => 'الهيرو — شريط "نشط الآن"',     'name' => 'hp_hero_live_badge',    'type' => 'text',     'default_value' => 'نعمل الآن على مشاريع تحسين نشطة' ],
            [ 'key' => 'field_hp_hero_cta_secondary', 'label' => 'الهيرو — نص زر CTA الثانوي',   'name' => 'hp_hero_cta_secondary', 'type' => 'text',     'default_value' => 'تعرّف على خدماتنا' ],
            [ 'key' => 'field_hp_cases_title',        'label' => 'قسم النتائج — العنوان',         'name' => 'hp_cases_title',        'type' => 'text',     'default_value' => 'الأرقام تتكلم' ],
            [ 'key' => 'field_hp_cases_desc',         'label' => 'قسم النتائج — الوصف',          'name' => 'hp_cases_desc',         'type' => 'textarea', 'rows' => 2, 'default_value' => 'نماذج من مشاريع نفّذناها — الأرقام الفعلية تُضاف بعد موافقة العملاء.' ],
            [
                'key'          => 'field_hp_why_points',
                'label'        => 'نقاط "لماذا نحن" (4 نقاط)',
                'name'         => 'hp_why_points',
                'type'         => 'repeater',
                'min'          => 4,
                'max'          => 4,
                'button_label' => 'إضافة نقطة',
                'sub_fields'   => [
                    [ 'key' => 'field_wp_title', 'label' => 'العنوان', 'name' => 'wp_title', 'type' => 'text' ],
                    [ 'key' => 'field_wp_desc',  'label' => 'الوصف',  'name' => 'wp_desc',  'type' => 'textarea', 'rows' => 2 ],
                ],
            ],
        ],
    ] );

    // ═══════════════════════════════════════════════════════════
    // Case Study Fields
    // ═══════════════════════════════════════════════════════════
    acf_add_local_field_group( [
        'key'      => 'group_case_study',
        'title'    => 'بيانات دراسة الحالة',
        'location' => [ [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'case_study' ] ] ],
        'fields'   => [
            [ 'key' => 'field_cs_client_name', 'label' => 'اسم العميل', 'name' => 'client_name', 'type' => 'text' ],
            [ 'key' => 'field_cs_challenge',   'label' => 'التحدي',     'name' => 'challenge',   'type' => 'textarea', 'rows' => 4 ],
            [ 'key' => 'field_cs_solution',    'label' => 'الحل / ما قدّمناه', 'name' => 'solution', 'type' => 'textarea', 'rows' => 4 ],
            [ 'key' => 'field_cs_result',      'label' => 'النتيجة',    'name' => 'result',      'type' => 'textarea', 'rows' => 4 ],
            [
                'key'          => 'field_cs_metrics',
                'label'        => 'مؤشرات الأداء',
                'name'         => 'metrics',
                'type'         => 'repeater',
                'max'          => 4,
                'button_label' => 'إضافة مؤشر',
                'sub_fields'   => [
                    [ 'key' => 'field_metric_label', 'label' => 'التسمية',  'name' => 'label', 'type' => 'text' ],
                    [ 'key' => 'field_metric_value', 'label' => 'القيمة',   'name' => 'value', 'type' => 'text' ],
                ],
            ],
            [ 'key' => 'field_cs_gallery',          'label' => 'صور المشروع (جاليري)', 'name' => 'gallery',          'type' => 'gallery', 'return_format' => 'array' ],
            [ 'key' => 'field_cs_cta_url',          'label' => 'رابط المشروع (اختياري)', 'name' => 'cta_url',          'type' => 'url' ],
            [ 'key' => 'field_cs_show_on_homepage', 'label' => 'عرض في الصفحة الرئيسية؟', 'name' => 'show_on_homepage', 'type' => 'true_false', 'default_value' => 0 ],
            [ 'key' => 'field_cs_duration',         'label' => 'مدة المشروع',            'name' => 'project_duration', 'type' => 'text', 'placeholder' => 'مثال: 6 أشهر' ],
            [
                'key'        => 'field_cs_chart_bars',
                'label'      => 'أعمدة الرسم البياني (10 قيم 0-100)',
                'name'       => 'chart_bars',
                'type'       => 'repeater',
                'min'        => 10,
                'max'        => 10,
                'instructions' => 'أدخل 10 قيم بين 0 و 100 لرسم الشارت التصويري.',
                'button_label' => 'إضافة قيمة',
                'sub_fields'   => [
                    [ 'key' => 'field_bar_height', 'label' => 'ارتفاع العمود %', 'name' => 'bar_height', 'type' => 'range', 'min' => 0, 'max' => 100 ],
                ],
            ],
        ],
    ] );

    // ═══════════════════════════════════════════════════════════
    // Team Member Fields
    // ═══════════════════════════════════════════════════════════
    acf_add_local_field_group( [
        'key'      => 'group_team_member',
        'title'    => 'بيانات عضو الفريق',
        'location' => [ [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'team_member' ] ] ],
        'fields'   => [
            [ 'key' => 'field_tm_job_title',     'label' => 'المسمى الوظيفي',     'name' => 'job_title',     'type' => 'text' ],
            [ 'key' => 'field_tm_role_badge',    'label' => 'تصنيف الدور (شعار صغير مثل: سيو، محتوى)', 'name' => 'role_badge', 'type' => 'text' ],
            [ 'key' => 'field_tm_short_bio',     'label' => 'نبذة قصيرة',         'name' => 'short_bio',     'type' => 'textarea', 'rows' => 3 ],
            [ 'key' => 'field_tm_linkedin',      'label' => 'رابط لينكد إن',      'name' => 'linkedin_url',  'type' => 'url' ],
            [ 'key' => 'field_tm_email',         'label' => 'البريد الإلكتروني',   'name' => 'member_email',  'type' => 'email' ],
            [ 'key' => 'field_tm_skills',        'label' => 'المهارات / التخصصات (تظهر كـ chips)', 'name' => 'skills', 'type' => 'textarea', 'instructions' => 'كل مهارة في سطر منفصل', 'rows' => 4 ],
            [ 'key' => 'field_tm_expertise',     'label' => 'سنوات الخبرة / خبرة مختصرة',              'name' => 'expertise',     'type' => 'text', 'placeholder' => 'مثال: 7+ سنوات في سيو المتاجر' ],
            [ 'key' => 'field_tm_twitter',      'label' => 'رابط تويتر / X',                           'name' => 'twitter_url',   'type' => 'url' ],
            [ 'key' => 'field_tm_display_order','label' => 'ترتيب العرض (رقم أصغر = يظهر أولاً)',      'name' => 'display_order', 'type' => 'number', 'default_value' => 0, 'min' => 0 ],
            [ 'key' => 'field_tm_avatar_color', 'label' => 'لون خلفية الصورة الرمزية (CSS gradient)', 'name' => 'avatar_color', 'type' => 'text', 'default_value' => 'linear-gradient(140deg,#0b1240,rgba(30,46,245,.25))' ],
            [ 'key' => 'field_tm_is_visible',   'label' => 'ظاهر في الصفحة؟',   'name' => 'is_visible',    'type' => 'true_false', 'default_value' => 1 ],
        ],
    ] );

    // ═══════════════════════════════════════════════════════════
    // Client Fields
    // ═══════════════════════════════════════════════════════════
    acf_add_local_field_group( [
        'key'      => 'group_client',
        'title'    => 'بيانات العميل',
        'location' => [ [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'client' ] ] ],
        'fields'   => [
            [ 'key' => 'field_cl_logo',       'label' => 'شعار العميل',            'name' => 'client_logo',  'type' => 'image', 'return_format' => 'array' ],
            [ 'key' => 'field_cl_url',        'label' => 'رابط موقع العميل',       'name' => 'client_url',   'type' => 'url' ],
            [ 'key' => 'field_cl_visible',    'label' => 'ظاهر في الموقع؟',        'name' => 'is_visible',   'type' => 'true_false', 'default_value' => 1 ],
        ],
    ] );

    // ═══════════════════════════════════════════════════════════
    // Platform Fields
    // ═══════════════════════════════════════════════════════════
    acf_add_local_field_group( [
        'key'      => 'group_platform',
        'title'    => 'بيانات المنصّة',
        'location' => [ [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'platform' ] ] ],
        'fields'   => [
            [ 'key' => 'field_pl_icon',    'label' => 'أيقونة / شعار المنصّة',   'name' => 'platform_icon', 'type' => 'image', 'return_format' => 'array' ],
            [ 'key' => 'field_pl_url',     'label' => 'رابط (اختياري)',           'name' => 'platform_url',  'type' => 'url' ],
            [ 'key' => 'field_pl_visible', 'label' => 'ظاهر في الموقع؟',         'name' => 'is_visible',    'type' => 'true_false', 'default_value' => 1 ],
        ],
    ] );

    // ═══════════════════════════════════════════════════════════
    // Sector Fields
    // ═══════════════════════════════════════════════════════════
    acf_add_local_field_group( [
        'key'      => 'group_sector',
        'title'    => 'بيانات القطاع',
        'location' => [ [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'sector' ] ] ],
        'fields'   => [
            [ 'key' => 'field_sec_hero_desc',  'label' => 'وصف الهيرو',   'name' => 'hero_description', 'type' => 'textarea', 'rows' => 2 ],
            [ 'key' => 'field_sec_card_desc',  'label' => 'وصف البطاقة في أرشيف القطاعات', 'name' => 'sector_card_desc', 'type' => 'text' ],
            [ 'key' => 'field_sec_icon_svg',   'label' => 'SVG أيقونة القطاع (كود كامل)', 'name' => 'sector_icon_svg', 'type' => 'textarea', 'rows' => 5 ],
            [
                'key'          => 'field_sec_challenges',
                'label'        => 'تحديات القطاع',
                'name'         => 'challenges',
                'type'         => 'repeater',
                'min'          => 1,
                'max'          => 6,
                'button_label' => 'إضافة تحدي',
                'sub_fields'   => [
                    [ 'key' => 'field_ch_title', 'label' => 'عنوان التحدي', 'name' => 'title', 'type' => 'text' ],
                    [ 'key' => 'field_ch_desc',  'label' => 'وصف التحدي',  'name' => 'desc',  'type' => 'textarea', 'rows' => 2 ],
                    [ 'key' => 'field_ch_icon',  'label' => 'أيقونة SVG',  'name' => 'icon',  'type' => 'textarea', 'rows' => 3 ],
                ],
            ],
            [
                'key'          => 'field_sec_solutions',
                'label'        => 'منهجيتنا / الحلول',
                'name'         => 'solutions',
                'type'         => 'repeater',
                'min'          => 1,
                'max'          => 6,
                'button_label' => 'إضافة حل',
                'sub_fields'   => [
                    [ 'key' => 'field_sol_title', 'label' => 'عنوان الحل', 'name' => 'title', 'type' => 'text' ],
                    [ 'key' => 'field_sol_desc',  'label' => 'وصف الحل',  'name' => 'desc',  'type' => 'textarea', 'rows' => 2 ],
                    [ 'key' => 'field_sol_icon',  'label' => 'أيقونة SVG', 'name' => 'icon',  'type' => 'textarea', 'rows' => 3 ],
                ],
            ],
            [
                'key'          => 'field_sec_case_studies',
                'label'        => 'دراسات الحالة المرتبطة',
                'name'         => 'related_case_studies',
                'type'         => 'relationship',
                'post_type'    => [ 'case_study' ],
                'max'          => 3,
                'return_format'=> 'id',
            ],
        ],
    ] );

    // ═══════════════════════════════════════════════════════════
    // Service Platform Page Fields (shared template)
    // ═══════════════════════════════════════════════════════════
    acf_add_local_field_group( [
        'key'      => 'group_service_platform',
        'title'    => 'صفحة خدمة / منصّة',
        'location' => [
            [ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'templates/template-service-platform.php' ] ],
            [ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'templates/template-service-seo.php' ] ],
            [ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'templates/template-service-products.php' ] ],
        ],
        'fields'   => [
            [ 'key' => 'field_sp_hero_tag',       'label' => 'التصنيف فوق العنوان',        'name' => 'service_hero_tag',       'type' => 'text' ],
            [ 'key' => 'field_sp_hero_title',     'label' => 'عنوان الصفحة الرئيسي',       'name' => 'service_hero_title',     'type' => 'text' ],
            [ 'key' => 'field_sp_hero_em',        'label' => 'الكلمة المُبرَزة في العنوان',  'name' => 'service_hero_em',        'type' => 'text' ],
            [ 'key' => 'field_sp_hero_desc',      'label' => 'وصف الهيرو',                'name' => 'service_hero_desc',      'type' => 'textarea', 'rows' => 3 ],
            [ 'key' => 'field_sp_platform_logo',  'label' => 'شعار المنصّة',               'name' => 'service_platform_logo',  'type' => 'image', 'return_format' => 'array' ],
            [ 'key' => 'field_sp_platform_color', 'label' => 'لون المنصّة (HEX)',           'name' => 'service_platform_color', 'type' => 'text', 'placeholder' => '#1e2ef5' ],
            [ 'key' => 'field_sp_platform_emoji', 'label' => 'إيموجي قسم "لماذا"',          'name' => 'service_platform_emoji', 'type' => 'text', 'placeholder' => '🚀' ],
            [ 'key' => 'field_sp_why_title',      'label' => 'عنوان قسم "لماذا"',           'name' => 'service_why_title',      'type' => 'text' ],
            [ 'key' => 'field_sp_why_desc',       'label' => 'وصف قسم "لماذا"',            'name' => 'service_why_desc',       'type' => 'textarea', 'rows' => 3 ],
            [ 'key' => 'field_sp_why_quote',      'label' => 'الاقتباس في البطاقة المرئية', 'name' => 'service_why_quote',      'type' => 'text' ],
            [
                'key'          => 'field_sp_features',
                'label'        => 'المميزات / ما نقدمه',
                'name'         => 'service_features',
                'type'         => 'repeater',
                'button_label' => 'إضافة ميزة',
                'sub_fields'   => [
                    [ 'key' => 'field_feat_title', 'label' => 'عنوان الميزة',   'name' => 'title', 'type' => 'text' ],
                    [ 'key' => 'field_feat_desc',  'label' => 'وصف الميزة',    'name' => 'desc',  'type' => 'textarea', 'rows' => 2 ],
                    [ 'key' => 'field_feat_icon',  'label' => 'أيقونة SVG',    'name' => 'icon',  'type' => 'textarea', 'rows' => 3 ],
                ],
            ],
            [
                'key'          => 'field_sp_steps',
                'label'        => 'خطوات العمل',
                'name'         => 'service_steps',
                'type'         => 'repeater',
                'button_label' => 'إضافة خطوة',
                'sub_fields'   => [
                    [ 'key' => 'field_stp_title', 'label' => 'عنوان الخطوة', 'name' => 'title', 'type' => 'text' ],
                    [ 'key' => 'field_stp_desc',  'label' => 'وصف الخطوة',  'name' => 'desc',  'type' => 'textarea', 'rows' => 2 ],
                ],
            ],
            [
                'key'          => 'field_sp_faqs',
                'label'        => 'الأسئلة الشائعة',
                'name'         => 'service_faqs',
                'type'         => 'repeater',
                'button_label' => 'إضافة سؤال',
                'sub_fields'   => [
                    [ 'key' => 'field_faq_q', 'label' => 'السؤال',  'name' => 'question', 'type' => 'text' ],
                    [ 'key' => 'field_faq_a', 'label' => 'الجواب',  'name' => 'answer',   'type' => 'textarea', 'rows' => 3 ],
                ],
            ],
            [
                'key'          => 'field_sp_deliverables',
                'label'        => 'بطاقات ماذا نقدّم',
                'name'         => 'service_deliverables',
                'type'         => 'repeater',
                'button_label' => 'إضافة بطاقة',
                'sub_fields'   => [
                    [ 'key' => 'field_del_title', 'label' => 'العنوان', 'name' => 'del_title', 'type' => 'text' ],
                    [ 'key' => 'field_del_desc',  'label' => 'الوصف',   'name' => 'del_desc',  'type' => 'textarea', 'rows' => 2 ],
                ],
            ],
            [ 'key' => 'field_sp_bottom_cta',      'label' => 'عنوان CTA أسفل الصفحة',        'name' => 'service_bottom_cta_title', 'type' => 'text' ],
            [ 'key' => 'field_sp_side_cta_h3',     'label' => 'عنوان بطاقة CTA الجانبية',     'name' => 'service_side_cta_h3',     'type' => 'textarea', 'rows' => 2 ],
            [ 'key' => 'field_sp_side_cta_desc',   'label' => 'وصف بطاقة CTA الجانبية',      'name' => 'service_side_cta_desc',   'type' => 'textarea', 'rows' => 2 ],
            [ 'key' => 'field_sp_side_cta_btn',    'label' => 'نص زر CTA الجانبي',            'name' => 'service_side_cta_btn',    'type' => 'text' ],
            [
                'key'          => 'field_sp_side_cta_checks',
                'label'        => 'قائمة مزايا CTA الجانبي',
                'name'         => 'service_side_cta_checks',
                'type'         => 'repeater',
                'button_label' => 'إضافة عنصر',
                'sub_fields'   => [
                    [ 'key' => 'field_sc_text', 'label' => 'النص', 'name' => 'sc_text', 'type' => 'text' ],
                ],
            ],
        ],
    ] );

    // ═══════════════════════════════════════════════════════════
    // About Page Fields
    // ═══════════════════════════════════════════════════════════
    acf_add_local_field_group( [
        'key'      => 'group_about_page',
        'title'    => 'محتوى صفحة: من نحن',
        'location' => [ [ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'templates/template-about.php' ] ] ],
        'fields'   => [
            [ 'key' => 'field_ab_hero_tag',   'label' => 'تصنيف الهيرو',                    'name' => 'about_hero_tag',   'type' => 'text',     'default_value' => 'قصتنا' ],
            [ 'key' => 'field_ab_hero_title', 'label' => 'عنوان الهيرو (سطر في كل سطر)',    'name' => 'about_hero_title', 'type' => 'textarea', 'rows' => 2, 'default_value' => "نعمل بمنطق الأداء\nلا بمنطق الوعود" ],
            [ 'key' => 'field_ab_hero_em',    'label' => 'الكلمة المُبرَزة بالتدرج اللوني', 'name' => 'about_hero_em',    'type' => 'text',     'default_value' => 'الأداء' ],
            [ 'key' => 'field_ab_hero_desc',  'label' => 'وصف الهيرو',                      'name' => 'about_hero_desc',  'type' => 'textarea', 'rows' => 3, 'default_value' => 'سيو هاوس متخصصون في تحسين محركات البحث، يخدمون الأسواق السعودية والخليجية. لا نُقدّم وعوداً — نُقدّم خطوات واضحة ونتائج قابلة للقياس.' ],
            [ 'key' => 'field_ab_who_tag',     'label' => 'تصنيف قسم من نحن',               'name' => 'about_who_tag',     'type' => 'text',     'default_value' => 'من نحن' ],
            [ 'key' => 'field_ab_who_heading', 'label' => 'عنوان قسم من نحن',               'name' => 'about_who_heading', 'type' => 'textarea', 'rows' => 2, 'default_value' => "متخصصون في السيو،\nلا في كل شيء" ],
            [ 'key' => 'field_ab_who_body1',   'label' => 'الفقرة الأولى — من نحن',         'name' => 'about_who_body1',   'type' => 'textarea', 'rows' => 3, 'default_value' => 'سيو هاوس تأسست لخدمة الأعمال السعودية والخليجية الباحثة عن حضور رقمي حقيقي في محركات البحث. لم نبنِ عملنا على الوعود — بنيناه على نتائج يمكن قياسها في Search Console.' ],
            [ 'key' => 'field_ab_who_body2',   'label' => 'الفقرة الثانية — من نحن',        'name' => 'about_who_body2',   'type' => 'textarea', 'rows' => 3, 'default_value' => 'نؤمن أن أفضل طريقة لإثبات قيمتنا هي أن نعمل بشفافية كاملة — تقرير شهري، أهداف محددة، وتواصل مباشر مع الفريق المنفذ.' ],
            [ 'key' => 'field_ab_principles_heading', 'label' => 'عنوان بطاقة المبادئ',     'name' => 'about_principles_heading', 'type' => 'text', 'default_value' => 'مبادئنا في العمل' ],
            [
                'key'          => 'field_ab_principles',
                'label'        => 'بطاقات المبادئ (3 عناصر)',
                'name'         => 'about_principles',
                'type'         => 'repeater',
                'min'          => 1,
                'max'          => 5,
                'button_label' => 'إضافة مبدأ',
                'sub_fields'   => [
                    [ 'key' => 'field_pr_title', 'label' => 'العنوان', 'name' => 'title', 'type' => 'text' ],
                    [ 'key' => 'field_pr_desc',  'label' => 'الوصف',   'name' => 'desc',  'type' => 'textarea', 'rows' => 2 ],
                ],
            ],
            [ 'key' => 'field_ab_values_tag',     'label' => 'تصنيف قسم القيم',            'name' => 'about_values_tag',     'type' => 'text', 'default_value' => 'قيمنا' ],
            [ 'key' => 'field_ab_values_heading', 'label' => 'عنوان قسم القيم',             'name' => 'about_values_heading', 'type' => 'text', 'default_value' => 'ما يحكم طريقة عملنا' ],
            [
                'key'          => 'field_ab_values',
                'label'        => 'بطاقات القيم (6 عناصر)',
                'name'         => 'about_values',
                'type'         => 'repeater',
                'min'          => 1,
                'max'          => 8,
                'button_label' => 'إضافة قيمة',
                'sub_fields'   => [
                    [ 'key' => 'field_vl_title', 'label' => 'العنوان', 'name' => 'title', 'type' => 'text' ],
                    [ 'key' => 'field_vl_desc',  'label' => 'الوصف',   'name' => 'desc',  'type' => 'textarea', 'rows' => 2 ],
                ],
            ],
            [ 'key' => 'field_ab_cta_tag',   'label' => 'تصنيف قسم CTA',                  'name' => 'about_cta_tag',   'type' => 'text',     'default_value' => 'تعرّف أكثر' ],
            [ 'key' => 'field_ab_cta_title', 'label' => 'عنوان قسم CTA',                   'name' => 'about_cta_title', 'type' => 'text',     'default_value' => 'هل تريد أن تعمل معنا؟' ],
            [ 'key' => 'field_ab_cta_desc',  'label' => 'وصف قسم CTA',                     'name' => 'about_cta_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'ابدأ باستشارة مجانية — وستعرف هل نحن الشريك المناسب لك.' ],
        ],
    ] );

    // ═══════════════════════════════════════════════════════════
    // Team Page Fields
    // ═══════════════════════════════════════════════════════════
    acf_add_local_field_group( [
        'key'      => 'group_team_page',
        'title'    => 'محتوى صفحة: فريق العمل',
        'location' => [ [ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'templates/template-team.php' ] ] ],
        'fields'   => [
            [ 'key' => 'field_tm_page_hero_tag',   'label' => 'تصنيف الهيرو',                      'name' => 'team_hero_tag',   'type' => 'text',     'default_value' => 'الفريق' ],
            [ 'key' => 'field_tm_page_hero_title', 'label' => 'عنوان الهيرو (سطر في كل سطر)',      'name' => 'team_hero_title', 'type' => 'textarea', 'rows' => 2, 'default_value' => "خبراء متخصصون\nلا موظفون عموميون" ],
            [ 'key' => 'field_tm_page_hero_em',    'label' => 'الكلمة المُبرَزة بالتدرج اللوني',   'name' => 'team_hero_em',    'type' => 'text',     'default_value' => 'متخصصون' ],
            [ 'key' => 'field_tm_page_hero_desc',  'label' => 'وصف الهيرو',                        'name' => 'team_hero_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'كل عضو في فريقنا متخصص في مجال محدد من السيو — لأن التخصص هو الفرق بين النتائج والوعود.' ],
            [ 'key' => 'field_tm_page_join_title', 'label' => 'عنوان بطاقة انضم إلينا',           'name' => 'team_join_title', 'type' => 'text',     'default_value' => 'نبحث عن موهبة' ],
            [ 'key' => 'field_tm_page_join_desc',  'label' => 'وصف بطاقة انضم إلينا',             'name' => 'team_join_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'هل أنت متخصص في السيو وتريد العمل في بيئة محترفة؟' ],
            [ 'key' => 'field_tm_page_join_btn',   'label' => 'نص زر انضم إلينا',                 'name' => 'team_join_btn',   'type' => 'text',     'default_value' => 'تواصل معنا' ],
        ],
    ] );

    // ═══════════════════════════════════════════════════════════
    // SEO Sub-Service Page Fields
    // ═══════════════════════════════════════════════════════════
    acf_add_local_field_group( [
        'key'      => 'group_service_seo_sub',
        'title'    => 'صفحة خدمة فرعية (سيو)',
        'location' => [ [ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'templates/template-service-seo-sub.php' ] ] ],
        'fields'   => [
            [ 'key' => 'field_ss_hero_tag',   'label' => 'تصنيف الهيرو',           'name' => 'sub_hero_tag',   'type' => 'text' ],
            [ 'key' => 'field_ss_hero_title', 'label' => 'عنوان الهيرو الرئيسي',   'name' => 'sub_hero_title', 'type' => 'text' ],
            [ 'key' => 'field_ss_hero_em',    'label' => 'الكلمة المُبرَزة',       'name' => 'sub_hero_em',    'type' => 'text' ],
            [ 'key' => 'field_ss_hero_desc',  'label' => 'وصف الهيرو',            'name' => 'sub_hero_desc',  'type' => 'textarea', 'rows' => 3 ],
            [ 'key' => 'field_ss_intro',      'label' => 'مقدمة الخدمة',          'name' => 'sub_intro',      'type' => 'wysiwyg', 'toolbar' => 'basic' ],
            [
                'key'          => 'field_ss_points',
                'label'        => 'ما يشمله هذا الخدمة',
                'name'         => 'sub_points',
                'type'         => 'repeater',
                'button_label' => 'إضافة نقطة',
                'sub_fields'   => [
                    [ 'key' => 'field_pt_title', 'label' => 'العنوان',   'name' => 'title', 'type' => 'text' ],
                    [ 'key' => 'field_pt_desc',  'label' => 'الوصف',    'name' => 'desc',  'type' => 'textarea', 'rows' => 2 ],
                    [ 'key' => 'field_pt_icon',  'label' => 'SVG أيقونة', 'name' => 'icon',  'type' => 'textarea', 'rows' => 3 ],
                ],
            ],
            [
                'key'          => 'field_ss_faqs',
                'label'        => 'الأسئلة الشائعة',
                'name'         => 'sub_faqs',
                'type'         => 'repeater',
                'button_label' => 'إضافة سؤال',
                'sub_fields'   => [
                    [ 'key' => 'field_sfaq_q', 'label' => 'السؤال', 'name' => 'question', 'type' => 'text' ],
                    [ 'key' => 'field_sfaq_a', 'label' => 'الجواب', 'name' => 'answer',   'type' => 'textarea', 'rows' => 3 ],
                ],
            ],
            [ 'key' => 'field_ss_why_tag',  'label' => 'تصنيف قسم "لماذا"',       'name' => 'sub_why_tag',  'type' => 'text' ],
            [ 'key' => 'field_ss_why_h2',   'label' => 'عنوان قسم "لماذا" (سطران)', 'name' => 'sub_why_h2', 'type' => 'textarea', 'rows' => 2 ],
            [ 'key' => 'field_ss_why_p',    'label' => 'فقرة قسم "لماذا"',         'name' => 'sub_why_p',   'type' => 'textarea', 'rows' => 3 ],
            [
                'key'          => 'field_ss_why_items',
                'label'        => 'قائمة مزايا "لماذا"',
                'name'         => 'sub_why_items',
                'type'         => 'repeater',
                'button_label' => 'إضافة عنصر',
                'sub_fields'   => [
                    [ 'key' => 'field_ss_wi_bold', 'label' => 'النص الغامق', 'name' => 'wi_bold', 'type' => 'text' ],
                    [ 'key' => 'field_ss_wi_tail', 'label' => 'النص الإضافي (اختياري)', 'name' => 'wi_tail', 'type' => 'text' ],
                ],
            ],
            [ 'key' => 'field_ss_tac_tag',  'label' => 'تصنيف قسم المنهجية/التكتيك', 'name' => 'sub_tac_tag',  'type' => 'text' ],
            [ 'key' => 'field_ss_tac_h2',   'label' => 'عنوان قسم المنهجية',          'name' => 'sub_tac_h2',  'type' => 'text' ],
            [ 'key' => 'field_ss_tac_desc', 'label' => 'وصف قسم المنهجية',            'name' => 'sub_tac_desc', 'type' => 'textarea', 'rows' => 2 ],
            [ 'key' => 'field_ss_proc_tag',  'label' => 'تصنيف قسم الخطوات',         'name' => 'sub_proc_tag',  'type' => 'text' ],
            [ 'key' => 'field_ss_proc_h2',   'label' => 'عنوان قسم الخطوات',         'name' => 'sub_proc_h2',   'type' => 'text' ],
            [ 'key' => 'field_ss_proc_desc', 'label' => 'وصف قسم الخطوات',           'name' => 'sub_proc_desc', 'type' => 'textarea', 'rows' => 2 ],
            [
                'key'          => 'field_ss_proc_steps',
                'label'        => 'خطوات العملية',
                'name'         => 'sub_proc_steps',
                'type'         => 'repeater',
                'button_label' => 'إضافة خطوة',
                'sub_fields'   => [
                    [ 'key' => 'field_ss_ps_n', 'label' => 'الرقم', 'name' => 'ps_n', 'type' => 'text' ],
                    [ 'key' => 'field_ss_ps_h', 'label' => 'العنوان', 'name' => 'ps_h', 'type' => 'text' ],
                    [ 'key' => 'field_ss_ps_p', 'label' => 'الوصف القصير', 'name' => 'ps_p', 'type' => 'text' ],
                ],
            ],
            [ 'key' => 'field_ss_faq_tag', 'label' => 'تصنيف قسم الأسئلة', 'name' => 'sub_faq_tag', 'type' => 'text' ],
            [ 'key' => 'field_ss_faq_h2',  'label' => 'عنوان قسم الأسئلة', 'name' => 'sub_faq_h2',  'type' => 'text' ],
            [ 'key' => 'field_ss_cta_h3',   'label' => 'عنوان بطاقة CTA الجانبية',    'name' => 'sub_cta_h3',   'type' => 'textarea', 'rows' => 2 ],
            [ 'key' => 'field_ss_cta_desc', 'label' => 'وصف بطاقة CTA الجانبية',     'name' => 'sub_cta_desc', 'type' => 'textarea', 'rows' => 2 ],
            [ 'key' => 'field_ss_cta_btn',  'label' => 'نص زر CTA الجانبي',          'name' => 'sub_cta_btn',  'type' => 'text' ],
            [
                'key'          => 'field_ss_cta_checks',
                'label'        => 'قائمة مزايا CTA الجانبي',
                'name'         => 'sub_cta_checks',
                'type'         => 'repeater',
                'button_label' => 'إضافة عنصر',
                'sub_fields'   => [
                    [ 'key' => 'field_ss_ck_text', 'label' => 'النص', 'name' => 'ck_text', 'type' => 'text' ],
                ],
            ],
            [ 'key' => 'field_ss_bottom_h2',  'label' => 'عنوان CTA أسفل الصفحة',  'name' => 'sub_bottom_h2', 'type' => 'text' ],
            [ 'key' => 'field_ss_bottom_p',   'label' => 'وصف CTA أسفل الصفحة',   'name' => 'sub_bottom_p',  'type' => 'textarea', 'rows' => 2 ],
            [ 'key' => 'field_ss_btn2_txt',   'label' => 'نص الزر الثاني (أسفل)', 'name' => 'sub_btn2_txt',  'type' => 'text' ],
        ],
    ] );

    // ═══════════════════════════════════════════════════════════
    // Services Hub Page Fields
    // ═══════════════════════════════════════════════════════════
    acf_add_local_field_group( [
        'key'      => 'group_services_hub',
        'title'    => 'محتوى صفحة: مركز الخدمات',
        'location' => [ [ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'templates/template-services.php' ] ] ],
        'fields'   => [
            [ 'key' => 'field_hub_hero_badge',    'label' => 'شعار الهيرو',                'name' => 'hub_hero_badge',    'type' => 'text',     'default_value' => 'خدماتنا' ],
            [ 'key' => 'field_hub_hero_title',    'label' => 'عنوان الهيرو (سطر في كل سطر)', 'name' => 'hub_hero_title', 'type' => 'textarea', 'rows' => 2, 'default_value' => "خدمات تبني\nوجودك الرقمي" ],
            [ 'key' => 'field_hub_hero_em',       'label' => 'الكلمة المُبرَزة بالتدرج',   'name' => 'hub_hero_em',       'type' => 'text',     'default_value' => 'وجودك الرقمي' ],
            [ 'key' => 'field_hub_hero_desc',     'label' => 'وصف الهيرو',                 'name' => 'hub_hero_desc',     'type' => 'textarea', 'rows' => 2, 'default_value' => 'من تحسين محركات البحث إلى إنشاء المتاجر — نغطّي كل ما يحتاجه عملك لينمو رقمياً.' ],
            [ 'key' => 'field_hub_services_title','label' => 'عنوان قسم الخدمات',          'name' => 'hub_services_title','type' => 'text',     'default_value' => 'اختر الخدمة التي تحتاجها' ],
            [ 'key' => 'field_hub_services_desc', 'label' => 'وصف قسم الخدمات',           'name' => 'hub_services_desc', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'كل خدمة مصمّمة لتحقيق هدف محدد — نوصّلك للنتيجة بأقصر طريق.' ],
            [ 'key' => 'field_hub_seo_sub_title', 'label' => 'عنوان قسم خدمات سيو التخصصية', 'name' => 'hub_seo_sub_title', 'type' => 'text', 'default_value' => 'ابنِ استراتيجيتك من هنا' ],
            [ 'key' => 'field_hub_seo_sub_desc',  'label' => 'وصف قسم خدمات سيو التخصصية',  'name' => 'hub_seo_sub_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'كل خدمة تُكمّل الأخرى — اختر ما يناسب وضعك الآن أو ابنِ باقة متكاملة.' ],
            [ 'key' => 'field_hub_why_title',     'label' => 'عنوان قسم لماذا سيو هاوس',  'name' => 'hub_why_title',     'type' => 'text',     'default_value' => 'ليس مجرد وكالة رقمية' ],
            [ 'key' => 'field_hub_why_desc',      'label' => 'وصف قسم لماذا سيو هاوس',   'name' => 'hub_why_desc',      'type' => 'textarea', 'rows' => 2, 'default_value' => 'نتخصص في السوق السعودي — نفهم اللغة، نعرف المنافسين، ونعمل بشفافية كاملة.' ],
            [ 'key' => 'field_hub_cta_tag',       'label' => 'تصنيف CTA',                  'name' => 'hub_cta_tag',       'type' => 'text',     'default_value' => 'ابدأ الآن' ],
            [ 'key' => 'field_hub_cta_title',     'label' => 'عنوان CTA',                   'name' => 'hub_cta_title',     'type' => 'text',     'default_value' => 'لا تعرف من أين تبدأ؟' ],
            [ 'key' => 'field_hub_cta_desc',      'label' => 'وصف CTA',                    'name' => 'hub_cta_desc',      'type' => 'textarea', 'rows' => 2, 'default_value' => 'احجز استشارة مجانية ونحدد معاً أفضل خدمة لنمو مشروعك.' ],
        ],
    ] );

    // ═══════════════════════════════════════════════════════════
    // Service Web Overview Page Fields
    // ═══════════════════════════════════════════════════════════
    acf_add_local_field_group( [
        'key'      => 'group_service_web_overview',
        'title'    => 'محتوى صفحة: تصميم المواقع',
        'location' => [ [ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'templates/template-service-web.php' ] ] ],
        'fields'   => [
            [ 'key' => 'field_web_hero_pre',  'label' => 'السطر الأول في العنوان',  'name' => 'web_hero_pre',  'type' => 'text',     'default_value' => 'تصميم مواقع' ],
            [ 'key' => 'field_web_hero_em',   'label' => 'الكلمة المُبرَزة (السطر الثاني)', 'name' => 'web_hero_em', 'type' => 'text', 'default_value' => 'تبني الثقة وتجلب العملاء' ],
            [ 'key' => 'field_web_hero_desc', 'label' => 'وصف الهيرو',              'name' => 'web_hero_desc', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'الموقع الإلكتروني هو مقرّك الرقمي — نُصمّمه ليُمثّلك باحترافية، يُحمّل بسرعة، يُقنع زوّارك، ويُحوّلهم إلى عملاء فعليين.' ],
            [ 'key' => 'field_web_why_title', 'label' => 'عنوان قسم "لماذا يهم الموقع"', 'name' => 'web_why_title', 'type' => 'textarea', 'rows' => 2, 'default_value' => "قبل أن يتّصل بك العميل،\nهو يبحث عنك" ],
            [ 'key' => 'field_web_why_desc',  'label' => 'وصف قسم "لماذا يهم الموقع"',  'name' => 'web_why_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'في أول 3 ثوانٍ من زيارة موقعك، يُقرّر زائرك إن كان سيتعامل معك أم لا. الموقع الضعيف يُضيع كل ميزانيتك التسويقية لأنه يفقد الزوار قبل أن يقرأوا عرضك أصلاً.' ],
            [
                'key'          => 'field_web_why_items',
                'label'        => 'نقاط "لماذا يهم الموقع" (4)',
                'name'         => 'web_why_items',
                'type'         => 'repeater',
                'min'          => 4,
                'max'          => 4,
                'button_label' => 'إضافة نقطة',
                'sub_fields'   => [
                    [ 'key' => 'field_wwi_label', 'label' => 'العنوان المميز', 'name' => 'wwi_label', 'type' => 'text' ],
                    [ 'key' => 'field_wwi_desc',  'label' => 'الشرح',         'name' => 'wwi_desc',  'type' => 'text' ],
                ],
            ],
            [ 'key' => 'field_web_types_title', 'label' => 'عنوان قسم أنواع المواقع',  'name' => 'web_types_title', 'type' => 'text',     'default_value' => 'نُصمّم 6 أنواع من المواقع' ],
            [ 'key' => 'field_web_types_desc',  'label' => 'وصف قسم أنواع المواقع',   'name' => 'web_types_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'لكل نشاط نوع موقع يخدم أهدافه بأفضل شكل ممكن — اختيار النوع الصحيح هو نصف الطريق للنجاح.' ],
            [
                'key'          => 'field_web_types_cards',
                'label'        => 'بطاقات أنواع المواقع (6)',
                'name'         => 'web_types_cards',
                'type'         => 'repeater',
                'min'          => 6,
                'max'          => 6,
                'button_label' => 'إضافة نوع',
                'sub_fields'   => [
                    [ 'key' => 'field_tc_tag',   'label' => 'التصنيف (تاج)',        'name' => 'tc_tag',   'type' => 'text' ],
                    [ 'key' => 'field_tc_title', 'label' => 'العنوان',              'name' => 'tc_title', 'type' => 'text' ],
                    [ 'key' => 'field_tc_desc',  'label' => 'الوصف',               'name' => 'tc_desc',  'type' => 'textarea', 'rows' => 2 ],
                    [ 'key' => 'field_tc_feats', 'label' => 'المميزات (سطر لكل ميزة)', 'name' => 'tc_feats', 'type' => 'textarea', 'rows' => 3 ],
                ],
            ],
            [ 'key' => 'field_web_tech_title', 'label' => 'عنوان قسم التقنيات',       'name' => 'web_tech_title', 'type' => 'text',     'default_value' => 'التقنيات التي نعمل بها' ],
            [ 'key' => 'field_web_tech_desc',  'label' => 'وصف قسم التقنيات',        'name' => 'web_tech_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'نختار التقنية الأنسب لكل مشروع — لا نُجبر العميل على تقنية واحدة.' ],
            [ 'key' => 'field_web_eff_title',  'label' => 'عنوان قسم "ما يُحدث الفرق"', 'name' => 'web_eff_title', 'type' => 'text', 'default_value' => 'ما الذي يجعل الموقع ينجح تجارياً؟' ],
            [ 'key' => 'field_web_eff_desc',   'label' => 'وصف قسم "ما يُحدث الفرق"',  'name' => 'web_eff_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'الموقع الجميل ليس بالضرورة موقعاً ناجحاً — هذه هي العناصر التي تُفرّق بين الاثنين.' ],
            [ 'key' => 'field_web_proc_title', 'label' => 'عنوان قسم المراحل',        'name' => 'web_proc_title', 'type' => 'text',     'default_value' => '5 مراحل لإطلاق موقعك' ],
            [ 'key' => 'field_web_proc_desc',  'label' => 'وصف قسم المراحل',         'name' => 'web_proc_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'كل مرحلة لها مخرج محدّد توافق عليه قبل الانتقال للمرحلة التالية.' ],
            [
                'key'          => 'field_web_proc_steps',
                'label'        => 'مراحل تصميم الموقع (5)',
                'name'         => 'web_proc_steps',
                'type'         => 'repeater',
                'min'          => 5,
                'max'          => 5,
                'button_label' => 'إضافة مرحلة',
                'sub_fields'   => [
                    [ 'key' => 'field_wps_num',   'label' => 'رقم المرحلة', 'name' => 'wps_num',   'type' => 'text' ],
                    [ 'key' => 'field_wps_title', 'label' => 'عنوان المرحلة', 'name' => 'wps_title', 'type' => 'text' ],
                    [ 'key' => 'field_wps_desc',  'label' => 'وصف المرحلة',  'name' => 'wps_desc',  'type' => 'textarea', 'rows' => 2 ],
                ],
            ],
            [ 'key' => 'field_web_faq_title', 'label' => 'عنوان قسم الأسئلة الشائعة', 'name' => 'web_faq_title', 'type' => 'text',     'default_value' => 'أسئلة عن تصميم المواقع' ],
            [
                'key'          => 'field_web_faqs',
                'label'        => 'الأسئلة الشائعة',
                'name'         => 'web_faqs',
                'type'         => 'repeater',
                'button_label' => 'إضافة سؤال',
                'sub_fields'   => [
                    [ 'key' => 'field_wfaq_q', 'label' => 'السؤال', 'name' => 'wfaq_q', 'type' => 'text' ],
                    [ 'key' => 'field_wfaq_a', 'label' => 'الجواب', 'name' => 'wfaq_a', 'type' => 'textarea', 'rows' => 3 ],
                ],
            ],
            [ 'key' => 'field_web_cta_title', 'label' => 'عنوان CTA النهائي',  'name' => 'web_cta_title', 'type' => 'text',     'default_value' => 'ابنِ موقعاً يستحق علامتك التجارية' ],
            [ 'key' => 'field_web_cta_desc',  'label' => 'وصف CTA النهائي',   'name' => 'web_cta_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'احجز استشارة مجانية ولنبدأ معاً في تصميم موقعك الجديد.' ],
        ],
    ] );

    // ═══════════════════════════════════════════════════════════
    // Service Stores Overview Page Fields
    // ═══════════════════════════════════════════════════════════
    acf_add_local_field_group( [
        'key'      => 'group_service_stores_overview',
        'title'    => 'محتوى صفحة: إنشاء المتاجر',
        'location' => [ [ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'templates/template-service-stores.php' ] ] ],
        'fields'   => [
            [ 'key' => 'field_stores_hero_pre',  'label' => 'السطر الأول في العنوان',  'name' => 'stores_hero_pre',  'type' => 'text',     'default_value' => 'إنشاء وتصميم' ],
            [ 'key' => 'field_stores_hero_em',   'label' => 'الكلمة المُبرَزة (السطر الثاني)', 'name' => 'stores_hero_em', 'type' => 'text', 'default_value' => 'متجرك الإلكتروني' ],
            [ 'key' => 'field_stores_hero_desc', 'label' => 'وصف الهيرو',              'name' => 'stores_hero_desc', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'من فكرة إلى متجر جاهز للبيع — هيكل تجاري ذكي، تجربة شراء سلسة، وقابلية للنمو على أي منصة (سلة، زد، شوبيفاي، أو ووكومرس).' ],
        ],
    ] );

    // ═══════════════════════════════════════════════════════════
    // Service Products Page Fields
    // ═══════════════════════════════════════════════════════════
    acf_add_local_field_group( [
        'key'      => 'group_service_products_page',
        'title'    => 'محتوى صفحة: رفع المنتجات',
        'location' => [ [ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'templates/template-service-products.php' ] ] ],
        'fields'   => [
            [ 'key' => 'field_prod_hero_tag',   'label' => 'تصنيف الهيرو',                    'name' => 'prod_hero_tag',   'type' => 'text',     'default_value' => 'رفع المنتجات' ],
            [ 'key' => 'field_prod_hero_title', 'label' => 'عنوان الهيرو (سطر في كل سطر)',    'name' => 'prod_hero_title', 'type' => 'textarea', 'rows' => 2, 'default_value' => "رفع المنتجات للمتاجر\nباحترافية" ],
            [ 'key' => 'field_prod_hero_em',    'label' => 'الكلمة المُبرَزة',                'name' => 'prod_hero_em',    'type' => 'text',     'default_value' => 'باحترافية' ],
            [ 'key' => 'field_prod_hero_desc',  'label' => 'وصف الهيرو',                      'name' => 'prod_hero_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'ليس مجرّد إدخال بيانات — رفع منظّم واحترافي عبر جميع المنصات مع تحسين لكل منتج يرفع فرصته في الظهور.' ],
        ],
    ] );

    // ═══════════════════════════════════════════════════════════
    // Results Page Fields
    // ═══════════════════════════════════════════════════════════
    acf_add_local_field_group( [
        'key'      => 'group_results_page',
        'title'    => 'محتوى صفحة: النتائج',
        'location' => [ [ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'templates/template-results.php' ] ] ],
        'fields'   => [
            [ 'key' => 'field_results_hero_tag',   'label' => 'تصنيف الهيرو',                 'name' => 'results_hero_tag',   'type' => 'text',     'default_value' => 'نتائج فعلية' ],
            [ 'key' => 'field_results_hero_title', 'label' => 'عنوان الهيرو (سطر في كل سطر)', 'name' => 'results_hero_title', 'type' => 'textarea', 'rows' => 2, 'default_value' => "نتائج من\nمشاريع حقيقية" ],
            [ 'key' => 'field_results_hero_em',    'label' => 'الكلمة المُبرَزة',             'name' => 'results_hero_em',    'type' => 'text',     'default_value' => 'مشاريع حقيقية' ],
            [ 'key' => 'field_results_hero_desc',  'label' => 'وصف الهيرو',                   'name' => 'results_hero_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'لا نتحدث عن نتائج افتراضية — هذه مشاريع نفّذناها لعملاء حقيقيين في قطاعات متنوعة. الأرقام الدقيقة تُضاف بعد موافقة العملاء.' ],
            [ 'key' => 'field_results_cta_tag',   'label' => 'تصنيف CTA أسفل الصفحة',       'name' => 'results_cta_tag',    'type' => 'text',     'default_value' => 'هل موقعك التالي؟' ],
            [ 'key' => 'field_results_cta_title', 'label' => 'عنوان CTA أسفل الصفحة',       'name' => 'results_cta_title',  'type' => 'text',     'default_value' => 'لنبني نتائجك معاً' ],
            [ 'key' => 'field_results_cta_desc',  'label' => 'وصف CTA أسفل الصفحة',         'name' => 'results_cta_desc',   'type' => 'textarea', 'rows' => 2, 'default_value' => 'احجز استشارة مجانية ونضع الأساس لنتيجة تستحق أن تُعرض هنا.' ],
            [ 'key' => 'field_results_btn2_txt',  'label' => 'نص الزر الثاني في CTA',        'name' => 'results_btn2_txt',   'type' => 'text',     'default_value' => 'تعرّف على السيو' ],
        ],
    ] );

    // ═══════════════════════════════════════════════════════════
    // Sectors Archive Options Fields
    // ═══════════════════════════════════════════════════════════
    acf_add_local_field_group( [
        'key'      => 'group_sectors_archive',
        'title'    => 'إعدادات أرشيف القطاعات',
        'location' => [ [ [ 'param' => 'options_page', 'operator' => '==', 'value' => 'seohouse-sectors-archive' ] ] ],
        'fields'   => [
            [ 'key' => 'field_sectors_hero_title',  'label' => 'عنوان الهيرو (سطر في كل سطر)', 'name' => 'sectors_hero_title',  'type' => 'textarea', 'rows' => 2, 'default_value' => "سيو مخصص\nلقطاعك" ],
            [ 'key' => 'field_sectors_hero_em',     'label' => 'الكلمة المُبرَزة',              'name' => 'sectors_hero_em',     'type' => 'text',     'default_value' => 'لقطاعك' ],
            [ 'key' => 'field_sectors_hero_desc',   'label' => 'وصف الهيرو',                    'name' => 'sectors_hero_desc',   'type' => 'textarea', 'rows' => 2, 'default_value' => 'لكل قطاع كلماته المفتاحية، جمهوره، ومنافسوه — استراتيجيتنا مبنية على هذا الفهم، لا على قوالب جاهزة.' ],
            [ 'key' => 'field_sectors_grid_title',  'label' => 'عنوان شبكة القطاعات',          'name' => 'sectors_grid_title',  'type' => 'text',     'default_value' => 'اختر قطاعك لمعرفة كيف نخدمه' ],
            [ 'key' => 'field_sectors_grid_desc',   'label' => 'وصف شبكة القطاعات',            'name' => 'sectors_grid_desc',   'type' => 'textarea', 'rows' => 2, 'default_value' => 'نعمل مع شركات من قطاعات متنوعة — لكل واحد منها منهجية وفهم خاص.' ],
            [ 'key' => 'field_sectors_why_title',   'label' => 'عنوان قسم لماذا التخصص',       'name' => 'sectors_why_title',   'type' => 'textarea', 'rows' => 2, 'default_value' => "استراتيجية قطاعية\nلا قالب جاهز" ],
            [ 'key' => 'field_sectors_why_body1',   'label' => 'الفقرة الأولى — لماذا التخصص', 'name' => 'sectors_why_body1',   'type' => 'textarea', 'rows' => 2, 'default_value' => 'كل قطاع يملك كلماته المفتاحية الخاصة، نية بحث مختلفة، ومنافسين محددين. الاستراتيجية الناجحة للعيادة تختلف عن استراتيجية المتجر الإلكتروني تماماً.' ],
            [ 'key' => 'field_sectors_why_body2',   'label' => 'الفقرة الثانية — لماذا التخصص', 'name' => 'sectors_why_body2', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'نبني استراتيجية السيو لكل عميل بناءً على فهم عميق لقطاعه — وليس نسخاً من قالب جاهز.' ],
            [ 'key' => 'field_sectors_cta_title',   'label' => 'عنوان CTA',                     'name' => 'sectors_cta_title',   'type' => 'text',     'default_value' => 'احجز استشارة مجانية ولنبدأ معاً' ],
            [ 'key' => 'field_sectors_hero_tag',    'label' => 'تصنيف الهيرو',                  'name' => 'sectors_hero_tag',    'type' => 'text',     'default_value' => 'تخصص قطاعي' ],
            [ 'key' => 'field_sectors_why_tag',     'label' => 'تصنيف قسم "لماذا التخصص مهم"', 'name' => 'sectors_why_tag',     'type' => 'text',     'default_value' => 'لماذا التخصص مهم' ],
            [ 'key' => 'field_sectors_intent_title','label' => 'عنوان بطاقة نية البحث',         'name' => 'sectors_intent_title','type' => 'text',     'default_value' => 'مثال: نية البحث تختلف حسب القطاع' ],
            [
                'key'          => 'field_sectors_intent_items',
                'label'        => 'عناصر مقارنة نية البحث (4)',
                'name'         => 'sectors_intent_items',
                'type'         => 'repeater',
                'min'          => 4,
                'max'          => 4,
                'button_label' => 'إضافة عنصر',
                'sub_fields'   => [
                    [ 'key' => 'field_si_color', 'label' => 'لون النقطة (CSS)', 'name' => 'si_color', 'type' => 'text' ],
                    [ 'key' => 'field_si_text',  'label' => 'النص',             'name' => 'si_text',  'type' => 'text' ],
                ],
            ],
        ],
    ] );

    // ═══════════════════════════════════════════════════════════
    // Results Archive Options Fields
    // ═══════════════════════════════════════════════════════════
    acf_add_local_field_group( [
        'key'      => 'group_results_archive',
        'title'    => 'إعدادات صفحة النتائج (أرشيف)',
        'location' => [ [ [ 'param' => 'options_page', 'operator' => '==', 'value' => 'seohouse-results' ] ] ],
        'fields'   => [
            [ 'key' => 'field_ra_hero_tag',   'label' => 'تصنيف الهيرو',                 'name' => 'ra_hero_tag',   'type' => 'text',     'default_value' => 'نتائج فعلية' ],
            [ 'key' => 'field_ra_hero_title', 'label' => 'عنوان الهيرو (سطر في كل سطر)', 'name' => 'ra_hero_title', 'type' => 'textarea', 'rows' => 2, 'default_value' => "نتائج من\nمشاريع حقيقية" ],
            [ 'key' => 'field_ra_hero_em',    'label' => 'الكلمة المُبرَزة',             'name' => 'ra_hero_em',    'type' => 'text',     'default_value' => 'مشاريع حقيقية' ],
            [ 'key' => 'field_ra_hero_desc',  'label' => 'وصف الهيرو',                   'name' => 'ra_hero_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'لا نتحدث عن نتائج افتراضية — هذه مشاريع نفّذناها لعملاء حقيقيين في قطاعات متنوعة. الأرقام الدقيقة تُضاف بعد موافقة العملاء.' ],
        ],
    ] );

    // ═══════════════════════════════════════════════════════════
    // SEO Service Page — Section Headings
    // ═══════════════════════════════════════════════════════════
    acf_add_local_field_group( [
        'key'      => 'group_service_seo_sections',
        'title'    => 'محتوى صفحة السيو — العناوين والأقسام',
        'location' => [ [ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'templates/template-service-seo.php' ] ] ],
        'fields'   => [
            [ 'key' => 'field_seo_lost_title',       'label' => 'عنوان قسم الفرص الضائعة',       'name' => 'seo_lost_title',       'type' => 'text',     'default_value' => 'كل يوم يبحث عملاؤك — وكل يوم تخسر أنت' ],
            [ 'key' => 'field_seo_lost_desc',        'label' => 'وصف قسم الفرص الضائعة',        'name' => 'seo_lost_desc',        'type' => 'textarea', 'rows' => 2, 'default_value' => 'إذا كان موقعك لا يظهر في الصفحة الأولى، فأنت لا تخسر زيارة فقط — أنت تخسر طلباً وعرض سعر وعميلاً يذهب لمنافسك.' ],
            [ 'key' => 'field_seo_why_title',        'label' => 'عنوان قسم لماذا السيو تجارياً', 'name' => 'seo_why_title',        'type' => 'textarea', 'rows' => 2, 'default_value' => "السيو ليس بنداً في الميزانية —\nهو خفض في تكلفة العميل" ],
            [ 'key' => 'field_seo_why_desc',         'label' => 'وصف قسم لماذا السيو تجارياً',  'name' => 'seo_why_desc',         'type' => 'textarea', 'rows' => 2, 'default_value' => 'كل عميل يأتي عبر البحث العضوي يكلّفك أقل من نظيره عبر الإعلان. والأهم — يبقى يأتي حتى بعد توقفك عن الإنفاق.' ],
            [ 'key' => 'field_seo_wwd_title',        'label' => 'عنوان قسم ماذا نفعل',          'name' => 'seo_wwd_title',        'type' => 'text',     'default_value' => 'ستة محاور تنفيذية تشكّل خدمة السيو' ],
            [ 'key' => 'field_seo_wwd_desc',         'label' => 'وصف قسم ماذا نفعل',           'name' => 'seo_wwd_desc',         'type' => 'textarea', 'rows' => 2, 'default_value' => 'السيو ليس مهمة واحدة — هو منظومة من ستة محاور كل واحد منها يدعم الآخر.' ],
            [ 'key' => 'field_seo_sub_title',        'label' => 'عنوان قسم الخدمات الفرعية',    'name' => 'seo_sub_title',        'type' => 'text',     'default_value' => 'اختر الخدمة التي يحتاجها عملك' ],
            [ 'key' => 'field_seo_reporting_title',  'label' => 'عنوان قسم التقارير',           'name' => 'seo_reporting_title',  'type' => 'textarea', 'rows' => 2, 'default_value' => "تقارير تتكلم لغة عملك\n— لا لغة المطورين" ],
            [ 'key' => 'field_seo_reporting_desc',   'label' => 'وصف قسم التقارير',             'name' => 'seo_reporting_desc',   'type' => 'textarea', 'rows' => 2, 'default_value' => 'في معظم الوكالات تأتيك تقارير ممتلئة بمصطلحات جوجل لا تفهم منها شيئاً. عندنا التقرير يُظهر لك بوضوح: كم ليد جاء من البحث، كم بيع، وأين تذهب ميزانيتك.' ],
            [ 'key' => 'field_seo_proof_title',      'label' => 'عنوان قسم النتائج',            'name' => 'seo_proof_title',      'type' => 'text',     'default_value' => 'نتائج حقيقية لعملاء حقيقيين' ],
            [ 'key' => 'field_seo_cta_title',        'label' => 'عنوان CTA',                     'name' => 'seo_cta_title',        'type' => 'text',     'default_value' => 'ابدأ بكسب عملاء يبحثون عنك فعلاً' ],
            [ 'key' => 'field_seo_hero_cta1',        'label' => 'نص زر الهيرو الأول',            'name' => 'seo_hero_cta1',        'type' => 'text',     'default_value' => 'احجز استشارة مجانية — 30 دقيقة' ],
            [ 'key' => 'field_seo_hero_cta2',        'label' => 'نص زر الهيرو الثاني',           'name' => 'seo_hero_cta2',        'type' => 'text',     'default_value' => 'خدماتنا' ],
            [
                'key'          => 'field_seo_hero_stats',
                'label'        => 'إحصائيات الهيرو (4 عناصر)',
                'name'         => 'seo_hero_stats',
                'type'         => 'repeater',
                'min'          => 4,
                'max'          => 4,
                'button_label' => 'إضافة إحصائية',
                'sub_fields'   => [
                    [ 'key' => 'field_seo_stat_n',     'label' => 'الجزء قبل em (أو كل الرقم إن لا em)',  'name' => 'stat_n',     'type' => 'text' ],
                    [ 'key' => 'field_seo_stat_em',    'label' => 'الجزء داخل em (اتركه فارغاً لعدم التمييز)', 'name' => 'stat_em', 'type' => 'text' ],
                    [ 'key' => 'field_seo_stat_label', 'label' => 'التسمية تحت الرقم',             'name' => 'stat_label', 'type' => 'text' ],
                ],
            ],
            [
                'key'          => 'field_seo_lost_cards',
                'label'        => 'بطاقات الفرص الضائعة (4)',
                'name'         => 'seo_lost_cards',
                'type'         => 'repeater',
                'min'          => 4,
                'max'          => 4,
                'button_label' => 'إضافة بطاقة',
                'sub_fields'   => [
                    [ 'key' => 'field_lc_title', 'label' => 'العنوان', 'name' => 'lc_title', 'type' => 'text' ],
                    [ 'key' => 'field_lc_body',  'label' => 'النص',   'name' => 'lc_body',  'type' => 'textarea', 'rows' => 2 ],
                ],
            ],
            [
                'key'          => 'field_seo_why_items',
                'label'        => 'نقاط "لماذا السيو تجارياً" (4)',
                'name'         => 'seo_why_items',
                'type'         => 'repeater',
                'min'          => 4,
                'max'          => 4,
                'button_label' => 'إضافة نقطة',
                'sub_fields'   => [
                    [ 'key' => 'field_wi_label', 'label' => 'العنوان المميز', 'name' => 'wi_label', 'type' => 'text' ],
                    [ 'key' => 'field_wi_desc',  'label' => 'الشرح',         'name' => 'wi_desc',  'type' => 'text' ],
                ],
            ],
            [
                'key'          => 'field_seo_impact_rows',
                'label'        => 'صفوف مقارنة التأثير (4)',
                'name'         => 'seo_impact_rows',
                'type'         => 'repeater',
                'min'          => 4,
                'max'          => 4,
                'button_label' => 'إضافة صف',
                'sub_fields'   => [
                    [ 'key' => 'field_ir_label', 'label' => 'التسمية', 'name' => 'ir_label', 'type' => 'text' ],
                    [ 'key' => 'field_ir_from',  'label' => 'القيمة السابقة', 'name' => 'ir_from', 'type' => 'text' ],
                    [ 'key' => 'field_ir_to',    'label' => 'القيمة الجديدة', 'name' => 'ir_to',   'type' => 'text' ],
                ],
            ],
            [ 'key' => 'field_seo_sub_desc',     'label' => 'وصف قسم الخدمات الفرعية',    'name' => 'seo_sub_desc',     'type' => 'textarea', 'rows' => 2, 'default_value' => 'كل خدمة يمكن تنفيذها مستقلة أو ضمن باقة سيو متكاملة — حسب وضع موقعك ومرحلتك.' ],
            [ 'key' => 'field_seo_method_title', 'label' => 'عنوان قسم المنهجية',          'name' => 'seo_method_title', 'type' => 'text',     'default_value' => 'منهجية شفافة من اليوم الأول' ],
            [ 'key' => 'field_seo_method_desc',  'label' => 'وصف قسم المنهجية',           'name' => 'seo_method_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'كل مرحلة لها هدف واضح ومؤشر قياس — لا خطوات غامضة، لا وعود بدون تنفيذ.' ],
            [ 'key' => 'field_seo_proof_desc',   'label' => 'وصف قسم نتائج العملاء',      'name' => 'seo_proof_desc',   'type' => 'textarea', 'rows' => 2, 'default_value' => 'نماذج من نتائج عملاء سابقين — أرقام موثّقة من Search Console وAnalytics، لا ادعاءات.' ],
            [
                'key'          => 'field_seo_proof_cards',
                'label'        => 'بطاقات نتائج العملاء (3)',
                'name'         => 'seo_proof_cards',
                'type'         => 'repeater',
                'min'          => 3,
                'max'          => 3,
                'button_label' => 'إضافة بطاقة',
                'sub_fields'   => [
                    [ 'key' => 'field_pc_sector', 'label' => 'القطاع',            'name' => 'pc_sector', 'type' => 'text' ],
                    [ 'key' => 'field_pc_result', 'label' => 'النتيجة (مثال: +312%)', 'name' => 'pc_result', 'type' => 'text' ],
                    [ 'key' => 'field_pc_desc',   'label' => 'الوصف',             'name' => 'pc_desc',   'type' => 'textarea', 'rows' => 2 ],
                    [ 'key' => 'field_pc_meta',   'label' => 'مدة التعاون',       'name' => 'pc_meta',   'type' => 'text' ],
                ],
            ],
            [ 'key' => 'field_seo_ind_title',    'label' => 'عنوان قسم القطاعات',         'name' => 'seo_ind_title',    'type' => 'text',     'default_value' => 'السيو يختلف من قطاع إلى آخر — ونحن نفهم ذلك' ],
            [ 'key' => 'field_seo_ind_desc',     'label' => 'وصف قسم القطاعات',          'name' => 'seo_ind_desc',     'type' => 'textarea', 'rows' => 2, 'default_value' => 'لكل قطاع جمهوره، كلماته، وقواعد ترتيبه. نُخصّص الاستراتيجية حسب طبيعة عملك.' ],
            [ 'key' => 'field_seo_faq_title',    'label' => 'عنوان قسم الأسئلة الشائعة', 'name' => 'seo_faq_title',    'type' => 'text',     'default_value' => 'أسئلة يسألها كل صاحب عمل' ],
        ],
    ] );

    // ═══════════════════════════════════════════════════════════
    // Contact Page Options Fields
    // ═══════════════════════════════════════════════════════════
    acf_add_local_field_group( [
        'key'      => 'group_contact_page',
        'title'    => 'محتوى صفحة: التواصل',
        'location' => [ [ [ 'param' => 'options_page', 'operator' => '==', 'value' => 'seohouse-contact' ] ] ],
        'fields'   => [
            [ 'key' => 'field_con_hero_tag',      'label' => 'تصنيف الهيرو',               'name' => 'contact_hero_tag',      'type' => 'text',     'default_value' => 'استشارة مجانية' ],
            [ 'key' => 'field_con_hero_title',    'label' => 'عنوان الهيرو (سطر في كل سطر)', 'name' => 'contact_hero_title',  'type' => 'textarea', 'rows' => 2, 'default_value' => "30 دقيقة قد تغيّر\nمسار موقعك" ],
            [ 'key' => 'field_con_hero_em',       'label' => 'الكلمة المُبرَزة',            'name' => 'contact_hero_em',       'type' => 'text',     'default_value' => 'موقعك' ],
            [ 'key' => 'field_con_hero_desc',     'label' => 'وصف الهيرو',                  'name' => 'contact_hero_desc',     'type' => 'textarea', 'rows' => 2, 'default_value' => 'أخبرنا عن موقعك ونشاطك — وسنخبرك بصدق أين أنت وما الذي يمكن تحقيقه. مجاناً، وبدون التزام.' ],
            [ 'key' => 'field_con_form_title',    'label' => 'عنوان النموذج',               'name' => 'contact_form_title',    'type' => 'text',     'default_value' => 'أرسل لنا طلبك' ],
            [ 'key' => 'field_con_form_sub',      'label' => 'وصف تحت عنوان النموذج',       'name' => 'contact_form_sub',      'type' => 'textarea', 'rows' => 2, 'default_value' => 'سنتواصل معك خلال 24 ساعة لتأكيد موعد الاستشارة.' ],
            [ 'key' => 'field_con_form_note',     'label' => 'الملاحظة تحت زر الإرسال',    'name' => 'contact_form_note',     'type' => 'text',     'default_value' => 'أو تواصل معنا على واتساب مباشرةً — سنردّ في أقرب وقت' ],
            [ 'key' => 'field_con_success_title', 'label' => 'عنوان رسالة الإرسال الناجح', 'name' => 'contact_success_title', 'type' => 'text',     'default_value' => 'تم الإرسال بنجاح!' ],
            [ 'key' => 'field_con_success_desc',  'label' => 'نص رسالة الإرسال الناجح',    'name' => 'contact_success_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'شكراً على تواصلك — سيتصل بك أحد متخصصينا خلال 24 ساعة لتحديد موعد الاستشارة.' ],
            [ 'key' => 'field_con_cal_title',     'label' => 'عنوان بطاقة الحجز',          'name' => 'contact_cal_title',     'type' => 'text',     'default_value' => 'احجز وقتك مباشرةً' ],
            [ 'key' => 'field_con_expect_title',  'label' => 'عنوان قسم "ماذا تتوقع"',     'name' => 'contact_expect_title',  'type' => 'text',     'default_value' => 'ماذا تتوقع بعد التواصل' ],
            [
                'key'          => 'field_con_expect_items',
                'label'        => 'عناصر قسم "ماذا تتوقع" (4)',
                'name'         => 'contact_expect_items',
                'type'         => 'repeater',
                'min'          => 4,
                'max'          => 6,
                'button_label' => 'إضافة عنصر',
                'sub_fields'   => [
                    [ 'key' => 'field_con_exp_text', 'label' => 'النص', 'name' => 'exp_text', 'type' => 'text' ],
                ],
            ],
        ],
    ] );

} );
