<?php
/**
 * Section "List" — SEO House - Sectors.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'list')) ?>" data-screen-label="List" style="position: relative; scroll-margin-top: 88px; border-bottom: 1px solid var(--sh-line);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <div style="margin-top: clamp(24px, 2.8vw, 36px); display: flex; flex-direction: column;">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty(sh_link($r1['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($r1['link'] ?? '')) ?>" data-sc-row class="hv-fdd2a3" style="display: grid; gap: 8px 24px; align-items: center; padding: 18px 4px; border-top: 1px solid var(--sh-line); color: var(--sh-ink); transition: color 0.22s, padding 0.22s;">
          <span style="display: flex; align-items: center; gap: 14px; min-width: 0px;"><span aria-hidden="true" data-sc-ico style="flex: 0 0 auto; align-self: center; width: 40px; height: 40px; border-radius: 12px; background: rgba(40, 84, 232, 0.2); border: 1px solid rgba(40, 84, 232, 0.3); display: flex; align-items: center; justify-content: center;"><?= sh_icon($r1['icon'] ?? 'i7b51f90c') ?></span><?php if (!empty($r1['heading'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(18px, 1.9vw, 22px);"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?></span>
          <?php if (!empty($r1['label'])) : ?><span style="font-size: 14.5px; color: var(--sh-text);"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
          <span data-sc-path style="display: flex; flex-wrap: wrap; align-items: center; gap: 6px;"><?php if (!empty($r1['eyebrow'])) : ?><span style="font-size: 12.5px; font-weight: 600; color: var(--sh-ink); background: rgba(40, 84, 232, 0.18); border-radius: 999px; padding: 5px 11px;"><?= esc_html($r1['eyebrow'] ?? '') ?></span><?php endif; ?><span aria-hidden="true" style="color: var(--sh-link); font-size: 12px;">←</span><?php if (!empty($r1['eyebrow_2'])) : ?><span style="font-size: 12.5px; font-weight: 600; color: var(--sh-ink); background: rgba(40, 84, 232, 0.18); border-radius: 999px; padding: 5px 11px;"><?= esc_html($r1['eyebrow_2'] ?? '') ?></span><?php endif; ?><span aria-hidden="true" style="color: var(--sh-link); font-size: 12px;">←</span><?php if (!empty($r1['eyebrow_3'])) : ?><span style="font-size: 12.5px; font-weight: 600; color: var(--sh-ink); background: rgba(40, 84, 232, 0.18); border-radius: 999px; padding: 5px 11px;"><?= esc_html($r1['eyebrow_3'] ?? '') ?></span><?php endif; ?></span>
        </a><?php endif; ?><?php endforeach; ?>
      </div>
    </div>
  </section>
