<?php
/**
 * Template part: blog article card
 */
$delay_class = isset( $args['delay'] ) ? ' ' . $args['delay'] : '';
$categories  = get_the_category();
$cat_name    = ! empty( $categories ) ? $categories[0]->name : '';
$read_time   = ceil( str_word_count( strip_tags( get_the_content() ) ) / 200 );
?>
<article class="art-card sr<?php echo esc_attr( $delay_class ); ?>">
  <a href="<?php the_permalink(); ?>">
    <div class="art-thumb">
      <?php if ( has_post_thumbnail() ) : ?>
        <?php the_post_thumbnail( 'seohouse-blog-thumb' ); ?>
      <?php endif; ?>
      <?php if ( $cat_name ) : ?>
        <span class="art-cat"><?php echo esc_html( $cat_name ); ?></span>
      <?php endif; ?>
    </div>
    <div class="art-body">
      <div class="art-meta">
        <span><?php echo get_the_date( 'F Y' ); ?></span>
        <span class="d"></span>
        <span><?php echo (int) $read_time; ?> دق قراءة</span>
      </div>
      <h3><?php the_title(); ?></h3>
      <p><?php echo esc_html( get_the_excerpt() ); ?></p>
      <span class="art-read">اقرأ المقال
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </span>
    </div>
  </a>
</article>
