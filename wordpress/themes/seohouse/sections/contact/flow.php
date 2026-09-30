<?php
/**
 * Section "Flow" — SEO House - Contact.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Flow" style="position: relative; overflow: hidden; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
    <div aria-hidden="true" style="position: absolute; inset: 0px; background: radial-gradient(45% 60% at 85% 30%, rgba(var(--sh-blue-rgb), 0.22), transparent 72%), radial-gradient(35% 50% at 10% 90%, rgba(var(--sh-blue-rgb), 0.12), transparent 70%); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-ct-grid style="display: grid; gap: clamp(26px, 3.2vw, 52px); align-items: center;">
        <div>
          <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
          <div style="margin-top: 20px; display: flex; flex-direction: column;">
            <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="display: flex; align-items: flex-start; gap: 16px; padding: 16px 0px; border-top: 1px solid rgba(255, 255, 255, 0.12);">
              <span aria-hidden="true" style="<?= esc_attr($i1 === 0 ? 'flex: 0 0 auto; width: 38px; height: 38px; border-radius: 11px; background: rgba(var(--sh-lime-rgb), 0.12); display: flex; align-items: center; justify-content: center;' : 'flex: 0 0 auto; width: 38px; height: 38px; border-radius: 11px; background: rgba(var(--sh-blue-rgb), 0.22); display: flex; align-items: center; justify-content: center;') ?>"><?= sh_icon($r1['icon'] ?? 'ib0043c43') ?></span>
              <span style="min-width: 0px;"><?php if (!empty($r1['heading'])) : ?><span style="display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16.5px;"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?><?php if (!empty($r1['text'])) : ?><span style="display: block; font-size: 15px; color: var(--sh-muted); margin-top: 6px; text-wrap: pretty;"><?= esc_html($r1['text'] ?? '') ?></span><?php endif; ?></span>
            </div><?php endforeach; ?>
          </div>
          <div style="margin-top: 22px; display: flex; flex-wrap: wrap; gap: 12px 16px; align-items: center;">
            <?php $r1_list = $f['items_2'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty(sh_link($r1['link'] ?? '')) && !empty($r1['link_label'])) : ?><a href="<?= esc_url(sh_link($r1['link'] ?? '')) ?>" style="font-size: 15px; font-weight: 600;"><?= esc_html($r1['link_label'] ?? '') ?></a><?php endif; ?><?php endforeach; ?>
          </div>
        </div>

        <div id="booking" data-dbg="true" style="scroll-margin-top: 88px; background: rgb(251, 252, 254); color: var(--sh-ink); border-radius: 18px; box-shadow: rgba(0, 0, 0, 0.75) 0px 30px 64px -38px; padding: clamp(20px, 2.4vw, 32px);">
          
            <div>
              <?php if (!empty($f['eyebrow_2'])) : ?><div style="font-size: 13px; font-weight: 600; color: var(--sh-blue);"><?= esc_html($f['eyebrow_2'] ?? '') ?></div><?php endif; ?>
              <?php if (!empty($f['title'])) : ?><h2 data-ct-h style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); line-height: 1.32; margin: 12px 0px 0px;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
              <div data-ct-fields style="margin-top: 20px; display: grid; gap: 14px;">
                
                  <?php $r1_list = $f['items_3'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_d11496eb = ['v1' => ['text', 'rtl', 'الاسم الكامل'], 'v2' => ['text', 'rtl', 'اسم الشركة'], 'v3' => ['email', 'ltr', 'name@company.com'], 'v4' => ['tel', 'ltr', '+966'], 'v5' => ['url', 'ltr', 'https://']]; $vk_d11496eb = $vt_d11496eb[$r1['variant'] ?? 'v1'] ?? $vt_d11496eb['v1']; ?><label style="<?= esc_attr($i1 === $i1_n - 1 ? 'display: flex; flex-direction: column; gap: 8px; font-size: 14px; font-weight: 600; color: var(--sh-ink); grid-column: 1 / -1;' : 'display: flex; flex-direction: column; gap: 8px; font-size: 14px; font-weight: 600; color: var(--sh-ink); grid-column: auto;') ?>">
                    <?php if (!empty($r1['label'])) : ?><span><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
                    <input type="<?= esc_attr($vk_d11496eb[0] ?? '') ?>" dir="<?= esc_attr($vk_d11496eb[1] ?? '') ?>" placeholder="<?= esc_attr($vk_d11496eb[2] ?? '') ?>" value="" style="min-height: 52px; border: 1.5px solid rgba(var(--sh-ink-rgb), 0.16); background: var(--sh-paper); padding: 0px 14px; font-size: 16px; border-radius: 13px; color: var(--sh-ink); text-align: start;">
                  </label><?php endforeach; ?>
                
                <?php $r1_list = $f['items_4'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><label style="display: flex; flex-direction: column; gap: 8px; font-size: 14px; font-weight: 600; color: var(--sh-ink);">
                  <?php if (!empty($r1['label'])) : ?><span><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
                  <select style="min-height: 52px; border: 1.5px solid rgba(var(--sh-ink-rgb), 0.16); background: var(--sh-paper); padding: 0px 14px; font-size: 16px; border-radius: 13px; color: var(--sh-ink);">
                    
                      <?php $r2_list = $r1['items'] ?? []; $i2_n = is_array($r2_list) ? count($r2_list) : 0; foreach ((array) $r2_list as $i2 => $r2) : ?><?php $vt_6ccb98d4 = ['v1' => ['السعودية'], 'v2' => ['مصر'], 'v3' => ['الإمارات'], 'v4' => ['سوق آخر'], 'v5' => ['تحسين محركات البحث'], 'v6' => ['تصميم وتطوير موقع'], 'v7' => ['تصميم وتطوير متجر'], 'v8' => ['رفع المنتجات وإدارة المحتوى'], 'v9' => ['غير محدد بعد']]; $vk_6ccb98d4 = $vt_6ccb98d4[$r2['variant'] ?? 'v1'] ?? $vt_6ccb98d4['v1']; ?><?php if (!empty($r2['label'])) : ?><option value="<?= esc_attr($vk_6ccb98d4[0] ?? '') ?>"><?= esc_html($r2['label'] ?? '') ?></option><?php endif; ?><?php endforeach; ?>
                    
                  </select>
                </label><?php endforeach; ?>
                <label style="display: flex; flex-direction: column; gap: 8px; font-size: 14px; font-weight: 600; color: var(--sh-ink); grid-column: 1 / -1;">
                  <?php if (!empty($f['label'])) : ?><span><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?>
                  <textarea rows="4" placeholder="اكتب باختصار ما تريد تحقيقه أو ما يعطّل موقعك حاليًا" style="border: 1.5px solid rgba(var(--sh-ink-rgb), 0.16); background: var(--sh-paper); padding: 12px 14px; font-size: 16px; border-radius: 13px; color: var(--sh-ink); resize: vertical; text-align: start;"></textarea>
                </label>
              </div>
              
              <?php if (!empty($f['button'])) : ?><button type="button" class="hv-d5cb1d" style="margin-top: 22px; width: 100%; background: var(--sh-lime); color: var(--sh-ink); font-weight: 700; font-size: 16.5px; min-height: 56px; padding: 0px 30px; border: 0px; border-radius: 14px; cursor: pointer; box-shadow: rgba(122, 160, 0, 0.8) 0px 12px 26px -16px; transition: background 0.2s;"><?= esc_html($f['button'] ?? '') ?></button><?php endif; ?>
              <?php if (!empty($f['label_2'])) : ?><div style="margin-top: 12px; font-size: 13px; color: var(--sh-slate);"><?= esc_html($f['label_2'] ?? '') ?></div><?php endif; ?>
            </div>
          

          
        </div>
      </div>
    </div>
  </section>
