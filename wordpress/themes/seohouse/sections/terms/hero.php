<?php
/**
 * Section "Hero" — SEO House - Terms.
 * Generated from the approved design by tools/design-import/convert.js.
 * Maintained by hand (2.6.0): the H1 falls back to the page title when the field is empty.
 * @sh-manual
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
$f['title'] = ! empty( $f['title'] ) ? $f['title'] : get_the_title();
?>
<section data-screen-label="Hero" style="position: relative; border-bottom: 1px solid rgba(255, 255, 255, 0.1); background: linear-gradient(rgb(46, 90, 240) 0%, rgb(40, 84, 232) 60%, rgb(36, 76, 214) 100%); color: rgb(255, 255, 255);">
    <div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(255, 255, 255, 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.9) 1px, transparent 1px); background-size: 72px 72px; mask-image: radial-gradient(90% 100% at 70% 10%, rgb(0, 0, 0), transparent 68%); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: 18px 20px 0px;">
      <?php sh_breadcrumbs(); ?>
    </div>

    <div style="position: relative; max-width: 1120px; margin: 0px auto; padding: clamp(18px, 2.2vw, 30px) 20px clamp(22px, 2.6vw, 32px); display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 12px 30px;">
      <div>
        <?php if (!empty($f['title'])) : ?><h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(26px, 2.8vw, 38px); line-height: 1.3; margin: 0px;"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 16px; color: rgb(255, 255, 255); margin: 12px 0px 0px; max-width: 40em;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
      </div>
      <?php if (!empty(sh_link($f['link'] ?? '')) && !empty($f['link_label'])) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" style="font-size: 14.5px; font-weight: 600;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
    </div>
  </section>
