<?php
/**
 * Reviews sections: the content comes only from the reviews plugin's shortcode (page override
 * «التقييمات في هذه الصفحة» → the shared one in «سيو هاوس ← تقييمات جوجل»). Without a
 * shortcode the theme leaves the section out. The design's example cards and placeholder box are
 * never shown, so their fields are hidden from the page editor (values are kept, not deleted).
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

/** Keys of the reviews section groups in the page field groups. */
function sh_core_reviews_groups(): array {
	static $keys = null;
	if ( null !== $keys ) {
		return $keys;
	}
	$keys = array();
	foreach ( (array) glob( SH_CORE_DIR . 'acf-json/group_sh_page_*.json' ) as $file ) {
		$g = json_decode( (string) file_get_contents( $file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		foreach ( (array) ( $g['fields'] ?? array() ) as $f ) {
			if ( 'group' === ( $f['type'] ?? '' ) && preg_match( '/^s_reviews(_\d+)?$/', (string) ( $f['name'] ?? '' ) ) ) {
				$keys[] = $f['key'];
			}
		}
	}
	return $keys;
}

add_filter(
	'acf/prepare_field',
	static function ( $field ) {
		if ( ! is_array( $field ) || ! is_admin() ) {
			return $field;
		}
		$groups = sh_core_reviews_groups();
		if ( in_array( $field['key'] ?? '', $groups, true ) ) {
			$field['instructions'] = __( 'محتوى القسم من شورت كود التقييمات («التقييمات في هذه الصفحة» في الشريط الجانبي، أو الشورت كود العام من «سيو هاوس ← تقييمات جوجل»). بلا شورت كود لا يظهر القسم.', 'seohouse-core' );
			return $field;
		}
		// all_link / all_label: the «شاهد جميع المراجعات على Google» button. 2.7.3: compare ACF's own field
		// name (_name); inside a group 'name' is already the input name «acf[group][key]», so before
		// this fix every field of the section was hidden, the kept ones included.
		$name = (string) ( $field['_name'] ?? ( $field['name'] ?? '' ) );
		if ( in_array( $field['parent'] ?? '', $groups, true ) && ! in_array( $name, array( 'sh_hide', 'sh_anchor', 'eyebrow', 'title', 'text', 'all_link', 'all_label' ), true ) ) {
			// example testimonials / placeholder texts of the design: never displayed
			return false;
		}
		return $field;
	},
	20
);

/* ------------------------------------------------------------------ one rule for the code */

/** The code is exactly one shortcode of a plugin that is active ([tag] or [tag attr=…]). */
function sh_core_reviews_valid( string $code ): bool {
	return (bool) preg_match( '/^\[([A-Za-z0-9_-]+)(?:\s[^\[\]]*)?\]$/', trim( $code ), $m ) && shortcode_exists( $m[1] );
}

function sh_core_reviews_global(): string {
	return trim( (string) sh_core_option( 'sh_reviews_shortcode', '' ) );
}

/**
 * What a page shows: its own code if valid, otherwise the shared one if valid, otherwise nothing.
 *
 * @return array{code:string,source:string} source: page | global | none
 */
function sh_core_reviews_for( int $post_id ): array {
	$own = $post_id ? trim( (string) sh_core_field( 'sh_reviews_shortcode_page', $post_id, '' ) ) : '';
	if ( '' !== $own && sh_core_reviews_valid( $own ) ) {
		return array( 'code' => $own, 'source' => 'page' );
	}
	$all = sh_core_reviews_global();
	if ( '' !== $all && sh_core_reviews_valid( $all ) ) {
		return array( 'code' => $all, 'source' => 'global' );
	}
	return array( 'code' => '', 'source' => 'none' );
}

/** Pages built from a template that has a reviews section. */
function sh_core_reviews_pages(): array {
	$g    = json_decode( (string) file_get_contents( SH_CORE_DIR . 'acf-json/group_sh_page_reviews.json' ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	$tpls = array();
	foreach ( (array) ( $g['location'] ?? array() ) as $rule ) {
		$tpls[] = $rule[0]['value'] ?? '';
	}
	$tpls = array_filter( $tpls );
	if ( ! $tpls ) {
		return array();
	}
	return get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => array( 'publish', 'draft', 'private' ),
			'posts_per_page' => -1,
			'orderby'        => 'menu_order title',
			'order'          => 'ASC',
			'meta_query'     => array( array( 'key' => '_wp_page_template', 'value' => $tpls, 'compare' => 'IN' ) ), // phpcs:ignore WordPress.DB.SlowDBQuery
		)
	);
}

/* ------------------------------------------------------------------ screen «تقييمات جوجل» */

add_action(
	'admin_menu',
	static function () {
		add_submenu_page( 'seohouse-settings', __( 'تقييمات جوجل', 'seohouse-core' ), __( 'تقييمات جوجل', 'seohouse-core' ), 'manage_options', 'seohouse-reviews', 'sh_core_reviews_page' );
	},
	25
);

function sh_core_reviews_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$saved = false;
	if ( isset( $_POST['sh_reviews_save'] ) ) {
		check_admin_referer( 'sh_reviews_save' );
		update_field( 'field_sh_opt_reviews_shortcode', sanitize_text_field( wp_unslash( $_POST['sh_reviews_global'] ?? '' ) ), 'option' );
		update_field( 'field_sh_opt_reviews_all_link', esc_url_raw( trim( wp_unslash( $_POST['sh_reviews_all_link'] ?? '' ) ) ), 'option' );
		update_field( 'field_sh_opt_reviews_all_label', sanitize_text_field( wp_unslash( $_POST['sh_reviews_all_label'] ?? '' ) ), 'option' );
		foreach ( (array) ( $_POST['sh_reviews_page'] ?? array() ) as $pid => $code ) {
			$pid = absint( $pid );
			if ( $pid && current_user_can( 'edit_post', $pid ) ) {
				update_field( 'field_sh_rv_shortcode', sanitize_text_field( wp_unslash( $code ) ), $pid );
			}
		}
		$saved = true;
	}
	$global = sh_core_reviews_global();
	$tag    = preg_match( '/^\[([A-Za-z0-9_-]+)/', $global, $m ) ? $m[1] : '';
	echo '<div class="wrap"><h1>' . esc_html__( 'تقييمات جوجل', 'seohouse-core' ) . '</h1>';
	if ( $saved ) {
		echo '<div class="notice notice-success"><p>' . esc_html__( 'حُفظت الإعدادات.', 'seohouse-core' ) . '</p></div>';
	}
	echo '<p style="max-width:62em;font-size:14px">' . esc_html__( 'التقييمات تأتي من إضافة التقييمات (مثل Trustindex) فقط. ثبّت الإضافة واربطها بحساب جوجل، ثم الصق الشورت كود الذي تعطيك إياه هنا. يظهر في مكان قسم التقييمات في الصفحات أدناه بنفس تصميم القسم، مرة واحدة في كل صفحة. بلا شورت كود صالح لا يظهر القسم ولا تُعرض شهادات تجريبية.', 'seohouse-core' ) . '</p>';

	// status
	if ( '' === $global ) {
		$state = array( 'warning', __( 'لا يوجد شورت كود عام: قسم التقييمات مخفي في كل الصفحات التي لا تملك شورت كود خاصًا.', 'seohouse-core' ) );
	} elseif ( sh_core_reviews_valid( $global ) ) {
		$state = array( 'success', sprintf( __( 'الشورت كود [%s] مسجّل من إضافة مفعّلة، ويظهر في الصفحات أدناه.', 'seohouse-core' ), $tag ) );
	} elseif ( $tag && ! shortcode_exists( $tag ) ) {
		$state = array( 'error', sprintf( __( 'الشورت كود [%s] غير مسجّل: الإضافة غير مثبتة أو غير مفعّلة. القسم مخفي حتى تُفعَّل.', 'seohouse-core' ), $tag ) );
	} else {
		$state = array( 'error', __( 'النص المدخل ليس شورت كود واحدًا صالحًا. القسم مخفي.', 'seohouse-core' ) );
	}
	echo '<div class="notice notice-' . esc_attr( $state[0] ) . ' inline" style="max-width:60em"><p><strong>' . esc_html__( 'الحالة:', 'seohouse-core' ) . '</strong> ' . esc_html( $state[1] ) . '</p></div>';

	echo '<form method="post">';
	wp_nonce_field( 'sh_reviews_save' );
	echo '<table class="form-table" role="presentation"><tr><th scope="row"><label for="sh-reviews-global">' . esc_html__( 'الشورت كود العام', 'seohouse-core' ) . '</label></th><td><input type="text" class="large-text code" dir="ltr" id="sh-reviews-global" name="sh_reviews_global" value="' . esc_attr( $global ) . '" placeholder="[trustindex no-registration=google]"><p class="description">' . esc_html__( 'شورت كود واحد فقط، كما تنسخه من الإضافة.', 'seohouse-core' ) . '</p></td></tr>'
		. '<tr><th scope="row"><label for="sh-reviews-all-link">' . esc_html__( 'رابط جميع المراجعات على Google', 'seohouse-core' ) . '</label></th><td><input type="url" class="large-text code" dir="ltr" id="sh-reviews-all-link" name="sh_reviews_all_link" value="' . esc_attr( (string) sh_core_option( 'sh_reviews_all_link', '' ) ) . '" placeholder="https://"><p class="description">' . esc_html__( 'رابط صفحة مراجعات نشاطك على Google Maps (افتح نشاطك في خرائط Google ← المراجعات ← انسخ الرابط). يظهر زر أسفل التقييمات في كل الصفحات ويفتح في تبويب جديد، ويمكن تغييره لصفحة من «قسم التقييمات» في محررها. فارغ = لا يظهر الزر.', 'seohouse-core' ) . '</p></td></tr>'
		. '<tr><th scope="row"><label for="sh-reviews-all-label">' . esc_html__( 'نص الزر', 'seohouse-core' ) . '</label></th><td><input type="text" class="regular-text" id="sh-reviews-all-label" name="sh_reviews_all_label" value="' . esc_attr( (string) sh_core_option( 'sh_reviews_all_label', '' ) ) . '" placeholder="' . esc_attr__( 'شاهد جميع المراجعات على Google', 'seohouse-core' ) . '"><p class="description">' . esc_html__( 'فارغ = «شاهد جميع المراجعات على Google».', 'seohouse-core' ) . '</p></td></tr></table>';

	echo '<h2>' . esc_html__( 'الصفحات التي فيها قسم التقييمات', 'seohouse-core' ) . '</h2><p>' . esc_html__( 'اترك «شورت كود خاص» فارغًا لتستخدم الصفحة الشورت كود العام. عند إدخاله يحل محل العام في تلك الصفحة فقط، ولا تُعرض التقييمات مرتين.', 'seohouse-core' ) . '</p>';
	echo '<table class="widefat striped" style="max-width:72em"><thead><tr><th>' . esc_html__( 'الصفحة', 'seohouse-core' ) . '</th><th>' . esc_html__( 'شورت كود خاص (اختياري)', 'seohouse-core' ) . '</th><th>' . esc_html__( 'ما يظهر الآن', 'seohouse-core' ) . '</th></tr></thead><tbody>';
	foreach ( sh_core_reviews_pages() as $p ) {
		$own    = (string) sh_core_field( 'sh_reviews_shortcode_page', $p->ID, '' );
		$use    = sh_core_reviews_for( (int) $p->ID );
		$hidden = false;
		foreach ( sh_core_sections( (int) $p->ID ) as $row ) {
			if ( str_starts_with( (string) ( $row['acf_fc_layout'] ?? '' ), 'reviews' ) && ! empty( $row['sh_hide'] ) ) {
				$hidden = true;
			}
		}
		if ( $hidden ) {
			$now = __( 'مخفي (القسم مخفي من محرر الصفحة)', 'seohouse-core' );
		} elseif ( 'page' === $use['source'] ) {
			$now = __( 'يظهر — الشورت كود الخاص', 'seohouse-core' );
		} elseif ( 'global' === $use['source'] ) {
			$now = __( 'يظهر — الشورت كود العام', 'seohouse-core' );
		} else {
			$now = __( 'مخفي (لا يوجد شورت كود صالح)', 'seohouse-core' );
		}
		if ( '' !== trim( $own ) && 'page' !== $use['source'] ) {
			$now .= ' — ' . __( 'الشورت كود الخاص غير صالح', 'seohouse-core' );
		}
		echo '<tr><td><a href="' . esc_url( (string) get_permalink( $p ) ) . '" target="_blank">' . esc_html( get_the_title( $p ) ) . '</a><br><small dir="ltr">' . esc_html( wp_make_link_relative( (string) get_permalink( $p ) ) ) . '</small></td><td><input type="text" class="regular-text code" dir="ltr" name="sh_reviews_page[' . (int) $p->ID . ']" value="' . esc_attr( $own ) . '"></td><td>' . esc_html( $now ) . '</td></tr>';
	}
	echo '</tbody></table>';
	submit_button( __( 'حفظ', 'seohouse-core' ), 'primary', 'sh_reviews_save' );
	echo '</form></div>';
}
