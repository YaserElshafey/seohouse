<?php
/**
 * Section "Platforms" — SEO House - Product Upload.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Platforms" style="position: relative; background: var(--sh-surface); color: var(--sh-ink); border-bottom: 1px solid var(--sh-line);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 3.8vw, 50px) 20px;">
      <div data-sec-head data-sh-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.3;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <div data-pu-plat style="margin-top: 22px; display: grid; gap: 14px;">
        <?php if (!empty(sh_link($f['link'] ?? ''))) : ?><a data-hcard href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" class="hv-3bca6c" style="display: flex; flex-direction: column; gap: 10px; background: rgb(255, 255, 255); border-radius: 16px; padding: 18px; box-shadow: 0 14px 34px -26px rgba(6,11,31,.45), 0 0 0 1px var(--sh-line); color: var(--sh-ink); transition: box-shadow 0.2s;">
          <span style="display: flex; align-items: center; min-height: 30px;"><?= sh_svg_img($f['logo'] ?? '', '', ['style' => 'height: 28px; width: auto; max-width: 120px; display: block;'], (int) ($f['logo_image'] ?? 0)) ?></span>
          <?php if (!empty($f['heading'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17px;"><?= esc_html($f['heading'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($f['label'])) : ?><span style="font-size: 14px; line-height: 1.7; color: var(--sh-text);"><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($f['eyebrow_2'])) : ?><span style="margin-top: auto; font-size: 14px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow_2'] ?? '') ?></span><?php endif; ?>
        </a><?php endif; ?>
        <?php if (!empty(sh_link($f['link_2'] ?? ''))) : ?><a data-hcard href="<?= esc_url(sh_link($f['link_2'] ?? '')) ?>" class="hv-3bca6c" style="display: flex; flex-direction: column; gap: 10px; background: rgb(255, 255, 255); border-radius: 16px; padding: 18px; box-shadow: 0 14px 34px -26px rgba(6,11,31,.45), 0 0 0 1px var(--sh-line); color: var(--sh-ink); transition: box-shadow 0.2s;">
          <span style="display: flex; align-items: center; min-height: 30px;"><span aria-hidden="true" style="display: inline-flex; align-items: center; height: 28px; padding: 0px 12px; border-radius: 8px; background: rgb(241, 244, 251); font-family: Alexandria, sans-serif; font-weight: 800; font-size: 16px; color: var(--sh-ink);">زد</span></span> <?php if (!empty($f['heading_2'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17px;"><?= esc_html($f['heading_2'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($f['label_2'])) : ?><span style="font-size: 14px; line-height: 1.7; color: var(--sh-text);"><?= esc_html($f['label_2'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($f['eyebrow_3'])) : ?><span style="margin-top: auto; font-size: 14px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow_3'] ?? '') ?></span><?php endif; ?>
        </a><?php endif; ?>
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty(sh_link($r1['link'] ?? ''))) : ?><a data-hcard href="<?= esc_url(sh_link($r1['link'] ?? '')) ?>" class="hv-3bca6c" style="display: flex; flex-direction: column; gap: 10px; background: rgb(255, 255, 255); border-radius: 16px; padding: 18px; box-shadow: 0 14px 34px -26px rgba(6,11,31,.45), 0 0 0 1px var(--sh-line); color: var(--sh-ink); transition: box-shadow 0.2s;">
          <span style="display: flex; align-items: center; min-height: 30px;"><?= sh_svg_img($r1['logo'] ?? '', '', ['style' => 'height: 28px; width: auto; max-width: 120px; display: block;'], (int) ($r1['logo_image'] ?? 0)) ?></span>
          <?php if (!empty($r1['heading'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17px;"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($r1['label'])) : ?><span style="font-size: 14px; line-height: 1.7; color: var(--sh-text);"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($r1['eyebrow'])) : ?><span style="margin-top: auto; font-size: 14px; font-weight: 600; color: var(--sh-link);"><?= esc_html($r1['eyebrow'] ?? '') ?></span><?php endif; ?>
        </a><?php endif; ?><?php endforeach; ?>
      </div>
    </div>
  </section>
