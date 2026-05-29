<?php
/**
 * Template part: clients trust bar
 */
$clients = sh_get_clients();
?>
<section id="clients">
  <div class="wrap">
    <p class="cl-lbl">عملاء يثقون بنا</p>
    <div class="cl-row">
      <?php if ( $clients->have_posts() ) :
          while ( $clients->have_posts() ) : $clients->the_post();
              $logo    = sh_field( 'client_logo' );
              $url     = sh_field( 'client_url' );
              $img_url = ! empty( $logo['url'] ) ? $logo['url'] : '';
              $tag         = $url ? 'a' : 'div';
              $attrs       = $url ? 'href="' . esc_url( $url ) . '" target="_blank" rel="noopener"' : '';
              $extra_class = $img_url ? '' : ' cl-ph-txt';
              ?>
              <<?php echo $tag; ?> <?php echo $attrs; ?> class="cl-item<?php echo $extra_class; ?>" title="<?php the_title_attribute(); ?>">
                <?php if ( $img_url ) : ?>
                  <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php the_title_attribute(); ?>">
                <?php else : ?>
                  <?php the_title(); ?>
                <?php endif; ?>
              </<?php echo $tag; ?>>
          <?php endwhile;
          wp_reset_postdata();
      else :
          // Placeholder slots when no clients are added yet
          for ( $i = 0; $i < 6; $i++ ) :
              echo '<div class="cl-item cl-ph-txt">شعار العميل</div>';
          endfor;
      endif; ?>
    </div>
  </div>
</section>
