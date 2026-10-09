<?php
/**
 * Results index filters: "all" + sectors present in published case studies.
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

$labels = array(
	'ecommerce'   => 'التجارة الإلكترونية',
	'health'      => 'الصحة والطب',
	'education'   => 'التعليم والتدريب',
	'real-estate' => 'العقارات',
	'tourism'     => 'السياحة',
	'tech'        => 'التقنية',
	'legal'       => 'القانون',
	'food'        => 'الأغذية والمطاعم',
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
