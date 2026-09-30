<?php
/**
 * Section "Cases" — SEO House - Results.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Cases" style="background: var(--sh-paper-2); color: var(--sh-ink); min-height: 40vh;">
    <div style="max-width: 1100px; margin: 0px auto; padding: clamp(26px, 3vw, 40px) 20px clamp(40px, 4.6vw, 64px);">
      <div role="group" aria-label="تصفية النتائج" style="display: flex; flex-wrap: wrap; gap: 8px;">
        
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty($r1['button'])) : ?><button type="button" aria-pressed="<?= esc_attr($i1 === 0 ? 'true' : 'false') ?>" style="<?= esc_attr($i1 === 0 ? 'min-height: 40px; padding: 0px 16px; border-radius: 999px; border: 1px solid var(--sh-blue); background: var(--sh-blue); color: rgb(255, 255, 255); font-style: inherit; font-variant: inherit; font-weight: 600; font-stretch: inherit; font-size: 14px; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-size-adjust: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; cursor: pointer;' : 'min-height: 40px; padding: 0px 16px; border-radius: 999px; border: 1px solid rgba(var(--sh-ink-rgb), 0.12); background: rgb(255, 255, 255); color: var(--sh-surface-3); font-style: inherit; font-variant: inherit; font-weight: 600; font-stretch: inherit; font-size: 14px; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-size-adjust: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; cursor: pointer;') ?>"><?= esc_html($r1['button'] ?? '') ?></button><?php endif; ?><?php endforeach; ?>
        
      </div>
      <ul style="list-style: none; margin: 22px 0px 0px; padding: 0px; display: flex; flex-direction: column; gap: 12px;">
        
          <?php $r1_list = $f['items_2'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><li>
            <?php if (!empty(sh_link($r1['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($r1['link'] ?? '')) ?>" data-res-card class="hv-012719" style="display: grid; gap: 14px 28px; align-items: center; background: rgb(255, 255, 255); border-radius: 16px; padding: clamp(18px, 2vw, 24px); box-shadow: rgba(var(--sh-ink-rgb), 0.5) 0px 14px 34px -28px, rgba(var(--sh-ink-rgb), 0.05) 0px 0px 0px 1px; color: var(--sh-ink); transition: box-shadow 0.2s;">
              <span style="display: block;">
                <?php if (!empty($r1['heading'])) : ?><span style="display: block; font-family: Alexandria, sans-serif; font-weight: 800; font-size: clamp(28px, 3vw, 38px); line-height: 1; color: var(--sh-blue);"><?= sh_inline($r1['heading'] ?? '') ?></span><?php endif; ?>
                <?php if (!empty($r1['eyebrow'])) : ?><span style="display: block; font-size: 14px; font-weight: 600; color: var(--sh-ink-soft); margin-top: 8px;"><?= esc_html($r1['eyebrow'] ?? '') ?></span><?php endif; ?>
              </span>
              <span style="display: block; min-width: 0px;">
                <?php if (!empty($r1['heading_2'])) : ?><span style="display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(17px, 1.6vw, 20px); line-height: 1.5;"><?= esc_html($r1['heading_2'] ?? '') ?></span><?php endif; ?>
                <span style="display: flex; flex-wrap: wrap; gap: 6px 16px; margin-top: 8px; font-size: 13.5px; color: var(--sh-slate);">
                  <?php $r2_list = $r1['items'] ?? []; $i2_n = is_array($r2_list) ? count($r2_list) : 0; foreach ((array) $r2_list as $i2 => $r2) : ?><?php if (!empty($r2['label'])) : ?><span style="white-space: nowrap;"><?= sh_inline($r2['label'] ?? '') ?></span><?php endif; ?><?php endforeach; ?>
                </span>
              </span>
              <?php if (!empty($r1['label'])) : ?><span data-res-go style="font-size: 14.5px; font-weight: 600; color: var(--sh-blue); white-space: nowrap;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
            </a><?php endif; ?>
          </li><?php endforeach; ?>
        
      </ul>
      <?php if (!empty($f['text'])) : ?><p style="margin: 18px 0px 0px; font-size: 14px; color: var(--sh-slate);"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
    </div>
  </section>
