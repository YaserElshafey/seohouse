<?php
/**
 * Template part: clients trust bar — infinite marquee
 */
$clients = sh_get_clients();

// Collect logo items
$cl_items = [];
if ( $clients->have_posts() ) {
    while ( $clients->have_posts() ) {
        $clients->the_post();
        $logo    = sh_field( 'client_logo' );
        $url     = sh_field( 'client_url' );
        $cl_items[] = [
            'title'   => get_the_title(),
            'img_url' => ! empty( $logo['url'] ) ? $logo['url'] : '',
            'url'     => $url,
        ];
    }
    wp_reset_postdata();
}

// Fallback placeholder slots
if ( empty( $cl_items ) ) {
    for ( $i = 0; $i < 6; $i++ ) {
        $cl_items[] = [ 'title' => 'شعار العميل', 'img_url' => '', 'url' => '' ];
    }
}

// Duplicate for seamless loop
$display_items = array_merge( $cl_items, $cl_items );
?>
<section id="clients">
  <div class="wrap">
    <p class="cl-lbl">عملاء يثقون بنا</p>
  </div>
  <div class="cl-marquee-outer">
    <div class="cl-marquee-track">
      <?php foreach ( $display_items as $item ) :
          $tag         = $item['url'] ? 'a' : 'div';
          $attrs       = $item['url'] ? 'href="' . esc_url( $item['url'] ) . '" target="_blank" rel="noopener"' : '';
          $extra_class = $item['img_url'] ? '' : ' cl-ph-txt';
      ?>
        <<?php echo $tag; ?> <?php echo $attrs; ?> class="cl-item<?php echo $extra_class; ?>" title="<?php echo esc_attr( $item['title'] ); ?>">
          <?php if ( $item['img_url'] ) : ?>
            <img src="<?php echo esc_url( $item['img_url'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>">
          <?php else : ?>
            <?php echo esc_html( $item['title'] ); ?>
          <?php endif; ?>
        </<?php echo $tag; ?>>
      <?php endforeach; ?>
    </div>
  </div>
</section>
