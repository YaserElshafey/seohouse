<?php
/**
 * Consultation requests.
 * - REST: POST /wp-json/seohouse/v1/lead (used by the booking form script).
 * - No-JS fallback: POST admin-post.php?action=sh_lead, then redirect back with ?sh_lead=ok|error#booking.
 * Server-side validation, honeypot + time trap + per-IP rate limit, duplicate suppression.
 * A request is "received" only after it is stored as a private sh_lead post; the email
 * notification result (and the mailer's error) is recorded on the request and can be retried
 * from the requests list.
 *
 * 2.7.0
 * - Time trap measured in the browser (`elapsed`, ms since the form was shown). The hidden `ts`
 *   printed in the page is only used without JavaScript and has no upper limit: a page served
 *   from a page cache for days must still accept requests (the home page failed this way).
 * - Repeated clicks / retries of the same fill carry the same `sid` and are stored once.
 *   The content duplicate check is per form source, so a contact-page request no longer hides a
 *   home-page request with the same contact and service.
 * - Source recorded and shown: «نموذج الاستشارة — <page>» or «نموذج اتصل بنا».
 * - Notification recipient: «بريد استلام الطلبات», default info@seohouse.agency (not the site's
 *   admin email).
 *
 * 2.7.1
 * - Booking form: separate required email and phone (international format with the country code,
 *   normalised to +<digits>; no country assumed). A page cached before the update still sends the
 *   single «contact» field and is accepted as before, so no request is lost.
 * - Every request gets a random reference (_sh_ref) returned to the browser. The scheduler step
 *   reports a booking with it: POST /lead/booking { ref, event, invitee }. The request is never
 *   stored twice; it becomes «موعد مؤكد» only after the booking is confirmed by Calendly's API
 *   («رمز Calendly API»). Without the token, or when Calendly can't be reached, it stays
 *   «طلب استشارة» with the booking recorded as unverified (re-check from the requests list).
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

/** Default recipient when «بريد استلام الطلبات» is empty. */
const SH_LEAD_DEFAULT_TO = 'info@seohouse.agency';

/** Notification recipients: «بريد استلام الطلبات» (comma separated), else info@seohouse.agency. */
function sh_lead_recipients(): array {
	$to = array_values( array_filter( array_map( 'trim', explode( ',', (string) sh_core_option( 'sh_lead_to', '' ) ) ), 'is_email' ) );
	return $to ? $to : array( SH_LEAD_DEFAULT_TO );
}

/**
 * Phone number in international format: +<country code><number>, 7–15 digits (E.164).
 * Accepts spaces, dashes, brackets, a leading 00 and Arabic-Indic digits; no country is assumed.
 * Returns '' when the number has no country code or is not a phone number.
 */
