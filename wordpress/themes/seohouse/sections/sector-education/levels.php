<?php
/**
 * Section "Levels" — SEO House - Sector Education.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'ed-levels')) ?>" data-screen-label="Levels" style="position: relative; scroll-margin-top: 88px; background: var(--sh-bg); border-bottom: 1px solid var(--sh-line);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(34px, 4vw, 56px) 20px;">
      <div data-sec-head data-center>
          <div style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><span aria-hidden="true" data-sx-ico style="flex: 0 0 auto; width: 30px; height: 30px; border-radius: 9px; background: rgba(40, 84, 232, 0.22); display: inline-flex; align-items: center; justify-content: center;"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="#2854E8" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="19" r="2"></circle><circle cx="18" cy="5" r="2"></circle><path d="M8 19h7a3 3 0 0 0 0-6H9a3 3 0 0 1 0-6h7"></path></svg></span><?php if (!empty($f['label'])) : ?><span><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?></div>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.35;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <?php if (!empty($f['text'])) : ?><p data-center style="font-size: 16.5px; line-height: 1.95; color: var(--sh-ink); margin: 14px 0px 0px; max-width: 44em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
      <div data-ed-cols style="margin-top: 22px; display: grid; gap: 0px; border-radius: 16px; overflow: hidden; border: 1px solid rgba(40, 84, 232, 0.25);">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_86675ce4 = ['v1' => ['padding: 20px; background: rgba(40, 84, 232, 0.06);'], 'v2' => ['padding: 20px; background: rgba(40, 84, 232, 0.12); border-inline-start: 1px solid rgba(40, 84, 232, 0.25);'], 'v3' => ['padding: 20px; background: rgba(40, 84, 232, 0.18); border-inline-start: 1px solid rgba(40, 84, 232, 0.25);']]; $vk_86675ce4 = $vt_86675ce4[$r1['variant'] ?? 'v1'] ?? $vt_86675ce4['v1']; ?><div style="<?= esc_attr($vk_86675ce4[0] ?? '') ?>">
          <?php if (!empty($r1['eyebrow'])) : ?><div style="font-size: 12.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($r1['eyebrow'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($r1['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17px; color: var(--sh-ink); margin-top: 6px;"><?= esc_html($r1['heading'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($r1['label'])) : ?><div style="font-size: 14.5px; color: var(--sh-ink); margin-top: 6px; line-height: 1.75;"><?= esc_html($r1['label'] ?? '') ?></div><?php endif; ?>
        </div><?php endforeach; ?>
      </div>
    </div>
  </section>
