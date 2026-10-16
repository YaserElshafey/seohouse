<?php
/**
 * «خدماتنا في الأسواق العربية» on the Saudi, Egyptian and UAE SEO pages (before the FAQ): links to
 * the two other country pages and to the general SEO page. Card colours, top bar and hover are those of
 * «خبرة في ثلاثة أسواق عربية» (sections/seo/markets.php); size and alignment are the block's own
 * (.sh-other-markets in assets/css/theme.css). Links come from get_permalink() of the pages found by path;
 * a page that is not published is left out.
 *
 * @package SEOHouse
 * @var array $args { key: seo-ksa | seo-egypt | seo-uae }
 */

defined( 'ABSPATH' ) || exit;

$current = (string) ( $args['key'] ?? '' );
$targets = array(
	'seo-ksa'   => array( 'services/seo/ksa', __( 'تحسين محركات البحث في السعودية', 'seohouse' ) ),
	'seo-egypt' => array( 'services/seo/egypt', __( 'تحسين محركات البحث في مصر', 'seohouse' ) ),
	'seo-uae'   => array( 'services/seo/uae', __( 'تحسين محركات البحث في الإمارات', 'seohouse' ) ),
	'seo'       => array( 'services/seo', __( 'خدمات تحسين محركات البحث', 'seohouse' ) ),
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
<section data-screen-label="Other markets" class="sh-other-markets">
	<div class="sh-other-markets__inner">
		<h2 class="sh-other-markets__title"><?php esc_html_e( 'خدماتنا في الأسواق العربية', 'seohouse' ); ?></h2>
		<div class="sh-other-markets__grid">
			<?php foreach ( $cards as $c ) : ?>
			<a data-hcard class="sh-other-markets__card" href="<?php echo esc_url( $c[0] ); ?>"><span aria-hidden="true" class="sh-other-markets__bar"></span><span class="sh-other-markets__label"><?php echo esc_html( $c[1] ); ?></span></a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
