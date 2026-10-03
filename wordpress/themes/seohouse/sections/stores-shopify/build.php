<?php
/**
 * Section "Build" — SEO House - Shopify.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'build')) ?>" data-screen-label="Build" style="position: relative; scroll-margin-top: 88px; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>      <div style="display: flex; flex-direction: column;">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div data-sh-def style="display: grid; gap: 6px 28px; padding: 17px 4px; border-top: 1px solid rgba(255, 255, 255, 0.12);">
          <?php if (!empty($r1['heading'])) : ?><span style="<?= esc_attr($i1 === 0 ? 'font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17px; color: var(--sh-lime);' : 'font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17px; color: var(--sh-text);') ?>"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($r1['text'])) : ?><span style="font-size: 15px; color: var(--sh-muted); text-wrap: pretty;"><?= esc_html($r1['text'] ?? '') ?></span><?php endif; ?>
        </div><?php endforeach; ?>
      </div>
    </div>
  </section>
