<?php
/**
 * Section "Branch" — SEO House - Sector Food.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'fd-branch')) ?>" data-screen-label="Branch" style="position: relative; scroll-margin-top: 88px; background: var(--sh-bg); border-bottom: 1px solid var(--sh-line);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(34px, 4vw, 56px) 20px;">
      <div data-fd-split style="display: grid; gap: 28px 52px; align-items: center;">
        <div>
      <div data-sec-head>
          <div style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><span aria-hidden="true" data-sx-ico style="flex: 0 0 auto; width: 30px; height: 30px; border-radius: 9px; background: rgba(40, 84, 232, 0.22); display: inline-flex; align-items: center; justify-content: center;"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="#2854E8" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-6.3-7-12a7 7 0 0 1 14 0c0 5.7-7 12-7 12z"></path><circle cx="12" cy="9" r="2.5"></circle></svg></span><?php if (!empty($f['label'])) : ?><span><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?></div>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.35;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
          <?php if (!empty($f['text'])) : ?><p style="font-size: 16.5px; line-height: 1.95; color: var(--sh-ink); margin: 14px 0px 0px; max-width: 44em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        </div>
        <div aria-label="ما تحتويه صفحة الفرع" style="border-radius: 18px; background: var(--sh-paper); color: var(--sh-ink); padding: 20px; box-shadow: rgba(0, 0, 0, 0.85) 0px 26px 58px -34px;">
          <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 12.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
          <div data-fd-fields style="margin-top: 12px; display: grid; gap: 8px;">
            <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_8808ba4f = ['v1' => ['width: 7px; height: 7px; border-radius: 2px; background: var(--sh-blue); box-shadow: none;'], 'v2' => ['width: 7px; height: 7px; border-radius: 2px; background: var(--sh-blue); box-shadow: rgb(111, 143, 26) 0px 0px 0px 1px;']]; $vk_8808ba4f = $vt_8808ba4f[$r1['variant'] ?? 'v1'] ?? $vt_8808ba4f['v1']; ?><span style="display: flex; align-items: center; gap: 8px; font-size: 14.5px; font-weight: 600; color: var(--sh-ink); background: rgb(255, 255, 255); border-radius: 10px; padding: 11px 12px; box-shadow: rgba(40, 84, 232, 0.14) 0px 0px 0px 1px;"><span aria-hidden="true" style="<?= esc_attr($vk_8808ba4f[0] ?? '') ?>"></span><?= esc_html($r1['label'] ?? '') ?></span><?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
