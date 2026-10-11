<?php
/**
 * Structured data check, read-only («إعدادات سيو هاوس ← فحص البيانات المنظمة», `wp seohouse-schema check`):
 *
 * - Output source: sample pages fetched like a visitor; each must carry exactly one graph from SEO
 *   House Core (script class "seohouse-schema") and no other structured data describing the same
 *   things (missing or duplicated schema is reported).
 * - The company printed compared with «إعدادات سيو هاوس» (the values shown on the site).
 * - Company values saved only in Rank Math (its stored settings are read for this report only; the
 *   graph never uses them): listed for a manual move, nothing is copied.
 * - Records needing an editor's decision (an article pointing to a deleted profile, a footer link that
 *   is not a profile, an empty «اسم الخدمة في Schema», an article without a real image, ratings).
 *
 * Nothing is written.
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * JSON-LD blocks printed on a page of this site (fetched like a visitor).
 *
 * @return array{error?:string,blocks?:array<int,array{class:string,valid:bool,nodes:array}>}
 */
function sh_schema_check_fetch( string $url ): array {
	$r = wp_remote_get(
		$url,
		array(
			'timeout'   => 20,
			'sslverify' => false, // the site's own address (loopback)
			'headers'   => array( 'Cache-Control' => 'no-cache' ),
		)
	);
	if ( is_wp_error( $r ) ) {
		return array( 'error' => $r->get_error_message() );
	}
	$blocks = array();
	preg_match_all( '#<script\b([^>]*)application/ld\+json([^>]*)>(.*?)</script>#is', (string) wp_remote_retrieve_body( $r ), $m, PREG_SET_ORDER );
	foreach ( $m as $s ) {
		$attrs = $s[1] . $s[2];
		$class = preg_match( '/class=["\']([^"\']*)["\']/', $attrs, $c ) ? $c[1] : '';
		$d     = json_decode( html_entity_decode( $s[3] ), true );
		$nodes = array();
		if ( is_array( $d ) ) {
			foreach ( isset( $d['@graph'] ) ? (array) $d['@graph'] : array( $d ) as $n ) {
				if ( is_array( $n ) ) {
					$nodes[] = $n;
				}
			}
		}
		$blocks[] = array(
			'class' => $class,
			'valid' => is_array( $d ),
			'nodes' => $nodes,
		);
	}
	return array(
		'status' => (int) wp_remote_retrieve_response_code( $r ),
		'blocks' => $blocks,
	);
}

/** Text of a printed value for display/comparison. */
function sh_schema_check_text( $v ): string {
	if ( is_array( $v ) ) {
		if ( isset( $v['url'] ) && is_string( $v['url'] ) ) {
			return $v['url'];
		}
		if ( isset( $v['streetAddress'] ) || isset( $v['addressLocality'] ) ) {
			return implode( '، ', array_filter( array( $v['streetAddress'] ?? '', $v['addressLocality'] ?? '', $v['addressRegion'] ?? '', $v['postalCode'] ?? '', $v['addressCountry'] ?? '' ), static fn( $x ) => is_string( $x ) && '' !== trim( $x ) ) );
		}
		if ( isset( $v['name'] ) && is_string( $v['name'] ) ) {
			return $v['name'];
		}
		return implode( '، ', array_map( 'sh_schema_check_text', $v ) );
	}
	return sh_core_plain( (string) $v );
}

/** Is this node the company (any spelling a plugin may use)? */
function sh_schema_check_is_company( array $n ): bool {
	$t = (array) ( $n['@type'] ?? array() );
	return str_ends_with( (string) ( $n['@id'] ?? '' ), '#organization' ) || array_intersect( $t, array( 'Organization', 'Corporation', 'LocalBusiness', 'ProfessionalService' ) );
}

