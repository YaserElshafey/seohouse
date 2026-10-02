<?php
/**
 * Plugin Name: SEO House Core
 * Description: أنواع المحتوى والحقول وأداة تجهيز المحتوى وطلبات الاستشارة والسيو والبيانات المنظمة لموقع سيو هاوس. مستقلة عن الثيم حتى لا تضيع البيانات عند تغيير المظهر.
 * Version: 2.2.1
 * Requires at least: 6.5
 * Requires PHP: 8.1
 * Author: SEO House
 * Text Domain: seohouse-core
 * Domain Path: /languages
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

define( 'SH_CORE_VERSION', '2.2.1' );
define( 'SH_CORE_FILE', __FILE__ );
define( 'SH_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'SH_CORE_URL', plugin_dir_url( __FILE__ ) );

require SH_CORE_DIR . 'inc/helpers.php';
require SH_CORE_DIR . 'inc/post-types.php';
require SH_CORE_DIR . 'inc/acf.php';
require SH_CORE_DIR . 'inc/breadcrumbs.php';
require SH_CORE_DIR . 'inc/seo.php';
require SH_CORE_DIR . 'inc/schema.php';
require SH_CORE_DIR . 'inc/leads.php';
require SH_CORE_DIR . 'inc/tracking.php';
require SH_CORE_DIR . 'inc/search.php';
require SH_CORE_DIR . 'inc/redirects.php';
require SH_CORE_DIR . 'inc/media.php';
require SH_CORE_DIR . 'inc/settings-tools.php';
require SH_CORE_DIR . 'inc/importer/class-importer.php';
require SH_CORE_DIR . 'inc/importer/admin.php';

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require SH_CORE_DIR . 'inc/importer/cli.php';
}

register_activation_hook(
	__FILE__,
	static function () {
		sh_core_register_post_types();
		flush_rewrite_rules( false );
		if ( ! get_option( 'sh_content_last_import' ) ) {
			update_option( 'sh_setup_redirect', 1, false );
		}
	}
);
register_deactivation_hook( __FILE__, static fn() => flush_rewrite_rules( false ) );

add_action( 'init', static fn() => load_plugin_textdomain( 'seohouse-core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ) );
