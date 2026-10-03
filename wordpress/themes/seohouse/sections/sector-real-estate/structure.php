<?php
/**
 * Section "Structure" — SEO House - Sector Real Estate.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 're-structure')) ?>" data-screen-label="Structure" style="position: relative; scroll-margin-top: 88px; background: var(--sh-ink); border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(34px, 4vw, 56px) 20px;">
      <div data-re-split style="display: grid; gap: 28px 52px; align-items: center;">
        <div>
      <div data-sec-head>
          <div style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><span aria-hidden="true" data-sx-ico style="flex: 0 0 auto; width: 30px; height: 30px; border-radius: 9px; background: rgba(var(--sh-blue-rgb), 0.22); display: inline-flex; align-items: center; justify-content: center;"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="#C7FF32" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="16" height="18" rx="1.5"></rect><path d="M9 7h2M13 7h2M9 11h2M13 11h2M10 21v-3h4v3"></path></svg></span><?php if (!empty($f['label'])) : ?><span><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?></div>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.35;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
          <?php if (!empty($f['text'])) : ?><p style="font-size: 16.5px; line-height: 1.95; color: var(--sh-muted); margin: 14px 0px 0px; max-width: 44em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        </div>
        <div aria-label="تسلسل صفحات الموقع العقاري" style="display: flex; flex-direction: column; gap: 10px; position: relative;">
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_38d0e4ff = ['v1' => ['width: 100%; margin-inline: auto; border-radius: 14px; padding: 16px 18px; background: rgba(var(--sh-blue-rgb), 0.2); border: 1px solid rgba(var(--sh-blue-rgb), 0.3);'], 'v2' => ['width: 86%; margin-inline: auto; border-radius: 14px; padding: 16px 18px; background: rgba(var(--sh-blue-rgb), 0.13); border: 1px solid rgba(var(--sh-blue-rgb), 0.3);'], 'v3' => ['width: 72%; margin-inline: auto; border-radius: 14px; padding: 16px 18px; background: rgba(var(--sh-lime-rgb), 0.07); border: 1px solid rgba(var(--sh-blue-rgb), 0.3);']]; $vk_38d0e4ff = $vt_38d0e4ff[$r1['variant'] ?? 'v1'] ?? $vt_38d0e4ff['v1']; ?><div style="<?= esc_attr($vk_38d0e4ff[0] ?? '') ?>"><?php if (!empty($r1['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16px; color: var(--sh-text);"><?= esc_html($r1['heading'] ?? '') ?></div><?php endif; ?><?php if (!empty($r1['label'])) : ?><div style="font-size: 14px; color: var(--sh-muted); margin-top: 4px;"><?= esc_html($r1['label'] ?? '') ?></div><?php endif; ?></div><?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
