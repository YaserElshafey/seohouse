<?php
/**
 * Rank Math as the one place for search title, description, social image and indexing.
 *
 * - Core's duplicate fields (عنوان SEO، وصف SEO، صورة المشاركة، منع الفهرسة) are hidden while
 *   Rank Math is active; the breadcrumb names and the Schema fields stay (they drive the page).
 * - Existing Core values are moved once into Rank Math's own fields, only where those are empty
 *   (screen «سيو هاوس ← نقل السيو إلى Rank Math», `wp seohouse rankmath-migrate`, and at the end
 *   of the site initialisation). Rank Math values an editor wrote are never changed.
 * - Until that move has run, a Core value is used only for a page whose Rank Math field is empty.
 * - Structured data: SEO House Core is the only source (inc/schema.php); Rank Math's JSON-LD is kept
 *   off (sh_rankmath_no_json_ld()).
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

/** Rank Math is installed and active (it may still be waiting for its registration step). */
function sh_rankmath_installed(): bool {
	return class_exists( 'RankMath' );
}

/**
 * Rank Math is actually running. While its registration step («Connect» or «Skip») has not been
 * completed it loads neither its editor box nor its front-end tags, so Core must not hand the
 * output over to it.
 */
function sh_rankmath_active(): bool {
	if ( ! class_exists( 'RankMath' ) ) {
		return false;
	}
	if ( class_exists( '\RankMath\Helper' ) && method_exists( '\RankMath\Helper', 'is_invalid_registration' ) ) {
		return ! \RankMath\Helper::is_invalid_registration();
	}
	return true;
}

/** Public post types that get the Rank Math box («SEO Controls»). */
function sh_rankmath_post_types(): array {
	return array( 'page', 'post', 'case_study', 'team_member' );
}

/** Types whose Rank Math box is switched off in Rank Math ← Titles & Meta. */
function sh_rankmath_controls_off(): array {
	$titles = get_option( 'rank-math-options-titles', array() );
	$off    = array();
	foreach ( sh_rankmath_post_types() as $t ) {
		if ( post_type_exists( $t ) && 'on' !== ( $titles[ "pt_{$t}_add_meta_box" ] ?? 'on' ) ) {
			$off[] = $t;
		}
	}
	return $off;
}

/** Switches the Rank Math box on for the site's public types; returns the types changed. */
function sh_rankmath_enable_controls(): array {
	$titles  = get_option( 'rank-math-options-titles', array() );
	$changed = array();
	foreach ( sh_rankmath_post_types() as $t ) {
		if ( post_type_exists( $t ) && 'on' !== ( $titles[ "pt_{$t}_add_meta_box" ] ?? '' ) ) {
			$titles[ "pt_{$t}_add_meta_box" ] = 'on';
			$changed[]                         = $t;
		}
	}
	if ( $changed ) {
		update_option( 'rank-math-options-titles', $titles );
	}
	return $changed;
}

// Once, when Rank Math first runs here: SEO Controls on for pages, articles, case studies and team.
add_action(
	'admin_init',
	static function () {
		if ( sh_rankmath_active() && ! get_option( 'sh_rankmath_controls_done' ) && current_user_can( 'manage_options' ) ) {
			sh_rankmath_enable_controls();
			update_option( 'sh_rankmath_controls_done', time(), false );
		}
	}
);

// Rank Math waiting for its registration step: say so where the editor looks for its box.
add_action(
	'admin_notices',
	static function () {
		if ( ! current_user_can( 'manage_options' ) || ! sh_rankmath_installed() || sh_rankmath_active() ) {
			return;
		}
		echo '<div class="notice notice-error"><p><strong>' . esc_html__( 'Rank Math مفعّلة لكنها متوقفة: لم تكتمل خطوة الحساب في معالج الإعداد.', 'seohouse-core' ) . '</strong> ' . esc_html__( 'حتى تكتمل لا يظهر صندوق Rank Math في المحرر ولا تُخرج وسوم البحث، ويتولى SEO House Core العنوان والوصف والبيانات المنظمة مؤقتًا.', 'seohouse-core' ) . '</p><p><a class="button button-primary" href="' . esc_url( admin_url( 'admin.php?page=rank-math-registration' ) ) . '">' . esc_html__( 'ربط حساب Rank Math', 'seohouse-core' ) . '</a> ';
		echo '<a class="button" href="' . esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=sh_rankmath_skip' ), 'sh_rankmath_skip' ) ) . '">' . esc_html__( 'تشغيل Rank Math بدون حساب (مثل «Skip» في المعالج)', 'seohouse-core' ) . '</a></p></div>';
	}
);

