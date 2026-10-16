<?php
/**
 * Section "Factors" — SEO House - Pricing.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'factors')) ?>" data-screen-label="Factors" style="position: relative; scroll-margin-top: 88px; border-bottom: 1px solid var(--sh-line);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head data-sh-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <div data-pr-fgrid style="margin-top: clamp(22px, 2.6vw, 32px); display: grid; gap: 14px;">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="<?= esc_attr($i1 === 0 ? 'border-radius: 16px; background: rgba(40, 84, 232, 0.07); border: 1px solid rgba(40, 84, 232, 0.3); padding: 18px;' : 'border-radius: 16px; background: var(--sh-surface); border: 1px solid rgba(40, 84, 232, 0.14); padding: 18px;') ?>">
          <div style="display: flex; align-items: center; gap: 11px;"><span aria-hidden="true" style="flex: 0 0 auto; width: 38px; height: 38px; border-radius: 11px; background: rgba(40, 84, 232, 0.22); display: flex; align-items: center; justify-content: center;"><?= sh_icon($r1['icon'] ?? 'i9dc5de98') ?></span><?php if (!empty($r1['heading'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16.5px; color: var(--sh-ink);"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?></div>
          <?php if (!empty($r1['text'])) : ?><p style="font-size: 14.5px; line-height: 1.8; color: var(--sh-ink); margin: 10px 0px 0px; text-wrap: pretty;"><?= esc_html($r1['text'] ?? '') ?></p><?php endif; ?>
        </div><?php endforeach; ?>
      </div>
    </div>
  </section>
