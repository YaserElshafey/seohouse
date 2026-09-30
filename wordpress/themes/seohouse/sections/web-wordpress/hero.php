<?php
/**
 * Section "Hero" — SEO House - WordPress.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Hero" style="position: relative; overflow: hidden; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
    <div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px); background-size: 72px 72px; mask-image: radial-gradient(90% 100% at 70% 10%, rgb(0, 0, 0), transparent 68%); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: 18px 20px 0px;">
      <?php sh_breadcrumbs(); ?>
    </div>

    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(22px, 2.6vw, 38px) 20px clamp(36px, 4vw, 56px);">
      <div style="max-width: 38em; animation: 0.7s ease 0s 1 normal both running fadeUp;">
        <?= sh_svg_img($f['logo'] ?? '', 'ووردبريس', ['style' => 'height: 22px; width: auto; display: block; opacity: 0.85;']) ?>
        <?php if (!empty($f['title'])) : ?><h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(28px, 3.2vw, 44px); line-height: 1.3; margin: 14px 0px 0px;"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 17px; line-height: 1.85; color: var(--sh-muted); margin: 16px 0px 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 14px 24px; margin-top: 26px;">
          <?php if (!empty($f['link_label'])) : ?><a href="#booking" class="hv-d5cb1d" style="background: var(--sh-lime); color: var(--sh-ink); font-weight: 700; font-size: 16.5px; min-height: 56px; display: inline-flex; align-items: center; padding: 0px 28px; border-radius: 14px;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
          <a href="#system" data-ghost style="min-height: 56px; display: inline-flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 600; color: var(--sh-text); padding: 0px 16px; border-radius: 14px;"><?= esc_html($f['link_label_2'] ?? '') ?> <i data-ghost-arrow aria-hidden="true">↓</i></a>
        </div>
      </div>

      <div data-wp-canvas style="margin-top: clamp(28px, 3.2vw, 44px); display: grid; gap: 14px; align-items: start; animation: 0.7s ease 0.12s 1 normal both running fadeUp;">
        <div style="border-radius: 16px; background: rgba(255, 255, 255, 0.04); padding: 16px;">
          <?php if (!empty($f['label'])) : ?><div style="font-size: 12px; color: var(--sh-crumb); padding-bottom: 10px; border-bottom: 1px solid rgba(255, 255, 255, 0.08);"><?= esc_html($f['label'] ?? '') ?></div><?php endif; ?>
          <div style="margin-top: 12px; display: flex; flex-direction: column; gap: 7px;">
            <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty($r1['label'])) : ?><div style="font-size: 14px; color: var(--sh-crumb-current); background: rgba(var(--sh-sky-rgb), 0.09); border: 1px solid rgba(var(--sh-sky-rgb), 0.2); border-radius: 8px; padding: 9px 11px;"><?= esc_html($r1['label'] ?? '') ?></div><?php endif; ?><?php endforeach; ?>
          </div>
        </div>
        <div style="border-radius: 16px; background: var(--sh-surface); padding: 16px; box-shadow: rgba(0, 0, 0, 0.9) 0px 22px 50px -34px;">
          <?php if (!empty($f['label_2'])) : ?><div style="font-size: 12px; color: var(--sh-crumb); padding-bottom: 10px; border-bottom: 1px solid rgba(255, 255, 255, 0.08);"><?= esc_html($f['label_2'] ?? '') ?></div><?php endif; ?>
          <div style="margin-top: 12px; display: flex; flex-direction: column; gap: 9px;">
            <?php if (!empty($f['label_3'])) : ?><div style="border-radius: 10px; background: rgba(var(--sh-lime-rgb), 0.1); border: 1px dashed rgba(var(--sh-lime-rgb), 0.32); padding: 16px; font-size: 14px; color: var(--sh-lime);"><?= esc_html($f['label_3'] ?? '') ?></div><?php endif; ?>
            <div data-wp-row style="display: grid; gap: 9px;">
              <?php $r1_list = $f['items_2'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty($r1['label'])) : ?><div style="border-radius: 10px; background: rgba(255, 255, 255, 0.05); padding: 16px 12px; font-size: 13.5px; color: var(--sh-crumb-current); text-align: center;"><?= esc_html($r1['label'] ?? '') ?></div><?php endif; ?><?php endforeach; ?>
            </div>
            <?php if (!empty($f['label_4'])) : ?><div style="border-radius: 10px; background: rgba(255, 255, 255, 0.05); padding: 16px; font-size: 14px; color: var(--sh-crumb-current);"><?= esc_html($f['label_4'] ?? '') ?></div><?php endif; ?>
            <?php if (!empty($f['label_5'])) : ?><div style="border-radius: 10px; background: rgba(255, 255, 255, 0.04); padding: 14px; font-size: 13.5px; color: var(--sh-dim);"><?= esc_html($f['label_5'] ?? '') ?></div><?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
