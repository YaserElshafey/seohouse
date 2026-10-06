<?php
/**
 * Section "Platforms" — SEO House - Homepage.
 * Generated from the approved design by tools/design-import/convert.js; maintained by hand since 2.7.1:
 * logos only (the name stays as the link's accessible name), each logo links to the platform —
 * the link from «إعدادات سيو هاوس ← المنصات والأدوات», else the platform's official site.
 * @sh-manual
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f = $args['f'] ?? array();
?>
<section data-screen-label="Platforms" style="position: relative; overflow: hidden; background: var(--sh-surface); color: var(--sh-ink);">
    <div aria-hidden="true" style="position: absolute; inset: 0px; opacity: 0.5; background-image: radial-gradient(rgba(40, 84, 232, 0.05) 1px, transparent 1px); background-size: 24px 24px; mask-image: radial-gradient(70% 80%, rgb(0, 0, 0), transparent 80%); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1200px; margin: 0px auto; padding: clamp(34px, 4vw, 56px) 20px;">
      <div style="text-align: center;">
        <?php if (!empty($f['eyebrow'])) : ?><div style="font-size: 13px; font-weight: 600; color: var(--sh-link);"><?= esc_html($f['eyebrow'] ?? '') ?></div><?php endif; ?>
        <?php if (!empty($f['title'])) : ?><h2 data-h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); margin: 10px 0px 0px; line-height: 1.3;"><?= esc_html($f['title'] ?? '') ?></h2><?php endif; ?>
      </div>
      <div style="margin-top: 28px;">
        <?php $r1_list = $f['items'] ?? []; $i1_n = is_array($r1_list) ? count($r1_list) : 0; foreach ((array) $r1_list as $i1 => $r1) : ?><div data-plat-row>
          <div data-plat-track>
            
              <?php foreach ( array( false, true ) as $dup2 ) : ?><?php $r2_list = $r1['items'] ?? []; $i2_n = is_array($r2_list) ? count($r2_list) : 0; foreach ((array) $r2_list as $i2 => $r2) : ?><div data-plat aria-hidden="<?= $dup2 ? 'true' : 'false' ?>">
                <?= sh_platform_logo_link( (string) ( $r2['logo'] ?? '' ), (int) ( $r2['logo_image'] ?? 0 ), (string) ( $r2['label'] ?? '' ), $dup2 ) ?>
              </div><?php endforeach; ?><?php endforeach; ?>
            
          </div>
        </div><?php endforeach; ?>
      </div>
    </div>
  </section>
