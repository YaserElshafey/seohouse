<?php
// Simulates the owner's edits on the live site (local test DB only).
$home = (int) get_option( 'page_on_front' );
$by   = static fn( $path ) => get_page_by_path( $path )->ID;
$set  = static function ( $id, $key, $val ) { update_post_meta( $id, $key, $val ); };

// 1. Edited copy (top-level fields)
$set( $home, 's_hero_title', 'نحسّن ظهور موقعك في محركات البحث' );
$set( $home, 's_hero_text', 'فريق بخبرات متنوعة يعمل معك لجذب العملاء وزيادة مبيعاتك.' );
$set( $by( 'team' ), 's_hero_text', 'نص فريق العمل كما عدّله المالك [EDIT-TEAM]' );

// 2. Edited list item (record of the services list on the home page)
$svc = maybe_unserialize( get_post_meta( $home, 's_services_items', true ) );
$first = (int) $svc[0];
foreach ( get_post_meta( $first ) as $k => $v ) {
	if ( '_' !== $k[0] && is_string( $v[0] ) && '' !== $v[0] && ! is_numeric( $v[0] ) ) { update_post_meta( $first, $k, $v[0] . ' [EDIT-ROW]' ); break; }
}

// 3. Edits inside sections whose field names change in the v5 design
foreach ( array(
	array( '', 's_services_title', 'خدماتنا [EDIT-HOME-SERVICES]' ),
	array( 'services/web-design/custom-dev', 's_fit_heading', 'عنوان مناسب لك [EDIT-FIT]' ),
	array( 'services/stores/woocommerce', 's_build_title', 'بناء المتجر [EDIT-WOO]' ),
	array( 'sectors/legal', 's_practice_title', 'مجالات الممارسة [EDIT-LEGAL]' ),
	array( 'services/seo/backlinks', 's_hero_title', 'بناء الروابط [EDIT-BACKLINKS]' ),
) as list( $path, $key, $val ) ) {
	$id = $path ? $by( $path ) : $home;
	if ( '' === (string) get_post_meta( $id, $key, true ) ) { WP_CLI::warning( "empty before edit: $path $key" ); }
	$set( $id, $key, $val );
}

// 4. Rank Math per-page settings
foreach ( array( $home, $by( 'services/seo' ), $by( 'team' ) ) as $id ) {
	$set( $id, 'rank_math_title', 'عنوان Rank Math معدّل ' . $id );
	$set( $id, 'rank_math_description', 'وصف Rank Math معدّل ' . $id );
	$set( $id, 'rank_math_focus_keyword', 'سيو,تحسين' );
}
$set( $by( 'terms' ), 'rank_math_robots', array( 'noindex' ) );
$titles = (array) get_option( 'rank-math-options-titles', array() );
$titles['homepage_title'] = 'عنوان الرئيسية في إعدادات Rank Math [EDIT-RM]';
update_option( 'rank-math-options-titles', $titles );

// 5. The owner deleted one team member permanently
$gone = get_page_by_path( 'mona-ali', OBJECT, 'team_member' );
wp_delete_post( $gone->ID, true );
WP_CLI::success( 'edits applied; deleted team member ' . $gone->ID );
