<?php
/**
 * Section "Hero" — SEO House - Product Upload.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Hero" data-hero-blue style="position: relative; overflow: hidden; background: linear-gradient(rgb(46, 90, 240) 0%, rgb(40, 84, 232) 60%, rgb(36, 76, 214) 100%); color: rgb(255, 255, 255);">
    <div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(255, 255, 255, 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.9) 1px, transparent 1px); background-size: 72px 72px; mask-image: radial-gradient(90% 100% at 88% 20%, rgb(0, 0, 0), transparent 68%); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: 18px 20px 0px;">
      <?php sh_breadcrumbs(); ?>
    </div>
    <div data-g2 style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(24px, 2.8vw, 42px) 20px clamp(38px, 4.2vw, 60px);">
      <div style="animation: 0.7s ease 0s 1 normal both running fadeUp;">
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: rgb(255, 255, 255);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(28px, 3.2vw, 45px); line-height: 1.3; margin: 12px 0px 0px; max-width: 21em;"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 17.5px; line-height: 1.85; color: rgb(255, 255, 255); max-width: 37em; margin: 18px 0px 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 14px 26px; margin-top: 28px;">
          <?php if (!empty($f['link_label'])) : ?><a href="#booking" data-hero-cta style="background: rgb(255, 255, 255); color: rgb(33, 72, 216); font-weight: 700; font-size: 16.5px; min-height: 56px; display: inline-flex; align-items: center; justify-content: center; padding: 0px 28px; border-radius: 14px;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
          <a href="#flow" data-hero-sec style="min-height: 56px; display: inline-flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 600; color: rgb(255, 255, 255); padding: 0px 16px; border-radius: 14px;"><?= esc_html($f['link_label_2'] ?? '') ?> <i data-ghost-arrow aria-hidden="true">↓</i></a>
        </div>
      </div>
      <div style="animation: 0.7s ease 0.12s 1 normal both running fadeUp;">
        <div aria-label="مثال توضيحي لصفحة منتج بعد الرفع" style="position: relative; border-radius: 20px; background: rgb(255, 255, 255); color: rgb(6, 11, 31); padding: 14px; box-shadow: rgba(0, 0, 0, 0.85) 0px 30px 60px -34px, rgba(var(--sh-blue-rgb), 0.25) 0px 0px 0px 1px;">
          <div style="display: flex; align-items: center; gap: 6px; padding: 2px 4px 12px;">
            <span style="width: 8px; height: 8px; border-radius: 999px; background: rgba(6, 11, 31, 0.15);"></span><span style="width: 8px; height: 8px; border-radius: 999px; background: rgba(6, 11, 31, 0.1);"></span>
            <?php if (!empty($f['eyebrow_2'])) : ?><span style="margin-inline-start: auto; font-size: 11.5px; font-weight: 600; color: rgb(255, 255, 255); background: rgba(var(--sh-blue-rgb), 0.08); border-radius: 999px; padding: 4px 10px;"><?= esc_html($f['eyebrow_2'] ?? '') ?></span><?php endif; ?>
          </div>
          <div data-pu-pp style="display: grid; gap: 14px; align-items: start;">
            <div data-pu-tile style="border-radius: 14px; background: linear-gradient(160deg, var(--sh-blue-tint), rgb(220, 228, 255)); align-self: stretch; min-height: 180px; display: flex; align-items: center; justify-content: center;"><svg viewBox="0 0 24 24" width="46" height="46" fill="none" stroke="#2F5BFF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"></rect><circle cx="9" cy="10" r="2"></circle><path d="m21 16-5-5L5 20"></path></svg></div>
            <div style="min-width: 0px;">
              <?php if (!empty($f['label'])) : ?><div style="font-size: 12px; color: var(--sh-slate);"><?= esc_html($f['label'] ?? '') ?></div><?php endif; ?>
              <?php if (!empty($f['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17px; line-height: 1.45; margin-top: 6px;"><?= esc_html($f['heading'] ?? '') ?></div><?php endif; ?>
              <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="<?= esc_attr($i1 === 0 ? 'display: flex; align-items: center; gap: 10px; margin-top: 8px;' : 'margin-top: 10px; display: flex; flex-wrap: wrap; gap: 6px;') ?>"><span style="font-weight: 700; font-size: 16px;">السعر</span><span style="font-size: 12px; font-weight: 600; color: rgb(11, 122, 75); background: rgba(11, 122, 75, 0.1); border-radius: 999px; padding: 3px 9px;">متوفر</span></div><?php endforeach; ?>
              <div style="margin-top: 10px; height: 6px; width: 92%; border-radius: 4px; background: rgba(6, 11, 31, 0.08);"></div>
              <div style="margin-top: 6px; height: 6px; width: 70%; border-radius: 4px; background: rgba(6, 11, 31, 0.08);"></div>
              <?php if (!empty($f['eyebrow_3'])) : ?><div style="margin-top: 12px; border-radius: 10px; background: rgb(255, 255, 255); color: rgb(33, 72, 216); font-size: 13px; font-weight: 700; text-align: center; padding: 9px;"><?= esc_html($f['eyebrow_3'] ?? '') ?></div><?php endif; ?>
            </div>
          </div>
          <div style="margin-top: 12px; border-radius: 12px; background: var(--sh-paper-2); padding: 10px 12px; display: flex; align-items: center; gap: 10px;">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="#2F5BFF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.5-3.5"></path></svg><?php if (!empty($f['label_2'])) : ?><span style="min-width: 0px; font-size: 12.5px; color: var(--sh-ink-soft); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= esc_html($f['label_2'] ?? '') ?></span><?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
