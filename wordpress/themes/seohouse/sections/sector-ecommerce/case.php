<?php
/**
 * Section "Case" — SEO House - Sector Ecommerce.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Case" style="position: relative; background: var(--sh-paper); color: var(--sh-ink); border-bottom: 1px solid rgba(var(--sh-ink-rgb), 0.08);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-ec-case style="display: grid; gap: clamp(24px, 3vw, 44px); align-items: center;">
        <div>
          <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-blue);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: clamp(34px, 4vw, 52px); color: var(--sh-blue); line-height: 1.05; margin-top: 14px; direction: ltr; unicode-bidi: isolate; text-align: end;"><?= esc_html($f['heading'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28; margin-top: 10px;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
          <?php if (!empty($f['text'])) : ?><p style="font-size: 16.5px; color: var(--sh-ink-soft); margin: 14px 0px 0px; max-width: 36em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
          <div data-ec-meta style="margin-top: 20px; display: grid; gap: 14px 20px; padding-top: 16px; border-top: 1px solid rgba(var(--sh-ink-rgb), 0.1);">
            <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="display: flex; flex-direction: column; gap: 4px;">
              <?php if (!empty($r1['label'])) : ?><span style="font-size: 12.5px; color: var(--sh-slate);"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
              <?php if (!empty($r1['heading'])) : ?><span style="font-size: 14.5px; font-weight: 700; color: var(--sh-ink);"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
            </div><?php endforeach; ?>
          </div>
        </div>
        <div style="min-width: 0px; background: rgb(255, 255, 255); border-radius: 16px; padding: 10px; box-shadow: rgba(var(--sh-ink-rgb), 0.55) 0px 20px 44px -32px;">
          <div style="display: flex; align-items: center; gap: 7px; padding: 4px 6px 10px;">
            <span aria-hidden="true" style="width: 9px; height: 9px; border-radius: 999px; background: rgba(var(--sh-ink-rgb), 0.16);"></span>
            <span aria-hidden="true" style="width: 9px; height: 9px; border-radius: 999px; background: rgba(var(--sh-ink-rgb), 0.12);"></span>
            <?php if (!empty($f['label'])) : ?><span style="margin-inline-start: auto; font-size: 11.5px; color: var(--sh-slate);"><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?>
          </div>
          <?php if (!empty(sh_link($f['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" target="_blank" rel="noopener" style="display: block; border-radius: 10px; overflow: hidden;">
            <?= sh_image($f['image'] ?? 0, ['style' => 'width: 100%; height: clamp(220px, 23vw, 300px); object-fit: contain; display: block; background: rgb(255, 255, 255);'], 'تقرير جوجل أناليتكس يوضح ارتفاع إيرادات البحث العضوي إلى 106,274 ريالًا') ?>
          </a><?php endif; ?>
        </div>
      </div>
    </div>
  </section>
