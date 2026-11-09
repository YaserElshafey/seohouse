<?php
/**
 * Case study rows (design: homepage "Results" list). One record per case study; each row
 * opens the case page.
 *
 * Contexts: "home" (as designed); "results" and "related" (2.8.8): the client's logo and name, the
 * field and country under it, and the case's own headline number. «related» lists other published
 * cases (args: exclude, limit).
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

$context = (string) ( $args['context'] ?? 'home' );
if ( 'related' === $context ) {
	$preview = sh_cases_preview(); // during a preview: the other new drafts too, without the cases to retire
	$cases   = get_posts(
		array(
			'post_type'      => 'case_study',
			'post_status'    => $preview ? array( 'publish', 'draft' ) : 'publish',
			'posts_per_page' => (int) ( $args['limit'] ?? 3 ),
			'post__not_in'   => array_map( 'intval', (array) ( $args['exclude'] ?? array() ) ),
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
			'no_found_rows'  => true,
		)
	);
	if ( $preview ) {
		$cases = array_values( array_filter( $cases, static fn( $p ) => ! get_post_meta( $p->ID, '_sh_retire_on_approval', true ) ) );
	}
} else {
	$cases = sh_case_studies( (int) ( $args['limit'] ?? 0 ) );
}
foreach ( $cases as $c ) :
	$f    = function_exists( 'get_fields' ) ? (array) get_fields( $c->ID ) : array();
	$rich = 'related' === $context || ( 'results' === $context && sh_cases_v2() );
	$meta = array();
	if ( ! $rich ) {
		// as designed: results page (client, sector, market, source), home (sector only)
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
	}
	$client  = ! empty( $f['client'] ) && ! empty( $f['client_public'] ) ? (string) $f['client'] : '';
	$title   = $rich && '' !== $client ? $client : get_the_title( $c );
	$sub     = $rich ? implode( ' · ', array_filter( array( (string) ( $f['sector'] ?? '' ), (string) ( $f['market'] ?? '' ) ) ) ) : '';
	$logo    = $rich && '' !== $client && ! empty( $f['client_logo'] ) ? (int) $f['client_logo'] : 0;
	$filters = implode( ' ', (array) ( $f['filter'] ?? array() ) );
	$draft   = 'publish' !== $c->post_status;
	$href    = sh_case_href( $c );
	?>
<li data-sh-filter-item="<?php echo esc_attr( $filters ); ?>">
	<a href="<?php echo esc_url( $href ); ?>" data-hcard data-res-card class="sh-hv-case" style="display: grid; gap: 14px 28px; align-items: center; background: rgb(255, 255, 255); border-radius: 16px; padding: clamp(18px, 2vw, 24px); box-shadow: 0 14px 34px -28px rgba(6,11,31,.5), 0 0 0 1px var(--sh-line); color: var(--sh-ink); transition: box-shadow 0.2s;">
		<span style="display: block;">
			<span style="display: block; font-family: Alexandria, sans-serif; font-weight: 800; font-size: clamp(28px, 3vw, 38px); line-height: 1; color: var(--sh-link);"><bdi><?php echo esc_html( $f['result'] ?? '' ); ?></bdi></span>
			<?php if ( ! empty( $f['result_label'] ) ) : ?>
			<span style="display: block; font-size: 14px; font-weight: 600; color: var(--sh-text); margin-top: 8px;"><?php echo esc_html( $f['result_label'] ); ?></span>
			<?php endif; ?>
		</span>
		<?php if ( $rich ) : ?>
		<span class="sh-case-client" style="display: flex; align-items: center; gap: 14px; min-width: 0px;">
			<?php if ( $logo ) : ?>
			<span class="sh-case-logo" aria-hidden="true"><?php echo wp_get_attachment_image( $logo, 'medium', false, array( 'alt' => '', 'loading' => 'lazy', 'decoding' => 'async' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			<?php endif; ?>
		<?php endif; ?>
			<span style="display: block; min-width: 0px;">
				<span style="display: block; font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(17px, 1.6vw, 20px); line-height: 1.5;"><?php echo esc_html( $title ); ?></span>
				<?php if ( '' !== $sub ) : ?>
				<span style="display: block; margin-top: 4px; font-size: 13.5px; color: var(--sh-text);"><?php echo esc_html( $sub ); ?></span>
				<?php endif; ?>
				<?php if ( $meta ) : ?>
				<span style="display: flex; flex-wrap: wrap; gap: 6px 16px; margin-top: 8px; font-size: 13.5px; color: var(--sh-text);">
					<?php foreach ( $meta as $m ) : ?>
					<span style="white-space: nowrap;"><?php echo esc_html( $m[0] ); ?> <b style="font-weight: 600; color: var(--sh-ink);"><?php echo esc_html( $m[1] ); ?></b></span>
					<?php endforeach; ?>
				</span>
				<?php endif; ?>
				<?php if ( $draft ) : ?>
				<span class="sh-case-draft"><?php esc_html_e( 'مسودة — معاينة', 'seohouse' ); ?></span>
				<?php endif; ?>
			</span>
		<?php if ( $rich ) : ?>
		</span>
		<?php endif; ?>
		<span data-res-go style="font-size: 14.5px; font-weight: 600; color: var(--sh-link); white-space: nowrap;"><?php esc_html_e( 'دراسة الحالة', 'seohouse' ); ?> <span aria-hidden="true"><?php echo esc_html( sh_arrow() ); ?></span></span>
	</a>
</li>
	<?php
endforeach;
