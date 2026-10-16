<?php
/**
 * Section "Hero" — SEO House - All Services.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Hero" data-hero-blue style="position: relative; overflow: hidden; background: linear-gradient(rgb(46, 90, 240) 0%, rgb(40, 84, 232) 60%, rgb(36, 76, 214) 100%); color: rgb(255, 255, 255);">
    <div aria-hidden="true" data-hero-grid style="position: absolute; inset: 0px; opacity: 0.07; background-image: linear-gradient(rgba(255, 255, 255, 0.9) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.9) 1px, transparent 1px); background-size: 72px 72px; mask-image: radial-gradient(90% 100% at 70% 10%, rgb(0, 0, 0), transparent 68%); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: 18px 20px 0px;">
      <?php sh_breadcrumbs(); ?>
    </div>

    <div data-g2 style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(24px, 2.8vw, 42px) 20px clamp(38px, 4.2vw, 60px);">
      <div style="animation: 0.7s ease 0s 1 normal both running fadeUp;">
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: rgb(255, 255, 255);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h1 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(28px, 3.2vw, 45px); line-height: 1.3; margin: 12px 0px 0px; max-width: 21em;"><?= esc_html($f['title'] ?? '') ?></h1><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="font-size: 17.5px; line-height: 1.85; color: rgb(255, 255, 255); max-width: 37em; margin: 18px 0px 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 14px 26px; margin-top: 28px;">
          <?php if (!empty($f['link_label'])) : ?><a href="#booking" data-hero-cta style="background: rgb(255, 255, 255); color: rgb(33, 72, 216); font-weight: 700; font-size: 16.5px; min-height: 56px; display: inline-flex; align-items: center; justify-content: center; padding: 0px 28px; border-radius: 14px;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
          <a href="#families" data-hero-sec style="min-height: 56px; display: inline-flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 600; color: rgb(255, 255, 255); padding: 0px 16px; border-radius: 14px;"><?= esc_html($f['link_label_2'] ?? '') ?> <i data-ghost-arrow aria-hidden="true">↓</i></a>
        </div>
      </div>
      <nav aria-label="فهرس الخدمات" style="animation: 0.7s ease 0.12s 1 normal both running fadeUp; position: relative;">
        <div aria-hidden="true" style="position: absolute; inset: -30px -20px; background: radial-gradient(60% 55% at 50% 40%, rgba(var(--sh-blue-rgb), 0.22), transparent 72%); pointer-events: none;"></div>
        <div style="position: relative; display: flex; flex-direction: column; gap: 0px;">
          <?php if (!empty(sh_link($f['link'] ?? ''))) : ?><a data-hcard href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" data-si-node class="hv-82c65b" style="display: flex; align-items: center; gap: 14px; padding: 18px 20px; border-radius: 16px; background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.35); color: rgb(255, 255, 255); transition: border-color 0.2s, background 0.2s;">
            <span style="flex: 0 0 auto; width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: rgb(255, 255, 255); color: rgb(33, 72, 216);"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="6.5"></circle><path d="m20 20-4.2-4.2"></path></svg></span>
            <span style="min-width: 0px; flex: 1 1 auto;"><?php if (!empty($f['heading'])) : ?><span style="display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17px;"><?= esc_html($f['heading'] ?? '') ?></span><?php endif; ?><?php if (!empty($f['label'])) : ?><span style="display: block; font-size: 13.5px; color: rgb(255, 255, 255); margin-top: 3px;"><?= esc_html($f['label'] ?? '') ?></span><?php endif; ?></span>
            <span aria-hidden="true" style="color: rgb(255, 255, 255);">←</span>
          </a><?php endif; ?>
          <div data-si-branch style="position: relative; padding-inline-start: 26px; margin-top: 12px; display: flex; flex-direction: column; gap: 10px;">
            <span aria-hidden="true" style="position: absolute; inset-inline-start: 21px; top: -12px; bottom: 28px; width: 1px; background: linear-gradient(var(--sh-lime), rgba(var(--sh-sky-rgb), 0.5));"></span>
            <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty(sh_link($r1['link'] ?? ''))) : ?><a data-hcard href="<?= esc_url(sh_link($r1['link'] ?? '')) ?>" data-si-node class="hv-8d0e6f" style="display: flex; align-items: center; gap: 14px; padding: 14px 16px; border-radius: 16px; background: rgba(255, 255, 255, 0.035); border: 1px solid rgba(var(--sh-sky-rgb), 0.18); color: rgb(255, 255, 255); transition: border-color 0.2s, background 0.2s;">
            <span style="flex: 0 0 auto; width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: rgba(var(--sh-blue-rgb), 0.18); color: rgb(255, 255, 255);"><?= sh_icon($r1['icon'] ?? 'ife038925') ?></span>
            <span style="min-width: 0px; flex: 1 1 auto;"><?php if (!empty($r1['heading'])) : ?><span style="display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 15.5px;"><?= esc_html($r1['heading'] ?? '') ?></span><?php endif; ?><?php if (!empty($r1['label'])) : ?><span style="display: block; font-size: 13.5px; color: rgb(255, 255, 255); margin-top: 3px;"><?= esc_html($r1['label'] ?? '') ?></span><?php endif; ?></span>
            <span aria-hidden="true" style="color: rgb(255, 255, 255);">←</span>
          </a><?php endif; ?><?php endforeach; ?>
          </div>
        </div>
      </nav>
    </div>
  </section>
