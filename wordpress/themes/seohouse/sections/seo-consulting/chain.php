<?php
/**
 * Section "Chain" — SEO House - SEO Consulting.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Chain" style="position: relative; overflow: hidden; background: var(--sh-bg); border-bottom: 1px solid var(--sh-line);">
    <div aria-hidden="true" style="position: absolute; inset-inline-start: -160px; bottom: -200px; width: 520px; height: 520px; background: radial-gradient(circle, rgba(40, 84, 232, 0.28), transparent 70%); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4vw, 54px) 20px;">
      <div data-sec-head data-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <?php if (!empty($f['text'])) : ?><p data-center style="font-size: 16.5px; color: var(--sh-ink); margin: 12px 0px 0px; max-width: 48em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
      <ol data-cn-row style="list-style: none; margin: clamp(22px, 2.6vw, 32px) 0px 0px; padding: 0px; display: grid; gap: 14px;">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><li style="position: relative; border-radius: 16px; background: var(--sh-surface); box-shadow: rgba(40, 84, 232, 0.18) 0px 0px 0px 1px inset; padding: 18px 18px 20px;">
          <span style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: var(--sh-link);"><?= esc_html(sprintf('%02d', $i1 + 1)) ?></span>
          <?php if (!empty($r1['heading'])) : ?><h3 style="margin: 10px 0px 0px; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17.5px;"><?= esc_html($r1['heading'] ?? '') ?></h3><?php endif; ?>
          <?php if (!empty($r1['text'])) : ?><p style="margin: 6px 0px 0px; font-size: 15px; line-height: 1.8; color: var(--sh-ink);"><?= esc_html($r1['text'] ?? '') ?></p><?php endif; ?>
          <span aria-hidden="true" data-cn-arrow style="position: absolute; top: 50%; inset-inline-end: -12px; transform: translateY(-50%); width: 10px; color: var(--sh-link); font-size: 14px;">←</span>
        </li><?php endforeach; ?>
        <li style="position: relative; border-radius: 16px; background: var(--sh-surface); box-shadow: rgba(40, 84, 232, 0.18) 0px 0px 0px 1px inset; padding: 18px 18px 20px;">
          <?php if (!empty($f['eyebrow_2'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: var(--sh-link);"><?= esc_html($f['eyebrow_2'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($f['heading'])) : ?><h3 style="margin: 10px 0px 0px; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17.5px;"><?= esc_html($f['heading'] ?? '') ?></h3><?php endif; ?>
          <?php if (!empty($f['text_2'])) : ?><p style="margin: 6px 0px 0px; font-size: 15px; line-height: 1.8; color: var(--sh-ink);"><?= esc_html($f['text_2'] ?? '') ?></p><?php endif; ?>
        </li>
      </ol>
    </div>
  </section>
