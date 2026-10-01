<?php
/**
 * ACF integration — runs on ACF (free) 6.x; ACF PRO and Secure Custom Fields work the same way.
 *
 * - Field definitions load from acf-json/ (Local JSON, one source).
 * - No PRO-only field types are used:
 *     · page sections are ACF Group fields in the design order (Flexible Content is PRO),
 *     · repeating items use the Core field type "sh_rows" (records of type sh_row; Repeater is PRO),
 *     · «إعدادات سيو هاوس» is a WordPress admin page that renders and saves the ACF field group
 *       with ACF's own form functions (Options Pages are PRO).
 * - Without ACF a clear notice is shown and dependent features stop safely.
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

/** Field type "sh_rows" (قائمة عناصر). */
add_action(
	'acf/include_field_types',
	static function () {
		require_once SH_CORE_DIR . 'inc/fields/class-sh-field-rows.php';
		acf_register_field_type( 'SH_Field_Rows' );
	}
);

/** Location rule «شاشة سيو هاوس» so the settings group is attached to the Core settings screen only. */
add_action(
	'acf/init',
	static function () {
		if ( ! class_exists( 'ACF_Location' ) || ! function_exists( 'acf_register_location_type' ) ) {
			return;
		}
		require_once SH_CORE_DIR . 'inc/fields/class-sh-location-screen.php';
		acf_register_location_type( 'SH_Location_Screen' );
	}
);

add_action(
	'admin_notices',
	static function () {
		if ( ! current_user_can( 'activate_plugins' ) || sh_core_acf_ready() ) {
			return;
		}
		echo '<div class="notice notice-error"><p><strong>' . esc_html__( 'SEO House Core:', 'seohouse-core' ) . '</strong> ';
		echo esc_html__( 'فعّل إضافة Advanced Custom Fields (الإصدار 6 المجاني يكفي). الحقول والإعدادات وأداة تجهيز المحتوى معطلة حتى التفعيل؛ لم يُحذف أي محتوى.', 'seohouse-core' );
		echo '</p></div>';
	}
);

/* ====================================================================== settings screen */

const SH_SETTINGS_SLUG = 'seohouse-settings';

/** Field groups attached to the settings screen. */
function sh_core_settings_groups(): array {
	return function_exists( 'acf_get_field_groups' ) ? acf_get_field_groups( array( 'sh_screen' => SH_SETTINGS_SLUG ) ) : array();
}

add_action(
	'admin_menu',
	static function () {
		$hook = add_menu_page(
			__( 'إعدادات سيو هاوس', 'seohouse-core' ),
			__( 'إعدادات سيو هاوس', 'seohouse-core' ),
			'manage_options',
			SH_SETTINGS_SLUG,
			'sh_core_settings_page',
			'dashicons-admin-generic',
			3
		);
		add_action( 'load-' . $hook, 'sh_core_settings_load' );
	},
	9
);

/** Save (ACF validation + acf_save_post on the "options" store) and enqueue the ACF editor. */
function sh_core_settings_load(): void {
	if ( ! sh_core_acf_ready() ) {
		return;
	}
	if ( acf_verify_nonce( 'options' ) && current_user_can( 'manage_options' ) ) {
		if ( acf_validate_save_post( true ) ) {
			acf_save_post( 'options' );
			wp_safe_redirect( add_query_arg( array( 'page' => SH_SETTINGS_SLUG, 'updated' => 'true' ), admin_url( 'admin.php' ) ) );
			exit;
		}
	}
	acf_enqueue_scripts();
	wp_enqueue_script( 'post' );
}

function sh_core_settings_page(): void {
	echo '<div class="wrap acf-settings-wrap"><h1>' . esc_html__( 'إعدادات سيو هاوس', 'seohouse-core' ) . '</h1>';
	if ( ! sh_core_acf_ready() ) {
		echo '<p>' . esc_html__( 'فعّل Advanced Custom Fields أولًا.', 'seohouse-core' ) . '</p></div>';
		return;
	}
	if ( isset( $_GET['updated'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- display only.
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'تم حفظ الإعدادات.', 'seohouse-core' ) . '</p></div>';
	}
	echo '<form id="post" method="post" name="post">';
	acf_form_data( array( 'screen' => 'options', 'post_id' => 'options' ) );
	echo '<div id="poststuff"><div id="post-body" class="metabox-holder columns-1"><div id="post-body-content">';
	foreach ( sh_core_settings_groups() as $group ) {
		$fields = acf_get_fields( $group );
		echo '<div id="acf-' . esc_attr( $group['key'] ) . '" class="postbox acf-postbox">';
		echo '<div class="postbox-header"><h2 class="hndle">' . esc_html( $group['title'] ) . '</h2></div>';
		echo '<div class="inside acf-fields -top -sidebar">';
		acf_render_fields( $fields, 'options', 'div', $group['instruction_placement'] ?? 'label' );
		echo '</div></div>';
		echo '<script>if(typeof acf!=="undefined"){acf.newPostbox(' . wp_json_encode( array( 'id' => 'acf-' . $group['key'], 'key' => $group['key'], 'style' => 'default', 'label' => 'top', 'edit' => false ) ) . ');}</script>';
	}
	echo '</div></div></div>';
	submit_button( __( 'حفظ الإعدادات', 'seohouse-core' ), 'primary large', 'sh_settings_save' );
	echo '</form></div>';
}

/**
 * Pages built from a design template keep all their content in ACF sections (the page body is
 * not used), so they open in the classic edit screen with the sections only.
 */
add_filter(
	'use_block_editor_for_post',
	static function ( $use, $post ) {
		return ( $post && 'page' === $post->post_type && sh_core_page_key( $post ) ) ? false : $use;
	},
	10,
	2
);

/**
 * Link fields that point to content which is not published yet (a page awaiting approved text,
 * a draft article) output nothing to visitors, so no link leads to a 404. The link reappears by
 * itself once the target is published. Editors and the admin/CLI still see the stored target.
 */
add_filter(
	'acf/format_value/type=page_link',
	static function ( $value ) {
		if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) || current_user_can( 'edit_posts' ) ) {
			return $value;
		}
		$visible = static fn( $v ) => ! is_numeric( $v ) || 'publish' === get_post_status( (int) $v );
		if ( is_array( $value ) ) {
			return array_values( array_filter( $value, $visible ) );
		}
		return $visible( $value ) ? $value : '';
	},
	5
);
