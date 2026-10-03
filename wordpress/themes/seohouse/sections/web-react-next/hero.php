<?php
/**
 * Section "Hero" — SEO House - React Next.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Hero" style="position: relative; overflow: hidden; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
    <div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px); background-size: 72px 72px; mask-image: radial-gradient(90% 100% at 50% 0%, rgb(0, 0, 0), transparent 68%); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: 18px 20px 0px;">
      <?php sh_breadcrumbs(); ?>
    </div>

    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(22px, 2.6vw, 38px) 20px clamp(36px, 4vw, 56px);">
      <div style="max-width: 40em; animation: 0.7s ease 0s 1 normal both running fadeUp;">
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(28px, 3.2vw, 44px); line-height: 1.3; margin: 12px 0px 0px;"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 17px; line-height: 1.85; color: var(--sh-muted); margin: 16px 0px 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 14px 24px; margin-top: 26px;">
          <?php if (!empty($f['link_label'])) : ?><a href="#booking" class="hv-d5cb1d" style="background: var(--sh-lime); color: var(--sh-ink); font-weight: 700; font-size: 16.5px; min-height: 56px; display: inline-flex; align-items: center; padding: 0px 28px; border-radius: 14px;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
          <a href="#when" data-ghost style="min-height: 56px; display: inline-flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 600; color: var(--sh-text); padding: 0px 16px; border-radius: 14px;"><?= esc_html($f['link_label_2'] ?? '') ?> <i data-ghost-arrow aria-hidden="true">↓</i></a>
        </div>
      </div>

      <div style="margin-top: clamp(28px, 3.2vw, 44px); display: flex; flex-direction: column; gap: 10px; animation: 0.7s ease 0.12s 1 normal both running fadeUp;">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_f40a4dc6 = ['v1' => ['display: flex; flex-wrap: wrap; align-items: center; gap: 10px 18px; border-radius: 14px; background: rgba(255, 255, 255, 0.04); border-inline-start: 3px solid var(--sh-lime); padding: 16px 20px; margin-inline-start: 0px;'], 'v2' => ['display: flex; flex-wrap: wrap; align-items: center; gap: 10px 18px; border-radius: 14px; background: rgba(255, 255, 255, 0.04); border-inline-start: 3px solid var(--sh-sky); padding: 16px 20px; margin-inline-start: 18px;'], 'v3' => ['display: flex; flex-wrap: wrap; align-items: center; gap: 10px 18px; border-radius: 14px; background: rgba(255, 255, 255, 0.04); border-inline-start: 3px solid var(--sh-sky); padding: 16px 20px; margin-inline-start: 36px;'], 'v4' => ['display: flex; flex-wrap: wrap; align-items: center; gap: 10px 18px; border-radius: 14px; background: rgba(255, 255, 255, 0.04); border-inline-start: 3px solid var(--sh-sky); padding: 16px 20px; margin-inline-start: 54px;']]; $vk_f40a4dc6 = $vt_f40a4dc6[$r1['variant'] ?? 'v1'] ?? $vt_f40a4dc6['v1']; ?><div style="<?= esc_attr($vk_f40a4dc6[0] ?? '') ?>">
          <?php if (!empty($r1['heading'])) : ?><span style="<?= esc_attr($i1 === 0 ? 'flex: 0 0 auto; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16.5px; color: var(--sh-lime);' : 'flex: 0 0 auto; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16.5px; color: var(--sh-text);') ?>"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($r1['label'])) : ?><span style="flex: 1 1 280px; font-size: 14.5px; color: var(--sh-muted);"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
        </div><?php endforeach; ?>
        <div style="display: flex; align-items: center; gap: 10px; margin-top: 4px; font-size: 13px; color: var(--sh-crumb);">
          <span aria-hidden="true" style="flex: 1 1 auto; height: 1px; background: linear-gradient(90deg, rgba(var(--sh-lime-rgb), 0.4), rgba(var(--sh-sky-rgb), 0.2), transparent);"></span>
          <?php if (!empty($f['label'])) : ?><span><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?>
        </div>
      </div>
    </div>
  </section>
