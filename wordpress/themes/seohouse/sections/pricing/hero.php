<?php
/**
 * Section "Hero" — SEO House - Pricing.
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

    <div data-pr-hero style="position: relative; max-width: 1220px; margin: 0px auto; padding: clamp(22px, 2.6vw, 38px) 20px clamp(34px, 3.8vw, 52px); display: grid; gap: clamp(24px, 3vw, 44px); align-items: center;">
      <div style="animation: 0.7s ease 0s 1 normal both running fadeUp;">
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(28px, 3.2vw, 44px); line-height: 1.3; margin: 12px 0px 0px; max-width: 19em;"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 17px; line-height: 1.85; color: var(--sh-muted); margin: 16px 0px 0px; max-width: 34em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 14px 24px; margin-top: 26px;">
          <?php if (!empty($f['link_label'])) : ?><a href="#booking" class="hv-d5cb1d" style="background: var(--sh-lime); color: var(--sh-ink); font-weight: 700; font-size: 16.5px; min-height: 56px; display: inline-flex; align-items: center; padding: 0px 28px; border-radius: 14px;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
          <a href="#factors" data-ghost style="min-height: 56px; display: inline-flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 600; color: var(--sh-text); padding: 0px 16px; border-radius: 14px;"><?= esc_html($f['link_label_2'] ?? '') ?> <i data-ghost-arrow aria-hidden="true">↓</i></a>
        </div>
      </div>
      <div style="animation: 0.7s ease 0.12s 1 normal both running fadeUp; border-radius: 20px; background: linear-gradient(150deg, var(--sh-surface), var(--sh-surface)); padding: clamp(20px, 2.4vw, 30px); box-shadow: rgba(0, 0, 0, 0.9) 0px 24px 54px -36px;">
        <?php if (!empty($f['label'])) : ?><div style="font-size: 12.5px; color: var(--sh-crumb);"><?= esc_html($f['label'] ?? '') ?></div><?php endif; ?>
        <div style="display: flex; align-items: baseline; gap: 10px; flex-wrap: wrap; margin-top: 12px;">
          <?php if (!empty($f['heading'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: clamp(30px, 3.4vw, 44px); color: var(--sh-lime); line-height: 1.05;"><?= esc_html($f['heading'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($f['text_2'])) : ?><span style="font-size: 16px; font-weight: 600; color: var(--sh-crumb-current);"><?= esc_html($f['text_2'] ?? '') ?></span><?php endif; ?>
        </div>
        <div aria-hidden="true" style="margin-top: 18px; height: 6px; border-radius: 999px; background: linear-gradient(90deg, rgba(var(--sh-lime-rgb), 0.6), rgba(var(--sh-sky-rgb), 0.5));"></div>
        <div style="margin-top: 10px; display: flex; justify-content: space-between; font-size: 12.5px; color: var(--sh-crumb);">
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty($r1['label'])) : ?><span><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?><?php endforeach; ?>
        </div>
        <?php if (!empty($f['text_3'])) : ?><p style="font-size: 14.5px; color: var(--sh-muted); margin: 16px 0px 0px;"><?= esc_html($f['text_3'] ?? '') ?></p><?php endif; ?>
      </div>
    </div>
  </section>
