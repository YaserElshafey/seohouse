<?php
/**
 * Section "Process" — SEO House - SEO Service Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Process" style="border-top: 1px solid rgba(255, 255, 255, 0.1);">
    <div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
      <?php if (!empty($f['title'])) : ?><h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); margin: 12px 0px 0px; line-height: 1.3;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>

      <div data-rail style="margin-top: 30px; display: grid; gap: 14px;">
        
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><button type="button" aria-pressed="<?= esc_attr($i1 === 0 ? 'true' : 'false') ?>" style="<?= esc_attr($i1 === 0 ? 'text-align: start; cursor: pointer; background: none; border-width: 2px 0px 0px; border-top-style: solid; border-right-style: initial; border-bottom-style: initial; border-left-style: initial; border-top-color: var(--sh-lime); border-right-color: initial; border-bottom-color: initial; border-left-color: initial; border-image: initial; padding: 18px 0px 0px; color: var(--sh-text); transition: border-color 0.3s;' : 'text-align: start; cursor: pointer; background: none; border-width: 2px 0px 0px; border-top-style: solid; border-right-style: initial; border-bottom-style: initial; border-left-style: initial; border-top-color: rgba(var(--sh-sky-rgb), 0.28); border-right-color: initial; border-bottom-color: initial; border-left-color: initial; border-image: initial; padding: 18px 0px 0px; color: var(--sh-text); transition: border-color 0.3s;') ?>">
            <span style="<?= esc_attr($i1 === 0 ? 'display: block; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 13px; color: var(--sh-lime);' : 'display: block; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 13px; color: var(--sh-sky);') ?>"><?= esc_html(sprintf('%02d', $i1 + 1)) ?></span>
            <?php if (!empty($r1['heading'])) : ?><span style="<?= esc_attr($i1 === 0 ? 'display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18px; margin-top: 8px; color: rgb(255, 255, 255);' : 'display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18px; margin-top: 8px; color: var(--sh-text);') ?>"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
            <?php if (!empty($r1['label'])) : ?><span style="display: block; font-size: 14.5px; color: var(--sh-crumb); margin-top: 6px;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
          </button><?php endforeach; ?>
        
      </div>

      <div data-phase-panel style="margin-top: 18px; border-radius: 18px; background: rgba(var(--sh-sky-rgb), 0.08); padding: clamp(18px, 2vw, 24px); display: grid; gap: 18px 34px; align-items: start;">
        <div>
          <?php if (!empty($f['eyebrow_2'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: 13px; color: var(--sh-lime);"><?= esc_html($f['eyebrow_2'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 19px; margin-top: 6px;"><?= esc_html($f['heading'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['text'])) : ?><p style="font-size: 15px; color: var(--sh-muted); margin: 8px 0px 0px;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
          <?php if (!empty($f['label'])) : ?><div style="margin-top: 12px; font-size: 14.5px; color: var(--sh-lime);"><?= esc_html($f['label'] ?? '') ?></div><?php endif; ?>
        </div>
        <div>
          <?php if (!empty($f['eyebrow_3'])) : ?><div style="font-size: 13px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow_3'] ?? '') ?></div><?php endif; ?>
          <div style="margin-top: 10px; display: flex; flex-direction: column; gap: 8px;">
            
              <?php $r1_list = $f['items_2'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="display: flex; align-items: flex-start; gap: 10px; font-size: 15.5px; color: var(--sh-text);">
                <span aria-hidden="true" style="flex: 0 0 auto; color: var(--sh-lime); font-size: 13px; margin-top: 3px;">✓</span><?php if (!empty($r1['label'])) : ?><span><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
              </div><?php endforeach; ?>
            
          </div>
        </div>
      
        <div data-phase-viz aria-hidden="true" style="border-radius: 14px; background: var(--sh-surface); border: 1px solid rgba(var(--sh-sky-rgb), 0.18); padding: 14px 16px;">
          <div style="display: flex; align-items: center; gap: 7px; padding-bottom: 10px; border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
            <span style="width: 7px; height: 7px; border-radius: 999px; background: rgba(255, 255, 255, 0.18);"></span>
            <span style="width: 7px; height: 7px; border-radius: 999px; background: rgba(255, 255, 255, 0.12);"></span>
            <?php if (!empty($f['label_2'])) : ?><span style="margin-inline-start: auto; font-size: 12px; color: var(--sh-crumb);"><?= esc_html($f['label_2'] ?? '') ?></span><?php endif; ?>
          </div>
          <div style="margin-top: 12px; display: flex; flex-direction: column; gap: 11px;">
            
              <?php $r1_list = $f['items_3'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_c386a60c = ['v1' => ['height: 100%; width: 78%; border-radius: 999px; background: linear-gradient(90deg, var(--sh-blue), var(--sh-sky));'], 'v2' => ['height: 100%; width: 62%; border-radius: 999px; background: linear-gradient(90deg, var(--sh-blue), var(--sh-sky));'], 'v3' => ['height: 100%; width: 48%; border-radius: 999px; background: linear-gradient(90deg, var(--sh-blue), var(--sh-sky));']]; $vk_c386a60c = $vt_c386a60c[$r1['variant'] ?? 'v1'] ?? $vt_c386a60c['v1']; ?><div>
                <?php if (!empty($r1['label'])) : ?><div style="font-size: 12.5px; color: var(--sh-muted);"><?= esc_html($r1['label'] ?? '') ?></div><?php endif; ?>
                <div style="margin-top: 6px; height: 6px; border-radius: 999px; background: rgba(255, 255, 255, 0.07); overflow: hidden;"><div style="<?= esc_attr($vk_c386a60c[0] ?? '') ?>"></div></div>
              </div><?php endforeach; ?>
            
          </div>
        </div>
      </div>

      <div data-phase-acc style="margin-top: 18px;">
        
          <?php $r1_list = $f['items_4'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="border-top: 1px solid rgba(255, 255, 255, 0.12);">
            <button type="button" aria-expanded="<?= esc_attr($i1 === 0 ? 'true' : 'false') ?>" style="width: 100%; min-height: 60px; background: none; border: 0px; cursor: pointer; color: var(--sh-text); display: flex; align-items: center; gap: 14px; padding: 12px 0px; text-align: start;">
              <span style="<?= esc_attr($i1 === 0 ? 'font-family: Alexandria, sans-serif; font-weight: 800; font-size: 13px; color: var(--sh-lime);' : 'font-family: Alexandria, sans-serif; font-weight: 800; font-size: 13px; color: var(--sh-sky);') ?>"><?= esc_html(sprintf('%02d', $i1 + 1)) ?></span>
              <?php if (!empty($r1['heading'])) : ?><span style="flex: 1 1 auto; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17px;"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
              <span aria-hidden="true" style="flex: 0 0 auto; color: var(--sh-sky); font-size: 19px;"><?= esc_html($r1['symbol'] ?? '') ?></span>
            </button>
            <div style="<?= esc_attr($i1 === 0 ? 'display: block; padding: 0px 0px 18px;' : 'display: none; padding: 0px 0px 18px;') ?>">
              <?php if (!empty($r1['text'])) : ?><p style="font-size: 15px; color: var(--sh-muted); margin: 0px 0px 6px;"><?= esc_html($r1['text'] ?? '') ?></p><?php endif; ?>
              <?php if (!empty($r1['label'])) : ?><div style="font-size: 14.5px; color: var(--sh-lime); margin-bottom: 12px;"><?= esc_html($r1['label'] ?? '') ?></div><?php endif; ?>
              <div style="display: flex; flex-direction: column; gap: 8px;">
                
                  <?php $r2_list = $r1['items'] ?? []; $i2_n = is_array($r2_list) ? count($r2_list) : 0; foreach ((array) $r2_list as $i2 => $r2) : ?><div style="display: flex; align-items: flex-start; gap: 10px; font-size: 15.5px; color: var(--sh-text);">
                    <span aria-hidden="true" style="flex: 0 0 auto; color: var(--sh-lime); font-size: 13px; margin-top: 3px;">✓</span><?php if (!empty($r2['label'])) : ?><span><?= esc_html($r2['label'] ?? '') ?></span><?php endif; ?>
                  </div><?php endforeach; ?>
                
              </div>
            </div>
          </div><?php endforeach; ?>
        
      </div>

      <div style="margin-top: clamp(26px, 3vw, 40px); border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 26px;">
        <div style="display: flex; flex-wrap: wrap; align-items: baseline; justify-content: space-between; gap: 12px 20px;">
          <div>
            <?php if (!empty($f['heading_2'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 20px;"><?= esc_html($f['heading_2'] ?? '') ?></div><?php endif; ?>
            <?php if (!empty($f['label_3'])) : ?><div style="font-size: 14.5px; color: var(--sh-crumb); margin-top: 6px;"><?= esc_html($f['label_3'] ?? '') ?></div><?php endif; ?>
          </div>
          <?php if (!empty(sh_link($f['link'] ?? '')) && !empty($f['link_label'])) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" style="font-size: 14.5px; font-weight: 600;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
        </div>
        <div data-team-strip style="margin-top: 18px;">
          <div data-team-track>
            
              <?php $r1_list = $f['items_5'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?= sh_image($r1['image'] ?? 0, ['aria-hidden' => 'false', 'data-face' => ''], '') ?><?php endforeach; ?>
            
          </div>
        </div>
      </div>
    </div>
  </section>
