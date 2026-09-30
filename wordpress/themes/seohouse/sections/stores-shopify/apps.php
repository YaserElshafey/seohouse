<?php
/**
 * Section "Apps" — SEO House - Shopify.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Apps" style="position: relative; background: var(--sh-surface); border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head data-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <?php if (!empty($f['text'])) : ?><p data-center style="font-size: 16.5px; color: var(--sh-muted); margin: 14px 0px 0px; max-width: 48em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
      <div data-sh-apps style="margin-top: clamp(24px, 2.8vw, 36px); display: grid; gap: 14px;">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_12113f21 = ['v1' => ['display: flex; flex-wrap: wrap; align-items: center; gap: 10px 16px; padding: 16px 18px; border-radius: 14px; background: rgba(255, 255, 255, 0.04); border-inline-start: 2px solid var(--sh-lime);', 'flex: 0 0 auto; font-size: 12.5px; font-weight: 700; color: var(--sh-lime);'], 'v2' => ['display: flex; flex-wrap: wrap; align-items: center; gap: 10px 16px; padding: 16px 18px; border-radius: 14px; background: rgba(255, 255, 255, 0.04); border-inline-start: 2px solid var(--sh-sky);', 'flex: 0 0 auto; font-size: 12.5px; font-weight: 700; color: var(--sh-sky);'], 'v3' => ['display: flex; flex-wrap: wrap; align-items: center; gap: 10px 16px; padding: 16px 18px; border-radius: 14px; background: rgba(255, 255, 255, 0.04); border-inline-start: 2px solid var(--sh-dim);', 'flex: 0 0 auto; font-size: 12.5px; font-weight: 700; color: var(--sh-dim);']]; $vk_12113f21 = $vt_12113f21[$r1['variant'] ?? 'v1'] ?? $vt_12113f21['v1']; ?><div style="<?= esc_attr($vk_12113f21[0] ?? '') ?>">
          <?php if (!empty($r1['heading'])) : ?><span style="flex: 1 1 200px; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16.5px;"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($r1['label'])) : ?><span style="flex: 2 1 320px; font-size: 14.5px; color: var(--sh-muted);"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($r1['eyebrow'])) : ?><span style="<?= esc_attr($vk_12113f21[1] ?? '') ?>"><?= esc_html($r1['eyebrow'] ?? '') ?></span><?php endif; ?>
        </div><?php endforeach; ?>
      </div>
    </div>
  </section>
