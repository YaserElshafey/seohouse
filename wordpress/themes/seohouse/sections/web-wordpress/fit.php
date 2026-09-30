<?php
/**
 * Section "Fit" — SEO House - WordPress.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Fit" style="position: relative; background: var(--sh-paper); color: var(--sh-ink); border-bottom: 1px solid rgba(var(--sh-ink-rgb), 0.08);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-blue);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>      <div data-g2s style="margin-top: clamp(22px, 2.6vw, 32px); align-items: stretch;">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="<?= esc_attr($i1 === 0 ? 'border-radius: 18px; background: rgb(255, 255, 255); padding: clamp(18px, 2.2vw, 26px); box-shadow: rgba(var(--sh-ink-rgb), 0.5) 0px 16px 36px -28px; border-top: 3px solid var(--sh-blue);' : 'border-radius: 18px; background: rgb(255, 255, 255); padding: clamp(18px, 2.2vw, 26px); box-shadow: rgba(var(--sh-ink-rgb), 0.5) 0px 16px 36px -28px; border-top: 3px solid rgba(var(--sh-ink-rgb), 0.2);') ?>">
          <?php if (!empty($r1['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18px;"><?= esc_html($r1['heading'] ?? '') ?></div><?php endif; ?>
          <div style="margin-top: 14px; display: flex; flex-direction: column; gap: 10px;">
            <?php $r2_list = $r1['items'] ?? []; $i2_n = is_array($r2_list) ? count($r2_list) : 0; foreach ((array) $r2_list as $i2 => $r2) : ?><?php $vt_c02663e3 = ['v1' => ['color: var(--sh-blue);'], 'v2' => ['color: rgb(132, 148, 166);']]; $vk_c02663e3 = $vt_c02663e3[$r2['variant'] ?? 'v1'] ?? $vt_c02663e3['v1']; ?><div style="display: flex; gap: 10px; font-size: 15px; color: var(--sh-ink-soft);"><span aria-hidden="true" style="<?= esc_attr($vk_c02663e3[0] ?? '') ?>"><?= esc_html($r2['symbol'] ?? '') ?></span><?php if (!empty($r2['label'])) : ?><span><?= esc_html($r2['label'] ?? '') ?></span><?php endif; ?></div><?php endforeach; ?>
          </div>
        </div><?php endforeach; ?>
      </div>
      <?php if (!empty(sh_link($f['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" style="display: inline-flex; align-items: center; gap: 8px; margin-top: 24px; font-size: 15px; font-weight: 600; color: var(--sh-blue); border-bottom: 1px solid rgba(var(--sh-blue-rgb), 0.4); padding-bottom: 3px;"><?= esc_html($f['link_label'] ?? '') ?> <span aria-hidden="true">←</span></a><?php endif; ?>
    </div>
  </section>
