<?php
// Silence is golden — fallback template.
get_header();

if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        get_template_part( 'template-parts/cards/article-card' );
    endwhile;
    the_posts_navigation();
else :
    echo '<p class="bod" style="text-align:center;padding:80px 0">' . esc_html__( 'لا توجد مقالات.', 'seohouse' ) . '</p>';
endif;

get_footer();
