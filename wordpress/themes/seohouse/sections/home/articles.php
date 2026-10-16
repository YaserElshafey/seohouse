<?php
/**
 * Section "Articles" — SEO House - Homepage.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Articles" style="background: var(--sh-surface); color: var(--sh-ink);">
    <div style="max-width: 1200px; margin: 0px auto; padding: clamp(30px, 3.4vw, 48px) 20px;">
      <div style="display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 14px;">
        <div>
          <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['title'])) : ?><h2 data-h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); margin: 10px 0px 0px; line-height: 1.3;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
        </div>
        <?php if (!empty(sh_link($f['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" style="font-size: 15px; font-weight: 600; color: var(--sh-link); display: inline-flex; align-items: center; gap: 8px; min-height: 44px;"><?= esc_html($f['link_label'] ?? '') ?> <span aria-hidden="true">←</span></a><?php endif; ?>
      </div>
      <div data-grid="blog2" style="margin-top: 20px; display: grid; gap: 16px; align-items: stretch;"><?php get_template_part( 'parts/dynamic/posts-home', null, array(  ) ); ?></div>
    </div>
  </section>
