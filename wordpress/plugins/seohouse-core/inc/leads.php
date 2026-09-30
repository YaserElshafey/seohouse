<?php
/**
 * Consultation requests.
 * - REST: POST /wp-json/seohouse/v1/lead (used by the booking form script).
 * - No-JS fallback: POST admin-post.php?action=sh_lead, then redirect back with ?sh_lead=ok|error#booking.
 * Server-side validation, honeypot + time trap + per-IP rate limit, duplicate suppression.
 * A request is "received" only after it is stored as a private sh_lead post; the email
 * notification result is recorded on the request.
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

const SH_LEAD_SERVICES = array(
	'seo'      => 'تحسين محركات البحث',
	'web'      => 'تصميم وتطوير موقع',
	'stores'   => 'تصميم متجر إلكتروني',
	'products' => 'إضافة المنتجات',
	'unsure'   => 'لم أحدد بعد',
);

function sh_lead_services(): array {
	$opt = sh_core_option( 'sh_booking', array() );
	$out = array();
	foreach ( (array) ( $opt['services'] ?? array() ) as $s ) {
		if ( ! empty( $s['key'] ) && ! empty( $s['label'] ) ) {
			$out[ sanitize_key( $s['key'] ) ] = $s['label'];
		}
	}
	return $out ? $out : SH_LEAD_SERVICES;
}

/**
 * Validate and store a request.
 *
 * @param array $in Raw input.
 * @return array{ok:bool,code:string,message:string,id?:int}
 */
