<?php
/**
 * Section "Scope" — SEO House - Egypt SEO Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'eg-scope')) ?>" data-screen-label="Scope" style="border-bottom: 1px solid rgba(255, 255, 255, 0.1); scroll-margin-top: 88px;">
    <div style="max-width: 1100px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 16.5px; color: var(--sh-muted); margin: 16px 0px 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
      </div>

      <div style="position: relative; margin-top: clamp(24px, 2.8vw, 38px); display: flex; flex-direction: column;">
        
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty(sh_link($r1['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($r1['link'] ?? '')) ?>" class="hv-52a56b" style="display: flex; align-items: flex-start; gap: 18px; padding: 20px 4px; border-top: 1px solid rgba(255, 255, 255, 0.12); color: var(--sh-text); transition: color 0.25s, padding 0.25s;">
            <span aria-hidden="true" style="<?= esc_attr($i1 === 0 ? 'flex: 0 0 auto; margin-top: 4px; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: var(--sh-lime);' : 'flex: 0 0 auto; margin-top: 4px; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 12.5px; color: var(--sh-sky);') ?>"><?= esc_html(sprintf('%02d', $i1 + 1)) ?></span>
            <span style="flex: 1 1 auto; min-width: 0px;">
              <?php if (!empty($r1['heading'])) : ?><span style="display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18.5px;"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
              <?php if (!empty($r1['text'])) : ?><span style="display: block; font-size: 15px; color: var(--sh-muted); margin-top: 7px; max-width: 46em; text-wrap: pretty;"><?= esc_html($r1['text'] ?? '') ?></span><?php endif; ?>
            </span>
            <span aria-hidden="true" style="flex: 0 0 auto; margin-top: 6px; color: var(--sh-sky);">←</span>
          </a><?php endif; ?><?php endforeach; ?>
        
      </div>
    </div>
  </section>
