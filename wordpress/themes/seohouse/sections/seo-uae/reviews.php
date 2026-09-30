<?php
/**
 * Section "Reviews" — SEO House - UAE SEO Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Reviews" style="background: var(--sh-paper); color: var(--sh-ink);">
    <div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-blue);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 16px; color: var(--sh-ink-soft); margin: 12px 0px 0px; max-width: 36em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
      </div>

      
      <div data-trustindex-mount style="margin-top: 22px; border-radius: 18px; background: rgb(255, 255, 255); border: 1px dashed rgba(var(--sh-blue-rgb), 0.28); padding: clamp(22px, 2.6vw, 34px); display: flex; flex-wrap: wrap; align-items: center; gap: 14px 28px;">
        <div style="flex: 1 1 320px; min-width: 0px;">
          <?php if (!empty($f['heading'])) : ?><div style="font-size: 15.5px; font-weight: 700; color: var(--sh-ink);"><?= esc_html($f['heading'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['text_2'])) : ?><div style="font-size: 15px; color: var(--sh-ink-soft); margin-top: 8px; max-width: 44em; text-wrap: pretty;"><?= esc_html($f['text_2'] ?? '') ?></div><?php endif; ?>
        </div>
        <?php if (!empty($f['label'])) : ?><div style="font-size: 12.5px; color: var(--sh-slate); background: rgba(var(--sh-blue-rgb), 0.06); border-radius: 999px; padding: 8px 14px;"><?= esc_html($f['label'] ?? '') ?></div><?php endif; ?>
      </div>
    </div>
  </section>