add_action(
	'admin_post_sh_rankmath_skip',
	static function () {
		check_admin_referer( 'sh_rankmath_skip' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( '', 403 );
		}
		update_option( 'rank_math_registration_skip', true ); // Rank Math's own "Skip" setting
		wp_safe_redirect( admin_url( 'admin.php?page=seohouse-rankmath&skipped=1' ) );
		exit;
	}
);

/** Core field name => what it becomes in Rank Math. */
function sh_rankmath_fields(): array {
	return array(
		'sh_seo_title'       => 'عنوان البحث',
		'sh_seo_description' => 'وصف البحث',
		'sh_seo_og_image'    => 'صورة المشاركة',
		'sh_seo_noindex'     => 'منع الفهرسة',
	);
}

/* ------------------------------------------------------------------ editor: one place to edit */

add_filter(
	'acf/prepare_field',
	static function ( $field ) {
		if ( ! is_array( $field ) ) {
			return $field;
		}
		$name = (string) ( $field['name'] ?? '' );
		// names arrive prefixed (acf[...]) on some screens: compare by key as well
		$key = (string) ( $field['key'] ?? '' );
		if ( 'field_sh_seo_rankmath_msg' === $key ) {
			return sh_rankmath_active() ? $field : false;
		}
		if ( sh_rankmath_active() && in_array( $key, array( 'field_sh_seo_title', 'field_sh_seo_description', 'field_sh_seo_og_image', 'field_sh_seo_noindex' ), true ) ) {
			return false; // edited in the Rank Math box
		}
		return $field;
	},
	20
);

/* ------------------------------------------------------------------ migration */

function sh_rankmath_migrated(): bool {
	return (bool) get_option( 'sh_rankmath_migrated' );
}

/** Rank Math's stored value for one of the four fields ('' / array() when empty). */
function sh_rankmath_current( int $id, string $field ) {
	switch ( $field ) {
		case 'sh_seo_title':
			return (string) get_post_meta( $id, 'rank_math_title', true );
		case 'sh_seo_description':
			return (string) get_post_meta( $id, 'rank_math_description', true );
		case 'sh_seo_og_image':
			return (string) get_post_meta( $id, 'rank_math_facebook_image', true );
		case 'sh_seo_noindex':
			$r = get_post_meta( $id, 'rank_math_robots', true );
			return is_array( $r ) ? array_values( $r ) : array();
	}
	return '';
}

/** Value Core would hand over for a post ('' when there is nothing to move). */
function sh_rankmath_core_value( WP_Post $p, string $field ) {
	$id = (int) $p->ID;
	switch ( $field ) {
		case 'sh_seo_title':
			return sh_core_plain( sh_core_field( 'sh_seo_title', $id, '' ) );
		case 'sh_seo_description':
			$d = sh_core_plain( sh_core_field( 'sh_seo_description', $id, '' ) );
			if ( '' === $d && in_array( $p->post_type, array( 'case_study', 'team_member' ), true ) ) {
				// these records have no body text for Rank Math's default (%excerpt%) to use
				$d = sh_seo_description_for( $p );
			}
			return $d;
		case 'sh_seo_og_image':
			$img = (int) sh_core_field( 'sh_seo_og_image', $id, 0 );
			return $img ? (string) wp_get_attachment_image_url( $img, 'full' ) : '';
		case 'sh_seo_noindex':
			return sh_core_field( 'sh_seo_noindex', $id, false ) ? array( 'noindex' ) : array();
	}
	return '';
}

/**
 * Rows of the move: one per post and field that has a Core value.
 *
 * @return array<int,array{id:int,title:string,type:string,field:string,label:string,core:mixed,rank_math:mixed,action:string}>
 *         action: 'fill' (Rank Math empty → will be filled) | 'same' | 'keep' (Rank Math has its own value)
 */
