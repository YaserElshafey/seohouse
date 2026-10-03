<?php
/**
 * Test fixture only: fills a local copy of the main site (wp eval-file, run on that copy) from
 * live-posts.json written by fetch-live-posts.py. IDs of posts and images are kept as on the live
 * site so image classes in the content (wp-image-<id>) stay valid, like in the real database.
 * Usage: wp --path=<main-site-copy> eval-file build-source.php <live-posts.json>
 */
$d = json_decode( file_get_contents( $args[0] ), true );

/**
 * The REST API returns rendered HTML; the database holds blocks. Rank Math's FAQ block is put
 * back in its stored form (block comment with its questions) so Rank Math builds its FAQPage
 * schema from it, as on the main site.
 */
function sh_fixture_raw( string $html ): string {
	return preg_replace_callback(
		'~<div id="rank-math-faq" class="rank-math-block">.*?</div>\s*</div>\s*</div>\s*</div>~s',
		static function ( $m ) {
			preg_match_all( '~<div id="(faq-question-\d+)" class="rank-math-list-item">\s*<h3 class="rank-math-question ">(.*?)</h3>\s*<div class="rank-math-answer ">(.*?)</div>\s*</div>~s', $m[0], $q, PREG_SET_ORDER );
			$questions = array();
			foreach ( $q as $x ) {
				$questions[] = array( 'id' => $x[1], 'title' => trim( $x[2] ), 'content' => trim( $x[3] ), 'visible' => true );
			}
			return '<!-- wp:rank-math/faq-block ' . wp_json_encode( array( 'questions' => $questions ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . ' -->' . "\n" . $m[0] . "\n" . '<!-- /wp:rank-math/faq-block -->';
		},
		$html
	);
}
global $wpdb;
$users = array();
foreach ( $d['users'] as $u ) {
	if ( 1 === (int) $u['id'] ) {
		$wpdb->update( $wpdb->users, array( 'user_login' => $u['slug'], 'user_nicename' => $u['slug'], 'display_name' => $u['name'], 'user_email' => $u['slug'] . '@example.com' ), array( 'ID' => 1 ) );
		clean_user_cache( 1 );
		$users[1] = 1;
		continue;
	}
	$id = username_exists( $u['slug'] ) ?: wp_insert_user( array( 'user_login' => $u['slug'], 'user_nicename' => $u['slug'], 'display_name' => $u['name'], 'user_email' => $u['slug'] . '@example.com', 'user_pass' => wp_generate_password(), 'role' => 'author', 'description' => $u['description'] ?? '' ) );
	$users[ (int) $u['id'] ] = (int) $id;
}
$terms = array();
foreach ( array( 'category' => $d['categories'], 'post_tag' => $d['tags'] ) as $tax => $list ) {
	foreach ( $list as $t ) {
		$e = get_term_by( 'slug', $t['slug'], $tax );
		$terms[ (int) $t['id'] ] = $e ? (int) $e->term_id : (int) wp_insert_term( $t['name'], $tax, array( 'slug' => $t['slug'], 'description' => $t['description'] ) )['term_id'];
	}
}
foreach ( $d['media'] as $m ) {
	if ( get_post( $m['id'] ) ) {
		continue;
	}
	$md   = $m['media_details'];
	$meta = array( 'width' => $md['width'], 'height' => $md['height'], 'file' => $md['file'], 'filesize' => $md['filesize'] ?? 0, 'sizes' => array(), 'image_meta' => $md['image_meta'] ?? array() );
	foreach ( (array) ( $md['sizes'] ?? array() ) as $name => $s ) {
		if ( 'full' !== $name ) {
			$meta['sizes'][ $name ] = array( 'file' => $s['file'], 'width' => $s['width'], 'height' => $s['height'], 'mime-type' => $s['mime_type'] );
		}
	}
	if ( ! empty( $md['original_image'] ) ) {
		$meta['original_image'] = $md['original_image'];
	}
	$id = wp_insert_post( array( 'import_id' => $m['id'], 'post_type' => 'attachment', 'post_status' => 'inherit', 'post_title' => html_entity_decode( $m['title']['rendered'] ), 'post_excerpt' => wp_strip_all_tags( $m['caption']['rendered'] ), 'post_mime_type' => $m['mime_type'], 'post_date' => $m['date'], 'post_date_gmt' => $m['date_gmt'], 'post_author' => $users[ (int) $m['author'] ] ?? 1, 'guid' => $m['source_url'] ) );
	update_post_meta( $id, '_wp_attached_file', $md['file'] );
	update_post_meta( $id, '_wp_attachment_metadata', $meta );
	update_post_meta( $id, '_wp_attachment_image_alt', $m['alt_text'] );
}
foreach ( $d['posts'] as $p ) {
	if ( get_post( $p['id'] ) ) {
		// refresh the content of an existing copy (raw form, as stored in the database)
		$wpdb->update( $wpdb->posts, array( 'post_content' => sh_fixture_raw( $p['content']['rendered'] ) ), array( 'ID' => (int) $p['id'] ) );
		delete_post_meta( (int) $p['id'], 'rank_math_schema_FAQPage' );
		clean_post_cache( (int) $p['id'] );
		continue;
	}
	kses_remove_filters();
	$id = wp_insert_post( array( 'import_id' => $p['id'], 'post_type' => 'post', 'post_status' => 'publish', 'post_title' => html_entity_decode( $p['title']['rendered'], ENT_QUOTES ), 'post_name' => $p['slug'], 'post_content' => sh_fixture_raw( $p['content']['rendered'] ), 'post_date' => $p['date'], 'post_date_gmt' => $p['date_gmt'], 'post_author' => $users[ (int) $p['author'] ] ?? 1, 'post_category' => array_map( static fn( $c ) => $terms[ $c ], $p['categories'] ) ) );
	$wpdb->update( $wpdb->posts, array( 'post_modified' => $p['modified'], 'post_modified_gmt' => $p['modified_gmt'], 'post_name' => $p['slug'] ), array( 'ID' => $id ) );
	if ( $p['featured_media'] ) {
		update_post_meta( $id, '_thumbnail_id', $p['featured_media'] );
	}
	$rm = $p['_rank_math'];
	update_post_meta( $id, 'rank_math_title', $rm['title'] );
	update_post_meta( $id, 'rank_math_description', $rm['description'] );
	update_post_meta( $id, 'rank_math_robots', array_values( array_intersect( array_map( 'trim', explode( ',', $rm['robots'] ) ), array( 'index', 'noindex', 'follow', 'nofollow' ) ) ) );
	update_post_meta( $id, 'rank_math_primary_category', (string) $terms[ $p['categories'][0] ] );
	if ( $rm['og_image'] ) {
		update_post_meta( $id, 'rank_math_facebook_image', $rm['og_image'] );
		update_post_meta( $id, 'rank_math_facebook_image_id', (string) $p['featured_media'] );
	}
}
// Rank Math's site entity as the main site shows it (Organization node of its schema)
foreach ( $d['posts'][0]['_rank_math']['schema'] as $ld ) {
	foreach ( (array) ( $ld['@graph'] ?? array() ) as $node ) {
		if ( 'Organization' === ( $node['@type'] ?? '' ) ) {
			$t = get_option( 'rank-math-options-titles', array() );
			$t = is_array( $t ) ? $t : array();
			$t['knowledgegraph_type'] = 'company';
			$t['knowledgegraph_name'] = $node['name'] ?? 'سيو هاوس';
			if ( ! empty( $node['logo']['url'] ) ) {
				$t['knowledgegraph_logo'] = $node['logo']['url'];
			}
			if ( ! empty( $node['sameAs'] ) ) {
				$t['social_additional_profiles'] = implode( "\n", (array) $node['sameAs'] );
			}
			update_option( 'rank-math-options-titles', $t );
		}
	}
}
// a draft and a page on the main site must not be migrated
wp_insert_post( array( 'post_type' => 'post', 'post_status' => 'draft', 'post_title' => 'مسودة في الموقع الأساسي (لا تُنقل)', 'post_name' => 'main-site-draft' ) );
echo 'source: ' . wp_count_posts()->publish . " published posts\n";
