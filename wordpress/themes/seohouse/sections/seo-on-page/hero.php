<?php
/**
 * Section "Hero" — SEO House - On-Page SEO.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Hero" style="position: relative; overflow: hidden;">
    <div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px); background-size: 72px 72px; mask-image: radial-gradient(90% 100% at 50% 0%, rgb(0, 0, 0), transparent 68%); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: 18px 20px 0px;">
      <?php sh_breadcrumbs(); ?>
    </div>

    <div data-g2 style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(24px, 2.8vw, 42px) 20px clamp(38px, 4.2vw, 60px);">
      <div style="animation: 0.7s ease 0s 1 normal both running fadeUp;">
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(28px, 3.2vw, 45px); line-height: 1.3; margin: 12px 0px 0px; max-width: 21em;"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 17.5px; line-height: 1.85; color: var(--sh-muted); max-width: 37em; margin: 18px 0px 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 14px 26px; margin-top: 28px;">
          <?php if (!empty($f['link_label'])) : ?><a href="#booking" class="hv-d5cb1d" style="background: var(--sh-lime); color: var(--sh-ink); font-weight: 700; font-size: 16.5px; min-height: 56px; display: inline-flex; align-items: center; justify-content: center; padding: 0px 28px; border-radius: 14px;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
          <a href="#layers" data-ghost style="min-height: 56px; display: inline-flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 600; color: var(--sh-text); padding: 0px 16px; border-radius: 14px;"><?= esc_html($f['link_label_2'] ?? '') ?> <i data-ghost-arrow aria-hidden="true">↓</i></a>
        </div>
      </div>
      <div style="animation: 0.7s ease 0.12s 1 normal both running fadeUp;">
        <div style="border-radius: 20px; background: linear-gradient(150deg, var(--sh-surface), var(--sh-surface)); padding: clamp(18px, 2.2vw, 24px); box-shadow: rgba(0, 0, 0, 0.9) 0px 24px 54px -36px;">
          <?php if (!empty($f['label'])) : ?><div style="border-radius: 10px; background: rgba(var(--sh-lime-rgb), 0.1); border: 1px solid rgba(var(--sh-lime-rgb), 0.28); padding: 11px 14px; font-size: 14px; color: var(--sh-lime);"><?= esc_html($f['label'] ?? '') ?></div><?php endif; ?>
          <div style="margin-top: 12px; border-radius: 12px; background: rgba(255, 255, 255, 0.05); padding: 14px;">
            <div style="height: 9px; width: 78%; border-radius: 4px; background: rgba(var(--sh-text-rgb), 0.75);"></div>
            <div style="height: 6px; width: 52%; border-radius: 4px; background: rgba(var(--sh-sky-rgb), 0.5); margin-top: 9px;"></div>
            <div style="margin-top: 14px; display: flex; flex-direction: column; gap: 7px;">
              <div style="height: 5px; width: 100%; border-radius: 3px; background: rgba(var(--sh-text-rgb), 0.18);"></div>
              <div style="height: 5px; width: 94%; border-radius: 3px; background: rgba(var(--sh-text-rgb), 0.18);"></div>
              <div style="height: 5px; width: 70%; border-radius: 3px; background: rgba(var(--sh-text-rgb), 0.18);"></div>
            </div>
            <div style="margin-top: 14px; display: flex; flex-wrap: wrap; gap: 7px;">
              <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty($r1['label'])) : ?><span style="font-size: 12px; color: var(--sh-sky); background: rgba(var(--sh-sky-rgb), 0.12); border-radius: 7px; padding: 6px 10px;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?><?php endforeach; ?>
            </div>
          </div>
          <?php if (!empty($f['label_2'])) : ?><div style="margin-top: 12px; border-radius: 10px; background: rgba(var(--sh-lime-rgb), 0.14); padding: 11px 14px; font-size: 14px; color: var(--sh-lime); text-align: center;"><?= esc_html($f['label_2'] ?? '') ?></div><?php endif; ?>
        </div></div>
    </div>
  </section>
