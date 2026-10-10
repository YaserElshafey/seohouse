<?php
defined( 'ABSPATH' ) || exit;

// ════════════════════════════════════════════════════════════════
// Custom Post Types
// ════════════════════════════════════════════════════════════════

add_action( 'init', function () {

    // ── Case Studies ──────────────────────────────────────────
    register_post_type( 'case_study', [
        'labels' => [
            'name'               => 'نتائج الأعمال',
            'singular_name'      => 'دراسة حالة',
            'add_new'            => 'إضافة دراسة حالة',
            'add_new_item'       => 'إضافة دراسة حالة جديدة',
            'edit_item'          => 'تعديل دراسة الحالة',
            'new_item'           => 'دراسة حالة جديدة',
            'view_item'          => 'عرض دراسة الحالة',
            'search_items'       => 'بحث في دراسات الحالة',
            'not_found'          => 'لا توجد دراسات حالة',
            'not_found_in_trash' => 'لا توجد دراسات حالة في المهملات',
            'menu_name'          => 'نتائج الأعمال',
        ],
        'public'              => true,
        'has_archive'         => true,
        'rewrite'             => [ 'slug' => 'results', 'with_front' => false ],
        'menu_icon'           => 'dashicons-chart-bar',
        'menu_position'       => 5,
        'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ],
        'show_in_rest'        => true,
        'taxonomies'          => [ 'case_study_sector' ],
    ] );

    // ── Team Members ──────────────────────────────────────────
    register_post_type( 'team_member', [
        'labels' => [
            'name'               => 'فريق العمل',
            'singular_name'      => 'عضو الفريق',
            'add_new'            => 'إضافة عضو',
            'add_new_item'       => 'إضافة عضو جديد',
            'edit_item'          => 'تعديل العضو',
            'new_item'           => 'عضو جديد',
            'search_items'       => 'بحث في الفريق',
            'not_found'          => 'لا يوجد أعضاء',
            'menu_name'          => 'فريق العمل',
        ],
        'public'              => true,
        'publicly_queryable'  => false,
        'has_archive'         => false,
        'rewrite'             => false,
        'menu_icon'           => 'dashicons-groups',
        'menu_position'       => 6,
        'supports'            => [ 'title', 'thumbnail', 'page-attributes' ],
        'show_in_rest'        => true,
    ] );

    // ── Clients ───────────────────────────────────────────────
    register_post_type( 'client', [
        'labels' => [
            'name'               => 'العملاء',
            'singular_name'      => 'عميل',
            'add_new'            => 'إضافة عميل',
            'add_new_item'       => 'إضافة عميل جديد',
            'edit_item'          => 'تعديل العميل',
            'new_item'           => 'عميل جديد',
            'search_items'       => 'بحث في العملاء',
            'not_found'          => 'لا يوجد عملاء',
            'menu_name'          => 'العملاء',
        ],
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'has_archive'         => false,
        'rewrite'             => false,
        'menu_icon'           => 'dashicons-businessman',
        'menu_position'       => 7,
        'supports'            => [ 'title', 'page-attributes' ],
        'show_in_rest'        => true,
    ] );

    // ── Platforms ─────────────────────────────────────────────
    register_post_type( 'platform', [
        'labels' => [
            'name'               => 'المنصّات',
            'singular_name'      => 'منصّة',
            'add_new'            => 'إضافة منصّة',
            'add_new_item'       => 'إضافة منصّة جديدة',
            'edit_item'          => 'تعديل المنصّة',
            'new_item'           => 'منصّة جديدة',
            'search_items'       => 'بحث في المنصّات',
            'not_found'          => 'لا توجد منصّات',
            'menu_name'          => 'المنصّات',
        ],
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'has_archive'         => false,
        'rewrite'             => false,
        'menu_icon'           => 'dashicons-grid-view',
        'menu_position'       => 8,
        'supports'            => [ 'title', 'page-attributes' ],
        'show_in_rest'        => true,
    ] );

    // ── Sectors ───────────────────────────────────────────────
    register_post_type( 'sector', [
        'labels' => [
            'name'               => 'القطاعات',
            'singular_name'      => 'قطاع',
            'add_new'            => 'إضافة قطاع',
            'add_new_item'       => 'إضافة قطاع جديد',
            'edit_item'          => 'تعديل القطاع',
            'new_item'           => 'قطاع جديد',
            'view_item'          => 'عرض القطاع',
            'search_items'       => 'بحث في القطاعات',
            'not_found'          => 'لا توجد قطاعات',
            'menu_name'          => 'القطاعات',
        ],
        'public'              => true,
        'has_archive'         => true,
        'rewrite'             => [ 'slug' => 'sectors', 'with_front' => false ],
        'menu_icon'           => 'dashicons-category',
        'menu_position'       => 9,
        'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ],
        'show_in_rest'        => true,
    ] );

    // ── Contact requests (contact form submissions) ──────────────
    // Post type slug kept as sh_booking so earlier entries stay in the same list.
    register_post_type( 'sh_booking', [
        'labels'       => [
            'name'          => 'طلبات التواصل',
            'menu_name'     => 'طلبات التواصل',
            'singular_name' => 'طلب تواصل',
            'all_items'     => 'جميع الطلبات',
            'edit_item'     => 'تفاصيل الطلب',
            'search_items'  => 'بحث في الطلبات',
            'not_found'     => 'لا توجد طلبات بعد',
        ],
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => true,
        'supports'     => [ 'title' ],
        'menu_icon'    => 'dashicons-email-alt',
        'capabilities' => [ 'create_posts' => 'do_not_allow' ],
        'map_meta_cap' => true,
    ] );

} );

