<?php
/**
 * Article rows (design data-bl-list) for the current query, plus real pagination.
 *
 * @package SEOHouse
 * @var array $args { skip_first: bool }
 */

defined( 'ABSPATH' ) || exit;

global $wp_query;
$rows = $wp_query->posts;
if ( ! empty( $args['skip_first'] ) ) {
	$rows = array_slice( $rows, 1 );
}
if ( $rows ) :
	?>
<ul data-bl-list style="list-style: none; margin: 22px 0px 0px; padding: 0px; border-top: 1px solid rgba(var(--sh-ink-rgb), 0.1);">
	<?php
	foreach ( $rows as $p ) :
		$cat    = sh_primary_category( $p );
		$member = sh_post_author_member( $p );
		?>
	<li style="border-bottom: 1px solid rgba(var(--sh-ink-rgb), 0.1);">
		<a href="<?php echo esc_url( get_permalink( $p ) ); ?>" data-bl-row class="sh-hv-blrow" style="display: grid; gap: 6px 24px; align-items: center; padding: 18px 4px; color: var(--sh-ink); transition: background 0.2s;">
			<span data-bl-cat style="font-size: 13px; font-weight: 600; color: var(--sh-blue);"><?php echo $cat ? esc_html( $cat->name ) : ''; ?></span>
			<span style="min-width: 0px; font-family: Alexandria, sans-serif; font-weight: 700; font-size: 16.5px; line-height: 1.6; text-wrap: pretty;"><?php echo esc_html( get_the_title( $p ) ); ?></span>
			<span data-bl-meta style="font-size: 13px; color: var(--sh-slate); display: flex; flex-wrap: wrap; gap: 2px 8px;"><span><?php echo esc_html( $member ? get_the_title( $member ) : sprintf( /* translators: %s: company */ __( 'فريق %s', 'seohouse' ), sh_site_name() ) ); ?></span><span aria-hidden="true">·</span><span><?php echo esc_html( sh_post_date( $p ) ); ?></span></span>
			<span data-bl-go aria-hidden="true" style="color: var(--sh-blue); font-size: 18px;">←</span>
		</a>
	</li>
	<?php endforeach; ?>
</ul>
	<?php
endif;

$total = (int) $wp_query->max_num_pages;
if ( $total > 1 ) :
	$cur  = max( 1, (int) get_query_var( 'paged' ) );
	$btn  = 'min-width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px;';
	$link = $btn . ' background: rgb(255, 255, 255); color: var(--sh-surface-3); font-weight: 600; box-shadow: rgba(var(--sh-ink-rgb), 0.1) 0px 0px 0px 1px inset;';
	?>
<nav aria-label="<?php esc_attr_e( 'ترقيم الصفحات', 'seohouse' ); ?>" style="margin-top: 26px; display: flex; flex-wrap: wrap; justify-content: center; align-items: center; gap: 6px;">
	<?php if ( $cur > 1 ) : ?>
	<a href="<?php echo esc_url( get_pagenum_link( $cur - 1 ) ); ?>" rel="prev" style="<?php echo esc_attr( $link ); ?> padding: 0px 14px; color: var(--sh-blue);">→ <?php esc_html_e( 'السابق', 'seohouse' ); ?></a>
	<?php endif; ?>
	<?php for ( $i = 1; $i <= $total; $i++ ) : ?>
		<?php if ( $i === $cur ) : ?>
	<span aria-current="page" style="<?php echo esc_attr( $btn ); ?> background: var(--sh-blue); color: rgb(255, 255, 255); font-weight: 700;"><?php echo (int) $i; ?></span>
		<?php else : ?>
	<a href="<?php echo esc_url( get_pagenum_link( $i ) ); ?>" style="<?php echo esc_attr( $link ); ?>"><?php echo (int) $i; ?></a>
		<?php endif; ?>
	<?php endfor; ?>
	<?php if ( $cur < $total ) : ?>
	<a href="<?php echo esc_url( get_pagenum_link( $cur + 1 ) ); ?>" rel="next" style="<?php echo esc_attr( $link ); ?> padding: 0px 14px; color: var(--sh-blue);"><?php esc_html_e( 'التالي', 'seohouse' ); ?> ←</a>
	<?php endif; ?>
</nav>
	<?php
endif;
