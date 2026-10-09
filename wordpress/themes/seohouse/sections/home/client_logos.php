<?php
/**
 * Section "Client logos" — SEO House - Homepage.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Client logos" style="border-top: 1px solid var(--sh-line); border-bottom: 1px solid var(--sh-line); background: var(--sh-surface);">
    <div data-lg-row style="max-width: 1320px; margin: 0px auto; padding: 0px 20px; display: flex; align-items: center; gap: 0px;">
      <div data-lg-title style="flex: 0 0 auto; display: flex; align-items: center; gap: 10px; padding-block: 22px; padding-inline-end: 26px; border-inline-end: 1px solid rgba(37, 43, 51, 0.11);">
        <span aria-hidden="true" style="width: 8px; height: 8px; border-radius: 2px; background: var(--sh-blue);"></span>
        <?php if (!empty($f['text'])) : ?><span style="font-weight: 600; font-size: 15.5px; color: var(--sh-ink); white-space: nowrap;"><?= esc_html($f['text'] ?? '') ?></span><?php endif; ?>
      </div>
      <div data-lg-track-wrap style="flex: 1 1 auto; min-width: 0px; overflow: hidden; mask-image: linear-gradient(90deg, transparent, rgb(0, 0, 0) 10%, rgb(0, 0, 0) 96%, transparent);">
        <div style="display: flex; align-items: center; width: max-content; padding-block: 22px; animation: 46s linear 0s infinite normal none running shRtl;"><?php sh_logo_track( array( 'cell' => 'flex: 0 0 auto; display: flex; align-items: center; justify-content: center;', 'img' => 'width: auto; object-fit: contain; display: block; opacity: 0.86;' ) ); ?></div>
      </div>
    </div>
  </section>
