<?php
/**
 * Section "Journey" — SEO House - Sector Health.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'hl-journey')) ?>" data-screen-label="Journey" style="position: relative; scroll-margin-top: 88px; background: var(--sh-bg); border-bottom: 1px solid var(--sh-line);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(34px, 4vw, 56px) 20px;">
      <div data-sec-head>
          <div style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><span aria-hidden="true" data-sx-ico style="flex: 0 0 auto; width: 30px; height: 30px; border-radius: 9px; background: rgba(40, 84, 232, 0.22); display: inline-flex; align-items: center; justify-content: center;"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="#2854E8" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 3v6a5 5 0 0 0 10 0V3"></path><path d="M10 14v2a5 5 0 0 0 10 0v-3"></path><circle cx="20" cy="11" r="2"></circle></svg></span><?php if (!empty($f['label'])) : ?><span><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?></div>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.35;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <div style="position: relative; margin-top: clamp(24px, 2.8vw, 34px);">
        <span aria-hidden="true" data-hl-line></span>
        <ol data-hl-rail style="list-style: none; margin: 0px; padding: 0px; display: grid; gap: 14px;">
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><li style="position: relative; padding-top: 26px;">
            <span aria-hidden="true" style="position: absolute; top: 0px; inset-inline-start: 0px; width: 14px; height: 14px; border-radius: 999px; background: var(--sh-surface); border: 2px solid var(--sh-blue);"></span>
            <?php if (!empty($r1['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16px; color: var(--sh-ink);"><?= esc_html($r1['heading'] ?? '') ?></div><?php endif; ?>
            <?php if (!empty($r1['label'])) : ?><div style="font-size: 14px; color: var(--sh-text); margin-top: 4px;"><?= esc_html($r1['label'] ?? '') ?></div><?php endif; ?>
          </li><?php endforeach; ?>
        </ol>
      </div>
      <?php if (!empty($f['text'])) : ?><p style="font-size: 16.5px; line-height: 1.95; color: var(--sh-ink); margin: 24px 0px 0px; max-width: 44em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
    </div>
  </section>
