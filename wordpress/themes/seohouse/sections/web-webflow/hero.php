<?php
/**
 * Section "Hero" — SEO House - Webflow.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Hero" style="position: relative; overflow: hidden; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
    <div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px); background-size: 72px 72px; mask-image: radial-gradient(90% 100% at 82% 0%, rgb(0, 0, 0), transparent 68%); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: 18px 20px 0px;">
      <?php sh_breadcrumbs(); ?>
    </div>

    <div data-wf-hero style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(22px, 2.6vw, 38px) 20px clamp(36px, 4vw, 56px); display: grid; gap: clamp(24px, 3vw, 44px); align-items: center;">
      <div style="animation: 0.7s ease 0s 1 normal both running fadeUp;">
        <?= sh_svg_img($f['logo'] ?? '', 'ويب فلو', ['style' => 'height: 22px; width: auto; display: block; opacity: 0.85;'], (int) ($f['logo_image'] ?? 0)) ?>
        <?php if (!empty($f['title'])) : ?><h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(28px, 3.2vw, 44px); line-height: 1.3; margin: 14px 0px 0px; max-width: 19em;"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 17px; line-height: 1.85; color: var(--sh-muted); max-width: 36em; margin: 16px 0px 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 14px 24px; margin-top: 26px;">
          <?php if (!empty($f['link_label'])) : ?><a href="#booking" class="hv-d5cb1d" style="background: var(--sh-lime); color: var(--sh-ink); font-weight: 700; font-size: 16.5px; min-height: 56px; display: inline-flex; align-items: center; padding: 0px 28px; border-radius: 14px;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
          <a href="#build" data-ghost style="min-height: 56px; display: inline-flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 600; color: var(--sh-text); padding: 0px 16px; border-radius: 14px;"><?= esc_html($f['link_label_2'] ?? '') ?> <i data-ghost-arrow aria-hidden="true">↓</i></a>
        </div>
      </div>

      <div style="animation: 0.7s ease 0.12s 1 normal both running fadeUp; border-radius: 16px; background: var(--sh-surface); box-shadow: rgba(0, 0, 0, 0.9) 0px 24px 54px -34px; overflow: hidden;">
        <div style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); font-size: 11.5px; color: var(--sh-crumb);">
          <?php if (!empty($f['label'])) : ?><span style="color: var(--sh-lime);"><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?><?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty($r1['label'])) : ?><span><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?><?php endforeach; ?>
        </div>
        <div style="padding: 14px 16px; display: flex; flex-direction: column; gap: 6px;">
          <?php $r1_list = $f['items_2'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_367e3344 = ['v1' => ['display: flex; align-items: center; gap: 9px; padding: 8px 10px; border-radius: 8px; background: transparent; padding-inline-start: 10px;', 'width: 6px; height: 6px; border-radius: 2px; background: rgba(var(--sh-sky-rgb), 0.55);', 'font-size: 13.5px; color: var(--sh-crumb-current); direction: ltr;'], 'v2' => ['display: flex; align-items: center; gap: 9px; padding: 8px 10px; border-radius: 8px; background: transparent; padding-inline-start: 26px;', 'width: 6px; height: 6px; border-radius: 2px; background: rgba(var(--sh-sky-rgb), 0.55);', 'font-size: 13.5px; color: var(--sh-crumb-current); direction: ltr;'], 'v3' => ['display: flex; align-items: center; gap: 9px; padding: 8px 10px; border-radius: 8px; background: transparent; padding-inline-start: 42px;', 'width: 6px; height: 6px; border-radius: 2px; background: rgba(var(--sh-sky-rgb), 0.55);', 'font-size: 13.5px; color: var(--sh-crumb-current); direction: ltr;'], 'v4' => ['display: flex; align-items: center; gap: 9px; padding: 8px 10px; border-radius: 8px; background: rgba(var(--sh-lime-rgb), 0.12); padding-inline-start: 58px;', 'width: 6px; height: 6px; border-radius: 2px; background: var(--sh-lime);', 'font-size: 13.5px; color: var(--sh-lime); direction: ltr;'], 'v5' => ['display: flex; align-items: center; gap: 9px; padding: 8px 10px; border-radius: 8px; background: transparent; padding-inline-start: 58px;', 'width: 6px; height: 6px; border-radius: 2px; background: rgba(var(--sh-sky-rgb), 0.55);', 'font-size: 13.5px; color: var(--sh-crumb-current); direction: ltr;']]; $vk_367e3344 = $vt_367e3344[$r1['variant'] ?? 'v1'] ?? $vt_367e3344['v1']; ?><div style="<?= esc_attr($vk_367e3344[0] ?? '') ?>">
            <span aria-hidden="true" style="<?= esc_attr($vk_367e3344[1] ?? '') ?>"></span>
            <?php if (!empty($r1['label'])) : ?><span style="<?= esc_attr($vk_367e3344[2] ?? '') ?>"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
          </div><?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
