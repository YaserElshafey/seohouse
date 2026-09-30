<?php
/**
 * Section "Hero" — SEO House - SEO Consulting.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Hero" style="position: relative; overflow: hidden;">
    <div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px); background-size: 72px 72px; mask-image: radial-gradient(90% 100% at 18% 0%, rgb(0, 0, 0), transparent 68%); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: 18px 20px 0px;">
      <?php sh_breadcrumbs(); ?>
    </div>
    <div data-g2 style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(24px, 2.8vw, 42px) 20px clamp(38px, 4.2vw, 60px);">
      <div style="animation: 0.7s ease 0s 1 normal both running fadeUp;">
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(28px, 3.2vw, 45px); line-height: 1.3; margin: 12px 0px 0px; max-width: 21em;"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 17.5px; line-height: 1.85; color: var(--sh-muted); max-width: 37em; margin: 18px 0px 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 14px 26px; margin-top: 28px;">
          <?php if (!empty($f['link_label'])) : ?><a href="#booking" class="hv-d5cb1d" style="background: var(--sh-lime); color: var(--sh-ink); font-weight: 700; font-size: 16.5px; min-height: 56px; display: inline-flex; align-items: center; justify-content: center; padding: 0px 28px; border-radius: 14px;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
          <a href="#when" data-ghost style="min-height: 56px; display: inline-flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 600; color: var(--sh-text); padding: 0px 16px; border-radius: 14px;"><?= esc_html($f['link_label_2'] ?? '') ?> <i data-ghost-arrow aria-hidden="true">↓</i></a>
        </div>
      </div>
      <div style="animation: 0.7s ease 0.12s 1 normal both running fadeUp;">
        <div style="border-radius: 20px; background: linear-gradient(150deg, var(--sh-surface), var(--sh-surface)); padding: clamp(18px, 2.2vw, 26px); box-shadow: rgba(0, 0, 0, 0.9) 0px 24px 54px -36px;">
          <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; font-size: 12.5px; color: var(--sh-crumb);">
            <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty($r1['label'])) : ?><span><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?><?php endforeach; ?>
          </div>
          <div style="margin-top: 16px; display: grid; grid-template-columns: repeat(2, minmax(0px, 1fr)); gap: 10px;">
            <?php $r1_list = $f['items_2'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_20f5d4f3 = ['v1' => ['border-radius: 13px; background: rgba(255, 255, 255, 0.05); padding: 14px; border-top: 2px solid var(--sh-sky);', 'font-size: 15px; font-weight: 700; color: var(--sh-sky);'], 'v2' => ['border-radius: 13px; background: rgba(255, 255, 255, 0.05); padding: 14px; border-top: 2px solid var(--sh-lime);', 'font-size: 15px; font-weight: 700; color: var(--sh-lime);']]; $vk_20f5d4f3 = $vt_20f5d4f3[$r1['variant'] ?? 'v1'] ?? $vt_20f5d4f3['v1']; ?><div style="<?= esc_attr($vk_20f5d4f3[0] ?? '') ?>">
              <?php if (!empty($r1['heading'])) : ?><div style="<?= esc_attr($vk_20f5d4f3[1] ?? '') ?>"><?= esc_html($r1['heading'] ?? '') ?></div><?php endif; ?>
              <?php if (!empty($r1['label'])) : ?><div style="font-size: 13.5px; color: var(--sh-dim); margin-top: 6px;"><?= esc_html($r1['label'] ?? '') ?></div><?php endif; ?>
            </div><?php endforeach; ?>
          </div>
          <svg aria-hidden="true" viewBox="0 0 320 70" preserveAspectRatio="none" style="margin-top: 14px; width: 100%; height: 60px; display: block;">
            <path d="M6 58 C70 58 96 40 150 34 C210 27 250 20 314 12" fill="none" stroke="rgba(76,172,255,.45)" stroke-width="1.8"></path>
            <g stroke="rgba(255,255,255,.07)"><path d="M0 20h320M0 40h320"></path></g>
          </svg>
        </div></div>
    </div>
  </section>
