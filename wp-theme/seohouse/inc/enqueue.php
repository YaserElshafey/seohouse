<?php
defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', function () {

    $ver = wp_get_theme()->get( 'Version' );

    // Main design system CSS (font loaded non-blocking via wp_head preload below)
    wp_enqueue_style(
        'seohouse-shared',
        get_template_directory_uri() . '/assets/css/shared.css',
        [],
        $ver
    );

    // Theme-specific styles (page-level inline additions)
    wp_enqueue_style(
        'seohouse-theme',
        get_template_directory_uri() . '/assets/css/theme.css',
        [ 'seohouse-shared' ],
        $ver
    );

    // Main JS
    wp_enqueue_script(
        'seohouse-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        $ver,
        true
    );

    // Pass data to JS
    wp_localize_script( 'seohouse-main', 'SeohouseData', [
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'seohouse_nonce' ),
        'homeUrl' => home_url( '/' ),
    ] );

    // Comment reply script on single posts
    if ( is_singular() && comments_open() ) {
        wp_enqueue_script( 'comment-reply' );
    }

}, 10 );

// SEO Pricing V2 — page-specific stylesheet (loaded only on this template)
add_action( 'wp_enqueue_scripts', function () {
    if ( ! is_page_template( 'templates/template-seo-pricing-v2.php' ) ) return;
    wp_enqueue_style(
        'seohouse-seo-pricing-v2',
        get_template_directory_uri() . '/assets/css/seo-pricing-v2.css',
        [ 'seohouse-theme' ],
        (string) filemtime( get_template_directory() . '/assets/css/seo-pricing-v2.css' )
    );
}, 10 );

// SEO Service page — page-specific stylesheet (loaded only on this template)
add_action( 'wp_enqueue_scripts', function () {
    if ( ! is_page_template( 'templates/template-service-seo.php' ) ) return;
    wp_enqueue_style(
        'seohouse-service-seo',
        get_template_directory_uri() . '/assets/css/service-seo.css',
        [ 'seohouse-theme' ],
        (string) filemtime( get_template_directory() . '/assets/css/service-seo.css' )
    );
}, 10 );

// Remove WordPress block/global styles — this is a custom theme that does not use the block editor
add_action( 'wp_enqueue_scripts', function () {
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wp-block-library-theme-deprecated' );
    wp_dequeue_style( 'global-styles' );
    wp_dequeue_style( 'classic-theme-styles' );
    wp_deregister_style( 'classic-theme-styles' );
    remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
    remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
}, 100 );

// Preconnect + non-blocking font load — removes render-blocking Cairo stylesheet
add_action( 'wp_head', function () {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    $f = 'https://fonts.googleapis.com/css2?family=Alexandria:wght@400;500;600;700;800;900&family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&family=Tajawal:wght@400;500;700&display=swap';
    echo '<link rel="preload" as="style" href="' . esc_url( $f ) . '" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
    echo '<noscript><link rel="stylesheet" href="' . esc_url( $f ) . '"></noscript>' . "\n";
}, 1 );
