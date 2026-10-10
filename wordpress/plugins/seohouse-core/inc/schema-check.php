<?php
/**
 * Structured data check, read-only: what the live pages print for the company compared with
 * «إعدادات سيو هاوس», and the records that need an editor's decision (an article pointing to a
 * deleted profile, a footer link that is not a profile, an empty «اسم الخدمة في Schema», an article
 * without a real image, ratings in the page). Shown in «إعدادات سيو هاوس ← فحص البيانات المنظمة» and
 * as `wp seohouse-schema check`. Nothing is written.
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

/** JSON-LD nodes printed on a page of this site (fetched like a visitor). */
function sh_schema_check_fetch( string $url ): array {
	$r = wp_remote_get(
		$url,
		array(
			'timeout'   => 20,
			'sslverify' => false, // the site's own address (loopback)
			'headers'   => array( 'Cache-Control' => 'no-cache' ),
		)
	);
	if ( is_wp_error( $r ) || 200 !== (int) wp_remote_retrieve_response_code( $r ) ) {
		return array( 'error' => is_wp_error( $r ) ? $r->get_error_message() : 'HTTP ' . wp_remote_retrieve_response_code( $r ) );
	}
	$nodes = array();
	$bad   = 0;
	preg_match_all( '#<script[^>]*application/ld\+json[^>]*>(.*?)</script>#is', (string) wp_remote_retrieve_body( $r ), $m );
	foreach ( $m[1] as $json ) {
		$d = json_decode( html_entity_decode( $json ), true );
		if ( ! is_array( $d ) ) {
			++$bad;
			continue;
		}
		foreach ( isset( $d['@graph'] ) ? (array) $d['@graph'] : array( $d ) as $n ) {
			if ( is_array( $n ) ) {
				$nodes[] = $n;
			}
		}
	}
	return array(
		'nodes'   => $nodes,
		'invalid' => $bad,
		'blocks'  => count( $m[1] ),
	);
}

/** Text of a printed value for display/comparison. */
function sh_schema_check_text( $v ): string {
	if ( is_array( $v ) ) {
		if ( isset( $v['url'] ) && is_string( $v['url'] ) ) {
			return $v['url'];
		}
		if ( isset( $v['streetAddress'] ) || isset( $v['addressLocality'] ) ) {
			return implode( '، ', array_filter( array( $v['streetAddress'] ?? '', $v['addressLocality'] ?? '', $v['addressRegion'] ?? '', $v['addressCountry'] ?? '' ), static fn( $x ) => is_string( $x ) && '' !== trim( $x ) ) );
		}
		if ( isset( $v['name'] ) && is_string( $v['name'] ) ) {
			return $v['name'];
		}
		return implode( '، ', array_map( 'sh_schema_check_text', $v ) );
	}
	return sh_core_plain( (string) $v );
}

/**
 * @return array{fetch:string,company:array,cases:array,ratings:string[]}
 *   company rows: [label, printed, settings, status]; status ok | conflict | missing | review
 */
