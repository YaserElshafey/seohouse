<?php
/**
 * Section "Fit" — SEO House - Custom Development.
 * Generated from the approved design by tools/design-import/convert.js; markup of the v5 design
 * bound by hand to the 2.x fields (heading/items, heading_2/items_2) so saved content keeps showing.
 * @sh-manual
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'fit')) ?>" data-screen-label="Fit" style="position: relative; scroll-margin-top: 88px; border-bottom: 1px solid var(--sh-line);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <div data-g2s style="margin-top: clamp(22px, 2.6vw, 32px); align-items: stretch;">
        <div style="border-radius: 18px; background: var(--sh-surface); padding: clamp(18px, 2.2vw, 26px); border-top: 2px solid var(--sh-blue);">
          <?php if (!empty($f['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18px;"><?= esc_html($f['heading'] ?? '') ?></div><?php endif; ?>
          <div style="margin-top: 14px; display: flex; flex-direction: column; gap: 11px;">
            <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="display: flex; gap: 11px; font-size: 15.5px; color: var(--sh-ink);"><span aria-hidden="true" style="flex: 0 0 auto; margin-top: 7px; width: 7px; height: 7px; border-radius: 999px; background: var(--sh-blue);"></span><?php if (!empty($r1['label'])) : ?><span style="text-wrap: pretty;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?></div><?php endforeach; ?>
          </div>
        </div>
        <div style="border-radius: 18px; background: var(--sh-surface); padding: clamp(18px, 2.2vw, 26px); border-top: 2px solid rgba(40, 84, 232, 0.4);">
          <?php if (!empty($f['heading_2'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18px;"><?= esc_html($f['heading_2'] ?? '') ?></div><?php endif; ?>
          <div style="margin-top: 14px; display: flex; flex-direction: column; gap: 11px;">
            <?php $r1_list = $f['items_2'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="display: flex; gap: 11px; font-size: 15.5px; color: var(--sh-ink);"><span aria-hidden="true" style="flex: 0 0 auto; margin-top: 7px; width: 7px; height: 7px; border-radius: 999px; background: var(--sh-blue);"></span><?php if (!empty($r1['label'])) : ?><span style="text-wrap: pretty;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?></div><?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
