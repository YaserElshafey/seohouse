<?php
/**
 * Section "Hero" — SEO House - Results.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Hero" data-hero-blue style="position: relative; overflow: hidden; border-bottom: 1px solid rgba(255, 255, 255, 0.08); background: linear-gradient(rgb(46, 90, 240) 0%, rgb(40, 84, 232) 60%, rgb(36, 76, 214) 100%); color: rgb(255, 255, 255);">
    <div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(255, 255, 255, 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.9) 1px, transparent 1px); background-size: 72px 72px; mask-image: radial-gradient(80% 100% at 50% 0%, rgb(0, 0, 0), transparent 70%); pointer-events: none;"></div>
    <div aria-hidden="true" style="position: absolute; inset-inline: 0px; top: -220px; margin: auto; width: 720px; height: 480px; background: radial-gradient(closest-side, rgba(var(--sh-blue-rgb), 0.32), transparent); pointer-events: none;"></div>
    <div style="position: relative; max-width: 900px; margin: 0px auto; padding: 18px 20px clamp(34px, 4vw, 50px); text-align: center;">
      <?php sh_breadcrumbs(); ?>
      <?php if (!empty($f['title'])) : ?><h1 style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: clamp(28px, 3.2vw, 44px); line-height: 1.3; margin: 22px 0px 0px;"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
      <?php if (!empty($f['text'])) : ?><p style="font-size: 17.5px; line-height: 1.85; color: rgb(255, 255, 255); margin: 12px auto 0px; max-width: 32em;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
    </div>
  </section>
