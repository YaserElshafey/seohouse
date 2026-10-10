<?php
defined( 'ABSPATH' ) || exit;

// ── Contact form ──────────────────────────────────────────────────
add_action( 'wp_ajax_sh_contact',        'sh_handle_contact' );
add_action( 'wp_ajax_nopriv_sh_contact', 'sh_handle_contact' );

/**
 * Signed render timestamp for the contact form ("time|hmac").
 * Lets the handler reject submissions sent faster than a person could type,
 * and still works on cached pages (only a minimum age is enforced).
 */
function sh_contact_form_token(): string {
    $t = (string) time();
    return $t . '|' . hash_hmac( 'sha256', $t, wp_salt( 'nonce' ) );
}

/**
 * Seconds since the form was rendered, or -1 when the token is missing/forged.
 */
function sh_contact_form_age( string $token ): int {
    $parts = explode( '|', $token );
    if ( count( $parts ) !== 2 || ! ctype_digit( $parts[0] ) ) {
        return -1;
    }
    if ( ! hash_equals( hash_hmac( 'sha256', $parts[0], wp_salt( 'nonce' ) ), $parts[1] ) ) {
        return -1;
    }
    return time() - (int) $parts[0];
}

function sh_handle_contact(): void {
    check_ajax_referer( 'seohouse_nonce', 'nonce' );

    // ── Spam checks ──────────────────────────────────────────────
    // Honeypot filled → bot. Answer "success" so it does not retry, but store/send nothing.
    if ( '' !== trim( (string) wp_unslash( $_POST['sh_hp'] ?? '' ) ) ) {
        wp_send_json_success();
    }
    $age = sh_contact_form_age( sanitize_text_field( wp_unslash( $_POST['sh_ts'] ?? '' ) ) );
    if ( $age < 0 ) {
        wp_send_json_error( [ 'msg' => 'انتهت صلاحية الصفحة، يرجى تحديثها ثم إعادة الإرسال' ] );
    }
    if ( $age < 3 ) {
        wp_send_json_success();
    }
    $ip      = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? '' ) );
    $rate_k  = 'sh_contact_rl_' . md5( $ip );
    $rate_n  = (int) get_transient( $rate_k );
    if ( $rate_n >= 5 ) {
        wp_send_json_error( [ 'msg' => 'تم استلام عدة طلبات من نفس الجهاز، يرجى المحاولة لاحقاً أو التواصل عبر الهاتف أو البريد' ] );
    }

    $name          = sanitize_text_field(     wp_unslash( $_POST['name']          ?? '' ) );
    $phone         = sanitize_text_field(     wp_unslash( $_POST['phone']         ?? '' ) );
    $email         = sanitize_email(          wp_unslash( $_POST['email']         ?? '' ) );
    $website       = esc_url_raw(             wp_unslash( $_POST['website']       ?? '' ) );
    $service       = sanitize_text_field(     wp_unslash( $_POST['service']       ?? '' ) );
    $message       = sanitize_textarea_field( wp_unslash( $_POST['message']       ?? '' ) );
    $plan_selected = sanitize_text_field(     wp_unslash( $_POST['plan_selected'] ?? '' ) );

    if ( ! $name || ! $phone || ! $email || ! $service ) {
        wp_send_json_error( [ 'msg' => 'يرجى ملء الحقول المطلوبة' ] );
    }
    if ( ! is_email( $email ) ) {
        wp_send_json_error( [ 'msg' => 'البريد الإلكتروني غير صحيح' ] );
    }
    if ( mb_strlen( $name ) > 100 || mb_strlen( $phone ) > 30 || mb_strlen( $service ) > 120 || mb_strlen( $message ) > 3000 ) {
        wp_send_json_error( [ 'msg' => 'بعض الحقول أطول من المسموح، يرجى الاختصار' ] );
    }
    // Link-stuffed messages are almost always spam.
    if ( preg_match_all( '#https?://#i', $message ) > 3 ) {
        wp_send_json_success();
    }

    set_transient( $rate_k, $rate_n + 1, 10 * MINUTE_IN_SECONDS );

    // ── Save the request in the admin (طلبات التواصل) ────────────
    $source = esc_url_raw( wp_get_referer() ?: '' );
    $lead_id = wp_insert_post( [
        'post_type'   => 'sh_booking',
        'post_status' => 'private',
        'post_title'  => $name,
        'meta_input'  => [
            'bk_phone'   => $phone,
            'bk_email'   => $email,
            'bk_website' => $website,
            'bk_service' => $service,
            'bk_message' => $message,
            'bk_plan'    => $plan_selected,
            'bk_source'  => $source,
        ],
    ], true );
    $saved = $lead_id && ! is_wp_error( $lead_id );

    // ── Email notification ───────────────────────────────────────
    $to      = sh_option( 'contact_email', get_option( 'admin_email' ) );
    $subject = 'طلب تواصل جديد — ' . $name;
    $body    = "الاسم: {$name}\nالجوال: {$phone}\nالبريد: {$email}\n";
    if ( $website ) {
        $body .= "الموقع: {$website}\n";
    }
    $body .= "الخدمة: {$service}\n";
    if ( $plan_selected ) {
        $body .= "الباقة المختارة: {$plan_selected}\n";
    }
    if ( $message ) {
        $body .= "\nالرسالة:\n{$message}\n";
    }
    if ( $source ) {
        $body .= "\nصفحة الإرسال: {$source}\n";
    }
    if ( $saved ) {
        $body .= 'عرض الطلب في لوحة التحكم: ' . admin_url( 'post.php?post=' . (int) $lead_id . '&action=edit' ) . "\n";
    }

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . str_replace( [ '<', '>', '"', ',' ], '', $name ) . ' <' . $email . '>',
    ];

    $mailed = wp_mail( $to, $subject, $body, $headers );
    if ( $saved && ! $mailed ) {
        update_post_meta( $lead_id, 'bk_mail_failed', 1 );
    }

    // The request is not lost as long as it was saved or emailed.
    if ( $saved || $mailed ) {
        wp_send_json_success();
    }
    wp_send_json_error( [ 'msg' => 'حدث خطأ أثناء الإرسال، يرجى المحاولة مرة أخرى' ] );
}