function sh_schema_check(): array {
	$out  = array(
		'fetch'   => '',
		'company' => array(),
		'cases'   => array(),
		'ratings' => array(),
	);
	$home = sh_schema_check_fetch( home_url( '/' ) );
	$core = sh_schema_organization();
	$org  = null;
	if ( isset( $home['error'] ) ) {
		$out['fetch'] = $home['error'];
	} else {
		if ( $home['invalid'] ) {
			$out['cases'][] = sprintf( 'الصفحة الرئيسية: %d من كتل JSON-LD غير صالحة.', $home['invalid'] );
		}
		$orgs = array_filter( $home['nodes'], static fn( $n ) => array_intersect( (array) ( $n['@type'] ?? array() ), array( 'Organization', 'Person' ) ) && str_ends_with( (string) ( $n['@id'] ?? '' ), '#organization' ) );
		$org  = $orgs ? reset( $orgs ) : null;
		if ( ! $org ) {
			$out['cases'][] = 'لم تُطبع عقدة الشركة بمعرّف ' . sh_schema_id( 'organization' ) . ' في الصفحة الرئيسية.';
		} elseif ( 'Organization' !== ( $org['@type'] ?? '' ) ) {
			$out['cases'][] = 'نوع عقدة الشركة ليس Organization: ' . wp_json_encode( $org['@type'] );
		}
	}
	$norm   = array(
		'telephone' => static fn( $v ) => preg_replace( '/\D+/', '', $v ),
		'email'     => static fn( $v ) => strtolower( $v ),
	);
	$labels = array(
		'name'          => 'الاسم',
		'alternateName' => 'الاسم اللاتيني',
		'url'           => 'الرابط',
		'logo'          => 'الشعار',
		'description'   => 'الوصف',
		'foundingDate'  => 'سنة التأسيس',
		'telephone'     => 'الهاتف',
		'email'         => 'البريد',
		'address'       => 'العنوان',
		'areaServed'    => 'الأسواق',
		'sameAs'        => 'الحسابات الرسمية',
	);
	foreach ( $labels as $p => $label ) {
		$printed  = $org ? sh_schema_check_text( $org[ $p ] ?? '' ) : '';
		$settings = sh_schema_check_text( $core[ $p ] ?? '' );
		$f        = $norm[ $p ] ?? static fn( $v ) => $v;
		if ( '' === $printed && '' === $settings ) {
			$status = 'missing';
		} elseif ( $f( $printed ) === $f( $settings ) ) {
			$status = 'ok';
		} elseif ( 'sameAs' === $p || 'logo' === $p || ( 'address' === $p && is_array( $org[ $p ] ?? null ) ) ) {
			$status = 'review'; // Rank Math's own list, image or structured address: compare by eye
		} else {
			$status = '' === $settings ? 'review' : 'conflict';
		}
		$out['company'][] = array( $label, $printed, $settings, $status );
	}
	if ( $org && ( isset( $org['aggregateRating'] ) || isset( $org['review'] ) ) ) {
		$out['ratings'][] = 'عقدة الشركة';
	}

	// ratings anywhere in the home page and in a page with the reviews section
	$pages = array( home_url( '/' ) => $home );
	foreach ( function_exists( 'sh_core_reviews_pages' ) ? array_slice( sh_core_reviews_pages(), 0, 1 ) : array() as $p ) {
		$u = (string) get_permalink( $p );
		if ( $u && ! isset( $pages[ $u ] ) ) {
			$pages[ $u ] = sh_schema_check_fetch( $u );
		}
	}
	foreach ( $pages as $u => $res ) {
		foreach ( (array) ( $res['nodes'] ?? array() ) as $n ) {
			if ( preg_match( '/"(AggregateRating|Review)"|"(aggregateRating|review)"\s*:/', (string) wp_json_encode( $n ) ) ) {
				$out['ratings'][] = $u;
				break;
			}
		}
	}

	// footer links that are not identity profiles
	foreach ( (array) sh_core_option( 'sh_socials', array() ) as $s ) {
		$u    = trim( (string) ( $s['url'] ?? '' ) );
		$why  = $u ? sh_schema_profile_problem( $u ) : '';
		if ( $why ) {
			$out['cases'][] = sprintf( 'رابط «%s» في «روابط التواصل في الفوتر» لا يُستخدم في sameAs (%s): %s', sh_core_plain( $s['label'] ?? '' ), $why, $u );
		}
	}
	$markets = sh_schema_markets();
	foreach ( $markets['other'] as $name ) {
		$out['cases'][] = sprintf( '«%s» في «الأسواق المخدومة» ليس دولة معروفة (السعودية، مصر، الإمارات)، فلا يُطبع في areaServed.', $name );
	}
	if ( ! preg_match( '/^\d{4}$/', trim( (string) sh_core_option( 'sh_founded', '' ) ) ) ) {
		$out['cases'][] = '«سنة التأسيس» ليست سنة من 4 أرقام، فلا يُطبع foundingDate.';
	}
	$logo = sh_schema_logo();
	if ( $logo && ( (int) ( $logo['width'] ?? 0 ) < 112 || (int) ( $logo['height'] ?? 0 ) < 112 ) && ! ( $org && ! empty( $org['logo'] ) && sh_schema_check_text( $org['logo'] ) !== ( $logo['url'] ?? '' ) ) ) {
		$out['cases'][] = sprintf( 'الشعار %dx%d بكسل؛ Google يطلب 112x112 على الأقل. ارفع «الشعار على الخلفية الفاتحة» بمقاس أكبر (أو اضبط شعار Rank Math).', (int) ( $logo['width'] ?? 0 ), (int) ( $logo['height'] ?? 0 ) );
	}

	// articles
	foreach ( get_posts( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => -1 ) ) as $p ) {
		[ $member, $linked ] = sh_schema_post_writer( $p );
		if ( ! $member && $linked ) {
			$out['cases'][] = sprintf( 'المقال «%s» مرتبط بعضو فريق محذوف أو غير منشور (#%d): لا يُطبع له مؤلف، ويظهر «بقلم» بلا اسم. اختر كاتبًا منشورًا في حقل «الكاتب» أو أفرغه.', get_the_title( $p ), $linked );
		}
		if ( ! has_post_thumbnail( $p ) ) {
			$out['cases'][] = sprintf( 'المقال «%s» بلا صورة بارزة: لا يُطبع image.', get_the_title( $p ) );
		}
	}
	foreach ( get_posts( array( 'post_type' => 'case_study', 'post_status' => 'publish', 'posts_per_page' => -1 ) ) as $p ) {
		if ( '' === sh_schema_post_image( $p ) ) {
			$out['cases'][] = sprintf( 'دراسة الحالة «%s» بلا صورة بارزة ولا لقطة موثقة: لا يُطبع image.', get_the_title( $p ) );
		}
	}
	foreach ( get_posts( array( 'post_type' => 'page', 'post_status' => 'publish', 'posts_per_page' => -1 ) ) as $p ) {
		if ( 'service' === sh_schema_page_type( $p->ID ) && '' === sh_core_plain( sh_core_field( 'sh_schema_service', $p->ID, '' ) ) ) {
			$out['cases'][] = sprintf( 'صفحة «%s»: «اسم الخدمة في Schema» فارغ، فلا يُطبع serviceType.', get_the_title( $p ) );
		}
	}
	return $out;
}

