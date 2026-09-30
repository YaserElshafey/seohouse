<?php
/**
 * Section "NoPromise" — SEO House - About.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="NoPromise" style="position: relative; background: var(--sh-surface); border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-ab-split style="display: grid; gap: clamp(22px, 2.8vw, 42px); align-items: start;">
        <div>
      <div data-sec-head>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
          <div style="margin-top: 20px; display: flex; flex-direction: column; gap: 12px;">
            <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="display: flex; gap: 11px; font-size: 15.5px; color: var(--sh-crumb-current);"><span aria-hidden="true" style="flex: 0 0 auto; margin-top: 7px; width: 7px; height: 7px; border-radius: 999px; background: var(--sh-lime);"></span><?php if (!empty($r1['label'])) : ?><span style="text-wrap: pretty;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?></div><?php endforeach; ?>
          </div>
        </div>
        <div style="border-radius: 18px; background: rgba(255, 255, 255, 0.04); padding: clamp(20px, 2.4vw, 28px);">
          <?php if (!empty($f['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17.5px;"><?= esc_html($f['heading'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['text'])) : ?><p style="font-size: 15px; color: var(--sh-muted); margin: 12px 0px 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
          <?php if (!empty(sh_link($f['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" style="display: inline-flex; align-items: center; gap: 8px; margin-top: 18px; font-size: 15px; font-weight: 600; color: var(--sh-lime); border-bottom: 1px solid rgba(var(--sh-lime-rgb), 0.45); padding-bottom: 3px;"><?= esc_html($f['link_label'] ?? '') ?> <span aria-hidden="true">←</span></a><?php endif; ?>
        </div>
      </div>
    </div>
  </section>
