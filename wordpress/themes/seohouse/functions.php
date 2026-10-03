<?php
/**
 * SEO House theme bootstrap.
 *
 * The theme renders the approved design. Content, post types and field
 * definitions live in the "SEO House Core" plugin so they survive a theme
 * change. Every ACF call goes through helpers that fail safely when ACF or
 * Core is missing (see inc/dependencies.php).
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

define( 'SH_THEME_VERSION', '2.4.0' );
define( 'SH_THEME_DIR', get_template_directory() );
define( 'SH_THEME_URI', get_template_directory_uri() );

require SH_THEME_DIR . '/inc/dependencies.php';
require SH_THEME_DIR . '/inc/setup.php';
require SH_THEME_DIR . '/inc/helpers.php';
require SH_THEME_DIR . '/inc/enqueue.php';
require SH_THEME_DIR . '/inc/navigation.php';
require SH_THEME_DIR . '/inc/breadcrumbs.php';
require SH_THEME_DIR . '/inc/render.php';
require SH_THEME_DIR . '/inc/dynamic.php';
