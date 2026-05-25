<?php
defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', function () {

    $ver = wp_get_theme()->get( 'Version' );

    // Google Fonts — Cairo
    wp_enqueue_style(
        'seohouse-fonts',
        'https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap',
        [],
        null
    );

    // Main design system CSS
    wp_enqueue_style(
        'seohouse-shared',
        get_template_directory_uri() . '/assets/css/shared.css',
        [ 'seohouse-fonts' ],
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

} );

// Preconnect for Google Fonts
add_action( 'wp_head', function () {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}, 1 );
