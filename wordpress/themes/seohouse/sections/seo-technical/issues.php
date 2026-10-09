<?php
/**
 * Section "Issues" — SEO House - Technical SEO.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'issues')) ?>" data-screen-label="Issues" style="position: relative; scroll-margin-top: 88px; border-bottom: 1px solid var(--sh-line);">
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sec-head data-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.28;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <?php if (!empty($f['text'])) : ?><p data-center style="font-size: 16.5px; color: var(--sh-ink); margin: 14px 0px 0px; max-width: 48em; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
      <div style="margin-top: clamp(24px, 2.8vw, 38px); display: flex; flex-direction: column;">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_31257a34 = ['v1' => ['flex: 0 0 auto; width: 9px; height: 9px; border-radius: 999px; background: var(--sh-blue);', 'font-size: 13px; font-weight: 600; color: var(--sh-link);'], 'v2' => ['flex: 0 0 auto; width: 9px; height: 9px; border-radius: 999px; background: var(--sh-blue);', 'font-size: 13px; font-weight: 600; color: var(--sh-link);'], 'v3' => ['flex: 0 0 auto; width: 9px; height: 9px; border-radius: 999px; background: var(--sh-dim);', 'font-size: 13px; font-weight: 600; color: var(--sh-text);']]; /* previous design values: {"v1": "v1", "v2": "v1", "v3": "v2"} */ $vk_31257a34 = $vt_31257a34[$r1['variant'] ?? 'v1'] ?? $vt_31257a34['v1']; ?><div data-prow style="padding: 18px 4px; border-top: 1px solid var(--sh-line);">
          <span style="display: flex; align-items: center; gap: 12px; min-width: 0px;">
            <span aria-hidden="true" style="<?= esc_attr($vk_31257a34[0] ?? '') ?>"></span>
            <?php if (!empty($r1['heading'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17.5px;"><?= sh_inline($r1['heading'] ?? '') ?></span><?php endif; ?>
          </span>
          <?php if (!empty($r1['text'])) : ?><span style="font-size: 15px; color: var(--sh-ink); text-wrap: pretty;"><?= esc_html($r1['text'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($r1['eyebrow'])) : ?><span style="<?= esc_attr($vk_31257a34[1] ?? '') ?>"><?= esc_html($r1['eyebrow'] ?? '') ?></span><?php endif; ?>
        </div><?php endforeach; ?>
      </div>
    </div>
  </section>
