<?php
// Dumps all content the owner could have edited: posts, post meta, terms, options.
// Usage: wp eval-file snapshot.php <out.json>
global $wpdb;
$out   = $args[0];
$skip  = '/^(_transient|_site_transient|cron$|rewrite_rules$|recently_activated$|rank_math_.*cache|.*_last_run$|can_compress_scripts$|seohouse_.*(log|notice|check).*|_sh_.*cache.*|recovery_keys$|uninstall_plugins$|active_plugins$|template$|stylesheet$|current_theme$|theme_mods_.*|.*version.*|.*_db_ver.*|db_upgraded$|auto_update.*|.*_installed.*)/';
$data  = array( 'posts' => array(), 'meta' => array(), 'terms' => array(), 'options' => array() );
foreach ( $wpdb->get_results( "SELECT ID,post_type,post_status,post_name,post_title,post_content,post_excerpt,post_parent,menu_order FROM {$wpdb->posts} WHERE post_type NOT IN ('revision','customize_changeset') ORDER BY ID", ARRAY_A ) as $p ) {
	$data['posts'][ $p['ID'] ] = $p;
}
foreach ( $wpdb->get_results( "SELECT post_id,meta_key,meta_value FROM {$wpdb->postmeta} WHERE meta_key NOT IN ('_edit_lock','_edit_last') ORDER BY meta_id", ARRAY_A ) as $m ) {
	$data['meta'][ $m['post_id'] . '|' . $m['meta_key'] ][] = $m['meta_value'];
}
foreach ( $wpdb->get_results( "SELECT t.term_id,t.name,t.slug,tt.taxonomy,tt.description FROM {$wpdb->terms} t JOIN {$wpdb->term_taxonomy} tt USING(term_id) ORDER BY t.term_id", ARRAY_A ) as $t ) {
	$data['terms'][ $t['term_id'] ] = $t;
}
foreach ( $wpdb->get_results( "SELECT option_name,option_value FROM {$wpdb->options} ORDER BY option_name", ARRAY_A ) as $o ) {
	if ( ! preg_match( $skip, $o['option_name'] ) ) {
		$data['options'][ $o['option_name'] ] = $o['option_value'];
	}
}
file_put_contents( $out, wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
WP_CLI::success( sprintf( '%d posts, %d meta, %d terms, %d options → %s', count( $data['posts'] ), count( $data['meta'] ), count( $data['terms'] ), count( $data['options'] ), $out ) );
