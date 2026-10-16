<?php
/**
 * Section "Hero" — SEO House - Shopify.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Hero" data-hero-blue style="position: relative; overflow: hidden; border-bottom: 1px solid rgba(255, 255, 255, 0.1); background: linear-gradient(rgb(46, 90, 240) 0%, rgb(40, 84, 232) 60%, rgb(36, 76, 214) 100%); color: rgb(255, 255, 255);">
    <div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(255, 255, 255, 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.9) 1px, transparent 1px); background-size: 72px 72px; mask-image: radial-gradient(90% 100% at 30% 0%, rgb(0, 0, 0), transparent 68%); pointer-events: none;"></div>

    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: 18px 20px 0px;">
      <?php sh_breadcrumbs(); ?>
    </div>

    <div style="position: relative; max-width: 1120px; margin: 0px auto; padding: clamp(20px, 2.4vw, 34px) 20px clamp(0px, 1vw, 10px); text-align: center; animation: 0.7s ease 0s 1 normal both running fadeUp;">
      <?= sh_svg_img($f['logo'] ?? '', 'شوبيفاي', ['style' => 'height: 22px; width: auto; display: inline-block; opacity: 0.85;'], (int) ($f['logo_image'] ?? 0)) ?>
      <?php if (!empty($f['title'])) : ?><h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(28px, 3.2vw, 44px); line-height: 1.3; margin: 14px auto 0px; max-width: 18em;"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
      <?php if (!empty($f['text'])) : ?><p style="font-size: 17px; line-height: 1.85; color: rgb(255, 255, 255); max-width: 40em; margin: 16px auto 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
      <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 14px 24px; margin-top: 26px;">
        <?php if (!empty($f['link_label'])) : ?><a href="#booking" data-hero-cta style="background: rgb(255, 255, 255); color: rgb(33, 72, 216); font-weight: 700; font-size: 16.5px; min-height: 56px; display: inline-flex; align-items: center; padding: 0px 28px; border-radius: 14px;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
        <a href="#build" data-hero-sec style="min-height: 56px; display: inline-flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 600; color: rgb(255, 255, 255); padding: 0px 16px; border-radius: 14px;"><?= esc_html($f['link_label_2'] ?? '') ?> <i data-ghost-arrow aria-hidden="true">↓</i></a>
      </div>
    </div>

    <div style="position: relative; max-width: 1060px; margin: clamp(26px, 3vw, 40px) auto 0px; padding: 0px 20px; animation: 0.7s ease 0.14s 1 normal both running fadeUp;">
      <div style="border-radius: 18px 18px 0px 0px; background: rgb(11, 20, 56); box-shadow: rgba(0, 0, 0, 0.9) 0px -24px 60px -34px; overflow: hidden;">
        <div style="display: flex; align-items: center; gap: 8px; padding: 12px 16px; border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
          <span aria-hidden="true" style="width: 9px; height: 9px; border-radius: 999px; background: rgba(255, 255, 255, 0.22);"></span>
          <span aria-hidden="true" style="width: 9px; height: 9px; border-radius: 999px; background: rgba(255, 255, 255, 0.16);"></span>
          <span aria-hidden="true" style="width: 9px; height: 9px; border-radius: 999px; background: rgba(255, 255, 255, 0.12);"></span>
          <?php if (!empty($f['label'])) : ?><span style="margin-inline-start: auto; font-size: 11.5px; color: rgb(255, 255, 255);"><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?>
        </div>
        <div style="padding: 16px; display: flex; flex-direction: column; gap: 10px;">
          <div style="display: flex; align-items: center; gap: 10px; border-radius: 10px; background: rgba(255, 255, 255, 0.09); border: 1px dashed rgba(255, 255, 255, 0.32); padding: 13px 15px;">
            <?php if (!empty($f['eyebrow'])) : ?><span style="font-size: 11.5px; color: rgb(255, 255, 255); font-weight: 700;"><?= esc_html($f['eyebrow'] ?? '') ?></span><?php endif; ?><?php if (!empty($f['label_2'])) : ?><span style="font-size: 14.5px; color: rgb(255, 255, 255);"><?= esc_html($f['label_2'] ?? '') ?></span><?php endif; ?>
          </div>
          <div data-sh-grid style="display: grid; gap: 10px;">
            <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty($r1['label'])) : ?><div style="border-radius: 10px; background: rgba(255, 255, 255, 0.05); border: 1px dashed rgba(var(--sh-sky-rgb), 0.28); padding: 13px 15px; font-size: 14.5px; color: rgb(255, 255, 255);"><?= esc_html($r1['label'] ?? '') ?></div><?php endif; ?><?php endforeach; ?>
          </div>
          <?php if (!empty($f['label_3'])) : ?><div style="border-radius: 10px; background: rgba(255, 255, 255, 0.04); border: 1px dashed rgba(var(--sh-sky-rgb), 0.2); padding: 13px 15px; font-size: 14.5px; color: rgb(255, 255, 255);"><?= esc_html($f['label_3'] ?? '') ?></div><?php endif; ?>
        </div>
      </div>
    </div>
  </section>
