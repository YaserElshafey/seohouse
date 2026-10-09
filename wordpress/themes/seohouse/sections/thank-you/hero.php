<?php
/**
 * Section "Hero" — SEO House - Thank You.
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

    <div style="position: relative; max-width: 880px; margin: 0px auto; padding: clamp(30px, 3.8vw, 56px) 20px clamp(32px, 4.4vw, 60px);">
      <div aria-hidden="true" style="width: 56px; height: 56px; border-radius: 999px; background: rgba(255, 255, 255, 0.16); color: rgb(255, 255, 255); display: flex; align-items: center; justify-content: center; font-size: 24px;">✓</div>
      <?php if (!empty($f['title'])) : ?><h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(28px, 3.2vw, 42px); line-height: 1.3; margin: 20px 0px 0px;"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
      <div style="margin-top: 26px; display: flex; flex-direction: column;">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="display: flex; align-items: flex-start; gap: 16px; padding: 16px 0px; border-top: 1px solid rgba(255, 255, 255, 0.12);">
          <span style="flex: 0 0 auto; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: rgb(255, 255, 255);"><?= esc_html(($i1 + 1)) ?></span>
          <span style="min-width: 0px;"><?php if (!empty($r1['heading'])) : ?><span style="display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16.5px;"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?><?php if (!empty($r1['text'])) : ?><span style="display: block; font-size: 15px; color: rgb(255, 255, 255); margin-top: 5px;"><?= esc_html($r1['text'] ?? '') ?></span><?php endif; ?></span>
        </div><?php endforeach; ?>
      </div>
      <div style="margin-top: 26px; display: flex; flex-wrap: wrap; gap: 12px 20px;">
        <?php $r1_list = $f['items_2'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty(sh_link($r1['link'] ?? '')) && !empty($r1['link_label'])) : ?><a href="<?= esc_url(sh_link($r1['link'] ?? '')) ?>" style="font-size: 15px; font-weight: 600;"><?= esc_html($r1['link_label'] ?? '') ?></a><?php endif; ?><?php endforeach; ?>
      </div>
    </div>
  </section>
