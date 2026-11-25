<?php
/**
 * Review links (2.9.16): a secret key in the address (?sh_preview=<key>) lets whoever has the link see
 * draft case studies and the results page as it will look after approval, without signing in, like
 * the «Public Post Preview» plugin. Only case studies; never indexed (X-Robots-Tag + robots meta).
 * Signed-in editors can use ?sh_preview=1. The key is made (or replaced, which ends the old links)
 * through POST seohouse/v1/preview-key (admin), and deleted with DELETE.
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

/** The request carries a valid review key, or comes from a signed-in editor asking for the preview. */
function sh_core_preview_ok(): bool {
	static $ok = null;
	if ( null !== $ok ) {
		return $ok;
	}
	$given = isset( $_GET['sh_preview'] ) ? sanitize_text_field( wp_unslash( (string) $_GET['sh_preview'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	if ( '' === $given ) {
		return $ok = false; // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments
	}
	if ( is_user_logged_in() && current_user_can( 'edit_others_pages' ) ) {
		return $ok = true; // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments
	}
	$key = (string) get_option( 'sh_preview_key', '' );
	return $ok = strlen( $key ) >= 24 && hash_equals( $key, $given ); // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments
}

/** Review address of a case study (draft or published) for the current preview, '' outside one. */
function sh_core_preview_link( WP_Post $p ): string {
	if ( ! sh_core_preview_ok() ) {
		return '';
	}
	$given = sanitize_text_field( wp_unslash( (string) $_GET['sh_preview'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
	$base  = 'publish' === $p->post_status ? (string) get_permalink( $p ) : add_query_arg( array( 'post_type' => $p->post_type, 'p' => $p->ID ), home_url( '/' ) );
	return add_query_arg( 'sh_preview', rawurlencode( $given ), $base );
}

// a draft case study opened with the key is shown as if published (in memory only; nothing is saved)
add_filter(
	'posts_results',
	static function ( $posts, $query ) {
		if ( is_admin() || ! $query->is_main_query() || 1 !== count( (array) $posts ) || ! sh_core_preview_ok() ) {
			return $posts;
		}
		$p = $posts[0];
		if ( 'case_study' === $p->post_type && in_array( $p->post_status, array( 'draft', 'pending' ), true ) ) {
			$p->post_status = 'publish';
			$p->sh_preview  = true;
		}
		return $posts;
	},
	10,
	2
);

add_action(
	'send_headers',
	static function () {
		if ( isset( $_GET['sh_preview'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			header( 'X-Robots-Tag: noindex, nofollow', true );
			header( 'Cache-Control: private, no-store', true );
		}
	}
);
add_filter(
	'wp_robots',
	static function ( $robots ) {
		return isset( $_GET['sh_preview'] ) ? array( 'noindex' => true, 'nofollow' => true ) : $robots; // phpcs:ignore WordPress.Security.NonceVerification
	},
	PHP_INT_MAX
);
add_filter(
	'rank_math/frontend/robots',
	static function ( $robots ) {
		return isset( $_GET['sh_preview'] ) ? array( 'index' => 'noindex', 'follow' => 'nofollow' ) : $robots; // phpcs:ignore WordPress.Security.NonceVerification
	},
	PHP_INT_MAX
);

add_action(
	'rest_api_init',
	static function () {
		register_rest_route(
			'seohouse/v1',
			'/preview-key',
			array(
				array(
					'methods'             => 'POST',
					'permission_callback' => static fn() => current_user_can( 'manage_options' ),
					'callback'            => static function () {
						$key = wp_generate_password( 32, false, false );
						update_option( 'sh_preview_key', $key, false );
						return rest_ensure_response( array( 'key' => $key ) );
					},
				),
				array(
					'methods'             => 'DELETE',
					'permission_callback' => static fn() => current_user_can( 'manage_options' ),
					'callback'            => static fn() => rest_ensure_response( array( 'deleted' => delete_option( 'sh_preview_key' ) ) ),
				),
			)
		);
	}
);
