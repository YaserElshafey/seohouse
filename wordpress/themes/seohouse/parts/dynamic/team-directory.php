<?php
/**
 * Team page directory: one card per team member (design data-tm-card).
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

foreach ( sh_team_members() as $m ) :
	$f    = function_exists( 'get_fields' ) ? (array) get_fields( $m->ID ) : array();
	$url  = get_permalink( $m );
	$name = get_the_title( $m );
	?>
<li data-tm-card class="sh-hv-tmcard" style="display: flex; flex-direction: column; border-radius: 18px; background: linear-gradient(rgb(14, 26, 72), var(--sh-surface)); border: 1px solid rgba(var(--sh-sky-rgb), 0.14); overflow: hidden; transition: border-color 0.2s, box-shadow 0.2s;">
	<a href="<?php echo esc_url( $url ); ?>" tabindex="-1" aria-hidden="true" data-tm-ph style="display: block; overflow: hidden; background: linear-gradient(rgb(22, 35, 90), var(--sh-surface));">
		<?php echo get_the_post_thumbnail( $m, 'medium_large', array( 'alt' => '', 'loading' => 'lazy', 'decoding' => 'async', 'data-tm-img' => '', 'style' => 'width: 100%; height: 100%; object-fit: cover; object-position: center top; display: block;' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</a>
	<div style="min-width: 0px; padding: 16px 16px 14px; display: flex; flex-direction: column; align-items: center; text-align: center; flex: 1 1 auto;">
		<h3 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17px; margin: 0px; line-height: 1.4; text-wrap: balance;"><a href="<?php echo esc_url( $url ); ?>" class="sh-hv-link" style="color: var(--sh-text);"><?php echo esc_html( $name ); ?></a></h3>
		<?php if ( ! empty( $f['role'] ) ) : ?>
		<p style="font-size: 14px; line-height: 1.55; color: var(--sh-muted); margin: 6px 0px 0px; max-width: 22em; text-wrap: balance;"><?php echo esc_html( $f['role'] ); ?></p>
		<?php endif; ?>
		<?php if ( ! empty( $f['experience'] ) ) : ?>
		<p style="align-self: center; font-size: 12px; font-weight: 600; color: var(--sh-lime); background: rgba(var(--sh-lime-rgb), 0.08); border-radius: 999px; padding: 3px 10px; margin: 10px 0px 0px;"><?php echo esc_html( $f['experience'] ); ?></p>
		<?php endif; ?>
		<div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 6px 14px; margin-top: auto; padding-top: 12px; border-top: 1px solid rgba(255, 255, 255, 0.08); margin-block-start: 14px; align-self: stretch;">
			<a href="<?php echo esc_url( $url ); ?>" class="sh-hv-link" style="font-size: 14px; font-weight: 600; color: var(--sh-sky);"><?php esc_html_e( 'الملف الشخصي', 'seohouse' ); ?> <span aria-hidden="true">←</span></a>
			<?php if ( ! empty( $f['linkedin'] ) ) : ?>
			<a href="<?php echo esc_url( $f['linkedin'] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( 'لينكدإن — ' . $name ); ?>" class="sh-hv-in" style="display: inline-flex; width: 32px; height: 32px; border-radius: 9px; background: rgba(var(--sh-blue-rgb), 0.18); color: var(--sh-crumb-current); align-items: center; justify-content: center;"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true" style="display: block;"><path d="M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zM3 9h4v12H3zM9 9h3.8v1.7h.1c.5-1 1.8-2 3.8-2 4 0 4.8 2.6 4.8 6V21h-4v-5.4c0-1.3 0-3-1.9-3s-2.1 1.4-2.1 2.9V21H9z"></path></svg></a>
			<?php endif; ?>
		</div>
	</div>
</li>
	<?php
endforeach;
