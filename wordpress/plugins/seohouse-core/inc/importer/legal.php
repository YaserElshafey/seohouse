<?php
/**
 * «تهيئة الموقع» → privacy policy and terms: shows whether each page carries the text of the
 * bundled content pack, and applies it to one page on request, even when the page counts as
 * edited (e.g. published with the earlier placeholder text). Before writing, the page's current
 * field values and Rank Math title/description are kept (meta _sh_pre_legal_text + a JSON file
 * in uploads/seohouse-backups). The page keeps its ID, address and status (draft or published).
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

const SH_LEGAL_PAGES = array(
	'privacy-policy' => 'سياسة الخصوصية',
	'terms'          => 'الشروط والأحكام',
);

/** The page that holds a legal text: the one the setup created, else the page on its address. */
function sh_legal_page( string $key ): ?WP_Post {
	$found = get_posts( array( 'post_type' => 'page', 'post_status' => 'any', 'posts_per_page' => 1, 'meta_key' => SH_Importer::META_KEY, 'meta_value' => 'page:' . $key ) ); // phpcs:ignore WordPress.DB.SlowDBQuery
	if ( $found ) {
		return $found[0];
	}
	$p = get_page_by_path( $key );
	return $p instanceof WP_Post ? $p : null;
}

/**
 * State of one legal page against the pack.
 *
 * @return array{page:?WP_Post,state:string,label:string,version:string}
 *         state: missing | pack | placeholder | edited
 */
function sh_legal_state( string $key ): array {
	$dir  = SH_Importer::default_dir();
	$seed = json_decode( (string) @file_get_contents( $dir . '/pages/' . $key . '.json' ), true ); // phpcs:ignore
	$man  = SH_Importer::manifest_of( $dir );
	$page = sh_legal_page( $key );
	$out  = array( 'page' => $page, 'state' => 'missing', 'label' => 'لا توجد صفحة', 'version' => (string) ( $man['version'] ?? '' ) );
	if ( ! $page || ! is_array( $seed ) ) {
		return $out;
	}
	$want = array();
	foreach ( $seed['sections'] as $s ) {
		if ( 'document' === ( $s['acf_fc_layout'] ?? '' ) ) {
			$want = array_map( static fn( $r ) => trim( (string) ( $r['body'] ?? '' ) ), $s['items_2'] ?? array() );
		}
	}
	$doc  = function_exists( 'get_field' ) ? get_field( 's_document', $page->ID ) : array();
	$have = array_map( static fn( $r ) => trim( (string) ( $r['body'] ?? '' ) ), is_array( $doc['items_2'] ?? null ) ? $doc['items_2'] : array() );
	if ( $have === $want ) {
		$out['state'] = 'pack';
		$out['label'] = 'النص الحالي هو نص الحزمة ' . $out['version'];
	} elseif ( '' === implode( '', $have ) ) {
		$out['state'] = 'placeholder';
		$out['label'] = 'بلا نص (فارغة أو نص مؤقت)';
	} else {
		$out['state'] = 'edited';
		$out['label'] = 'نص مختلف عن الحزمة (عُدّل هنا)';
	}
	return $out;
}

