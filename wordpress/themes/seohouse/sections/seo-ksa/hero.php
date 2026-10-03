<?php
/**
 * Section "Hero" — SEO House - Saudi SEO Page.
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

    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: 18px 20px 0px;">
      <?php sh_breadcrumbs(); ?>
    </div>

    <div data-grid="ksahero" style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(26px, 3vw, 44px) 20px clamp(32px, 4.4vw, 60px); display: grid; gap: clamp(28px, 3.2vw, 52px); align-items: center;">
      <div style="animation: 0.7s ease 0s 1 normal both running fadeUp;">
        <?php if (!empty($f['title'])) : ?><h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(28px, 3.3vw, 46px); line-height: 1.3; margin: 0px; max-width: 21em;"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 17.5px; line-height: 1.85; color: var(--sh-muted); max-width: 36em; margin: 20px 0px 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 14px 26px; margin-top: 30px;">
          <?php if (!empty($f['link_label'])) : ?><a href="#booking" class="hv-d5cb1d" style="background: var(--sh-lime); color: var(--sh-ink); font-weight: 700; font-size: 16.5px; min-height: 56px; display: inline-flex; align-items: center; justify-content: center; padding: 0px 28px; border-radius: 14px;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
          <a href="#ksa-market" data-ghost style="min-height: 56px; display: inline-flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 600; color: var(--sh-text); padding: 0px 16px; border-radius: 14px;"><?= esc_html($f['link_label_2'] ?? '') ?> <i data-ghost-arrow aria-hidden="true">↓</i></a>
        </div>
      </div>

      <div data-journey dir="ltr" role="img" aria-label="خريطة السعودية بحدودها الحقيقية، وعليها مسار يوضح انتقال الباحث من نية البحث إلى الصفحة المناسبة ثم الزيارة المؤهلة والاستفسار" style="animation: 0.7s ease 0.12s 1 normal both running fadeUp;">
        <svg viewBox="0 0 640 540" preserveAspectRatio="xMidYMid meet" aria-hidden="true" style="position: absolute; inset: 0px; width: 100%; height: 100%;">
          <defs>
            <linearGradient id="ksaEdge" x1="0" y1="0" x2="1" y2="1">
              <stop offset="0" stop-color="#4CACFF" stop-opacity=".85"></stop>
              <stop offset="1" stop-color="#2F5BFF" stop-opacity=".35"></stop>
            </linearGradient>
            <radialGradient id="ksaFill" cx="50%" cy="40%" r="70%">
              <stop offset="0" stop-color="#2F5BFF" stop-opacity=".2"></stop>
              <stop offset="1" stop-color="#2F5BFF" stop-opacity="0"></stop>
            </radialGradient>
          </defs>

          <path d="M253.7 512.4 L250.1 499.3 L241.7 490.1 L239.5 477.9 L225.1 466.9 L210.2 441.2 L202.3 416.2 L183 395.1 L170.5 390.1 L152 360.8 L148.8 339.5 L149.9 321.4 L133.9 287.4 L120.8 275.4 L105.7 269 L96.5 251.5 L98 244.5 L90.3 228.7 L82.1 221.8 L71.2 199 L54.2 174.3 L39.9 153.2 L26 153.4 L30.3 136.5 L31.6 125.8 L35 113.6 L66.2 118.4 L78.2 109 L84.9 98 L106.3 93.7 L110.9 83.5 L120.1 78.2 L92.2 47.6 L148.2 32.2 L153.6 27.6 L187.2 35.9 L228.9 57.4 L307.7 119 L359.7 121.5 L384.6 124.4 L391.5 139 L411.3 138.2 L422.3 164.7 L436 171.7 L440.8 182.4 L459.9 195.3 L461.5 208 L458.8 218.2 L462.3 228.5 L470.3 237.1 L474.1 247.1 L478.2 254.7 L486.7 260.7 L494.4 258.6 L499.7 270.3 L500.8 277.4 L511.5 308.4 L595.6 323.9 L601.2 317.4 L614 339.1 L595.4 400.4 L511.5 431.1 L430.9 442.9 L404.8 456.7 L384.8 488.8 L371.7 494 L364.7 483.7 L354 485.3 L327 482.2 L321.9 479.1 L289.6 479.8 L282 482.6 L270.6 474.6 L263.1 489.7 L266 502.6 L253.7 512.4 Z" fill="url(#ksaFill)" stroke="url(#ksaEdge)" stroke-width="1.6" stroke-linejoin="round"></path>

          <path id="ksaTrail" d="M181.6 136.8 Q271.7 193.5 377.3 179.7 Q329.6 242.2 332.6 320.7 Q283.6 360.2 271.1 421.9" fill="none" stroke="rgba(76,172,255,.55)" stroke-width="1.8" stroke-dasharray="10 12" style="animation: 9s linear 0s infinite normal none running dashFlow;"></path>
          <circle r="4.5" fill="#C7FF32" style="offset-path: path(&quot;M 181.6 136.8 Q 271.7 193.5 377.3 179.7 Q 329.6 242.2 332.6 320.7 Q 283.6 360.2 271.1 421.9&quot;); animation: 9s linear 0s infinite normal none running traveler;"></circle>

          <g fill="none" stroke="rgba(76,172,255,.25)" stroke-width="1">
            <circle cx="181.6" cy="136.8" r="14"></circle>
            <circle cx="377.3" cy="179.7" r="14"></circle>
            <circle cx="332.6" cy="320.7" r="14"></circle>
            <circle cx="271.1" cy="421.9" r="14" stroke="rgba(199,255,50,.45)"></circle>
          </g>
          <g fill="none" stroke="rgba(76,172,255,.3)" stroke-width="1" stroke-dasharray="3 4">
            <path d="M181.6 122.8 V114.8"></path>
            <path d="M377.3 165.7 V157.7"></path>
            <path d="M332.6 306.7 V298.7"></path>
            <path d="M271.1 435.9 V443.9"></path>
          </g>

        </svg>
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_880e92b1 = ['v1' => ['left: 28.38%; bottom: 77.63%;'], 'v2' => ['left: 58.95%; bottom: 69.69%;'], 'v3' => ['left: 51.97%; bottom: 43.57%;']]; $vk_880e92b1 = $vt_880e92b1[$r1['variant'] ?? 'v1'] ?? $vt_880e92b1['v1']; ?><div data-jlabel dir="rtl" style="<?= esc_attr($vk_880e92b1[0] ?? '') ?>"><i></i><?= esc_html($r1['label'] ?? '') ?></div><?php endforeach; ?>
        <div data-jlabel data-final="true" dir="rtl" style="left: 42.36%; top: 81.09%;"><i></i><?= esc_html($f['label'] ?? '') ?></div>
      </div>
    </div>
  </section>
