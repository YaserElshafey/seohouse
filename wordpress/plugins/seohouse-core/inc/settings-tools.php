<?php
/**
 * "إدارة الإعدادات": export / import / restore defaults of the SEO House settings.
 * Import shows a preview of changed keys before applying. Administrators only.
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

/** Option field groups by tab (names from acf-json/group_sh_options.json). */
function sh_settings_tabs(): array {
	$file = SH_CORE_DIR . 'acf-json/group_sh_options.json';
	$g    = json_decode( (string) file_get_contents( $file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	$tabs = array();
	$cur  = '';
	foreach ( (array) ( $g['fields'] ?? array() ) as $f ) {
		if ( 'tab' === $f['type'] ) {
			$cur          = $f['label'];
			$tabs[ $cur ] = array();
		} elseif ( 'message' !== $f['type'] && $cur ) {
			$tabs[ $cur ][] = $f;
		}
	}
	return $tabs;
}

function sh_settings_export(): array {
	$out = array( '_format' => 'seohouse-settings', '_version' => SH_CORE_VERSION, 'values' => array() );
	foreach ( sh_settings_tabs() as $fields ) {
		foreach ( $fields as $f ) {
			$out['values'][ $f['name'] ] = get_field( $f['key'], 'option', false );
		}
	}
	return $out;
}

add_action(
	'admin_menu',
	static function () {
		add_submenu_page( 'seohouse-settings', __( 'إدارة الإعدادات', 'seohouse-core' ), __( 'إدارة الإعدادات', 'seohouse-core' ), 'manage_options', 'seohouse-settings-tools', 'sh_settings_tools_page' );
	},
	20
);

function sh_settings_tools_page(): void {
	if ( ! current_user_can( 'manage_options' ) || ! sh_core_acf_ready() ) {
		echo '<div class="wrap"><p>' . esc_html__( 'غير متاح.', 'seohouse-core' ) . '</p></div>';
		return;
	}
	$preview = null;
	$notice  = '';
	if ( isset( $_POST['sh_tools_action'] ) && check_admin_referer( 'sh_settings_tools' ) ) {
		$action = sanitize_key( wp_unslash( $_POST['sh_tools_action'] ) );
		if ( 'preview' === $action || 'apply' === $action ) {
			$raw  = isset( $_POST['sh_import'] ) ? wp_unslash( $_POST['sh_import'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- JSON validated below.
			$data = json_decode( (string) $raw, true );
			if ( ! is_array( $data ) || ( $data['_format'] ?? '' ) !== 'seohouse-settings' ) {
				$notice = __( 'الملف ليس تصدير إعدادات سيو هاوس.', 'seohouse-core' );
			} else {
				$current = sh_settings_export()['values'];
				$allowed = array_keys( $current );
				$preview = array();
				foreach ( (array) $data['values'] as $k => $v ) {
					if ( in_array( $k, $allowed, true ) && wp_json_encode( $v ) !== wp_json_encode( $current[ $k ] ) ) {
						$preview[ $k ] = $v;
					}
				}
				if ( 'apply' === $action ) {
					$keys = array();
					foreach ( sh_settings_tabs() as $fields ) {
						foreach ( $fields as $f ) {
							$keys[ $f['name'] ] = $f['key'];
						}
					}
					foreach ( $preview as $k => $v ) {
						update_field( $keys[ $k ], $v, 'option' );
					}
					$notice  = sprintf( __( 'طُبّق %d تغييرًا.', 'seohouse-core' ), count( $preview ) );
					$preview = null;
				}
			}
		} elseif ( 'reset' === $action ) {
			$tab = sanitize_text_field( wp_unslash( $_POST['sh_tab'] ?? '' ) );
			foreach ( sh_settings_tabs()[ $tab ] ?? array() as $f ) {
				if ( array_key_exists( 'default_value', $f ) && '' !== $f['default_value'] ) {
					update_field( $f['key'], $f['default_value'], 'option' );
				} else {
					delete_field( $f['key'], 'option' );
				}
			}
			$notice = sprintf( __( 'أُعيدت قيم «%s» إلى الافتراضي المعتمد.', 'seohouse-core' ), $tab );
		}
	}
	echo '<div class="wrap"><h1>' . esc_html__( 'إدارة إعدادات سيو هاوس', 'seohouse-core' ) . '</h1>';
	if ( $notice ) {
		echo '<div class="notice notice-info"><p>' . esc_html( $notice ) . '</p></div>';
	}
	$export = wp_json_encode( sh_settings_export(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
	echo '<h2>' . esc_html__( 'تصدير', 'seohouse-core' ) . '</h2><p>' . esc_html__( 'انسخ النص أو نزّله ملفًا للاحتفاظ بنسخة من الإعدادات (لا تتضمن المحتوى أو الطلبات).', 'seohouse-core' ) . '</p>';
	echo '<textarea readonly rows="8" class="large-text code" dir="ltr">' . esc_textarea( $export ) . '</textarea>';
	echo '<p><a class="button" download="seohouse-settings.json" href="data:application/json;charset=utf-8,' . rawurlencode( $export ) . '">' . esc_html__( 'تنزيل الملف', 'seohouse-core' ) . '</a></p>';

	echo '<h2>' . esc_html__( 'استيراد', 'seohouse-core' ) . '</h2><form method="post">';
	wp_nonce_field( 'sh_settings_tools' );
	$val = isset( $_POST['sh_import'] ) && null !== $preview ? wp_unslash( $_POST['sh_import'] ) : ''; // phpcs:ignore
	echo '<textarea name="sh_import" rows="6" class="large-text code" dir="ltr">' . esc_textarea( (string) $val ) . '</textarea>';
	if ( is_array( $preview ) ) {
		echo '<h3>' . esc_html__( 'التغييرات قبل التطبيق', 'seohouse-core' ) . '</h3>';
		if ( ! $preview ) {
			echo '<p>' . esc_html__( 'لا توجد فروق.', 'seohouse-core' ) . '</p>';
		} else {
			echo '<ul>';
			foreach ( $preview as $k => $v ) {
				echo '<li><code>' . esc_html( $k ) . '</code></li>';
			}
			echo '</ul><button class="button button-primary" name="sh_tools_action" value="apply">' . esc_html__( 'تطبيق التغييرات', 'seohouse-core' ) . '</button> ';
		}
	}
	echo '<button class="button" name="sh_tools_action" value="preview">' . esc_html__( 'معاينة التغييرات', 'seohouse-core' ) . '</button></form>';

	echo '<h2>' . esc_html__( 'استعادة الافتراضي لمجموعة', 'seohouse-core' ) . '</h2><form method="post" onsubmit="return confirm(\'' . esc_js( __( 'ستُستبدل قيم هذه المجموعة. متابعة؟', 'seohouse-core' ) ) . '\')">';
	wp_nonce_field( 'sh_settings_tools' );
	echo '<select name="sh_tab">';
	foreach ( array_keys( sh_settings_tabs() ) as $t ) {
		echo '<option>' . esc_html( $t ) . '</option>';
	}
	echo '</select> <button class="button" name="sh_tools_action" value="reset">' . esc_html__( 'استعادة', 'seohouse-core' ) . '</button></form>' . sh_core_tools_switch_html() . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
}
