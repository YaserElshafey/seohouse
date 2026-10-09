<?php
/**
 * Section "Path" — SEO House - All Services.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Path" style="background: var(--sh-surface); color: var(--sh-ink);">
    <div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head data-sh-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <div data-g3 style="margin-top: clamp(24px, 2.8vw, 36px);">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty(sh_link($r1['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($r1['link'] ?? '')) ?>" class="hv-54a5cb" style="<?= esc_attr($i1 === 0 ? 'display: flex; flex-direction: column; gap: 10px; padding: 20px 4px; border-top: 2px solid var(--sh-blue); color: var(--sh-ink);' : 'display: flex; flex-direction: column; gap: 10px; padding: 20px 4px; border-top: 2px solid rgba(40, 84, 232, 0.3); color: var(--sh-ink);') ?>">
          <?php if (!empty($r1['eyebrow'])) : ?><span style="font-size: 12.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($r1['eyebrow'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($r1['heading'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 19px;"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($r1['text'])) : ?><span style="font-size: 15px; color: var(--sh-text);"><?= esc_html($r1['text'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($r1['label'])) : ?><span style="font-size: 14.5px; font-weight: 600; color: var(--sh-link); margin-top: auto;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
        </a><?php endif; ?><?php endforeach; ?>
      </div>
      <?php if (!empty(sh_link($f['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" style="display: inline-flex; align-items: center; gap: 8px; margin-top: 24px; font-size: 15px; font-weight: 600; color: var(--sh-link); border-bottom: 1px solid rgba(40, 84, 232, 0.4); padding-bottom: 3px;"><?= esc_html($f['link_label'] ?? '') ?> <span aria-hidden="true">←</span></a><?php endif; ?>
    </div>
  </section>
