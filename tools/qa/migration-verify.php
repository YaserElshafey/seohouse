<?php
/**
 * Checks the article migration field by field: every published post of the main site against its
 * copy here (title, content, slug, dates, author, categories, featured image + alt, images in the
 * content, Rank Math fields), plus backups and the live URL. Read-only on both sides.
 * Usage: wp --path=<this site> eval-file migration-verify.php [out.json]
 */
$src = SH_Post_Migration::find_source();
[ $h ] = array( $src['host'] );
$db  = new mysqli( $h, $src['user'], $src['pass'], $src['name'] );
$db->set_charset( 'utf8mb4' );
$db->query( 'SET SESSION TRANSACTION READ ONLY' );
$P   = $src['prefix'];
$q   = static fn( $sql ) => $db->query( $sql )->fetch_all( MYSQLI_ASSOC );
$m   = new SH_Post_Migration();
$m->connect();
$res = array();
$ok  = static function ( $name, $pass, $detail = '' ) use ( &$res ) {
	$res[] = array( 'ok' => (bool) $pass, 'name' => $name, 'detail' => $detail );
	echo ( $pass ? 'PASS' : 'FAIL' ) . "  $name" . ( $detail ? "  — $detail" : '' ) . "\n";
};
$posts = $q( "SELECT * FROM {$P}posts WHERE post_type='post' AND post_status='publish'" );
$ok( 'all published posts of the main site found (source count from its posts table)', count( $posts ) > 0, count( $posts ) . ' posts' );
$here = get_posts( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => -1 ) );
$ok( 'published articles here = published articles on the main site', count( $here ) === count( $posts ), count( $here ) . ' / ' . count( $posts ) );
foreach ( $posts as $s ) {
	$sid  = (int) $s['ID'];
	$slug = urldecode( $s['post_name'] );
	$t    = get_posts( array( 'post_type' => 'post', 'post_status' => 'any', 'meta_key' => '_sh_source_post', 'meta_value' => (string) $sid, 'posts_per_page' => 2 ) );
	$ok( "[$slug] exactly one copy", 1 === count( $t ), count( $t ) . ' found' );
	if ( ! $t ) {
		continue;
	}
	$p = $t[0];
	$ok( "[$slug] title", $p->post_title === $s['post_title'] );
	$ok( "[$slug] slug and status", $p->post_name === $s['post_name'] && 'publish' === $p->post_status );
	$ok( "[$slug] dates (published, modified)", $p->post_date === $s['post_date'] && $p->post_date_gmt === $s['post_date_gmt'] && $p->post_modified_gmt === $s['post_modified_gmt'], $p->post_date . ' / ' . $p->post_modified );
	$ok( "[$slug] content identical apart from the site address", preg_replace( '/wp-image-\d+/', 'wp-image-N', $p->post_content ) === preg_replace( '/wp-image-\d+/', 'wp-image-N', $m->rewrite( $s['post_content'] ) ) && strlen( $p->post_content ) > 1000, strlen( $p->post_content ) . ' bytes, ' . substr_count( $p->post_content, '<h2' ) . ' H2, ' . substr_count( $p->post_content, '<table' ) . ' tables, ' . substr_count( $p->post_content, '<img' ) . ' images' );
	$ok( "[$slug] no main-site address left in the content", false === strpos( $p->post_content, '//seohouse.agency/wp-content' ) && ! preg_match( '~https?://seohouse\.agency/(?!new/)~', $p->post_content ) );
	$au = $q( "SELECT u.user_login, u.display_name FROM {$P}users u WHERE u.ID=" . (int) $s['post_author'] )[0];
	$ok( "[$slug] author", get_the_author_meta( 'display_name', $p->post_author ) === $au['display_name'], $au['display_name'] );
	$sc = array_column( $q( "SELECT t.slug FROM {$P}term_relationships r JOIN {$P}term_taxonomy x ON x.term_taxonomy_id=r.term_taxonomy_id JOIN {$P}terms t ON t.term_id=x.term_id WHERE x.taxonomy='category' AND r.object_id=$sid" ), 'slug' );
	$tc = wp_get_post_categories( $p->ID, array( 'fields' => 'slugs' ) );
	sort( $sc ); sort( $tc );
	$ok( "[$slug] categories", $sc === $tc, implode( ',', array_map( 'urldecode', $tc ) ) );
	$sthumb = $q( "SELECT meta_value FROM {$P}postmeta WHERE post_id=$sid AND meta_key='_thumbnail_id'" );
	if ( $sthumb ) {
		$sa    = (int) $sthumb[0]['meta_value'];
		$salt  = $q( "SELECT meta_value FROM {$P}postmeta WHERE post_id=$sa AND meta_key='_wp_image_alt' OR (post_id=$sa AND meta_key='_wp_attachment_image_alt')" )[0]['meta_value'] ?? '';
		$sfile = $q( "SELECT meta_value FROM {$P}postmeta WHERE post_id=$sa AND meta_key='_wp_attached_file'" )[0]['meta_value'];
		$th    = (int) get_post_thumbnail_id( $p );
		$ok( "[$slug] featured image: same file, alt text, file present", $th && get_post_meta( $th, '_wp_attached_file', true ) === $sfile && get_post_meta( $th, '_wp_attachment_image_alt', true ) === $salt && file_exists( get_attached_file( $th ) ), $sfile . ' — alt: ' . $salt );
		$meta = wp_get_attachment_metadata( $th );
		$miss = array();
		foreach ( (array) ( $meta['sizes'] ?? array() ) as $sz ) {
			if ( ! file_exists( dirname( get_attached_file( $th ) ) . '/' . $sz['file'] ) ) {
				$miss[] = $sz['file'];
			}
		}
		$ok( "[$slug] featured image: every size file present", ! $miss, count( (array) ( $meta['sizes'] ?? array() ) ) . ' sizes' . ( $miss ? ' missing ' . implode( ',', $miss ) : '' ) );
	}
	preg_match_all( '/<img[^>]+src="([^"]+)"[^>]*>/', $p->post_content, $im );
	$bad = array();
	foreach ( $im[1] as $u ) {
		$path = str_replace( wp_upload_dir()['baseurl'], wp_upload_dir()['basedir'], $u );
		if ( ! file_exists( urldecode( $path ) ) && ! file_exists( $path ) ) {
			$bad[] = $u;
		}
	}
	preg_match_all( '/<img[^>]*>/', $p->post_content, $tags );
	$noalt = array_filter( $tags[0], static fn( $x ) => ! preg_match( '/alt="[^"]+"/', $x ) );
	$ok( "[$slug] images inside the article: files here, alt kept", ! $bad, count( $im[1] ) . ' images' . ( $bad ? ' missing: ' . implode( ' ', $bad ) : '' ) . ( $noalt ? ' (' . count( $noalt ) . ' without alt on the main site too)' : '' ) );
	$srm  = array();
	foreach ( $q( "SELECT meta_key, meta_value FROM {$P}postmeta WHERE post_id=$sid AND meta_key LIKE 'rank\\_math\\_%'" ) as $r ) {
		$srm[ $r['meta_key'] ] = $r['meta_value'];
	}
	$diff = array();
	foreach ( $srm as $k => $v ) {
		$here_v = get_post_meta( $p->ID, $k, true );
		if ( in_array( $k, array( 'rank_math_facebook_image_id', 'rank_math_primary_category' ), true ) ) {
			continue;
		}
		$want = $m->rewrite( is_serialized( $v ) ? wp_json_encode( maybe_unserialize( $v ) ) : $v );
		$have = is_array( $here_v ) ? wp_json_encode( $here_v ) : (string) $here_v;
		if ( $want !== $have ) {
			$diff[] = $k;
		}
	}
	$pc = get_post_meta( $p->ID, 'rank_math_primary_category', true );
	$ok( "[$slug] Rank Math fields (" . count( $srm ) . ')', ! $diff && ( ! isset( $srm['rank_math_primary_category'] ) || ( $pc && get_term( (int) $pc ) ) ), $diff ? 'differ: ' . implode( ',', $diff ) : implode( ', ', array_keys( $srm ) ) );
	$code = wp_remote_retrieve_response_code( wp_remote_get( get_permalink( $p ), array( 'timeout' => 20 ) ) );
	$ok( "[$slug] URL answers 200", 200 === (int) $code, wp_make_link_relative( get_permalink( $p ) ) );
	$ok( "[$slug] same path as on the main site (after /new)", wp_make_link_relative( get_permalink( $p ) ) === '/new/blog/' . $s['post_name'] . '/' );
	$bk = get_post_meta( $p->ID, '_sh_pre_migration', true );
	if ( $bk ) {
		$ok( "[$slug] backup of the replaced draft kept", ! empty( $bk['post_content'] ) && ! empty( $bk['post_title'] ), $bk['post_status'] . ' — ' . $bk['post_title'] );
	}
}
$files = glob( wp_upload_dir()['basedir'] . '/seohouse-backups/drafts-before-migration-*.json' );
$ok( 'backup file of replaced drafts (not web-readable)', $files && file_exists( dirname( $files[0] ) . '/.htaccess' ), $files ? basename( $files[0] ) : '' );
file_put_contents( $args[0] ?? '/dev/null', wp_json_encode( $res, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
$f = count( array_filter( $res, static fn( $r ) => ! $r['ok'] ) );
echo "\n" . ( count( $res ) - $f ) . '/' . count( $res ) . " passed\n";
