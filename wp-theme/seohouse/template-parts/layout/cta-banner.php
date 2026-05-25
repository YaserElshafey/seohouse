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
<section class="sec" style="background:var(--blue);position:relative;overflow:hidden;text-align:center">
  <div style="position:absolute;border-radius:50%;border:1px solid rgba(255,255,255,.07);top:50%;left:50%;transform:translate(-50%,-50%);width:420px;height:420px;pointer-events:none"></div>
  <div style="position:absolute;border-radius:50%;border:1px solid rgba(255,255,255,.04);top:50%;left:50%;transform:translate(-50%,-50%);width:650px;height:650px;pointer-events:none"></div>
  <div style="position:absolute;border-radius:50%;border:1px solid rgba(255,255,255,.025);top:50%;left:50%;transform:translate(-50%,-50%);width:880px;height:880px;pointer-events:none"></div>
  <div class="wrap">
    <div style="position:relative;z-index:1;max-width:520px;margin-inline:auto" class="sr">
      <?php if ( $tag ) : ?>
        <span class="tag d" style="justify-content:center;margin-bottom:11px"><?php echo esc_html( $tag ); ?></span>
      <?php endif; ?>
      <h2 class="h2 wh" style="margin-bottom:11px"><?php echo esc_html( $title ); ?></h2>
      <p style="font-size:clamp(14px,1.4vw,16px);color:rgba(255,255,255,.55);line-height:1.88;margin-bottom:28px"><?php echo esc_html( $description ); ?></p>
      <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap">
        <?php foreach ( $buttons as $btn ) : ?>
          <a href="<?php echo esc_url( $btn['url'] ?? '#' ); ?>" class="btn <?php echo esc_attr( $btn['class'] ?? 'btn-w lg' ); ?>">
            <?php echo esc_html( $btn['text'] ); ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
