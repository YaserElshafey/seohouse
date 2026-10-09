<?php
// LOCAL TEST ONLY: applies the approved SEO values to the test database (same fields the owner edits in the dashboard).
$pages = array(
	18 => array( 'title' => 'شركة سيو لتحسين محركات البحث', 'text' => 'سيو هاوس شركة سيو تساعد الشركات والمتاجر على تحسين ظهورها في محركات البحث، من معالجة المشكلات التقنية إلى تطوير المحتوى وقياس الاستفسارات والمبيعات.',
		'rm_title' => 'شركة سيو لتحسين محركات البحث | سيو هاوس', 'rm_desc' => 'سيو هاوس شركة سيو تساعدك على تحسين ظهور موقعك في نتائج البحث عبر السيو التقني والمحتوى وتحسين الصفحات وبناء الروابط، مع قياس الأثر على الزيارات والمبيعات.' ),
	35 => array( 'title' => 'شركة سيو في السعودية', 'text' => 'تبحث عن شركة سيو في السعودية؟ يساعد فريق سيو هاوس الشركات والمتاجر السعودية على تحسين ظهورها في محركات البحث، بخطة تناسب نشاطها ومتابعة مباشرة عن بُعد.',
		'rm_title' => 'شركة سيو في السعودية لتحسين محركات البحث | سيو هاوس', 'rm_desc' => 'تبحث عن شركة سيو في السعودية؟ تساعد سيو هاوس الشركات والمتاجر في السوق السعودي على تحسين ظهورها في نتائج البحث بخطة واضحة ومتابعة عن بُعد وقياس للنتائج.' ),
	36 => array( 'title' => 'شركة سيو في مصر', 'text' => 'سيو هاوس شركة سيو في مصر تساعد الشركات والمتاجر على الوصول إلى عملائها عبر محركات البحث، من مراجعة الموقع وتطوير محتواه إلى متابعة الاستفسارات والمبيعات.', 'eyebrow' => 'السوق المصري',
		'rm_title' => 'شركة سيو في مصر لتحسين محركات البحث | سيو هاوس', 'rm_desc' => 'سيو هاوس شركة سيو في مصر تساعد الشركات والمتاجر على تحسين ظهور مواقعها في نتائج البحث عبر السيو التقني والمحتوى وتحسين الصفحات، مع قياس الأثر على العملاء.' ),
	37 => array( 'title' => 'شركة سيو في الإمارات', 'text' => 'تبحث عن شركة سيو في الإمارات؟ نساعد الشركات العاملة في السوق الإماراتي على تحسين مواقعها ومحتواها للوصول إلى العملاء الذين يبحثون عن خدماتها.', 'eyebrow' => 'السوق الإماراتي',
		'rm_title' => 'شركة سيو في الإمارات لتحسين محركات البحث | سيو هاوس', 'rm_desc' => 'تبحث عن شركة سيو في الإمارات؟ تخدم سيو هاوس الشركات العاملة في السوق الإماراتي بتحليل المنافسة وتحسين الصفحات والمحتوى والجوانب التقنية وقياس أثر العمل.' ),
);
foreach ( $pages as $id => $v ) {
	update_post_meta( $id, 's_hero_title', $v['title'] );
	update_post_meta( $id, 's_hero_text', $v['text'] );
	if ( isset( $v['eyebrow'] ) ) update_post_meta( $id, 's_hero_eyebrow', $v['eyebrow'] );
	update_post_meta( $id, 'rank_math_title', $v['rm_title'] );
	update_post_meta( $id, 'rank_math_description', $v['rm_desc'] );
}
update_post_meta( 6, 'rank_math_description', 'سيو هاوس تساعد الشركات والمتاجر على النمو عبر تحسين محركات البحث وتصميم المواقع والمتاجر الإلكترونية، بخطة واضحة ونتائج قابلة للقياس.' );
$names = array( 35 => 'شركة سيو في السعودية ←', 36 => 'شركة سيو في مصر ←', 37 => 'شركة سيو في الإمارات ←' );
foreach ( array( 6 => 'eyebrow', 18 => 'label' ) as $pid => $key ) {
	foreach ( (array) get_post_meta( $pid, 's_markets_items', true ) as $row ) {
		$target = url_to_postid( sh_link( get_post_meta( $row, 'link', true ) ) ) ?: (int) get_post_meta( $row, 'link', true );
		if ( isset( $names[ $target ] ) ) { update_post_meta( $row, $key, $names[ $target ] ); echo "row $row ($pid) -> {$names[$target]}\n"; }
	}
}
foreach ( wp_get_nav_menu_items( get_nav_menu_locations()['footer'] ) as $it ) {
	$map = array( 35 => 'شركة سيو في السعودية', 36 => 'شركة سيو في مصر', 37 => 'شركة سيو في الإمارات' );
	if ( isset( $map[ (int) $it->object_id ] ) && 'page' === $it->object ) { wp_update_post( array( 'ID' => $it->ID, 'post_title' => $map[ (int) $it->object_id ] ) ); echo "menu {$it->ID} -> {$map[(int)$it->object_id]}\n"; }
}
update_field( 'field_sh_opt_footer_text', 'سيو هاوس شركة سيو متخصصة في تحسين محركات البحث وتصميم المواقع والمتاجر الإلكترونية. نساعد الشركات في السعودية ومصر والإمارات على تطوير حضورها الرقمي منذ 2017.', 'option' );
echo "footer: ", get_option( 'options_sh_footer_text' ), "\n";