/** Sample pages: one of each kind that exists. [label => url] */
function sh_schema_check_pages(): array {
	$pages = array( 'الرئيسية' => home_url( '/' ) );
	$keys  = array(
		'about'    => 'عن الشركة',
		'contact'  => 'اتصل بنا',
		'services' => 'الخدمات',
		'seo'      => 'خدمة السيو',
		'seo-ksa'  => 'السيو في السعودية',
		'sectors'  => 'القطاعات',
		'results'  => 'نتائج الأعمال',
		'team'     => 'الفريق',
	);
	$found = array();
	foreach ( get_posts( array( 'post_type' => 'page', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ) ) as $p ) {
		$k = sh_core_page_key( $p->ID );
		if ( isset( $keys[ $k ] ) && ! isset( $found[ $k ] ) ) {
			$found[ $k ] = (string) get_permalink( $p );
		} elseif ( str_starts_with( $k, 'sector-' ) && ! isset( $found['sector'] ) ) {
			$found['sector'] = (string) get_permalink( $p );
		}
	}
	foreach ( $keys as $k => $label ) {
		if ( isset( $found[ $k ] ) ) {
			$pages[ $label ] = $found[ $k ];
		}
	}
	if ( isset( $found['sector'] ) ) {
		$pages['قطاع'] = $found['sector'];
	}
	$blog = (int) get_option( 'page_for_posts' );
	if ( $blog ) {
		$pages['المدونة'] = (string) get_permalink( $blog );
	}
	foreach ( array( 'post' => 'مقال', 'case_study' => 'دراسة حالة', 'team_member' => 'عضو فريق' ) as $type => $label ) {
		$one = get_posts( array( 'post_type' => $type, 'post_status' => 'publish', 'posts_per_page' => 1, 'fields' => 'ids' ) );
		if ( $one ) {
			$pages[ $label ] = (string) get_permalink( $one[0] );
		}
	}
	return $pages;
}

/** Rank Math's switch for its Schema module (its own stored setting, read only). */
function sh_schema_check_rankmath_state(): array {
	return array(
		'installed' => function_exists( 'sh_rankmath_installed' ) && sh_rankmath_installed(),
		'active'    => function_exists( 'sh_rankmath_active' ) && sh_rankmath_active(),
		'schema'    => in_array( 'rich-snippet', (array) get_option( 'rank_math_modules', array() ), true ),
	);
}

/**
 * Company values that exist in Rank Math's saved settings: [label, Rank Math, SEO House, status, where].
 * status: move (only in Rank Math) | differs | same. Read for this report only.
 */
function sh_schema_check_rankmath_values(): array {
	$o = get_option( 'rank-math-options-titles', array() );
	if ( ! is_array( $o ) || ! $o ) {
		return array();
	}
	$rows   = array();
	$add    = static function ( string $label, string $rm, string $core, string $where, ?callable $norm = null ) use ( &$rows ) {
		$rm   = trim( $rm );
		$core = trim( $core );
		if ( '' === $rm ) {
			return;
		}
		$f      = $norm ? $norm : static fn( $v ) => $v;
		$status = '' === $core ? 'move' : ( $f( $rm ) === $f( $core ) ? 'same' : 'differs' );
		$rows[] = array( $label, $rm, $core, $status, $where );
	};
	$digits = static fn( $v ) => preg_replace( '/\D+/', '', $v );
	$add( 'اسم الشركة', (string) ( $o['knowledgegraph_name'] ?? '' ), sh_core_plain( sh_core_option( 'sh_company_name', '' ) ), 'الهوية ← اسم الشركة' );
	$add( 'الشعار', (string) ( $o['knowledgegraph_logo'] ?? '' ), sh_schema_image( sh_core_option( 'sh_logo_light', 0 ) ), 'الهوية ← الشعار على الخلفية الفاتحة' );
	$add( 'الهاتف', (string) ( $o['phone'] ?? '' ), sh_core_plain( sh_core_option( 'sh_phone', '' ) ), 'بيانات الشركة ← الهاتف', $digits );
	$add( 'البريد', (string) ( $o['email'] ?? '' ), (string) sh_core_option( 'sh_email', '' ), 'بيانات الشركة ← البريد الإلكتروني', 'strtolower' );
	$addr = $o['local_address'] ?? '';
	$add( 'العنوان', is_array( $addr ) ? sh_schema_check_text( $addr ) : (string) $addr, sh_core_plain( sh_core_option( 'sh_address', '' ) ), 'بيانات الشركة ← العنوان' );
	$profiles = array_filter( array_merge( array( (string) ( $o['social_url_facebook'] ?? '' ) ), preg_split( '/\r\n|\r|\n/', (string) ( $o['social_additional_profiles'] ?? '' ) ) ) );
	if ( ! empty( $o['twitter_author_names'] ) ) {
		$profiles[] = 'https://twitter.com/' . $o['twitter_author_names'];
	}
	$ours = array_map( static fn( $u ) => untrailingslashit( strtolower( $u ) ), sh_schema_profiles() );
	foreach ( $profiles as $u ) {
		$u = trim( $u );
		if ( '' !== $u ) {
			$in = in_array( untrailingslashit( strtolower( $u ) ), $ours, true );
			$rows[] = array( 'حساب رسمي', $u, $in ? $u : '', $in ? 'same' : 'move', 'الفوتر ← روابط التواصل في الفوتر' );
		}
	}
	return $rows;
}

