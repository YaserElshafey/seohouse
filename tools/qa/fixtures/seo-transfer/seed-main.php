<?php
// Seeds the local copy of the main site (127.0.0.1:8096) with the paths and SEO values seohouse.agency shows today
// (live.json, read 3 Oct 2026). A value of the form "X - سيو هاوس" is produced by a Rank Math template, as on a
// typical install; any other value is stored in the page's own Rank Math field. Plus a few test-only cases.
$live = json_decode( file_get_contents( __DIR__ . '/live.json' ), true );
$by   = array_column( $live, null, 'path' );
$sep  = ' - سيو هاوس';
$t = get_option( 'rank-math-options-titles', array() );
$t = array_merge( $t, array(
	'title_separator' => '-',
	'homepage_title' => '%sitename% %page% %sep% %sitedesc%', 'homepage_description' => '',
	'pt_page_title' => '%title% %sep% %sitename%', 'pt_page_description' => '',
	'pt_post_title' => '%title% %sep% %sitename%', 'pt_post_description' => '%excerpt%',
	'pt_case_study_title' => '%title% %sep% %sitename%', 'pt_case_study_description' => '%excerpt%',
	'pt_case_study_archive_title' => '%title% %sep% %sitename%', 'pt_case_study_archive_description' => '%title% Archive %sep% %sitename%',
	'pt_sector_title' => '%title% %sep% %sitename%', 'pt_sector_description' => '%excerpt%',
	'pt_sector_archive_title' => '%title% %sep% %sitename%', 'pt_sector_archive_description' => '%title% Archive %sep% %sitename%',
	'tax_category_title' => '%term% %sep% %sitename%', 'tax_category_description' => '%term_description%',
	'pt_page_add_meta_box' => 'on', 'pt_post_add_meta_box' => 'on',
) );
update_option( 'rank-math-options-titles', $t );
update_option( 'blogdescription', 'وكالة سيو' );

$made = array();
$page = function ( string $path, string $type = 'page' ) use ( &$made, $by, $sep ) {
	$parts  = array_values( array_filter( explode( '/', trim( $path, '/' ) ) ) );
	$slug   = end( $parts );
	$parent = 0;
	if ( 'page' === $type ) {
		$pp = '/';
		foreach ( array_slice( $parts, 0, -1 ) as $seg ) {
			$pp .= $seg . '/';
			$parent = $made[ $pp ] ?? 0;
		}
	}
	$v     = $by[ $path ] ?? array( 'title' => '', 'description' => '' );
	$title = $v['title'];
	$auto  = str_ends_with( $title, $sep );
	$name  = $auto ? substr( $title, 0, -strlen( $sep ) ) : ( $slug ? $slug : 'home' );
	$id    = wp_insert_post( array( 'post_type' => $type, 'post_status' => 'publish', 'post_title' => $name, 'post_name' => $slug, 'post_parent' => $parent, 'post_content' => 'نص الصفحة على الموقع الأساسي.' ) );
	if ( ! $auto && '' !== $title ) {
		update_post_meta( $id, 'rank_math_title', $title );
	}
	if ( '' !== $v['description'] ) {
		if ( 'case_study' === $type ) {
			wp_update_post( array( 'ID' => $id, 'post_excerpt' => $v['description'] ) ); // shown through the %excerpt% template
		} else {
			update_post_meta( $id, 'rank_math_description', $v['description'] );
		}
	}
	return $made[ $path ] = $id;
};

foreach ( $live as $v ) {
	$p = $v['path'];
	if ( 200 !== $v['code'] || str_starts_with( $p, '/blog/' ) || in_array( $p, array( '/results/', '/sectors/' ), true ) ) {
		continue;
	}
	if ( str_starts_with( $p, '/results/' ) ) {
		$page( $p, 'case_study' );
	} elseif ( str_starts_with( $p, '/sectors/' ) ) {
		$page( $p, 'sector' );
	} else {
		$existing = get_page_by_path( trim( $p, '/' ) );
		if ( $existing ) {
			wp_delete_post( $existing->ID, true );
		}
		$page( $p );
	}
}
// home: static front page; blog: posts page
update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $made['/'] );
$made['/blog/'] = $page( '/blog/' );
update_option( 'page_for_posts', $made['/blog/'] );
// articles: their own values
foreach ( get_posts( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => -1 ) ) as $post ) {
	$v = $by[ '/blog/' . urldecode( $post->post_name ) . '/' ] ?? null;
	if ( $v ) {
		str_ends_with( $v['title'], $sep ) ? delete_post_meta( $post->ID, 'rank_math_title' ) : update_post_meta( $post->ID, 'rank_math_title', $v['title'] );
		update_post_meta( $post->ID, 'rank_math_description', $v['description'] );
	}
}
// categories: template title, no description (as today)
foreach ( array( 'off-page-seo' => 'سيو خارجي', 'technical-seo' => 'سيو تقني' ) as $slug => $name ) {
	$term = get_term_by( 'slug', $slug, 'category' );
	wp_update_term( $term->term_id, 'category', array( 'name' => $name, 'description' => '' ) );
}
// the two archives /results/ and /sectors/ come from the custom types (has_archive) and their templates
// test-only cases
$ty = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'شكرًا', 'post_name' => 'thank-you', 'post_parent' => $made['/contact/'] ) ); // same last slug as /thank-you/ on /new/, different path
update_post_meta( $ty, 'rank_math_title', 'صفحة شكر في مسار آخر' );
update_post_meta( $made['/pricing/'], 'rank_math_robots', array( 'noindex', 'nofollow' ) );           // robots must not travel
update_post_meta( $made['/services/'], 'rank_math_canonical_url', 'https://example.com/canonical-main/' ); // canonical must not travel
flush_rewrite_rules();
echo count( $made ) . " pages/records\n";
