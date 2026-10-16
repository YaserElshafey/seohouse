<?php
/**
 * «تهيئة الموقع» → سياسة الخصوصية والشروط والأحكام.
 *
 * The text of these two pages is never written by an update (class-importer.php: LEGAL). This box
 * shows, for the page that actually answers /privacy-policy/ and /terms/, whether it carries the
 * text of the content pack — the wording that was published on the main site until the move,
 * word for word (docs/legal) — and applies it on request, only when the page's current text was
 * put there automatically:
 *
 * - empty, or only the design's "[يُضاف النص القانوني المعتمد قبل النشر]" placeholders;
 * - the unapproved draft that content pack 2.5.0 wrote.
 *
 * Any other text was written by someone and is never replaced. Before writing, the page's field
 * values, status and Rank Math title/description/robots are kept (meta _sh_pre_legal_text and a
 * JSON file in uploads/seohouse-backups). Only the page's two sections (title area and text) are
 * written: status, address, Rank Math values (title, description, robots, canonical) stay.
 * WP-CLI: wp seohouse legal [--apply=<privacy-policy|terms>]
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

const SH_LEGAL_PAGES = array(
	'privacy-policy' => 'سياسة الخصوصية',
	'terms'          => 'الشروط والأحكام',
);

/**
 * Values put there automatically by earlier content packs (never typed by a person).
 * bodies: md5 of the list of section texts (sh_legal_fingerprint); hero: the title-area sentence.
 */
const SH_LEGAL_AUTOMATIC = array(
	'privacy-policy' => array(
		'bodies' => array( '288ebf9b88e67bb01ee4acff2fb67d7e' ), // 2.5.0 draft (not approved)
		'hero'   => array( '', 'توضح هذه الصفحة كيف يتعامل الموقع مع بياناتك. النص قيد المراجعة القانونية قبل النشر.', 'توضح هذه السياسة كيف تتعامل سيو هاوس مع البيانات الشخصية لزوار هذا الموقع ولمن يطلبون استشارة أو خدمة من خلاله: ما الذي نجمعه، ولماذا، ومن يطّلع عليه، وكم نحتفظ به، وما حقوقك تجاهه.' ),
	),
	'terms'          => array(
		'bodies' => array( '6c82f57be4f5c59e327b8462d4be7ad2' ), // 2.5.0 draft (not approved)
		'hero'   => array( '', 'شروط استخدام الموقع وطلب الخدمات. النص قيد المراجعة القانونية قبل النشر.', 'تنظّم هذه الشروط استخدامك لموقع سيو هاوس وطلب خدماتها من خلاله. باستخدام الموقع أو إرسال أي نموذج فيه، فإنك توافق على هذه الشروط وعلى سياسة الخصوصية. إذا لم توافق عليها، فالرجاء عدم استخدام الموقع.' ),
	),
);

function sh_legal_fingerprint( array $bodies ): string {
	return md5( wp_json_encode( array_map( static fn( $b ) => trim( str_replace( "\r\n", "\n", (string) $b ) ), $bodies ), JSON_UNESCAPED_UNICODE ) );
}

/** The page visitors get on /<key>/ (published first), else the newest page with that address. */
function sh_legal_page( string $key ): ?WP_Post {
	$pages = get_posts(
		array(
			'post_type'        => 'page',
			'name'             => $key,
			'post_parent'      => 0,
			'post_status'      => array( 'publish', 'private', 'draft', 'pending', 'future' ),
			'posts_per_page'   => -1,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'suppress_filters' => true,
		)
	);
	foreach ( $pages as $p ) {
		if ( 'publish' === $p->post_status ) {
			return $p;
		}
	}
	return $pages[0] ?? null;
}

/** The pack's values for one legal page: [hero, document] or null. */
function sh_legal_pack( string $key ): ?array {
	$seed = json_decode( (string) @file_get_contents( SH_Importer::default_dir() . '/pages/' . $key . '.json' ), true ); // phpcs:ignore
	if ( ! is_array( $seed ) ) {
		return null;
	}
	$out = array( 'hero' => array(), 'document' => array() );
	foreach ( (array) $seed['sections'] as $s ) {
		$layout = $s['acf_fc_layout'] ?? '';
		if ( isset( $out[ $layout ] ) ) {
			unset( $s['acf_fc_layout'] );
			$out[ $layout ] = $s;
		}
	}
	return $out;
}

