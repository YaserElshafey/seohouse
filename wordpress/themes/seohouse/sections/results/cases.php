<?php
/**
 * Section "Cases" — SEO House - Results.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Cases" style="background: var(--sh-surface); color: var(--sh-ink); min-height: 40vh;">
    <div style="max-width: 1100px; margin: 0px auto; padding: clamp(26px, 3vw, 40px) 20px clamp(40px, 4.6vw, 64px);">
      <div role="group" aria-label="تصفية النتائج" style="display: flex; flex-wrap: wrap; gap: 8px;"><?php get_template_part( 'parts/dynamic/case-filters', null, array(  ) ); ?></div>
      <ul style="list-style: none; margin: 22px 0px 0px; padding: 0px; display: flex; flex-direction: column; gap: 12px;"><?php get_template_part( 'parts/dynamic/case-list', null, array( 'context' => 'results', 'limit' => 0 ) ); ?></ul>
      <?php if (!empty($f['text'])) : ?><p style="margin: 18px 0px 0px; font-size: 14px; color: var(--sh-text);"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
    </div>
  </section>
