<?php
/**
 * Section "Sectors" — SEO House - SEO Service Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Sectors" style="border-bottom: 1px solid var(--sh-line);">
    <div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div style="display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 16px;">
        <div>
          <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['title'])) : ?><h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); margin: 12px 0px 0px; line-height: 1.3;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
        </div>
        <?php if (!empty(sh_link($f['link'] ?? '')) && !empty($f['link_label'])) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" style="font-size: 15px; font-weight: 600;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
      </div>

      <div data-sec-list style="margin-top: 30px; display: grid;">
        
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty(sh_link($r1['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($r1['link'] ?? '')) ?>" class="hv-fdd2a3" style="display: flex; align-items: center; gap: 16px; padding: 20px 4px; border-top: 1px solid var(--sh-line); color: var(--sh-ink); transition: color 0.25s, padding 0.25s;">
            <span aria-hidden="true" style="<?= esc_attr($i1 === 0 ? 'flex: 0 0 auto; width: 34px; height: 34px; border-radius: 10px; background: rgb(40, 84, 232); color: rgb(37, 43, 51); display: flex; align-items: center; justify-content: center;' : 'flex: 0 0 auto; width: 34px; height: 34px; border-radius: 10px; background: rgba(40, 84, 232, 0.12); color: rgb(33, 72, 216); display: flex; align-items: center; justify-content: center;') ?>">
              <?= sh_icon($r1['icon'] ?? 'i28c5742f') ?>
            </span>
            <span style="flex: 1 1 auto; min-width: 0px;">
              <?php if (!empty($r1['heading'])) : ?><span style="display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18px;"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
              <?php if (!empty($r1['label'])) : ?><span style="display: block; font-size: 14.5px; color: var(--sh-text); margin-top: 2px;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
            </span>
            <span aria-hidden="true" style="flex: 0 0 auto; color: var(--sh-link);">←</span>
          </a><?php endif; ?><?php endforeach; ?>
        
      </div>
    </div>
  </section>
