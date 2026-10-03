<?php
/**
 * Section "Platforms" — SEO House - Web Design.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Platforms" style="position: relative; overflow: hidden; background: var(--sh-ink); border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
    <div aria-hidden="true" style="position: absolute; inset: 0px; background: radial-gradient(50% 70% at 12% 20%, rgba(var(--sh-blue-rgb), 0.28), transparent 70%); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(34px, 4.4vw, 58px) 20px;">
      <div data-sec-head data-sh-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-sky);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; line-height: 1.3;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <div data-wd-tech style="margin-top: clamp(22px, 2.6vw, 32px); display: grid; gap: 14px;">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty(sh_link($r1['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($r1['link'] ?? '')) ?>" class="hv-2d2a70" style="display: flex; flex-direction: column; gap: 12px; border-radius: 16px; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(var(--sh-sky-rgb), 0.16); padding: 18px 18px 16px; color: var(--sh-text); transition: border-color 0.2s, background 0.2s;">
          <span style="display: flex; gap: 8px;"><span style="display: inline-flex; width: 40px; height: 40px; border-radius: 10px; background: rgb(255, 255, 255); align-items: center; justify-content: center;"><?= sh_svg_img($r1['logo'] ?? '', '', ['style' => 'width: 24px; height: 24px; display: block;'], (int) ($r1['logo_image'] ?? 0)) ?></span></span>
          <?php if (!empty($r1['heading'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18px;"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($r1['label'])) : ?><span style="font-size: 14.5px; line-height: 1.8; color: var(--sh-muted); text-wrap: pretty;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($r1['eyebrow'])) : ?><span style="margin-top: auto; font-size: 14px; font-weight: 600; color: var(--sh-lime);"><?= esc_html($r1['eyebrow'] ?? '') ?></span><?php endif; ?>
        </a><?php endif; ?><?php endforeach; ?>
        <?php if (!empty(sh_link($f['link'] ?? ''))) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" class="hv-2d2a70" style="display: flex; flex-direction: column; gap: 12px; border-radius: 16px; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(var(--sh-sky-rgb), 0.16); padding: 18px 18px 16px; color: var(--sh-text); transition: border-color 0.2s, background 0.2s;">
          <span style="display: flex; gap: 8px;"><?php $r1_list = $f['items_2'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><span style="display: inline-flex; width: 40px; height: 40px; border-radius: 10px; background: rgb(255, 255, 255); align-items: center; justify-content: center;"><?= sh_svg_img($r1['logo'] ?? '', '', ['style' => 'width: 24px; height: 24px; display: block;'], (int) ($r1['logo_image'] ?? 0)) ?></span><?php endforeach; ?></span>
          <?php if (!empty($f['heading'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18px;"><?= esc_html($f['heading'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($f['label'])) : ?><span style="font-size: 14.5px; line-height: 1.8; color: var(--sh-muted); text-wrap: pretty;"><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($f['eyebrow_2'])) : ?><span style="margin-top: auto; font-size: 14px; font-weight: 600; color: var(--sh-lime);"><?= esc_html($f['eyebrow_2'] ?? '') ?></span><?php endif; ?>
        </a><?php endif; ?>
        <?php if (!empty(sh_link($f['link_2'] ?? ''))) : ?><a href="<?= esc_url(sh_link($f['link_2'] ?? '')) ?>" class="hv-2d2a70" style="display: flex; flex-direction: column; gap: 12px; border-radius: 16px; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(var(--sh-sky-rgb), 0.16); padding: 18px 18px 16px; color: var(--sh-text); transition: border-color 0.2s, background 0.2s;">
          <span style="display: flex; gap: 8px;"><span style="display: inline-flex; width: 40px; height: 40px; border-radius: 10px; background: rgba(var(--sh-lime-rgb), 0.14); color: var(--sh-lime); align-items: center; justify-content: center;"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 8l-4 4 4 4M16 8l4 4-4 4M14 5l-4 14"></path></svg></span></span>
          <?php if (!empty($f['heading_2'])) : ?><span style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18px;"><?= esc_html($f['heading_2'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($f['label_2'])) : ?><span style="font-size: 14.5px; line-height: 1.8; color: var(--sh-muted); text-wrap: pretty;"><?= esc_html($f['label_2'] ?? '') ?></span><?php endif; ?>
          <?php if (!empty($f['eyebrow_3'])) : ?><span style="margin-top: auto; font-size: 14px; font-weight: 600; color: var(--sh-lime);"><?= esc_html($f['eyebrow_3'] ?? '') ?></span><?php endif; ?>
        </a><?php endif; ?>
      </div>
    </div>
  </section>