function sh_lead_phone( string $raw ): string {
	$raw = strtr( $raw, array_combine( array( '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩', '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' ), array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' ) ) );
	$raw = preg_replace( '/[\s().\-\x{200E}\x{200F}\x{202A}-\x{202E}]/u', '', trim( $raw ) );
	if ( str_starts_with( $raw, '00' ) ) {
		$raw = '+' . substr( $raw, 2 );
	}
	return preg_match( '/^\+[1-9][0-9]{6,14}$/', $raw ) ? $raw : '';
}

/** Request state: «طلب استشارة» until a booking is confirmed by the scheduler. */
function sh_lead_state_label( int $id ): string {
	if ( 'booked' === get_post_meta( $id, '_sh_status', true ) ) {
		return __( 'موعد مؤكد', 'seohouse-core' );
	}
	return 'unverified' === get_post_meta( $id, '_sh_booking_state', true ) ? __( 'طلب استشارة — حجز غير متحقق', 'seohouse-core' ) : __( 'طلب استشارة', 'seohouse-core' );
}

/** Booking time in the site's time zone, e.g. «2026-10-08 14:30 (Asia/Riyadh)». */
function sh_lead_booking_time( int $id ): string {
	$start = strtotime( (string) get_post_meta( $id, '_sh_booking_start', true ) );
	if ( ! $start ) {
		return '';
	}
	$end = strtotime( (string) get_post_meta( $id, '_sh_booking_end', true ) );
	$tz  = wp_timezone_string();
	return wp_date( 'Y-m-d H:i', $start ) . ( $end ? '–' . wp_date( 'H:i', $end ) : '' ) . ' (' . $tz . ')';
}

/** Human label of where a request came from. */
function sh_lead_source_label( int $id ): string {
	$page = (int) get_post_meta( $id, '_sh_page', true );
	if ( 'contact' === get_post_meta( $id, '_sh_source', true ) ) {
		return __( 'نموذج اتصل بنا', 'seohouse-core' );
	}
	$where = $page ? ( (int) get_option( 'page_on_front' ) === $page ? __( 'الرئيسية', 'seohouse-core' ) : get_the_title( $page ) ) : '';
	return $where ? sprintf( /* translators: %s: page */ __( 'نموذج الاستشارة — %s', 'seohouse-core' ), $where ) : __( 'نموذج الاستشارة', 'seohouse-core' );
}

/**
 * Sends (or re-sends) the notification of a stored request with the site's current mail settings.
 * Records the result on the request: _sh_mail sent|failed, _sh_mail_error, _sh_mail_to, _sh_mail_time.
 */
function sh_lead_notify( int $id ): bool {
	$m        = static fn( $k ) => (string) get_post_meta( $id, '_sh_' . $k, true );
	$services = sh_lead_services();
	$service  = $services[ $m( 'service' ) ] ?? $m( 'service' );
	$source   = sh_lead_source_label( $id );
	$page     = (int) $m( 'page' );
	$contact  = $m( 'contact' );
	$body     = "طلب استشارة جديد\n\n";
	$body    .= "المصدر: {$source}\n";
	$body    .= 'الحالة: ' . sh_lead_state_label( $id ) . "\n";
	$body    .= 'الاسم: ' . $m( 'name' ) . "\n" . ( '' !== $m( 'email' ) ? '' : "التواصل: {$contact}\n" ) . "الخدمة: {$service}\n";
	foreach ( array( 'email' => 'البريد', 'phone' => 'الهاتف', 'company' => 'الشركة', 'market' => 'السوق', 'goal' => 'الهدف' ) as $k => $label ) {
		if ( '' !== $m( $k ) ) {
			$body .= "{$label}: " . $m( $k ) . "\n";
		}
	}
	$body .= 'الموقع: ' . ( $m( 'site' ) ? $m( 'site' ) : '—' ) . "\n";
	$body .= 'الصفحة: ' . ( $page ? get_permalink( $page ) : '—' ) . "\n";
	$body .= 'التاريخ: ' . get_the_date( 'Y-m-d H:i', $id ) . "\n";
	$body .= 'في لوحة التحكم: ' . admin_url( 'post.php?post=' . $id . '&action=edit' ) . "\n";
	$headers = is_email( $contact ) ? array( 'Reply-To: ' . $contact ) : array();
	$to      = sh_lead_recipients();

	$error   = '';
	$catch   = static function ( $e ) use ( &$error ) {
		$error = is_wp_error( $e ) ? $e->get_error_message() : 'wp_mail';
	};
	add_action( 'wp_mail_failed', $catch );
	$sent = (bool) wp_mail( $to, '[' . wp_specialchars_decode( get_bloginfo( 'name' ) ) . '] طلب استشارة (' . $source . '): ' . $service, $body, $headers );
	remove_action( 'wp_mail_failed', $catch );

	update_post_meta( $id, '_sh_mail', $sent ? 'sent' : 'failed' );
	update_post_meta( $id, '_sh_mail_error', $sent ? '' : ( $error ? $error : __( 'لم يقبل خادم البريد الرسالة.', 'seohouse-core' ) ) );
	update_post_meta( $id, '_sh_mail_to', implode( ', ', $to ) );
	update_post_meta( $id, '_sh_mail_time', current_time( 'mysql' ) );
	return $sent;
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
	if ( isset( $in['elapsed'] ) && '' !== (string) $in['elapsed'] ) {
		// measured in the browser: time from showing the form to sending it
		if ( (int) $in['elapsed'] < 2500 ) {
			return $fail( 'timing', __( 'تعذر إرسال الطلب. انتظر لحظة ثم حاول مرة أخرى.', 'seohouse-core' ) );
		}
	} else {
		// without JavaScript: the time printed in the page; no upper limit (cached pages)
		$ts = (int) ( $in['ts'] ?? 0 );
		if ( $ts && time() - $ts < 3 ) {
			return $fail( 'timing', __( 'تعذر إرسال الطلب. انتظر لحظة ثم حاول مرة أخرى.', 'seohouse-core' ) );
		}
	}

	$services = sh_lead_services();
	$service  = sanitize_key( (string) ( $in['service'] ?? '' ) );
	$name     = sanitize_text_field( (string) ( $in['name'] ?? '' ) );
	$contact  = sanitize_text_field( (string) ( $in['contact'] ?? '' ) );
	$site     = esc_url_raw( trim( (string) ( $in['site'] ?? '' ) ) );
	$page_id  = absint( $in['page_id'] ?? 0 );
	$source   = sanitize_key( (string) ( $in['source'] ?? 'booking' ) );
	$source   = in_array( $source, array( 'booking', 'contact' ), true ) ? $source : 'booking';
	$sid      = preg_replace( '/[^a-zA-Z0-9-]/', '', substr( (string) ( $in['sid'] ?? '' ), 0, 64 ) );

	// contact page form: name, company, email, phone, site, market, service, goal
	$extra = array();
	if ( 'contact' === $source ) {
		$extra = array(
			'company' => sanitize_text_field( (string) ( $in['company'] ?? '' ) ),
			'email'   => sanitize_email( (string) ( $in['email'] ?? '' ) ),
			'phone'   => sanitize_text_field( (string) ( $in['phone'] ?? '' ) ),
			'market'  => sanitize_text_field( (string) ( $in['market'] ?? '' ) ),
			'goal'    => sanitize_textarea_field( (string) ( $in['goal'] ?? '' ) ),
		);
		if ( '' === $name || '' === $extra['company'] || mb_strlen( $name ) > 120 || mb_strlen( $extra['company'] ) > 160 ) {
			return $fail( 'name', __( 'أدخل الاسم واسم الشركة للمتابعة.', 'seohouse-core' ) );
		}
		if ( ! is_email( $extra['email'] ) ) {
			return $fail( 'email', __( 'أدخل بريدًا إلكترونيًا صحيحًا.', 'seohouse-core' ) );
		}
		if ( ! preg_match( '/^\+?[0-9\s()-]{7,20}$/', $extra['phone'] ) ) {
			return $fail( 'phone', __( 'أدخل رقم الهاتف أو واتساب.', 'seohouse-core' ) );
		}
		if ( '' === trim( $extra['goal'] ) || mb_strlen( $extra['goal'] ) > 3000 ) {
			return $fail( 'goal', __( 'اكتب الهدف أو التحدي الأساسي.', 'seohouse-core' ) );
		}
		$contact = $extra['email'];
	} elseif ( isset( $in['email'] ) || isset( $in['phone'] ) ) {
		// booking form (2.7.1): email and phone, both required
		$extra = array(
			'email' => sanitize_email( (string) ( $in['email'] ?? '' ) ),
			'phone' => sh_lead_phone( sanitize_text_field( (string) ( $in['phone'] ?? '' ) ) ),
		);
		if ( ! is_email( $extra['email'] ) ) {
			return $fail( 'email', __( 'أدخل بريدًا إلكترونيًا صحيحًا.', 'seohouse-core' ) );
		}
		if ( '' === $extra['phone'] ) {
			return $fail( 'phone', __( 'أدخل رقم الهاتف مع رمز الدولة، يبدأ بـ + أو 00.', 'seohouse-core' ) );
		}
		$contact = $extra['email'];
	}

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

	// the same fill sent again (double click, retry after a network error): already stored
	if ( '' !== $sid ) {
		$same = get_posts( array( 'post_type' => 'sh_lead', 'post_status' => 'private', 'posts_per_page' => 1, 'fields' => 'ids', 'meta_key' => '_sh_sid', 'meta_value' => $sid ) ); // phpcs:ignore WordPress.DB.SlowDBQuery
		if ( $same ) {
			return array( 'ok' => true, 'code' => 'duplicate', 'message' => __( 'طلبك مسجل لدينا بالفعل.', 'seohouse-core' ), 'id' => (int) $same[0], 'ref' => (string) get_post_meta( (int) $same[0], '_sh_ref', true ) );
		}
	}
	// the same request from the same form within 10 minutes (e.g. sent again after a reload)
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
				array( 'key' => '_sh_source', 'value' => $source ),
				array( 'key' => '_sh_page', 'value' => (string) $page_id ),
			),
		)
	);
	if ( $dup ) {
		return array( 'ok' => true, 'code' => 'duplicate', 'message' => __( 'طلبك مسجل لدينا بالفعل.', 'seohouse-core' ), 'id' => (int) $dup[0], 'ref' => (string) get_post_meta( (int) $dup[0], '_sh_ref', true ) );
	}

	// rate limit: 5 new requests / 10 minutes per IP (hashed, not stored in clear)
	$ip   = (string) ( $_SERVER['REMOTE_ADDR'] ?? '' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	$bkey = 'sh_lead_rl_' . substr( hash_hmac( 'sha256', $ip, wp_salt( 'nonce' ) ), 0, 20 );
	$hits = (int) get_transient( $bkey );
	if ( $hits >= 5 ) {
		return $fail( 'rate', __( 'استلمنا عدة طلبات من هذا الجهاز. حاول بعد قليل.', 'seohouse-core' ) );
	}

	$ref = bin2hex( random_bytes( 12 ) );
	$id  = wp_insert_post(
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
				'_sh_sid'     => $sid,
				'_sh_status'  => 'new',
				'_sh_ref'     => $ref,
			) + array_combine( array_map( static fn( $k ) => '_sh_' . $k, array_keys( $extra ) ), array_values( $extra ) ),
		),
		true
	);
	if ( is_wp_error( $id ) || ! $id ) {
		return $fail( 'store', __( 'تعذر حفظ الطلب الآن. حاول مرة أخرى أو تواصل معنا عبر صفحة التواصل.', 'seohouse-core' ) );
	}

	set_transient( $bkey, $hits + 1, 10 * MINUTE_IN_SECONDS );
	sh_lead_notify( (int) $id );

	do_action( 'sh_lead_stored', $id, compact( 'name', 'contact', 'site', 'service', 'page_id', 'source' ) );

	return array( 'ok' => true, 'code' => 'stored', 'message' => __( 'وصلنا طلبك.', 'seohouse-core' ), 'id' => (int) $id, 'ref' => $ref );
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
		register_rest_route(
			'seohouse/v1',
			'/lead/booking',
			array(
				'methods'             => 'POST',
				'permission_callback' => '__return_true', // the random request reference is the key; Calendly's API confirms the booking.
				'callback'            => static function ( WP_REST_Request $r ) {
					$res = sh_lead_booking_report( $r->get_params() );
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
		'sh_state'   => __( 'الحالة', 'seohouse-core' ),
		'sh_source'  => __( 'المصدر', 'seohouse-core' ),
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
			case 'sh_state':
				echo sh_lead_state_html( (int) $id ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
				break;
			case 'sh_contact':
				$email = (string) get_post_meta( $id, '_sh_email', true );
				$phone = (string) get_post_meta( $id, '_sh_phone', true );
				echo $email || $phone ? esc_html( $email ) . ( $phone ? '<br><span dir="ltr">' . esc_html( $phone ) . '</span>' : '' ) : esc_html( get_post_meta( $id, '_sh_contact', true ) );
				break;
			case 'sh_site':
				$s = get_post_meta( $id, '_sh_site', true );
				echo $s ? '<a href="' . esc_url( $s ) . '" target="_blank" rel="noopener">' . esc_html( $s ) . '</a>' : '—';
				break;
			case 'sh_page':
				$p = (int) get_post_meta( $id, '_sh_page', true );
				echo $p ? esc_html( get_the_title( $p ) ) : '—';
				break;
			case 'sh_source':
				echo esc_html( sh_lead_source_label( (int) $id ) );
				break;
			case 'sh_mail':
				echo sh_lead_mail_status_html( (int) $id ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
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
					'الحالة'  => sh_lead_state_label( (int) $post->ID ),
					'الموعد'  => sh_lead_booking_time( (int) $post->ID ),
					'المصدر'  => sh_lead_source_label( (int) $post->ID ),
					'الاسم'   => get_post_meta( $post->ID, '_sh_name', true ),
					'البريد'  => get_post_meta( $post->ID, '_sh_email', true ),
					'التواصل' => get_post_meta( $post->ID, '_sh_email', true ) ? '' : get_post_meta( $post->ID, '_sh_contact', true ),
					'الخدمة'  => sh_lead_services()[ get_post_meta( $post->ID, '_sh_service', true ) ] ?? '',
					'الموقع'  => get_post_meta( $post->ID, '_sh_site', true ),
					'الشركة'  => get_post_meta( $post->ID, '_sh_company', true ),
					'الهاتف'  => get_post_meta( $post->ID, '_sh_phone', true ),
					'السوق'   => get_post_meta( $post->ID, '_sh_market', true ),
					'الهدف'   => get_post_meta( $post->ID, '_sh_goal', true ),
					'الصفحة'  => ( $p = (int) get_post_meta( $post->ID, '_sh_page', true ) ) ? get_permalink( $p ) : '',
				);
				echo '<table class="widefat striped"><tbody>';
				foreach ( array_filter( $rows, static fn( $v ) => '' !== (string) $v ) as $k => $v ) {
					echo '<tr><th style="width:120px">' . esc_html( $k ) . '</th><td>' . esc_html( (string) $v ) . '</td></tr>';
				}
				echo '</tbody></table>';
				echo '<p><strong>' . esc_html__( 'الإشعار بالبريد:', 'seohouse-core' ) . '</strong> ' . sh_lead_mail_status_html( (int) $post->ID ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
			},
			'sh_lead',
			'normal',
			'high'
		);
	}
);

/* ------------------------------------------------------------------ notification status + retry */

/** Status of the email notification, with the reason and a resend link when it failed. */
function sh_lead_mail_status_html( int $id ): string {
	$state = (string) get_post_meta( $id, '_sh_mail', true );
	$to    = (string) get_post_meta( $id, '_sh_mail_to', true );
	$when  = (string) get_post_meta( $id, '_sh_mail_time', true );
	$retry = current_user_can( 'edit_post', $id )
		? '<br><a href="' . esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=sh_lead_resend&lead=' . $id ), 'sh_lead_resend_' . $id ) ) . '">' . esc_html__( 'إعادة الإرسال', 'seohouse-core' ) . '</a>'
		: '';
	if ( 'sent' === $state ) {
		return esc_html__( 'أُرسل', 'seohouse-core' ) . ( $to ? '<br><small dir="ltr">' . esc_html( $to ) . '</small>' : '' ) . ( $when ? '<br><small>' . esc_html( $when ) . '</small>' : '' ) . $retry;
	}
	$err = (string) get_post_meta( $id, '_sh_mail_error', true );
	return '<strong style="color:#b32d2e">' . esc_html__( 'فشل الإرسال', 'seohouse-core' ) . '</strong>'
		. ( $err ? '<br><small>' . esc_html( $err ) . '</small>' : '' )
		. ( $to ? '<br><small dir="ltr">' . esc_html( $to ) . '</small>' : '' )
		. '<br><small>' . esc_html__( 'الطلب محفوظ. راجع إعدادات البريد (SMTP) ثم أعد الإرسال.', 'seohouse-core' ) . '</small>'
		. $retry;
}

add_action(
	'admin_post_sh_lead_resend',
	static function () {
		$id = absint( $_GET['lead'] ?? 0 ); // phpcs:ignore WordPress.Security.NonceVerification -- verified below
		check_admin_referer( 'sh_lead_resend_' . $id );
		if ( ! $id || 'sh_lead' !== get_post_type( $id ) || ! current_user_can( 'edit_post', $id ) ) {
			wp_die( esc_html__( 'غير مسموح.', 'seohouse-core' ) );
		}
		$ok   = sh_lead_notify( $id );
		$back = wp_get_referer() ? wp_get_referer() : admin_url( 'edit.php?post_type=sh_lead' );
		wp_safe_redirect( add_query_arg( 'sh_resent', $ok ? 'ok' : 'failed', remove_query_arg( 'sh_resent', $back ) ) );
		exit;
	}
);

add_action(
	'admin_notices',
	static function () {
		$r = sanitize_key( $_GET['sh_resent'] ?? '' ); // phpcs:ignore WordPress.Security.NonceVerification -- display only
		if ( 'ok' === $r ) {
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'أُعيد إرسال إشعار الطلب.', 'seohouse-core' ) . '</p></div>';
		} elseif ( 'failed' === $r ) {
			echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'تعذر إرسال الإشعار مرة أخرى. الطلب ما زال محفوظًا. راجع إعدادات البريد (SMTP) على الاستضافة.', 'seohouse-core' ) . '</p></div>';
		}
	}
);

