<?php
/**
 * Case study rows (design: homepage "Results" list). One record per case study; each row
 * opens the case page.
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

$context = (string) ( $args['context'] ?? 'home' );
$cases   = sh_case_studies( (int) ( $args['limit'] ?? 0 ) );
foreach ( $cases as $c ) :
	$f    = function_exists( 'get_fields' ) ? (array) get_fields( $c->ID ) : array();
	$meta = array();
	if ( 'results' === $context && ! empty( $f['client'] ) && ! empty( $f['client_public'] ) ) {
		$meta[] = array( __( 'العميل:', 'seohouse' ), $f['client'] );
	}
	foreach ( array( 'sector' => __( 'القطاع:', 'seohouse' ), 'market' => __( 'السوق:', 'seohouse' ) ) as $k => $l ) {
		if ( ! empty( $f[ $k ] ) ) {
			$meta[] = array( $l, $f[ $k ] );
		}
	}
	if ( 'results' === $context && ! empty( $f['source'] ) ) {
		$meta[] = array( __( 'المصدر:', 'seohouse' ), $f['source'] );
	}
	if ( 'home' === $context ) {
		$meta = array_slice( $meta, 0, 1 );
	}
	$filters = implode( ' ', (array) ( $f['filter'] ?? array() ) );
	?>
<li data-sh-filter-item="<?php echo esc_attr( $filters ); ?>">
	<a href="<?php echo esc_url( get_permalink( $c ) ); ?>" data-hcard data-res-card class="sh-hv-case" style="display: grid; gap: 14px 28px; align-items: center; background: rgb(255, 255, 255); border-radius: 16px; padding: clamp(18px, 2vw, 24px); box-shadow: 0 14px 34px -28px rgba(6,11,31,.5), 0 0 0 1px var(--sh-line); color: var(--sh-ink); transition: box-shadow 0.2s;">
		<span style="display: block;">
			<span style="display: block; font-family: Alexandria, sans-serif; font-weight: 800; font-size: clamp(28px, 3vw, 38px); line-height: 1; color: var(--sh-link);"><bdi><?php echo esc_html( $f['result'] ?? '' ); ?></bdi></span>
			<?php if ( ! empty( $f['result_label'] ) ) : ?>
			<span style="display: block; font-size: 14px; font-weight: 600; color: var(--sh-text); margin-top: 8px;"><?php echo esc_html( $f['result_label'] ); ?></span>
			<?php endif; ?>
		</span>
		<span style="display: block; min-width: 0px;">
			<span style="display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(17px, 1.6vw, 20px); line-height: 1.5;"><?php echo esc_html( get_the_title( $c ) ); ?></span>
			<?php if ( $meta ) : ?>
			<span style="display: flex; flex-wrap: wrap; gap: 6px 16px; margin-top: 8px; font-size: 13.5px; color: var(--sh-text);">
				<?php foreach ( $meta as $m ) : ?>
				<span style="white-space: nowrap;"><?php echo esc_html( $m[0] ); ?> <b style="font-weight: 600; color: var(--sh-ink);"><?php echo esc_html( $m[1] ); ?></b></span>
				<?php endforeach; ?>
			</span>
			<?php endif; ?>
		</span>
		<span data-res-go style="font-size: 14.5px; font-weight: 600; color: var(--sh-link); white-space: nowrap;"><?php esc_html_e( 'دراسة الحالة', 'seohouse' ); ?> <span aria-hidden="true">←</span></span>
	</a>
</li>
	<?php
endforeach;
