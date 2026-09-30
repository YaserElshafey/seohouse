<?php
/**
 * Section "Document" — SEO House - Privacy Policy.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Document" style="position: relative; background: var(--sh-paper); color: var(--sh-ink);">
    <div data-lg-grid style="position: relative; max-width: 1120px; margin: 0px auto; padding: clamp(28px, 3.4vw, 50px) 20px clamp(32px, 4.4vw, 60px); display: grid; gap: clamp(22px, 3vw, 52px); align-items: start;">
      <nav data-lg-nav aria-label="محتويات الصفحة" style="border-radius: 14px; background: rgb(255, 255, 255); padding: 16px 18px; box-shadow: rgba(var(--sh-ink-rgb), 0.5) 0px 14px 32px -28px;">
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13px; font-weight: 700; color: var(--sh-blue); margin-bottom: 10px;"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <ol style="list-style: none; margin: 0px; padding: 0px; display: flex; flex-direction: column; gap: 2px;">
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_6155100f = ['v1' => ['#p1'], 'v2' => ['#p2'], 'v3' => ['#p3'], 'v4' => ['#p4'], 'v5' => ['#p5'], 'v6' => ['#p6'], 'v7' => ['#p7'], 'v8' => ['#p8']]; $vk_6155100f = $vt_6155100f[$r1['variant'] ?? 'v1'] ?? $vt_6155100f['v1']; ?><li><a href="<?= esc_attr($vk_6155100f[0] ?? '') ?>" class="hv-a18a86" style="display: flex; gap: 10px; font-size: 14.5px; color: var(--sh-ink-soft); padding: 7px 0px;"><?php if (!empty($r1['label'])) : ?><span><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?></a></li><?php endforeach; ?>
        </ol>
      </nav>
      <div style="min-width: 0px; max-width: 720px;">
        <?php if (!empty($f['label'])) : ?><div style="font-size: 13.5px; color: var(--sh-slate);"><?= esc_html($f['label'] ?? '') ?> </div><?php endif; ?>
        <?php $r1_list = $f['items_2'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_84b96cf1 = ['v1' => ['p1'], 'v2' => ['p2'], 'v3' => ['p3'], 'v4' => ['p4'], 'v5' => ['p5'], 'v6' => ['p6'], 'v7' => ['p7'], 'v8' => ['p8']]; $vk_84b96cf1 = $vt_84b96cf1[$r1['variant'] ?? 'v1'] ?? $vt_84b96cf1['v1']; ?><div id="<?= esc_attr($vk_84b96cf1[0] ?? '') ?>" style="<?= esc_attr($i1 === 0 ? 'scroll-margin-top: 96px; padding: 18px 0px 0px;' : 'scroll-margin-top: 96px; padding: 26px 0px 0px;') ?>">
          <?php if (!empty($r1['title'])) : ?><h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); line-height: 1.4; margin: 0px 0px 12px;"><?= esc_html($r1['title'] ?? '') ?></h2><?php endif; ?>
          <?php if (!empty($r1['label'])) : ?><span style="display: inline-block; font-size: 14.5px; color: rgb(142, 91, 0); background: rgba(245, 166, 35, 0.12); border-radius: 8px; padding: 6px 10px;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
        </div><?php endforeach; ?>
      </div>
    </div>
  </section>
