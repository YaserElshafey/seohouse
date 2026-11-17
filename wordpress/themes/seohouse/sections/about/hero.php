<?php
/**
 * Section "Hero" — SEO House - About.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Hero" data-hero-blue style="position: relative; overflow: hidden; border-bottom: 1px solid rgba(255, 255, 255, 0.1); background: linear-gradient(rgb(46, 90, 240) 0%, rgb(40, 84, 232) 60%, rgb(36, 76, 214) 100%); color: rgb(255, 255, 255);">
    <div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(255, 255, 255, 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.9) 1px, transparent 1px); background-size: 72px 72px; mask-image: radial-gradient(90% 100% at 18% 0%, rgb(0, 0, 0), transparent 68%); pointer-events: none;"></div>
    <div aria-hidden="true" style="position: absolute; inset: 0px; opacity: 0.04; background-image: linear-gradient(90deg, rgba(255, 255, 255, 0.9) 1px, transparent 1px); background-size: 64px 100%; mask-image: linear-gradient(rgb(0, 0, 0), transparent 85%); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: 18px 20px 0px;">
      <?php sh_breadcrumbs(); ?>
    </div>

    <div style="position: relative; max-width: 860px; margin: 0px auto; padding: clamp(22px, 2.6vw, 38px) 20px clamp(32px, 3.6vw, 48px); text-align: center; animation: 0.7s ease 0s 1 normal both running fadeUp;">
      <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: rgb(255, 255, 255);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
      <?php if (!empty($f['title'])) : ?><h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(28px, 3.2vw, 44px); line-height: 1.3; margin: 12px auto 0px; max-width: 20em;"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
      <?php if (!empty($f['text'])) : ?><p style="font-size: 18px; line-height: 1.85; color: rgb(255, 255, 255); margin: 16px auto 0px; max-width: 30em; text-wrap: balance;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 14px 24px; margin-top: 26px;">
          <?php if (!empty($f['link_label'])) : ?><a href="#booking" data-hero-cta style="background: rgb(255, 255, 255); color: rgb(33, 72, 216); font-weight: 700; font-size: 16.5px; min-height: 56px; display: inline-flex; align-items: center; padding: 0px 28px; border-radius: 14px;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
          <a href="#principles" data-hero-sec style="min-height: 56px; display: inline-flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 600; color: rgb(255, 255, 255); padding: 0px 16px; border-radius: 14px;"><?= esc_html($f['link_label_2'] ?? '') ?> <i data-ghost-arrow aria-hidden="true">↓</i></a>
        </div>
    </div>
  </section>
