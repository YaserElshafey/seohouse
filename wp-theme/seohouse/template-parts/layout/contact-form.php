<?php
/**
 * Reusable contact form card.
 *
 * Args:
 *   form_title     (string) — card heading
 *   form_sub       (string) — card sub-text
 *   form_note      (string) — note below submit button
 *   success_title  (string) — success screen title
 *   success_desc   (string) — success screen body text
 *   custom_content (string) — optional HTML; replaces the <form> if non-empty
 *   expect_title   (string) — heading for the expectations card (shown below form)
 *   expect_items   (array)  — array of ['exp_text' => '...'] items; card hidden if empty
 *
 * The form submits to the existing sh_handle_contact() AJAX handler.
 * The hidden plan_selected field carries the chosen pricing plan (empty on the
 * regular contact page; filled by pricing-page JS).
 */
$form_title    = $args['form_title']    ?? sh_option( 'contact_form_title',    'أرسل لنا طلبك' );
$form_sub      = $args['form_sub']      ?? sh_option( 'contact_form_sub',      'سنتواصل معك خلال 24 ساعة لتأكيد موعد الاستشارة.' );
$form_note     = $args['form_note']     ?? sh_option( 'contact_form_note',     'أو تواصل معنا على واتساب مباشرةً — سنردّ في أقرب وقت' );
$success_title = $args['success_title'] ?? sh_option( 'contact_success_title', 'تم الإرسال بنجاح!' );
$success_desc  = $args['success_desc']  ?? sh_option( 'contact_success_desc',  'شكراً على تواصلك — سيتصل بك أحد متخصصينا خلال 24 ساعة لتحديد موعد الاستشارة.' );
$custom_content = $args['custom_content'] ?? '';
$expect_title  = $args['expect_title']  ?? '';
$expect_items  = $args['expect_items']  ?? [];
?>
<div class="con-form-col">
<div class="sr">
  <div class="form-card">
    <div id="formWrap">
      <div class="form-title"><?php echo esc_html( $form_title ); ?></div>
      <p class="form-sub"><?php echo esc_html( $form_sub ); ?></p>
      <?php if ( ! trim( wp_strip_all_tags( $custom_content ) ) ) : ?>
      <form id="conForm">
        <input type="hidden" name="plan_selected" id="planSelected" value="">
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
        <p class="form-note"><?php echo esc_html( $form_note ); ?></p>
      </form>
      <?php else : ?>
        <?php echo wp_kses_post( $custom_content ); ?>
      <?php endif; ?>
    </div>

    <div class="form-success" id="formSuccess">
      <div class="success-ico">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <h3 style="font-size:20px;font-weight:800;color:var(--ink);margin-bottom:8px"><?php echo esc_html( $success_title ); ?></h3>
      <p style="font-size:14px;color:var(--muted);line-height:1.72;max-width:320px;margin-inline:auto"><?php echo esc_html( $success_desc ); ?></p>
    </div>
  </div>
</div>

<?php if ( ! empty( $expect_items ) ) : ?>
<div class="expect-card sr">
  <div class="expect-card-title"><?php echo esc_html( $expect_title ); ?></div>
  <div class="chklist">
    <?php foreach ( $expect_items as $ei ) : ?>
    <div class="chk-item"><div class="chk-ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div><?php echo esc_html( $ei['exp_text'] ?? '' ); ?></div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

</div>
