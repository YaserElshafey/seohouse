<?php
/**
 * Search results template
 *
 * Displays results for WordPress site-wide search queries.
 * Uses the shared page-hero, blog-layout, article-card, sidebar,
 * and pagination patterns consistent with home.php and archive.php.
 */
get_header();

$_blog_pid = (int) get_option( 'page_for_posts' ) ?: null;
$_sb_title = sh_field( 'blog_sidebar_cta_title', $_blog_pid, 'استشارة مجانية' );
$_sb_desc  = sh_field( 'blog_sidebar_cta_desc',  $_blog_pid, 'هل تريد تحسين ترتيب موقعك؟ احجز 30 دقيقة معنا مجاناً.' );
$_sb_btn   = sh_field( 'blog_sidebar_cta_btn',   $_blog_pid, 'احجز الآن' );

$query       = get_search_query(); // Sanitized by WordPress core
$found_posts = (int) $GLOBALS['wp_query']->found_posts;

// Hero title: highlight the query term with the brand gradient animation
$hero_title = $query
    ? 'نتائج البحث عن: <em class="grad-em">' . esc_html( $query ) . '</em>'
    : 'نتائج البحث';

// Hero description: result count when there are hits, generic prompt otherwise
$hero_desc = $found_posts > 0
    ? 'وجدنا ' . number_format_i18n( $found_posts ) . ( $found_posts === 1 ? ' نتيجة' : ' نتيجة' )
    : '';

get_template_part( 'template-parts/layout/page-hero', null, [
    'tag'        => 'بحث',
    'title'      => $hero_title,
    'description'=> $hero_desc,
    'breadcrumb' => [ 'نتائج البحث' => '' ],
] );
?>

<section class="sec sec-surface">
  <div class="wrap">
    <div class="blog-layout">

      <!-- ─ Results column ─ -->
      <div>

        <?php if ( have_posts() ) : ?>

          <div class="art-grid">
            <?php
            $first = true;
            while ( have_posts() ) : the_post();
                if ( $first ) : ?>
                  <div class="art-card art-feat sr">
                    <div class="art-thumb feat-h">
                      <?php if ( has_post_thumbnail() ) the_post_thumbnail( 'seohouse-blog-thumb' ); ?>
                      <?php
                      $cats = get_the_category();
                      if ( $cats ) : ?>
                        <span class="art-cat"><?php echo esc_html( $cats[0]->name ); ?></span>
                      <?php endif; ?>
                    </div>
                    <div class="art-body">
                      <div class="art-meta">
                        <span><?php echo esc_html( get_the_date() ); ?></span>
                      </div>
                      <h2><?php the_title(); ?></h2>
                      <p><?php echo esc_html( get_the_excerpt() ); ?></p>
                      <a href="<?php the_permalink(); ?>" class="art-read">
                        اقرأ المزيد
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                      </a>
                    </div>
                  </div>
                <?php
                $first = false;
                else :
                    get_template_part( 'template-parts/cards/article-card' );
                endif;
            endwhile;
            ?>
          </div>

          <?php if ( $GLOBALS['wp_query']->max_num_pages > 1 ) : ?>
          <div class="pager sr">
            <?php echo paginate_links( [ 'prev_text' => '←', 'next_text' => '→' ] ); ?>
          </div>
          <?php endif; ?>

        <?php else : ?>

          <!-- ─ No-results state ─ -->
          <div class="srch-empty sr">
            <div class="srch-empty-ico" aria-hidden="true">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            </div>
            <p class="srch-empty-msg">
              <?php if ( $query ) : ?>
                لم نعثر على نتائج تطابق "<strong><?php echo esc_html( $query ); ?></strong>" — جرّب كلمة مختلفة أو تصفّح المحتوى أدناه.
              <?php else : ?>
                أدخل كلمة ابحث عنها.
              <?php endif; ?>
            </p>
            <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="srch-form">
              <label for="srch-input" class="screen-reader-text">بحث في الموقع</label>
              <input
                id="srch-input"
                class="form-input srch-input"
                type="search"
                name="s"
                placeholder="ابحث مجدداً..."
                value="<?php echo esc_attr( $query ); ?>"
              >
              <button type="submit" class="btn btn-p">بحث</button>
            </form>
            <div class="srch-links">
              <a href="<?php echo esc_url( sh_page_url( 'blog' ) ); ?>" class="btn btn-g sm">تصفّح المدونة</a>
              <a href="<?php echo esc_url( sh_page_url( 'services' ) ); ?>" class="btn btn-g sm">خدماتنا</a>
            </div>
          </div>

        <?php endif; ?>

      </div>

      <!-- ─ Sidebar ─ -->
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
                  <?php if ( has_post_thumbnail() ) the_post_thumbnail( 'thumbnail' ); ?>
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

        <div class="sb-box sb-cta sr d2">
          <div class="sb-cta-title"><?php echo esc_html( $_sb_title ); ?></div>
          <p class="sb-cta-desc"><?php echo esc_html( $_sb_desc ); ?></p>
          <a href="<?php echo esc_url( sh_page_url( 'contact' ) ); ?>" class="btn btn-w sm"><?php echo esc_html( $_sb_btn ); ?></a>
        </div>

      </div>

    </div>
  </div>
</section>

<?php get_footer(); ?>
