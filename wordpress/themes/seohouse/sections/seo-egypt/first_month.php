<?php
/**
 * Section "First month" — SEO House - Egypt SEO Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="First month" style="background: var(--sh-surface);">
    <div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head data-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <?php if (!empty($f['text'])) : ?><p data-center style="font-size: 16.5px; color: var(--sh-muted); margin: 14px 0px 0px; max-width: 46em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>

      <div data-eg-first style="margin-top: clamp(24px, 2.8vw, 36px); display: grid; gap: 16px;">
        
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="<?= esc_attr($i1 === 0 ? 'display: flex; flex-direction: column; gap: 10px; padding-top: 14px; border-top: 2px solid var(--sh-lime);' : 'display: flex; flex-direction: column; gap: 10px; padding-top: 14px; border-top: 2px solid rgba(var(--sh-sky-rgb), 0.35);') ?>">
            <span style="<?= esc_attr($i1 === 0 ? 'font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: var(--sh-lime);' : 'font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: var(--sh-sky);') ?>"><?= esc_html(sprintf('%02d', $i1 + 1)) ?></span>
            <?php if (!empty($r1['text'])) : ?><span style="font-size: 15.5px; color: var(--sh-text); text-wrap: pretty;"><?= esc_html($r1['text'] ?? '') ?></span><?php endif; ?>
          </div><?php endforeach; ?>
        
      </div>
    </div>
  </section>