// ── Contact request admin columns ─────────────────────────────────
add_filter( 'manage_sh_booking_posts_columns', function ( $cols ) {
    return [
        'cb'             => $cols['cb'],
        'title'          => 'الاسم',
        'bk_phone'       => 'الجوال',
        'bk_email'       => 'البريد',
        'bk_service'     => 'الخدمة',
        'date_submitted' => 'وقت الإرسال',
    ];
} );

add_action( 'manage_sh_booking_posts_custom_column', function ( $col, $post_id ) {
    if ( in_array( $col, [ 'bk_phone', 'bk_email', 'bk_service' ], true ) ) {
        echo esc_html( get_post_meta( $post_id, $col, true ) );
    }
    if ( 'date_submitted' === $col ) {
        echo esc_html( get_the_date( 'Y-m-d H:i', $post_id ) );
    }
}, 10, 2 );

// ── Contact request details (read-only box on the edit screen) ────
add_action( 'add_meta_boxes_sh_booking', function () {
    add_meta_box( 'sh_booking_details', 'بيانات الطلب', function ( $post ) {
        $rows = [
            'bk_phone'   => 'الجوال',
            'bk_email'   => 'البريد',
            'bk_website' => 'الموقع',
            'bk_service' => 'الخدمة',
            'bk_plan'    => 'الباقة المختارة',
            'bk_message' => 'الرسالة',
            'bk_source'  => 'صفحة الإرسال',
            'bk_date'    => 'التاريخ المطلوب',
            'bk_time'    => 'الوقت المطلوب',
        ];
        echo '<table class="widefat striped"><tbody>';
        foreach ( $rows as $key => $label ) {
            $val = (string) get_post_meta( $post->ID, $key, true );
            if ( '' === $val ) {
                continue;
            }
            echo '<tr><th style="width:160px">' . esc_html( $label ) . '</th><td>' . nl2br( esc_html( $val ) ) . '</td></tr>';
        }
        echo '<tr><th>وقت الإرسال</th><td>' . esc_html( get_the_date( 'Y-m-d H:i', $post ) ) . '</td></tr>';
        if ( get_post_meta( $post->ID, 'bk_mail_failed', true ) ) {
            echo '<tr><th>تنبيه</th><td style="color:#b32d2e">تعذّر إرسال إشعار البريد لهذا الطلب — راجع إعدادات البريد في الموقع.</td></tr>';
        }
        echo '</tbody></table>';
    }, 'sh_booking', 'normal', 'high' );
} );

// ════════════════════════════════════════════════════════════════
// Taxonomies
// ════════════════════════════════════════════════════════════════

add_action( 'init', function () {

    // Case study sector taxonomy (for filtering on results page)
    register_taxonomy( 'case_study_sector', 'case_study', [
        'labels' => [
            'name'          => 'القطاعات',
            'singular_name' => 'قطاع',
            'add_new_item'  => 'إضافة قطاع',
            'edit_item'     => 'تعديل القطاع',
            'search_items'  => 'بحث في القطاعات',
            'not_found'     => 'لا توجد قطاعات',
        ],
        'public'            => true,
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'rewrite'           => [ 'slug' => 'results/sector', 'with_front' => false ],
    ] );

} );

// Flush rewrite rules on theme activation
add_action( 'after_switch_theme', function () {
    flush_rewrite_rules();
} );
