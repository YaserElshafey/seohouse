<?php
/**
 * Template part: case study card
 * Context: $post must be a case_study post, queried before calling this.
 */
$post_id      = get_the_ID();
$sector_terms = get_the_terms( $post_id, 'case_study_sector' );
$sector_label = ! empty( $sector_terms ) && ! is_wp_error( $sector_terms ) ? $sector_terms[0]->name : sh_field( 'client_name', $post_id, '' );
$duration     = sh_field( 'project_duration', $post_id );
$metrics      = sh_field( 'metrics', $post_id );
$chart_bars   = sh_case_chart_bars( $post_id );
$delay_class  = isset( $args['delay'] ) ? ' ' . $args['delay'] : '';
?>
<article class="cs-card sr<?php echo esc_attr( $delay_class ); ?>">
  <div class="cs-screen">
    <div class="cs-chrome">
      <div class="cs-dots"><span></span><span></span><span></span></div>
      <div class="cs-bar"></div>
    </div>
    <div class="cs-img">
      <?php if ( has_post_thumbnail() ) : ?>
        <?php the_post_thumbnail( 'seohouse-case-thumb', [ 'style' => 'position:absolute;inset:24px 0 0;width:100%;height:calc(100% - 24px);object-fit:cover;opacity:.55' ] ); ?>
      <?php endif; ?>
      <div class="cs-chart">
        <?php foreach ( $chart_bars as $bar ) :
            $h = is_array( $bar ) && isset( $bar['bar_height'] ) ? (int) $bar['bar_height'] : (int) $bar;
            $hi = $h >= 55 ? ' hi' : '';
            ?>
            <div class="cs-b<?php echo $hi; ?>" style="height:<?php echo $h; ?>%"></div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <div class="cs-body">
    <?php if ( $sector_label ) : ?>
      <div class="cs-sector"><?php echo esc_html( $sector_label ); ?></div>
    <?php endif; ?>
    <h3><?php the_title(); ?></h3>
    <p><?php echo esc_html( get_the_excerpt() ); ?></p>
    <?php if ( ! empty( $metrics ) && is_array( $metrics ) ) : ?>
      <div class="cs-meta">
        <?php foreach ( array_slice( $metrics, 0, 2 ) as $m ) : ?>
          <div>
            <div class="cs-ml"><?php echo esc_html( $m['metric_label'] ); ?></div>
            <div class="cs-mv"><?php echo esc_html( $m['metric_value'] ); ?></div>
          </div>
        <?php endforeach; ?>
        <?php if ( ! empty( $duration ) ) : ?>
          <div>
            <div class="cs-ml">المدة</div>
            <div class="cs-mv"><?php echo esc_html( $duration ); ?></div>
          </div>
        <?php endif; ?>
      </div>
    <?php elseif ( $duration ) : ?>
      <div class="cs-meta">
        <div>
          <div class="cs-ml">المدة</div>
          <div class="cs-mv"><?php echo esc_html( $duration ); ?></div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</article>
