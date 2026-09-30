<?php
/**
 * Section "Visibility" — SEO House - Sector Ecommerce.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'ec-visibility')) ?>" data-screen-label="Visibility" style="position: relative; scroll-margin-top: 88px; background: var(--sh-paper-2); color: var(--sh-ink); border-bottom: 1px solid rgba(var(--sh-ink-rgb), 0.07);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(34px, 4vw, 56px) 20px;">
      <div data-ec-split style="display: grid; gap: 24px 48px; align-items: start;">
        <div>
      <div data-sec-head>
          <div style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 600; color: var(--sh-blue);"><span aria-hidden="true" data-sx-ico style="flex: 0 0 auto; width: 30px; height: 30px; border-radius: 9px; background: rgba(var(--sh-blue-rgb), 0.1); display: inline-flex; align-items: center; justify-content: center;"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="#2F5BFF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8z"></path><circle cx="7.5" cy="7.5" r="1.5"></circle></svg></span><?php if (!empty($f['label'])) : ?><span><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?></div>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.35;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
          <?php if (!empty($f['text'])) : ?><p style="font-size: 16.5px; line-height: 1.95; color: var(--sh-ink-soft); margin: 14px 0px 0px; max-width: 44em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        </div>
        <ul style="list-style: none; margin: 0px; padding: 20px 22px; background: rgb(255, 255, 255); border-radius: 16px; box-shadow: rgba(var(--sh-ink-rgb), 0.45) 0px 14px 34px -26px; display: flex; flex-direction: column; gap: 0px;">
          <?php if (!empty($f['text_2'])) : ?><li style="font-size: 13px; font-weight: 600; color: var(--sh-blue); padding-bottom: 10px;"><?= esc_html($f['text_2'] ?? '') ?></li><?php endif; ?>
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><li style="display: flex; gap: 10px; align-items: baseline; font-size: 15px; color: var(--sh-surface-3); padding: 11px 0px; border-top: 1px solid rgba(var(--sh-ink-rgb), 0.08);"><span aria-hidden="true" style="width: 7px; height: 7px; border-radius: 2px; background: var(--sh-blue); flex: 0 0 auto; transform: translateY(-1px);"></span><?= esc_html($r1['text'] ?? '') ?></li><?php endforeach; ?>
        </ul>
      </div>
    </div>
  </section>
