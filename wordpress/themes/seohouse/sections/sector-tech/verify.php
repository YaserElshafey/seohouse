<?php
/**
 * Section "Verify" — SEO House - Sector Tech.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'tc-verify')) ?>" data-screen-label="Verify" style="position: relative; scroll-margin-top: 88px; background: var(--sh-paper-2); color: var(--sh-ink); border-bottom: 1px solid rgba(var(--sh-ink-rgb), 0.07);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(34px, 4vw, 56px) 20px;">
      <div data-tc-split style="display: grid; gap: 24px 48px; align-items: start;">
        <div>
      <div data-sec-head>
          <div style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 600; color: var(--sh-blue);"><span aria-hidden="true" data-sx-ico style="flex: 0 0 auto; width: 30px; height: 30px; border-radius: 9px; background: rgba(var(--sh-blue-rgb), 0.1); display: inline-flex; align-items: center; justify-content: center;"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="#2F5BFF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M9 2v6M15 2v6M6 8h12v4a6 6 0 0 1-12 0z"></path><path d="M12 18v4"></path></svg></span><?php if (!empty($f['label'])) : ?><span><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?></div>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.35;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
          <?php if (!empty($f['text'])) : ?><p style="font-size: 16.5px; line-height: 1.95; color: var(--sh-ink-soft); margin: 14px 0px 0px; max-width: 44em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        </div>
        <div style="background: rgb(255, 255, 255); border-radius: 16px; padding: 20px; box-shadow: rgba(var(--sh-ink-rgb), 0.45) 0px 14px 34px -26px;">
          <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13px; font-weight: 600; color: var(--sh-blue);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['text_2'])) : ?><div style="font-size: 15px; color: var(--sh-surface-3); padding: 11px 0px; border-top: 1px solid rgba(var(--sh-ink-rgb), 0.08); margin-top: 10px;"><?= esc_html($f['text_2'] ?? '') ?></div><?php endif; ?>
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty($r1['text'])) : ?><div style="font-size: 15px; color: var(--sh-surface-3); padding: 11px 0px; border-top: 1px solid rgba(var(--sh-ink-rgb), 0.08); margin-top: 0px;"><?= esc_html($r1['text'] ?? '') ?></div><?php endif; ?><?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
