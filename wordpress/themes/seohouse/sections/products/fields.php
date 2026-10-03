<?php
/**
 * Section "Fields" — SEO House - Product Upload.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Fields" style="position: relative; overflow: hidden; background: linear-gradient(160deg, rgb(15, 31, 102) 0%, rgb(10, 19, 64) 60%, var(--sh-ink) 100%); border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
    <div aria-hidden="true" style="position: absolute; inset: 0px; opacity: 0.06; background-image: radial-gradient(var(--sh-sky) 1px, transparent 1px); background-size: 24px 24px; pointer-events: none;"></div>
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 3.8vw, 50px) 20px;">
      <div data-sec-head data-sh-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-lime);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.3;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <div data-pu-fields style="margin-top: 24px; display: grid; gap: 18px 26px;">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="display: flex; align-items: flex-start; gap: 11px;"><span style="flex: 0 0 auto; width: 34px; height: 34px; border-radius: 10px; background: rgba(var(--sh-blue-rgb), 0.18); display: flex; align-items: center; justify-content: center;"><?= sh_icon($r1['icon'] ?? 'i59b0f2e2') ?></span><span style="min-width: 0px;"><?php if (!empty($r1['heading'])) : ?><span style="display: block; font-weight: 700; font-size: 15px; color: var(--sh-text);"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?><?php if (!empty($r1['label'])) : ?><span style="display: block; font-size: 13.5px; line-height: 1.7; color: var(--sh-muted); margin-top: 3px;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?></span></div><?php endforeach; ?>
      </div>
    </div>
  </section>
