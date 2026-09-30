<?php
/**
 * Section "Hero" — SEO House - Contact.
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

    <div style="position: relative; max-width: 1000px; margin: 0px auto; padding: clamp(22px, 2.6vw, 38px) 20px clamp(26px, 3vw, 40px); animation: 0.7s ease 0s 1 normal both running fadeUp;">
      <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
      <?php if (!empty($f['title'])) : ?><h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(28px, 3.2vw, 44px); line-height: 1.3; margin: 12px 0px 0px; max-width: 19em;"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
      <?php if (!empty($f['text'])) : ?><p style="font-size: 17px; line-height: 1.85; color: var(--sh-muted); margin: 16px 0px 0px; max-width: 38em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
    </div>
  </section>
