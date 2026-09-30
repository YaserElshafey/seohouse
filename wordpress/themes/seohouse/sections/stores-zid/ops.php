<?php
/**
 * Section "Ops" — SEO House - Zid.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'ops')) ?>" data-screen-label="Ops" style="position: relative; scroll-margin-top: 88px; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head data-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <?php if (!empty($f['text'])) : ?><p data-center style="font-size: 16.5px; color: var(--sh-muted); margin: 14px 0px 0px; max-width: 48em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>      <div style="display: flex; flex-direction: column; border-radius: 16px; background: rgba(255, 255, 255, 0.035); overflow: hidden;">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div data-zd-row style="<?= esc_attr($i1 === 0 ? 'display: grid; gap: 6px 24px; padding: 16px 20px; align-items: baseline;' : 'display: grid; gap: 6px 24px; padding: 16px 20px; border-top: 1px solid rgba(255, 255, 255, 0.08); align-items: baseline;') ?>">
          <span style="display: flex; align-items: center; gap: 11px;"><span aria-hidden="true" style="<?= esc_attr($i1 === 0 ? 'width: 7px; height: 7px; border-radius: 999px; background: var(--sh-lime);' : 'width: 7px; height: 7px; border-radius: 999px; background: var(--sh-sky);') ?>"></span><?php if (!empty($r1['heading'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16.5px;"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?></span>
          <?php if (!empty($r1['label'])) : ?><span style="font-size: 14.5px; color: var(--sh-muted); text-wrap: pretty;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
        </div><?php endforeach; ?>
      </div>
    </div>
  </section>
