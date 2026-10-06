<?php
/**
 * Section "System" — SEO House - WordPress.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'system')) ?>" data-screen-label="System" style="position: relative; scroll-margin-top: 88px; border-bottom: 1px solid var(--sh-line);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head data-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <?php if (!empty($f['text'])) : ?><p data-center style="font-size: 16.5px; color: var(--sh-ink); margin: 14px 0px 0px; max-width: 48em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>      <div data-g3 style="margin-top: clamp(22px, 2.6vw, 34px);">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="border-radius: 16px; background: var(--sh-surface); padding: 18px 20px;">
          <?php if (!empty($r1['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17.5px;"><?= esc_html($r1['heading'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($r1['label'])) : ?><div style="font-size: 14.5px; color: var(--sh-ink); margin-top: 8px; text-wrap: pretty;"><?= esc_html($r1['label'] ?? '') ?></div><?php endif; ?>
        </div><?php endforeach; ?>
      </div>
    </div>
  </section>
