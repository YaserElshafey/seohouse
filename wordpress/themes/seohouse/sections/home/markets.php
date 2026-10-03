<?php
/**
 * Section "Markets" — SEO House - Homepage.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Markets" style="position: relative; overflow: hidden; background: var(--sh-ink); border-top: 1px solid rgba(255, 255, 255, 0.08);">
    <div aria-hidden="true" style="position: absolute; inset: 0px; background: radial-gradient(50% 60% at 50% 0%, rgba(var(--sh-blue-rgb), 0.18), transparent 70%); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(34px, 4.2vw, 56px) 20px;">
      <div style="text-align: center;">
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); margin: 10px 0px 0px; line-height: 1.3;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <div data-grid="mkt" style="margin-top: 28px; display: grid; gap: 16px;">
        
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty(sh_link($r1['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($r1['link'] ?? '')) ?>" data-mkt-card class="hv-ffd766" style="position: relative; display: flex; flex-direction: column; border-radius: 20px; overflow: hidden; background: linear-gradient(165deg, rgb(19, 32, 99) 0%, var(--sh-surface) 70%); border: 1px solid rgba(var(--sh-sky-rgb), 0.16); color: var(--sh-text); transition: border-color 0.25s, box-shadow 0.25s;">
            <span aria-hidden="true" data-mkt-map style="position: relative; display: block; height: clamp(150px, 15vw, 190px); background-image: radial-gradient(rgba(var(--sh-sky-rgb), 0.16) 1px, transparent 1px); background-size: 14px 14px;">
              <?= sh_icon($r1['icon'] ?? 'i2ea38d47') ?>
            </span>
            <span style="display: flex; flex-direction: column; flex: 1 1 auto; padding: 0px clamp(18px, 2vw, 24px) clamp(18px, 2vw, 22px); text-align: center; align-items: center;">
              <?php if (!empty($r1['heading'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: clamp(21px, 2vw, 25px); color: rgb(255, 255, 255);"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
              <?php if (!empty($r1['label'])) : ?><span style="color: var(--sh-muted); font-size: 14.5px; line-height: 1.8; margin-top: 8px; max-width: 24em;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
              <?php if (!empty($r1['eyebrow'])) : ?><span style="margin-top: auto; padding-top: 14px; font-size: 14px; font-weight: 600; color: var(--sh-lime);"><?= esc_html($r1['eyebrow'] ?? '') ?></span><?php endif; ?>
            </span>
          </a><?php endif; ?><?php endforeach; ?>
        
      </div>
    </div>
  </section>
