<?php
/**
 * Section "Sectors" — SEO House - Egypt SEO Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Sectors" style="background: var(--sh-surface);">
    <div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div style="display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 16px;">
        <div data-sec-head>
          <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
        </div>
        <?php if (!empty(sh_link($f['link'] ?? '')) && !empty($f['link_label'])) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" style="font-size: 15px; font-weight: 600;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
      </div>

      <div data-eg-split style="margin-top: clamp(24px, 2.8vw, 38px); display: grid; gap: clamp(24px, 3vw, 46px); align-items: stretch;">
        <div style="display: flex; flex-direction: column;">
          
            <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><button type="button" aria-pressed="<?= esc_attr($i1 === 0 ? 'true' : 'false') ?>" style="<?= esc_attr($i1 === 0 ? 'text-align: start; cursor: pointer; background: none; border-width: 1px 0px 0px; border-top-style: solid; border-right-style: initial; border-bottom-style: initial; border-left-style: initial; border-top-color: rgba(255, 255, 255, 0.12); border-right-color: initial; border-bottom-color: initial; border-left-color: initial; border-image: initial; display: flex; align-items: center; gap: 14px; padding: 16px 4px; color: var(--sh-lime); transition: color 0.25s, padding 0.25s; padding-inline-start: 10px;' : 'text-align: start; cursor: pointer; background: none; border-width: 1px 0px 0px; border-top-style: solid; border-right-style: initial; border-bottom-style: initial; border-left-style: initial; border-top-color: rgba(255, 255, 255, 0.12); border-right-color: initial; border-bottom-color: initial; border-left-color: initial; border-image: initial; display: flex; align-items: center; gap: 14px; padding: 16px 4px; color: var(--sh-text); transition: color 0.25s, padding 0.25s; padding-inline-start: 4px;') ?>">
              <?php if (!empty($r1['heading'])) : ?><span style="flex: 1 1 auto; font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(17px, 1.7vw, 20px);"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
              <span aria-hidden="true" style="<?= esc_attr($i1 === 0 ? 'flex: 0 0 auto; color: var(--sh-lime);' : 'flex: 0 0 auto; color: var(--sh-sky);') ?>">←</span>
            </button><?php endforeach; ?>
          
        </div>

        <div style="border-radius: 20px; background: linear-gradient(150deg, var(--sh-surface), var(--sh-surface)); padding: clamp(22px, 2.6vw, 32px); display: flex; flex-direction: column; gap: 14px;">
          <?php if (!empty($f['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: clamp(20px, 2.1vw, 26px); color: rgb(255, 255, 255);"><?= esc_html($f['heading'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['text'])) : ?><p style="font-size: 16px; color: var(--sh-crumb-current); margin: 0px; max-width: 34em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
          <?php if (!empty($f['eyebrow_2'])) : ?><div style="font-size: 13px; font-weight: 600; color: var(--sh-sky); margin-top: 6px;"><?= esc_html($f['eyebrow_2'] ?? '') ?></div><?php endif; ?>
          <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px 10px;">
            
              <?php $r1_list = $f['items_2'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty($r1['label'])) : ?><span style="font-size: 14px; color: var(--sh-text); background: rgba(255, 255, 255, 0.06); border-radius: 10px; padding: 8px 13px;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?><?php endforeach; ?>
            
          </div>
          <?php if (!empty(sh_link($f['link_2'] ?? '')) && !empty($f['link_label_2'])) : ?><a href="<?= esc_url(sh_link($f['link_2'] ?? '')) ?>" style="margin-top: auto; align-self: flex-start; font-size: 15px; font-weight: 600; color: var(--sh-lime); border-bottom: 1px solid rgba(var(--sh-lime-rgb), 0.45); padding-bottom: 3px;"><?= esc_html($f['link_label_2'] ?? '') ?></a><?php endif; ?>
        </div>
      </div>

      <div data-eg-acc style="margin-top: 24px;">
        
          <?php $r1_list = $f['items_3'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="border-top: 1px solid rgba(255, 255, 255, 0.12);">
            <button type="button" aria-expanded="<?= esc_attr($i1 === 0 ? 'true' : 'false') ?>" style="width: 100%; min-height: 58px; background: none; border: 0px; cursor: pointer; color: var(--sh-text); display: flex; align-items: center; justify-content: space-between; gap: 14px; padding: 12px 0px; text-align: start;">
              <?php if (!empty($r1['heading'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17px;"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
              <span aria-hidden="true" style="flex: 0 0 auto; color: var(--sh-sky); font-size: 19px;"><?= esc_html($r1['symbol'] ?? '') ?></span>
            </button>
            <div style="<?= esc_attr($i1 === 0 ? 'display: block; padding: 0px 0px 18px;' : 'display: none; padding: 0px 0px 18px;') ?>">
              <?php if (!empty($r1['text'])) : ?><p style="font-size: 15.5px; color: var(--sh-muted); margin: 0px 0px 12px;"><?= esc_html($r1['text'] ?? '') ?></p><?php endif; ?>
              <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                
                  <?php $r2_list = $r1['items'] ?? []; $i2_n = is_array($r2_list) ? count($r2_list) : 0; foreach ((array) $r2_list as $i2 => $r2) : ?><?php if (!empty($r2['label'])) : ?><span style="font-size: 14px; color: var(--sh-crumb-current); background: rgba(255, 255, 255, 0.06); border-radius: 10px; padding: 7px 12px;"><?= esc_html($r2['label'] ?? '') ?></span><?php endif; ?><?php endforeach; ?>
                
              </div>
              <?php if (!empty(sh_link($r1['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($r1['link'] ?? '')) ?>" style="display: inline-flex; align-items: center; gap: 8px; margin-top: 14px; font-size: 14.5px; font-weight: 600;"><?= esc_html($r1['link_label'] ?? '') ?> <span aria-hidden="true">←</span></a><?php endif; ?>
            </div>
          </div><?php endforeach; ?>
        
      </div>
    </div>
  </section>
