<?php
/**
 * Section "Case" — SEO House - Sector Health.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Case" style="position: relative; background: var(--sh-surface); border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head data-center>
          <div style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><span aria-hidden="true" data-sx-ico style="flex: 0 0 auto; width: 30px; height: 30px; border-radius: 9px; background: rgba(var(--sh-blue-rgb), 0.22); display: inline-flex; align-items: center; justify-content: center;"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="#C7FF32" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20V10M10 20V4M16 20v-7M22 20H2"></path></svg></span><?php if (!empty($f['label'])) : ?><span><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?></div>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <?php if (!empty($f['text'])) : ?><p data-center style="font-size: 16.5px; color: var(--sh-muted); margin: 14px 0px 0px; max-width: 48em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>

      <div data-hc-strip style="margin-top: clamp(24px, 2.8vw, 36px); display: grid; gap: 0px; border-radius: 18px; overflow: hidden; background: rgba(255, 255, 255, 0.04);">
        <div style="padding: clamp(20px, 2.4vw, 28px); display: flex; flex-direction: column; justify-content: center; gap: 6px;">
          <?php if (!empty($f['label_2'])) : ?><div style="font-size: 12.5px; color: var(--sh-crumb);"><?= esc_html($f['label_2'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: clamp(26px, 2.8vw, 34px); color: var(--sh-dim); line-height: 1.1;"><?= esc_html($f['heading'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['label_3'])) : ?><div style="font-size: 13px; color: var(--sh-crumb);"><?= esc_html($f['label_3'] ?? '') ?></div><?php endif; ?>
        </div>
        <div aria-hidden="true" data-hc-pulse style="display: flex; align-items: center; justify-content: center; padding: 0px 6px;">
          <svg viewBox="0 0 120 60" preserveAspectRatio="none" style="width: 100%; height: 56px; display: block;">
            <path d="M2 46 H26 l7 -12 6 24 7 -34 8 40 6 -18 H72 l8 -22 L118 12" fill="none" stroke="#4CACFF" stroke-width="1.8" stroke-linejoin="round" stroke-linecap="round" opacity=".75"></path>
          </svg>
        </div>
        <div style="padding: clamp(20px, 2.4vw, 28px); display: flex; flex-direction: column; justify-content: center; gap: 6px; background: rgba(var(--sh-lime-rgb), 0.08);">
          <?php if (!empty($f['label_4'])) : ?><div style="font-size: 12.5px; color: var(--sh-crumb);"><?= esc_html($f['label_4'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['heading_2'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: clamp(26px, 2.8vw, 34px); color: var(--sh-lime); line-height: 1.1;"><?= esc_html($f['heading_2'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['label_5'])) : ?><div style="font-size: 13px; color: var(--sh-crumb);"><?= esc_html($f['label_5'] ?? '') ?></div><?php endif; ?>
        </div>
      </div>

      <div data-hc-meta style="margin-top: 18px; display: grid; gap: 14px 20px; padding-top: 16px; border-top: 1px solid rgba(255, 255, 255, 0.1);">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="display: flex; flex-direction: column; gap: 4px;">
          <?php if (!empty($r1['label'])) : ?><span style="font-size: 12.5px; color: var(--sh-crumb);"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($r1['heading'])) : ?><span style="font-size: 14.5px; font-weight: 700; color: var(--sh-text);"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
        </div><?php endforeach; ?>
      </div>

      <div style="margin-top: 20px; border-radius: 16px; background: rgba(255, 255, 255, 0.05); padding: 10px;">
        <div style="display: flex; align-items: center; gap: 8px; padding: 4px 6px 10px; font-size: 11.5px; color: var(--sh-crumb);">
          <span aria-hidden="true" style="width: 7px; height: 7px; border-radius: 999px; background: rgba(255, 255, 255, 0.2);"></span>
          <?php if (!empty($f['label_6'])) : ?><span style="margin-inline-start: auto;"><?= esc_html($f['label_6'] ?? '') ?></span><?php endif; ?>
        </div>
        <?php if (!empty(sh_link($f['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" target="_blank" rel="noopener" style="display: block; border-radius: 10px; overflow: hidden;">
          <?= sh_image($f['image'] ?? 0, ['style' => 'width: 100%; height: clamp(200px, 20vw, 280px); object-fit: contain; display: block; background: rgb(255, 255, 255);'], 'لوحة سيرش كونسول تظهر 12.9 ألف نقرة خلال 28 يومًا مقابل 825 نقرة في الفترة السابقة') ?>
        </a><?php endif; ?>
      </div>
    </div>
  </section>
