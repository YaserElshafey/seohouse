<?php
/**
 * Section "Method" — SEO House - Egypt SEO Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Method" style="border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
    <div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>

      <div style="position: relative; margin-top: clamp(26px, 3vw, 42px);">
        <span aria-hidden="true" data-eg-rail></span>
        <div data-eg-phases style="display: grid; gap: 24px 26px;">
          
            <div data-eg-phase style="position: relative;">
              <span aria-hidden="true" data-eg-dot style="display: block; width: 19px; height: 19px; border-radius: 999px; background: var(--sh-ink); border: 2px solid var(--sh-lime);"></span>
              <?php if (!empty($f['eyebrow_2'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: 13px; color: var(--sh-lime); margin-top: 16px;"><?= esc_html($f['eyebrow_2'] ?? '') ?></div><?php endif; ?>
              <?php if (!empty($f['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18.5px; margin-top: 8px;"><?= esc_html($f['heading'] ?? '') ?></div><?php endif; ?>
              <?php if (!empty($f['text'])) : ?><p style="font-size: 15px; color: var(--sh-muted); margin: 8px 0px 0px; max-width: 26em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
              <div style="display: flex; flex-wrap: wrap; gap: 8px 14px; margin-top: 14px;">
                
                  <?php if (!empty(sh_link($f['link'] ?? '')) && !empty($f['link_label'])) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" class="hv-9ef762" style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
                
              </div>
            </div>
          
            <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div data-eg-phase style="position: relative;">
              <span aria-hidden="true" data-eg-dot style="display: block; width: 19px; height: 19px; border-radius: 999px; background: var(--sh-ink); border: 2px solid var(--sh-sky);"></span>
              <div style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: 13px; color: var(--sh-sky); margin-top: 16px;"><?= esc_html(sprintf('%02d', $i1 + 2)) ?></div>
              <?php if (!empty($r1['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18.5px; margin-top: 8px;"><?= esc_html($r1['heading'] ?? '') ?></div><?php endif; ?>
              <?php if (!empty($r1['text'])) : ?><p style="font-size: 15px; color: var(--sh-muted); margin: 8px 0px 0px; max-width: 26em; text-wrap: pretty;"><?= esc_html($r1['text'] ?? '') ?></p><?php endif; ?>
              <div style="display: flex; flex-wrap: wrap; gap: 8px 14px; margin-top: 14px;">
                
                  <?php $r2_list = $r1['items'] ?? []; $i2_n = is_array($r2_list) ? count($r2_list) : 0; foreach ((array) $r2_list as $i2 => $r2) : ?><?php if (!empty(sh_link($r2['link'] ?? '')) && !empty($r2['link_label'])) : ?><a href="<?= esc_url(sh_link($r2['link'] ?? '')) ?>" class="hv-9ef762" style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($r2['link_label'] ?? '') ?></a><?php endif; ?><?php endforeach; ?>
                
              </div>
            </div><?php endforeach; ?>
          
            <div data-eg-phase style="position: relative;">
              <span aria-hidden="true" data-eg-dot style="display: block; width: 19px; height: 19px; border-radius: 999px; background: var(--sh-ink); border: 2px solid var(--sh-sky);"></span>
              <?php if (!empty($f['eyebrow_3'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: 13px; color: var(--sh-sky); margin-top: 16px;"><?= esc_html($f['eyebrow_3'] ?? '') ?></div><?php endif; ?>
              <?php if (!empty($f['heading_2'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18.5px; margin-top: 8px;"><?= esc_html($f['heading_2'] ?? '') ?></div><?php endif; ?>
              <?php if (!empty($f['text_2'])) : ?><p style="font-size: 15px; color: var(--sh-muted); margin: 8px 0px 0px; max-width: 26em; text-wrap: pretty;"><?= esc_html($f['text_2'] ?? '') ?></p><?php endif; ?>
              <div style="display: flex; flex-wrap: wrap; gap: 8px 14px; margin-top: 14px;">
                
                  <?php if (!empty(sh_link($f['link_2'] ?? '')) && !empty($f['link_label_2'])) : ?><a href="<?= esc_url(sh_link($f['link_2'] ?? '')) ?>" class="hv-9ef762" style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['link_label_2'] ?? '') ?></a><?php endif; ?>
                
              </div>
            </div>
          
        </div>
      </div>

      <?php if (!empty(sh_link($f['link_3'] ?? ''))) : ?><a href="<?= esc_url(sh_link($f['link_3'] ?? '')) ?>" style="display: inline-flex; align-items: center; gap: 8px; margin-top: 26px; font-size: 15px; font-weight: 600; border-bottom: 1px solid rgba(var(--sh-sky-rgb), 0.5); padding-bottom: 3px;"><?= esc_html($f['link_label_3'] ?? '') ?> <span aria-hidden="true">←</span></a><?php endif; ?>
    </div>
  </section>
