<?php
/**
 * Section "Families" — SEO House - All Services.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'families')) ?>" data-screen-label="Families" style="position: relative; overflow: hidden; background: radial-gradient(50% 60% at 85% 10%, rgba(40,84,232,.16), transparent 70%), var(--sh-bg); border-bottom: 1px solid var(--sh-line);">
    <div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head data-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <?php if (!empty($f['text'])) : ?><p data-center style="font-size: 16.5px; color: var(--sh-ink); margin: 14px 0px 0px; max-width: 48em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
      <div data-g4 style="margin-top: clamp(26px, 3vw, 40px);">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="<?= esc_attr($i1 === 0 ? 'border-radius: 18px; background: linear-gradient(160deg, rgba(40, 84, 232, 0.08), rgba(40, 84, 232, 0.08)); border: 1px solid rgba(40, 84, 232, 0.3); padding: 20px; display: flex; flex-direction: column; gap: 12px;' : 'border-radius: 18px; background: rgba(255, 255, 255, 0.035); border: 1px solid rgba(40, 84, 232, 0.16); padding: 20px; display: flex; flex-direction: column; gap: 12px;') ?>">
          <?php if (!empty($r1['eyebrow'])) : ?><div style="font-size: 12.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($r1['eyebrow'] ?? '') ?></div><?php endif; ?>
          <h3 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 20px; margin: 0px; display: flex; align-items: center; gap: 11px;"><span aria-hidden="true" style="<?= esc_attr($i1 === 0 ? 'flex: 0 0 auto; width: 40px; height: 40px; border-radius: 12px; background: var(--sh-blue); display: flex; align-items: center; justify-content: center;' : 'flex: 0 0 auto; width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(150deg, var(--sh-blue), rgb(26, 59, 214)); display: flex; align-items: center; justify-content: center;') ?>"><?= sh_icon($r1['icon'] ?? 'i4197e9b2') ?></span><?php if (!empty($r1['label'])) : ?><span><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?></h3>
          <?php if (!empty($r1['text'])) : ?><p style="font-size: 15px; color: var(--sh-ink); margin: 0px; text-wrap: pretty;"><?= esc_html($r1['text'] ?? '') ?></p><?php endif; ?>
          <div aria-hidden="true" data-fam-flow style="display: flex; flex-wrap: wrap; align-items: center; gap: 6px; padding: 10px 12px; border-radius: 12px; background: rgba(6, 11, 31, 0.45); border: 1px solid rgba(40, 84, 232, 0.14);"><?php if (!empty($r1['eyebrow_2'])) : ?><span style="font-size: 12.5px; font-weight: 600; color: var(--sh-ink);"><?= esc_html($r1['eyebrow_2'] ?? '') ?></span><?php endif; ?><span style="color: var(--sh-link); font-size: 12px;">←</span><?php if (!empty($r1['eyebrow_3'])) : ?><span style="font-size: 12.5px; font-weight: 600; color: var(--sh-ink);"><?= esc_html($r1['eyebrow_3'] ?? '') ?></span><?php endif; ?><span style="color: var(--sh-link); font-size: 12px;">←</span><?php if (!empty($r1['eyebrow_4'])) : ?><span style="font-size: 12.5px; font-weight: 600; color: var(--sh-ink);"><?= esc_html($r1['eyebrow_4'] ?? '') ?></span><?php endif; ?></div>
          <div style="display: flex; flex-direction: column; gap: 2px; margin-top: 4px;">
            <?php $r2_list = $r1['items'] ?? []; $i2_n = is_array($r2_list) ? count($r2_list) : 0; foreach ((array) $r2_list as $i2 => $r2) : ?><?php if (!empty(sh_link($r2['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($r2['link'] ?? '')) ?>" class="hv-54a5cb" style="font-size: 14.5px; color: var(--sh-ink); padding: 7px 0px; display: flex; align-items: center; justify-content: space-between; gap: 10px; border-bottom: 1px solid var(--sh-line);"><?php if (!empty($r2['label'])) : ?><span><?= esc_html($r2['label'] ?? '') ?></span><?php endif; ?><span aria-hidden="true" style="color: var(--sh-link);">←</span></a><?php endif; ?><?php endforeach; ?>
          </div>
        </div><?php endforeach; ?>
        <div style="border-radius: 18px; background: rgba(255, 255, 255, 0.035); border: 1px solid rgba(40, 84, 232, 0.16); padding: 20px; display: flex; flex-direction: column; gap: 12px;">
          <?php if (!empty($f['eyebrow_2'])) : ?><div style="font-size: 12.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow_2'] ?? '') ?></div><?php endif; ?>
          <h3 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 20px; margin: 0px; display: flex; align-items: center; gap: 11px;"><span aria-hidden="true" style="flex: 0 0 auto; width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(150deg, var(--sh-blue), rgb(26, 59, 214)); display: flex; align-items: center; justify-content: center;"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="#ffffff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9zM14 3v6h6"></path></svg></span><?php if (!empty($f['label'])) : ?><span><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?></h3>
          <?php if (!empty($f['text_2'])) : ?><p style="font-size: 15px; color: var(--sh-ink); margin: 0px; text-wrap: pretty;"><?= esc_html($f['text_2'] ?? '') ?></p><?php endif; ?>
          <div aria-hidden="true" data-fam-flow style="display: flex; flex-wrap: wrap; align-items: center; gap: 6px; padding: 10px 12px; border-radius: 12px; background: rgba(6, 11, 31, 0.45); border: 1px solid rgba(40, 84, 232, 0.14);"><?php if (!empty($f['eyebrow_3'])) : ?><span style="font-size: 12.5px; font-weight: 600; color: var(--sh-ink);"><?= esc_html($f['eyebrow_3'] ?? '') ?></span><?php endif; ?><span style="color: var(--sh-link); font-size: 12px;">←</span><?php if (!empty($f['eyebrow_4'])) : ?><span style="font-size: 12.5px; font-weight: 600; color: var(--sh-ink);"><?= esc_html($f['eyebrow_4'] ?? '') ?></span><?php endif; ?><span style="color: var(--sh-link); font-size: 12px;">←</span><?php if (!empty($f['eyebrow_5'])) : ?><span style="font-size: 12.5px; font-weight: 600; color: var(--sh-ink);"><?= esc_html($f['eyebrow_5'] ?? '') ?></span><?php endif; ?></div>
          <div style="display: flex; flex-direction: column; gap: 2px; margin-top: 4px;">
            <?php if (!empty(sh_link($f['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" class="hv-54a5cb" style="font-size: 14.5px; color: var(--sh-ink); padding: 7px 0px; display: flex; align-items: center; justify-content: space-between; gap: 10px; border-bottom: 1px solid var(--sh-line);"><?php if (!empty($f['label_2'])) : ?><span><?= esc_html($f['label_2'] ?? '') ?></span><?php endif; ?><span aria-hidden="true" style="color: var(--sh-link);">←</span></a><?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
