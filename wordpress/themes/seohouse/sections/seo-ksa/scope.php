<?php
/**
 * Section "Scope" — SEO House - Saudi SEO Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Scope" style="border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
    <div style="max-width: 1100px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>

      <div style="position: relative; margin-top: clamp(24px, 2.8vw, 38px);">
        <span aria-hidden="true" style="position: absolute; inset-inline-start: 9px; top: 30px; bottom: 30px; width: 1px; background: linear-gradient(rgba(var(--sh-lime-rgb), 0.5), rgba(var(--sh-sky-rgb), 0.4), rgba(var(--sh-sky-rgb), 0.08));"></span>
        
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty(sh_link($r1['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($r1['link'] ?? '')) ?>" class="hv-52a56b" style="position: relative; display: flex; align-items: flex-start; gap: 22px; padding: 20px 4px; border-top: 1px solid rgba(255, 255, 255, 0.1); color: var(--sh-text); transition: padding 0.25s, color 0.25s;">
            <span aria-hidden="true" style="<?= esc_attr($i1 === 0 ? 'flex: 0 0 auto; margin-top: 6px; width: 19px; height: 19px; border-radius: 999px; background: var(--sh-ink); border: 2px solid var(--sh-lime);' : 'flex: 0 0 auto; margin-top: 6px; width: 19px; height: 19px; border-radius: 999px; background: var(--sh-ink); border: 2px solid var(--sh-sky);') ?>"></span>
            <span style="flex: 1 1 auto; min-width: 0px;">
              <?php if (!empty($r1['heading'])) : ?><span style="display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(18px, 1.8vw, 21px);"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
              <?php if (!empty($r1['text'])) : ?><span style="display: block; font-size: 15.5px; color: var(--sh-muted); margin-top: 6px; max-width: 44em;"><?= esc_html($r1['text'] ?? '') ?></span><?php endif; ?>
            </span>
            <span aria-hidden="true" style="flex: 0 0 auto; margin-top: 6px; color: var(--sh-sky);">←</span>
          </a><?php endif; ?><?php endforeach; ?>
        
      </div>

      <?php if (!empty(sh_link($f['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" style="display: inline-flex; align-items: center; gap: 8px; margin-top: 22px; font-size: 15px; font-weight: 600; border-bottom: 1px solid rgba(var(--sh-sky-rgb), 0.5); padding-bottom: 3px;"><?= esc_html($f['link_label'] ?? '') ?> <span aria-hidden="true">←</span></a><?php endif; ?>
    </div>
  </section>
