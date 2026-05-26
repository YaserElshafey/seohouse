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
            [ 'key' => 'field_hero_headline',    'label' => 'العنوان الرئيسي للهيرو',    'name' => 'hero_headline',    'type' => 'text',     'default_value' => 'كن آخر نقرة يبحث عنها عميلك' ],
            [ 'key' => 'field_hero_emphasis',    'label' => 'الكلمة المتدرجة (باللون)',  'name' => 'hero_emphasis',    'type' => 'text',     'default_value' => 'آخر نقرة' ],
            [ 'key' => 'field_hero_subtext',     'label' => 'النص التعريفي في الهيرو',   'name' => 'hero_subtext',     'type' => 'textarea', 'rows' => 2, 'default_value' => 'نضع موقعك أمام العملاء الذين يبحثون عمّا تقدّمه — بدقة، بأدلة، وبنتائج قابلة للقياس.' ],
            [ 'key' => 'field_hero_cta_primary', 'label' => 'نص الزر الرئيسي',          'name' => 'hero_cta_primary', 'type' => 'text',     'default_value' => 'احجز استشارة مجانية 30 دقيقة' ],
            [ 'key' => 'field_why_title',        'label' => 'عنوان قسم "لماذا نحن"',    'name' => 'why_title',        'type' => 'text',     'default_value' => 'نعمل بمنطق الأداء، لا بمنطق الوعود' ],
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
        ],
    ] );

} );
