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
	$body    .= 'الاسم: ' . $m( 'name' ) . "\nالتواصل: {$contact}\nالخدمة: {$service}\n";
	foreach ( array( 'company' => 'الشركة', 'phone' => 'الهاتف', 'market' => 'السوق', 'goal' => 'الهدف' ) as $k => $label ) {
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
			return array( 'ok' => true, 'code' => 'duplicate', 'message' => __( 'طلبك مسجل لدينا بالفعل.', 'seohouse-core' ), 'id' => (int) $same[0] );
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
		return array( 'ok' => true, 'code' => 'duplicate', 'message' => __( 'طلبك مسجل لدينا بالفعل.', 'seohouse-core' ), 'id' => (int) $dup[0] );
	}

	// rate limit: 5 new requests / 10 minutes per IP (hashed, not stored in clear)
	$ip   = (string) ( $_SERVER['REMOTE_ADDR'] ?? '' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	$bkey = 'sh_lead_rl_' . substr( hash_hmac( 'sha256', $ip, wp_salt( 'nonce' ) ), 0, 20 );
	$hits = (int) get_transient( $bkey );
	if ( $hits >= 5 ) {
		return $fail( 'rate', __( 'استلمنا عدة طلبات من هذا الجهاز. حاول بعد قليل.', 'seohouse-core' ) );
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
				'_sh_sid'     => $sid,
				'_sh_status'  => 'new',
			) + ( $extra ? array(
				'_sh_company' => $extra['company'],
				'_sh_phone'   => $extra['phone'],
				'_sh_market'  => $extra['market'],
				'_sh_goal'    => $extra['goal'],
			) : array() ),
		),
		true
	);
	if ( is_wp_error( $id ) || ! $id ) {
		return $fail( 'store', __( 'تعذر حفظ الطلب الآن. حاول مرة أخرى أو تواصل معنا عبر صفحة التواصل.', 'seohouse-core' ) );
	}

	set_transient( $bkey, $hits + 1, 10 * MINUTE_IN_SECONDS );
	sh_lead_notify( (int) $id );

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
					'المصدر'  => sh_lead_source_label( (int) $post->ID ),
					'الاسم'   => get_post_meta( $post->ID, '_sh_name', true ),
					'التواصل' => get_post_meta( $post->ID, '_sh_contact', true ),
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
