<?php
/**
 * ACF location rule «شاشة سيو هاوس»: attaches a field group to a Core admin screen
 * (the settings page). The screen passes array( 'sh_screen' => '<slug>' ) to acf_get_field_groups().
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

class SH_Location_Screen extends ACF_Location {

	public function initialize() {
		$this->name     = 'sh_screen';
		$this->label    = __( 'شاشة سيو هاوس', 'seohouse-core' );
		$this->category = 'forms';
	}

	public function match( $rule, $screen, $field_group ) {
		if ( ! isset( $screen['sh_screen'] ) ) {
			return false;
		}
		return $this->compare_to_rule( $screen['sh_screen'], $rule );
	}

	public function get_values( $rule ) {
		return array( 'seohouse-settings' => __( 'إعدادات سيو هاوس', 'seohouse-core' ) );
	}
}
