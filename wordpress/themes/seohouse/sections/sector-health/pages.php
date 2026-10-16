<?php
/**
 * Section "Pages" — SEO House - Sector Health.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'hl-pages')) ?>" data-screen-label="Pages" style="position: relative; scroll-margin-top: 88px; background: var(--sh-surface); color: var(--sh-ink); border-bottom: 1px solid var(--sh-line);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(34px, 4vw, 56px) 20px;">
      <div data-sec-head data-sh-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.35;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <div data-hl-types style="margin-top: 22px; display: grid; gap: 14px;">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="<?= esc_attr($i1 === 0 ? 'background: rgb(255, 255, 255); border-radius: 16px; padding: 20px; box-shadow: rgba(6, 11, 31, 0.45) 0px 14px 34px -26px; border-top: 3px solid var(--sh-blue);' : 'background: rgb(255, 255, 255); border-radius: 16px; padding: 20px; box-shadow: rgba(6, 11, 31, 0.45) 0px 14px 34px -26px; border-top: 3px solid rgba(40, 84, 232, 0.35);') ?>">
          <span aria-hidden="true" data-sx-ico style="width: 38px; height: 38px; border-radius: 11px; background: rgba(40, 84, 232, 0.1); display: flex; align-items: center; justify-content: center; margin-bottom: 12px;"><?= sh_icon($r1['icon'] ?? 'i55c26f0f') ?></span>
            <?php if (!empty($r1['heading'])) : ?><h3 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(18px, 1.6vw, 21px); line-height: 1.45; margin: 0px; color: var(--sh-ink);"><?= esc_html($r1['heading'] ?? '') ?></h3><?php endif; ?>
          <?php if (!empty($r1['text'])) : ?><p style="font-size: 15px; line-height: 1.8; color: var(--sh-text); margin: 8px 0px 0px;"><?= esc_html($r1['text'] ?? '') ?></p><?php endif; ?>
        </div><?php endforeach; ?>
      </div>
      <div style="margin-top: 26px; padding-top: 22px; border-top: 1px solid var(--sh-line);">
        <?php if (!empty($f['heading'])) : ?><h3 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(18px, 1.6vw, 21px); line-height: 1.45; margin: 0px; color: var(--sh-ink);"><?= esc_html($f['heading'] ?? '') ?></h3><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 16.5px; line-height: 1.95; color: var(--sh-text); margin: 10px 0px 0px; max-width: 44em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
      </div>
    </div>
  </section>