function sh_rankmath_plan(): array {
	$rows  = array();
	$posts = get_posts(
		array(
			'post_type'      => array( 'page', 'post', 'case_study', 'team_member' ),
			'post_status'    => array( 'publish', 'draft', 'pending', 'private', 'future' ),
			'posts_per_page' => -1,
			'orderby'        => array( 'post_type' => 'ASC', 'menu_order' => 'ASC', 'title' => 'ASC' ),
		)
	);
	foreach ( $posts as $p ) {
		foreach ( sh_rankmath_fields() as $field => $label ) {
			$core = sh_rankmath_core_value( $p, $field );
			if ( '' === $core || array() === $core ) {
				continue;
			}
			$rm = sh_rankmath_current( (int) $p->ID, $field );
			if ( 'sh_seo_noindex' === $field ) {
				$action = in_array( 'noindex', $rm, true ) ? 'same' : ( $rm ? 'keep' : 'fill' );
			} else {
				$action = '' === $rm ? 'fill' : ( $rm === $core ? 'same' : 'keep' );
			}
			$rows[] = array(
				'id'        => (int) $p->ID,
				'title'     => get_the_title( $p ),
				'type'      => $p->post_type,
				'field'     => $field,
				'label'     => $label,
				'core'      => $core,
				'rank_math' => $rm,
				'action'    => $action,
			);
		}
	}
	return $rows;
}

/** Writes the 'fill' rows into Rank Math's fields. Returns the number of values written. */
function sh_rankmath_apply( ?array $plan = null ): int {
	$n = 0;
	foreach ( null === $plan ? sh_rankmath_plan() : $plan as $r ) {
		if ( 'fill' !== $r['action'] ) {
			continue;
		}
		$id = (int) $r['id'];
		// re-check just before writing: never replace a value that appeared meanwhile
		$now = sh_rankmath_current( $id, $r['field'] );
		if ( '' !== $now && array() !== $now ) {
			continue;
		}
		switch ( $r['field'] ) {
			case 'sh_seo_title':
				update_post_meta( $id, 'rank_math_title', wp_slash( $r['core'] ) );
				break;
			case 'sh_seo_description':
				update_post_meta( $id, 'rank_math_description', wp_slash( $r['core'] ) );
				break;
			case 'sh_seo_og_image':
				$img = (int) sh_core_field( 'sh_seo_og_image', $id, 0 );
				update_post_meta( $id, 'rank_math_facebook_image', esc_url_raw( $r['core'] ) );
				update_post_meta( $id, 'rank_math_facebook_image_id', $img );
				break;
			case 'sh_seo_noindex':
				update_post_meta( $id, 'rank_math_robots', array( 'noindex' ) );
				break;
		}
		++$n;
	}
	update_option( 'sh_rankmath_migrated', array( 'time' => time(), 'written' => $n ), false );
	return $n;
}

/** Core's design title and description of a post, as handed to Rank Math ('' when none). */
function sh_rankmath_design_values( int $id ): array {
	$p = get_post( $id );
	if ( ! $p || ! sh_rankmath_active() ) {
		return array();
	}
	return array(
		'rank_math_title'       => (string) sh_rankmath_core_value( $p, 'sh_seo_title' ),
		'rank_math_description' => (string) sh_rankmath_core_value( $p, 'sh_seo_description' ),
	);
}

/**
 * After a content update changed a design value: a Rank Math field that still holds the previous
 * design value (copied there automatically, never edited) follows to the new one. A value typed
 * in Rank Math, or written by «نقل عناوين وأوصاف SEO», differs from the old design value and stays.
 */
function sh_rankmath_follow_design( int $id, array $old ): void {
	$new = sh_rankmath_design_values( $id );
	foreach ( $old as $key => $was ) {
		$now = (string) get_post_meta( $id, $key, true );
		if ( '' !== $was && $now === $was && '' !== ( $new[ $key ] ?? '' ) && $new[ $key ] !== $was ) {
			update_post_meta( $id, $key, wp_slash( $new[ $key ] ) );
		}
	}
}

/* ------------------------------------------------------------------ front end */

add_action(
	'plugins_loaded',
	static function () {
		if ( ! sh_rankmath_active() ) {
			return;
		}
		// Before the move has run, a Core value fills only an empty Rank Math field.
		add_filter(
			'rank_math/frontend/title',
			static function ( $title ) {
				$id = sh_seo_object_id();
				if ( ! $id || sh_rankmath_migrated() || '' !== (string) get_post_meta( $id, 'rank_math_title', true ) ) {
					return $title;
				}
				$t = sh_core_plain( sh_core_field( 'sh_seo_title', $id, '' ) );
				return $t ? $t : $title;
			}
		);
		add_filter(
			'rank_math/frontend/description',
			static function ( $desc ) {
				$id = sh_seo_object_id();
				if ( ! $id || sh_rankmath_migrated() || '' !== (string) get_post_meta( $id, 'rank_math_description', true ) ) {
					return $desc;
				}
				$p = get_post( $id );
				$d = $p ? sh_rankmath_core_value( $p, 'sh_seo_description' ) : '';
				return $d ? $d : $desc;
			}
		);
	},
	20
);

