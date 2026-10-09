<?php
/**
 * Section "First month" — SEO House - UAE SEO Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="First month" style="border-bottom: 1px solid var(--sh-line);">
    <div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head data-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <?php if (!empty($f['text'])) : ?><p data-center style="font-size: 16.5px; color: var(--sh-ink); margin: 14px 0px 0px; max-width: 48em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>

      <div style="margin-top: clamp(24px, 2.8vw, 38px); border-radius: 18px; background: rgba(255, 255, 255, 0.035); overflow: hidden;">
        <div data-ua-prow data-ua-phead style="display: grid; align-items: center; gap: 14px 20px; padding: 14px 20px; background: rgba(40, 84, 232, 0.1); font-size: 12.5px; font-weight: 600; color: var(--sh-text);">
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty($r1['label'])) : ?><span><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?><?php endforeach; ?><?php if (!empty($f['label'])) : ?><span data-ua-pweight><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?>
        </div>
        
          <?php $r1_list = $f['items_2'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_95a7600d = ['v1' => ['display: block; height: 100%; width: 96%; background: rgb(33, 72, 216);', 'flex: 0 0 auto; font-size: 13px; color: rgb(33, 72, 216);'], 'v2' => ['display: block; height: 100%; width: 88%; background: rgb(33, 72, 216);', 'flex: 0 0 auto; font-size: 13px; color: rgb(33, 72, 216);'], 'v3' => ['display: block; height: 100%; width: 68%; background: rgb(33, 72, 216);', 'flex: 0 0 auto; font-size: 13px; color: rgb(76, 89, 107);'], 'v4' => ['display: block; height: 100%; width: 60%; background: rgb(33, 72, 216);', 'flex: 0 0 auto; font-size: 13px; color: rgb(76, 89, 107);'], 'v5' => ['display: block; height: 100%; width: 46%; background: rgb(33, 72, 216);', 'flex: 0 0 auto; font-size: 13px; color: rgb(76, 89, 107);']]; $vk_95a7600d = $vt_95a7600d[$r1['variant'] ?? 'v1'] ?? $vt_95a7600d['v1']; ?><div data-ua-prow style="display: grid; align-items: center; gap: 10px 20px; padding: 18px 20px; border-top: 1px solid var(--sh-line);">
            <span style="display: flex; align-items: center; gap: 12px; min-width: 0px;">
              <span aria-hidden="true" style="flex: 0 0 auto; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: rgb(33, 72, 216);"><?= esc_html(sprintf('%02d', $i1 + 1)) ?></span>
              <?php if (!empty($r1['text'])) : ?><span style="font-size: 15.5px; font-weight: 600; color: var(--sh-ink); text-wrap: pretty;"><?= esc_html($r1['text'] ?? '') ?></span><?php endif; ?>
            </span>
            <?php if (!empty($r1['label'])) : ?><span style="font-size: 14.5px; color: var(--sh-ink); text-wrap: pretty;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
            <span data-ua-pweight style="display: flex; align-items: center; gap: 10px;">
              <span aria-hidden="true" style="flex: 1 1 auto; max-width: 84px; height: 5px; border-radius: 999px; background: var(--sh-line); overflow: hidden;"><span style="<?= esc_attr($vk_95a7600d[0] ?? '') ?>"></span></span>
              <?php if (!empty($r1['label_2'])) : ?><span style="<?= esc_attr($vk_95a7600d[1] ?? '') ?>"><?= esc_html($r1['label_2'] ?? '') ?></span><?php endif; ?>
            </span>
          </div><?php endforeach; ?>
        
      </div>
    </div>
  </section>
