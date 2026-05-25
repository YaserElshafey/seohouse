<?php
defined( 'ABSPATH' ) || exit;

// ── Load includes ────────────────────────────────────────────────
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/cpt.php';
require_once get_template_directory() . '/inc/menus.php';
require_once get_template_directory() . '/inc/acf-fields.php';
require_once get_template_directory() . '/inc/helpers.php';

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
    add_image_size( 'seohouse-team',       400, 400, true );
    add_image_size( 'seohouse-case-thumb', 800, 450, true );
    add_image_size( 'seohouse-blog-thumb', 800, 450, true );
    add_image_size( 'seohouse-client-logo', 300, 120, false );
    add_image_size( 'seohouse-platform-icon', 120, 120, false );

} );

// ── Content width ─────────────────────────────────────────────────
if ( ! isset( $content_width ) ) {
    $content_width = 1200;
}

// ── Excerpt length ────────────────────────────────────────────────
add_filter( 'excerpt_length', fn() => 28 );
add_filter( 'excerpt_more', fn() => '...' );
