<?php
defined( 'ABSPATH' ) || exit;

// ── Contact form ──────────────────────────────────────────────────
add_action( 'wp_ajax_sh_contact',        'sh_handle_contact' );
add_action( 'wp_ajax_nopriv_sh_contact', 'sh_handle_contact' );

function sh_handle_contact(): void {
    check_ajax_referer( 'seohouse_nonce', 'nonce' );

    $name    = sanitize_text_field(     wp_unslash( $_POST['name']    ?? '' ) );
    $phone   = sanitize_text_field(     wp_unslash( $_POST['phone']   ?? '' ) );
    $email   = sanitize_email(          wp_unslash( $_POST['email']   ?? '' ) );
    $website = esc_url_raw(             wp_unslash( $_POST['website'] ?? '' ) );
    $service = sanitize_text_field(     wp_unslash( $_POST['service'] ?? '' ) );
    $message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

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

// ── Booking ───────────────────────────────────────────────────────
add_action( 'wp_ajax_sh_booking',        'sh_handle_booking' );
add_action( 'wp_ajax_nopriv_sh_booking', 'sh_handle_booking' );

function sh_handle_booking(): void {
    check_ajax_referer( 'seohouse_nonce', 'nonce' );

    $name  = sanitize_text_field( wp_unslash( $_POST['bk_name']  ?? '' ) );
    $phone = sanitize_text_field( wp_unslash( $_POST['bk_phone'] ?? '' ) );
    $email = sanitize_email(      wp_unslash( $_POST['bk_email'] ?? '' ) );
    $date  = sanitize_text_field( wp_unslash( $_POST['bk_date']  ?? '' ) );
    $time  = sanitize_text_field( wp_unslash( $_POST['bk_time']  ?? '' ) );

    if ( ! $name || ! $phone || ! $date || ! $time ) {
        wp_send_json_error( [ 'msg' => 'يرجى اختيار التاريخ والوقت وإدخال الاسم والجوال' ] );
    }
    if ( $email && ! is_email( $email ) ) {
        wp_send_json_error( [ 'msg' => 'البريد الإلكتروني غير صحيح' ] );
    }

    wp_insert_post( [
        'post_type'   => 'sh_booking',
        'post_title'  => $name . ' — ' . $date . ' ' . $time,
        'post_status' => 'publish',
        'meta_input'  => [
            'bk_name'  => $name,
            'bk_phone' => $phone,
            'bk_email' => $email,
            'bk_date'  => $date,
            'bk_time'  => $time,
        ],
    ] );

    // Notify admin
    $to      = sh_option( 'contact_email', get_option( 'admin_email' ) );
    $subject = 'حجز جديد — ' . $name . ' في ' . $date . ' الساعة ' . $time;
    $body    = "اسم العميل: {$name}\nالجوال: {$phone}\n";
    if ( $email ) {
        $body .= "البريد: {$email}\n";
    }
    $body  .= "التاريخ: {$date}\nالوقت: {$time}";
    $h      = [ 'Content-Type: text/plain; charset=UTF-8' ];
    if ( $email ) {
        $h[] = 'Reply-To: ' . $name . ' <' . $email . '>';
    }
    wp_mail( $to, $subject, $body, $h );

    // Confirmation to client
    if ( $email ) {
        $site = get_bloginfo( 'name' );
        wp_mail(
            $email,
            'تأكيد حجز استشارتك — ' . $site,
            "مرحباً {$name}،\n\nتم تأكيد حجزك بنجاح.\n\nالتاريخ: {$date}\nالوقت: {$time}\n\nسنتواصل معك قريباً لإرسال رابط اجتماع Google Meet.\n\nشكراً — فريق {$site}",
            [ 'Content-Type: text/plain; charset=UTF-8' ]
        );
    }

    wp_send_json_success();
}
