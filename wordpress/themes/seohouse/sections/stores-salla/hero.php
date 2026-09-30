<?php
/**
 * Section "Hero" — SEO House - Salla.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Hero" style="position: relative; overflow: hidden; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
    <div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px); background-size: 72px 72px; mask-image: radial-gradient(90% 100% at 30% 0%, rgb(0, 0, 0), transparent 68%); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: 18px 20px 0px;">
      <?php sh_breadcrumbs(); ?>
    </div>

    <div data-sa-hero style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(24px, 2.8vw, 42px) 20px clamp(38px, 4.2vw, 58px); display: grid; gap: clamp(26px, 3vw, 50px); align-items: center;">
      <div style="animation: 0.7s ease 0s 1 normal both running fadeUp;">
        <?= sh_svg_img($f['logo'] ?? '', 'سلة', ['style' => 'height: 22px; width: auto; display: block; opacity: 0.85;']) ?>
        <?php if (!empty($f['title'])) : ?><h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(28px, 3.2vw, 44px); line-height: 1.3; margin: 14px 0px 0px; max-width: 19em;"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 17px; line-height: 1.85; color: var(--sh-muted); max-width: 36em; margin: 16px 0px 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 14px 24px; margin-top: 26px;">
          <?php if (!empty($f['link_label'])) : ?><a href="#booking" class="hv-d5cb1d" style="background: var(--sh-lime); color: var(--sh-ink); font-weight: 700; font-size: 16.5px; min-height: 56px; display: inline-flex; align-items: center; padding: 0px 28px; border-radius: 14px;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
          <a href="#setup" data-ghost style="min-height: 56px; display: inline-flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 600; color: var(--sh-text); padding: 0px 16px; border-radius: 14px;"><?= esc_html($f['link_label_2'] ?? '') ?> <i data-ghost-arrow aria-hidden="true">↓</i></a>
        </div>
      </div>

      <div style="animation: 0.7s ease 0.12s 1 normal both running fadeUp; border-radius: 18px; background: rgb(251, 252, 254); color: var(--sh-ink); padding: clamp(16px, 2vw, 22px); box-shadow: rgba(0, 0, 0, 0.85) 0px 26px 58px -34px;">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; padding-bottom: 12px; border-bottom: 1px dashed rgba(var(--sh-ink-rgb), 0.18);">
          <?php if (!empty($f['label'])) : ?><span style="font-size: 12.5px; color: var(--sh-slate);"><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($f['eyebrow'])) : ?><span style="font-size: 11.5px; font-weight: 700; color: var(--sh-blue); background: rgba(var(--sh-blue-rgb), 0.08); border-radius: 999px; padding: 5px 10px;"><?= esc_html($f['eyebrow'] ?? '') ?></span><?php endif; ?>
        </div>
        <div style="padding: 14px 0px; display: flex; flex-direction: column; gap: 10px;">
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="display: flex; align-items: center; gap: 10px; font-size: 14.5px;">
            <span aria-hidden="true" style="flex: 0 0 auto; width: 18px; height: 18px; border-radius: 999px; background: rgba(var(--sh-blue-rgb), 0.1); color: var(--sh-blue); display: flex; align-items: center; justify-content: center; font-size: 11px;">✓</span>
            <?php if (!empty($r1['label'])) : ?><span style="flex: 1 1 auto; font-weight: 600; color: var(--sh-ink);"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
            <?php if (!empty($r1['label_2'])) : ?><span style="font-size: 13px; color: var(--sh-slate);"><?= esc_html($r1['label_2'] ?? '') ?></span><?php endif; ?>
          </div><?php endforeach; ?>
        </div>
        <div style="border-top: 1px dashed rgba(var(--sh-ink-rgb), 0.18); padding-top: 12px; display: flex; align-items: center; justify-content: space-between;">
          <?php if (!empty($f['label_2'])) : ?><span style="font-size: 13px; color: var(--sh-slate);"><?= esc_html($f['label_2'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($f['eyebrow_2'])) : ?><span style="font-size: 14px; font-weight: 700; color: rgb(47, 90, 0); background: rgba(var(--sh-lime-rgb), 0.35); border-radius: 999px; padding: 6px 12px;"><?= esc_html($f['eyebrow_2'] ?? '') ?></span><?php endif; ?>
        </div>
      </div>
    </div>
  </section>
