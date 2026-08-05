<?php
/**
 * Template part: inner page hero section
 *
 * Expected $args:
 *   tag            — small eyebrow label above title (string)
 *   badge_html     — raw HTML for a custom badge (overrides tag; used for .h-badge/.acc-badge/.sec-badge)
 *   title          — main heading (string, may include <em> for gradient word)
 *   description    — subtext paragraph (string)
 *   breadcrumb     — array of [ 'Label' => 'url' ] (last item url = '' for current page)
 *   buttons        — array of [ 'text' => '', 'url' => '', 'class' => 'btn-p' ]
 *   after_cta_html — raw HTML rendered after buttons (e.g. .h-stats or .plat-badges)
 */
$tag            = $args['tag']            ?? '';
$badge_html     = $args['badge_html']     ?? '';
$title          = $args['title']          ?? get_the_title();
$description    = $args['description']    ?? '';
$breadcrumb     = $args['breadcrumb']     ?? [];
$buttons        = $args['buttons']        ?? [];
$after_cta_html = $args['after_cta_html'] ?? '';
?>
<section class="page-hero">
  <div class="wrap">
    <div class="page-hero-inner">

      <?php if ( ! empty( $breadcrumb ) ) : ?>
        <div class="breadcrumb">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>">الرئيسية</a>
          <?php foreach ( $breadcrumb as $label => $url ) : ?>
            <svg class="bc-sep" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
            <?php if ( $url ) : ?>
              <a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $label ); ?></a>
            <?php else : ?>
              <span class="bc-current"><?php echo esc_html( $label ); ?></span>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if ( $badge_html ) : ?>
        <?php echo $badge_html; // phpcs:ignore WordPress.Security.EscapeOutput ?>
      <?php elseif ( $tag ) : ?>
        <span class="tag d"><?php echo esc_html( $tag ); ?></span>
      <?php endif; ?>

      <h1 class="page-h1"><?php echo wp_kses_post( $title ); ?></h1>

      <?php if ( $description ) : ?>
        <p class="page-hero-p"><?php echo esc_html( $description ); ?></p>
      <?php endif; ?>

      <?php if ( ! empty( $buttons ) ) : ?>
        <div class="page-hero-btns">
          <?php foreach ( $buttons as $btn ) : ?>
            <a href="<?php echo esc_url( $btn['url'] ?? '#' ); ?>" class="btn <?php echo esc_attr( $btn['class'] ?? 'btn-p' ); ?>">
              <?php echo wp_kses_post( $btn['text'] ); ?>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if ( $after_cta_html ) : ?>
        <?php echo $after_cta_html; // phpcs:ignore WordPress.Security.EscapeOutput ?>
      <?php endif; ?>

    </div>
  </div>
</section>
