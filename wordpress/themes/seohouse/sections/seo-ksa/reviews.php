<?php
/**
 * Section "Reviews" — SEO House - Saudi SEO Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Reviews" style="background: var(--sh-surface); color: var(--sh-ink);">
    <div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div style="display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 16px;">
        <div>
          <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
          <button type="button" aria-label="التقييم السابق" class="hv-58e92e" style="width: 46px; height: 46px; border-radius: 999px; border: 1.5px solid var(--sh-line); background: rgb(255, 255, 255); color: var(--sh-ink); font-size: 18px; cursor: pointer; transition: background 0.2s, border-color 0.2s;">→</button>
          <button type="button" aria-label="التقييم التالي" class="hv-58e92e" style="width: 46px; height: 46px; border-radius: 999px; border: 1.5px solid var(--sh-line); background: rgb(255, 255, 255); color: var(--sh-ink); font-size: 18px; cursor: pointer; transition: background 0.2s, border-color 0.2s;">←</button>
        </div>
      </div>

      
      <?php if ( ! sh_dynamic_slot( 'reviews-slot' ) ) : ?><div data-rev-grid style="margin-top: 24px; display: grid; gap: 16px;">
        
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div data-rev="<?= esc_attr($i1 === 0 ? '0' : '1') ?>" style="background: rgb(255, 255, 255); border-radius: 18px; padding: clamp(20px, 2.2vw, 26px); box-shadow: rgba(6, 11, 31, 0.5) 0px 16px 36px -28px; display: flex; flex-direction: column; gap: 12px;">
            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 10px 14px;">
              <span style="color: var(--sh-text); letter-spacing: 2px; font-size: 16px;">★★★★★</span>
              <?php if (!empty($r1['label'])) : ?><span style="font-size: 13px; color: var(--sh-text);"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
            </div>
            <?php if (!empty($r1['text'])) : ?><p style="font-size: 15.5px; color: var(--sh-text); margin: 0px; text-wrap: pretty;"><?= esc_html($r1['text'] ?? '') ?></p><?php endif; ?>
            <?php if (!empty($r1['label_2'])) : ?><div style="margin-top: auto; padding-top: 12px; border-top: 1px solid var(--sh-line); font-size: 14.5px; font-weight: 600;"><?= esc_html($r1['label_2'] ?? '') ?></div><?php endif; ?>
          </div><?php endforeach; ?>
        
      </div><?php endif; ?>

      <div style="margin-top: 18px; display: flex; align-items: center; gap: 10px;">
        
          <button type="button" aria-label="التقييم 1" style="width: 26px; height: 5px; border-radius: 999px; border: 0px; padding: 0px; cursor: pointer; background: rgb(40, 84, 232);"></button>
        
          <button type="button" aria-label="التقييم 2" style="width: 26px; height: 5px; border-radius: 999px; border: 0px; padding: 0px; cursor: pointer; background: rgba(6, 11, 31, 0.2);"></button>
        
          <button type="button" aria-label="التقييم 3" style="width: 26px; height: 5px; border-radius: 999px; border: 0px; padding: 0px; cursor: pointer; background: rgba(6, 11, 31, 0.2);"></button>
        
        <?php if (!empty(sh_link($f['link'] ?? '')) && !empty($f['link_label'])) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" style="margin-inline-start: auto; font-size: 14.5px; font-weight: 600; color: var(--sh-link); border-bottom: 1px solid rgba(40, 84, 232, 0.4); padding-bottom: 3px;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
      </div>
    </div>
  </section>
