<?php
/**
 * Section "Market" — SEO House - Egypt SEO Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Market" style="position: relative; background: rgb(11, 19, 48); overflow: hidden;">
    <div aria-hidden="true" style="position: absolute; inset: 0px; opacity: 0.05; background-image: repeating-linear-gradient(90deg, rgba(var(--sh-sky-rgb), 0.9) 0px, rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px, transparent 34px); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <div style="display: flex; flex-wrap: wrap; gap: 14px 44px; margin-top: 16px;">
        <?php if (!empty($f['text'])) : ?><p style="flex: 1 1 440px; font-size: 17px; color: var(--sh-muted); margin: 0px; max-width: 44em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        <?php if (!empty($f['text_2'])) : ?><p style="flex: 0 1 300px; font-size: 15px; color: var(--sh-crumb); margin: 0px; align-self: flex-end;"><?= esc_html($f['text_2'] ?? '') ?></p><?php endif; ?>
      </div>

      <div style="position: relative; margin-top: clamp(28px, 3.2vw, 44px);">
        <span aria-hidden="true" data-eg-jrail></span>
        <div data-eg-journey style="display: grid; gap: 22px 26px;">
          
            <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div data-eg-stage style="position: relative;">
              <span aria-hidden="true" data-eg-jdot style="<?= esc_attr($i1 === 0 ? 'display: block; width: 17px; height: 17px; border-radius: 999px; background: rgb(11, 19, 48); border: 2px solid var(--sh-lime);' : 'display: block; width: 17px; height: 17px; border-radius: 999px; background: rgb(11, 19, 48); border: 2px solid var(--sh-sky);') ?>"></span>
              <div style="<?= esc_attr($i1 === 0 ? 'font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: var(--sh-lime); margin-top: 16px;' : 'font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: var(--sh-sky); margin-top: 16px;') ?>"><?= esc_html(sprintf('%02d', $i1 + 1)) ?></div>
              <?php if (!empty($r1['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18.5px; margin-top: 7px;"><?= esc_html($r1['heading'] ?? '') ?></div><?php endif; ?>
              <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 14px;">
                
                  <?php $r2_list = $r1['items'] ?? []; $i2_n = is_array($r2_list) ? count($r2_list) : 0; foreach ((array) $r2_list as $i2 => $r2) : ?><span style="display: inline-flex; align-items: center; gap: 8px; font-size: 14px; color: var(--sh-text); background: rgb(17, 28, 68); border: 1px solid rgba(var(--sh-sky-rgb), 0.28); border-radius: 999px; padding: 8px 14px;"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="#8494B5" stroke-width="1.7" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.5-3.5"></path></svg><?= esc_html($r2['label'] ?? '') ?></span><?php endforeach; ?>
                
              </div>
              <div style="display: inline-flex; align-items: center; gap: 8px; margin-top: 12px; font-size: 13.5px; font-weight: 600; color: var(--sh-ink); background: var(--sh-lime); border-radius: 10px; padding: 7px 12px;"><span aria-hidden="true">←</span><?= esc_html($r1['eyebrow'] ?? '') ?></div>
              <?php if (!empty($r1['text'])) : ?><p style="font-size: 15px; color: var(--sh-muted); margin: 12px 0px 0px; max-width: 26em; text-wrap: pretty;"><?= esc_html($r1['text'] ?? '') ?></p><?php endif; ?>
            </div><?php endforeach; ?>
          
        </div>
      </div>
    </div>
  </section>