// ── SEO Pricing V2 contact form — isolated handler with required website ──
add_action( 'wp_ajax_sh_pricing_contact',        'sh_handle_pricing_contact' );
add_action( 'wp_ajax_nopriv_sh_pricing_contact', 'sh_handle_pricing_contact' );

function sh_handle_pricing_contact(): void {
    check_ajax_referer( 'seohouse_nonce', 'nonce' );

    $name        = sanitize_text_field( wp_unslash( $_POST['name']    ?? '' ) );
    $phone       = sanitize_text_field( wp_unslash( $_POST['phone']   ?? '' ) );
    $email       = sanitize_email(      wp_unslash( $_POST['email']   ?? '' ) );
    $service     = sanitize_text_field( wp_unslash( $_POST['service'] ?? '' ) );
    $website_raw = trim( wp_unslash( $_POST['website'] ?? '' ) );
    $website     = esc_url_raw( $website_raw );

    if ( ! $name || ! $phone || ! $email || ! $service ) {
        wp_send_json_error( [ 'msg' => 'يرجى ملء جميع الحقول المطلوبة' ] );
    }
    if ( ! is_email( $email ) ) {
        wp_send_json_error( [ 'msg' => 'البريد الإلكتروني غير صحيح' ] );
    }
    if ( empty( $website_raw ) ) {
        wp_send_json_error( [ 'msg' => 'يرجى إدخال رابط موقعك الإلكتروني' ] );
    }
    if ( ! wp_http_validate_url( $website ) ) {
        wp_send_json_error( [ 'msg' => 'رابط الموقع غير صحيح. أدخل عنوانًا كاملًا مثل: https://example.com' ] );
    }

    $to      = sh_option( 'contact_email', get_option( 'admin_email' ) );
    $subject = 'طلب تسعير SEO — ' . $name;
    $body    = "الاسم: {$name}\nالجوال: {$phone}\nالبريد: {$email}\nالموقع: {$website}\nنوع النشاط: {$service}";

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>',
    ];

    if ( wp_mail( $to, $subject, $body, $headers ) ) {
        wp_send_json_success();
    } else {
        wp_send_json_error( [ 'msg' => 'حدث خطأ أثناء الإرسال، يرجى المحاولة مرة أخرى' ] );
    }
}
