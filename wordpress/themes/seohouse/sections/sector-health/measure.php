<?php
/**
 * Section "Measure" — SEO House - Sector Health.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'hl-measure')) ?>" data-screen-label="Measure" style="position: relative; scroll-margin-top: 88px; background: var(--sh-ink); border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(34px, 4vw, 56px) 20px;">
      <div data-sec-head data-center>
          <div style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><span aria-hidden="true" data-sx-ico style="flex: 0 0 auto; width: 30px; height: 30px; border-radius: 9px; background: rgba(var(--sh-blue-rgb), 0.22); display: inline-flex; align-items: center; justify-content: center;"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="#C7FF32" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="17" rx="2"></rect><path d="M3 9h18M8 2v4M16 2v4"></path></svg></span><?php if (!empty($f['label'])) : ?><span><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?></div>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.35;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <div data-pts data-n="3" style="margin-top: 24px; display: grid; gap: 18px 28px; grid-template-columns: repeat(3, minmax(0px, 1fr));">
        <?php if (!empty($f['text'])) : ?><div style="border-top: 2px solid var(--sh-lime); padding-top: 14px; font-size: 15.5px; line-height: 1.85; color: var(--sh-muted); text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></div><?php endif; ?>
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty($r1['text'])) : ?><div style="border-top: 2px solid rgba(var(--sh-sky-rgb), 0.4); padding-top: 14px; font-size: 15.5px; line-height: 1.85; color: var(--sh-muted); text-wrap: pretty;"><?= esc_html($r1['text'] ?? '') ?></div><?php endif; ?><?php endforeach; ?>
      </div>
    </div>
  </section>
