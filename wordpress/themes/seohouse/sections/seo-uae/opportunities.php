<?php
/**
 * Section "Opportunities" — SEO House - UAE SEO Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'uae-opportunities')) ?>" data-screen-label="Opportunities" style="border-bottom: 1px solid rgba(255, 255, 255, 0.1); scroll-margin-top: 88px;">
    <div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head data-sh-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>

      <div data-ua-opps style="margin-top: clamp(24px, 2.8vw, 40px); display: grid; gap: clamp(20px, 2.4vw, 36px); align-items: stretch;">
        <div style="display: flex; flex-direction: column;">
          
            <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><button type="button" aria-pressed="<?= esc_attr($i1 === 0 ? 'true' : 'false') ?>" style="<?= esc_attr($i1 === 0 ? 'text-align: start; cursor: pointer; width: 100%; min-height: 62px; border-width: 1px 0px 0px; border-top-style: solid; border-right-style: initial; border-bottom-style: initial; border-left-style: initial; border-top-color: rgba(255, 255, 255, 0.12); border-right-color: initial; border-bottom-color: initial; border-left-color: initial; border-image: initial; border-inline-start: 2px solid var(--sh-lime); background: rgba(var(--sh-lime-rgb), 0.07); color: rgb(255, 255, 255); display: flex; align-items: center; gap: 14px; padding: 14px; transition: background 0.22s, border-color 0.22s, color 0.22s;' : 'text-align: start; cursor: pointer; width: 100%; min-height: 62px; border-width: 1px 0px 0px; border-top-style: solid; border-right-style: initial; border-bottom-style: initial; border-left-style: initial; border-top-color: rgba(255, 255, 255, 0.12); border-right-color: initial; border-bottom-color: initial; border-left-color: initial; border-image: initial; border-inline-start: 2px solid transparent; background: transparent; color: var(--sh-text); display: flex; align-items: center; gap: 14px; padding: 14px; transition: background 0.22s, border-color 0.22s, color 0.22s;') ?>">
              <span style="<?= esc_attr($i1 === 0 ? 'flex: 0 0 auto; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: var(--sh-lime);' : 'flex: 0 0 auto; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: var(--sh-sky);') ?>"><?= esc_html(sprintf('%02d', $i1 + 1)) ?></span>
              <?php if (!empty($r1['heading'])) : ?><span style="flex: 1 1 auto; min-width: 0px; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16.5px;"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
              <span aria-hidden="true" style="<?= esc_attr($i1 === 0 ? 'flex: 0 0 auto; color: var(--sh-lime);' : 'flex: 0 0 auto; color: var(--sh-sky);') ?>">←</span>
            </button><?php endforeach; ?>
          
        </div>

        <div style="border-radius: 20px; background: linear-gradient(150deg, var(--sh-surface), var(--sh-surface)); padding: clamp(22px, 2.6vw, 32px); display: flex; flex-direction: column; gap: 18px;">
          <?php if (!empty($f['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: clamp(20px, 2.1vw, 26px); color: rgb(255, 255, 255);"><?= esc_html($f['heading'] ?? '') ?></div><?php endif; ?>
          
            <?php $r1_list = $f['items_2'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="padding-top: 16px; border-top: 1px solid rgba(255, 255, 255, 0.1);">
              <?php if (!empty($r1['eyebrow'])) : ?><div style="<?= esc_attr($i1 === $i1_n - 1 ? 'font-size: 13px; font-weight: 600; color: var(--sh-lime);' : 'font-size: 13px; font-weight: 600; color: var(--sh-sky);') ?>"><?= esc_html($r1['eyebrow'] ?? '') ?></div><?php endif; ?>
              <?php if (!empty($r1['text'])) : ?><div style="font-size: 15.5px; color: var(--sh-crumb-current); margin-top: 8px; max-width: 34em; text-wrap: pretty;"><?= esc_html($r1['text'] ?? '') ?></div><?php endif; ?>
            </div><?php endforeach; ?>
          
        </div>
      </div>

      <div data-ua-opp-acc style="margin-top: 24px;">
        
          <?php $r1_list = $f['items_3'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="border-top: 1px solid rgba(255, 255, 255, 0.12);">
            <button type="button" aria-expanded="<?= esc_attr($i1 === 0 ? 'true' : 'false') ?>" style="width: 100%; min-height: 60px; background: none; border: 0px; cursor: pointer; color: var(--sh-text); display: flex; align-items: center; gap: 14px; padding: 12px 0px; text-align: start;">
              <span style="<?= esc_attr($i1 === 0 ? 'flex: 0 0 auto; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: var(--sh-lime);' : 'flex: 0 0 auto; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: var(--sh-sky);') ?>"><?= esc_html(sprintf('%02d', $i1 + 1)) ?></span>
              <?php if (!empty($r1['heading'])) : ?><span style="flex: 1 1 auto; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16.5px;"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
              <span aria-hidden="true" style="flex: 0 0 auto; color: var(--sh-sky); font-size: 19px;"><?= esc_html($r1['symbol'] ?? '') ?></span>
            </button>
            <div style="<?= esc_attr($i1 === 0 ? 'display: block; padding: 0px 0px 18px;' : 'display: none; padding: 0px 0px 18px;') ?>">
              
                <?php $r2_list = $r1['items'] ?? []; $i2_n = is_array($r2_list) ? count($r2_list) : 0; foreach ((array) $r2_list as $i2 => $r2) : ?><div style="margin-bottom: 12px;">
                  <?php if (!empty($r2['eyebrow'])) : ?><div style="font-size: 13px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($r2['eyebrow'] ?? '') ?></div><?php endif; ?>
                  <?php if (!empty($r2['text'])) : ?><div style="font-size: 15px; color: var(--sh-muted); margin-top: 6px;"><?= esc_html($r2['text'] ?? '') ?></div><?php endif; ?>
                </div><?php endforeach; ?>
              
            </div>
          </div><?php endforeach; ?>
        
      </div>
    </div>
  </section>
