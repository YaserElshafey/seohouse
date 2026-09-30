<?php
/**
 * Section "Sectors" — SEO House - All Services.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Sectors" style="background: var(--sh-surface);">
    <div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div style="display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 16px;">
        <div data-sec-head style="flex: 1 1 420px;">
          <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
        </div>
        <?php if (!empty(sh_link($f['link'] ?? '')) && !empty($f['link_label'])) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" style="font-size: 15px; font-weight: 600;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
      </div>
      <div style="margin-top: 24px; display: flex; flex-wrap: wrap; gap: 10px;">
        
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty(sh_link($r1['link'] ?? '')) && !empty($r1['link_label'])) : ?><a href="<?= esc_url(sh_link($r1['link'] ?? '')) ?>" class="hv-90a743" style="font-size: 15px; color: var(--sh-crumb-current); background: rgba(255, 255, 255, 0.05); border-radius: 999px; padding: 10px 16px;"><?= esc_html($r1['link_label'] ?? '') ?></a><?php endif; ?><?php endforeach; ?>
        
      </div>
    </div>
  </section>
