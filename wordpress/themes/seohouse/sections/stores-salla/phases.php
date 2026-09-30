<?php
/**
 * Section "Phases" — SEO House - Salla.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Phases" style="position: relative; background: var(--sh-surface); border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head data-sh-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <div data-sa-steps style="margin-top: clamp(24px, 2.8vw, 36px); display: grid; gap: 0px clamp(28px, 3.4vw, 60px);">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="display: flex; align-items: flex-start; gap: 16px; padding: 18px 0px; border-top: 1px solid rgba(255, 255, 255, 0.12);">
          <span style="<?= esc_attr($i1 === 0 ? 'flex: 0 0 auto; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 26px; line-height: 1; color: var(--sh-lime);' : 'flex: 0 0 auto; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 26px; line-height: 1; color: rgba(var(--sh-sky-rgb), 0.5);') ?>"><?= esc_html(sprintf('%02d', $i1 + 1)) ?></span>
          <span style="min-width: 0px;"><?php if (!empty($r1['heading'])) : ?><span style="display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17.5px;"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?><?php if (!empty($r1['text'])) : ?><span style="display: block; font-size: 15px; color: var(--sh-muted); margin-top: 6px;"><?= esc_html($r1['text'] ?? '') ?></span><?php endif; ?></span>
        </div><?php endforeach; ?>
      </div>
    </div>
  </section>
