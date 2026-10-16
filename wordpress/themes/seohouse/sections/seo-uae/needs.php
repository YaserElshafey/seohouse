<?php
/**
 * Section "Needs" — SEO House - UAE SEO Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Needs" style="background: var(--sh-bg);">
    <div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28; margin: 0px;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      <div data-ua-needs style="margin-top: clamp(24px, 2.8vw, 38px); display: grid; gap: 0px clamp(28px, 3.4vw, 60px);">
        
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="display: flex; align-items: flex-start; gap: 16px; padding: 22px 0px; border-top: 1px solid var(--sh-line);">
            <span aria-hidden="true" style="<?= esc_attr($i1 === 0 ? 'flex: 0 0 auto; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 26px; color: rgb(33, 72, 216); line-height: 1;' : 'flex: 0 0 auto; font-family: Alexandria, sans-serif; font-weight: 800; font-size: 26px; color: rgba(40, 84, 232, 0.7); line-height: 1;') ?>"><?= esc_html(sprintf('%02d', $i1 + 1)) ?></span>
            <span style="min-width: 0px;">
              <?php if (!empty($r1['heading'])) : ?><span style="display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17.5px; line-height: 1.5;"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
              <?php if (!empty($r1['text'])) : ?><span style="display: block; font-size: 15px; color: var(--sh-ink); margin-top: 8px;"><?= esc_html($r1['text'] ?? '') ?></span><?php endif; ?>
              <?php if (!empty(sh_link($r1['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($r1['link'] ?? '')) ?>" style="display: inline-flex; align-items: center; gap: 8px; margin-top: 12px; font-size: 14.5px; font-weight: 600;"><?= esc_html($r1['link_label'] ?? '') ?> <span aria-hidden="true">←</span></a><?php endif; ?>
            </span>
          </div><?php endforeach; ?>
        
      </div>
    </div>
  </section>
