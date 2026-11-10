<?php
/**
 * Section "Phases" — SEO House - Online Stores.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Phases" style="position: relative; background: var(--sh-bg); border-bottom: 1px solid var(--sh-line);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <div style="position: relative; margin-top: clamp(26px, 3vw, 40px);">
        <span aria-hidden="true" data-railline></span>
        <div data-rail5 style="--n: 5;">
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div data-railstep style="position: relative;">
            <span aria-hidden="true" data-raildot style="display: block; width: 15px; height: 15px; border-radius: 999px; background: var(--sh-surface); border: 2px solid var(--sh-blue);"></span>
            <div style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12px; color: var(--sh-link); margin-top: 14px;"><?= esc_html(sprintf('%02d', $i1 + 1)) ?></div>
            <?php if (!empty($r1['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17px; margin-top: 8px;"><?= esc_html($r1['heading'] ?? '') ?></div><?php endif; ?>
            <?php if (!empty($r1['label'])) : ?><div style="font-size: 14.5px; color: var(--sh-ink); margin-top: 7px; text-wrap: pretty;"><?= esc_html($r1['label'] ?? '') ?></div><?php endif; ?>
          </div><?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
