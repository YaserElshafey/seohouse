<?php
/**
 * Section "Results" — SEO House - Homepage.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'results')) ?>" data-screen-label="Results" style="position: relative; background: var(--sh-paper-2); color: var(--sh-ink);">
    <div style="position: relative; max-width: 1100px; margin: 0px auto; padding: clamp(34px, 4.2vw, 56px) 20px;">
      <div data-sh-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13px; font-weight: 600; color: var(--sh-blue);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); margin: 10px 0px 0px; line-height: 1.3;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      
      <ul style="list-style: none; margin: 24px 0px 0px; padding: 0px; display: flex; flex-direction: column; gap: 12px;"><?php get_template_part( 'parts/dynamic/case-list', null, array( 'context' => 'home', 'limit' => 3 ) ); ?></ul>
      <div style="margin-top: 22px; text-align: center;"><?php if (!empty(sh_link($f['link'] ?? '')) && !empty($f['link_label'])) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" class="hv-9bcb5e" style="display: inline-flex; align-items: center; gap: 8px; min-height: 48px; padding: 0px 22px; border-radius: 12px; background: var(--sh-blue); color: rgb(255, 255, 255); font-weight: 600;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?></div>
    </div>
  </section>
