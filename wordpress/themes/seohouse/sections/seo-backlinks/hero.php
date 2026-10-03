<?php
/**
 * Section "Hero" — SEO House - Backlinks.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Hero" style="position: relative; overflow: hidden; background: radial-gradient(60% 80% at 22% 45%, rgba(var(--sh-blue-rgb), 0.24), transparent 70%), var(--sh-ink);">
    <div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(var(--sh-sky-rgb), 0.9) 1px, transparent 1px); background-size: 72px 72px; mask-image: radial-gradient(90% 100% at 30% 0%, rgb(0, 0, 0), transparent 68%); pointer-events: none;"></div>
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
          <a href="#quality" data-ghost style="min-height: 56px; display: inline-flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 600; color: var(--sh-text); padding: 0px 16px; border-radius: 14px;"><?= esc_html($f['link_label_2'] ?? '') ?> <i data-ghost-arrow aria-hidden="true">↓</i></a>
        </div>
      </div>
      <div style="animation: 0.7s ease 0.12s 1 normal both running fadeUp;">
        <div aria-hidden="true" style="position: relative; border-radius: 20px; background: linear-gradient(160deg, rgb(22, 35, 106) 0%, var(--sh-surface) 60%); border: 1px solid rgba(var(--sh-sky-rgb), 0.2); padding: clamp(18px, 2.2vw, 24px); box-shadow: rgba(0, 0, 0, 0.9) 0px 24px 54px -36px;">
          <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; padding-bottom: 12px; border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
            <span style="display: flex; align-items: center; gap: 9px; font-size: 13.5px; font-weight: 600; color: var(--sh-text);"><span style="width: 28px; height: 28px; border-radius: 8px; background: rgba(var(--sh-blue-rgb), 0.3); display: inline-flex; align-items: center; justify-content: center;"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="#C7FF32" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.5-3.5"></path></svg></span><?= esc_html($f['eyebrow_2'] ?? '') ?></span>
            <?php if (!empty($f['label'])) : ?><span style="font-size: 11.5px; color: var(--sh-crumb);"><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?>
          </div>
          <div style="margin-top: 12px; display: flex; flex-direction: column; gap: 2px;">
            <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_9a76a732 = ['v1' => ['font-size: 12px; font-weight: 600; color: var(--sh-lime);'], 'v2' => ['font-size: 12px; font-weight: 600; color: var(--sh-sky);']]; $vk_9a76a732 = $vt_9a76a732[$r1['variant'] ?? 'v1'] ?? $vt_9a76a732['v1']; ?><div style="display: flex; align-items: center; gap: 10px; padding: 9px 2px; border-bottom: 1px dashed rgba(255, 255, 255, 0.07);"><span style="flex: 0 0 auto; display: inline-flex;"><?= sh_icon($r1['icon'] ?? 'i33c65835') ?></span><?php if (!empty($r1['label'])) : ?><span style="flex: 1 1 auto; font-size: 14px; color: var(--sh-crumb-current);"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?><?php if (!empty($r1['eyebrow'])) : ?><span style="<?= esc_attr($vk_9a76a732[0] ?? '') ?>"><?= esc_html($r1['eyebrow'] ?? '') ?></span><?php endif; ?></div><?php endforeach; ?>
          </div>
          <div style="margin-top: 14px; border-radius: 12px; background: rgba(255, 255, 255, 0.04); padding: 12px 14px;">
            <?php if (!empty($f['label_2'])) : ?><div style="font-size: 12px; color: var(--sh-crumb);"><?= esc_html($f['label_2'] ?? '') ?></div><?php endif; ?>
            <div style="margin-top: 8px; display: flex; flex-direction: column; gap: 6px;">
              <span style="height: 6px; width: 100%; border-radius: 4px; background: rgba(var(--sh-text-rgb), 0.12);"></span>
              <span style="display: flex; align-items: center; gap: 6px;"><span style="height: 6px; width: 38%; border-radius: 4px; background: rgba(var(--sh-text-rgb), 0.12);"></span><?php if (!empty($f['eyebrow_3'])) : ?><span style="font-size: 12px; font-weight: 600; color: var(--sh-sky); border-bottom: 1.5px solid var(--sh-sky);"><?= esc_html($f['eyebrow_3'] ?? '') ?></span><?php endif; ?><span style="height: 6px; flex: 1 1 0%; border-radius: 4px; background: rgba(var(--sh-text-rgb), 0.12);"></span></span>
              <span style="height: 6px; width: 72%; border-radius: 4px; background: rgba(var(--sh-text-rgb), 0.12);"></span>
            </div>
          </div>
          <div style="margin-top: 14px; display: grid; grid-template-columns: repeat(3, minmax(0px, 1fr)); gap: 6px; font-size: 12px; font-weight: 600; text-align: center;">
            <?php if (!empty($f['label_3'])) : ?><span style="border-radius: 9px; padding: 8px 4px; background: var(--sh-lime); color: var(--sh-ink);"><?= esc_html($f['label_3'] ?? '') ?></span><?php endif; ?>
            <?php if (!empty($f['label_4'])) : ?><span style="border-radius: 9px; padding: 8px 4px; background: rgba(var(--sh-sky-rgb), 0.12); color: var(--sh-sky);"><?= esc_html($f['label_4'] ?? '') ?></span><?php endif; ?>
            <?php if (!empty($f['label_5'])) : ?><span style="border-radius: 9px; padding: 8px 4px; background: rgba(255, 255, 255, 0.05); color: var(--sh-crumb);"><?= esc_html($f['label_5'] ?? '') ?></span><?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
