<?php
/**
 * «أسواق أخرى نخدمها» on the Saudi, Egyptian and UAE SEO pages (before the FAQ): links to the two
 * other country pages and to the general SEO page. Same card component as «خبرة في ثلاثة أسواق
 * عربية» (sections/seo/markets.php). Links come from get_permalink() of the pages found by path;
 * a page that is not published is left out.
 *
 * @package SEOHouse
 * @var array $args { key: seo-ksa | seo-egypt | seo-uae }
 */

defined( 'ABSPATH' ) || exit;

$current = (string) ( $args['key'] ?? '' );
$targets = array(
	'seo-ksa'   => array( 'services/seo/ksa', __( 'شركة سيو في السعودية', 'seohouse' ) ),
	'seo-egypt' => array( 'services/seo/egypt', __( 'شركة سيو في مصر', 'seohouse' ) ),
	'seo-uae'   => array( 'services/seo/uae', __( 'شركة سيو في الإمارات', 'seohouse' ) ),
	'seo'       => array( 'services/seo', __( 'شركة سيو', 'seohouse' ) ),
);
unset( $targets[ $current ] );
$cards = array();
foreach ( $targets as $t ) {
	$page = get_page_by_path( $t[0], OBJECT, 'page' );
	if ( $page && 'publish' === $page->post_status ) {
		$cards[] = array( (string) get_permalink( $page ), $t[1] );
	}
}
if ( ! $cards ) {
	return;
}
?>
<section data-screen-label="Other markets" style="border-bottom: 1px solid var(--sh-line);">
	<div style="max-width: 1200px; margin: 0px auto; padding: clamp(32px, 4.4vw, 60px) 20px;">
		<h2 style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(23px, 2.2vw, 31px); margin: 0px; line-height: 1.3;"><?php esc_html_e( 'أسواق أخرى نخدمها', 'seohouse' ); ?></h2>
		<div style="margin-top: 28px; display: grid; gap: 16px; grid-template-columns: repeat(auto-fit, minmax(min(100%, 260px), 1fr));">
			<?php foreach ( $cards as $c ) : ?>
			<a data-hcard href="<?php echo esc_url( $c[0] ); ?>" style="display: flex; flex-direction: column; border-radius: 20px; overflow: hidden; background: var(--sh-surface); border: 1px solid var(--sh-line); color: var(--sh-ink);">
				<span aria-hidden="true" style="display: block; height: 4px; background: linear-gradient(90deg, var(--sh-sky), rgba(40, 84, 232, 0.6));"></span>
				<span style="display: flex; flex-direction: column; gap: 10px; padding: clamp(20px, 2.2vw, 28px); flex: 1 1 auto;">
					<span style="font-family: Alexandria, sans-serif; font-weight: 800; font-size: 22px; color: var(--sh-ink);"><?php echo esc_html( $c[1] ); ?></span>
				</span>
			</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
