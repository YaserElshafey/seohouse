<?php
/**
 * Section "Layers" — SEO House - SEO Service Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Layers" style="background: var(--sh-surface); color: var(--sh-ink);">
    <div data-layers style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px; display: grid; gap: clamp(28px, 3.2vw, 52px); align-items: center;">
      <div>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); margin: 12px 0px 0px; line-height: 1.3;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 17px; color: var(--sh-text); margin: 16px 0px 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
      </div>

      <div style="display: flex; flex-direction: column; gap: 10px;">
        
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_62bd8bac = ['v1' => ['position: relative; display: flex; align-items: center; gap: 16px; width: 100%; margin-inline-start: auto; background: rgb(250, 251, 252); color: rgb(37, 43, 51); border-radius: 14px; padding: 18px 20px;', 'font-family: Alexandria, sans-serif; font-weight: 800; font-size: 13px; color: rgb(33, 72, 216);', 'font-size: 14.5px; color: rgb(76, 89, 107); margin-top: 4px;'], 'v2' => ['position: relative; display: flex; align-items: center; gap: 16px; width: 92%; margin-inline-start: auto; background: var(--sh-blue-tint); color: rgb(37, 43, 51); border-radius: 14px; padding: 18px 20px;', 'font-family: Alexandria, sans-serif; font-weight: 800; font-size: 13px; color: rgb(33, 72, 216);', 'font-size: 14.5px; color: rgb(76, 89, 107); margin-top: 4px;'], 'v3' => ['position: relative; display: flex; align-items: center; gap: 16px; width: 84%; margin-inline-start: auto; background: rgb(40, 84, 232); color: rgb(255, 255, 255); border-radius: 14px; padding: 18px 20px;', 'font-family: Alexandria, sans-serif; font-weight: 800; font-size: 13px; color: rgb(255, 255, 255);', 'font-size: 14.5px; color: rgb(255, 255, 255); margin-top: 4px;'], 'v4' => ['position: relative; display: flex; align-items: center; gap: 16px; width: 76%; margin-inline-start: auto; background: rgb(33, 72, 216); color: rgb(255, 255, 255); border-radius: 14px; padding: 18px 20px;', 'font-family: Alexandria, sans-serif; font-weight: 800; font-size: 13px; color: rgb(255, 255, 255);', 'font-size: 14.5px; color: rgb(255, 255, 255); margin-top: 4px;']]; $vk_62bd8bac = $vt_62bd8bac[$r1['variant'] ?? 'v1'] ?? $vt_62bd8bac['v1']; ?><div style="<?= esc_attr($vk_62bd8bac[0] ?? '') ?>">
            <span aria-hidden="true" style="<?= esc_attr($i1 === $i1_n - 1 ? 'flex: 0 0 auto; width: 40px; height: 40px; border-radius: 11px; background: rgba(6, 11, 31, 0.1); display: flex; align-items: center; justify-content: center;' : 'flex: 0 0 auto; width: 40px; height: 40px; border-radius: 11px; background: rgba(40, 84, 232, 0.1); display: flex; align-items: center; justify-content: center;') ?>"><?= sh_icon($r1['icon'] ?? 'i45bcef23') ?></span>
            <span style="<?= esc_attr($vk_62bd8bac[1] ?? '') ?>"><?= esc_html(sprintf('%02d', $i1 + 1)) ?></span>
            <div style="min-width: 0px;">
              <?php if (!empty($r1['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18px;"><?= esc_html($r1['heading'] ?? '') ?></div><?php endif; ?>
              <?php if (!empty($r1['label'])) : ?><div style="<?= esc_attr($vk_62bd8bac[2] ?? '') ?>"><?= esc_html($r1['label'] ?? '') ?></div><?php endif; ?>
            </div>
          </div><?php endforeach; ?>
        
      </div>
    </div>
  </section>
