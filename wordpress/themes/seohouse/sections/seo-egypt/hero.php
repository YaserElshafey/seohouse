<?php
/**
 * Section "Hero" — SEO House - Egypt SEO Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Hero" data-hero-blue style="position: relative; overflow: hidden; background: linear-gradient(rgb(46, 90, 240) 0%, rgb(40, 84, 232) 60%, rgb(36, 76, 214) 100%); color: rgb(255, 255, 255);">
    <div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(255, 255, 255, 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.9) 1px, transparent 1px); background-size: 72px 72px; mask-image: radial-gradient(90% 100% at 30% 0%, rgb(0, 0, 0), transparent 68%); pointer-events: none;"></div>
    <svg aria-hidden="true" viewBox="0 0 1440 420" preserveAspectRatio="none" style="position: absolute; inset-inline: 0px; bottom: 0px; width: 100%; height: 62%; opacity: 0.5; pointer-events: none;">
      <path d="M1440 372 C1200 372 1120 300 980 272 C840 244 760 176 600 150 C440 124 300 96 0 88" fill="none" stroke="rgba(76,172,255,.22)" stroke-width="1.4"></path>
      <path d="M1440 404 C1180 404 1060 352 900 326 C740 300 620 244 0 210" fill="none" stroke="rgba(255,255,255,.12)" stroke-width="1.2"></path>
    </svg>

    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: 18px 20px 0px;">
      <?php sh_breadcrumbs(); ?>
    </div>

    <div data-eg-hero style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(24px, 2.8vw, 42px) 20px clamp(32px, 4.4vw, 60px); display: grid; gap: clamp(28px, 3.2vw, 52px); align-items: center;">
      <div style="animation: 0.7s ease 0s 1 normal both running fadeUp;">
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: rgb(255, 255, 255);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(28px, 3.2vw, 45px); line-height: 1.3; margin: 12px 0px 0px; max-width: 21em;"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 17.5px; line-height: 1.85; color: rgb(255, 255, 255); max-width: 37em; margin: 18px 0px 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 14px 26px; margin-top: 28px;">
          <?php if (!empty($f['link_label'])) : ?><a href="#booking" data-hero-cta style="background: rgb(255, 255, 255); color: rgb(33, 72, 216); font-weight: 700; font-size: 16.5px; min-height: 56px; display: inline-flex; align-items: center; justify-content: center; padding: 0px 28px; border-radius: 14px;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
          <a href="#eg-scope" data-hero-sec style="min-height: 56px; display: inline-flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 600; color: rgb(255, 255, 255); padding: 0px 16px; border-radius: 14px;"><?= esc_html($f['link_label_2'] ?? '') ?> <i data-ghost-arrow aria-hidden="true">↓</i></a>
        </div>
      </div>

      <div role="img" aria-label="مخطط يوضح انتقال الباحث من عبارة البحث إلى الصفحة المناسبة ثم طلب الخدمة" style="position: relative; display: flex; flex-direction: column; gap: 12px; animation: 0.7s ease 0.12s 1 normal both running fadeUp;">
        <div style="background: rgb(11, 20, 56); border-radius: 16px; padding: 14px 16px; display: flex; align-items: center; gap: 12px; box-shadow: rgba(0, 0, 0, 0.9) 0px 20px 44px -32px;">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#8494B5" stroke-width="1.5" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="M16.5 16.5 21 21"></path></svg>
          <?php if (!empty($f['text_2'])) : ?><span style="font-size: 16px; color: rgb(255, 255, 255);"><?= esc_html($f['text_2'] ?? '') ?></span><?php endif; ?>
          <span aria-hidden="true" style="margin-inline-start: auto; width: 1.5px; height: 18px; background: rgb(255, 255, 255); animation: 2.6s ease-in-out 0s infinite normal none running nodeGlow;"></span>
        </div>

        <div style="display: flex; flex-wrap: wrap; gap: 8px 10px;">
          
            <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty($r1['label'])) : ?><span style="font-size: 14.5px; color: rgb(255, 255, 255); background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(var(--sh-sky-rgb), 0.18); border-radius: 999px; padding: 9px 15px;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?><?php endforeach; ?>
          
        </div>

        <svg aria-hidden="true" viewBox="0 0 320 60" preserveAspectRatio="none" style="width: 100%; height: 52px; display: block;">
          <path d="M300 8 C240 8 230 50 160 50 C90 50 80 20 20 20" fill="none" stroke="rgba(76,172,255,.5)" stroke-width="1.6" stroke-dasharray="7 9" style="animation: 7s linear 0s infinite normal none running egFlow;"></path>
        </svg>

        <div style="border-radius: 16px; background: rgba(255, 255, 255, 0.05); padding: 16px 18px;">
          <?php if (!empty($f['eyebrow_2'])) : ?><div style="font-size: 13px; color: rgb(255, 255, 255); font-weight: 600;"><?= esc_html($f['eyebrow_2'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['text_3'])) : ?><div style="font-size: 16px; color: rgb(255, 255, 255); margin-top: 8px;"><?= esc_html($f['text_3'] ?? '') ?></div><?php endif; ?>
        </div>

        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 12px;">
          <?php if (!empty($f['text_4'])) : ?><span style="background: rgba(255, 255, 255, 0.14); color: rgb(255, 255, 255); font-weight: 600; font-size: 15px; border-radius: 999px; padding: 10px 16px;"><?= esc_html($f['text_4'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($f['label'])) : ?><span style="font-size: 13.5px; color: rgb(255, 255, 255);"><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?>
        </div>
      </div>
    </div>
  </section>
