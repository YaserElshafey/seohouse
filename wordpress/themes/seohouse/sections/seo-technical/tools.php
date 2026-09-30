<?php
/**
 * Section "Tools" — SEO House - Technical SEO.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Tools" style="position: relative; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
    <div style="position: relative; max-width: 1100px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sh-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 16px; line-height: 1.8; color: var(--sh-muted); margin: 12px 0px 0px;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
      </div>
      <div data-g3 style="margin-top: clamp(22px, 2.6vw, 32px);">
          <div style="border-radius: 16px; background: var(--sh-surface); border: 1px solid rgba(var(--sh-sky-rgb), 0.14); padding: 18px 20px; display: flex; flex-direction: column; align-items: center; text-align: center; gap: 10px;">
            <span aria-hidden="true" style="height: 30px; display: inline-flex; align-items: center; justify-content: center; width: 30px; border-radius: 8px; background: rgba(var(--sh-sky-rgb), 0.16);"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#4CACFF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.5-3.5"></path></svg></span>
            <?php if (!empty($f['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16.5px;"><?= esc_html($f['heading'] ?? '') ?></div><?php endif; ?>
            <?php if (!empty($f['label'])) : ?><div style="font-size: 14.5px; line-height: 1.7; color: var(--sh-muted);"><?= esc_html($f['label'] ?? '') ?></div><?php endif; ?>
          </div>
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="border-radius: 16px; background: var(--sh-surface); border: 1px solid rgba(var(--sh-sky-rgb), 0.14); padding: 18px 20px; display: flex; flex-direction: column; align-items: center; text-align: center; gap: 10px;">
            <?= sh_svg_img($r1['logo'] ?? '', '', ['style' => 'height: 30px; width: auto;']) ?>
            <?php if (!empty($r1['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16.5px;"><?= esc_html($r1['heading'] ?? '') ?></div><?php endif; ?>
            <?php if (!empty($r1['label'])) : ?><div style="font-size: 14.5px; line-height: 1.7; color: var(--sh-muted);"><?= esc_html($r1['label'] ?? '') ?></div><?php endif; ?>
          </div><?php endforeach; ?>
      </div>
    </div>
  </section>