/* ------------------------------------------------------------------ scheduler step (Calendly) */

/** GET a Calendly API v2 resource with the saved token. Only api.calendly.com URLs are requested. */
function sh_lead_calendly_get( string $uri, string $token ) {
	if ( ! preg_match( '#^https://api\.calendly\.com/scheduled_events/[A-Za-z0-9-]+(?:/invitees/[A-Za-z0-9-]+)?$#', $uri ) ) {
		return new WP_Error( 'sh_uri', __( 'رابط حجز غير صالح.', 'seohouse-core' ) );
	}
	$r = wp_remote_get( $uri, array( 'timeout' => 12, 'headers' => array( 'Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json' ) ) );
	if ( is_wp_error( $r ) ) {
		return $r;
	}
	$code = (int) wp_remote_retrieve_response_code( $r );
	$json = json_decode( (string) wp_remote_retrieve_body( $r ), true );
	if ( 200 !== $code || empty( $json['resource'] ) ) {
		/* translators: %d: HTTP status */
		return new WP_Error( 'sh_api', sprintf( __( 'ردّ Calendly برمز %d (راجع رمز API).', 'seohouse-core' ), $code ) );
	}
	return $json['resource'];
}

/**
 * Confirms a reported booking with Calendly and, only then, marks the request «موعد مؤكد».
 * The invitee must be active and carry this request's reference (utm_content) or its email.
 *
 * @return array{ok:bool,confirmed:bool,code:string,message:string,start?:string,end?:string}
 */
