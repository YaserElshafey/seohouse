<?php
/**
 * Section "Fit" — SEO House - WooCommerce.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Fit" style="position: relative; background: var(--sh-surface); color: var(--sh-ink); border-bottom: 1px solid var(--sh-line);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>      <div data-g2c style="margin-top: clamp(22px, 2.6vw, 32px);">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="display: flex; align-items: flex-start; gap: 11px; font-size: 15.5px; color: var(--sh-ink);">
          <span aria-hidden="true" style="flex: 0 0 auto; margin-top: 7px; width: 7px; height: 7px; border-radius: 999px; background: var(--sh-blue);"></span>
          <?php if (!empty($r1['label'])) : ?><span style="text-wrap: pretty;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
        </div><?php endforeach; ?>
      </div>      <?php if (!empty(sh_link($f['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" style="display: inline-flex; align-items: center; gap: 8px; margin-top: 24px; font-size: 15px; font-weight: 600; color: var(--sh-link); border-bottom: 1px solid rgba(40, 84, 232, 0.4); padding-bottom: 3px;"><?= esc_html($f['link_label'] ?? '') ?> <span aria-hidden="true">←</span></a><?php endif; ?>
    </div>
  </section>