function sh_lead_handle( array $in ): array {
	$fail = static fn( $code, $msg ) => array( 'ok' => false, 'code' => $code, 'message' => $msg );

	// spam checks: honeypot and minimum fill time
	if ( ! empty( $in['company_website'] ) ) {
		return $fail( 'spam', __( 'تعذر إرسال الطلب.', 'seohouse-core' ) );
	}
	$ts = (int) ( $in['ts'] ?? 0 );
	if ( $ts && ( time() - $ts < 3 || time() - $ts > DAY_IN_SECONDS ) ) {
		return $fail( 'timing', __( 'انتهت صلاحية النموذج، حدّث الصفحة وحاول مرة أخرى.', 'seohouse-core' ) );
	}

	$services = sh_lead_services();
	$service  = sanitize_key( (string) ( $in['service'] ?? '' ) );
	$name     = sanitize_text_field( (string) ( $in['name'] ?? '' ) );
	$contact  = sanitize_text_field( (string) ( $in['contact'] ?? '' ) );
	$site     = esc_url_raw( trim( (string) ( $in['site'] ?? '' ) ) );
	$page_id  = absint( $in['page_id'] ?? 0 );
	$source   = sanitize_key( (string) ( $in['source'] ?? 'booking' ) );

	if ( ! isset( $services[ $service ] ) ) {
		return $fail( 'service', __( 'اختر الخدمة المطلوبة.', 'seohouse-core' ) );
	}
	if ( '' === $name || mb_strlen( $name ) > 120 ) {
		return $fail( 'name', __( 'اكتب اسمك.', 'seohouse-core' ) );
	}
	$is_email = (bool) is_email( $contact );
	$is_phone = (bool) preg_match( '/^\+?[0-9\s-]{8,20}$/', $contact );
	if ( ! $is_email && ! $is_phone ) {
		return $fail( 'contact', __( 'اكتب رقم جوال أو بريدًا إلكترونيًا صحيحًا.', 'seohouse-core' ) );
	}
	if ( ! empty( $in['site'] ) && ! $site ) {
		return $fail( 'site', __( 'رابط الموقع غير صحيح.', 'seohouse-core' ) );
	}

	// rate limit: 5 requests / 10 minutes per IP (hashed, not stored in clear)
	$ip   = (string) ( $_SERVER['REMOTE_ADDR'] ?? '' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	$bkey = 'sh_lead_rl_' . substr( hash_hmac( 'sha256', $ip, wp_salt( 'nonce' ) ), 0, 20 );
	$hits = (int) get_transient( $bkey );
	if ( $hits >= 5 ) {
		return $fail( 'rate', __( 'استلمنا عدة طلبات من هذا الجهاز. حاول بعد قليل.', 'seohouse-core' ) );
	}
	set_transient( $bkey, $hits + 1, 10 * MINUTE_IN_SECONDS );

	// duplicate within 10 minutes → same request, no second notification
	$dup = get_posts(
		array(
			'post_type'      => 'sh_lead',
			'post_status'    => 'private',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'date_query'     => array( array( 'after' => '10 minutes ago' ) ),
			'meta_query'     => array(
				array( 'key' => '_sh_contact', 'value' => $contact ),
				array( 'key' => '_sh_service', 'value' => $service ),
			),
		)
	);
	if ( $dup ) {
		return array( 'ok' => true, 'code' => 'duplicate', 'message' => __( 'طلبك مسجل لدينا بالفعل.', 'seohouse-core' ), 'id' => (int) $dup[0] );
	}

	$id = wp_insert_post(
		array(
			'post_type'   => 'sh_lead',
			'post_status' => 'private',
			'post_title'  => sprintf( '%s — %s', $name, $services[ $service ] ),
			'meta_input'  => array(
				'_sh_name'    => $name,
				'_sh_contact' => $contact,
				'_sh_site'    => $site,
				'_sh_service' => $service,
				'_sh_page'    => $page_id,
				'_sh_source'  => $source,
				'_sh_status'  => 'new',
			),
		),
		true
	);
	if ( is_wp_error( $id ) || ! $id ) {
		return $fail( 'store', __( 'تعذر حفظ الطلب الآن. حاول مرة أخرى أو تواصل معنا عبر صفحة التواصل.', 'seohouse-core' ) );
	}

	$to = array_filter( array_map( 'trim', explode( ',', (string) sh_core_option( 'sh_lead_to', '' ) ) ), 'is_email' );
	if ( ! $to ) {
		$to = array( get_option( 'admin_email' ) );
	}
	$body  = "طلب استشارة جديد\n\n";
	$body .= "الاسم: {$name}\nالتواصل: {$contact}\nالخدمة: {$services[ $service ]}\n";
	$body .= 'الموقع: ' . ( $site ? $site : '—' ) . "\n";
	$body .= 'الصفحة: ' . ( $page_id ? get_permalink( $page_id ) : '—' ) . "\n";
	$body .= 'في لوحة التحكم: ' . admin_url( 'post.php?post=' . $id . '&action=edit' ) . "\n";
	$headers = $is_email ? array( 'Reply-To: ' . $contact ) : array();
	$sent    = wp_mail( $to, '[' . wp_specialchars_decode( get_bloginfo( 'name' ) ) . '] طلب استشارة: ' . $services[ $service ], $body, $headers );
	update_post_meta( $id, '_sh_mail', $sent ? 'sent' : 'failed' );

	do_action( 'sh_lead_stored', $id, compact( 'name', 'contact', 'site', 'service', 'page_id', 'source' ) );

	return array( 'ok' => true, 'code' => 'stored', 'message' => __( 'وصلنا طلبك.', 'seohouse-core' ), 'id' => (int) $id );
}

add_action(
	'rest_api_init',
	static function () {
		register_rest_route(
			'seohouse/v1',
			'/lead',
			array(
				'methods'             => 'POST',
				'permission_callback' => '__return_true', // public form; protected by honeypot, timing and rate limit.
				'callback'            => static function ( WP_REST_Request $r ) {
					$res = sh_lead_handle( $r->get_params() );
					unset( $res['id'] );
					return new WP_REST_Response( $res, $res['ok'] ? 200 : 422 );
				},
			)
		);
	}
);

/** No-JS fallback. */
$sh_lead_post = static function () {
	$res  = sh_lead_handle( wp_unslash( $_POST ) ); // phpcs:ignore WordPress.Security.NonceVerification -- public form, see spam checks.
	$back = wp_get_referer() ? wp_get_referer() : home_url( '/' );
	$back = remove_query_arg( 'sh_lead', $back );
	wp_safe_redirect( add_query_arg( 'sh_lead', $res['ok'] ? 'ok' : 'error', strtok( $back, '#' ) ) . '#booking' );
	exit;
};
add_action( 'admin_post_nopriv_sh_lead', $sh_lead_post );
add_action( 'admin_post_sh_lead', $sh_lead_post );

/* ------------------------------------------------------------------ admin list */
add_filter(
	'manage_sh_lead_posts_columns',
	static fn( $c ) => array(
		'cb'         => $c['cb'],
		'title'      => __( 'الطلب', 'seohouse-core' ),
		'sh_contact' => __( 'التواصل', 'seohouse-core' ),
		'sh_site'    => __( 'الموقع', 'seohouse-core' ),
		'sh_page'    => __( 'الصفحة', 'seohouse-core' ),
		'sh_mail'    => __( 'الإشعار', 'seohouse-core' ),
		'date'       => $c['date'],
	)
);
add_action(
	'manage_sh_lead_posts_custom_column',
	static function ( $col, $id ) {
		switch ( $col ) {
			case 'sh_contact':
				echo esc_html( get_post_meta( $id, '_sh_contact', true ) );
				break;
			case 'sh_site':
				$s = get_post_meta( $id, '_sh_site', true );
				echo $s ? '<a href="' . esc_url( $s ) . '" target="_blank" rel="noopener">' . esc_html( $s ) . '</a>' : '—';
				break;
			case 'sh_page':
				$p = (int) get_post_meta( $id, '_sh_page', true );
				echo $p ? esc_html( get_the_title( $p ) ) : '—';
				break;
			case 'sh_mail':
				echo 'sent' === get_post_meta( $id, '_sh_mail', true ) ? esc_html__( 'أُرسل', 'seohouse-core' ) : '<strong style="color:#b32d2e">' . esc_html__( 'فشل الإرسال — راجع إعدادات البريد', 'seohouse-core' ) . '</strong>';
				break;
		}
	},
	10,
	2
);
add_action(
	'add_meta_boxes_sh_lead',
	static function () {
		add_meta_box(
			'sh_lead_data',
			__( 'بيانات الطلب', 'seohouse-core' ),
			static function ( $post ) {
				$rows = array(
					'الاسم'   => get_post_meta( $post->ID, '_sh_name', true ),
					'التواصل' => get_post_meta( $post->ID, '_sh_contact', true ),
					'الخدمة'  => sh_lead_services()[ get_post_meta( $post->ID, '_sh_service', true ) ] ?? '',
					'الموقع'  => get_post_meta( $post->ID, '_sh_site', true ),
					'الصفحة'  => ( $p = (int) get_post_meta( $post->ID, '_sh_page', true ) ) ? get_permalink( $p ) : '',
					'الإشعار' => get_post_meta( $post->ID, '_sh_mail', true ),
				);
				echo '<table class="widefat striped"><tbody>';
				foreach ( $rows as $k => $v ) {
					echo '<tr><th style="width:120px">' . esc_html( $k ) . '</th><td>' . esc_html( (string) $v ) . '</td></tr>';
				}
				echo '</tbody></table>';
			},
			'sh_lead',
			'normal',
			'high'
		);
	}
);
