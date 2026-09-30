<?php
/**
 * Section "Lifecycle" — SEO House - Sector Real Estate.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 're-lifecycle')) ?>" data-screen-label="Lifecycle" style="position: relative; scroll-margin-top: 88px; background: var(--sh-paper-2); color: var(--sh-ink); border-bottom: 1px solid rgba(var(--sh-ink-rgb), 0.07);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(34px, 4vw, 56px) 20px;">
      <div data-sec-head data-center>
          <div style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 600; color: var(--sh-blue);"><span aria-hidden="true" data-sx-ico style="flex: 0 0 auto; width: 30px; height: 30px; border-radius: 9px; background: rgba(var(--sh-blue-rgb), 0.1); display: inline-flex; align-items: center; justify-content: center;"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="#2F5BFF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-3-6.7L21 8"></path><path d="M21 3v5h-5"></path></svg></span><?php if (!empty($f['label'])) : ?><span><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?></div>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.35;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <?php if (!empty($f['text'])) : ?><p data-center style="font-size: 16.5px; line-height: 1.95; color: var(--sh-ink-soft); margin: 14px 0px 0px; max-width: 44em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
      <div role="table" aria-label="حالة الصفحة والإجراء" style="margin-top: 22px; background: rgb(255, 255, 255); border-radius: 16px; box-shadow: rgba(var(--sh-ink-rgb), 0.45) 0px 14px 34px -26px; overflow: hidden;">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_c5e2a2d6 = ['v1' => ['font-family: Alexandria, sans-serif; font-weight: 700; font-size: 15.5px; color: var(--sh-ink);'], 'v2' => ['font-family: Alexandria, sans-serif; font-weight: 700; font-size: 15.5px; color: var(--sh-blue);']]; $vk_c5e2a2d6 = $vt_c5e2a2d6[$r1['variant'] ?? 'v1'] ?? $vt_c5e2a2d6['v1']; ?><div role="row" data-re-row style="<?= esc_attr($i1 === 0 ? 'display: grid; gap: 6px 24px; padding: 16px 20px;' : 'display: grid; gap: 6px 24px; padding: 16px 20px; border-top: 1px solid rgba(var(--sh-ink-rgb), 0.08);') ?>">
          <?php if (!empty($r1['heading'])) : ?><span role="cell" style="<?= esc_attr($vk_c5e2a2d6[0] ?? '') ?>"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($r1['text'])) : ?><span role="cell" style="font-size: 15px; line-height: 1.8; color: var(--sh-ink-soft);"><?= esc_html($r1['text'] ?? '') ?></span><?php endif; ?>
        </div><?php endforeach; ?>
      </div>
    </div>
  </section>
