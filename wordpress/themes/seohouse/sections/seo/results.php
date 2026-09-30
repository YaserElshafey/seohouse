<?php
/**
 * Section "Results" — SEO House - SEO Service Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Results" style="border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
    <div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div style="display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 16px;">
        <div>
          <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['title'])) : ?><h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); margin: 12px 0px 0px; line-height: 1.3;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
        </div>
        <?php if (!empty(sh_link($f['link'] ?? '')) && !empty($f['link_label'])) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" style="font-size: 15px; font-weight: 600;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
      </div>

      <div data-case-tabs role="tablist" style="margin-top: 26px; display: flex; gap: 10px; overflow-x: auto; scrollbar-width: none; padding-bottom: 4px;">
        
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty($r1['button'])) : ?><button type="button" role="tab" aria-selected="<?= esc_attr($i1 === 0 ? 'true' : 'false') ?>" style="<?= esc_attr($i1 === 0 ? 'flex: 0 0 auto; min-height: 48px; padding: 0px 20px; border-radius: 999px; cursor: pointer; font-size: 15px; font-weight: 600; white-space: nowrap; background: var(--sh-lime); color: var(--sh-ink); border: 1px solid var(--sh-lime); transition: background 0.25s, color 0.25s;' : 'flex: 0 0 auto; min-height: 48px; padding: 0px 20px; border-radius: 999px; cursor: pointer; font-size: 15px; font-weight: 600; white-space: nowrap; background: transparent; color: var(--sh-text); border: 1px solid rgba(255, 255, 255, 0.22); transition: background 0.25s, color 0.25s;') ?>"><?= esc_html($r1['button'] ?? '') ?></button><?php endif; ?><?php endforeach; ?>
        
      </div>

      <div data-case style="margin-top: 18px; display: grid; gap: clamp(22px, 2.6vw, 40px); align-items: center; border-radius: 24px; background: linear-gradient(150deg, var(--sh-surface), var(--sh-surface)); padding: clamp(22px, 2.6vw, 34px); box-shadow: rgba(0, 0, 0, 0.9) 0px 24px 54px -34px;">
        <div style="min-width: 0px;">
          <div style="display: flex; flex-wrap: wrap; gap: 8px;">
            <?php $r1_list = $f['items_2'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty($r1['label'])) : ?><span style="font-size: 13.5px; color: var(--sh-muted); background: rgba(255, 255, 255, 0.06); border-radius: 999px; padding: 7px 13px;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?><?php endforeach; ?>
          </div>
          <?php if (!empty($f['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: clamp(34px, 4vw, 54px); color: var(--sh-lime); line-height: 1.08; margin-top: 16px;"><?= esc_html($f['heading'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['heading_2'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 20px; margin-top: 10px;"><?= esc_html($f['heading_2'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['text'])) : ?><p style="font-size: 16px; color: var(--sh-crumb-current); margin: 12px 0px 0px; max-width: 34em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
          <div style="display: flex; flex-wrap: wrap; gap: 8px 26px; margin-top: 18px; font-size: 13.5px; color: var(--sh-crumb);">
            <?php $r1_list = $f['items_3'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty($r1['label'])) : ?><span><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?><?php endforeach; ?>
          </div>
          <?php if (!empty(sh_link($f['link_2'] ?? ''))) : ?><a href="<?= esc_url(sh_link($f['link_2'] ?? '')) ?>" style="display: inline-flex; align-items: center; gap: 8px; margin-top: 18px; font-size: 15px; font-weight: 600; color: var(--sh-lime); border-bottom: 1px solid rgba(var(--sh-lime-rgb), 0.45); padding-bottom: 3px;"><?= esc_html($f['link_label_2'] ?? '') ?> <span>←</span></a><?php endif; ?>
        </div>

        <div style="min-width: 0px; border-radius: 16px; background: rgba(255, 255, 255, 0.06); padding: 10px;">
          <div style="display: flex; align-items: center; gap: 7px; padding: 4px 6px 10px;">
            <span aria-hidden="true" style="width: 9px; height: 9px; border-radius: 999px; background: rgba(255, 255, 255, 0.25);"></span>
            <span aria-hidden="true" style="width: 9px; height: 9px; border-radius: 999px; background: rgba(255, 255, 255, 0.18);"></span>
            <span aria-hidden="true" style="width: 9px; height: 9px; border-radius: 999px; background: rgba(255, 255, 255, 0.12);"></span>
            <?php if (!empty($f['label'])) : ?><span style="margin-inline-start: auto; font-size: 11.5px; color: var(--sh-crumb);"><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?>
          </div>
          <?= sh_image($f['image'] ?? 0, ['style' => 'width: 100%; height: 300px; object-fit: contain; display: block; border-radius: 10px; background: rgb(255, 255, 255);'], 'تقرير جوجل أناليتكس يوضح إيرادات البحث العضوي 106,274 ريالًا مقابل 19,956 ريالًا') ?>
        </div>
      </div>
    </div>
  </section>
