<?php
/**
 * Section "Pricing strip" — SEO House - SEO Service Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Pricing strip" style="border-top: 1px solid rgba(255, 255, 255, 0.1);">
    <div style="max-width: 1200px; margin: 0px auto; padding: clamp(28px, 3vw, 42px) 20px;">
      <div style="display: flex; flex-wrap: wrap; gap: 18px 36px; align-items: center; justify-content: space-between; border-radius: 20px; background: rgba(255, 255, 255, 0.04); padding: clamp(22px, 2.4vw, 30px);">
        <div style="flex: 1 1 420px; min-width: 0px;">
          <?php if (!empty($f['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 20px;"><?= esc_html($f['heading'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['text'])) : ?><p style="font-size: 15.5px; color: var(--sh-muted); margin: 10px 0px 0px; max-width: 48em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        </div>
        <div style="display: flex; flex-wrap: wrap; gap: 12px 20px; align-items: center;">
          <?php if (!empty($f['link_label'])) : ?><a href="#booking" class="hv-d5cb1d" style="background: var(--sh-lime); color: var(--sh-ink); font-weight: 700; font-size: 16px; min-height: 54px; display: inline-flex; align-items: center; padding: 0px 26px; border-radius: 14px;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
          <?php if (!empty(sh_link($f['link'] ?? '')) && !empty($f['link_label_2'])) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" style="font-size: 15px; font-weight: 600; border-bottom: 1px solid rgba(var(--sh-sky-rgb), 0.5); padding-bottom: 3px;"><?= esc_html($f['link_label_2'] ?? '') ?></a><?php endif; ?>
        </div>
      </div>
    </div>
  </section>