/** Field keys of the page's two sections and their sub-fields (from the field group JSON). */
function sh_legal_keys( string $key ): array {
	$g   = json_decode( (string) @file_get_contents( SH_CORE_DIR . 'acf-json/group_sh_page_' . str_replace( '-', '_', $key ) . '.json' ), true ); // phpcs:ignore
	$out = array();
	foreach ( (array) ( $g['fields'] ?? array() ) as $f ) {
		if ( in_array( $f['name'] ?? '', array( 's_hero', 's_document' ), true ) ) {
			$out[ $f['name'] ] = $f['key'];
		}
	}
	return $out;
}

/**
 * State of one legal page.
 *
 * @return array{page:?WP_Post,state:string,label:string,mapped:?WP_Post,robots:string}
 *         state: missing | pack | automatic | manual | nopack
 */
function sh_legal_state( string $key ): array {
	$page   = sh_legal_page( $key );
	$pack   = sh_legal_pack( $key );
	$mapped = get_posts( array( 'post_type' => 'page', 'post_status' => 'any', 'posts_per_page' => 1, 'meta_key' => SH_Importer::META_KEY, 'meta_value' => 'page:' . $key ) ); // phpcs:ignore WordPress.DB.SlowDBQuery
	$out    = array( 'page' => $page, 'state' => 'missing', 'label' => 'لا توجد صفحة على /' . $key . '/', 'mapped' => $mapped ? $mapped[0] : null, 'robots' => '' );
	if ( ! $page ) {
		return $out;
	}
	if ( function_exists( 'sh_rankmath_active' ) && sh_rankmath_active() ) {
		$robots        = get_post_meta( $page->ID, 'rank_math_robots', true );
		$out['robots'] = is_array( $robots ) && in_array( 'noindex', $robots, true ) ? 'noindex' : 'index';
	} else {
		$out['robots'] = sh_core_field( 'sh_seo_noindex', $page->ID, false ) ? 'noindex' : 'index';
	}
	if ( ! $pack ) {
		$out['state'] = 'nopack';
		$out['label'] = 'حزمة المحتوى غير متاحة';
		return $out;
	}
	$doc  = get_field( 's_document', $page->ID );
	$rows = is_array( $doc['items_2'] ?? null ) ? array_values( $doc['items_2'] ) : array();
	$have = array_map( static fn( $r ) => trim( str_replace( "\r\n", "\n", (string) ( $r['body'] ?? '' ) ) ), $rows );
	$want = array_map( static fn( $r ) => trim( (string) ( $r['body'] ?? '' ) ), (array) ( $pack['document']['items_2'] ?? array() ) );
	if ( $have === $want ) {
		$out['state'] = 'pack';
		$out['label'] = 'فيها النص المعتمد';
	} elseif ( '' === implode( '', $have ) || in_array( sh_legal_fingerprint( $have ), SH_LEGAL_AUTOMATIC[ $key ]['bodies'], true ) ) {
		$out['state'] = 'automatic';
		$out['label'] = '' === implode( '', $have ) ? 'بلا نص (فارغة أو نص مؤقت بين معقوفين)' : 'فيها مسودة 2.5.0 غير المعتمدة';
	} else {
		$out['state'] = 'manual';
		$out['label'] = 'فيها نص كتبه شخص (عُدّل يدويًا) — لا يُكتب فوقه';
	}
	return $out;
}

