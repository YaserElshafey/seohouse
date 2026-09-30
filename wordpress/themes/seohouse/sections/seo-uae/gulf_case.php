<?php
/**
 * Section "Gulf case" — SEO House - UAE SEO Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'uae-case')) ?>" data-screen-label="Gulf case" style="background: var(--sh-paper); color: var(--sh-ink);">
    <div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head data-sh-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-blue);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>

      <div data-ua-case style="margin-top: clamp(20px, 2.4vw, 32px); display: grid; gap: clamp(24px, 3vw, 46px); align-items: center;">
        <div>
          <?php if (!empty($f['text'])) : ?><p style="font-size: 16.5px; color: var(--sh-ink-soft); margin: 0px; max-width: 38em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        <?php if (!empty($f['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: clamp(30px, 3.4vw, 44px); color: var(--sh-blue); line-height: 1.1; margin-top: 20px;"><?= esc_html($f['heading'] ?? '') ?></div><?php endif; ?>
        <div data-ua-meta style="margin-top: 22px; display: grid; gap: 16px 20px; padding-top: 18px; border-top: 1px solid rgba(var(--sh-ink-rgb), 0.1);">
          
            <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div data-ua-meta-item style="display: flex; flex-direction: column; gap: 4px;">
              <?php if (!empty($r1['label'])) : ?><span style="font-size: 12.5px; color: var(--sh-slate);"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
              <?php if (!empty($r1['heading'])) : ?><span style="font-size: 14.5px; font-weight: 700; color: var(--sh-ink);"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
            </div><?php endforeach; ?>
          
        </div>
      </div>
      <div style="min-width: 0px; background: rgb(255, 255, 255); border-radius: 16px; padding: 10px; box-shadow: rgba(var(--sh-ink-rgb), 0.55) 0px 20px 44px -32px;">
        <div style="display: flex; align-items: center; gap: 7px; padding: 4px 6px 10px;">
          <span aria-hidden="true" style="width: 9px; height: 9px; border-radius: 999px; background: rgba(var(--sh-ink-rgb), 0.16);"></span>
          <span aria-hidden="true" style="width: 9px; height: 9px; border-radius: 999px; background: rgba(var(--sh-ink-rgb), 0.12);"></span>
          <span aria-hidden="true" style="width: 9px; height: 9px; border-radius: 999px; background: rgba(var(--sh-ink-rgb), 0.08);"></span>
          <?php if (!empty($f['label'])) : ?><span style="margin-inline-start: auto; font-size: 11.5px; color: var(--sh-slate);"><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?>
        </div>
        <?php if (!empty(sh_link($f['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" target="_blank" rel="noopener" title="افتح التقرير بالحجم الكامل" style="display: block; border-radius: 10px; overflow: hidden;">
          <?= sh_image($f['image'] ?? 0, ['style' => 'width: 100%; height: clamp(220px, 23vw, 300px); object-fit: contain; display: block; background: rgb(255, 255, 255);'], 'تقرير جوجل أناليتكس يوضح نمو الإيرادات العضوية بنسبة 253.05%') ?>
        </a><?php endif; ?>
        </div>
      </div>
    </div>
  </section>
