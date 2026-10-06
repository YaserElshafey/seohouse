<?php
/**
 * Daily admin after the move (2.7.3).
 *
 * - The one-time tools of the move — «تهيئة الموقع», «نقل المقالات», «نقل عناوين وأوصاف SEO»,
 *   «نقل السيو إلى Rank Math» — leave the «إعدادات سيو هاوس» menu. They stay in the plugin for
 *   maintenance and appear only after «إدارة الإعدادات ← أدوات الصيانة» is switched on (off by
 *   default). While hidden their screens and the setup steps cannot be opened or run; none of them
 *   runs on its own. A site that was never initialised still sees «تهيئة الموقع».
 * - The «تحديث محتوى التصميم متاح» notice is not shown on an initialised site (it only appears
 *   inside «تهيئة الموقع» when the tools are switched on).
 * - Settings that no longer affect the site are hidden from «إعدادات سيو هاوس»; their field
 *   definitions and saved values are kept (export / import of settings still carries them):
 *   the 2.x colour tab, the unused WhatsApp number, the booking-tool fields (no calendar since 2.7.3).
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

const SH_MAINTENANCE_SLUGS = array( 'seohouse-content-setup', 'seohouse-migrate-posts', 'seohouse-seo-transfer', 'seohouse-rankmath' );

/** Maintenance tools visible: switched on in «إدارة الإعدادات», or a site that was never initialised (setup only). */
function sh_core_tools_visible( string $slug = '' ): bool {
	if ( get_option( 'sh_show_maintenance_tools' ) ) {
		return true;
	}
	return 'seohouse-content-setup' === $slug && ! get_option( 'sh_content_last_import' );
}

add_action(
	'admin_menu',
	static function () {
		foreach ( SH_MAINTENANCE_SLUGS as $slug ) {
			if ( ! sh_core_tools_visible( $slug ) ) {
				remove_submenu_page( 'seohouse-settings', $slug );
			}
		}
	},
	999
);

/** Fields kept in the database but no longer shown (see header). */
const SH_HIDDEN_SETTING_KEYS = array(
	'field_sh_opt_tab_design',
	'field_sh_opt_design_msg',
	'field_sh_opt_c_ink',
	'field_sh_opt_c_blue',
	'field_sh_opt_c_sky',
	'field_sh_opt_c_lime',
	'field_sh_opt_c_paper',
	'field_sh_opt_c_text',
	'field_sh_opt_c_muted',
	'field_sh_opt_whatsapp',
	'field_sh_opt_booking_provider',
	'field_sh_opt_booking_url',
	'field_sh_opt_booking_duration',
	'field_sh_opt_booking_token',
	'field_sh_opt_booking__step2__title',
);

add_filter(
	'acf/prepare_field',
	static function ( $field ) {
		if ( is_array( $field ) && is_admin() && in_array( $field['key'] ?? '', SH_HIDDEN_SETTING_KEYS, true ) ) {
			return false;
		}
		return $field;
	},
	5
);

/** «إدارة الإعدادات» → «أدوات الصيانة» switch. */
function sh_core_tools_switch_html(): string {
	$on   = (bool) get_option( 'sh_show_maintenance_tools' );
	$html = '<h2 style="margin-top:32px">' . esc_html__( 'أدوات الصيانة (متقدم)', 'seohouse-core' ) . '</h2>';
	$html .= '<p style="max-width:62em">' . esc_html__( 'أدوات مرحلة النقل والتجهيز: تهيئة الموقع، نقل المقالات، نقل عناوين وأوصاف SEO، نقل السيو إلى Rank Math. لا تحتاجها في الاستخدام اليومي ولا تعمل تلقائيًا. «تهيئة الموقع» قد تعيد كتابة محتوى الصفحات التي لم تُعدَّل؛ لا تشغّلها على الموقع المجهز.', 'seohouse-core' ) . '</p>';
	$html .= '<form method="post">' . wp_nonce_field( 'sh_tools_visibility', '_wpnonce', true, false );
	$html .= '<label><input type="checkbox" name="sh_show_tools" value="1"' . checked( $on, true, false ) . '> ' . esc_html__( 'إظهار أدوات الصيانة في قائمة «إعدادات سيو هاوس»', 'seohouse-core' ) . '</label> ';
	$html .= '<button class="button" name="sh_tools_visibility" value="1">' . esc_html__( 'حفظ', 'seohouse-core' ) . '</button></form>';
	return $html;
}

add_action(
	'admin_init',
	static function () {
		if ( empty( $_POST['sh_tools_visibility'] ) || ! current_user_can( 'manage_options' ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- verified below
			return;
		}
		check_admin_referer( 'sh_tools_visibility' );
		update_option( 'sh_show_maintenance_tools', empty( $_POST['sh_show_tools'] ) ? 0 : 1, false );
		wp_safe_redirect( admin_url( 'admin.php?page=seohouse-settings-tools&sh_tools=' . ( empty( $_POST['sh_show_tools'] ) ? 'off' : 'on' ) ) );
		exit;
	}
);
