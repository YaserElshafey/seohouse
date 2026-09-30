<?php
/**
 * Theme supports, menus, image sizes and front-end clean-up.
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	static function () {
		load_theme_textdomain( 'seohouse', SH_THEME_DIR . '/languages' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
		add_theme_support( 'editor-styles' );
		add_editor_style( array( 'assets/css/fonts.css', 'assets/css/editor.css' ) );

		register_nav_menus(
			array(
				'primary' => __( 'القائمة الرئيسية (الهيدر والقائمة الجانبية)', 'seohouse' ),
				'footer'  => __( 'أعمدة الفوتر (العناصر العليا = عناوين الأعمدة)', 'seohouse' ),
				'legal'   => __( 'روابط أسفل الفوتر', 'seohouse' ),
			)
		);

		add_image_size( 'sh-card', 720, 480, true );
		add_image_size( 'sh-wide', 1440, 0, false );
		add_image_size( 'sh-portrait', 480, 600, true );
	}
);

// Front-end weight: no emoji polyfill, no block CSS on design templates.
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );

add_action(
	'wp_enqueue_scripts',
	static function () {
		if ( ! is_singular( 'post' ) && ! is_page_template( 'default' ) ) {
			wp_dequeue_style( 'wp-block-library' );
			wp_dequeue_style( 'wp-block-library-theme' );
			wp_dequeue_style( 'global-styles' );
			wp_dequeue_style( 'classic-theme-styles' );
		}
	},
	100
);

/** Excerpt length suited to the design cards. */
add_filter( 'excerpt_length', static fn() => 26 );
add_filter( 'excerpt_more', static fn() => '…' );
