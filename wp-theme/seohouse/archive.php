<?php
/**
 * General archive template (categories, tags, dates)
 */
get_header();

if ( is_category() ) {
    $archive_title = 'تصنيف: ' . single_cat_title( '', false );
    $archive_desc  = category_description();
} elseif ( is_tag() ) {
    $archive_title = 'الوسم: ' . single_tag_title( '', false );
    $archive_desc  = '';
} elseif ( is_date() ) {
    $archive_title = 'أرشيف: ' . get_the_date( 'F Y' );
    $archive_desc  = '';
} else {
    $archive_title = get_the_archive_title();
    $archive_desc  = get_the_archive_description();
}
?>

<?php
get_template_part( 'template-parts/layout/page-hero', null, [
    'tag'        => 'أرشيف',
    'title'      => $archive_title,
    'description'=> wp_strip_all_tags( $archive_desc ),
    'breadcrumb' => [ 'المدونة' => sh_page_url( 'blog' ), $archive_title => '' ],
] );
?>

<section class="sec sec-surface">
  <div class="wrap">
    <div class="art-grid">
      <?php if ( have_posts() ) :
          $first = true;
          while ( have_posts() ) : the_post();
              if ( $first ) : ?>
                <div class="art-card art-feat sr">
                  <div class="art-thumb feat-h">
                    <?php if ( has_post_thumbnail() ) the_post_thumbnail( 'seohouse-blog-thumb' ); ?>
                    <?php $cats = get_the_category(); if ( $cats ) : ?><span class="art-cat"><?php echo esc_html( $cats[0]->name ); ?></span><?php endif; ?>
                  </div>
                  <div class="art-body">
                    <div class="art-meta"><span><?php echo esc_html( get_the_date() ); ?></span></div>
                    <h2><?php the_title(); ?></h2>
                    <p><?php echo esc_html( get_the_excerpt() ); ?></p>
                    <a href="<?php the_permalink(); ?>" class="art-read">اقرأ المقال <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
                  </div>
                </div>
              <?php $first = false;
              else :
                  get_template_part( 'template-parts/cards/article-card' );
              endif;
          endwhile;
      else : ?>
        <p class="empty-state">لا توجد مقالات في هذا الأرشيف.</p>
      <?php endif; ?>
    </div>

    <?php if ( $GLOBALS['wp_query']->max_num_pages > 1 ) : ?>
    <div class="pager sr">
      <?php echo paginate_links( [ 'prev_text' => '←', 'next_text' => '→' ] ); ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
