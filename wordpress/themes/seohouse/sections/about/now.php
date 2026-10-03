<?php
/**
 * Section "Now" — SEO House - About.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Now" style="position: relative; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-ab-two style="display: grid; gap: clamp(24px, 3vw, 46px); align-items: start;">
        <div>
      <div data-sec-head>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <?php if (!empty($f['text'])) : ?><p style="font-size: 16.5px; color: var(--sh-muted); margin: 14px 0px 0px; max-width: 48em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
          <?php if (!empty(sh_link($f['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" style="display: inline-flex; align-items: center; gap: 8px; margin-top: 20px; font-size: 15px; font-weight: 600; border-bottom: 1px solid rgba(var(--sh-sky-rgb), 0.5); padding-bottom: 3px;"><?= esc_html($f['link_label'] ?? '') ?> <span aria-hidden="true">←</span></a><?php endif; ?>
        </div>
        <div style="display: flex; flex-direction: column;">
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="display: flex; align-items: flex-start; gap: 16px; padding: 16px 0px; border-top: 1px solid rgba(255, 255, 255, 0.12);">
            <?php if (!empty($r1['eyebrow'])) : ?><span style="<?= esc_attr($i1 === 0 ? 'flex: 0 0 auto; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 13px; color: var(--sh-lime); min-width: 56px;' : 'flex: 0 0 auto; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 13px; color: var(--sh-sky); min-width: 56px;') ?>"><?= esc_html($r1['eyebrow'] ?? '') ?></span><?php endif; ?>
            <?php if (!empty($r1['text'])) : ?><span style="min-width: 0px; font-size: 15.5px; color: var(--sh-muted); text-wrap: pretty;"><?= esc_html($r1['text'] ?? '') ?></span><?php endif; ?>
          </div><?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
