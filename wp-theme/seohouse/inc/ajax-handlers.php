<?php
defined( 'ABSPATH' ) || exit;

// ── Contact form ──────────────────────────────────────────────────
add_action( 'wp_ajax_sh_contact',        'sh_handle_contact' );
add_action( 'wp_ajax_nopriv_sh_contact', 'sh_handle_contact' );

function sh_handle_contact(): void {
    check_ajax_referer( 'seohouse_nonce', 'nonce' );

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
        $body .= "\nالرسالة:\n{$message}";
    }

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
