<?php
/**
 * Section "Principles" — SEO House - About.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'principles')) ?>" data-screen-label="Principles" style="position: relative; background: var(--sh-surface); color: var(--sh-ink); scroll-margin-top: 88px; border-bottom: 1px solid var(--sh-line);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head data-sh-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <div data-ab-four style="margin-top: clamp(22px, 2.6vw, 32px); display: grid; gap: 18px 30px;">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_07b63cb4 = ['v1' => ['flex: 0 0 auto; margin-top: 6px; width: 8px; height: 8px; border-radius: 2px; background: var(--sh-blue);'], 'v2' => ['flex: 0 0 auto; margin-top: 6px; width: 8px; height: 8px; border-radius: 2px; background: rgba(40, 84, 232, 0.45);']]; $vk_07b63cb4 = $vt_07b63cb4[$r1['variant'] ?? 'v1'] ?? $vt_07b63cb4['v1']; ?><div style="display: flex; align-items: flex-start; gap: 12px;">
          <span aria-hidden="true" style="<?= esc_attr($vk_07b63cb4[0] ?? '') ?>"></span>
          <span style="min-width: 0px;"><?php if (!empty($r1['heading'])) : ?><span style="display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16.5px;"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?><?php if (!empty($r1['text'])) : ?><span style="display: block; font-size: 15px; color: var(--sh-text); margin-top: 5px; text-wrap: pretty;"><?= esc_html($r1['text'] ?? '') ?></span><?php endif; ?></span>
        </div><?php endforeach; ?>
      </div>
    </div>
  </section>
