<?php
/**
 * Section "Hero" — SEO House - Homepage.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Hero" style="position: relative; overflow: hidden; background: var(--sh-ink);">
    <div aria-hidden="true" data-hero-bg style="position: absolute; inset: 0px; pointer-events: none; overflow: hidden;">
      <div style="position: absolute; inset: 0px; background: radial-gradient(60% 80% at 22% 45%, rgba(var(--sh-blue-rgb), 0.22), transparent 70%), radial-gradient(40% 55% at 85% 0%, rgba(var(--sh-sky-rgb), 0.1), transparent 70%), linear-gradient(var(--sh-ink) 0%, rgb(8, 18, 56) 100%);"></div>
      <div style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px); background-size: 64px 64px; mask-image: radial-gradient(70% 80% at 60% 30%, rgb(0, 0, 0), transparent 75%);"></div>
      <svg viewBox="0 0 1200 600" preserveAspectRatio="none" style="position: absolute; inset-inline: 0px; bottom: 0px; width: 100%; height: 62%; opacity: 0.16;"><g fill="none" stroke="#4CACFF" stroke-width="1.2"><path d="M0 520 C 180 510, 320 470, 470 455 S 760 380, 1200 250"></path><path d="M0 560 C 220 552, 380 520, 540 508 S 820 450, 1200 350" stroke-dasharray="3 8"></path></g><g fill="#C7FF32"><circle cx="470" cy="455" r="3"></circle><circle cx="820" cy="360" r="3"></circle></g></svg>
    </div>
    <div aria-hidden="true" style="position: absolute; inset: 0px; background: radial-gradient(48% 62% at 26% 50%, rgba(var(--sh-blue-rgb), 0.3), rgba(var(--sh-blue-rgb), 0.08) 55%, transparent 78%), radial-gradient(30% 40% at 12% 90%, rgba(var(--sh-sky-rgb), 0.1), transparent 70%); pointer-events: none;"></div>
    <div data-grid="hero" style="position: relative; max-width: 1320px; margin: 0px auto; padding: clamp(32px, 3.6vw, 54px) 20px clamp(36px, 3.8vw, 56px); display: grid; gap: clamp(28px, 3.2vw, 46px); align-items: center;">
      <div style="animation: 0.7s ease 0s 1 normal both running fadeUp;">
        <?php if (!empty($f['title'])) : ?><h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(30px, 3.4vw, 48px); line-height: 1.4; margin: 0px; max-width: 15em;"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 17.5px; line-height: 1.85; color: var(--sh-muted); max-width: 610px; margin: 18px 0px 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 14px 28px; margin-top: 28px;">
          <?php if (!empty($f['link_label'])) : ?><a href="#booking" class="hv-d5cb1d" style="background: var(--sh-lime); color: var(--sh-ink); font-weight: 700; font-size: 16.5px; min-height: 56px; display: inline-flex; align-items: center; justify-content: center; padding: 0px 28px; border-radius: 14px;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
          <a href="#results" data-ghost style="min-height: 56px; display: inline-flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 600; color: var(--sh-text); padding: 0px 16px; border-radius: 14px;"><?= esc_html($f['link_label_2'] ?? '') ?> <i data-ghost-arrow aria-hidden="true">←</i></a>
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
          <?php if (!empty(sh_link($f['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" style="display: inline-flex; align-items: center; gap: 8px; font-size: 14.5px; font-weight: 600; border-bottom: 1px solid rgba(var(--sh-sky-rgb), 0.5); padding-bottom: 2px;"><?= esc_html($f['link_label_3'] ?? '') ?> <span>←</span></a><?php endif; ?>
        </div>

      </div>
    </div>
  </section>
