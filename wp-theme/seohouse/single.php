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
  <div class="h-dots"></div>
  <div class="h-g1"></div>
  <div class="h-g2"></div>
  <div class="wrap">
    <div class="page-hero-inner">
      <div class="breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">الرئيسية</a>
        <svg class="bc-sep" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        <a href="<?php echo esc_url( sh_page_url( 'blog' ) ); ?>">المدونة</a>
        <?php if ( $cat_name ) : ?>
        <svg class="bc-sep" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        <span style="color:rgba(255,255,255,.6)"><?php echo esc_html( $cat_name ); ?></span>
        <?php endif; ?>
      </div>
      <?php if ( $cat_name ) : ?>
        <span class="tag d" style="margin-bottom:18px"><?php echo esc_html( $cat_name ); ?></span>
      <?php endif; ?>
      <h1 class="page-h1"><?php the_title(); ?></h1>
      <p class="page-hero-p" style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
        <span><?php echo esc_html( get_the_date() ); ?></span>
        <span style="width:3px;height:3px;border-radius:50%;background:rgba(255,255,255,.3)"></span>
        <span><?php echo esc_html( $read_time ); ?> دق قراءة</span>
        <span style="width:3px;height:3px;border-radius:50%;background:rgba(255,255,255,.3)"></span>
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
        <div style="margin-top:32px">
          <h3 style="font-size:16px;font-weight:800;color:var(--ink);margin-bottom:18px">مقالات ذات صلة</h3>
          <div class="art-grid" style="grid-template-columns:1fr 1fr">
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

        <div class="sb-box" style="background:var(--blue);border-color:var(--blue)">
          <div style="font-size:13px;font-weight:800;color:#fff;margin-bottom:10px">استشارة مجانية</div>
          <p style="font-size:12.5px;color:rgba(255,255,255,.65);line-height:1.72;margin-bottom:16px">هل تريد تحسين ترتيب موقعك؟ احجز 30 دقيقة معنا مجاناً.</p>
          <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-w sm" style="width:100%;justify-content:center">احجز الآن</a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
