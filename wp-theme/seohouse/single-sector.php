<?php
/**
 * Single sector page
 */
get_header();

while ( have_posts() ) : the_post();
    $hero_desc    = sh_field( 'hero_description' );
    $challenges   = sh_field( 'challenges' );
    $solutions    = sh_field( 'solutions' );
    $related_cs   = sh_field( 'related_case_studies' );
    $icon_svg     = sh_field( 'sector_icon_svg' );
?>

<?php
get_template_part( 'template-parts/layout/page-hero', null, [
    'tag'         => 'القطاع',
    'title'       => get_the_title(),
    'description' => $hero_desc ?: get_the_excerpt(),
    'breadcrumb'  => [
        'القطاعات'      => sh_page_url( 'sectors' ),
        get_the_title() => '',
    ],
    'buttons' => [
        [ 'text' => 'احجز استشارة مجانية', 'url' => sh_page_url( 'contact' ), 'class' => 'btn-p lg' ],
    ],
] );
?>

<?php if ( get_the_content() ) : ?>
<section class="sec sec-white">
  <div class="wrap">
    <div style="max-width:760px">
      <div class="post-content"><?php the_content(); ?></div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ( ! empty( $challenges ) ) : ?>
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh sr"><span class="tag">التحديات</span><h2 class="h2">تحديات هذا القطاع</h2></div>
    <div class="sector-challenges">
      <?php foreach ( $challenges as $idx => $ch ) : ?>
      <div class="sc-card sr <?php echo ( $idx % 2 === 1 ) ? 'd1' : ''; ?>">
        <?php if ( ! empty( $ch['icon'] ) ) : ?>
          <div class="val-ico" style="margin-bottom:14px;margin-inline:0"><?php echo wp_kses( $ch['icon'], [ 'svg' => [ 'viewBox' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'width' => true, 'height' => true ], 'path' => [ 'd' => true ], 'polyline' => [ 'points' => true ], 'circle' => [ 'cx' => true, 'cy' => true, 'r' => true ], 'rect' => [ 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true ] ] ); ?></div>
        <?php endif; ?>
        <h3><?php echo esc_html( $ch['title'] ?? '' ); ?></h3>
        <p><?php echo esc_html( $ch['desc'] ?? '' ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ( ! empty( $solutions ) ) : ?>
<section class="sec sec-white">
  <div class="wrap">
    <div class="sh sr"><span class="tag">الحلول</span><h2 class="h2">كيف نُحسّن حضورك في هذا القطاع</h2></div>
    <div class="features-grid">
      <?php foreach ( $solutions as $idx => $sol ) : ?>
      <div class="feat-card sr <?php echo ( $idx % 3 === 1 ) ? 'd1' : ( ( $idx % 3 === 2 ) ? 'd2' : '' ); ?>">
        <div class="ico-box lg" style="margin-bottom:16px">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="22" height="22"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <h3><?php echo esc_html( $sol['title'] ?? '' ); ?></h3>
        <p><?php echo esc_html( $sol['desc'] ?? '' ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ( ! empty( $related_cs ) ) : ?>
<section class="sec sec-surface">
  <div class="wrap">
    <div class="sh sr"><span class="tag">نتائج</span><h2 class="h2">مشاريع من هذا القطاع</h2></div>
    <div class="results-grid">
      <?php foreach ( $related_cs as $cs_post ) : ?>
        <?php
        global $post;
        $post = get_post( $cs_post );
        setup_postdata( $post );
        get_template_part( 'template-parts/cards/case-study-card' );
        ?>
      <?php endforeach; wp_reset_postdata(); ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php
get_template_part( 'template-parts/layout/cta-banner', null, [
    'tag'   => 'ابدأ الآن',
    'title' => 'هل نشاطك في هذا القطاع؟',
] );
?>

<?php endwhile; ?>

<?php get_footer(); ?>
