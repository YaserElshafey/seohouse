<?php
/**
 * Template part: inner page hero section
 *
 * Expected $args:
 *   tag         — small label above title (string)
 *   title       — main heading (string, may include <em> for gradient word)
 *   description — subtext (string)
 *   breadcrumb  — array of [ 'Label' => 'url' ] (last item url = '' for current)
 *   buttons     — array of [ 'text' => '', 'url' => '', 'class' => 'btn-p' ]
 */
$tag         = $args['tag'] ?? '';
$title       = $args['title'] ?? get_the_title();
$description = $args['description'] ?? '';
$breadcrumb  = $args['breadcrumb'] ?? [];
$buttons     = $args['buttons'] ?? [];
?>
<section class="page-hero">
  <div class="h-dots"></div>
  <div class="h-g1" style="position:absolute;inset-inline-start:-150px;bottom:-80px;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(30,46,245,.2),transparent 65%);filter:blur(60px);pointer-events:none"></div>
  <div class="h-g2" style="position:absolute;inset-inline-end:-80px;top:0;width:380px;height:380px;border-radius:50%;background:radial-gradient(circle,rgba(30,46,245,.1),transparent 65%);filter:blur(65px);pointer-events:none"></div>
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
              <span style="color:rgba(255,255,255,.6)"><?php echo esc_html( $label ); ?></span>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if ( $tag ) : ?>
        <span class="tag d" style="margin-bottom:18px"><?php echo esc_html( $tag ); ?></span>
      <?php endif; ?>

      <h1 class="page-h1"><?php echo wp_kses_post( $title ); ?></h1>

      <?php if ( $description ) : ?>
        <p class="page-hero-p"><?php echo esc_html( $description ); ?></p>
      <?php endif; ?>

      <?php if ( ! empty( $buttons ) ) : ?>
        <div class="page-hero-btns">
          <?php foreach ( $buttons as $btn ) : ?>
            <a href="<?php echo esc_url( $btn['url'] ?? '#' ); ?>" class="btn <?php echo esc_attr( $btn['class'] ?? 'btn-p' ); ?>">
              <?php echo esc_html( $btn['text'] ); ?>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
