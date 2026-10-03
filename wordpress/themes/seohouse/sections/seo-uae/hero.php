<?php
/**
 * Section "Hero" — SEO House - UAE SEO Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Hero" style="position: relative; overflow: hidden;">
    <div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px); background-size: 72px 72px; mask-image: radial-gradient(90% 100% at 88% 20%, rgb(0, 0, 0), transparent 68%); pointer-events: none;"></div>
    <div aria-hidden="true" style="position: absolute; inset: 0px; opacity: 0.05; background-image: linear-gradient(90deg, rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px); background-size: 88px 100%; mask-image: linear-gradient(rgb(0, 0, 0), transparent 85%); pointer-events: none;"></div>

    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: 18px 20px 0px;">
      <?php sh_breadcrumbs(); ?>
    </div>

    <div data-ua-hero style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(24px, 2.8vw, 42px) 20px clamp(32px, 4.4vw, 60px); display: grid; gap: clamp(28px, 3.2vw, 54px); align-items: center;">
      <div style="animation: 0.7s ease 0s 1 normal both running fadeUp;">
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(28px, 3.2vw, 45px); line-height: 1.3; margin: 12px 0px 0px; max-width: 21em;"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 17.5px; line-height: 1.85; color: var(--sh-muted); max-width: 37em; margin: 18px 0px 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 14px 26px; margin-top: 28px;">
          <?php if (!empty($f['link_label'])) : ?><a href="#booking" class="hv-d5cb1d" style="background: var(--sh-lime); color: var(--sh-ink); font-weight: 700; font-size: 16.5px; min-height: 56px; display: inline-flex; align-items: center; justify-content: center; padding: 0px 28px; border-radius: 14px;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
          <a href="#uae-opportunities" data-ghost style="min-height: 56px; display: inline-flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 600; color: var(--sh-text); padding: 0px 16px; border-radius: 14px;"><?= esc_html($f['link_label_2'] ?? '') ?> <i data-ghost-arrow aria-hidden="true">↓</i></a>
        </div>
      </div>

      <div role="img" aria-label="لوحة توضح ترتيب نتائج البحث وانتقال صفحة الموقع إلى موضع أفضل ثم تحولها إلى زيارة مؤهلة واستفسار" style="position: relative; animation: 0.7s ease 0.12s 1 normal both running fadeUp;">
        <div style="display: flex; align-items: center; gap: 10px; background: var(--sh-surface); border-radius: 14px; padding: 13px 16px; box-shadow: rgba(0, 0, 0, 0.9) 0px 20px 44px -34px;">
          <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="#8494B5" stroke-width="1.5" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="M16.5 16.5 21 21"></path></svg>
          <?php if (!empty($f['text_2'])) : ?><span style="font-size: 15.5px; color: var(--sh-crumb-current);"><?= esc_html($f['text_2'] ?? '') ?></span><?php endif; ?>
          <span aria-hidden="true" style="margin-inline-start: auto; font-size: 12px; color: var(--sh-crumb);">نتائج البحث</span>
        </div>

        <div data-ua-serp style="margin-top: 12px;">
          
            <div style="display: flex; align-items: center; gap: 12px; border-radius: 14px; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.08); padding: 14px 16px;">
              <span aria-hidden="true" style="flex: 0 0 auto; width: 24px; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: var(--sh-crumb);">01</span>
              <span style="flex: 1 1 auto; min-width: 0px;">
                <?php if (!empty($f['label'])) : ?><span style="display: block; font-size: 14.5px; font-weight: 600; color: var(--sh-crumb);"><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?>
                <span style="display: block; height: 6px; margin-top: 8px; border-radius: 999px; background: rgba(255, 255, 255, 0.12); width: 72%;"></span>
              </span>
            </div>
          
            <div data-ua-drop="true" style="display: flex; align-items: center; gap: 12px; border-radius: 14px; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.08); padding: 14px 16px;">
              <span aria-hidden="true" style="flex: 0 0 auto; width: 24px; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: var(--sh-crumb);">02</span>
              <span style="flex: 1 1 auto; min-width: 0px;">
                <?php if (!empty($f['label_2'])) : ?><span style="display: block; font-size: 14.5px; font-weight: 600; color: var(--sh-crumb);"><?= esc_html($f['label_2'] ?? '') ?></span><?php endif; ?>
                <span style="display: block; height: 6px; margin-top: 8px; border-radius: 999px; background: rgba(255, 255, 255, 0.12); width: 58%;"></span>
              </span>
            </div>
          
            <div data-ua-mine="true" style="display: flex; align-items: center; gap: 12px; border-radius: 14px; background: rgba(var(--sh-lime-rgb), 0.08); border: 1px solid rgba(var(--sh-lime-rgb), 0.45); padding: 14px 16px;">
              <span aria-hidden="true" style="flex: 0 0 auto; width: 24px; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: var(--sh-lime);">03</span>
              <span style="flex: 1 1 auto; min-width: 0px;">
                <?php if (!empty($f['label_3'])) : ?><span style="display: block; font-size: 14.5px; font-weight: 600; color: var(--sh-text);"><?= esc_html($f['label_3'] ?? '') ?></span><?php endif; ?>
                <span style="display: block; height: 6px; margin-top: 8px; border-radius: 999px; background: rgba(var(--sh-lime-rgb), 0.55); width: 80%;"></span>
              </span>
            </div>
          
            <div style="display: flex; align-items: center; gap: 12px; border-radius: 14px; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.08); padding: 14px 16px;">
              <span aria-hidden="true" style="flex: 0 0 auto; width: 24px; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: var(--sh-crumb);">04</span>
              <span style="flex: 1 1 auto; min-width: 0px;">
                <?php if (!empty($f['label_4'])) : ?><span style="display: block; font-size: 14.5px; font-weight: 600; color: var(--sh-crumb);"><?= esc_html($f['label_4'] ?? '') ?></span><?php endif; ?>
                <span style="display: block; height: 6px; margin-top: 8px; border-radius: 999px; background: rgba(255, 255, 255, 0.12); width: 48%;"></span>
              </span>
            </div>
          
        </div>

        <svg aria-hidden="true" viewBox="0 0 320 54" preserveAspectRatio="none" style="margin-top: 6px; width: 100%; height: 46px; display: block;">
          <path d="M306 44 C250 44 236 14 170 14 C104 14 92 40 14 40" fill="none" stroke="rgba(76,172,255,.45)" stroke-width="1.5" stroke-dasharray="6 8" style="animation: 7s linear 0s infinite normal none running uaeDash;"></path>
        </svg>

        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 10px 12px;">
          <span style="display: inline-flex; align-items: center; gap: 8px; font-size: 14.5px; color: var(--sh-crumb-current); background: rgba(255, 255, 255, 0.05); border-radius: 999px; padding: 9px 15px;"><i aria-hidden="true" style="width: 8px; height: 8px; border-radius: 999px; background: var(--sh-sky); animation: 3s ease-in-out 0s infinite normal none running uaeDot;"></i><?= esc_html($f['label_5'] ?? '') ?></span>
          <span aria-hidden="true" style="color: var(--sh-sky);">←</span>
          <span style="display: inline-flex; align-items: center; gap: 8px; font-size: 14.5px; color: var(--sh-crumb-current); background: rgba(255, 255, 255, 0.05); border-radius: 999px; padding: 9px 15px;"><i aria-hidden="true" style="width: 8px; height: 8px; border-radius: 999px; background: var(--sh-sky); animation: 3s ease-in-out 0.6s infinite normal none running uaeDot;"></i><?= esc_html($f['label_6'] ?? '') ?></span>
          <span aria-hidden="true" style="color: var(--sh-sky);">←</span>
          <?php if (!empty($f['label_7'])) : ?><span style="font-size: 14.5px; font-weight: 600; color: var(--sh-ink); background: var(--sh-lime); border-radius: 999px; padding: 9px 15px;"><?= esc_html($f['label_7'] ?? '') ?></span><?php endif; ?>
        </div>
      </div>
    </div>
  </section>
