<?php
/**
 * Section "Why" — SEO House - Egypt SEO Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Why" style="border-bottom: 1px solid var(--sh-line);">
    <div style="max-width: 1200px; margin: 0px auto; padding: clamp(28px, 3vw, 42px) 20px;">
      <div style="border-radius: 20px; background: linear-gradient(120deg, rgba(40, 84, 232, 0.16), rgba(255, 255, 255, 0.035)); padding: clamp(22px, 2.6vw, 32px);">
        <?php if (!empty($f['title'])) : ?><h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); line-height: 1.35; max-width: 30em; margin: 0px;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
        <div data-eg-why style="margin-top: 20px; display: grid; gap: 14px 30px;">
          
            <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div style="display: flex; align-items: flex-start; gap: 11px; font-size: 15.5px; color: var(--sh-ink);">
              <span aria-hidden="true" style="flex: 0 0 auto; margin-top: 6px; width: 7px; height: 7px; border-radius: 999px; background: rgb(40, 84, 232);"></span>
              <?php if (!empty($r1['label'])) : ?><span style="text-wrap: pretty;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
            </div><?php endforeach; ?>
          
        </div>
      </div>
    </div>
  </section>
