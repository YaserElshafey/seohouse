<?php
/**
 * Section "Hero" — SEO House - Homepage.
 * Generated from the approved design by tools/design-import/convert.js; v5 markup bound by hand to the
 * 2.x fields: results button = link_label_2, team link = link_label_3 (the v5 generator renames them).
 * @sh-manual
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Hero" data-hero-blue style="position: relative; overflow: hidden; background: rgb(40, 84, 232); color: rgb(255, 255, 255);">
    <div aria-hidden="true" data-hero-bg style="position: absolute; inset: 0px; pointer-events: none; overflow: hidden;">
      <div style="position: absolute; inset: 0px; background: radial-gradient(60% 80% at 22% 45%, rgba(255, 255, 255, 0.1), transparent 70%), radial-gradient(40% 55% at 85% 0%, rgba(20, 48, 170, 0.35), transparent 70%), linear-gradient(rgb(46, 90, 240) 0%, rgb(40, 84, 232) 60%, rgb(36, 76, 214) 100%);"></div>
      <div style="position: absolute; inset: 0px; opacity: 0.1; background-image: linear-gradient(rgba(255, 255, 255, 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.9) 1px, transparent 1px); background-size: 64px 64px; mask-image: radial-gradient(70% 80% at 60% 30%, rgb(0, 0, 0), transparent 75%);"></div>
      <svg viewBox="0 0 1200 600" preserveAspectRatio="none" style="position: absolute; inset-inline: 0px; bottom: 0px; width: 100%; height: 62%; opacity: 0.22;"><g fill="none" stroke="#FFFFFF" stroke-width="1.2"><path d="M0 520 C 180 510, 320 470, 470 455 S 760 380, 1200 250"></path><path d="M0 560 C 220 552, 380 520, 540 508 S 820 450, 1200 350" stroke-dasharray="3 8"></path></g><g fill="#FFFFFF"><circle cx="470" cy="455" r="3"></circle><circle cx="820" cy="360" r="3"></circle></g></svg>
    </div>
    <div aria-hidden="true" style="position: absolute; inset: 0px; background: radial-gradient(48% 62% at 26% 50%, rgba(255, 255, 255, 0.08), transparent 78%); pointer-events: none;"></div>
    <div data-grid="hero" style="position: relative; max-width: 1320px; margin: 0px auto; padding: clamp(32px, 3.6vw, 54px) 20px clamp(36px, 3.8vw, 56px); display: grid; gap: clamp(28px, 3.2vw, 46px); align-items: center;">
      <div data-hero-text style="animation: 0.7s ease 0s 1 normal both running fadeUp; text-align: center; display: flex; flex-direction: column; align-items: center;">
        <?php if (!empty($f['title'])) : ?><h1 data-hero-h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(25px, 2.3vw, 34px); line-height: 1.4; margin: 0px; color: rgb(255, 255, 255);"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p data-hero-lead style="font-size: 17.5px; line-height: 1.85; color: rgb(255, 255, 255); margin: 16px 0px 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 14px 20px; margin-top: 28px;">
          <?php if (!empty($f['link_label'])) : ?><a href="#booking" data-hero-cta style="background: rgb(255, 255, 255); color: rgb(33, 72, 216); font-weight: 700; font-size: 16.5px; min-height: 56px; display: inline-flex; align-items: center; justify-content: center; padding: 0px 28px; border-radius: 14px; box-shadow: rgba(10, 20, 80, 0.55) 0px 10px 24px -14px;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
          <a href="#results" data-hero-sec style="min-height: 56px; display: inline-flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 600; color: rgb(255, 255, 255); padding: 0px 22px; border-radius: 14px; border: 1px solid rgba(255, 255, 255, 0.55); background: rgba(255, 255, 255, 0.06); text-decoration: none;"><?php if (!empty($f['link_label_2'])) : ?><span data-hs-txt><?= esc_html($f['link_label_2'] ?? '') ?></span><?php endif; ?> <span aria-hidden="true">←</span></a>
        </div>

      </div>

      <div style="animation: 0.7s ease 0.12s 1 normal both running fadeUp;">
        <div data-hero-desk style="position: relative;">
          <div data-hero-cols><?php get_template_part( 'parts/dynamic/team-columns', null, array( 'columns' => 3 ) ); ?></div>
        </div>

        <div data-hero-mob>
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="<?= esc_attr($i1 === 0 ? 'overflow: auto hidden; scrollbar-width: none; mask-image: linear-gradient(90deg, transparent, rgb(0, 0, 0) 6%, rgb(0, 0, 0) 94%, transparent);' : 'overflow: auto hidden; scrollbar-width: none; margin-top: 8px; mask-image: linear-gradient(90deg, transparent, rgb(0, 0, 0) 6%, rgb(0, 0, 0) 94%, transparent);') ?>">
            <div data-mob-track style="display: flex; width: max-content; animation: 44s linear 0s infinite normal none running shRtl;"><?php get_template_part( 'parts/dynamic/team-track', null, array( 'track' => 0, 'img_style' => 'flex: 0 0 auto; width: 112px; height: 112px; margin-inline-end: 8px; object-fit: cover; object-position: center 20%; display: block; border-radius: 14px; filter: saturate(0.85);' ) ); ?></div>
          </div><?php endforeach; ?>
        </div>

        <div data-team-link style="display: flex; justify-content: flex-start; align-items: center; gap: 12px; margin-top: 16px;">
          <?php if (!empty(sh_link($f['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" style="display: inline-flex; align-items: center; gap: 8px; font-size: 14.5px; font-weight: 600; color: rgb(255, 255, 255); border-bottom: 1px solid rgba(255, 255, 255, 0.5); padding-bottom: 2px;"><?= esc_html($f['link_label_3'] ?? '') ?> <span>←</span></a><?php endif; ?>
        </div>

      </div>
    </div>
  </section>
