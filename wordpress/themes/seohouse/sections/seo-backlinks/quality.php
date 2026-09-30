<?php
/**
 * Section "Quality" — SEO House - Backlinks.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'quality')) ?>" data-screen-label="Quality" style="position: relative; scroll-margin-top: 88px; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head data-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <?php if (!empty($f['text'])) : ?><p data-center style="font-size: 16.5px; color: var(--sh-muted); margin: 14px 0px 0px; max-width: 48em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>      <div style="margin-top: clamp(24px, 2.8vw, 38px); display: flex; flex-direction: column;">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_7a48bf15 = ['v1' => ['flex: 0 0 auto; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: var(--sh-lime);', 'font-size: 13px; font-weight: 600; color: var(--sh-lime);'], 'v2' => ['flex: 0 0 auto; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: var(--sh-sky);', 'font-size: 13px; font-weight: 600; color: var(--sh-dim);']]; $vk_7a48bf15 = $vt_7a48bf15[$r1['variant'] ?? 'v1'] ?? $vt_7a48bf15['v1']; ?><div data-prow style="padding: 18px 4px; border-top: 1px solid rgba(255, 255, 255, 0.12);">
          <span style="display: flex; align-items: center; gap: 12px; min-width: 0px;">
            <span aria-hidden="true" style="<?= esc_attr($vk_7a48bf15[0] ?? '') ?>"><?= esc_html(sprintf('%02d', $i1 + 1)) ?></span>
            <?php if (!empty($r1['heading'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17.5px;"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
          </span>
          <?php if (!empty($r1['text'])) : ?><span style="font-size: 15px; color: var(--sh-muted); text-wrap: pretty;"><?= esc_html($r1['text'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($r1['eyebrow'])) : ?><span style="<?= esc_attr($vk_7a48bf15[1] ?? '') ?>"><?= esc_html($r1['eyebrow'] ?? '') ?></span><?php endif; ?>
        </div><?php endforeach; ?>
      </div>
    </div>
  </section>
