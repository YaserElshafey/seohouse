<?php
/**
 * Section "Services" — SEO House - Homepage.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 * @sh-manual
 * Maintained by hand since 2.7.12: white cards ([data-lcard], theme.css) instead of the generated
 * translucent ones; the first card is the lead service ([data-card-lead]). Content and links unchanged.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Services" style="position: relative; overflow: hidden; background: var(--sh-bg); border-bottom: 1px solid var(--sh-line);">
    <div aria-hidden="true" style="position: absolute; inset: 0px; background: radial-gradient(55% 70% at 85% 20%, rgba(40, 84, 232, 0.05), transparent 70%), radial-gradient(35% 50% at 10% 100%, rgba(171, 182, 194, 0.08), transparent 70%); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div style="display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 16px;">
        <?php if (!empty($f['title'])) : ?><h2 data-h2 data-svc-h style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); margin: 0px; line-height: 1.3; flex: 1 1 100%;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
        <?php if (!empty(sh_link($f['link'] ?? '')) && !empty($f['link_label'])) : ?><a href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" style="font-size: 15px; font-weight: 600;"><?= esc_html($f['link_label'] ?? '') ?></a><?php endif; ?>
      </div>
      <div data-grid="services4" style="margin-top: 36px; display: grid; gap: 16px;">
        
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php if (!empty(sh_link($r1['link'] ?? ''))) : ?><a data-lcard<?= 0 === $i1 ? ' data-card-lead' : '' ?> href="<?= esc_url(sh_link($r1['link'] ?? '')) ?>" style="display: flex; flex-direction: column; gap: 12px; padding: clamp(22px, 2.4vw, 30px); border-radius: 6px 22px 22px;">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px;">
              <span aria-hidden="true" data-lcard-icon>
                <?= sh_icon($r1['icon'] ?? 'i9db3f4c7') ?>
              </span>
              <?php if (!empty($r1['eyebrow'])) : ?><span style="<?= esc_attr($i1 === 0 ? 'display: inline-block; font-size: 12.5px; font-weight: 600; color: rgb(255, 255, 255); background: var(--sh-blue); border-radius: 999px; padding: 6px 12px;' : 'display: none; font-size: 12.5px; font-weight: 600; color: rgb(255, 255, 255); background: var(--sh-blue); border-radius: 999px; padding: 6px 12px;') ?>"><?= esc_html($r1['eyebrow'] ?? '') ?></span><?php endif; ?>
            </div>
            <?php if (!empty($r1['heading'])) : ?><h3 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 20px; margin: 0px; color: var(--sh-ink);"><?= esc_html($r1['heading'] ?? '') ?></h3><?php endif; ?>
            <?php if (!empty($r1['text'])) : ?><p style="font-size: 15.5px; line-height: 1.75; color: var(--sh-text); margin: 0px;"><?= esc_html($r1['text'] ?? '') ?></p><?php endif; ?>
            <div aria-hidden="true" data-svc-viz style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap; padding: 10px 12px; border-radius: 12px;">
              
                <?php $r2_list = $r1['items'] ?? []; $i2_n = is_array($r2_list) ? count($r2_list) : 0; foreach ((array) $r2_list as $i2 => $r2) : ?><span data-svc-step style="display: inline-flex; align-items: center; gap: 6px; font-size: 12.5px; font-weight: 600; color: var(--sh-ink);"><span data-svc-dot style="width: 6px; height: 6px; border-radius: 999px;"></span><?= esc_html($r2['eyebrow'] ?? '') ?></span><?php endforeach; ?>
              
            </div>
            <?php if (!empty($r1['label'])) : ?><span data-lcard-more style="margin-top: auto; padding-top: 8px; font-size: 14.5px;"><?= sh_more_label($r1['label'] ?? '') ?></span><?php endif; ?>
          </a><?php endif; ?><?php endforeach; ?>
        
      </div>
    </div>
  </section>
