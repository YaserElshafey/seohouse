<?php
/**
 * Template Name: Contact Page
 */
get_header();

$contact_email    = sh_option( 'contact_email',    'info@seohouse.sa' );
$contact_phone    = sh_option( 'contact_phone',    '' );
$social_linkedin  = sh_option( 'social_linkedin',  '#' );
$consult_duration = sh_option( 'consult_duration', '30 دقيقة' );

$contact_hero_tag  = sh_option( 'contact_hero_tag',  'استشارة مجانية' );
$contact_hero_raw  = sh_option( 'contact_hero_title', "30 دقيقة قد تغيّر\nمسار موقعك" );
$contact_hero_em   = sh_option( 'contact_hero_em',   'موقعك' );
$contact_hero_desc = sh_option( 'contact_hero_desc', 'أخبرنا عن موقعك ونشاطك — وسنخبرك بصدق أين أنت وما الذي يمكن تحقيقه. مجاناً، وبدون التزام.' );

$contact_hero_display = $contact_hero_em
    ? str_replace( $contact_hero_em, '<em>' . esc_html( $contact_hero_em ) . '</em>', esc_html( $contact_hero_raw ) )
    : esc_html( $contact_hero_raw );
$contact_hero_display = nl2br( $contact_hero_display );

$contact_form_title    = sh_option( 'contact_form_title',    'أرسل لنا طلبك' );
$contact_form_sub      = sh_option( 'contact_form_sub',      'سنتواصل معك خلال 24 ساعة لتأكيد موعد الاستشارة.' );
$contact_form_note     = sh_option( 'contact_form_note',     'أو تواصل معنا على واتساب مباشرةً — سنردّ في أقرب وقت' );
$contact_success_title = sh_option( 'contact_success_title', 'تم الإرسال بنجاح!' );
$contact_success_desc  = sh_option( 'contact_success_desc',  'شكراً على تواصلك — سيتصل بك أحد متخصصينا خلال 24 ساعة لتحديد موعد الاستشارة.' );
$contact_cal_title     = sh_option( 'contact_cal_title',     'احجز وقتك مباشرةً' );
$contact_expect_title  = sh_option( 'contact_expect_title',  'ماذا تتوقع بعد التواصل' );
$contact_expect_items  = sh_option( 'contact_expect_items',  [] );
if ( empty( $contact_expect_items ) ) {
    $contact_expect_items = [
        [ 'exp_text' => 'ردّ خلال 24 ساعة في أيام العمل' ],
        [ 'exp_text' => 'تحليل أولي سريع لموقعك' ],
        [ 'exp_text' => 'اجتماع ' . $consult_duration . ' عبر Google Meet' ],
        [ 'exp_text' => 'توصيات فورية — بدون أي التزام' ],
    ];
}
?>

<?php
get_template_part( 'template-parts/layout/page-hero', null, [
    'tag'         => $contact_hero_tag,
    'title'       => $contact_hero_display,
    'description' => $contact_hero_desc,
    'breadcrumb'  => [ 'اتصل بنا' => '' ],
] );
?>

