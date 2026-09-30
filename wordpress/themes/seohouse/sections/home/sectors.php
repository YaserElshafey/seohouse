<?php
/**
 * Section "Sectors" — SEO House - Homepage.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Sectors" style="background: var(--sh-paper); color: var(--sh-ink);">
    <div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px clamp(32px, 4vw, 56px);">
      <div style="text-align: center;">
        <div>
          <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13px; font-weight: 600; color: var(--sh-blue);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['title'])) : ?><h2 data-h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); margin: 10px 0px 0px; line-height: 1.3;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
        </div>
        <?php if (!empty(sh_link($f['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" style="display: inline-flex; align-items: center; gap: 8px; margin-top: 10px; font-size: 15px; font-weight: 600; color: var(--sh-blue);"><?= esc_html($f['link_label'] ?? '') ?> <span aria-hidden="true">←</span></a><?php endif; ?>
      </div>
      <div style="margin-top: 26px; display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 14px;">
        
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty(sh_link($r1['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($r1['link'] ?? '')) ?>" class="hv-903450" style="background: rgb(255, 255, 255); border-radius: 18px; padding: 20px 18px; display: flex; flex-direction: column; align-items: center; text-align: center; gap: 10px; color: var(--sh-ink); box-shadow: rgba(var(--sh-ink-rgb), 0.06) 0px 1px 2px; transition: box-shadow 0.3s, transform 0.3s;">
            <span aria-hidden="true" style="width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(150deg, var(--sh-blue), rgb(26, 59, 214)); display: flex; align-items: center; justify-content: center;"><?= sh_icon($r1['icon'] ?? 'i245ac198') ?></span>
            <?php if (!empty($r1['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 19px; margin-top: 10px;"><?= esc_html($r1['heading'] ?? '') ?></div><?php endif; ?>
            <?php if (!empty($r1['label'])) : ?><div style="font-size: 14.5px; color: var(--sh-ink-soft); margin-top: 6px;"><?= esc_html($r1['label'] ?? '') ?></div><?php endif; ?>
              <div aria-hidden="true" style="margin-top: auto; padding-top: 12px; border-top: 1px dashed rgba(var(--sh-blue-rgb), 0.25); display: flex; align-items: center; justify-content: center; gap: 6px; flex-wrap: wrap; align-self: stretch;">
                
                  <?php $r2_list = $r1['items'] ?? []; $i2_n = is_array($r2_list) ? count($r2_list) : 0; foreach ((array) $r2_list as $i2 => $r2) : ?><?php if (!empty($r2['eyebrow'])) : ?><span data-sc-step style="font-size: 12.5px; font-weight: 600; color: var(--sh-blue); background: rgba(var(--sh-blue-rgb), 0.07); border-radius: 999px; padding: 4px 10px;"><?= esc_html($r2['eyebrow'] ?? '') ?></span><?php endif; ?><?php endforeach; ?>
                
              </div>
          </a><?php endif; ?><?php endforeach; ?>
        
      </div>
    </div>
  </section>
