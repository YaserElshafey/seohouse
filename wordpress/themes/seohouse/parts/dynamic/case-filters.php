<?php
/**
 * Results index filters: "all" + sectors present in published case studies.
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

$labels = array(
	'ecommerce'   => __( 'التجارة الإلكترونية', 'seohouse' ),
	'health'      => __( 'الصحة والطب', 'seohouse' ),
	'education'   => __( 'التعليم والتدريب', 'seohouse' ),
	'real-estate' => __( 'العقارات', 'seohouse' ),
	'tourism'     => __( 'السياحة', 'seohouse' ),
	'tech'        => __( 'التقنية', 'seohouse' ),
	'legal'       => __( 'القانون', 'seohouse' ),
	'food'        => __( 'الأغذية والمطاعم', 'seohouse' ),
);
$present = array();
foreach ( sh_case_studies() as $c ) {
	foreach ( (array) sh_field( 'filter', $c->ID, array() ) as $k ) {
		$present[ $k ] = true;
	}
}
$groups = array( 'all' => __( 'كل النتائج', 'seohouse' ) ) + array_intersect_key( $labels, $present );
if ( count( $groups ) < 2 ) {
	return;
}
foreach ( $groups as $k => $l ) :
	?>
<button type="button" data-sh-filter="<?php echo esc_attr( $k ); ?>" aria-pressed="<?php echo 'all' === $k ? 'true' : 'false'; ?>" style="min-height: 40px; padding: 0px 16px; border-radius: 999px; font: inherit; font-weight: 600; font-size: 14px; cursor: pointer;"><?php echo esc_html( $l ); ?></button>
	<?php
endforeach;
