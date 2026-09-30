<?php
/**
 * Section "Direction" — SEO House - Pricing.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Direction" style="position: relative; background: var(--sh-paper); color: var(--sh-ink); border-bottom: 1px solid rgba(var(--sh-ink-rgb), 0.08);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head data-sh-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-blue);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <div data-pr-two style="margin-top: clamp(22px, 2.6vw, 32px); display: grid; gap: 16px;">
        <div style="border-radius: 18px; background: rgb(255, 255, 255); padding: clamp(18px, 2.2vw, 26px); box-shadow: rgba(var(--sh-ink-rgb), 0.5) 0px 16px 36px -28px; border-top: 3px solid var(--sh-blue);">
          <?php if (!empty($f['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18px;"><?= esc_html($f['heading'] ?? '') ?></div><?php endif; ?>
          <div style="margin-top: 14px; display: flex; flex-direction: column; gap: 10px;">
            <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="display: flex; gap: 10px; font-size: 15px; color: var(--sh-ink-soft);"><span aria-hidden="true" style="color: var(--sh-blue);">↑</span><?php if (!empty($r1['label'])) : ?><span><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?></div><?php endforeach; ?>
          </div>
        </div>
        <div style="border-radius: 18px; background: rgb(255, 255, 255); padding: clamp(18px, 2.2vw, 26px); box-shadow: rgba(var(--sh-ink-rgb), 0.5) 0px 16px 36px -28px; border-top: 3px solid rgba(var(--sh-blue-rgb), 0.3);">
          <?php if (!empty($f['heading_2'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18px;"><?= esc_html($f['heading_2'] ?? '') ?></div><?php endif; ?>
          <div style="margin-top: 14px; display: flex; flex-direction: column; gap: 10px;">
            <?php $r1_list = $f['items_2'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="display: flex; gap: 10px; font-size: 15px; color: var(--sh-ink-soft);"><span aria-hidden="true" style="color: var(--sh-slate);">↓</span><?php if (!empty($r1['label'])) : ?><span><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?></div><?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