add_action(
	'admin_menu',
	static function () {
		add_submenu_page( 'seohouse-settings', __( 'فحص البيانات المنظمة', 'seohouse-core' ), __( 'فحص البيانات المنظمة', 'seohouse-core' ), 'manage_options', 'seohouse-schema', 'sh_schema_check_page' );
	},
	30
);

function sh_schema_check_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	echo '<div class="wrap"><h1>' . esc_html__( 'فحص البيانات المنظمة', 'seohouse-core' ) . '</h1><p style="max-width:62em">' . esc_html__( 'يقرأ الصفحة الرئيسية كما يراها الزائر، ويقارن بيانات الشركة المطبوعة في Schema بإعدادات سيو هاوس (الظاهرة في الموقع)، ويعرض الحالات التي تحتاج قرارًا منك. لا يغيّر أي قيمة. عند التعارض صحّح القيمة في مصدرها: Rank Math ← العناوين والوصف ← Local SEO، أو «إعدادات سيو هاوس».', 'seohouse-core' ) . '</p>';
	$check_url = wp_nonce_url( add_query_arg( 'sh_schema_check', 1, admin_url( 'admin.php?page=seohouse-schema' ) ), 'sh_schema_check' );
	if ( ! isset( $_GET['sh_schema_check'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ?? '' ) ), 'sh_schema_check' ) ) {
		echo '<p><a class="button button-primary" href="' . esc_url( $check_url ) . '">' . esc_html__( 'افحص الآن', 'seohouse-core' ) . '</a></p></div>';
		return;
	}
	$c      = sh_schema_check();
	$status = array(
		'ok'       => 'مطابق',
		'conflict' => 'تعارض: Rank Math يطبع قيمة غير الظاهرة في الموقع — صحّح المصدر الخاطئ',
		'missing'  => 'غير موجود في المصدرين',
		'review'   => 'راجع بالنظر (قيمة Rank Math أو قائمة أو صورة)',
	);
	if ( $c['fetch'] ) {
		echo '<div class="notice notice-warning inline"><p>' . esc_html( 'تعذرت قراءة الصفحة الرئيسية من الخادم نفسه: ' . $c['fetch'] ) . '</p></div>';
	}
	echo '<table class="widefat striped" style="max-width:80em"><thead><tr><th>' . esc_html__( 'الخاصية', 'seohouse-core' ) . '</th><th>' . esc_html__( 'المطبوع في Schema', 'seohouse-core' ) . '</th><th>' . esc_html__( 'إعدادات سيو هاوس (الظاهر للزائر)', 'seohouse-core' ) . '</th><th>' . esc_html__( 'الحالة', 'seohouse-core' ) . '</th></tr></thead><tbody>';
	foreach ( $c['company'] as $r ) {
		echo '<tr><td>' . esc_html( $r[0] ) . '</td><td>' . esc_html( $r[1] ) . '</td><td>' . esc_html( $r[2] ) . '</td><td>' . ( 'conflict' === $r[3] ? '<strong>' . esc_html( $status[ $r[3] ] ) . '</strong>' : esc_html( $status[ $r[3] ] ) ) . '</td></tr>';
	}
	echo '</tbody></table>';
	$cases = $c['cases'];
	foreach ( $c['ratings'] as $u ) {
		$cases[] = 'تقييمات (AggregateRating أو Review) في Schema: ' . $u . ' — أوقف خيار Schema/Rich snippet في إضافة التقييمات؛ التقييمات الظاهرة تبقى.';
	}
	echo '<h3>' . esc_html__( 'حالات تحتاج مراجعة', 'seohouse-core' ) . '</h3>';
	echo $cases ? '<ul style="list-style:disc;padding-inline-start:20px;max-width:80em">' . implode( '', array_map( static fn( $x ) => '<li>' . esc_html( $x ) . '</li>', $cases ) ) . '</ul>' : '<p>' . esc_html__( 'لا شيء.', 'seohouse-core' ) . '</p>';
	echo '</div>';
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command(
		'seohouse-schema check',
		static function () {
			$c = sh_schema_check();
			if ( $c['fetch'] ) {
				WP_CLI::warning( 'Home page not fetched: ' . $c['fetch'] );
			}
			foreach ( $c['company'] as $r ) {
				WP_CLI::log( sprintf( '%-8s %s | printed: %s | settings: %s', $r[3], $r[0], $r[1], $r[2] ) );
			}
			foreach ( $c['ratings'] as $r ) {
				WP_CLI::log( 'rating   ' . $r );
			}
			foreach ( $c['cases'] as $r ) {
				WP_CLI::log( 'review   ' . $r );
			}
			WP_CLI::success( count( array_filter( $c['company'], static fn( $r ) => 'conflict' === $r[3] ) ) . ' conflict(s), ' . count( $c['cases'] ) . ' case(s) to review, ' . count( $c['ratings'] ) . ' page(s) with ratings' );
		}
	);
}