/**
 * @return array{state:array,pages:array,company:array,rankmath:array,cases:array,ratings:string[]}
 *   pages rows: [label, url, core blocks, other blocks (text), company nodes, status]; status ok | missing | duplicate | invalid | error
 *   company rows: [label, printed, settings, status]; status ok | conflict | missing | review
 */
function sh_schema_check(): array {
	$out = array(
		'state'    => sh_schema_check_rankmath_state(),
		'pages'    => array(),
		'company'  => array(),
		'rankmath' => sh_schema_check_rankmath_values(),
		'cases'    => array(),
		'ratings'  => array(),
	);
	$org = null;
	foreach ( sh_schema_check_pages() as $label => $url ) {
		$res = sh_schema_check_fetch( $url );
		if ( isset( $res['error'] ) ) {
			$out['pages'][] = array( $label, $url, 0, $res['error'], 0, 'error' );
			continue;
		}
		$core   = array_values( array_filter( $res['blocks'], static fn( $b ) => str_contains( $b['class'], 'seohouse-schema' ) ) );
		$others = array_values( array_filter( $res['blocks'], static fn( $b ) => ! str_contains( $b['class'], 'seohouse-schema' ) ) );
		$nodes  = array_merge( ...array_map( static fn( $b ) => $b['nodes'], $res['blocks'] ?: array( array( 'nodes' => array() ) ) ) );
		$orgs   = array_filter( $nodes, 'sh_schema_check_is_company' );
		// another source describing what Core describes (a page, the company, an article…) is a duplicate
		$core_types = array();
		foreach ( $core as $b ) {
			foreach ( $b['nodes'] as $n ) {
				$core_types = array_merge( $core_types, (array) ( $n['@type'] ?? array() ) );
			}
		}
		$other_txt = array();
		$dup       = count( $core ) > 1 || count( $orgs ) > 1;
		foreach ( $others as $b ) {
			$t = array();
			foreach ( $b['nodes'] as $n ) {
				$t = array_merge( $t, (array) ( $n['@type'] ?? array() ) );
				if ( sh_schema_check_is_company( $n ) || array_intersect( (array) ( $n['@type'] ?? array() ), $core_types ) || array_intersect( (array) ( $n['@type'] ?? array() ), array( 'WebSite', 'WebPage', 'BreadcrumbList', 'Article', 'BlogPosting', 'Service', 'FAQPage', 'Person', 'ItemList' ) ) ) {
					$dup = true;
				}
			}
			$other_txt[] = ( $b['class'] ? $b['class'] : 'بلا class' ) . ': ' . implode( ', ', array_unique( $t ) );
		}
		$invalid = array_filter( $res['blocks'], static fn( $b ) => ! $b['valid'] );
		if ( 200 !== $res['status'] ) {
			$status = 'error';
		} elseif ( $invalid ) {
			$status = 'invalid';
		} elseif ( ! $core ) {
			$status = 'missing';
		} elseif ( $dup ) {
			$status = 'duplicate';
		} else {
			$status = 'ok';
		}
		$out['pages'][] = array( $label, $url, count( $core ), implode( ' | ', $other_txt ), count( $orgs ), $status );
		if ( 'الرئيسية' === $label && $core ) {
			foreach ( $core[0]['nodes'] as $n ) {
				if ( sh_schema_check_is_company( $n ) ) {
					$org = $n;
				}
			}
		}
		foreach ( $nodes as $n ) {
			if ( preg_match( '/"(AggregateRating|Review)"|"(aggregateRating|review)"\s*:/', (string) wp_json_encode( $n ) ) ) {
				$out['ratings'][] = $url;
				break;
			}
		}
	}

	// the company printed vs «إعدادات سيو هاوس»
	$settings = sh_schema_organization();
	$norm     = array(
		'telephone' => static fn( $v ) => preg_replace( '/\D+/', '', $v ),
		'email'     => static fn( $v ) => strtolower( $v ),
	);
	$labels   = array(
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
		$printed = $org ? sh_schema_check_text( $org[ $p ] ?? '' ) : '';
		$value   = sh_schema_check_text( $settings[ $p ] ?? '' );
		$f       = $norm[ $p ] ?? static fn( $v ) => $v;
		if ( ! $org || ( '' === $printed && '' === $value ) ) {
			$status = 'missing';
		} elseif ( $f( $printed ) === $f( $value ) ) {
			$status = 'ok';
		} else {
			$status = 'conflict';
		}
		$out['company'][] = array( $label, $printed, $value, $status );
	}
	if ( $org && ( isset( $org['aggregateRating'] ) || isset( $org['review'] ) ) ) {
		$out['ratings'][] = 'عقدة الشركة';
	}
	$out['ratings'] = array_values( array_unique( $out['ratings'] ) );

	// records that need a decision
	foreach ( (array) sh_core_option( 'sh_socials', array() ) as $s ) {
		$u   = trim( (string) ( $s['url'] ?? '' ) );
		$why = $u ? sh_schema_profile_problem( $u ) : '';
		if ( $why ) {
			$out['cases'][] = sprintf( 'رابط «%s» في «روابط التواصل في الفوتر» لا يُستخدم في sameAs (%s): %s', sh_core_plain( $s['label'] ?? '' ), $why, $u );
		}
	}
	foreach ( sh_schema_markets()['other'] as $name ) {
		$out['cases'][] = sprintf( '«%s» في «الأسواق المخدومة» ليس دولة معروفة (السعودية، مصر، الإمارات)، فلا يُطبع في areaServed.', $name );
	}
	if ( ! preg_match( '/^\d{4}$/', trim( (string) sh_core_option( 'sh_founded', '' ) ) ) ) {
		$out['cases'][] = '«سنة التأسيس» ليست سنة من 4 أرقام، فلا يُطبع foundingDate.';
	}
	$logo = sh_schema_logo();
	if ( $logo && ( (int) ( $logo['width'] ?? 0 ) < 112 || (int) ( $logo['height'] ?? 0 ) < 112 ) ) {
		$out['cases'][] = sprintf( 'الشعار في Schema %dx%d بكسل؛ Google يطلب 112x112 على الأقل. ارفع «الشعار على الخلفية الفاتحة» بمقاس أكبر يظهر على خلفية بيضاء.', (int) ( $logo['width'] ?? 0 ), (int) ( $logo['height'] ?? 0 ) );
	}
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

/** One line on where the structured data comes from. */
function sh_schema_check_source_text( array $state ): string {
	$rm = ! $state['installed'] ? 'Rank Math غير مفعّلة.' : ( $state['schema'] ? 'وحدة Schema في Rank Math تعمل، وإخراجها للبيانات المنظمة موقوف من Core دون تغيير إعداداتها.' : 'وحدة Schema في Rank Math متوقفة.' );
	return 'المصدر الوحيد للبيانات المنظمة: SEO House Core — مخطط واحد في <head> (class="seohouse-schema") من إعدادات سيو هاوس وحقول المحتوى. ' . $rm . ' العنوان والوصف وcanonical وrobots وOpen Graph وخرائط الموقع تبقى من Rank Math.';
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
	$state = sh_schema_check_rankmath_state();
	echo '<div class="wrap"><h1>' . esc_html__( 'فحص البيانات المنظمة', 'seohouse-core' ) . '</h1>';
	echo '<p style="max-width:62em"><strong>' . esc_html( sh_schema_check_source_text( $state ) ) . '</strong></p>';
	echo '<p style="max-width:62em">' . esc_html__( 'الفحص يقرأ عينة من الصفحات كما يراها الزائر: يتأكد من وجود مخطط Core مرة واحدة دون مصدر آخر مكرر، ويقارن بيانات الشركة المطبوعة بإعدادات سيو هاوس، ويعرض القيم المحفوظة في Rank Math فقط لتنقلها يدويًا إن كانت معتمدة، والحالات التي تحتاج قرارك. لا يغيّر أي قيمة.', 'seohouse-core' ) . '</p>';
	$check_url = wp_nonce_url( add_query_arg( 'sh_schema_check', 1, admin_url( 'admin.php?page=seohouse-schema' ) ), 'sh_schema_check' );
	if ( ! isset( $_GET['sh_schema_check'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ?? '' ) ), 'sh_schema_check' ) ) {
		echo '<p><a class="button button-primary" href="' . esc_url( $check_url ) . '">' . esc_html__( 'افحص الآن', 'seohouse-core' ) . '</a></p></div>';
		return;
	}
	$c   = sh_schema_check();
	$th  = static fn( array $cols ) => '<thead><tr>' . implode( '', array_map( static fn( $x ) => '<th>' . esc_html( $x ) . '</th>', $cols ) ) . '</tr></thead>';
	$td  = static fn( $x, bool $ltr = false ) => '<td' . ( $ltr ? ' dir="ltr" style="text-align:start"' : '' ) . '>' . esc_html( (string) $x ) . '</td>';
	$pst = array(
		'ok'        => 'سليم: مخطط Core مرة واحدة',
		'missing'   => 'لا توجد بيانات منظمة من Core',
		'duplicate' => 'تكرار: مصدر آخر يصف نفس الصفحة أو الشركة',
		'invalid'   => 'JSON غير صالح',
		'error'     => 'تعذرت قراءة الصفحة',
	);
	echo '<h2>' . esc_html__( 'مصدر الإخراج في الصفحات', 'seohouse-core' ) . '</h2><table class="widefat striped" style="max-width:90em">' . $th( array( 'الصفحة', 'الرابط', 'مخطط Core', 'بيانات منظمة أخرى', 'عقد الشركة', 'الحالة' ) ) . '<tbody>'; // phpcs:ignore WordPress.Security.EscapeOutput
	foreach ( $c['pages'] as $r ) {
		$bad = 'ok' !== $r[5];
		echo '<tr>' . $td( $r[0] ) . $td( $r[1], true ) . $td( $r[2] ) . $td( $r[3] ? $r[3] : '—', true ) . $td( $r[4] ) . '<td>' . ( $bad ? '<strong>' . esc_html( $pst[ $r[5] ] ) . '</strong>' : esc_html( $pst[ $r[5] ] ) ) . '</td></tr>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
	echo '</tbody></table>';

	$cst = array(
		'ok'       => 'مطابق',
		'conflict' => 'مختلف عن إعدادات سيو هاوس',
		'missing'  => 'غير مطبوع',
		'review'   => 'راجع',
	);
	echo '<h2>' . esc_html__( 'بيانات الشركة المطبوعة (الصفحة الرئيسية)', 'seohouse-core' ) . '</h2><table class="widefat striped" style="max-width:90em">' . $th( array( 'الخاصية', 'المطبوع في Schema', 'إعدادات سيو هاوس (الظاهر للزائر)', 'الحالة' ) ) . '<tbody>'; // phpcs:ignore WordPress.Security.EscapeOutput
	foreach ( $c['company'] as $r ) {
		echo '<tr>' . $td( $r[0] ) . $td( $r[1] ) . $td( $r[2] ) . '<td>' . ( 'conflict' === $r[3] ? '<strong>' . esc_html( $cst[ $r[3] ] ) . '</strong>' : esc_html( $cst[ $r[3] ] ) ) . '</td></tr>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
	echo '</tbody></table>';

	$rst = array(
		'move'    => 'في Rank Math فقط — انقله يدويًا إن كان معتمدًا',
		'differs' => 'مختلف — حدّد القيمة الصحيحة',
		'same'    => 'مطابق',
	);
	echo '<h2>' . esc_html__( 'قيم الشركة المحفوظة في Rank Math', 'seohouse-core' ) . '</h2><p class="description" style="max-width:62em">' . esc_html__( 'لا تُستخدم في البيانات المنظمة. تُعرض هنا فقط حتى لا تضيع قيمة معتمدة محفوظة في Rank Math وحده؛ لا يُنقل شيء تلقائيًا.', 'seohouse-core' ) . '</p>';
	if ( $c['rankmath'] ) {
		echo '<table class="widefat striped" style="max-width:90em">' . $th( array( 'القيمة', 'في Rank Math', 'في سيو هاوس', 'الحالة', 'مكانها في سيو هاوس' ) ) . '<tbody>'; // phpcs:ignore WordPress.Security.EscapeOutput
		foreach ( $c['rankmath'] as $r ) {
			echo '<tr>' . $td( $r[0] ) . $td( $r[1] ) . $td( $r[2] ? $r[2] : '—' ) . '<td>' . ( 'same' !== $r[3] ? '<strong>' . esc_html( $rst[ $r[3] ] ) . '</strong>' : esc_html( $rst[ $r[3] ] ) ) . '</td>' . $td( $r[4] ) . '</tr>'; // phpcs:ignore WordPress.Security.EscapeOutput
		}
		echo '</tbody></table>';
	} else {
		echo '<p>' . esc_html__( 'لا توجد قيم شركة محفوظة في Rank Math.', 'seohouse-core' ) . '</p>';
	}

	$cases = $c['cases'];
	foreach ( $c['ratings'] as $u ) {
		$cases[] = 'تقييمات (AggregateRating أو Review) في البيانات المنظمة: ' . $u . ' — أوقف خيار Schema/Rich snippet في إضافة التقييمات؛ التقييمات الظاهرة تبقى.';
	}
	echo '<h2>' . esc_html__( 'حالات تحتاج مراجعة', 'seohouse-core' ) . '</h2>';
	echo $cases ? '<ul style="list-style:disc;padding-inline-start:20px;max-width:80em">' . implode( '', array_map( static fn( $x ) => '<li>' . esc_html( $x ) . '</li>', $cases ) ) . '</ul>' : '<p>' . esc_html__( 'لا شيء.', 'seohouse-core' ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput
	echo '</div>';
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command(
		'seohouse-schema check',
		static function () {
			$c = sh_schema_check();
			WP_CLI::log( sh_schema_check_source_text( $c['state'] ) );
			foreach ( $c['pages'] as $r ) {
				WP_CLI::log( sprintf( 'page     %-9s core=%d companies=%d %s %s%s', $r[5], $r[2], $r[4], $r[0], $r[1], $r[3] ? ' | other: ' . $r[3] : '' ) );
			}
			foreach ( $c['company'] as $r ) {
				WP_CLI::log( sprintf( 'company  %-8s %s | printed: %s | settings: %s', $r[3], $r[0], $r[1], $r[2] ) );
			}
			foreach ( $c['rankmath'] as $r ) {
				WP_CLI::log( sprintf( 'rankmath %-8s %s | Rank Math: %s | SEO House: %s', $r[3], $r[0], $r[1], $r[2] ) );
			}
			foreach ( $c['ratings'] as $r ) {
				WP_CLI::log( 'rating   ' . $r );
			}
			foreach ( $c['cases'] as $r ) {
				WP_CLI::log( 'review   ' . $r );
			}
			$bad = count( array_filter( $c['pages'], static fn( $r ) => 'ok' !== $r[5] ) );
			WP_CLI::success( sprintf( '%d page(s) checked, %d with missing/duplicate/invalid schema, %d company value(s) differing, %d Rank Math value(s) to move or decide, %d case(s), %d page(s) with ratings', count( $c['pages'] ), $bad, count( array_filter( $c['company'], static fn( $r ) => 'conflict' === $r[3] ) ), count( array_filter( $c['rankmath'], static fn( $r ) => 'same' !== $r[3] ) ), count( $c['cases'] ), count( $c['ratings'] ) ) );
		}
	);
}
