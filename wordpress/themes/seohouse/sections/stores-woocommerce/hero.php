<?php
/**
 * Section "Hero" — SEO House - WooCommerce.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Hero" style="position: relative; overflow: hidden; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
    <div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px); background-size: 72px 72px; mask-image: radial-gradient(90% 100% at 18% 0%, rgb(0, 0, 0), transparent 68%); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: 18px 20px 0px;">
      <?php sh_breadcrumbs(); ?>
    </div>

    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(22px, 2.6vw, 38px) 20px clamp(36px, 4vw, 56px);">
      <div style="max-width: 38em; animation: 0.7s ease 0s 1 normal both running fadeUp;">
        <?= sh_svg_img($f['logo'] ?? '', 'ووكومرس', ['style' => 'height: 16px; width: auto; display: block; opacity: 0.85;']) ?>
        <?php if (!empty($f['title'])) : ?><h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(28px, 3.2vw, 44px); line-height: 1.3; margin: 14px 0px 0px;"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 17px; line-height: 1.85; color: var(--sh-muted); margin: 16px 0px 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 14px 24px; margin-top: 26px;">
          <?php if (!empty($f['link_label'])) : ?><a href="#booking" class="hv-d5cb1d" style="background: var(--sh-lime); color: var(--sh-ink); font-weight: 700; font-size: 16.5px; min-height: 56px; display: inline-flex; align-items: center; padding: 0px 28px; border-radius: 14px;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
          <a href="#build" data-ghost style="min-height: 56px; display: inline-flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 600; color: var(--sh-text); padding: 0px 16px; border-radius: 14px;"><?= esc_html($f['link_label_2'] ?? '') ?> <i data-ghost-arrow aria-hidden="true">↓</i></a>
        </div>
      </div>

      <div data-wc-join style="margin-top: clamp(28px, 3.2vw, 44px); display: grid; gap: 0px; align-items: stretch; animation: 0.7s ease 0.12s 1 normal both running fadeUp;">
        <div style="border-radius: 16px 0px 0px 16px; background: rgba(255, 255, 255, 0.04); padding: clamp(18px, 2.2vw, 24px);">
          <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 12.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
          <div style="margin-top: 12px; display: flex; flex-direction: column; gap: 8px;">
            <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty($r1['label'])) : ?><div style="font-size: 14.5px; color: var(--sh-crumb-current); background: rgba(255, 255, 255, 0.04); border-radius: 9px; padding: 10px 12px;"><?= esc_html($r1['label'] ?? '') ?></div><?php endif; ?><?php endforeach; ?>
          </div>
        </div>
        <div data-wc-mid style="display: flex; align-items: center; justify-content: center; padding: 10px;">
          <span aria-hidden="true" style="width: 46px; height: 46px; border-radius: 999px; background: rgba(var(--sh-lime-rgb), 0.14); color: var(--sh-lime); display: flex; align-items: center; justify-content: center; font-size: 19px;">⇄</span>
        </div>
        <div style="border-radius: 0px 16px 16px 0px; background: rgba(255, 255, 255, 0.04); padding: clamp(18px, 2.2vw, 24px);">
          <?php if (!empty($f['eyebrow_2'])) : ?><div style="font-size: 12.5px; font-weight: 600; color: var(--sh-lime);"><?= esc_html($f['eyebrow_2'] ?? '') ?></div><?php endif; ?>
          <div style="margin-top: 12px; display: flex; flex-direction: column; gap: 8px;">
            <?php $r1_list = $f['items_2'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty($r1['label'])) : ?><div style="font-size: 14.5px; color: var(--sh-crumb-current); background: rgba(255, 255, 255, 0.04); border-radius: 9px; padding: 10px 12px;"><?= esc_html($r1['label'] ?? '') ?></div><?php endif; ?><?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
