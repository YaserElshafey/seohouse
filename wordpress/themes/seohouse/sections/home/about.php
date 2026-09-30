<?php
/**
 * Section "About" — SEO House - Homepage.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="About" style="background: var(--sh-ink); color: var(--sh-text);">
    <div data-grid="about" style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px; display: grid; gap: clamp(26px, 3vw, 52px); align-items: center;">
      <div>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); margin: 10px 0px 0px; line-height: 1.3;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 17px; color: var(--sh-muted); margin: 16px 0px 0px; max-width: 38em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        <?php if (!empty(sh_link($f['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" style="display: inline-flex; align-items: center; gap: 8px; margin-top: 20px; font-size: 14.5px; font-weight: 600; border-bottom: 1px solid rgba(var(--sh-sky-rgb), 0.5); padding-bottom: 3px;"><?= esc_html($f['link_label'] ?? '') ?> <span>←</span></a><?php endif; ?>
      </div>
      <div style="display: flex; flex-direction: column; gap: 2px;">
        
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="display: flex; align-items: center; gap: 14px; padding: 18px 0px; border-top: 1px solid rgba(255, 255, 255, 0.12);">
            <span aria-hidden="true" style="flex: 0 0 auto; width: 28px; height: 28px; border-radius: 999px; background: rgba(var(--sh-lime-rgb), 0.16); color: var(--sh-lime); display: flex; align-items: center; justify-content: center; font-size: 13px;">✓</span>
            <?php if (!empty($r1['text'])) : ?><span style="font-size: 16.5px; color: var(--sh-text);"><?= esc_html($r1['text'] ?? '') ?></span><?php endif; ?>
          </div><?php endforeach; ?>
        
      </div>
    </div>
  </section>