function sh_lead_booking_verify( int $id ): array {
	$invitee = (string) get_post_meta( $id, '_sh_booking_invitee', true );
	$token   = trim( (string) sh_core_option( 'sh_booking_token', '' ) );
	$pending = static function ( string $code, string $why ) use ( $id ) {
		update_post_meta( $id, '_sh_booking_state', 'unverified' );
		update_post_meta( $id, '_sh_booking_error', $why );
		return array( 'ok' => true, 'confirmed' => false, 'code' => $code, 'message' => $why );
	};
	if ( '' === $token ) {
		return $pending( 'unverified', __( 'لم يُضف رمز Calendly API، فلم يُتحقق من الحجز.', 'seohouse-core' ) );
	}
	$inv = sh_lead_calendly_get( $invitee, $token );
	if ( is_wp_error( $inv ) ) {
		return $pending( 'verify_failed', $inv->get_error_message() );
	}
	$ref   = (string) get_post_meta( $id, '_sh_ref', true );
	$email = strtolower( (string) get_post_meta( $id, '_sh_email', true ) );
	$mine  = ( '' !== $ref && ( $inv['tracking']['utm_content'] ?? '' ) === $ref ) || ( '' !== $email && strtolower( (string) ( $inv['email'] ?? '' ) ) === $email );
	if ( ! $mine ) {
		return array( 'ok' => false, 'confirmed' => false, 'code' => 'mismatch', 'message' => __( 'الحجز لا يخص هذا الطلب.', 'seohouse-core' ) );
	}
	if ( 'active' !== ( $inv['status'] ?? '' ) ) {
		return $pending( 'canceled', __( 'الحجز ملغى في Calendly.', 'seohouse-core' ) );
	}
	$event = sh_lead_calendly_get( (string) ( $inv['event'] ?? '' ), $token );
	if ( is_wp_error( $event ) ) {
		return $pending( 'verify_failed', $event->get_error_message() );
	}
	if ( 'active' !== ( $event['status'] ?? '' ) || empty( $event['start_time'] ) ) {
		return $pending( 'canceled', __( 'الموعد غير نشط في Calendly.', 'seohouse-core' ) );
	}
	$first = 'booked' !== get_post_meta( $id, '_sh_status', true );
	update_post_meta( $id, '_sh_status', 'booked' );
	update_post_meta( $id, '_sh_booking_state', 'confirmed' );
	update_post_meta( $id, '_sh_booking_error', '' );
	update_post_meta( $id, '_sh_booking_event', (string) $event['uri'] );
	update_post_meta( $id, '_sh_booking_start', (string) $event['start_time'] );
	update_post_meta( $id, '_sh_booking_end', (string) ( $event['end_time'] ?? '' ) );
	update_post_meta( $id, '_sh_booking_tz', sanitize_text_field( (string) ( $inv['timezone'] ?? '' ) ) );
	update_post_meta( $id, '_sh_booking_confirmed', current_time( 'mysql' ) );
	if ( $first ) {
		sh_lead_notify_booking( $id );
	}
	return array(
		'ok'        => true,
		'confirmed' => true,
		'code'      => 'confirmed',
		'message'   => __( 'تم تأكيد الموعد.', 'seohouse-core' ),
		'start'     => (string) $event['start_time'],
		'end'       => (string) ( $event['end_time'] ?? '' ),
	);
}

