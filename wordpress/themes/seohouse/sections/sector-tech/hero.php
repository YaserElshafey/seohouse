<?php
/**
 * Section "Hero" — SEO House - Sector Tech.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Hero" data-hero-blue style="position: relative; overflow: hidden; border-bottom: 1px solid rgba(255, 255, 255, 0.1); background: linear-gradient(rgb(46, 90, 240) 0%, rgb(40, 84, 232) 60%, rgb(36, 76, 214) 100%); color: rgb(255, 255, 255);">
    <div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(255, 255, 255, 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.9) 1px, transparent 1px); background-size: 72px 72px; mask-image: radial-gradient(90% 100% at 82% 0%, rgb(0, 0, 0), transparent 68%); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: 18px 20px 0px;">
      <?php sh_breadcrumbs(); ?>
    </div>
    <div data-sx-hgrid style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(22px, 2.6vw, 38px) 20px clamp(36px, 4vw, 56px);">
      <div data-sx-hero-wrap data-sx-hero-copy style="min-width: 0px; animation: 0.7s ease 0s 1 normal both running fadeUp;">
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: rgb(255, 255, 255);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(26px, 2.9vw, 40px); line-height: 1.35; margin: 12px 0px 0px; max-width: 18em; text-wrap: balance;"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p data-long style="max-width: 34em; font-size: 17px; line-height: 1.85; color: rgb(255, 255, 255); margin: 16px 0px 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 14px 24px; margin-top: 26px;">
          <?php if (!empty($f['link_label'])) : ?><a href="#booking" data-hero-cta style="background: rgb(255, 255, 255); color: rgb(33, 72, 216); font-weight: 700; font-size: 16.5px; min-height: 56px; display: inline-flex; align-items: center; padding: 0px 28px; border-radius: 14px;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
          <a href="#tc-problem" data-hero-sec style="min-height: 56px; display: inline-flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 600; color: rgb(255, 255, 255); padding: 0px 16px; border-radius: 14px;"><?= esc_html($f['link_label_2'] ?? '') ?> <i data-ghost-arrow aria-hidden="true">↓</i></a>
        </div>
      </div>
      <div data-sx-panel style="animation: 0.7s ease 0.12s 1 normal both running fadeUp; border-radius: 20px; background: rgb(251, 252, 254); color: rgb(6, 11, 31); padding: clamp(16px, 2vw, 22px); box-shadow: rgba(0, 0, 0, 0.9) 0px 30px 64px -38px, rgba(var(--sh-sky-rgb), 0.18) 0px 0px 0px 1px;">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px;">
          <?php if (!empty($f['eyebrow_2'])) : ?><span style="font-size: 12.5px; font-weight: 600; color: rgb(255, 255, 255);"><?= esc_html($f['eyebrow_2'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($f['label'])) : ?><span style="font-size: 11.5px; color: var(--sh-slate);"><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?>
        </div>
        <div style="margin-top: 10px; display: flex; align-items: center; gap: 10px; border-radius: 12px; background: rgb(238, 242, 251); padding: 11px 14px;">
          <svg aria-hidden="true" viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="#2F5BFF" stroke-width="1.6" stroke-linecap="round"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.5-3.5"></path></svg>
          <?php if (!empty($f['label_2'])) : ?><span style="font-size: 14.5px; font-weight: 600; color: var(--sh-surface-3);"><?= esc_html($f['label_2'] ?? '') ?></span><?php endif; ?>
        </div>
        <ol style="list-style: none; margin: 14px 0px 0px; padding: 0px; position: relative;">
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><li style="position: relative; display: flex; align-items: flex-start; gap: 12px; padding: 10px 0px; border-bottom: 1px dashed rgba(6, 11, 31, 0.1);">
            <span aria-hidden="true" style="flex: 0 0 auto; width: 34px; height: 34px; border-radius: 10px; background: rgba(var(--sh-blue-rgb), 0.1); display: inline-flex; align-items: center; justify-content: center;"><?= sh_icon($r1['icon'] ?? 'i410bbad1') ?></span>
            <span style="min-width: 0px;"><?php if (!empty($r1['heading'])) : ?><span style="display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 15px; color: rgb(6, 11, 31);"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?><?php if (!empty($r1['label'])) : ?><span style="display: block; font-size: 13px; line-height: 1.6; color: var(--sh-slate); margin-top: 3px;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?></span>
          </li><?php endforeach; ?>
          <li style="display: flex; align-items: center; gap: 12px; margin-top: 12px; border-radius: 12px; background: rgb(6, 11, 31); padding: 10px 12px;">
            <span aria-hidden="true" style="flex: 0 0 auto; width: 34px; height: 34px; border-radius: 10px; background: rgb(255, 255, 255); display: inline-flex; align-items: center; justify-content: center;"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#060B1F" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4z"></path><path d="M22 2 11 13"></path></svg></span>
            <span style="min-width: 0px;"><?php if (!empty($f['label_3'])) : ?><span style="display: block; font-size: 11.5px; color: rgb(255, 255, 255);"><?= esc_html($f['label_3'] ?? '') ?></span><?php endif; ?><?php if (!empty($f['heading'])) : ?><span style="display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 15px; color: rgb(255, 255, 255);"><?= esc_html($f['heading'] ?? '') ?></span><?php endif; ?></span>
          </li>
        </ol>
      </div>
    </div>
  </section>
