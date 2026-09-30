<?php
/**
 * Section "Pick" — SEO House - Sectors.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Pick" style="position: relative; background: var(--sh-paper); color: var(--sh-ink); border-bottom: 1px solid rgba(var(--sh-ink-rgb), 0.08);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head data-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-blue);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <?php if (!empty($f['text'])) : ?><p data-center style="font-size: 16.5px; color: var(--sh-ink-soft); margin: 14px 0px 0px; max-width: 48em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
      <div data-sc-pick style="margin-top: clamp(22px, 2.6vw, 32px); display: grid; gap: 18px 30px;">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_4d90de82 = ['v1' => ['flex: 0 0 auto; margin-top: 7px; width: 8px; height: 8px; border-radius: 2px; background: var(--sh-blue);'], 'v2' => ['flex: 0 0 auto; margin-top: 7px; width: 8px; height: 8px; border-radius: 2px; background: rgba(var(--sh-blue-rgb), 0.45);']]; $vk_4d90de82 = $vt_4d90de82[$r1['variant'] ?? 'v1'] ?? $vt_4d90de82['v1']; ?><div style="display: flex; align-items: flex-start; gap: 12px;">
          <span aria-hidden="true" style="<?= esc_attr($vk_4d90de82[0] ?? '') ?>"></span>
          <span style="min-width: 0px;"><?php if (!empty($r1['heading'])) : ?><span style="display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16.5px;"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?><?php if (!empty($r1['text'])) : ?><span style="display: block; font-size: 15px; color: var(--sh-ink-soft); margin-top: 5px;"><?= esc_html($r1['text'] ?? '') ?></span><?php endif; ?></span>
        </div><?php endforeach; ?>
      </div>
      <?php if (!empty(sh_link($f['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" style="display: inline-flex; align-items: center; gap: 8px; margin-top: 24px; font-size: 15px; font-weight: 600; color: var(--sh-blue); border-bottom: 1px solid rgba(var(--sh-blue-rgb), 0.4); padding-bottom: 3px;"><?= esc_html($f['link_label'] ?? '') ?> <span aria-hidden="true">←</span></a><?php endif; ?>
    </div>
  </section>
