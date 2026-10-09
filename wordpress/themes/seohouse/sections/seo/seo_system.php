<?php
/**
 * Section "SEO system" — SEO House - SEO Service Page.
 * Generated from the approved design by tools/design-import/convert.js.
 * To maintain this file by hand add the tag sh-manual (prefixed with @) as its own line here.
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section id="<?= esc_attr(sh_anchor($f, 'seo-system')) ?>" data-screen-label="SEO system" style="scroll-margin-top: 88px; background: var(--sh-bg); color: var(--sh-ink);">
    <div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
      <div data-sh-center>
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); margin: 12px 0px 0px; line-height: 1.3;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
        <?php if (!empty($f['text'])) : ?><p style="max-width: 44em; font-size: 17px; color: var(--sh-text); margin: 16px 0px 0px; text-wrap: pretty;"><?= esc_html($f['text'] ?? '') ?></p><?php endif; ?>
      </div>

      <div data-eco style="margin-top: clamp(28px, 3.4vw, 46px);">
        
          <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><?php $vt_bfd79dcb = ['v1' => ['grid-area: a; position: relative; display: flex; flex-direction: column; gap: 8px; padding: clamp(18px, 2vw, 24px); border-radius: 6px 18px 18px; background: rgba(37, 43, 51, 0.04); border: 1px solid var(--sh-line); color: var(--sh-ink); transition: background 0.3s, transform 0.3s;'], 'v2' => ['grid-area: b; position: relative; display: flex; flex-direction: column; gap: 8px; padding: clamp(18px, 2vw, 24px); border-radius: 6px 18px 18px; background: rgba(37, 43, 51, 0.04); border: 1px solid var(--sh-line); color: var(--sh-ink); transition: background 0.3s, transform 0.3s;'], 'v3' => ['grid-area: c; position: relative; display: flex; flex-direction: column; gap: 8px; padding: clamp(18px, 2vw, 24px); border-radius: 6px 18px 18px; background: rgba(37, 43, 51, 0.04); border: 1px solid var(--sh-line); color: var(--sh-ink); transition: background 0.3s, transform 0.3s;'], 'v4' => ['grid-area: d; position: relative; display: flex; flex-direction: column; gap: 8px; padding: clamp(18px, 2vw, 24px); border-radius: 6px 18px 18px; background: rgba(37, 43, 51, 0.04); border: 1px solid var(--sh-line); color: var(--sh-ink); transition: background 0.3s, transform 0.3s;'], 'v5' => ['grid-area: e; position: relative; display: flex; flex-direction: column; gap: 8px; padding: clamp(18px, 2vw, 24px); border-radius: 6px 18px 18px; background: rgba(37, 43, 51, 0.04); border: 1px solid var(--sh-line); color: var(--sh-ink); transition: background 0.3s, transform 0.3s;']]; $vk_bfd79dcb = $vt_bfd79dcb[$r1['variant'] ?? 'v1'] ?? $vt_bfd79dcb['v1']; ?><?php if (!empty(sh_link($r1['link'] ?? ''))) : ?><a data-hcard href="<?= esc_url(sh_link($r1['link'] ?? '')) ?>" data-eco-item class="hv-9f987a" style="<?= esc_attr($vk_bfd79dcb[0] ?? '') ?>">
            <div style="display: flex; align-items: center; gap: 12px;">
              <span aria-hidden="true" style="flex: 0 0 auto; width: 38px; height: 38px; border-radius: 11px; background: rgba(171, 182, 194, 0.14); color: rgb(40, 84, 232); display: flex; align-items: center; justify-content: center;">
                <?= sh_icon($r1['icon'] ?? 'i5a243655') ?>
              </span>
              <?php if (!empty($r1['heading'])) : ?><h3 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18.5px; margin: 0px; color: var(--sh-ink);"><?= esc_html($r1['heading'] ?? '') ?></h3><?php endif; ?>
            </div>
            <?php if (!empty($r1['text'])) : ?><p style="font-size: 15px; color: var(--sh-text); margin: 0px;"><?= esc_html($r1['text'] ?? '') ?></p><?php endif; ?>
            <?php if (!empty($r1['eyebrow'])) : ?><span style="font-size: 14px; font-weight: 600; color: var(--sh-link); margin-top: auto;"><?= esc_html($r1['eyebrow'] ?? '') ?></span><?php endif; ?>
          </a><?php endif; ?><?php endforeach; ?>
        

        <div style="grid-area: hub; position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; gap: 10px; padding: clamp(20px, 2.4vw, 30px); border-radius: 20px; background: var(--sh-blue); min-height: 150px;">
          <span aria-hidden="true" style="width: 30px; height: 2px; background: rgba(255, 255, 255, 0.75); border-radius: 2px;"></span>
          <?php if (!empty($f['heading'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: clamp(17px, 1.7vw, 21px); line-height: 1.4; color: rgb(255, 255, 255);"><?= esc_html($f['heading'] ?? '') ?></div><?php endif; ?>
        </div>
      </div>

      <?php if (!empty(sh_link($f['link'] ?? ''))) : ?><a data-hcard href="<?= esc_url(sh_link($f['link'] ?? '')) ?>" class="hv-5291c6" style="margin-top: clamp(24px, 2.6vw, 34px); display: flex; flex-wrap: wrap; align-items: center; gap: 14px 26px; padding: clamp(20px, 2.2vw, 28px); border-radius: 18px; background: linear-gradient(115deg, rgba(40, 84, 232, 0.1), rgba(40, 84, 232, 0.14)); color: var(--sh-ink); transition: transform 0.3s;">
        <div style="flex: 1 1 380px; min-width: 0px;">
          <?php if (!empty($f['heading_2'])) : ?><div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 20px;"><?= esc_html($f['heading_2'] ?? '') ?></div><?php endif; ?>
          <?php if (!empty($f['text_2'])) : ?><p style="font-size: 15.5px; color: var(--sh-ink); margin: 8px 0px 0px;"><?= esc_html($f['text_2'] ?? '') ?></p><?php endif; ?>
        </div>
        <?php if (!empty($f['text_3'])) : ?><span style="font-weight: 600; color: var(--sh-link); font-size: 15px;"><?= esc_html($f['text_3'] ?? '') ?></span><?php endif; ?>
      </a><?php endif; ?>
    </div>
  </section>
