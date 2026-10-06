<?php
/**
 * Section "Reviews" — SEO House - Homepage.
 * Generated from the approved design by tools/design-import/convert.js; maintained by hand since 2.7.1:
 * link to all Google reviews under the section (fields all_link / all_label of the section, else a Google
 * Maps link saved in the social links; shown only when a link is saved — no link is guessed). The reviews widget (Trustindex shortcode) is unchanged.
 * @sh-manual
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Reviews" style="background: var(--sh-bg); color: var(--sh-ink);">
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
        
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_ebf3d4cd = ['v1' => ['0'], 'v2' => ['1'], 'v3' => ['2']]; $vk_ebf3d4cd = $vt_ebf3d4cd[$r1['variant'] ?? 'v1'] ?? $vt_ebf3d4cd['v1']; ?><div data-rev="<?= esc_attr($vk_ebf3d4cd[0] ?? '') ?>" style="background: rgb(255, 255, 255); border-radius: 20px; padding: 28px; box-shadow: rgba(255, 255, 255, 0.5) 0px 16px 36px -26px;">
            <div style="display: flex; align-items: center; gap: 12px;">
              <?php if (!empty($r1['label'])) : ?><div style="width: 44px; height: 44px; border-radius: 999px; background: rgba(40, 84, 232, 0.1); display: flex; align-items: center; justify-content: center; font-size: 11px; color: var(--sh-link);"><?= esc_html($r1['label'] ?? '') ?></div><?php endif; ?>
              <div>
                <?php if (!empty($r1['text'])) : ?><div style="font-weight: 600; font-size: 15px;"><?= esc_html($r1['text'] ?? '') ?></div><?php endif; ?>
                <?php if (!empty($r1['label_2'])) : ?><div style="font-size: 13px; color: var(--sh-text);"><?= esc_html($r1['label_2'] ?? '') ?></div><?php endif; ?>
              </div>
            </div>
            <div style="color: var(--sh-text); margin-top: 16px; letter-spacing: 2px;">★★★★★</div>
            <?php if (!empty($r1['text_2'])) : ?><p style="font-size: 15.5px; color: var(--sh-text); margin: 12px 0px 0px; text-wrap: pretty;"><?= esc_html($r1['text_2'] ?? '') ?></p><?php endif; ?>
            <?php if (!empty($r1['label_3'])) : ?><div style="margin-top: 16px; font-size: 11.5px; color: var(--sh-link); background: rgba(40, 84, 232, 0.04); border-radius: 999px; display: inline-block; padding: 5px 12px;"><?= esc_html($r1['label_3'] ?? '') ?></div><?php endif; ?>
          </div><?php endforeach; ?>
        
      </div><?php endif; ?>
      <div style="margin-top: 16px; display: flex; gap: 6px;">
        
          <button type="button" aria-label="التقييم 1" style="width: 28px; height: 5px; border-radius: 999px; border: 0px; cursor: pointer; background: rgb(40, 84, 232);"></button>
        
          <button type="button" aria-label="التقييم 2" style="width: 28px; height: 5px; border-radius: 999px; border: 0px; cursor: pointer; background: rgba(6, 11, 31, 0.2);"></button>
        
          <button type="button" aria-label="التقييم 3" style="width: 28px; height: 5px; border-radius: 999px; border: 0px; cursor: pointer; background: rgba(6, 11, 31, 0.2);"></button>
        
          <button type="button" aria-label="التقييم 4" style="width: 28px; height: 5px; border-radius: 999px; border: 0px; cursor: pointer; background: rgba(6, 11, 31, 0.2);"></button>
        
      </div>
      <?php $all_url = sh_google_reviews_url( $f ); if ( '' !== $all_url ) : ?>
      <div style="margin-top: 22px; text-align: center;">
        <a href="<?= esc_url( $all_url ) ?>" target="_blank" rel="noopener" data-reviews-all class="hv-54a5cb" style="display: inline-flex; align-items: center; gap: 8px; min-height: 44px; font-size: 15px; font-weight: 600; color: var(--sh-link); border-bottom: 1px solid rgba(40, 84, 232, 0.45);"><?= esc_html( '' !== trim( (string) ( $f['all_label'] ?? '' ) ) ? $f['all_label'] : __( 'شاهد جميع المراجعات على Google', 'seohouse' ) ) ?> <span aria-hidden="true">↗</span><span class="screen-reader-text"><?php esc_html_e( '(يفتح في نافذة جديدة)', 'seohouse' ); ?></span></a>
      </div>
      <?php endif; ?>
    </div>
  </section>
