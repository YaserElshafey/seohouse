<?php
/**
 * Section "Platforms" — SEO House - Online Stores.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Platforms" style="position: relative; background: var(--sh-surface); color: var(--sh-ink); border-bottom: 1px solid var(--sh-line);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4vw, 56px) 20px;">
      <div data-pf-head style="display: grid; gap: 14px 40px; align-items: end;">
        <div data-sec-head>
          <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.3;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
        </div>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 16px; line-height: 1.85; color: var(--sh-text); margin: 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
      </div>
      <div data-pf-grid style="margin-top: clamp(22px, 2.6vw, 32px); display: grid; gap: 14px;">
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty(sh_link($r1['link'] ?? ''))) : ?><a data-hcard href="<?= esc_url(sh_link($r1['link'] ?? '')) ?>" data-pf-card class="hv-3bca6c" style="display: flex; flex-direction: column; gap: 12px; background: rgb(255, 255, 255); border-radius: 16px; padding: 20px; box-shadow: 0 14px 34px -26px rgba(6,11,31,.45), 0 0 0 1px var(--sh-line); color: var(--sh-ink); transition: box-shadow 0.2s;">
            <span style="display: flex; align-items: center; justify-content: space-between; gap: 10px; min-height: 32px;">
              <?= sh_svg_img($r1['logo'] ?? '', '', ['style' => 'height: 26px; width: auto; max-width: 110px; display: block;'], (int) ($r1['logo_image'] ?? 0)) ?>
              <?php if (!empty($r1['eyebrow'])) : ?><span style="font-size: 12px; font-weight: 600; color: var(--sh-link); background: rgba(40, 84, 232, 0.08); border-radius: 999px; padding: 5px 10px; white-space: nowrap;"><?= esc_html($r1['eyebrow'] ?? '') ?></span><?php endif; ?>
            </span>
            <?php if (!empty($r1['heading'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18px;"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
            <?php if (!empty($r1['text'])) : ?><span style="font-size: 15px; line-height: 1.75; color: var(--sh-text); text-wrap: pretty;"><?= esc_html($r1['text'] ?? '') ?></span><?php endif; ?>
            <?php if (!empty($r1['label'])) : ?><span style="margin-top: auto; font-size: 14.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
          </a><?php endif; ?><?php endforeach; ?>
          <?php if (!empty(sh_link($f['link'] ?? ''))) : ?><a data-hcard href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" data-pf-card class="hv-3bca6c" style="display: flex; flex-direction: column; gap: 12px; background: rgb(255, 255, 255); border-radius: 16px; padding: 20px; box-shadow: 0 14px 34px -26px rgba(6,11,31,.45), 0 0 0 1px var(--sh-line); color: var(--sh-ink); transition: box-shadow 0.2s;">
            <span style="display: flex; align-items: center; justify-content: space-between; gap: 10px; min-height: 32px;">
              <?php if (!empty($f['heading'])) : ?><span style="display: inline-flex; align-items: center; height: 28px; padding: 0px 12px; border-radius: 8px; border: 1px solid var(--sh-line); font-family: Alexandria, sans-serif; font-weight: 700; font-size: 15px; color: var(--sh-ink);"><?= esc_html($f['heading'] ?? '') ?></span><?php endif; ?>
              <?php if (!empty($f['eyebrow_2'])) : ?><span style="font-size: 12px; font-weight: 600; color: var(--sh-link); background: rgba(40, 84, 232, 0.08); border-radius: 999px; padding: 5px 10px; white-space: nowrap;"><?= esc_html($f['eyebrow_2'] ?? '') ?></span><?php endif; ?>
            </span>
            <?php if (!empty($f['heading_2'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18px;"><?= esc_html($f['heading_2'] ?? '') ?></span><?php endif; ?>
            <?php if (!empty($f['text_2'])) : ?><span style="font-size: 15px; line-height: 1.75; color: var(--sh-text); text-wrap: pretty;"><?= esc_html($f['text_2'] ?? '') ?></span><?php endif; ?>
            <?php if (!empty($f['label'])) : ?><span style="margin-top: auto; font-size: 14.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?>
          </a><?php endif; ?>
          <?php if (!empty(sh_link($f['link_2'] ?? ''))) : ?><a data-hcard href="<?= esc_url(sh_link($f['link_2'] ?? '')) ?>" data-pf-card class="hv-3bca6c" style="display: flex; flex-direction: column; gap: 12px; background: rgb(255, 255, 255); border-radius: 16px; padding: 20px; box-shadow: 0 14px 34px -26px rgba(6,11,31,.45), 0 0 0 1px var(--sh-line); color: var(--sh-ink); transition: box-shadow 0.2s;">
            <span style="display: flex; align-items: center; justify-content: space-between; gap: 10px; min-height: 32px;">
              <?= sh_svg_img($f['logo'] ?? '', '', ['style' => 'height: 16px; width: auto; display: block;'], (int) ($f['logo_image'] ?? 0)) ?>
              <?php if (!empty($f['eyebrow_3'])) : ?><span style="font-size: 12px; font-weight: 600; color: var(--sh-link); background: rgba(40, 84, 232, 0.08); border-radius: 999px; padding: 5px 10px; white-space: nowrap;"><?= esc_html($f['eyebrow_3'] ?? '') ?></span><?php endif; ?>
            </span>
            <?php if (!empty($f['heading_3'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18px;"><?= esc_html($f['heading_3'] ?? '') ?></span><?php endif; ?>
            <?php if (!empty($f['text_3'])) : ?><span style="font-size: 15px; line-height: 1.75; color: var(--sh-text); text-wrap: pretty;"><?= esc_html($f['text_3'] ?? '') ?></span><?php endif; ?>
            <?php if (!empty($f['label_2'])) : ?><span style="margin-top: auto; font-size: 14.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['label_2'] ?? '') ?></span><?php endif; ?>
          </a><?php endif; ?>
      </div>
    </div>
  </section>
