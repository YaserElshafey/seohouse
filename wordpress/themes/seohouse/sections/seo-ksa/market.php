<?php
/**
 * Section "Market" — SEO House - Saudi SEO Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'ksa-market')) ?>" data-screen-label="Market" style="position: relative; background: rgb(11, 19, 48); overflow: hidden; scroll-margin-top: 88px;">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head data-sh-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 17px; color: var(--sh-muted); margin: 16px 0px 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
      </div>

      <div data-mkt-split style="margin-top: clamp(26px, 3vw, 42px); display: grid; gap: clamp(26px, 3.2vw, 54px); align-items: start;">
        <div data-ksa-axes style="position: relative; display: flex; flex-direction: column;">
          <span aria-hidden="true" style="position: absolute; inset-inline-start: 9px; top: 24px; bottom: 24px; width: 1px; background: linear-gradient(rgba(var(--sh-lime-rgb), 0.5), rgba(var(--sh-sky-rgb), 0.4), rgba(var(--sh-sky-rgb), 0.1));"></span>
          
            <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_0fa180ec = ['v1' => ['position: relative; display: flex; align-items: flex-start; gap: 18px; padding: 0px 0px 22px;'], 'v2' => ['position: relative; display: flex; align-items: flex-start; gap: 18px; padding: 22px 0px;'], 'v3' => ['position: relative; display: flex; align-items: flex-start; gap: 18px; padding: 22px 0px 0px;']]; $vk_0fa180ec = $vt_0fa180ec[$r1['variant'] ?? 'v1'] ?? $vt_0fa180ec['v1']; ?><div style="<?= esc_attr($vk_0fa180ec[0] ?? '') ?>">
              <span aria-hidden="true" style="<?= esc_attr($i1 === 0 ? 'flex: 0 0 auto; margin-top: 5px; width: 19px; height: 19px; border-radius: 999px; background: rgb(11, 19, 48); border: 2px solid var(--sh-lime);' : 'flex: 0 0 auto; margin-top: 5px; width: 19px; height: 19px; border-radius: 999px; background: rgb(11, 19, 48); border: 2px solid var(--sh-sky);') ?>"></span>
              <span style="min-width: 0px;">
                <span style="<?= esc_attr($i1 === 0 ? 'display: block; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: var(--sh-lime);' : 'display: block; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: var(--sh-sky);') ?>"><?= esc_html(sprintf('%02d', $i1 + 1)) ?></span>
                <?php if (!empty($r1['heading'])) : ?><span style="display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 19px; margin-top: 7px; color: rgb(255, 255, 255);"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
                <?php if (!empty($r1['text'])) : ?><span style="display: block; font-size: 15.5px; color: var(--sh-muted); margin-top: 8px; max-width: 30em;"><?= esc_html($r1['text'] ?? '') ?></span><?php endif; ?>
              </span>
            </div><?php endforeach; ?>
          
        </div>

        <div style="border-radius: 18px; background: rgba(255, 255, 255, 0.035); border: 1px solid rgba(var(--sh-sky-rgb), 0.16); padding: clamp(18px, 2.2vw, 26px);">
          <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 10px 14px; padding-bottom: 14px; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
            <?php if (!empty($f['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16.5px;"><?= esc_html($f['heading'] ?? '') ?></div><?php endif; ?>
            <?php if (!empty($f['label'])) : ?><div style="font-size: 12px; color: var(--sh-crumb);"><?= esc_html($f['label'] ?? '') ?></div><?php endif; ?>
          </div>
          <div style="display: flex; flex-direction: column;">
            
              <?php $r1_list = $f['items_2'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="display: flex; align-items: flex-start; gap: 14px; padding: 15px 0px; border-bottom: 1px solid rgba(255, 255, 255, 0.07);">
                <span aria-hidden="true" style="flex: 0 0 auto; width: 38px; height: 38px; border-radius: 11px; background: rgba(var(--sh-blue-rgb), 0.22); display: flex; align-items: center; justify-content: center;"><?= sh_icon($r1['icon'] ?? 'i16a8b069') ?></span>
                <span style="min-width: 0px;">
                  <?php if (!empty($r1['text'])) : ?><span style="display: block; font-size: 15.5px; font-weight: 600; color: var(--sh-text);"><?= esc_html($r1['text'] ?? '') ?></span><?php endif; ?>
                  <?php if (!empty($r1['label'])) : ?><span style="display: block; font-size: 14px; color: var(--sh-dim); margin-top: 5px;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
                  <span style="display: flex; flex-wrap: wrap; gap: 6px; margin-top: 10px;">
                    <?php $r2_list = $r1['items'] ?? []; $i2_n = is_array($r2_list) ? count($r2_list) : 0; foreach ((array) $r2_list as $i2 => $r2) : ?><?php if (!empty($r2['eyebrow'])) : ?><span style="font-size: 12px; font-weight: 600; color: var(--sh-crumb-current); background: rgba(var(--sh-blue-rgb), 0.16); border: 1px solid rgba(var(--sh-sky-rgb), 0.25); border-radius: 999px; padding: 4px 10px;"><?= esc_html($r2['eyebrow'] ?? '') ?></span><?php endif; ?><?php endforeach; ?>
                  </span>
                </span>
              </div><?php endforeach; ?>
            
          </div>
        </div>
      </div>
    </div>
  </section>
