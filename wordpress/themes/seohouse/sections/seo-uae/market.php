<?php
/**
 * Section "Market" — SEO House - UAE SEO Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Market" style="position: relative; background: rgb(11, 19, 48); overflow: hidden;">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 17px; color: var(--sh-muted); margin: 18px 0px 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
      </div>

      <div style="position: relative; margin-top: clamp(28px, 3.2vw, 44px);">
        <span aria-hidden="true" data-ua-srail></span>
        <div data-ua-signals style="display: grid; gap: 26px 30px;">
          
            <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div data-ua-signal style="position: relative;">
              <span aria-hidden="true" data-ua-sdot style="<?= esc_attr($i1 === 0 ? 'display: block; width: 19px; height: 19px; border-radius: 999px; background: rgb(11, 19, 48); border: 2px solid var(--sh-lime);' : 'display: block; width: 19px; height: 19px; border-radius: 999px; background: rgb(11, 19, 48); border: 2px solid var(--sh-sky);') ?>"></span>
              <?php if (!empty($r1['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 19.5px; margin-top: 18px;"><?= esc_html($r1['heading'] ?? '') ?></div><?php endif; ?>
              <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 12px;">
                
                  <?php $r2_list = $r1['items'] ?? []; $i2_n = is_array($r2_list) ? count($r2_list) : 0; foreach ((array) $r2_list as $i2 => $r2) : ?><div style="display: flex; align-items: flex-start; gap: 10px; font-size: 15.5px; color: var(--sh-muted);">
                    <span aria-hidden="true" style="flex: 0 0 auto; color: var(--sh-sky); margin-top: 1px;">—</span><?php if (!empty($r2['label'])) : ?><span><?= esc_html($r2['label'] ?? '') ?></span><?php endif; ?>
                  </div><?php endforeach; ?>
                
              </div>
            </div><?php endforeach; ?>
          
        </div>
      </div>
    </div>
  </section>
