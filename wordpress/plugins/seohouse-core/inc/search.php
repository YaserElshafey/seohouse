<?php
/**
 * Site search over ACF content: design pages keep their text in section and list fields,
 * so a plain-text copy is kept in one meta key (_sh_search_text) and included in the "s" query.
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

/** Collect visible text of a post's fields. */
function sh_search_text( int $post_id ): string {
	if ( ! function_exists( 'get_fields' ) ) {
		return '';
	}
	$parts = array();
	$walk  = static function ( $v, $k = '' ) use ( &$walk, &$parts ) {
		if ( is_array( $v ) ) {
			if ( ! empty( $v['sh_hide'] ) ) {
				return; // hidden sections are not searchable
			}
			foreach ( $v as $kk => $x ) {
				$walk( $x, (string) $kk );
			}
			return;
		}
		if ( ! is_string( $v ) || '' === trim( $v ) ) {
			return;
		}
		if ( in_array( $k, array( 'acf_fc_layout', 'variant', 'icon', 'logo', 'sh_anchor', 'review_notes', 'filter' ), true ) || preg_match( '#^(https?:|/|\#)#', $v ) ) {
			return;
		}
		$parts[] = wp_strip_all_tags( $v );
	};
	$walk( (array) get_fields( $post_id ) );
	return trim( preg_replace( '/\s+/u', ' ', implode( ' ', $parts ) ) );
}

function sh_search_index( int $post_id ): void {
	$type = get_post_type( $post_id );
	if ( ! in_array( $type, array( 'page', 'case_study', 'team_member' ), true ) ) {
		return;
	}
	update_post_meta( $post_id, '_sh_search_text', sh_search_text( $post_id ) );
}

add_action(
	'acf/save_post',
	static function ( $post_id ) {
		if ( is_numeric( $post_id ) ) {
			sh_search_index( (int) $post_id );
		}
	},
	20
);

/** Include the indexed text in front-end searches. */
add_filter(
	'posts_search',
	static function ( $search, WP_Query $q ) {
		if ( is_admin() || ! $q->is_main_query() || ! $q->is_search() ) {
			return $search;
		}
		global $wpdb;
		$terms = (array) $q->get( 'search_terms' );
		if ( ! $terms ) {
			return $search;
		}
		$and = array();
		foreach ( $terms as $t ) {
			$like  = '%' . $wpdb->esc_like( $t ) . '%';
			$and[] = $wpdb->prepare(
				"({$wpdb->posts}.post_title LIKE %s OR {$wpdb->posts}.post_excerpt LIKE %s OR {$wpdb->posts}.post_content LIKE %s OR EXISTS (SELECT 1 FROM {$wpdb->postmeta} shm WHERE shm.post_id = {$wpdb->posts}.ID AND shm.meta_key = '_sh_search_text' AND shm.meta_value LIKE %s))",
				$like,
				$like,
				$like,
				$like
			);
		}
		return ' AND (' . implode( ' AND ', $and ) . ') ';
	},
	10,
	2
);

/** Search results: public content types only. */
add_action(
	'pre_get_posts',
	static function ( WP_Query $q ) {
		if ( ! is_admin() && $q->is_main_query() && $q->is_search() ) {
			$q->set( 'post_type', array( 'page', 'post', 'case_study', 'team_member' ) );
			$q->set( 'posts_per_page', 12 );
		}
	}
);
