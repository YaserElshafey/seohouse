<?php
/**
 * Section "Hero" — SEO House - Privacy Policy.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Hero" style="position: relative; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
    <div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px); background-size: 72px 72px; mask-image: radial-gradient(90% 100% at 30% 0%, rgb(0, 0, 0), transparent 68%); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: 18px 20px 0px;">
      <?php sh_breadcrumbs(); ?>
    </div>

    <div style="position: relative; max-width: 1120px; margin: 0px auto; padding: clamp(18px, 2.2vw, 30px) 20px clamp(22px, 2.6vw, 32px);">
      <?php if (!empty($f['title'])) : ?><h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(26px, 2.8vw, 38px); line-height: 1.3; margin: 0px;"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
      <?php if (!empty($f['text'])) : ?><p style="font-size: 16px; color: var(--sh-muted); margin: 12px 0px 0px; max-width: 44em;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
    </div>
  </section>
