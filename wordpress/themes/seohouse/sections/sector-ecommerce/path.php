<?php
/**
 * Section "Path" — SEO House - Sector Ecommerce.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'ec-path')) ?>" data-screen-label="Path" style="position: relative; scroll-margin-top: 88px; background: var(--sh-bg); border-bottom: 1px solid var(--sh-line);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(34px, 4vw, 56px) 20px;">
      <div data-sec-head>
          <div style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><span aria-hidden="true" data-sx-ico style="flex: 0 0 auto; width: 30px; height: 30px; border-radius: 9px; background: rgba(40, 84, 232, 0.22); display: inline-flex; align-items: center; justify-content: center;"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="#2854E8" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 4h18l-7 8v6l-4 2v-8z"></path></svg></span><?php if (!empty($f['label'])) : ?><span><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?></div>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.35;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <ol data-ec-path style="list-style: none; margin: clamp(22px, 2.6vw, 32px) 0px 0px; padding: 0px; display: grid; gap: 0px;">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_c5148d95 = ['v1' => ['position: relative; padding: 18px 18px 18px 22px; border-top: 3px solid var(--sh-blue); background: var(--sh-surface);'], 'v2' => ['position: relative; padding: 18px 18px 18px 22px; border-top: 3px solid rgba(40, 84, 232, 0.55); background: var(--sh-surface);'], 'v3' => ['position: relative; padding: 18px 18px 18px 22px; border-top: 3px solid rgba(40, 84, 232, 0.75); background: var(--sh-surface);'], 'v4' => ['position: relative; padding: 18px 18px 18px 22px; border-top: 3px solid var(--sh-blue); background: rgba(40, 84, 232, 0.06);']]; $vk_c5148d95 = $vt_c5148d95[$r1['variant'] ?? 'v1'] ?? $vt_c5148d95['v1']; ?><li style="<?= esc_attr($vk_c5148d95[0] ?? '') ?>">
          <span aria-hidden="true" style="<?= esc_attr($i1 === $i1_n - 1 ? 'display: inline-flex; width: 28px; height: 28px; border-radius: 8px; background: rgba(40, 84, 232, 0.14); align-items: center; justify-content: center; margin-bottom: 10px;' : 'display: inline-flex; width: 28px; height: 28px; border-radius: 8px; background: rgba(40, 84, 232, 0.2); align-items: center; justify-content: center; margin-bottom: 10px;') ?>"><?= sh_icon($r1['icon'] ?? 'i0724eab7') ?></span>
            <?php if (!empty($r1['eyebrow'])) : ?><div style="font-size: 12.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($r1['eyebrow'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($r1['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16px; color: var(--sh-ink); margin-top: 6px;"><?= esc_html($r1['heading'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($r1['label'])) : ?><div style="font-size: 14px; color: var(--sh-text); margin-top: 4px;"><?= esc_html($r1['label'] ?? '') ?></div><?php endif; ?>
        </li><?php endforeach; ?>
      </ol>
      <?php if (!empty($f['text'])) : ?><p style="font-size: 16.5px; line-height: 1.95; color: var(--sh-ink); margin: 22px 0px 0px; max-width: 44em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
    </div>
  </section>
