<?php
/**
 * Template part: case study card (proof-card style)
 * Context: $post must be a case_study post, queried before calling this.
 */
$post_id      = get_the_ID();
$sector_terms = get_the_terms( $post_id, 'case_study_sector' );
$sector       = ! empty( $sector_terms ) && ! is_wp_error( $sector_terms ) ? $sector_terms[0]->name : '';
$client       = sh_field( 'client_name', $post_id );
$metrics      = sh_field( 'metrics', $post_id );
$headline     = sh_field( 'headline_result', $post_id ) ?: ( $metrics[0]['value'] ?? '—' );
$card_meta    = sh_field( 'card_meta', $post_id );
$delay_class  = isset( $args['delay'] ) ? ' ' . $args['delay'] : '';
?>
<div class="proof-card sr<?php echo esc_attr( $delay_class ); ?>">
  <?php if ( $sector ) : ?>
  <div class="proof-sector"><?php echo esc_html( $sector ); ?></div>
  <?php endif; ?>
  <?php if ( $client ) : ?>
  <div class="proof-client"><?php echo esc_html( $client ); ?></div>
  <?php endif; ?>
  <div class="proof-result <?php echo esc_attr( sh_value_class( $headline ) ); ?>"><em><?php echo esc_html( $headline ); ?></em></div>
  <div class="proof-desc"><?php echo esc_html( get_the_excerpt() ); ?></div>
  <?php if ( $card_meta ) : ?>
  <div class="proof-meta"><div class="proof-dot"></div><?php echo esc_html( $card_meta ); ?></div>
  <?php endif; ?>
</div>
