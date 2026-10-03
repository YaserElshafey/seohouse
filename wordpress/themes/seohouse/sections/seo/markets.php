<?php
/**
 * Section "Markets" — SEO House - SEO Service Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Markets" style="border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
    <div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
      <?php if (!empty($f['title'])) : ?><h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); margin: 12px 0px 0px; line-height: 1.3;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      <div data-mkt3 style="margin-top: 28px; display: grid; gap: 16px;">
        
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty(sh_link($r1['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($r1['link'] ?? '')) ?>" class="hv-9f987a" style="display: flex; flex-direction: column; border-radius: 20px; overflow: hidden; background: rgba(255, 255, 255, 0.04); color: var(--sh-text); transition: transform 0.3s;">
            <span aria-hidden="true" style="display: block; height: 4px; background: linear-gradient(90deg, var(--sh-sky), rgba(var(--sh-lime-rgb), 0.6));"></span>
            <span style="display: flex; flex-direction: column; gap: 10px; padding: clamp(20px, 2.2vw, 28px); flex: 1 1 auto;">
              <?php if (!empty($r1['heading'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: 22px; color: rgb(255, 255, 255);"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
              <?php if (!empty($r1['text'])) : ?><span style="font-size: 15.5px; color: var(--sh-muted);"><?= esc_html($r1['text'] ?? '') ?></span><?php endif; ?>
              <?php if (!empty($r1['label'])) : ?><span style="margin-top: auto; padding-top: 8px; font-size: 14.5px; font-weight: 600; color: var(--sh-lime);"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
            </span>
          </a><?php endif; ?><?php endforeach; ?>
        
      </div>
    </div>
  </section>
