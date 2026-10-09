<?php
/**
 * Homepage "Articles": the latest post as the lead card and the next two as rows (design data-grid="blog2").
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

$articles = get_posts( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 3, 'no_found_rows' => true ) );
if ( ! $articles ) {
	return;
}
$lead   = array_shift( $articles );
$cat    = sh_primary_category( $lead );
$member = sh_post_author_member( $lead );
$intro  = (string) sh_field( 'intro', $lead->ID, '' );
$intro  = $intro ? $intro : get_the_excerpt( $lead );
?>
<a href="<?php echo esc_url( get_permalink( $lead ) ); ?>" data-hcard-lead data-blog-lead class="sh-hv-lead" style="position: relative; overflow: hidden; display: flex; flex-direction: column; border-radius: 18px; background: var(--sh-blue); color: rgb(255, 255, 255); padding: clamp(20px, 2.2vw, 28px); transition: box-shadow 0.25s;">
	<span aria-hidden="true" style="position: absolute; inset: 0px; opacity: 0.14; background-image: linear-gradient(rgba(255, 255, 255, 0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.5) 1px, transparent 1px); background-size: 32px 32px; mask-image: radial-gradient(80% 70% at 15% 100%, rgb(0, 0, 0), transparent 70%); pointer-events: none;"></span>
	<?php if ( $cat ) : ?>
	<span style="position: relative; display: inline-flex; align-self: flex-start; font-size: 12.5px; font-weight: 700; color: rgb(255, 255, 255); background: rgba(255, 255, 255, 0.18); border-radius: 999px; padding: 5px 11px;"><?php echo esc_html( $cat->name ); ?></span>
	<?php endif; ?>
	<span style="position: relative; display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(18px, 1.7vw, 22px); line-height: 1.5; margin-top: 14px; text-wrap: pretty;"><?php echo esc_html( get_the_title( $lead ) ); ?></span>
	<?php if ( $intro ) : ?>
	<span style="position: relative; display: block; font-size: 15px; line-height: 1.85; color: rgb(255, 255, 255); margin-top: 10px; max-width: 40em;"><?php echo esc_html( wp_trim_words( $intro, 34, '…' ) ); ?></span>
	<?php endif; ?>
	<span style="position: relative; margin-top: auto; padding-top: 18px; display: flex; flex-wrap: wrap; align-items: center; gap: 6px 14px; font-size: 13px; color: rgb(255, 255, 255);">
		<span style="white-space: nowrap;"><?php echo esc_html( $member ? get_the_title( $member ) : __( 'فريق سيو هاوس', 'seohouse' ) ); ?></span><span aria-hidden="true" style="width: 4px; height: 4px; border-radius: 999px; background: rgba(255, 255, 255, 0.5);"></span><span style="white-space: nowrap;"><?php echo esc_html( sh_post_date( $lead ) ); ?></span><span aria-hidden="true" style="width: 4px; height: 4px; border-radius: 999px; background: rgba(255, 255, 255, 0.5);"></span><span style="white-space: nowrap;"><?php echo esc_html( sh_reading_label( sh_reading_minutes( $lead ) ) ); ?></span>
		<span style="margin-inline-start: auto; font-weight: 700; color: rgb(255, 255, 255);"><?php esc_html_e( 'اقرأ المقال', 'seohouse' ); ?> <span aria-hidden="true">←</span></span>
	</span>
</a>
<?php if ( $articles ) : ?>
<div style="display: flex; flex-direction: column; gap: 12px;">
	<?php
	foreach ( $articles as $p ) :
		$c = sh_primary_category( $p );
		?>
	<a href="<?php echo esc_url( get_permalink( $p ) ); ?>" data-hcard data-blog-row class="sh-hv-row" style="display: flex; flex-direction: column; gap: 8px; flex: 1 1 0px; background: rgb(255, 255, 255); border-radius: 16px; padding: 18px 20px; box-shadow: rgba(37, 43, 51, 0.06) 0px 0px 0px 1px; color: var(--sh-ink); transition: box-shadow 0.2s, color 0.2s;">
		<?php if ( $c ) : ?><span style="font-size: 12.5px; font-weight: 600; color: var(--sh-link);"><?php echo esc_html( $c->name ); ?></span><?php endif; ?>
		<span style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16.5px; line-height: 1.55;"><?php echo esc_html( get_the_title( $p ) ); ?></span>
		<span style="margin-top: auto; display: flex; align-items: center; justify-content: space-between; gap: 10px; font-size: 12.5px; color: var(--sh-text);"><span><?php echo esc_html( sh_post_date( $p ) ); ?></span><span aria-hidden="true" style="color: var(--sh-link); font-weight: 700;">←</span></span>
	</a>
	<?php endforeach; ?>
</div>
<?php endif; ?>
