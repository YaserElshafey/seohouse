<?php
/**
 * Section "Season" — SEO House - Sector Tourism.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'tr-season')) ?>" data-screen-label="Season" style="position: relative; scroll-margin-top: 88px; background: var(--sh-surface); color: var(--sh-ink); border-bottom: 1px solid var(--sh-line);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(34px, 4vw, 56px) 20px;">
      <div data-tr-split style="display: grid; gap: 24px 48px; align-items: start;">
        <div>
      <div data-sec-head>
          <div style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><span aria-hidden="true" data-sx-ico style="flex: 0 0 auto; width: 30px; height: 30px; border-radius: 9px; background: rgba(40, 84, 232, 0.1); display: inline-flex; align-items: center; justify-content: center;"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="#2854E8" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2M12 20v2M2 12h2M20 12h2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"></path></svg></span><?php if (!empty($f['label'])) : ?><span><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?></div>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.35;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
          <?php if (!empty($f['text'])) : ?><p style="font-size: 16.5px; line-height: 1.95; color: var(--sh-text); margin: 14px 0px 0px; max-width: 44em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        </div>
        <ol style="list-style: none; margin: 0px; padding: 0px; display: flex; flex-direction: column; gap: 10px;">
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><li style="display: flex; gap: 14px; align-items: center; background: rgb(255, 255, 255); border-radius: 14px; padding: 14px 16px; box-shadow: rgba(40, 84, 232, 0.16) 0px 0px 0px 1px;"><?php if (!empty($r1['eyebrow'])) : ?><span style="flex: 0 0 auto; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 13px; color: var(--sh-link); min-width: 84px;"><?= esc_html($r1['eyebrow'] ?? '') ?></span><?php endif; ?><?php if (!empty($r1['text'])) : ?><span style="font-size: 15px; color: var(--sh-text);"><?= esc_html($r1['text'] ?? '') ?></span><?php endif; ?></li><?php endforeach; ?>
        </ol>
      </div>
    </div>
  </section>
