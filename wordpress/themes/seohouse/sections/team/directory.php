<?php
/**
 * Section "Directory" — SEO House - Team.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'directory')) ?>" data-screen-label="Directory" style="position: relative; overflow: hidden; scroll-margin-top: 88px; border-bottom: 1px solid var(--sh-line);">
    <div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <?php if (!empty($f['title'])) : ?><h2 style="position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0px, 0px, 0px, 0px);"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      <ul data-tm-grid role="list" style="list-style: none; margin: 0px; padding: 0px; display: grid; gap: 16px;"><?php get_template_part( 'parts/dynamic/team-directory', null, array(  ) ); ?></ul>
      <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px 24px; margin-top: 28px; padding-top: 20px; border-top: 1px solid var(--sh-line);">
        <?php if (!empty($f['text'])) : ?><p style="font-size: 15.5px; color: var(--sh-ink); margin: 0px;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        <?php if (!empty(sh_link($f['link'] ?? '')) && !empty($f['link_label'])) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" style="font-size: 15px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
      </div>
    </div>
  </section>