/**
 * The scheduler step reports a booking: { ref, invitee } (Calendly's event_scheduled message).
 * Attaches it to the stored request — never creates a request — and verifies it with Calendly.
 */
function sh_lead_booking_report( array $in ): array {
	$fail = static fn( $code, $msg ) => array( 'ok' => false, 'confirmed' => false, 'code' => $code, 'message' => $msg );
	$ref  = preg_replace( '/[^a-f0-9]/', '', strtolower( (string) ( $in['ref'] ?? '' ) ) );
	$inv  = trim( (string) ( $in['invitee'] ?? '' ) );
	if ( 24 !== strlen( $ref ) || 'calendly' !== sh_core_option( 'sh_booking_provider', 'none' ) ) {
		return $fail( 'ref', __( 'تعذر ربط الحجز بالطلب.', 'seohouse-core' ) );
	}
	if ( ! preg_match( '#^https://api\.calendly\.com/scheduled_events/[A-Za-z0-9-]+/invitees/[A-Za-z0-9-]+$#', $inv ) ) {
		return $fail( 'invitee', __( 'تعذر ربط الحجز بالطلب.', 'seohouse-core' ) );
	}
	$ids = get_posts( array( 'post_type' => 'sh_lead', 'post_status' => 'private', 'posts_per_page' => 1, 'fields' => 'ids', 'meta_key' => '_sh_ref', 'meta_value' => $ref ) ); // phpcs:ignore WordPress.DB.SlowDBQuery
	if ( ! $ids ) {
		return $fail( 'ref', __( 'تعذر ربط الحجز بالطلب.', 'seohouse-core' ) );
	}
	$id = (int) $ids[0];
	// the same booking reported again (reload, second message): answer from what is stored
	if ( get_post_meta( $id, '_sh_booking_invitee', true ) === $inv && 'booked' === get_post_meta( $id, '_sh_status', true ) ) {
		return array( 'ok' => true, 'confirmed' => true, 'code' => 'confirmed', 'message' => __( 'تم تأكيد الموعد.', 'seohouse-core' ), 'start' => (string) get_post_meta( $id, '_sh_booking_start', true ), 'end' => (string) get_post_meta( $id, '_sh_booking_end', true ) );
	}
	$key  = 'sh_lead_bk_' . $id;
	$hits = (int) get_transient( $key );
	if ( $hits >= 10 ) {
		return $fail( 'rate', __( 'حاول بعد قليل.', 'seohouse-core' ) );
	}
	set_transient( $key, $hits + 1, HOUR_IN_SECONDS );
	update_post_meta( $id, '_sh_booking_invitee', $inv );
	return sh_lead_booking_verify( $id );
}

