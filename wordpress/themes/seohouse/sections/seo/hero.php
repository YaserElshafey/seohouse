<?php
/**
 * Section "Hero" — SEO House - SEO Service Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Hero" data-hero-blue style="position: relative; overflow: hidden; background: linear-gradient(rgb(46, 90, 240) 0%, rgb(40, 84, 232) 60%, rgb(36, 76, 214) 100%); color: rgb(255, 255, 255);">
    <div aria-hidden="true" style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(255, 255, 255, 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.9) 1px, transparent 1px); background-size: 72px 72px; mask-image: radial-gradient(120% 110% at 78% 0%, rgb(0, 0, 0), transparent 70%); pointer-events: none;"></div>
    <div aria-hidden="true" style="position: absolute; inset-inline-end: -200px; top: -160px; width: 600px; height: 600px; background: radial-gradient(circle, rgba(var(--sh-blue-rgb), 0.4), transparent 70%); pointer-events: none;"></div>

    <div style="position: relative; max-width: 1320px; margin: 0px auto; padding: 18px 20px 0px;">
      <?php sh_breadcrumbs(); ?>
    </div>
    <div data-grid="hero" style="position: relative; max-width: 1320px; margin: 0px auto; padding: clamp(32px, 4vw, 60px) 20px clamp(32px, 4.4vw, 60px); display: grid; gap: clamp(30px, 3.4vw, 52px); align-items: center;">
      <div style="animation: 0.7s ease 0s 1 normal both running fadeUp;">
        <?php if (!empty($f['title'])) : ?><h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(28px, 3.1vw, 44px); line-height: 1.3; margin: 0px; text-wrap: balance;"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 17.5px; line-height: 1.85; color: rgb(255, 255, 255); max-width: 600px; margin: 22px 0px 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 14px 26px; margin-top: 32px;">
          <?php if (!empty($f['link_label'])) : ?><a href="#booking" data-hero-cta style="background: rgb(255, 255, 255); color: rgb(33, 72, 216); font-weight: 700; font-size: 16.5px; min-height: 56px; display: inline-flex; align-items: center; justify-content: center; padding: 0px 28px; border-radius: 14px;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
          <a href="#seo-system" data-hero-sec style="min-height: 56px; display: inline-flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 600; color: rgb(255, 255, 255); padding: 0px 16px; border-radius: 14px;"><?= esc_html($f['link_label_2'] ?? '') ?> <i data-ghost-arrow aria-hidden="true">↓</i></a>
        </div>
      </div>

      <div style="position: relative; animation: 0.7s ease 0.12s 1 normal both running fadeUp;">
        <svg aria-hidden="true" viewBox="0 0 400 260" preserveAspectRatio="none" style="position: absolute; inset: 0px; width: 100%; height: 100%; opacity: 0.5; pointer-events: none;">
          <path d="M8 232 C70 232 96 176 150 160 C210 142 236 96 300 72 C336 58 368 44 396 36" fill="none" stroke="rgba(76,172,255,.45)" stroke-width="2" stroke-dasharray="640" style="animation: 13s ease-in-out 0s infinite normal none running hDraw;"></path>
          <path d="M8 248 C90 248 130 206 210 190 C280 176 320 140 396 116" fill="none" stroke="rgba(255,255,255,.22)" stroke-width="1.6" stroke-dasharray="640" style="animation: 13s ease-in-out 0.4s infinite normal none running hDraw;"></path>
        </svg>

        <div style="position: relative; display: flex; flex-direction: column; justify-content: center; gap: 14px; min-height: 100%;">
          <div style="background: rgb(11, 20, 56); border-radius: 16px; padding: 14px 16px; box-shadow: rgba(0, 0, 0, 0.9) 0px 20px 44px -30px; display: flex; align-items: center; gap: 12px;">
            <svg viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="#8494B5" stroke-width="1.5" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="M16.5 16.5 21 21"></path></svg>
            <div style="display: flex; align-items: center; gap: 4px; min-width: 0px;">
              <?php if (!empty($f['text_2'])) : ?><span data-type-mask style="font-size: 16.5px; color: rgb(255, 255, 255); white-space: nowrap; animation: 13s steps(28) 0s infinite normal none running hType;"><?= esc_html($f['text_2'] ?? '') ?></span><?php endif; ?>
              <span aria-hidden="true" style="width: 1.5px; height: 19px; background: rgb(255, 255, 255); animation: 13s steps(1) 0s infinite normal none running hCaret;"></span>
            </div>
          </div>

          <div style="background: rgb(255, 255, 255); color: rgb(6, 11, 31); border-radius: 16px; padding: 16px 18px; box-shadow: rgba(0, 0, 0, 0.85) 0px 22px 48px -30px; animation: 13s ease-in-out 0s infinite normal none running hPop2;">
            <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--sh-ink-soft);">
              <?php if (!empty($f['label'])) : ?><span style="width: 18px; height: 18px; border-radius: 4px; background: rgba(var(--sh-blue-rgb), 0.1); display: flex; align-items: center; justify-content: center; font-size: 10px; color: rgb(255, 255, 255);"><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?>
              <?php if (!empty($f['label_2'])) : ?><span><?= esc_html($f['label_2'] ?? '') ?></span><?php endif; ?>
              <?php if (!empty($f['eyebrow'])) : ?><span style="margin-inline-start: auto; font-size: 11px; color: rgb(27, 127, 59); font-weight: 600;"><?= esc_html($f['eyebrow'] ?? '') ?></span><?php endif; ?>
            </div>
            <?php if (!empty($f['text_3'])) : ?><div style="font-size: 17px; font-weight: 600; color: rgb(26, 13, 171); margin-top: 8px; line-height: 1.5;"><?= esc_html($f['text_3'] ?? '') ?></div><?php endif; ?>
            <?php if (!empty($f['label_3'])) : ?><div style="font-size: 13.5px; color: var(--sh-ink-soft); margin-top: 6px;"><?= esc_html($f['label_3'] ?? '') ?></div><?php endif; ?>
          </div>

          <div style="display: flex; align-items: center; gap: 12px; animation: 13s ease-in-out 0s infinite normal none running hPop3;">
            <span aria-hidden="true" style="width: 34px; height: 34px; border-radius: 10px; background: rgba(var(--sh-sky-rgb), 0.16); color: rgb(255, 255, 255); display: flex; align-items: center; justify-content: center; font-size: 16px;">↓</span>
            <?php if (!empty($f['label_4'])) : ?><div style="flex: 1 1 auto; background: rgba(255, 255, 255, 0.05); border-radius: 14px; padding: 12px 14px; font-size: 14.5px; color: rgb(255, 255, 255);"><?= esc_html($f['label_4'] ?? '') ?></div><?php endif; ?>
          </div>

          <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 12px; animation: 13s ease-in-out 0s infinite normal none running hPop4;">
            <?php if (!empty($f['label_5'])) : ?><span style="background: rgba(255, 255, 255, 0.14); color: rgb(255, 255, 255); font-weight: 600; font-size: 14.5px; border-radius: 999px; padding: 10px 16px;"><?= esc_html($f['label_5'] ?? '') ?></span><?php endif; ?>
            <?php if (!empty($f['label_6'])) : ?><span style="font-size: 13.5px; color: rgb(255, 255, 255);"><?= esc_html($f['label_6'] ?? '') ?></span><?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
