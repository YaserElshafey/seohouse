<?php
/**
 * Section "Human" — SEO House - SEO Content.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Human" style="position: relative; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-g2s>
        <div>
      <div data-sec-head>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <?php if (!empty($f['text'])) : ?><p style="font-size: 16.5px; color: var(--sh-muted); margin: 14px 0px 0px; max-width: 48em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        </div>
        <div style="display: flex; flex-direction: column; gap: 12px;">
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="<?= esc_attr($i1 === 0 ? 'border-radius: 16px; background: rgba(255, 255, 255, 0.04); padding: 18px 20px; border-inline-start: 2px solid rgba(var(--sh-sky-rgb), 0.5);' : 'border-radius: 16px; background: rgba(255, 255, 255, 0.04); padding: 18px 20px; border-inline-start: 2px solid var(--sh-lime);') ?>">
            <?php if (!empty($r1['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17px;"><?= esc_html($r1['heading'] ?? '') ?></div><?php endif; ?>
            <?php if (!empty($r1['text'])) : ?><div style="font-size: 15px; color: var(--sh-muted); margin-top: 8px;"><?= esc_html($r1['text'] ?? '') ?></div><?php endif; ?>
          </div><?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