/** Email: a booking was confirmed for a stored request (same recipients and mail settings). */
function sh_lead_notify_booking( int $id ): bool {
	$m    = static fn( $k ) => (string) get_post_meta( $id, '_sh_' . $k, true );
	$when = sh_lead_booking_time( $id );
	$body = "موعد مؤكد لطلب استشارة\n\n";
	$body .= 'المصدر: ' . sh_lead_source_label( $id ) . "\n";
	$body .= 'الموعد: ' . $when . "\n";
	$body .= 'بتوقيت العميل: ' . ( $m( 'booking_tz' ) ? $m( 'booking_tz' ) : '—' ) . "\n";
	$body .= 'الاسم: ' . $m( 'name' ) . "\nالبريد: " . $m( 'email' ) . "\nالهاتف: " . $m( 'phone' ) . "\n";
	$body .= 'في لوحة التحكم: ' . admin_url( 'post.php?post=' . $id . '&action=edit' ) . "\n";
	$sent = (bool) wp_mail( sh_lead_recipients(), '[' . wp_specialchars_decode( get_bloginfo( 'name' ) ) . '] موعد مؤكد: ' . $m( 'name' ) . ' — ' . $when, $body, is_email( $m( 'email' ) ) ? array( 'Reply-To: ' . $m( 'email' ) ) : array() );
	update_post_meta( $id, '_sh_booking_mail', $sent ? 'sent' : 'failed' );
	return $sent;
}

