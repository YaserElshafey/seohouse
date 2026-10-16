<?php
// LOCAL TEST ONLY: proposed text for /services/seo/ (page 18), the same fields the owner edits.
update_post_meta( 18, 's_layers_text', 'نرتّب العمل حسب حالة الموقع وهدفه: إن كانت الصفحات لا تُفهرس نبدأ بالأساس التقني، وإن كانت تُفهرس ولا تظهر نعمل على الصفحات والمحتوى، وإن كانت تظهر ولا تجلب طلبات نركّز على الصفحات التي تقود إلى التواصل والشراء.' );
$desc = array(
	'01' => 'نراجع النشاط والسوق والوضع الحالي، ونتأكد أن القياس يسجّل الاستفسارات والمبيعات قبل أي تعديل.',
	'02' => 'تدقيق تقني وتحليل للمنافسين والكلمات والصفحات، ثم ترتيب المهام حسب الأثر المتوقع والجهد المطلوب.',
	'03' => 'ينفّذ فريقنا التحسينات التقنية والصفحات والمحتوى والروابط حسب النطاق، وننسّق مع مطورك ما يحتاج تطويرًا مخصصًا.',
	'04' => 'نراجع شهريًا الظهور والزيارات والاستفسارات والمبيعات القادمة من البحث، ونحدد أولويات المرحلة التالية.',
);
foreach ( (array) get_post_meta( 18, 's_process_phases', true ) as $row ) {
	$n = get_post_meta( $row, 'num', true );
	if ( isset( $desc[ $n ] ) ) update_post_meta( $row, 'desc', $desc[ $n ] );
}
$tools = (array) get_post_meta( 18, 's_tools_items', true );
update_post_meta( $tools[0], 'text', 'الظهور والنقرات والزيارات من البحث، ثم الاستفسارات والمبيعات التي نتتبعها عبر النماذج والاتصال والشراء في جوجل أناليتكس.' );
$faq = (array) get_post_meta( 18, 's_faq_items', true );
foreach ( $faq as $row ) {
	$q = get_post_meta( $row, 'question', true );
	if ( 'كم يستغرق ظهور نتائج السيو؟' === $q ) update_post_meta( $row, 'answer', 'يعتمد على حالة الموقع التقنية، وقوة المنافسة على العبارات المستهدفة، وعمر الموقع وثقته، وسرعة تنفيذ التعديلات واعتمادها. تظهر المؤشرات الأولى من فهرسة وظهور ونقرات غالبًا بين 6 و12 أسبوعًا، والأثر على الطلبات يحتاج وقتًا أطول، ولا نضمن ترتيبًا أو موعدًا محددًا.' );
	if ( 'هل أحتاج إلى مطور؟' === $q ) update_post_meta( $row, 'answer', 'ليس بالضرورة. فريقنا مسؤول عن التحليل والأولويات والمحتوى وتحسين الصفحات، وينفّذ مباشرة على ووردبريس وسلة وزد وشوبيفاي متى توفرت الصلاحيات. في الأنظمة المخصصة نسلّم مطورك المهام التقنية بوضوح ونراجعها بعد التنفيذ.' );
}
$exists = array_filter( $faq, static fn( $r ) => 'ما الذي تحتاجونه مني لبدء العمل؟' === get_post_meta( $r, 'question', true ) );
if ( ! $exists ) {
	$field = get_post_meta( $faq[0], '_sh_field', true );
	$id = wp_insert_post( array( 'post_type' => 'sh_row', 'post_status' => 'publish', 'post_parent' => 18, 'menu_order' => count( $faq ), 'post_title' => 'row', 'meta_input' => array(
		'_sh_owner' => '18', '_sh_field' => $field,
		'question' => 'ما الذي تحتاجونه مني لبدء العمل؟', '_question' => get_post_meta( $faq[0], '_question', true ),
		'answer' => 'صلاحية الوصول إلى الموقع وإلى سيرش كونسول وجوجل أناليتكس، ومعلومات عن خدماتك وأسعارك وأولوياتك، ومراجعة المحتوى المتخصص قبل نشره. وإن كان لديك مطور، نحتاج تواصلًا مباشرًا معه في المهام التقنية.', '_answer' => get_post_meta( $faq[0], '_answer', true ),
	) ) );
	// placed after «هل أحتاج إلى مطور؟»
	$pos = array_search( (int) current( array_filter( $faq, static fn( $r ) => 'هل أحتاج إلى مطور؟' === get_post_meta( $r, 'question', true ) ) ), array_map( 'intval', $faq ), true );
	array_splice( $faq, false === $pos ? count( $faq ) : $pos + 1, 0, array( $id ) );
	update_post_meta( 18, 's_faq_items', array_map( 'intval', $faq ) );
	foreach ( $faq as $i => $r ) wp_update_post( array( 'ID' => $r, 'menu_order' => $i ) );
}
wp_cache_flush();
echo "ok\n";
