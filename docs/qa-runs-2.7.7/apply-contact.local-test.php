<?php
// LOCAL TEST ONLY: the values the owner enters in the dashboard.
update_field( 'field_sh_opt_phone', '+201050861791', 'option' );
update_field( 'field_sh_opt_email', 'info@seohouse.agency', 'option' );
update_field( 'field_sh_opt_address', '234 شارع عبد السلام عارف، لوران، الإسكندرية، مصر', 'option' );
update_field( 'field_sh_opt_hours', 'من الأحد إلى الخميس، من 9 صباحًا إلى 5 مساءً بتوقيت مصر', 'option' );
$bk = get_field( 'sh_booking', 'option', false ) ?: array();
update_option( 'options_sh_booking_step2_text', 'وصلنا طلبك، وسيتواصل معك فريقنا لتحديد موعد الاستشارة' );
update_post_meta( 13, 's_flow_button', 'إرسال الطلب' );
foreach ( (array) get_post_meta( 13, 's_flow_items_3', true ) as $row ) {
	if ( 'v4' === get_post_meta( $row, 'variant', true ) ) { update_post_meta( $row, 'label', 'رقم الهاتف' ); }
}
echo "ok\n";