/** State cell: «طلب استشارة» / «موعد مؤكد» + time, with a re-check link for an unverified booking. */
function sh_lead_state_html( int $id ): string {
	$out = esc_html( sh_lead_state_label( $id ) );
	if ( 'booked' === get_post_meta( $id, '_sh_status', true ) ) {
		return '<strong style="color:#00752a">' . $out . '</strong><br><small dir="ltr">' . esc_html( sh_lead_booking_time( $id ) ) . '</small>';
	}
	if ( 'unverified' === get_post_meta( $id, '_sh_booking_state', true ) ) {
		$err  = (string) get_post_meta( $id, '_sh_booking_error', true );
		$out .= $err ? '<br><small>' . esc_html( $err ) . '</small>' : '';
		if ( current_user_can( 'edit_post', $id ) ) {
			$out .= '<br><a href="' . esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=sh_lead_verify&lead=' . $id ), 'sh_lead_verify_' . $id ) ) . '">' . esc_html__( 'تحقق من الحجز في Calendly', 'seohouse-core' ) . '</a>';
		}
	}
	return $out;
}

add_action(
	'admin_post_sh_lead_verify',
	static function () {
		$id = absint( $_GET['lead'] ?? 0 ); // phpcs:ignore WordPress.Security.NonceVerification -- verified below
		check_admin_referer( 'sh_lead_verify_' . $id );
		if ( ! $id || 'sh_lead' !== get_post_type( $id ) || ! current_user_can( 'edit_post', $id ) ) {
			wp_die( esc_html__( 'غير مسموح.', 'seohouse-core' ) );
		}
		$res  = sh_lead_booking_verify( $id );
		$back = wp_get_referer() ? wp_get_referer() : admin_url( 'edit.php?post_type=sh_lead' );
		wp_safe_redirect( add_query_arg( 'sh_verified', $res['confirmed'] ? 'ok' : 'no', remove_query_arg( 'sh_verified', $back ) ) );
		exit;
	}
);
add_action(
	'admin_notices',
	static function () {
		$r = sanitize_key( $_GET['sh_verified'] ?? '' ); // phpcs:ignore WordPress.Security.NonceVerification -- display only
		if ( 'ok' === $r ) {
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'تأكد الحجز من Calendly؛ الطلب الآن «موعد مؤكد».', 'seohouse-core' ) . '</p></div>';
		} elseif ( 'no' === $r ) {
			echo '<div class="notice notice-warning is-dismissible"><p>' . esc_html__( 'لم يتأكد الحجز من Calendly. السبب مكتوب تحت حالة الطلب.', 'seohouse-core' ) . '</p></div>';
		}
	}
);
