<?php
/**
 * Section "Flow" — SEO House - Product Upload.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'flow')) ?>" data-screen-label="Flow" style="position: relative; scroll-margin-top: 88px; background: var(--sh-surface); color: var(--sh-ink); border-bottom: 1px solid var(--sh-line);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(34px, 4vw, 56px) 20px;">
      <div data-sec-head data-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.3;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <?php if (!empty($f['text'])) : ?><p data-center style="font-size: 16px; line-height: 1.9; color: var(--sh-text); margin: 12px 0px 0px; max-width: 46em;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
      <ol data-pu-steps style="margin: clamp(22px, 2.6vw, 32px) 0px 0px; padding: 0px; display: grid; gap: 16px;">
          <li data-pu-step style="position: relative; list-style: none; display: flex; flex-direction: column; min-width: 0px;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
              <span style="flex: 0 0 auto; width: 32px; height: 32px; border-radius: 10px; background: rgba(40, 84, 232, 0.12); display: flex; align-items: center; justify-content: center;"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="#2854E8" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><path d="M14 3v6h6"></path></svg></span>
              <?php if (!empty($f['eyebrow_2'])) : ?><span style="font-size: 12px; font-weight: 700; color: var(--sh-link);"><?= esc_html($f['eyebrow_2'] ?? '') ?></span><?php endif; ?>
              <?php if (!empty($f['heading'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16.5px;"><?= esc_html($f['heading'] ?? '') ?></span><?php endif; ?>
            </div>
            <div style="flex: 1 1 auto; border-radius: 16px; background: rgb(255, 255, 255); padding: 14px; box-shadow: 0 16px 36px -28px rgba(6,11,31,.5), 0 0 0 1px var(--sh-line);"><div dir="ltr" style="font-family: ui-monospace, Menlo, monospace; font-size: 12px; color: var(--sh-text); border-radius: 10px; overflow: hidden; border: 1px solid var(--sh-line);">
              <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_ea034c2e = ['v1' => ['display: grid; grid-template-columns: 1.3fr 0.6fr 0.5fr; background: rgb(238, 241, 247); font-weight: 700; color: var(--sh-text);'], 'v2' => ['display: grid; grid-template-columns: 1.3fr 0.6fr 0.5fr; border-top: 1px solid var(--sh-line);'], 'v3' => ['display: grid; grid-template-columns: 1.3fr 0.6fr 0.5fr; border-top: 1px solid var(--sh-line); background: rgb(255, 246, 242);']]; $vk_ea034c2e = $vt_ea034c2e[$r1['variant'] ?? 'v1'] ?? $vt_ea034c2e['v1']; ?><div style="<?= esc_attr($vk_ea034c2e[0] ?? '') ?>"><?php $r2_list = $r1['items'] ?? []; $i2_n = is_array($r2_list) ? count($r2_list) : 0; foreach ((array) $r2_list as $i2 => $r2) : ?><?php if (!empty($r2['label'])) : ?><span style="padding: 7px 8px;"><?= esc_html($r2['label'] ?? '') ?></span><?php endif; ?><?php endforeach; ?></div><?php endforeach; ?>
            </div>
            <?php if (!empty($f['label'])) : ?><div style="margin-top: 10px; font-size: 13px; color: var(--sh-text); line-height: 1.7;"><?= esc_html($f['label'] ?? '') ?></div><?php endif; ?></div>
          </li>
          <li data-pu-step style="position: relative; list-style: none; display: flex; flex-direction: column; min-width: 0px;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
              <span style="flex: 0 0 auto; width: 32px; height: 32px; border-radius: 10px; background: rgba(40, 84, 232, 0.12); display: flex; align-items: center; justify-content: center;"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="#2854E8" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20h9"></path><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"></path></svg></span>
              <?php if (!empty($f['eyebrow_3'])) : ?><span style="font-size: 12px; font-weight: 700; color: var(--sh-link);"><?= esc_html($f['eyebrow_3'] ?? '') ?></span><?php endif; ?>
              <?php if (!empty($f['heading_2'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16.5px;"><?= esc_html($f['heading_2'] ?? '') ?></span><?php endif; ?>
            </div>
            <div style="flex: 1 1 auto; border-radius: 16px; background: rgb(255, 255, 255); padding: 14px; box-shadow: 0 16px 36px -28px rgba(6,11,31,.5), 0 0 0 1px var(--sh-line);"><div><?php $r1_list = $f['items_2'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="display: flex; align-items: center; gap: 8px; padding: 7px 0px; border-top: 1px solid var(--sh-line);"><?php if (!empty($r1['label'])) : ?><span style="flex: 0 0 5.5em; font-size: 12px; color: var(--sh-text);"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?><?php if (!empty($r1['eyebrow'])) : ?><span style="flex: 1 1 auto; min-width: 0px; font-size: 13px; font-weight: 600; color: var(--sh-ink); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= esc_html($r1['eyebrow'] ?? '') ?></span><?php endif; ?><svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="#2854E8" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 5 5 9-10"></path></svg></div><?php endforeach; ?></div></div>
          </li>
          <li data-pu-step style="position: relative; list-style: none; display: flex; flex-direction: column; min-width: 0px;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
              <span style="flex: 0 0 auto; width: 32px; height: 32px; border-radius: 10px; background: var(--sh-blue); display: flex; align-items: center; justify-content: center;"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="#060B1F" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 9 5 4h14l2 5"></path><path d="M4 9v11h16V9"></path><path d="M3 9h18"></path></svg></span>
              <?php if (!empty($f['eyebrow_4'])) : ?><span style="font-size: 12px; font-weight: 700; color: var(--sh-link);"><?= esc_html($f['eyebrow_4'] ?? '') ?></span><?php endif; ?>
              <?php if (!empty($f['heading_3'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16.5px;"><?= esc_html($f['heading_3'] ?? '') ?></span><?php endif; ?>
            </div>
            <div style="flex: 1 1 auto; border-radius: 16px; background: rgb(255, 255, 255); padding: 14px; box-shadow: 0 16px 36px -28px rgba(6,11,31,.5), 0 0 0 1px var(--sh-line);"><div style="display: flex; gap: 12px; align-items: flex-start;"><div style="flex: 0 0 72px; aspect-ratio: 1 / 1; border-radius: 10px; background: linear-gradient(160deg, var(--sh-blue-tint), rgb(220, 228, 255)); display: flex; align-items: center; justify-content: center;"><svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#2854E8" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"></rect><circle cx="9" cy="10" r="2"></circle><path d="m21 16-5-5L5 20"></path></svg></div><div style="min-width: 0px;"><?php if (!empty($f['heading_4'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 14.5px; line-height: 1.45;"><?= esc_html($f['heading_4'] ?? '') ?></div><?php endif; ?><?php if (!empty($f['label_2'])) : ?><div style="font-size: 12px; color: var(--sh-text); margin-top: 4px;"><?= esc_html($f['label_2'] ?? '') ?></div><?php endif; ?></div></div>
            <div style="margin-top: 12px; border-radius: 10px; border: 1px solid rgba(40, 84, 232, 0.25); padding: 10px;"><?php if (!empty($f['label_3'])) : ?><div dir="ltr" style="font-size: 11.5px; color: var(--sh-text); text-align: start;"><?= esc_html($f['label_3'] ?? '') ?></div><?php endif; ?><?php if (!empty($f['eyebrow_5'])) : ?><div style="font-size: 13.5px; color: var(--sh-text); font-weight: 600; margin-top: 4px;"><?= esc_html($f['eyebrow_5'] ?? '') ?></div><?php endif; ?><div style="height: 5px; width: 90%; border-radius: 3px; background: var(--sh-line); margin-top: 7px;"></div></div></div>
          </li>
      </ol>
    </div>
  </section>
