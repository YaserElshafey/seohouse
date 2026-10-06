<?php
/**
 * Section "Flow" — SEO House - Contact.
 * Generated from the approved design by tools/design-import/convert.js, then wired by hand:
 * the form posts to SEO House Core (seohouse/v1/lead, source "contact"; no-JS fallback admin-post).
 * @sh-manual
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Flow" style="position: relative; overflow: hidden; border-bottom: 1px solid var(--sh-line);">
    <div aria-hidden="true" style="position: absolute; inset: 0px; background: radial-gradient(45% 60% at 85% 30%, rgba(40, 84, 232, 0.22), transparent 72%), radial-gradient(35% 50% at 10% 90%, rgba(40, 84, 232, 0.12), transparent 70%); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-ct-grid style="display: grid; gap: clamp(26px, 3.2vw, 52px); align-items: center;">
        <div>
          <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
          <div style="margin-top: 20px; display: flex; flex-direction: column;">
            <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="display: flex; align-items: flex-start; gap: 16px; padding: 16px 0px; border-top: 1px solid var(--sh-line);">
              <span aria-hidden="true" style="<?= esc_attr($i1 === 0 ? 'flex: 0 0 auto; width: 38px; height: 38px; border-radius: 11px; background: rgba(40, 84, 232, 0.12); display: flex; align-items: center; justify-content: center;' : 'flex: 0 0 auto; width: 38px; height: 38px; border-radius: 11px; background: rgba(40, 84, 232, 0.22); display: flex; align-items: center; justify-content: center;') ?>"><?= sh_icon($r1['icon'] ?? 'ib0043c43') ?></span>
              <span style="min-width: 0px;"><?php if (!empty($r1['heading'])) : ?><span style="display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16.5px;"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?><?php if (!empty($r1['text'])) : ?><span style="display: block; font-size: 15px; color: var(--sh-ink); margin-top: 6px; text-wrap: pretty;"><?= esc_html($r1['text'] ?? '') ?></span><?php endif; ?></span>
            </div><?php endforeach; ?>
          </div>
          <div style="margin-top: 22px; display: flex; flex-wrap: wrap; gap: 12px 16px; align-items: center;">
            <?php $r1_list = $f['items_2'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty(sh_link($r1['link'] ?? '')) && !empty($r1['link_label'])) : ?><a href="<?= esc_url(sh_link($r1['link'] ?? '')) ?>" style="font-size: 15px; font-weight: 600;"><?= esc_html($r1['link_label'] ?? '') ?></a><?php endif; ?><?php endforeach; ?>
          </div>
        </div>

        <div id="booking" data-sh-contact style="scroll-margin-top: 88px; background: rgb(251, 252, 254); color: var(--sh-ink); border-radius: 18px; box-shadow: rgba(0, 0, 0, 0.75) 0px 30px 64px -38px; padding: clamp(20px, 2.4vw, 32px);">
          <?php
          $ct_status = isset( $_GET['sh_lead'] ) ? sanitize_key( wp_unslash( $_GET['sh_lead'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification -- display only.
          $ct_inputs = array( 'v1' => array( 'text', 'rtl', 'الاسم الكامل', 'name', 'name' ), 'v2' => array( 'text', 'rtl', 'اسم الشركة', 'company', 'organization' ), 'v3' => array( 'email', 'ltr', 'name@company.com', 'email', 'email' ), 'v4' => array( 'tel', 'ltr', '', 'phone', 'tel' ), 'v5' => array( 'url', 'ltr', 'https://', 'site', 'url' ) );
          // market options keep their label as value; service options map to the lead service keys
          $ct_service_keys = array( 'v5' => 'seo', 'v6' => 'web', 'v7' => 'stores', 'v8' => 'products', 'v9' => 'unsure' );
          $ct_fstyle = 'min-height: 52px; border: 1.5px solid var(--sh-line); background: var(--sh-surface); padding: 0px 14px; font-size: 16px; border-radius: 13px; color: var(--sh-ink);';
          ?>
            <form data-ct-form method="post" action="<?= esc_url( admin_url( 'admin-post.php' ) ) ?>" novalidate<?= 'ok' === $ct_status ? ' hidden' : '' ?>>
              <input type="hidden" name="action" value="sh_lead">
              <input type="hidden" name="source" value="contact">
              <input type="hidden" name="page_id" value="<?= esc_attr( (string) get_queried_object_id() ) ?>">
              <input type="hidden" name="ts" value="<?= esc_attr( (string) time() ) ?>">
              <div aria-hidden="true" style="position: absolute; inset-inline-start: -10000px; width: 1px; height: 1px; overflow: hidden;"><label><?php esc_html_e( 'اترك هذا الحقل فارغًا', 'seohouse' ); ?><input type="text" name="company_website" tabindex="-1" autocomplete="off" value=""></label></div>
              <?php if (!empty($f['eyebrow_2'])) : ?><div style="font-size: 13px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow_2'] ?? '') ?></div><?php endif; ?>
              <?php if (!empty($f['title'])) : ?><h2 data-ct-h style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); line-height: 1.32; margin: 12px 0px 0px;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
              <div data-ct-fields style="margin-top: 20px; display: grid; gap: 14px;">
                <?php $r1_list = $f['items_3'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : $in = $ct_inputs[ $r1['variant'] ?? 'v1' ] ?? $ct_inputs['v1']; ?><label style="display: flex; flex-direction: column; gap: 8px; font-size: 14px; font-weight: 600; color: var(--sh-ink); grid-column: <?= $i1 === $i1_n - 1 ? '1 / -1' : 'auto' ?>;">
                    <?php if (!empty($r1['label'])) : ?><span><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
                    <input type="<?= esc_attr($in[0]) ?>" name="<?= esc_attr($in[3]) ?>" autocomplete="<?= esc_attr($in[4]) ?>" dir="<?= esc_attr($in[1]) ?>" placeholder="<?= esc_attr($in[2]) ?>" value="" style="<?= esc_attr($ct_fstyle) ?> text-align: start;">
                  </label><?php endforeach; ?>
                <?php $r1_list = $f['items_4'] ?? []; foreach ((array) $r1_list as $i1 => $r1) : $is_service = false; foreach ( (array) ( $r1['items'] ?? array() ) as $o ) { $is_service = $is_service || isset( $ct_service_keys[ $o['variant'] ?? '' ] ); } ?><label style="display: flex; flex-direction: column; gap: 8px; font-size: 14px; font-weight: 600; color: var(--sh-ink);">
                  <?php if (!empty($r1['label'])) : ?><span><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
                  <select name="<?= $is_service ? 'service' : 'market' ?>" style="<?= esc_attr($ct_fstyle) ?>">
                    <?php foreach ((array) ($r1['items'] ?? []) as $r2) : if (empty($r2['label'])) { continue; } $v = $is_service ? ( $ct_service_keys[ $r2['variant'] ?? '' ] ?? 'unsure' ) : $r2['label']; ?><option value="<?= esc_attr($v) ?>"><?= esc_html($r2['label']) ?></option><?php endforeach; ?>
                  </select>
                </label><?php endforeach; ?>
                <label style="display: flex; flex-direction: column; gap: 8px; font-size: 14px; font-weight: 600; color: var(--sh-ink); grid-column: 1 / -1;">
                  <?php if (!empty($f['label'])) : ?><span><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?>
                  <textarea name="goal" rows="4" placeholder="اكتب باختصار ما تريد تحقيقه أو ما يعطّل موقعك حاليًا" style="border: 1.5px solid var(--sh-line); background: var(--sh-surface); padding: 12px 14px; font-size: 16px; border-radius: 13px; color: var(--sh-ink); resize: vertical; text-align: start;"></textarea>
                </label>
              </div>
              <div role="alert" data-ct-error<?= 'error' === $ct_status ? '' : ' hidden' ?> style="margin-top: 16px; display: flex; align-items: flex-start; gap: 10px; background: #FDECEC; box-shadow: inset 0 0 0 1px rgba(198,40,40,.2); color: #8E1B1B; border-radius: 13px; padding: 12px 16px; font-size: 14.5px; font-weight: 600;"><svg aria-hidden="true" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#C62828" stroke-width="1.8" stroke-linecap="round" style="flex: 0 0 auto; margin-top: 2px;"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16.5v.5"/></svg><span data-ct-error-text><?= 'error' === $ct_status ? esc_html__( 'تعذر إرسال الطلب. راجع البيانات وحاول مرة أخرى.', 'seohouse' ) : '' ?></span></div>
              <?php if (!empty($f['button'])) : ?><button type="submit" class="hv-5e167b" style="margin-top: 22px; width: 100%; background: var(--sh-blue); color: rgb(255, 255, 255); font-weight: 700; font-size: 16.5px; min-height: 56px; padding: 0px 30px; border: 0px; border-radius: 14px; cursor: pointer; box-shadow: rgba(122, 160, 0, 0.8) 0px 12px 26px -16px; transition: background 0.2s;"><?= esc_html($f['button'] ?? '') ?></button><?php endif; ?>
              <?php if (!empty($f['label_2'])) : ?><div style="margin-top: 12px; font-size: 13px; color: var(--sh-text);"><?= esc_html($f['label_2'] ?? '') ?></div><?php endif; ?>
            </form>

            <div data-ct-sent<?= 'ok' === $ct_status ? '' : ' hidden' ?>>
              <div aria-hidden="true" style="width: 52px; height: 52px; border-radius: 999px; background: var(--sh-blue); color: rgb(255, 255, 255); display: flex; align-items: center; justify-content: center; font-size: 23px;">✓</div>
              <div style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: 23px; margin-top: 16px;" tabindex="-1" data-ct-sent-title><?php esc_html_e( 'استلمنا بياناتك', 'seohouse' ); ?></div>
              <p style="font-size: 16px; color: var(--sh-text); margin: 12px 0px 0px;"><?php esc_html_e( 'الخطوة التالية اختيار موعد المكالمة.', 'seohouse' ); ?></p>
              <div data-ct-receipt style="margin-top: 18px; border: 1px dashed rgba(40, 84, 232, 0.3); border-radius: 16px; background: var(--sh-surface); padding: 22px; text-align: center; font-size: 14.5px; color: var(--sh-text);"><?php $bk = (array) sh_option( 'sh_booking', array() ); echo esc_html( $bk['step2']['text'] ?? __( 'سجّلنا طلبك. نراجع موقعك ونتواصل معك لتحديد موعد المكالمة.', 'seohouse' ) ); ?></div>
              <div data-ct-summary style="margin-top: 14px; font-size: 14px; color: var(--sh-text);"></div>
              <button type="button" data-ct-reset style="margin-top: 20px; background: none; border: 1.5px solid var(--sh-line); color: var(--sh-ink); font-weight: 600; min-height: 50px; padding: 0px 20px; border-radius: 14px; cursor: pointer;"><?php esc_html_e( 'إرسال طلب آخر', 'seohouse' ); ?></button>
            </div>
        </div>
      </div>
    </div>
  </section>
