<?php
/**
 * Section "Hero" — SEO House - Custom Development.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Hero" style="position: relative; overflow: hidden; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
    <div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px); background-size: 72px 72px; mask-image: radial-gradient(90% 100% at 70% 10%, rgb(0, 0, 0), transparent 68%); pointer-events: none;"></div>
    <div aria-hidden="true" style="position: absolute; inset: 0px; opacity: 0.05; background-image: repeating-linear-gradient(45deg, rgba(var(--sh-sky-rgb), 0.9) 0px, rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px, transparent 26px); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: 18px 20px 0px;">
      <?php sh_breadcrumbs(); ?>
    </div>

    <div data-cd-hero style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(22px, 2.6vw, 38px) 20px clamp(36px, 4vw, 56px); display: grid; gap: clamp(24px, 3vw, 46px); align-items: center;">
      <div style="animation: 0.7s ease 0s 1 normal both running fadeUp;">
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(28px, 3.2vw, 44px); line-height: 1.3; margin: 12px 0px 0px; max-width: 19em;"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 17px; line-height: 1.85; color: var(--sh-muted); max-width: 36em; margin: 16px 0px 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 14px 24px; margin-top: 26px;">
          <?php if (!empty($f['link_label'])) : ?><a href="#booking" class="hv-d5cb1d" style="background: var(--sh-lime); color: var(--sh-ink); font-weight: 700; font-size: 16.5px; min-height: 56px; display: inline-flex; align-items: center; padding: 0px 28px; border-radius: 14px;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
          <a href="#fit" data-ghost style="min-height: 56px; display: inline-flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 600; color: var(--sh-text); padding: 0px 16px; border-radius: 14px;"><?= esc_html($f['link_label_2'] ?? '') ?> <i data-ghost-arrow aria-hidden="true">↓</i></a>
        </div>
      </div>

      <div style="animation: 0.7s ease 0.12s 1 normal both running fadeUp;">
        <svg viewBox="0 0 340 300" preserveAspectRatio="xMidYMid meet" role="img" aria-label="مخطط نظام يربط المستخدمين بالعمليات والبيانات والتكاملات" style="width: 100%; height: auto; display: block;">
          <g stroke="rgba(76,172,255,.35)" stroke-width="1.2" fill="none">
            <path d="M170 150 L170 58"></path><path d="M170 150 L64 214"></path><path d="M170 150 L276 214"></path><path d="M170 150 L170 254"></path>
          </g>
          <rect x="110" y="126" width="120" height="48" rx="12" fill="rgba(199,255,50,.12)" stroke="#C7FF32" stroke-width="1.4"></rect>
          <text x="170" y="156" text-anchor="middle" font-family="'IBM Plex Sans Arabic',sans-serif" font-size="14" font-weight="700" fill="#C7FF32">النظام</text>
          <g font-family="'IBM Plex Sans Arabic',sans-serif" font-size="13" fill="#D6DEF0" text-anchor="middle">
            <rect x="108" y="30" width="124" height="42" rx="11" fill="rgba(255,255,255,.05)" stroke="rgba(76,172,255,.35)" stroke-width="1.1"></rect><text x="170" y="56">المستخدمون</text>
            <rect x="8" y="192" width="112" height="42" rx="11" fill="rgba(255,255,255,.05)" stroke="rgba(76,172,255,.35)" stroke-width="1.1"></rect><text x="64" y="218">العمليات</text>
            <rect x="220" y="192" width="112" height="42" rx="11" fill="rgba(255,255,255,.05)" stroke="rgba(76,172,255,.35)" stroke-width="1.1"></rect><text x="276" y="218">البيانات</text>
            <rect x="108" y="248" width="124" height="42" rx="11" fill="rgba(255,255,255,.05)" stroke="rgba(76,172,255,.35)" stroke-width="1.1"></rect><text x="170" y="274">التكاملات</text>
          </g>
        </svg>
      </div>
    </div>
  </section>