/** Applies the pack's text to one legal page when its current text is automatic. Returns [ok, message]. */
function sh_legal_apply( string $key ): array {
	if ( ! isset( SH_LEGAL_PAGES[ $key ] ) ) {
		return array( false, 'صفحة غير معروفة.' );
	}
	$st = sh_legal_state( $key );
	if ( 'automatic' !== $st['state'] ) {
		return array( false, SH_LEGAL_PAGES[ $key ] . ': ' . $st['label'] . '. لم يُكتب شيء.' );
	}
	$page = $st['page'];
	$pack = sh_legal_pack( $key );
	$keys = sh_legal_keys( $key );
	if ( empty( $keys['s_hero'] ) || empty( $keys['s_document'] ) ) {
		return array( false, 'مجموعة حقول الصفحة غير موجودة.' );
	}
	// copy first
	$copy  = array(
		'time'        => gmdate( 'c' ),
		'page'        => $page->ID,
		'post_status' => $page->post_status,
		'fields'      => get_fields( $page->ID, false ),
		'rank_math'   => array(
			'title'       => get_post_meta( $page->ID, 'rank_math_title', true ),
			'description' => get_post_meta( $page->ID, 'rank_math_description', true ),
			'robots'      => get_post_meta( $page->ID, 'rank_math_robots', true ),
		),
	);
	$all   = get_post_meta( $page->ID, '_sh_pre_legal_text', true );
	$all   = is_array( $all ) ? $all : array();
	$all[] = $copy;
	update_post_meta( $page->ID, '_sh_pre_legal_text', wp_slash( array_slice( $all, -5 ) ) );
	$up  = wp_upload_dir();
	$dir = trailingslashit( $up['basedir'] ) . 'seohouse-backups';
	wp_mkdir_p( $dir );
	if ( ! file_exists( $dir . '/index.php' ) ) {
		file_put_contents( $dir . '/index.php', "<?php // Silence.\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		file_put_contents( $dir . '/.htaccess', "Require all denied\nDeny from all\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	}
	$file = $dir . '/' . $key . '-before-legal-text-' . gmdate( 'Ymd-His' ) . '-' . wp_generate_password( 6, false ) . '.json';
	file_put_contents( $file, wp_json_encode( $copy, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions

	// title area: the page title if empty; the sentence under it only if it was automatic
	$hero = get_field( 's_hero', $page->ID );
	$hero = is_array( $hero ) ? $hero : array();
	if ( '' === trim( (string) ( $hero['title'] ?? '' ) ) ) {
		$hero['title'] = $pack['hero']['title'] ?? get_the_title( $page );
	}
	if ( in_array( trim( (string) ( $hero['text'] ?? '' ) ), SH_LEGAL_AUTOMATIC[ $key ]['hero'], true ) ) {
		$hero['text'] = (string) ( $pack['hero']['text'] ?? '' );
	}
	if ( isset( $pack['hero']['link'] ) && empty( $hero['link'] ) ) {
		$target = get_page_by_path( trim( (string) ( $pack['hero']['link']['__route'] ?? '' ), '/' ) );
		if ( $target ) {
			$hero['link']       = $target->ID;
			$hero['link_label'] = $hero['link_label'] ?? ( $pack['hero']['link_label'] ?? '' );
		}
	}
	update_field( $keys['s_hero'], $hero, $page->ID );
	$doc            = get_field( 's_document', $page->ID );
	$doc            = is_array( $doc ) ? $doc : array();
	$doc['eyebrow'] = '' !== trim( (string) ( $doc['eyebrow'] ?? '' ) ) ? $doc['eyebrow'] : (string) ( $pack['document']['eyebrow'] ?? '' );
	$doc['label']   = (string) ( $pack['document']['label'] ?? '' );
	$doc['updated'] = (string) ( $pack['document']['updated'] ?? '' );
	$doc['items_2'] = array_map( static fn( $r ) => array( 'title' => (string) ( $r['title'] ?? '' ), 'body' => (string) ( $r['body'] ?? '' ) ), (array) ( $pack['document']['items_2'] ?? array() ) );
	unset( $doc['items'] );
	update_field( $keys['s_document'], $doc, $page->ID );
	clean_post_cache( $page->ID );
	if ( function_exists( 'sh_search_index' ) ) {
		sh_search_index( $page->ID );
	}
	$after = sh_legal_state( $key );
	return array(
		'pack' === $after['state'],
		'pack' === $after['state']
			? sprintf( 'طُبّق النص المعتمد على «%s» (الصفحة #%d، %s). حُفظت نسخة من قيمها السابقة: %s', SH_LEGAL_PAGES[ $key ], $page->ID, 'publish' === get_post_status( $page ) ? 'منشورة' : 'مسودة', str_replace( ABSPATH, '', $file ) )
			: 'لم يكتمل التطبيق: ' . $after['label'],
	);
}

/** The box at the end of «تهيئة الموقع». */
function sh_legal_box(): void {
	$msg = null;
	if ( isset( $_POST['sh_legal_apply'] ) && check_admin_referer( 'sh_legal_apply' ) && current_user_can( 'manage_options' ) ) {
		$msg = sh_legal_apply( sanitize_key( wp_unslash( $_POST['sh_legal_apply'] ) ) );
	}
	echo '<h2 id="sh-legal">' . esc_html__( 'سياسة الخصوصية والشروط والأحكام', 'seohouse-core' ) . '</h2>';
	if ( $msg ) {
		echo '<div class="notice notice-' . ( $msg[0] ? 'success' : 'error' ) . ' inline" style="max-width:60em"><p>' . esc_html( $msg[1] ) . '</p></div>';
	}
	echo '<p style="max-width:60em">' . esc_html__( 'التهيئة لا تغيّر نص هاتين الصفحتين. النص المعتمد في الحزمة هو نص الموقع الأساسي كما كان منشورًا حتى النقل (آخر تحديث 30 مايو 2026) دون تعديل. يُطبَّق من هنا فقط على صفحة نصها فارغ أو مؤقت أو مسودة 2.5.0؛ الصفحة التي عدّلها أحد لا تُلمس. تُحفظ نسخة من قيمها أولًا، ولا تتغير حالتها ولا رابطها ولا إعدادات Rank Math (العنوان والوصف وrobots).', 'seohouse-core' ) . '</p>';
	echo '<form method="post"><table class="widefat striped" style="max-width:60em"><tbody>';
	wp_nonce_field( 'sh_legal_apply' );
	foreach ( SH_LEGAL_PAGES as $key => $title ) {
		$st = sh_legal_state( $key );
		$p  = $st['page'];
		echo '<tr data-legal="' . esc_attr( $key ) . '" data-state="' . esc_attr( $st['state'] ) . '"><th style="width:11em">' . esc_html( $title ) . '</th><td>';
		if ( $p ) {
			echo '<a href="' . esc_url( get_permalink( $p ) ) . '" target="_blank" dir="ltr">/' . esc_html( $key ) . '/</a> — <a href="' . esc_url( get_edit_post_link( $p->ID ) ) . '">#' . (int) $p->ID . '</a> — ' . esc_html( 'publish' === $p->post_status ? 'منشورة' : 'مسودة' ) . ' — <code>' . esc_html( $st['robots'] ) . '</code><br>';
		}
		echo '<strong>' . esc_html( $st['label'] ) . '</strong>';
		if ( $st['mapped'] && $p && $st['mapped']->ID !== $p->ID ) {
			echo '<br><small>' . esc_html( sprintf( 'للعلم: صفحة أخرى أنشأتها التهيئة (#%d، %s) ليست الصفحة التي تظهر على هذا الرابط.', $st['mapped']->ID, $st['mapped']->post_status ) ) . '</small>';
		}
		$pack = sh_legal_pack( $key );
		if ( $pack && 'pack' !== $st['state'] ) {
			echo '<details style="margin-top:6px"><summary>' . esc_html__( 'النص الذي سيُطبَّق', 'seohouse-core' ) . '</summary><div style="max-height:22em;overflow:auto;background:#fff;padding:8px 12px;border:1px solid #dcdcde">';
			foreach ( (array) $pack['document']['items_2'] as $r ) {
				if ( '' !== (string) ( $r['title'] ?? '' ) ) {
					echo '<h4 style="margin:10px 0 4px">' . esc_html( $r['title'] ) . '</h4>';
				}
				echo wp_kses_post( wpautop( (string) ( $r['body'] ?? '' ) ) );
			}
			echo '<p><em>' . esc_html( ( $pack['document']['label'] ?? '' ) . ' ' . ( $pack['document']['updated'] ?? '' ) ) . '</em></p></div></details>';
		}
		echo '</td><td style="width:12em">';
		if ( 'automatic' === $st['state'] ) {
			echo '<button class="button button-primary" name="sh_legal_apply" value="' . esc_attr( $key ) . '" onclick="return confirm(\'' . esc_js( __( 'تطبيق النص المعتمد على هذه الصفحة؟ تُحفظ نسخة من قيمها الحالية أولًا.', 'seohouse-core' ) ) . '\')">' . esc_html__( 'تطبيق النص المعتمد', 'seohouse-core' ) . '</button>';
		}
		echo '</td></tr>';
	}
	echo '</tbody></table></form>';
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	/**
	 * State of the privacy policy and terms pages; --apply=<key> applies the pack text (automatic text only).
	 *
	 * ## OPTIONS
	 *
	 * [--apply=<key>]
	 * : privacy-policy or terms.
	 */
	WP_CLI::add_command(
		'seohouse legal',
		static function ( $args, $assoc ) {
			if ( ! empty( $assoc['apply'] ) ) {
				[ $ok, $m ] = sh_legal_apply( (string) $assoc['apply'] );
				$ok ? WP_CLI::success( $m ) : WP_CLI::error( $m, false );
			}
			foreach ( SH_LEGAL_PAGES as $key => $title ) {
				$st = sh_legal_state( $key );
				WP_CLI::log( sprintf( '%-15s %-10s #%s %s %s — %s', $key, $st['state'], $st['page'] ? $st['page']->ID : '-', $st['page'] ? $st['page']->post_status : '', $st['robots'], $st['label'] ) );
			}
		}
	);
}
