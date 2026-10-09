<?php
/**
 * Section "Proof" — SEO House - SEO Content.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Proof" style="position: relative; background: var(--sh-surface); color: var(--sh-ink); border-bottom: 1px solid var(--sh-line);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 18px 40px;">
        <div style="flex: 1 1 380px; min-width: 0px;">
          <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: clamp(24px, 2.6vw, 32px); color: var(--sh-link); line-height: 1.2; margin-top: 10px;"><?= esc_html($f['heading'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['text'])) : ?><p style="font-size: 15.5px; color: var(--sh-text); margin: 12px 0px 0px; max-width: 40em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        </div>
        <?php if (!empty($f['label'])) : ?><div style="font-size: 12.5px; color: var(--sh-text);"><?= esc_html($f['label'] ?? '') ?></div><?php endif; ?>
      </div>
      <?php if (!empty(sh_link($f['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" style="display: inline-flex; align-items: center; gap: 8px; margin-top: 24px; font-size: 15px; font-weight: 600; color: var(--sh-link); border-bottom: 1px solid rgba(40, 84, 232, 0.4); padding-bottom: 3px;"><?= esc_html($f['link_label'] ?? '') ?> <span aria-hidden="true">←</span></a><?php endif; ?>
    </div>
  </section>
