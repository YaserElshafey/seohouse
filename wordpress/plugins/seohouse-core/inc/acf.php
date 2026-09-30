<?php
/**
 * ACF integration: field definitions are loaded from acf-json/ (Local JSON, one source),
 * the settings page is an ACF Options Page, and a clear notice is shown when ACF with
 * Repeater/Flexible Content (ACF PRO or Secure Custom Fields) is not active.
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

/** Load field groups from the plugin. */
add_filter(
	'acf/settings/load_json',
	static function ( $paths ) {
		$paths[] = SH_CORE_DIR . 'acf-json';
		return $paths;
	}
);

/**
 * Save edited groups back to the plugin only on development environments;
 * production edits to field definitions should go through the source repository.
 */
add_filter(
	'acf/settings/save_json',
	static function ( $path ) {
		return ( 'production' !== wp_get_environment_type() && defined( 'SH_ACF_SAVE_JSON' ) && SH_ACF_SAVE_JSON ) ? SH_CORE_DIR . 'acf-json' : $path;
	}
);

add_action(
	'acf/init',
	static function () {
		if ( ! function_exists( 'acf_add_options_page' ) ) {
			return;
		}
		acf_add_options_page(
			array(
				'page_title'      => __( 'إعدادات سيو هاوس', 'seohouse-core' ),
				'menu_title'      => __( 'إعدادات سيو هاوس', 'seohouse-core' ),
				'menu_slug'       => 'seohouse-settings',
				'capability'      => 'manage_options',
				'position'        => 3,
				'icon_url'        => 'dashicons-admin-generic',
				'redirect'        => false,
				'update_button'   => __( 'حفظ الإعدادات', 'seohouse-core' ),
				'updated_message' => __( 'تم حفظ الإعدادات.', 'seohouse-core' ),
				'autoload'        => true,
			)
		);
	}
);

add_action(
	'admin_notices',
	static function () {
		if ( ! current_user_can( 'activate_plugins' ) || sh_core_acf_ready() ) {
			return;
		}
		echo '<div class="notice notice-error"><p><strong>' . esc_html__( 'SEO House Core:', 'seohouse-core' ) . '</strong> ';
		echo esc_html__( 'تحتاج الحقول إلى ACF PRO أو Secure Custom Fields (تتضمن Repeater وFlexible Content وOptions). الحقول والإعدادات وأداة تجهيز المحتوى معطلة حتى التفعيل؛ لم يُحذف أي محتوى.', 'seohouse-core' );
		echo '</p></div>';
	}
);

/** Editors edit content; only administrators see the global settings (capability above). */

/** Readable row titles in repeaters/flexible layouts: use the first text value. */
add_filter(
	'acf/fields/flexible_content/layout_title',
	static function ( $title, $field, $layout, $i ) {
		if ( ! function_exists( 'get_sub_field' ) ) {
			return $title;
		}
		$hidden = get_sub_field( 'sh_hide' );
		$t      = get_sub_field( 'title' );
		$label  = esc_html( wp_strip_all_tags( (string) $t ) );
		return $title . ( $label ? ' — <span style="font-weight:400">' . $label . '</span>' : '' ) . ( $hidden ? ' <em style="color:#b32d2e">(' . esc_html__( 'مخفي', 'seohouse-core' ) . ')</em>' : '' );
	},
	10,
	4
);

/** Allow the approved SVG icon select to list the design icon library (labels from the theme). */
add_filter(
	'acf/prepare_field/type=select',
	static function ( $field ) {
		return $field;
	}
);