/**
 * Structured data has one source: SEO House Core (inc/schema.php prints the whole graph in wp_head).
 * Rank Math's own JSON-LD stays off even if its Schema module is switched on: its graph is emptied
 * through its documented filter rank_math/json_ld, last of all, on front-end pages only — Rank
 * Math then prints no script. Its saved schema settings are not touched, and the editor preview in
 * the admin keeps working. Nothing else in Rank Math's head output (title, description, canonical,
 * robots, Open Graph) is affected.
 */
function sh_rankmath_no_json_ld( $data ) {
	if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return $data;
	}
	return array();
}
add_filter( 'rank_math/json_ld', 'sh_rankmath_no_json_ld', PHP_INT_MAX );

/* ------------------------------------------------------------------ admin: preview and move */

add_action(
	'admin_menu',
	static function () {
		if ( sh_rankmath_active() ) {
			add_submenu_page( 'seohouse-settings', __( 'نقل السيو إلى Rank Math', 'seohouse-core' ), __( 'نقل السيو إلى Rank Math', 'seohouse-core' ), 'manage_options', 'seohouse-rankmath', 'sh_rankmath_admin_page' );
		}
	},
	30
);

function sh_rankmath_admin_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$done = null;
	if ( isset( $_POST['sh_rankmath_apply'] ) ) {
		check_admin_referer( 'sh_rankmath_apply' );
		$done = sh_rankmath_apply();
	}
	if ( isset( $_POST['sh_rankmath_controls'] ) ) {
		check_admin_referer( 'sh_rankmath_controls' );
		$on = sh_rankmath_enable_controls();
		echo '<div class="notice notice-success"><p>' . esc_html( $on ? 'فُعّل صندوق Rank Math لـ: ' . implode( '، ', $on ) : 'صندوق Rank Math مفعّل لكل الأنواع.' ) . '</p></div>';
	}
	$plan   = sh_rankmath_plan();
	$counts = array_count_values( wp_list_pluck( $plan, 'action' ) );
	$fmt    = static fn( $v ) => is_array( $v ) ? implode( ', ', $v ) : (string) $v;
	$names  = array( 'fill' => 'سيُنقل (حقل Rank Math فارغ)', 'same' => 'مطابق', 'keep' => 'يبقى كما هو (قيمة حرّرها Rank Math)' );
	echo '<div class="wrap"><h1>' . esc_html__( 'نقل عنوان ووصف البحث إلى Rank Math', 'seohouse-core' ) . '</h1>';
	if ( null !== $done ) {
		echo '<div class="notice notice-success"><p>' . esc_html( sprintf( 'نُقلت %d قيمة إلى حقول Rank Math الفارغة. لم تتغير أي قيمة موجودة.', $done ) ) . '</p></div>';
	}
	if ( isset( $_GET['skipped'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		echo '<div class="notice notice-success"><p>' . esc_html__( 'Rank Math تعمل الآن بدون حساب. صندوقها يظهر في المحرر.', 'seohouse-core' ) . '</p></div>';
	}
	$labels = array( 'page' => 'الصفحات', 'post' => 'المقالات', 'case_study' => 'دراسات الحالة', 'team_member' => 'فريق العمل' );
	$off    = sh_rankmath_controls_off();
	echo '<h2>' . esc_html__( 'صندوق Rank Math في المحرر (SEO Controls)', 'seohouse-core' ) . '</h2><table class="widefat" style="max-width:40em"><tbody>';
	foreach ( sh_rankmath_post_types() as $t ) {
		if ( post_type_exists( $t ) ) {
			echo '<tr><td>' . esc_html( $labels[ $t ] ) . '</td><td>' . ( in_array( $t, $off, true ) ? '<strong>' . esc_html__( 'مطفأ', 'seohouse-core' ) . '</strong>' : esc_html__( 'ظاهر', 'seohouse-core' ) ) . '</td></tr>';
		}
	}
	echo '</tbody></table>';
	if ( $off ) {
		echo '<form method="post">';
		wp_nonce_field( 'sh_rankmath_controls' );
		submit_button( __( 'أظهر صندوق Rank Math لكل الأنواع', 'seohouse-core' ), 'secondary', 'sh_rankmath_controls', false );
		echo '</form>';
	}
	echo '<p style="max-width:62em">' . esc_html__( 'مع تفعيل Rank Math يُحرَّر عنوان البحث ووصفه وصورة المشاركة والفهرسة من صندوق Rank Math في محرر الصفحة فقط، وتُخفى الحقول المكررة من «السيو ومسار التنقل». هذه الصفحة تنقل القيم الموجودة في حقول سيو هاوس مرة واحدة إلى حقول Rank Math الفارغة فقط؛ القيم التي حرّرتها في Rank Math تبقى كما هي.', 'seohouse-core' ) . '</p>';
	$last = get_option( 'sh_rankmath_migrated' );
	if ( is_array( $last ) ) {
		echo '<p>' . esc_html( sprintf( 'آخر نقل: %s (%d قيمة).', wp_date( 'Y-m-d H:i', (int) $last['time'] ), (int) $last['written'] ) ) . '</p>';
	}
	echo '<p><strong>' . esc_html( sprintf( 'سيُنقل: %d — مطابق: %d — يبقى: %d', $counts['fill'] ?? 0, $counts['same'] ?? 0, $counts['keep'] ?? 0 ) ) . '</strong></p>';
	echo '<form method="post">';
	wp_nonce_field( 'sh_rankmath_apply' );
	submit_button( __( 'انقل القيم إلى حقول Rank Math الفارغة', 'seohouse-core' ), 'primary', 'sh_rankmath_apply', false, empty( $counts['fill'] ) ? array( 'disabled' => 'disabled' ) : array() );
	echo '</form>';
	echo '<table class="widefat striped" style="margin-top:16px"><thead><tr><th>الصفحة</th><th>الحقل</th><th>قيمة سيو هاوس</th><th>قيمة Rank Math الحالية</th><th>الإجراء</th></tr></thead><tbody>';
	foreach ( $plan as $r ) {
		echo '<tr><td><a href="' . esc_url( (string) get_edit_post_link( $r['id'] ) ) . '">' . esc_html( $r['title'] ) . '</a> <small>(' . esc_html( $r['type'] ) . ')</small></td><td>' . esc_html( $r['label'] ) . '</td><td>' . esc_html( $fmt( $r['core'] ) ) . '</td><td>' . esc_html( $fmt( $r['rank_math'] ) ) . '</td><td>' . esc_html( $names[ $r['action'] ] ) . '</td></tr>';
	}
	echo '</tbody></table>';
	echo '<h2 id="sh-schema">' . esc_html__( 'البيانات المنظمة', 'seohouse-core' ) . '</h2><p style="max-width:62em">' . esc_html__( 'سيو هاوس Core هي المصدر الوحيد للبيانات المنظمة: تطبع مخططًا واحدًا كاملًا من إعدادات سيو هاوس وحقول المحتوى، سواء كانت وحدة Schema في Rank Math متوقفة أو تعمل (يبقى إخراج Rank Math للبيانات المنظمة متوقفًا دون تغيير إعداداته). Rank Math يبقى مسؤولًا عن العنوان والوصف وcanonical وrobots وOpen Graph وخرائط الموقع.', 'seohouse-core' ) . '</p>';
	echo '<p><a class="button" href="' . esc_url( admin_url( 'admin.php?page=seohouse-schema' ) ) . '">' . esc_html__( 'فحص البيانات المنظمة', 'seohouse-core' ) . '</a></p></div>';
}

/* ------------------------------------------------------------------ sitemap cache after a move or an update */

/**
 * Rank Math keeps the XML sitemaps it built in files (uploads/rank-math). A site move that copies
 * files over the old site (All-in-One WP Migration, FTP) leaves the old site's cached sitemaps
 * there, and Rank Math keeps serving them: addresses of the previous site, old dates. When Core is
 * installed or updated, the cache is emptied once so Rank Math builds the sitemaps from this site's
 * content. Nothing else changes (no content, no SEO values, no Rank Math settings).
 */
function sh_rankmath_sitemap_cache_clear(): bool {
	if ( ! class_exists( '\RankMath\Sitemap\Cache' ) || ! method_exists( '\RankMath\Sitemap\Cache', 'invalidate_storage' ) ) {
		return false;
	}
	\RankMath\Sitemap\Cache::invalidate_storage();
	return true;
}

add_action(
	'admin_init',
	static function () {
		if ( get_option( 'sh_core_sitemap_cache_version' ) === SH_CORE_VERSION || ! sh_rankmath_active() ) {
			return;
		}
		if ( sh_rankmath_sitemap_cache_clear() ) {
			update_option( 'sh_core_sitemap_cache_version', SH_CORE_VERSION, false );
		}
	}
);
