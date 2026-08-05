<?php
/**
 * Single blog post template
 */
get_header();

while ( have_posts() ) : the_post();
    $cat     = get_the_category();
    $cat_name = ! empty( $cat ) ? $cat[0]->name : '';
    $cat_url  = ! empty( $cat ) ? get_category_link( $cat[0]->term_id ) : '';
    $words   = str_word_count( wp_strip_all_tags( get_the_content() ) );
    $read_time = max( 1, round( $words / 200 ) );
?>

<section class="page-hero">
  <div class="wrap">
    <div class="page-hero-inner">
      <div class="breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">الرئيسية</a>
        <svg class="bc-sep" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        <a href="<?php echo esc_url( sh_page_url( 'blog' ) ); ?>">المدونة</a>
        <?php if ( $cat_name ) : ?>
        <svg class="bc-sep" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        <span class="bc-current"><?php echo esc_html( $cat_name ); ?></span>
        <?php endif; ?>
      </div>
      <?php if ( $cat_name ) : ?>
        <span class="tag d"><?php echo esc_html( $cat_name ); ?></span>
      <?php endif; ?>
      <h1 class="page-h1"><?php the_title(); ?></h1>
      <p class="page-hero-p post-meta-row">
        <span><?php echo esc_html( get_the_date() ); ?></span>
        <span class="meta-dot"></span>
        <span><?php echo esc_html( $read_time ); ?> دق قراءة</span>
        <span class="meta-dot"></span>
        <span><?php the_author(); ?></span>
      </p>
    </div>
  </div>
</section>

<section class="sec sec-surface">
  <div class="wrap">
    <div class="post-layout">
      <div>
        <div class="post-body">
          <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail( 'seohouse-blog-thumb', [ 'class' => 'post-cover' ] ); ?>
          <?php endif; ?>
          <div class="post-content">
            <?php the_content(); ?>
          </div>
          <?php
          $tags = get_the_tags();
          if ( $tags ) :
          ?>
          <div class="post-tags">
            <?php foreach ( $tags as $tag ) : ?>
              <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="chip"><?php echo esc_html( $tag->name ); ?></a>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>

        <!-- Related posts -->
        <?php
        $related = new WP_Query( [
            'post_type'      => 'post',
            'posts_per_page' => 2,
            'post__not_in'   => [ get_the_ID() ],
            'category__in'   => wp_get_post_categories( get_the_ID() ),
            'orderby'        => 'rand',
        ] );
        if ( $related->have_posts() ) :
        ?>
        <div class="related-posts">
          <h3 class="related-posts-title">مقالات ذات صلة</h3>
          <div class="art-grid-related">
            <?php while ( $related->have_posts() ) : $related->the_post(); ?>
              <?php get_template_part( 'template-parts/cards/article-card' ); ?>
            <?php endwhile; wp_reset_postdata(); ?>
          </div>
        </div>
        <?php endif; ?>
      </div>

      <!-- Sidebar -->
      <div class="sidebar">
        <div class="sb-box">
          <div class="sb-title">التصنيفات</div>
          <div class="cat-list">
            <?php
            $categories = get_categories( [ 'hide_empty' => true ] );
            foreach ( $categories as $category ) :
            ?>
              <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="cat-item">
                <?php echo esc_html( $category->name ); ?>
                <span class="cat-count"><?php echo esc_html( $category->count ); ?></span>
              </a>
            <?php endforeach; ?>
          </div>
        </div>

        <?php $blog_pid = (int) get_option( 'page_for_posts' ) ?: null; ?>
        <div class="sb-box sb-cta">
          <div class="sb-cta-title"><?php echo esc_html( sh_field( 'blog_sidebar_cta_title', $blog_pid, 'استشارة مجانية' ) ); ?></div>
          <p class="sb-cta-desc"><?php echo esc_html( sh_field( 'blog_sidebar_cta_desc', $blog_pid, 'هل تريد تحسين ترتيب موقعك؟ احجز 30 دقيقة معنا مجاناً.' ) ); ?></p>
          <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-w sm"><?php echo esc_html( sh_field( 'blog_sidebar_cta_btn', $blog_pid, 'احجز الآن' ) ); ?></a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
