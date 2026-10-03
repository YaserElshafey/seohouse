<?php
/**
 * Section "Practice" — SEO House - Sector Legal.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'lg-practice')) ?>" data-screen-label="Practice" style="position: relative; scroll-margin-top: 88px; background: var(--sh-ink); border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(34px, 4vw, 56px) 20px;">
      <div data-sec-head>
          <div style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><span aria-hidden="true" data-sx-ico style="flex: 0 0 auto; width: 30px; height: 30px; border-radius: 9px; background: rgba(var(--sh-blue-rgb), 0.22); display: inline-flex; align-items: center; justify-content: center;"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="#C7FF32" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v18M5 21h14M6 7h12"></path><path d="m6 7-3 7a3 3 0 0 0 6 0zM18 7l-3 7a3 3 0 0 0 6 0z"></path></svg></span><?php if (!empty($f['label'])) : ?><span><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?></div>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.35;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <div role="table" aria-label="الفرق بين المقال وصفحة الخدمة" style="margin-top: 22px; border-radius: 16px; overflow: hidden; border: 1px solid rgba(var(--sh-sky-rgb), 0.2);">
        <div role="row" data-lg-row style="display: grid; background: rgba(var(--sh-blue-rgb), 0.14);">
          <span role="columnheader"></span>
          <?php if (!empty($f['eyebrow'])) : ?><span role="columnheader" style="padding: 12px 16px; font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($f['eyebrow_2'])) : ?><span role="columnheader" style="padding: 12px 16px; font-size: 13.5px; font-weight: 600; color: var(--sh-lime);"><?= esc_html($f['eyebrow_2'] ?? '') ?></span><?php endif; ?>
        </div>
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div role="row" data-lg-row style="display: grid; border-top: 1px solid rgba(255, 255, 255, 0.08);">
          <?php if (!empty($r1['eyebrow'])) : ?><span role="rowheader" style="padding: 14px 16px; font-size: 14px; font-weight: 600; color: rgb(154, 168, 198);"><?= esc_html($r1['eyebrow'] ?? '') ?></span><?php endif; ?>
          <?php $r2_list = $r1['items'] ?? []; $i2_n = is_array($r2_list) ? count($r2_list) : 0; foreach ((array) $r2_list as $i2 => $r2) : ?><?php if (!empty($r2['text'])) : ?><span role="cell" style="padding: 14px 16px; font-size: 15px; color: var(--sh-text);"><?= esc_html($r2['text'] ?? '') ?></span><?php endif; ?><?php endforeach; ?>
        </div><?php endforeach; ?>
      </div>
      <?php if (!empty($f['text'])) : ?><p style="font-size: 16.5px; line-height: 1.95; color: var(--sh-muted); margin: 22px 0px 0px; max-width: 44em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
    </div>
  </section>
