<?php
/**
 * Template part: full-width CTA banner (blue background, concentric circles)
 *
 * $args:
 *   tag         — small tag text
 *   title       — heading
 *   description — body text
 *   buttons     — array of [ 'text', 'url', 'class' ]
 */
$tag         = $args['tag']         ?? sh_option( 'global_cta_title', 'ابدأ الآن' );
$title       = $args['title']       ?? sh_option( 'global_cta_title', 'موقعك يستحق أن يُرى' );
$description = $args['description'] ?? sh_option( 'global_cta_desc', 'خصص 30 دقيقة معنا — وسنخبرك بصدق أين أنت وأين يمكن أن تكون.' );
$buttons     = $args['buttons']     ?? [
    [ 'text' => 'احجز استشارة مجانية', 'url' => sh_page_url( 'contact' ), 'class' => 'btn-w lg' ],
    [ 'text' => 'تعرّف على السيو',     'url' => sh_page_url( 'services/seo' ), 'class' => 'btn-g lg' ],
];
?>
<section class="sec cta-banner">
  <div class="cta-ring-lg" aria-hidden="true"></div>
  <div class="wrap">
    <div class="cta-content sr">
      <?php if ( $tag ) : ?>
        <span class="tag d"><?php echo esc_html( $tag ); ?></span>
      <?php endif; ?>
      <h2 class="h2 wh"><?php echo esc_html( $title ); ?></h2>
      <p class="cta-desc"><?php echo esc_html( $description ); ?></p>
      <div class="cta-btns">
        <?php foreach ( $buttons as $btn ) : ?>
          <a href="<?php echo esc_url( $btn['url'] ?? '#' ); ?>" class="btn <?php echo esc_attr( $btn['class'] ?? 'btn-w lg' ); ?>">
            <?php echo esc_html( $btn['text'] ); ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
