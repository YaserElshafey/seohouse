<?php
/**
 * Section "Phases" — SEO House - Zid.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Phases" style="position: relative; background: var(--sh-bg); border-bottom: 1px solid var(--sh-line);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head data-sh-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <div data-zd-flow style="margin-top: clamp(24px, 2.8vw, 36px); display: grid; gap: 12px;">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="position: relative; border-radius: 14px; background: var(--sh-surface); padding: 16px 18px;">
          <span style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12px; color: var(--sh-link);"><?= esc_html(sprintf('%02d', $i1 + 1)) ?></span>
          <?php if (!empty($r1['heading'])) : ?><span style="display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16.5px; margin-top: 8px;"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($r1['label'])) : ?><span style="display: block; font-size: 14px; color: var(--sh-ink); margin-top: 6px;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
          <span aria-hidden="true" style="position: absolute; inset-inline-start: -11px; top: 50%; transform: translateY(-50%); color: var(--sh-link); font-size: 15px;">←</span>
        </div><?php endforeach; ?>
        <div style="position: relative; border-radius: 14px; background: var(--sh-surface); padding: 16px 18px;">
          <?php if (!empty($f['eyebrow_2'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12px; color: var(--sh-link);"><?= esc_html($f['eyebrow_2'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($f['heading'])) : ?><span style="display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16.5px; margin-top: 8px;"><?= esc_html($f['heading'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($f['label'])) : ?><span style="display: block; font-size: 14px; color: var(--sh-ink); margin-top: 6px;"><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?>

        </div>
      </div>
    </div>
  </section>
