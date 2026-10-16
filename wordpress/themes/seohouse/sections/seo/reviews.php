<?php
/**
 * Section "Reviews" — SEO House - SEO Service Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Reviews" data-reviews-source="google-maps" style="background: var(--sh-surface); color: var(--sh-ink);">
    <div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px clamp(32px, 3.6vw, 48px);">
      <div style="display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 16px;">
        <div>
          <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['title'])) : ?><h2 data-h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); margin: 10px 0px 0px; line-height: 1.3;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
        </div>
        <div style="display: flex; gap: 8px;">
          <button type="button" aria-label="السابق" class="hv-3fbe92" style="width: 48px; height: 48px; border-radius: 999px; border: 1px solid var(--sh-line); background: rgb(255, 255, 255); cursor: pointer; color: var(--sh-ink); font-size: 16px;">→</button>
          <button type="button" aria-label="التالي" class="hv-3fbe92" style="width: 48px; height: 48px; border-radius: 999px; border: 1px solid var(--sh-line); background: rgb(255, 255, 255); cursor: pointer; color: var(--sh-ink); font-size: 16px;">←</button>
        </div>
      </div>
      <?php if ( ! sh_dynamic_slot( 'reviews-slot' ) ) : ?><div data-grid="reviews" style="margin-top: 28px; display: grid; gap: 16px;">
        
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_ba5caaa2 = ['v1' => ['0'], 'v2' => ['1'], 'v3' => ['2']]; $vk_ba5caaa2 = $vt_ba5caaa2[$r1['variant'] ?? 'v1'] ?? $vt_ba5caaa2['v1']; ?><div data-rev="<?= esc_attr($vk_ba5caaa2[0] ?? '') ?>" style="background: rgb(255, 255, 255); border-radius: 20px; padding: 28px; box-shadow: rgba(6, 11, 31, 0.5) 0px 16px 36px -26px;">
            <div style="display: flex; align-items: center; gap: 12px;">
              <?php if (!empty($r1['label'])) : ?><div style="width: 44px; height: 44px; border-radius: 999px; background: rgba(40, 84, 232, 0.1); display: flex; align-items: center; justify-content: center; font-size: 11px; color: var(--sh-link);"><?= esc_html($r1['label'] ?? '') ?></div><?php endif; ?>
              <div>
                <?php if (!empty($r1['text'])) : ?><div style="font-weight: 600; font-size: 15px;"><?= esc_html($r1['text'] ?? '') ?></div><?php endif; ?>
                <?php if (!empty($r1['label_2'])) : ?><div style="font-size: 13px; color: var(--sh-text);"><?= esc_html($r1['label_2'] ?? '') ?></div><?php endif; ?>
              </div>
            </div>
            <div style="color: var(--sh-text); margin-top: 16px; letter-spacing: 2px;">★★★★★</div>
            <?php if (!empty($r1['text_2'])) : ?><p style="font-size: 15.5px; color: var(--sh-text); margin: 12px 0px 0px; text-wrap: pretty;"><?= esc_html($r1['text_2'] ?? '') ?></p><?php endif; ?>
            <?php if (!empty($r1['label_3'])) : ?><div style="margin-top: 16px; font-size: 11.5px; color: var(--sh-link); background: rgba(40, 84, 232, 0.08); border-radius: 999px; display: inline-block; padding: 5px 12px;"><?= esc_html($r1['label_3'] ?? '') ?></div><?php endif; ?>
          </div><?php endforeach; ?>
        
      </div><?php endif; ?>
      <div style="margin-top: 16px; display: flex; gap: 6px;">
        
          <button type="button" aria-label="التقييم 1" style="width: 28px; height: 5px; border-radius: 999px; border: 0px; cursor: pointer; background: rgb(40, 84, 232);"></button>
        
          <button type="button" aria-label="التقييم 2" style="width: 28px; height: 5px; border-radius: 999px; border: 0px; cursor: pointer; background: rgba(6, 11, 31, 0.2);"></button>
        
          <button type="button" aria-label="التقييم 3" style="width: 28px; height: 5px; border-radius: 999px; border: 0px; cursor: pointer; background: rgba(6, 11, 31, 0.2);"></button>
        
          <button type="button" aria-label="التقييم 4" style="width: 28px; height: 5px; border-radius: 999px; border: 0px; cursor: pointer; background: rgba(6, 11, 31, 0.2);"></button>
        
      </div>
    </div>
  </section>
