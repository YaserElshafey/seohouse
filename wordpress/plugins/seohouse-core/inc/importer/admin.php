<?php
/**
 * Admin screen "تجهيز المحتوى": upload the content pack (zip), dry run, run, verify.
 * Administrators only; every action is nonce-protected.
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'admin_menu',
	static function () {
		add_submenu_page( 'seohouse-settings', __( 'تجهيز المحتوى', 'seohouse-core' ), __( 'تجهيز المحتوى', 'seohouse-core' ), 'manage_options', 'seohouse-content-setup', 'sh_import_admin_page' );
	},
	30
);

function sh_import_admin_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$dir    = SH_Importer::default_dir();
	$result = null;
	$error  = '';

	if ( isset( $_POST['sh_import_do'] ) && check_admin_referer( 'sh_import' ) ) {
		$do = sanitize_key( wp_unslash( $_POST['sh_import_do'] ) );
		if ( 'upload' === $do && ! empty( $_FILES['sh_pack']['tmp_name'] ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
			$name = sanitize_file_name( wp_unslash( $_FILES['sh_pack']['name'] ?? '' ) );
			if ( ! str_ends_with( strtolower( $name ), '.zip' ) ) {
				$error = __( 'ارفع ملف ZIP لحزمة المحتوى.', 'seohouse-core' );
			} else {
				$tmp = $dir . '-tmp';
				$res = unzip_file( $_FILES['sh_pack']['tmp_name'], $tmp ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
				if ( is_wp_error( $res ) ) {
					$error = $res->get_error_message();
				} else {
					// accept a zip with or without a top-level folder
					$root = file_exists( $tmp . '/manifest.json' ) ? $tmp : ( glob( $tmp . '/*/manifest.json' )[0] ?? '' );
					if ( ! $root ) {
						$error = __( 'الملف لا يحتوي manifest.json.', 'seohouse-core' );
					} else {
						global $wp_filesystem;
						$wp_filesystem->delete( $dir, true );
						$wp_filesystem->move( dirname( $root . '/manifest.json' ), $dir, true );
					}
					$wp_filesystem->delete( $tmp, true );
				}
			}
		} elseif ( in_array( $do, array( 'dry', 'run', 'update' ), true ) ) {
			if ( function_exists( 'set_time_limit' ) ) {
				set_time_limit( 600 ); // phpcs:ignore
			}
			$targets = array_map( 'sanitize_text_field', (array) wp_unslash( $_POST['sh_update'] ?? array() ) );
			$imp     = new SH_Importer(
				$dir,
				array(
					'dry_run' => 'dry' === $do || ! empty( $_POST['sh_preview'] ),
					'update'  => 'update' === $do ? $targets : array(),
				)
			);
			$imp->run();
			$result = $imp;
		} elseif ( 'verify' === $do ) {
			$result = ( new SH_Importer( $dir ) )->verify();
		}
	}

	$has_pack = file_exists( $dir . '/manifest.json' );
	$last     = get_option( 'sh_content_last_import' );
	echo '<div class="wrap"><h1>' . esc_html__( 'تجهيز المحتوى من التصميم المعتمد', 'seohouse-core' ) . '</h1>';
	echo '<p>' . esc_html__( 'تنشئ الأداة الصفحات والحالات والفريق والمقالات والوسائط والقوائم والإعدادات من حزمة المحتوى. لا تعمل تلقائيًا، ولا تكرر ما أنشأته، ولا تكتب فوق تعديلاتك.', 'seohouse-core' ) . '</p>';
	if ( $error ) {
		echo '<div class="notice notice-error"><p>' . esc_html( $error ) . '</p></div>';
	}
	if ( ! sh_core_acf_ready() ) {
		echo '<div class="notice notice-error"><p>' . esc_html__( 'فعّل ACF PRO أو Secure Custom Fields أولًا.', 'seohouse-core' ) . '</p></div></div>';
		return;
	}
	echo '<h2>1. ' . esc_html__( 'حزمة المحتوى', 'seohouse-core' ) . '</h2>';
	echo $has_pack ? '<p>✔ ' . esc_html( $dir ) . '</p>' : '<p>' . esc_html__( 'لم تُرفع الحزمة بعد.', 'seohouse-core' ) . '</p>';
	echo '<form method="post" enctype="multipart/form-data">';
	wp_nonce_field( 'sh_import' );
	echo '<input type="file" name="sh_pack" accept=".zip"> <button class="button" name="sh_import_do" value="upload">' . esc_html__( 'رفع seohouse-content.zip', 'seohouse-core' ) . '</button></form>';

	if ( $has_pack ) {
		echo '<h2>2. ' . esc_html__( 'التجهيز', 'seohouse-core' ) . '</h2>';
		if ( $last ) {
			/* translators: %s: date */
			echo '<p>' . esc_html( sprintf( __( 'آخر تجهيز: %s', 'seohouse-core' ), wp_date( 'Y-m-d H:i', $last['time'] ) ) ) . '</p>';
		}
		echo '<form method="post" style="display:flex;gap:8px;flex-wrap:wrap">';
		wp_nonce_field( 'sh_import' );
		echo '<button class="button" name="sh_import_do" value="dry">' . esc_html__( 'تشغيل تجريبي (بدون كتابة)', 'seohouse-core' ) . '</button>';
		echo '<button class="button button-primary" name="sh_import_do" value="run">' . esc_html__( 'تجهيز / استكمال الناقص', 'seohouse-core' ) . '</button>';
		echo '<button class="button" name="sh_import_do" value="verify">' . esc_html__( 'مطابقة القيم مع التصميم', 'seohouse-core' ) . '</button></form>';

		echo '<h3>' . esc_html__( 'تحديث مقصود لسجلات محددة', 'seohouse-core' ) . '</h3><form method="post">';
		wp_nonce_field( 'sh_import' );
		foreach ( array( 'pages' => 'الصفحات', 'team' => 'الفريق', 'cases' => 'دراسات الحالة', 'posts' => 'المقالات', 'menus' => 'القوائم', 'options' => 'الإعدادات' ) as $k => $l ) {
			echo '<label style="margin-inline-end:14px"><input type="checkbox" name="sh_update[]" value="' . esc_attr( $k ) . '"> ' . esc_html( $l ) . '</label>';
		}
		echo '<p><label><input type="checkbox" name="sh_preview" value="1" checked> ' . esc_html__( 'معاينة الفروق فقط', 'seohouse-core' ) . '</label></p>';
		echo '<button class="button" name="sh_import_do" value="update">' . esc_html__( 'تحديث ما لم يُعدَّل', 'seohouse-core' ) . '</button></form>';
	}

	if ( $result instanceof SH_Importer ) {
		echo '<h2>' . esc_html__( 'السجل', 'seohouse-core' ) . '</h2><p>';
		foreach ( $result->counts as $k => $v ) {
			echo '<strong>' . esc_html( $k ) . '</strong>: ' . (int) $v . ' &nbsp; ';
		}
		echo '</p><table class="widefat striped"><tbody>';
		foreach ( $result->log as $l ) {
			if ( 'skipped' === $l[0] ) {
				continue;
			}
			echo '<tr><td>' . esc_html( $l[0] ) . '</td><td dir="ltr">' . esc_html( $l[1] ) . '</td><td>' . esc_html( $l[2] ) . '</td></tr>';
		}
		echo '</tbody></table>';
	} elseif ( is_array( $result ) ) {
		echo '<h2>' . esc_html__( 'المطابقة', 'seohouse-core' ) . '</h2><table class="widefat striped"><thead><tr><th>الصفحة</th><th>القيم</th><th>الفروق</th></tr></thead><tbody>';
		foreach ( $result as $r ) {
			echo '<tr><td>' . esc_html( $r[0] ) . '</td><td>' . (int) $r[1] . '</td><td>' . (int) $r[2] . '</td></tr>';
		}
		echo '</tbody></table>';
	}
	echo '</div>';
}
