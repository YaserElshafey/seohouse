<?php
defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', function () {

    register_nav_menus( [
        'main-menu'           => 'القائمة الرئيسية (موبايل)',
        'footer-menu-seo'     => 'فوتر — تحسين محركات البحث',
        'footer-menu-web'     => 'فوتر — تصميم المواقع',
        'footer-menu-stores'  => 'فوتر — إنشاء المتاجر',
        'footer-menu-company' => 'فوتر — الشركة',
    ] );

} );
