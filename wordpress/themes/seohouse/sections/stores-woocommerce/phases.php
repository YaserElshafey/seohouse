<?php
/**
 * Section "Phases" — SEO House - WooCommerce.
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
      <div data-sec-head>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <div style="margin-top: clamp(24px, 2.8vw, 36px); display: flex; flex-direction: column; gap: 10px;">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_8912ce14 = ['v1' => ['width: 100%; margin-inline-start: auto; display: flex; align-items: center; gap: 16px; border-radius: 14px; background: var(--sh-surface); padding: 16px 20px;'], 'v2' => ['width: 92%; margin-inline-start: auto; display: flex; align-items: center; gap: 16px; border-radius: 14px; background: var(--sh-surface); padding: 16px 20px;'], 'v3' => ['width: 84%; margin-inline-start: auto; display: flex; align-items: center; gap: 16px; border-radius: 14px; background: var(--sh-surface); padding: 16px 20px;'], 'v4' => ['width: 76%; margin-inline-start: auto; display: flex; align-items: center; gap: 16px; border-radius: 14px; background: var(--sh-surface); padding: 16px 20px;'], 'v5' => ['width: 68%; margin-inline-start: auto; display: flex; align-items: center; gap: 16px; border-radius: 14px; background: rgba(40, 84, 232, 0.1); padding: 16px 20px;']]; $vk_8912ce14 = $vt_8912ce14[$r1['variant'] ?? 'v1'] ?? $vt_8912ce14['v1']; ?><div style="<?= esc_attr($vk_8912ce14[0] ?? '') ?>">
          <span style="flex: 0 0 auto; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: var(--sh-link);"><?= esc_html(sprintf('%02d', $i1 + 1)) ?></span>
          <?php if (!empty($r1['heading'])) : ?><span style="flex: 0 1 auto; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17px;"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($r1['label'])) : ?><span data-wc-note style="flex: 1 1 auto; font-size: 14.5px; color: var(--sh-ink); text-align: end;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
        </div><?php endforeach; ?>
      </div>
    </div>
  </section>
