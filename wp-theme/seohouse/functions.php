<?php
defined( 'ABSPATH' ) || exit;

// ── Load includes ────────────────────────────────────────────────
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/cpt.php';
require_once get_template_directory() . '/inc/menus.php';
require_once get_template_directory() . '/inc/acf-fields.php';
require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/ajax-handlers.php';

// ── Theme setup ───────────────────────────────────────────────────
add_action( 'after_setup_theme', function () {

    load_theme_textdomain( 'seohouse', get_template_directory() . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Custom logo support
    add_theme_support( 'custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ] );

    // Image sizes
    add_image_size( 'seohouse-team',       400, 400, [ 'center', 'top' ] );
    add_image_size( 'seohouse-case-thumb', 800, 450, true );
    add_image_size( 'seohouse-blog-thumb', 800, 450, true );
    add_image_size( 'seohouse-client-logo', 300, 120, false );
    add_image_size( 'seohouse-platform-icon', 120, 120, false );

} );

// ── Content width ─────────────────────────────────────────────────
if ( ! isset( $content_width ) ) {
    $content_width = 1200;
}

// ── Permalink structure: /blog/%postname%/ ────────────────────────
add_action( 'after_switch_theme', function () {
    update_option( 'permalink_structure', '/blog/%postname%/' );
    flush_rewrite_rules();
} );

// One-time setter for already-active installs
add_action( 'admin_init', function () {
    if ( get_option( 'seohouse_permalink_v1' ) ) return;
    update_option( 'permalink_structure', '/blog/%postname%/' );
    flush_rewrite_rules();
    update_option( 'seohouse_permalink_v1', '1' );
} );

// ── Excerpt length ────────────────────────────────────────────────
add_filter( 'excerpt_length', fn() => 28 );
add_filter( 'excerpt_more', fn() => '...' );
