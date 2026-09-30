<?php
/**
 * Section "Sectors" — SEO House - UAE SEO Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Sectors" style="border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
    <div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div style="display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 16px;">
        <div data-sec-head>
          <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
        </div>
        <?php if (!empty(sh_link($f['link'] ?? '')) && !empty($f['link_label'])) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" style="font-size: 15px; font-weight: 600;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
      </div>

      <div data-ua-sectors style="margin-top: clamp(24px, 2.8vw, 38px); display: grid; gap: 14px;">
        
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_aca0bf6d = ['v1' => ['display: block; width: 26px; height: 2px; border-radius: 2px; background: var(--sh-lime);'], 'v2' => ['display: block; width: 26px; height: 2px; border-radius: 2px; background: rgba(var(--sh-sky-rgb), 0.55);']]; $vk_aca0bf6d = $vt_aca0bf6d[$r1['variant'] ?? 'v1'] ?? $vt_aca0bf6d['v1']; ?><?php if (!empty(sh_link($r1['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($r1['link'] ?? '')) ?>" class="hv-7121d4" style="position: relative; display: flex; flex-direction: column; gap: 12px; border-radius: 16px; background: rgba(255, 255, 255, 0.035); padding: 20px; color: var(--sh-text); transition: background 0.25s, transform 0.25s;">
            <span aria-hidden="true" style="<?= esc_attr($vk_aca0bf6d[0] ?? '') ?>"></span>
            <?php if (!empty($r1['heading'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17.5px;"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
            <?php if (!empty($r1['label'])) : ?><span style="font-size: 14.5px; color: var(--sh-muted); text-wrap: pretty;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
            <span aria-hidden="true" style="margin-top: auto; font-size: 14px; color: var(--sh-sky);">←</span>
          </a><?php endif; ?><?php endforeach; ?>
        
      </div>
    </div>
  </section>