/** Applies the pack's text to one legal page. Returns [ok, message]. */
function sh_legal_apply( string $key ): array {
	if ( ! isset( SH_LEGAL_PAGES[ $key ] ) ) {
		return array( false, 'صفحة غير معروفة.' );
	}
	$page = sh_legal_page( $key );
	if ( $page ) {
		$copy = array(
			'time'                  => gmdate( 'c' ),
			'page'                  => $page->ID,
			'post_status'           => $page->post_status,
			'fields'                => function_exists( 'get_fields' ) ? get_fields( $page->ID, false ) : array(),
			'rank_math_title'       => get_post_meta( $page->ID, 'rank_math_title', true ),
			'rank_math_description' => get_post_meta( $page->ID, 'rank_math_description', true ),
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
		file_put_contents( $dir . '/' . $key . '-before-legal-text-' . gmdate( 'Ymd-His' ) . '-' . wp_generate_password( 6, false ) . '.json', wp_json_encode( $copy, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	}
	$status = $page ? $page->post_status : '';
	$imp    = new SH_Importer(
		SH_Importer::default_dir(),
		array(
			'only'                 => array( 'pages' ),
			'update'               => array( 'page:' . $key ),
			'force'                => true,
			'adopt_existing_pages' => true, // /new/ only: a page on the address that the setup did not create
		)
	);
	$imp->run();
	$page = sh_legal_page( $key );
	if ( $page && $status && $page->post_status !== $status ) {
		wp_update_post( array( 'ID' => $page->ID, 'post_status' => $status ) ); // the page stays as it was: draft or published
	}
	$state = sh_legal_state( $key );
	return array( 'pack' === $state['state'], 'pack' === $state['state'] ? 'طُبّق النص على «' . SH_LEGAL_PAGES[ $key ] . '». حالتها: ' . ( 'publish' === ( $page->post_status ?? '' ) ? 'منشورة' : 'مسودة' ) . '. حُفظت نسخة من قيمها السابقة.' : 'لم يكتمل التطبيق: ' . $state['label'] );
}

/** The box at the end of the setup screen. */
function sh_legal_box(): void {
	$msg = null;
	if ( isset( $_POST['sh_legal_apply'] ) && check_admin_referer( 'sh_legal_apply' ) && current_user_can( 'manage_options' ) ) {
		$msg = sh_legal_apply( sanitize_key( wp_unslash( $_POST['sh_legal_apply'] ) ) );
	}
	echo '<h2 id="sh-legal">' . esc_html__( 'سياسة الخصوصية والشروط والأحكام', 'seohouse-core' ) . '</h2>';
	if ( $msg ) {
		echo '<div class="notice notice-' . ( $msg[0] ? 'success' : 'error' ) . ' inline" style="max-width:60em"><p>' . esc_html( $msg[1] ) . '</p></div>';
	}
	echo '<p style="max-width:60em">' . esc_html__( 'حزمة المحتوى تحمل نصًا مقترحًا للصفحتين يحتاج مراجعتك (بين معقوفين ما يجب أن تكمله: البريد والاسم القانوني والدولة). «إعادة التهيئة» تضعه في الصفحة التي لم تُعدَّل. إن عُدّلت الصفحة أو نُشرت بالنص المؤقت، فطبّقه من هنا: تُحفظ نسخة من قيمها الحالية أولًا، وتبقى الصفحة منشورة أو مسودة كما هي.', 'seohouse-core' ) . '</p>';
	echo '<form method="post"><table class="widefat striped" style="max-width:60em"><tbody>';
	wp_nonce_field( 'sh_legal_apply' );
	foreach ( SH_LEGAL_PAGES as $key => $title ) {
		$st = sh_legal_state( $key );
		$p  = $st['page'];
		echo '<tr data-legal="' . esc_attr( $key ) . '" data-state="' . esc_attr( $st['state'] ) . '"><th style="width:12em">' . esc_html( $title ) . '</th><td>';
		if ( $p ) {
			echo '<a href="' . esc_url( get_edit_post_link( $p->ID ) ) . '">' . esc_html( '#' . $p->ID ) . '</a> — ' . esc_html( 'publish' === $p->post_status ? 'منشورة' : 'مسودة' ) . ' — ';
		}
		echo esc_html( $st['label'] ) . '</td><td style="width:14em">';
		if ( 'pack' !== $st['state'] && 'missing' !== $st['state'] ) {
			$warn = 'edited' === $st['state'] ? __( 'سيحل نص الحزمة محل النص الحالي لهذه الصفحة (تُحفظ نسخة منه). متابعة؟', 'seohouse-core' ) : __( 'تطبيق نص الحزمة على هذه الصفحة؟', 'seohouse-core' );
			echo '<button class="button" name="sh_legal_apply" value="' . esc_attr( $key ) . '" onclick="return confirm(\'' . esc_js( $warn ) . '\')">' . esc_html__( 'تطبيق نص الحزمة', 'seohouse-core' ) . '</button>';
		}
		echo '</td></tr>';
	}
	echo '</tbody></table></form>';
}