<section class="sec sec-surface">
  <div class="wrap">
    <div class="con-layout">

      <!-- Form -->
      <div class="sr">
        <div class="form-card">
          <div id="formWrap">
            <div class="form-title"><?php echo esc_html( $contact_form_title ); ?></div>
            <p class="form-sub"><?php echo esc_html( $contact_form_sub ); ?></p>
            <?php
            if ( function_exists( 'the_content' ) ) {
                ob_start();
                the_content();
                $custom_content = ob_get_clean();
            } else {
                $custom_content = '';
            }
            if ( ! trim( wp_strip_all_tags( $custom_content ) ) ) :
            ?>
            <form id="conForm">
              <div class="form-row">
                <div class="form-group">
                  <label>الاسم <span>*</span></label>
                  <input class="form-input" type="text" name="name" placeholder="اسمك الكريم" required>
                </div>
                <div class="form-group">
                  <label>رقم الجوال <span>*</span></label>
                  <input class="form-input" type="tel" name="phone" placeholder="05xxxxxxxx" required>
                </div>
              </div>
              <div class="form-group">
                <label>البريد الإلكتروني <span>*</span></label>
                <input class="form-input" type="email" name="email" placeholder="your@email.com" required>
              </div>
              <div class="form-group">
                <label>رابط الموقع أو المتجر</label>
                <input class="form-input" type="url" name="website" placeholder="https://yourwebsite.com">
              </div>
              <div class="form-group">
                <label>الخدمة المطلوبة <span>*</span></label>
                <select class="form-select" name="service" required>
                  <option value="" disabled selected>اختر الخدمة</option>
                  <option>تحسين محركات البحث</option>
                  <option>سيو المتاجر الإلكترونية</option>
                  <option>بناء الباك لينك</option>
                  <option>كتابة المحتوى</option>
                  <option>استشارة تحليل الأداء</option>
                  <option>إنشاء وتصميم موقع</option>
                  <option>إنشاء وتصميم متجر</option>
                  <option>رفع منتجات متجر</option>
                  <option>لست متأكداً — أحتاج استشارة</option>
                </select>
              </div>
              <div class="form-group">
                <label>ما الذي تريد تحسينه؟</label>
                <textarea class="form-textarea" name="message" placeholder="أخبرنا باختصار عن وضع موقعك الحالي وما تريد تحقيقه..."></textarea>
              </div>
              <button type="submit" class="form-submit">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                أرسل الطلب
              </button>
              <p id="conFormError" class="form-error"></p>
              <p class="form-note"><?php echo esc_html( $contact_form_note ); ?></p>
            </form>
            <?php else :
                echo wp_kses_post( $custom_content );
            endif; ?>
          </div>

          <div class="form-success" id="formSuccess">
            <div class="success-ico">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <h3 style="font-size:20px;font-weight:800;color:var(--ink);margin-bottom:8px"><?php echo esc_html( $contact_success_title ); ?></h3>
            <p style="font-size:14px;color:var(--muted);line-height:1.72;max-width:320px;margin-inline:auto"><?php echo esc_html( $contact_success_desc ); ?></p>
          </div>
        </div>
      </div>

      <!-- Info panel -->
      <div class="info-panel">

        <!-- Booking via Calendly embed -->
        <?php
        $_cal_embed = trim( sh_option( 'calendly_embed', '' ) );
        $_show_cal  = $_cal_embed && strpos( $_cal_embed, 'calendly.com' ) !== false;
        if ( $_show_cal ) {
            wp_enqueue_script( 'calendly-widget', 'https://assets.calendly.com/assets/external/widget.js', [], null, true );
        }
        ?>
        <?php if ( $_show_cal ) : ?>
        <div class="info-card sr d1">
          <div class="info-head">
            <div class="info-ico">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div>
              <div class="info-t"><?php echo esc_html( $contact_cal_title ); ?></div>
              <div class="info-sub"><?php echo esc_html( $consult_duration ); ?> · Google Meet · مجانية</div>
            </div>
          </div>
          <div class="calendly-wrap">
            <?php
            // Strip <script> tags from embed (widget.js already enqueued above).
            $strip = preg_replace( '/<script\b[^>]*>.*?<\/script>/is', '', $_cal_embed );
            echo wp_kses( $strip, [
                'div' => [
                    'class'            => true,
                    'style'            => true,
                    'id'               => true,
                    'data-url'         => true,
                    'data-resize'      => true,
                    'data-prefill'     => true,
                    'data-utm-source'  => true,
                    'data-utm-medium'  => true,
                    'data-utm-campaign'=> true,
                    'data-utm-content' => true,
                    'data-utm-term'    => true,
                ],
            ] );
            ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Contact details card -->
        <div class="info-card sr d2">
          <div style="font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.22);margin-bottom:14px;position:relative;z-index:1">وسائل التواصل</div>
          <div class="con-details">
            <?php if ( $contact_email ) : ?>
            <a href="mailto:<?php echo esc_attr( $contact_email ); ?>" class="con-detail">
              <div class="con-d-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div>
              <div><div class="con-d-t"><?php echo esc_html( $contact_email ); ?></div><div class="con-d-sub">للاستفسارات العامة</div></div>
            </a>
            <?php endif; ?>
            <?php if ( $contact_phone ) : ?>
            <a href="https://wa.me/<?php echo esc_attr( preg_replace( '/\D/', '', $contact_phone ) ); ?>" class="con-detail" target="_blank" rel="noopener">
              <div class="con-d-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg></div>
              <div><div class="con-d-t">واتساب</div><div class="con-d-sub">للتواصل السريع</div></div>
            </a>
            <?php endif; ?>
            <?php if ( $social_linkedin && $social_linkedin !== '#' ) : ?>
            <a href="<?php echo esc_url( $social_linkedin ); ?>" class="con-detail" target="_blank" rel="noopener">
              <div class="con-d-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg></div>
              <div><div class="con-d-t">لينكدإن</div><div class="con-d-sub">تابعنا للمحتوى المتخصص</div></div>
            </a>
            <?php endif; ?>
          </div>
        </div>

        <!-- Quick expectations -->
        <div style="background:var(--surface);border:1px solid var(--line);border-radius:var(--r2);padding:18px 20px" class="sr d3">
          <div style="font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:12px"><?php echo esc_html( $contact_expect_title ); ?></div>
          <div class="chklist">
            <?php foreach ( $contact_expect_items as $ei ) : ?>
            <div class="chk-item"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div><?php echo esc_html( $ei['exp_text'] ?? '' ); ?></div>
            <?php endforeach; ?>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
