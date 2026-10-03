<?php
/**
 * Section "Honest" — SEO House - Backlinks.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Honest" style="position: relative; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div style="border-radius: 18px; background: rgba(255, 255, 255, 0.04); padding: clamp(20px, 2.4vw, 28px); border-inline-start: 2px solid var(--sh-lime);">
        <?php if (!empty($f['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18.5px;"><?= esc_html($f['heading'] ?? '') ?></div><?php endif; ?>
        <div style="margin-top: 14px; display: flex; flex-direction: column; gap: 10px;">
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="display: flex; gap: 11px; font-size: 15.5px; color: var(--sh-crumb-current);"><span aria-hidden="true" style="flex: 0 0 auto; margin-top: 7px; width: 7px; height: 7px; border-radius: 999px; background: var(--sh-lime);"></span><?php if (!empty($r1['label'])) : ?><span style="text-wrap: pretty;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?></div><?php endforeach; ?>
        </div>
      </div>
      <?php if (!empty(sh_link($f['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" style="display: inline-flex; align-items: center; gap: 8px; margin-top: 24px; font-size: 15px; font-weight: 600; border-bottom: 1px solid rgba(var(--sh-sky-rgb), 0.5); padding-bottom: 3px;"><?= esc_html($f['link_label'] ?? '') ?> <span aria-hidden="true">←</span></a><?php endif; ?>
    </div>
  </section>
