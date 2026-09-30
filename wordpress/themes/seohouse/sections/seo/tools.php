<?php
/**
 * Section "Tools" — SEO House - SEO Service Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Tools" style="position: relative; background: var(--sh-surface); overflow: hidden;">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div style="max-width: 46em;">
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); margin: 12px 0px 0px; line-height: 1.3;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 17px; color: var(--sh-muted); margin: 14px 0px 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
      </div>

      <div data-stages style="position: relative; margin-top: clamp(28px, 3vw, 40px); display: grid; gap: clamp(20px, 2.4vw, 34px);">
        <span aria-hidden="true" data-stage-line></span>
        
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="position: relative; display: flex; flex-direction: column; gap: 10px;">
            <div data-stage-head style="display: flex; align-items: center; gap: 12px; height: 22px;">
              <span aria-hidden="true" style="<?= esc_attr($i1 === 0 ? 'flex: 0 0 auto; width: 11px; height: 11px; border-radius: 999px; background: var(--sh-lime); box-shadow: rgba(var(--sh-lime-rgb), 0.16) 0px 0px 0px 4px;' : 'flex: 0 0 auto; width: 11px; height: 11px; border-radius: 999px; background: var(--sh-sky); box-shadow: rgba(var(--sh-sky-rgb), 0.14) 0px 0px 0px 4px;') ?>"></span>
              <span style="<?= esc_attr($i1 === 0 ? 'font-family: Alexandria, sans-serif; font-weight: 800; font-size: 15px; line-height: 1; color: var(--sh-lime);' : 'font-family: Alexandria, sans-serif; font-weight: 800; font-size: 15px; line-height: 1; color: var(--sh-sky);') ?>"><?= esc_html(sprintf('%02d', $i1 + 1)) ?></span>
            </div>
            <?php if (!empty($r1['heading'])) : ?><h3 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 19px; margin: 0px;"><?= esc_html($r1['heading'] ?? '') ?></h3><?php endif; ?>
            <?php if (!empty($r1['text'])) : ?><p style="font-size: 15.5px; color: var(--sh-muted); margin: 0px; max-width: 26em; text-wrap: pretty;"><?= esc_html($r1['text'] ?? '') ?></p><?php endif; ?>
            <?php if (!empty($r1['label'])) : ?><div style="margin-top: 4px; font-size: 13px; color: var(--sh-crumb);"><?= esc_html($r1['label'] ?? '') ?></div><?php endif; ?>
          </div><?php endforeach; ?>
        
      </div>

      <div style="margin-top: clamp(26px, 2.8vw, 38px); border-top: 1px solid rgba(255, 255, 255, 0.12); padding-top: 20px;">
        <?php if (!empty($f['eyebrow_2'])) : ?><div style="font-size: 14px; font-weight: 600; color: var(--sh-text);"><?= esc_html($f['eyebrow_2'] ?? '') ?></div><?php endif; ?>
        <div data-plat-row style="margin-top: 14px;">
          <div data-plat-track>
            
              <?php $r1_list = $f['items_2'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_25696bfb = ['v1' => ['false'], 'v2' => ['true']]; $vk_25696bfb = $vt_25696bfb[$r1['variant'] ?? 'v1'] ?? $vt_25696bfb['v1']; ?><div data-plat aria-hidden="<?= esc_attr($vk_25696bfb[0] ?? '') ?>">
                <span data-plat-chip><?= sh_svg_img($r1['logo'] ?? '', '', ['width' => '24', 'height' => '24']) ?><?php if (!empty($r1['label'])) : ?><span><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?></span>
              </div><?php endforeach; ?>
            
          </div>
        </div>
      </div>
    </div>
  </section>
