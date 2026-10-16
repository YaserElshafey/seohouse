<?php
/**
 * Section "First month" — SEO House - Saudi SEO Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="First month" style="position: relative; background: var(--sh-bg); overflow: hidden;">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head data-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <?php if (!empty($f['text'])) : ?><p data-center style="font-size: 16.5px; color: var(--sh-ink); margin: 14px 0px 0px; max-width: 48em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>

      <div style="position: relative; margin-top: clamp(26px, 3vw, 40px);">
        <span aria-hidden="true" data-ksa-frail></span>
        <div data-ksa-first style="display: grid; gap: 20px 22px;">
          
            <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div data-ksa-fstep style="position: relative;">
              <span aria-hidden="true" data-ksa-fdot style="<?= esc_attr($i1 === 0 ? 'display: block; width: 15px; height: 15px; border-radius: 999px; background: var(--sh-surface); border: 2px solid rgb(40, 84, 232);' : 'display: block; width: 15px; height: 15px; border-radius: 999px; background: var(--sh-surface); border: 2px solid var(--sh-sky);') ?>"></span>
              <div style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12px; color: rgb(33, 72, 216); margin-top: 14px;"><?= esc_html(sprintf('%02d', $i1 + 1)) ?></div>
              <?php if (!empty($r1['text'])) : ?><div style="font-size: 15.5px; color: var(--sh-ink); margin-top: 8px; text-wrap: pretty;"><?= esc_html($r1['text'] ?? '') ?></div><?php endif; ?>
            </div><?php endforeach; ?>
          
        </div>
      </div>
    </div>
  </section>
