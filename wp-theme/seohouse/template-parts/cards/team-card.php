<?php
/**
 * Template part: team member card
 */
$post_id      = get_the_ID();
$job_title    = sh_field( 'job_title', $post_id );
$role_badge   = sh_field( 'role_badge', $post_id );
$expertise    = sh_field( 'expertise', $post_id );
$short_bio    = sh_field( 'short_bio', $post_id );
$linkedin_url = sh_field( 'linkedin_url', $post_id );
$twitter_url  = sh_field( 'twitter_url', $post_id );
$member_email = sh_field( 'member_email', $post_id );
$skills_raw   = sh_field( 'skills', $post_id );
$avatar_color = sh_field( 'avatar_color', $post_id, 'linear-gradient(140deg,#0b1240,rgba(30,46,245,.25))' );
$skills       = array_filter( array_map( 'trim', explode( "\n", $skills_raw ) ) );
$initials     = sh_initials( get_the_title() );
$delay_class  = isset( $args['delay_class'] ) ? ' ' . $args['delay_class'] : '';
?>
<div class="tm-card sr<?php echo esc_attr( $delay_class ); ?>">
  <div class="tm-avatar" style="background:<?php echo esc_attr( $avatar_color ); ?>">
    <?php if ( has_post_thumbnail() ) : ?>
      <?php the_post_thumbnail( 'seohouse-team' ); ?>
    <?php else : ?>
      <span class="tm-initials"><?php echo esc_html( $initials ); ?></span>
    <?php endif; ?>
    <?php if ( $role_badge ) : ?>
      <span class="tm-role-badge"><?php echo esc_html( $role_badge ); ?></span>
    <?php endif; ?>
  </div>
  <div class="tm-body">
    <div class="tm-name"><?php the_title(); ?></div>
    <?php if ( $job_title ) : ?>
      <div class="tm-role"><?php echo esc_html( $job_title ); ?></div>
    <?php endif; ?>
    <?php if ( $expertise ) : ?>
      <div class="tm-expertise"><?php echo esc_html( $expertise ); ?></div>
    <?php endif; ?>
    <?php if ( $short_bio ) : ?>
      <p class="tm-bio"><?php echo esc_html( $short_bio ); ?></p>
    <?php endif; ?>
    <?php if ( ! empty( $skills ) ) : ?>
      <div class="tm-tags">
        <?php foreach ( $skills as $skill ) : ?>
          <span class="chip"><?php echo esc_html( $skill ); ?></span>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <?php if ( $linkedin_url || $twitter_url || $member_email ) : ?>
      <div class="tm-socials">
        <?php if ( $linkedin_url ) : ?>
          <a href="<?php echo esc_url( $linkedin_url ); ?>" class="tm-soc" target="_blank" rel="noopener" aria-label="لينكد إن">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
          </a>
        <?php endif; ?>
        <?php if ( $twitter_url ) : ?>
          <a href="<?php echo esc_url( $twitter_url ); ?>" class="tm-soc" target="_blank" rel="noopener" aria-label="تويتر / X">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4l16 16M4 20L20 4"/></svg>
          </a>
        <?php endif; ?>
        <?php if ( $member_email ) : ?>
          <a href="mailto:<?php echo esc_attr( $member_email ); ?>" class="tm-soc" aria-label="البريد الإلكتروني">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
