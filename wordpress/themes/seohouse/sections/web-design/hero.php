<?php
/**
 * Section "Hero" — SEO House - Web Design.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Hero" data-hero-blue style="position: relative; overflow: hidden; background: linear-gradient(rgb(46, 90, 240) 0%, rgb(40, 84, 232) 60%, rgb(36, 76, 214) 100%); color: rgb(255, 255, 255);">
    <div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(255, 255, 255, 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.9) 1px, transparent 1px); background-size: 72px 72px; mask-image: radial-gradient(90% 100% at 50% 0%, rgb(0, 0, 0), transparent 68%); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: 18px 20px 0px;">
      <?php sh_breadcrumbs(); ?>
    </div>
    <div data-g2 style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(24px, 2.8vw, 42px) 20px clamp(38px, 4.2vw, 60px);">
      <div style="animation: 0.7s ease 0s 1 normal both running fadeUp;">
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: rgb(255, 255, 255);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(28px, 3.2vw, 45px); line-height: 1.3; margin: 12px 0px 0px; max-width: 21em;"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 17.5px; line-height: 1.85; color: rgb(255, 255, 255); max-width: 37em; margin: 18px 0px 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 14px 26px; margin-top: 28px;">
          <?php if (!empty($f['link_label'])) : ?><a href="#booking" data-hero-cta style="background: rgb(255, 255, 255); color: rgb(33, 72, 216); font-weight: 700; font-size: 16.5px; min-height: 56px; display: inline-flex; align-items: center; justify-content: center; padding: 0px 28px; border-radius: 14px;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
          <a href="#pillars" data-hero-sec style="min-height: 56px; display: inline-flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 600; color: rgb(255, 255, 255); padding: 0px 16px; border-radius: 14px;"><?= esc_html($f['link_label_2'] ?? '') ?> <i data-ghost-arrow aria-hidden="true">↓</i></a>
        </div>
      </div>
      <div style="animation: 0.7s ease 0.12s 1 normal both running fadeUp;">
        <div style="border-radius: 20px; background: linear-gradient(150deg, rgb(11, 20, 56), rgb(11, 20, 56)); padding: clamp(18px, 2.2vw, 26px); box-shadow: rgba(0, 0, 0, 0.9) 0px 24px 54px -36px;">
          <?php if (!empty($f['label'])) : ?><div style="font-size: 12.5px; color: rgb(255, 255, 255);"><?= esc_html($f['label'] ?? '') ?></div><?php endif; ?>
          <div style="margin-top: 14px; display: flex; flex-direction: column; gap: 8px;">
            <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="<?= esc_attr($i1 === $i1_n - 1 ? 'display: flex; align-items: center; gap: 12px; border-radius: 11px; background: rgba(255, 255, 255, 0.1); padding: 11px 14px;' : 'display: flex; align-items: center; gap: 12px; border-radius: 11px; background: rgba(255, 255, 255, 0.05); padding: 11px 14px;') ?>">
              <span aria-hidden="true" style="<?= esc_attr($i1 === $i1_n - 1 ? 'flex: 0 0 auto; width: 7px; height: 7px; border-radius: 999px; background: rgb(255, 255, 255);' : 'flex: 0 0 auto; width: 7px; height: 7px; border-radius: 999px; background: var(--sh-sky);') ?>"></span>
              <?php if (!empty($r1['label'])) : ?><span style="flex: 1 1 auto; font-size: 14.5px; font-weight: 600;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
              <?php if (!empty($r1['label_2'])) : ?><span style="font-size: 13px; color: rgb(255, 255, 255); text-align: end;"><?= esc_html($r1['label_2'] ?? '') ?></span><?php endif; ?>
            </div><?php endforeach; ?>
          </div>
        </div></div>
    </div>
  </section>
