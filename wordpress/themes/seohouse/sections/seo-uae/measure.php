<?php
/**
 * Section "Measure" — SEO House - UAE SEO Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Measure" style="background: var(--sh-surface); color: var(--sh-ink);">
    <div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head data-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>

      <?php if (!empty($f['text'])) : ?><p data-center style="font-size: 16.5px; color: var(--sh-text); margin: 12px 0px 0px; max-width: 52em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
      <div data-ua-mgrid style="margin-top: clamp(20px, 2.4vw, 30px); display: grid; gap: 14px 26px;">
        
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="<?= esc_attr($i1 === 0 ? 'display: flex; flex-direction: column; gap: 10px; background: rgb(255, 255, 255); border-radius: 16px; padding: 18px; box-shadow: rgba(6, 11, 31, 0.45) 0px 14px 34px -26px; border-top: 3px solid rgb(40, 84, 232);' : 'display: flex; flex-direction: column; gap: 10px; background: rgb(255, 255, 255); border-radius: 16px; padding: 18px; box-shadow: rgba(6, 11, 31, 0.45) 0px 14px 34px -26px; border-top: 3px solid var(--sh-blue);') ?>">
            <span style="display: flex; align-items: center; justify-content: space-between; gap: 12px;"><span style="width: 38px; height: 38px; border-radius: 11px; background: rgba(40, 84, 232, 0.1); display: flex; align-items: center; justify-content: center;"><?= sh_icon($r1['icon'] ?? 'i50196e0e') ?></span></span>
            <?php if (!empty($r1['heading'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17px;"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
            <?php if (!empty($r1['text'])) : ?><span style="font-size: 15px; line-height: 1.8; color: var(--sh-text);"><?= esc_html($r1['text'] ?? '') ?></span><?php endif; ?>
          </div><?php endforeach; ?>
        
      </div>
    </div>
  </section>
