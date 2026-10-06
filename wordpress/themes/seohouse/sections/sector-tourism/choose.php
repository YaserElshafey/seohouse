<?php
/**
 * Section "Choose" — SEO House - Sector Tourism.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'tr-choose')) ?>" data-screen-label="Choose" style="position: relative; scroll-margin-top: 88px; background: var(--sh-bg); border-bottom: 1px solid var(--sh-line);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(34px, 4vw, 56px) 20px;">
      <div data-sec-head data-center>
          <div style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><span aria-hidden="true" data-sx-ico style="flex: 0 0 auto; width: 30px; height: 30px; border-radius: 9px; background: rgba(40, 84, 232, 0.22); display: inline-flex; align-items: center; justify-content: center;"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="#2854E8" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M2 16l20-8-8 12-2-6z"></path></svg></span><?php if (!empty($f['label'])) : ?><span><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?></div>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.35;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <?php if (!empty($f['text'])) : ?><p data-center style="font-size: 16.5px; line-height: 1.95; color: var(--sh-ink); margin: 14px 0px 0px; max-width: 44em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
      <div data-tr-pair style="margin-top: 24px; display: grid; gap: 0px; align-items: stretch;">
        <div style="border-radius: 16px; padding: 20px; background: rgba(40, 84, 232, 0.1); border: 1px solid rgba(40, 84, 232, 0.3);">
          <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 12.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17px; color: var(--sh-ink); margin-top: 6px;"><?= esc_html($f['heading'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['label_2'])) : ?><div style="font-size: 14.5px; color: var(--sh-ink); margin-top: 8px; line-height: 1.8;"><?= esc_html($f['label_2'] ?? '') ?></div><?php endif; ?>
        </div>
        <div aria-hidden="true" data-tr-arrow style="display: flex; align-items: center; justify-content: center; color: var(--sh-link); font-size: 20px;">←</div>
        <div style="border-radius: 16px; padding: 20px; background: rgba(40, 84, 232, 0.06); border: 1px solid rgba(40, 84, 232, 0.3);">
          <?php if (!empty($f['eyebrow_2'])) : ?><div style="font-size: 12.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow_2'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['heading_2'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17px; color: var(--sh-ink); margin-top: 6px;"><?= esc_html($f['heading_2'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['label_3'])) : ?><div style="font-size: 14.5px; color: var(--sh-ink); margin-top: 8px; line-height: 1.8;"><?= esc_html($f['label_3'] ?? '') ?></div><?php endif; ?>
        </div>
      </div>
    </div>
  </section>
