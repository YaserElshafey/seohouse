<?php
/**
 * Section "Platforms" — SEO House - Homepage.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Platforms" style="position: relative; overflow: hidden; background: var(--sh-paper-2); color: var(--sh-ink);">
    <div aria-hidden="true" style="position: absolute; inset: 0px; opacity: 0.5; background-image: radial-gradient(rgba(var(--sh-blue-rgb), 0.16) 1px, transparent 1px); background-size: 24px 24px; mask-image: radial-gradient(70% 80%, rgb(0, 0, 0), transparent 80%); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(34px, 4vw, 56px) 20px;">
      <div style="text-align: center;">
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13px; font-weight: 600; color: var(--sh-blue);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); margin: 10px 0px 0px; line-height: 1.3;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <div style="margin-top: 28px;">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div data-plat-row>
          <div data-plat-track>
            
              <?php foreach ( array( false, true ) as $dup2 ) : ?><?php $r2_list = $r1['items'] ?? []; $i2_n = is_array($r2_list) ? count($r2_list) : 0; foreach ((array) $r2_list as $i2 => $r2) : ?><div data-plat aria-hidden="<?= $dup2 ? 'true' : 'false' ?>">
                <span data-plat-chip><?= sh_svg_img($r2['logo'] ?? '', '', ['data-wide' => 'false']) ?><?php if (!empty($r2['label'])) : ?><span><?= esc_html($r2['label'] ?? '') ?></span><?php endif; ?></span>
              </div><?php endforeach; ?><?php endforeach; ?>
            
          </div>
        </div><?php endforeach; ?>
      </div>
    </div>
  </section>
