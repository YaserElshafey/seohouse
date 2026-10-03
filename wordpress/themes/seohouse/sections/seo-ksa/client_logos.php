<?php
/**
 * Section "Client logos" — SEO House - Saudi SEO Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Client logos" style="border-top: 1px solid rgba(255, 255, 255, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.1); background: var(--sh-surface); padding: 24px 0px 28px;">
    <div style="max-width: 1200px; margin: 0px auto; padding: 0px 20px 18px; display: flex; flex-direction: column; align-items: center; gap: 12px;">
      <?php if (!empty($f['text'])) : ?><div style="font-weight: 600; font-size: 15.5px; color: var(--sh-muted);"><?= esc_html($f['text'] ?? '') ?></div><?php endif; ?>
      
    </div>
    <div style="overflow: hidden; mask-image: linear-gradient(90deg, transparent, rgb(0, 0, 0) 8%, rgb(0, 0, 0) 92%, transparent);">
      <div style="display: flex; width: max-content; animation: 38s linear 0s infinite normal none running shRtl;"><?php sh_logo_track( array( 'cell' => 'flex: 0 0 auto; display: flex; align-items: center; justify-content: center;', 'img' => 'width: auto; object-fit: contain; display: block; opacity: 0.82;' ) ); ?></div>
    </div>
  </section>
