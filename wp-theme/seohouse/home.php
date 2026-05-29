<?php
/**
 * Blog archive template (used when a page is set as Posts Page)
 */
get_header();

$_blog_pid = (int) get_option( 'page_for_posts' ) ?: null;

$_blog_hero_raw   = sh_field( 'blog_hero_title', $_blog_pid, "رؤى وأدوات\nتبني موقعك" );
$_blog_hero_em    = sh_field( 'blog_hero_em',    $_blog_pid, 'تبني موقعك' );
$_blog_hero_title = $_blog_hero_em
    ? str_replace( $_blog_hero_em, '<em>' . esc_html( $_blog_hero_em ) . '</em>', esc_html( $_blog_hero_raw ) )
    : esc_html( $_blog_hero_raw );
$_blog_hero_title = nl2br( $_blog_hero_title );

$_blog_sb_title = sh_field( 'blog_sidebar_cta_title', $_blog_pid, 'استشارة مجانية' );
$_blog_sb_desc  = sh_field( 'blog_sidebar_cta_desc',  $_blog_pid, 'هل تريد تحسين ترتيب موقعك؟ احجز 30 دقيقة معنا مجاناً.' );
$_blog_sb_btn   = sh_field( 'blog_sidebar_cta_btn',   $_blog_pid, 'احجز الآن' );
?>

<?php
get_template_part( 'template-parts/layout/page-hero', null, [
    'tag'         => sh_field( 'blog_hero_tag',  $_blog_pid, 'المدونة' ),
    'title'       => $_blog_hero_title,
    'description' => sh_field( 'blog_hero_desc', $_blog_pid, 'مقالات متخصصة في السيو والتجارة الإلكترونية من فريق سيو هاوس — مكتوبة للتطبيق، لا للقراءة فقط.' ),
    'breadcrumb'  => [ 'المدونة' => '' ],
] );
?>

<section class="sec sec-surface">
  <div class="wrap">
    <div class="blog-layout">
      <div>
        <div class="art-grid">
          <?php
          $first = true;
          while ( have_posts() ) : the_post();
              if ( $first ) : ?>
                <div class="art-card art-feat sr">
                  <div class="art-thumb feat-h">
                    <?php if ( has_post_thumbnail() ) the_post_thumbnail( 'seohouse-blog-thumb', [ 'style' => 'position:absolute;inset:0;width:100%;height:100%;object-fit:cover' ] ); ?>
                    <?php $cats = get_the_category(); if ( $cats ) : ?><span class="art-cat"><?php echo esc_html( $cats[0]->name ); ?></span><?php endif; ?>
                  </div>
                  <div class="art-body">
                    <div class="art-meta">
                      <span><?php echo esc_html( get_the_date() ); ?></span>
                      <span class="d"></span>
                      <span><?php echo esc_html( max( 1, round( str_word_count( wp_strip_all_tags( get_the_content() ) ) / 200 ) ) ); ?> دق قراءة</span>
                    </div>
                    <h2><?php the_title(); ?></h2>
                    <p><?php echo esc_html( get_the_excerpt() ); ?></p>
                    <a href="<?php the_permalink(); ?>" class="art-read">اقرأ المقال كاملاً <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
                  </div>
                </div>
              <?php $first = false;
              else :
                  get_template_part( 'template-parts/cards/article-card' );
              endif;
          endwhile;
          ?>
        </div>

        <!-- Pagination -->
        <?php if ( $GLOBALS['wp_query']->max_num_pages > 1 ) : ?>
        <div class="pager sr">
          <?php
          echo paginate_links( [
              'prev_text' => '→',
              'next_text' => '←',
              'type'      => 'list',
              'before_page_number' => '<span class="pg">',
              'after_page_number'  => '</span>',
          ] );
          ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- Sidebar -->
      <div class="sidebar">
        <div class="sb-box sr">
          <div class="sb-title">التصنيفات</div>
          <div class="cat-list">
            <?php
            $categories = get_categories( [ 'hide_empty' => true ] );
            foreach ( $categories as $cat ) :
            ?>
              <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="cat-item">
                <?php echo esc_html( $cat->name ); ?>
                <span class="cat-count"><?php echo esc_html( $cat->count ); ?></span>
              </a>
            <?php endforeach; ?>
          </div>
        </div>

        <?php
        $popular = new WP_Query( [
            'post_type'      => 'post',
            'posts_per_page' => 3,
            'orderby'        => 'comment_count',
        ] );
        if ( $popular->have_posts() ) :
        ?>
        <div class="sb-box sr d1">
          <div class="sb-title">المقالات الأكثر قراءة</div>
          <div class="pop-list">
            <?php while ( $popular->have_posts() ) : $popular->the_post(); ?>
              <a href="<?php the_permalink(); ?>" class="pop-item">
                <div class="pop-thumb">
                  <?php if ( has_post_thumbnail() ) the_post_thumbnail( 'thumbnail', [ 'style' => 'width:100%;height:100%;object-fit:cover' ] ); ?>
                </div>
                <div>
                  <div class="pop-t"><?php the_title(); ?></div>
                  <div class="pop-d"><?php echo esc_html( get_the_date() ); ?></div>
                </div>
              </a>
            <?php endwhile; wp_reset_postdata(); ?>
          </div>
        </div>
        <?php endif; ?>

        <div class="sb-box sr d2" style="background:var(--blue);border-color:var(--blue)">
          <div style="font-size:13px;font-weight:800;color:#fff;margin-bottom:10px"><?php echo esc_html( $_blog_sb_title ); ?></div>
          <p style="font-size:12.5px;color:rgba(255,255,255,.65);line-height:1.72;margin-bottom:16px"><?php echo esc_html( $_blog_sb_desc ); ?></p>
          <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-w sm" style="width:100%;justify-content:center"><?php echo esc_html( $_blog_sb_btn ); ?></a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
