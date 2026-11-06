<?php
/**
 * Writers are team members (2.8.0).
 *
 * A WordPress account that writes articles is linked to its team profile in «فريق العمل ← العضو ←
 * حساب الكاتب في ووردبريس» (field `author`). An article may also name its writer directly in «خيارات
 * المقال ← الكاتب من فريق العمل» (field `author_member`), which wins over the account.
 *
 * - Every author link WordPress or a plugin builds (get_author_posts_url: Rank Math, feeds, themes) points
 *   to the linked team profile.
 * - Author archives (/author/{login}/ and ?author=N) are not pages of this site: they redirect (301) to the
 *   team profile, or to the blog when the account has no profile. This also stops the archives from
 *   revealing account logins.
 * - On the front end the writer's name is the profile's name (Rank Math's «Written by», feeds).
 * - The public REST list of accounts (/wp/v2/users) is closed to visitors who are not logged in; the
 *   editor (logged in) keeps it.
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

/** Published, public team profile linked to a WordPress account (0 when none). */
function sh_core_member_for_user( int $user_id ): int {
	static $cache = array();
	if ( $user_id <= 0 ) {
		return 0;
	}
	if ( ! isset( $cache[ $user_id ] ) ) {
		$found              = get_posts(
			array(
				'post_type'        => 'team_member',
				'post_status'      => 'publish',
				'posts_per_page'   => 1,
				'fields'           => 'ids',
				'has_password'     => false,
				'orderby'          => array(
					'menu_order' => 'ASC',
					'ID'         => 'ASC',
				),
				'meta_key'         => 'author', // phpcs:ignore WordPress.DB.SlowDBQuery
				'meta_value'       => $user_id, // phpcs:ignore WordPress.DB.SlowDBQuery
				'suppress_filters' => false,
			)
		);
		$cache[ $user_id ] = $found ? (int) $found[0] : 0;
	}
	return $cache[ $user_id ];
}

/** Published, public team profile credited for an article: its own field first, then its account's profile. */
function sh_core_member_for_post( $post = null ): int {
	$post = get_post( $post );
	if ( ! $post ) {
		return 0;
	}
	$m = (int) sh_core_field( 'author_member', $post->ID, 0 );
	if ( $m ) {
		return sh_core_member_is_public( $m ) ? $m : 0;
	}
	return sh_core_member_for_user( (int) $post->post_author );
}

function sh_core_member_is_public( int $member_id ): bool {
	return $member_id > 0 && 'team_member' === get_post_type( $member_id ) && 'publish' === get_post_status( $member_id ) && '' === (string) get_post_field( 'post_password', $member_id );
}

// Author links → team profile.
add_filter(
	'author_link',
	static function ( $link, $author_id ) {
		$member = sh_core_member_for_user( (int) $author_id );
		return $member ? (string) get_permalink( $member ) : $link;
	},
	10,
	2
);

// Author archives → team profile (or the blog). Runs before canonical redirects (which would turn
// ?author=N into /author/{login}/).
add_action(
	'template_redirect',
	static function () {
		if ( ! is_author() ) {
			return;
		}
		if ( is_feed() ) {
			wp_safe_redirect( get_feed_link(), 301, 'SEO House' ); // the site's feed; author feeds are not kept
			exit;
		}
		$user   = get_queried_object();
		$member = $user instanceof WP_User ? sh_core_member_for_user( (int) $user->ID ) : 0;
		if ( $member ) {
			$to = (string) get_permalink( $member );
		} else {
			$blog = (int) get_option( 'page_for_posts' );
			$page = $blog ? get_post( $blog ) : get_page_by_path( 'blog' );
			$to   = $page && 'publish' === $page->post_status ? (string) get_permalink( $page ) : home_url( '/' );
		}
		wp_safe_redirect( $to, 301, 'SEO House' );
		exit;
	},
	1
);

// Writer's name on the front end (Rank Math «Written by», feeds): the credited profile's name.
add_filter(
	'the_author',
	static function ( $name ) {
		if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return $name;
		}
		$post = get_post();
		if ( ! $post || 'post' !== $post->post_type ) {
			return $name;
		}
		$member = sh_core_member_for_post( $post );
		return $member ? get_the_title( $member ) : $name;
	}
);

// «حساب الكاتب في ووردبريس» decides whose articles a profile signs: only users who may edit others'
// posts (editors, administrators) see and change it, so an author cannot claim another writer's articles.
add_filter(
	'acf/prepare_field/name=author',
	static function ( $field ) {
		return 'team_member' === get_post_type() && ! current_user_can( 'edit_others_posts' ) ? false : $field;
	}
);
add_filter(
	'acf/update_value/name=author',
	static function ( $value, $post_id ) {
		if ( 'team_member' === get_post_type( $post_id ) && ! current_user_can( 'edit_others_posts' ) ) {
			return get_post_meta( (int) $post_id, 'author', true ); // keep what it was
		}
		return $value;
	},
	10,
	2
);

// Account list over REST: logged-in users only (the block editor needs it; visitors do not).
add_filter(
	'rest_endpoints',
	static function ( $endpoints ) {
		if ( is_user_logged_in() ) {
			return $endpoints;
		}
		foreach ( array_keys( $endpoints ) as $route ) {
			if ( 0 === strpos( $route, '/wp/v2/users' ) ) {
				unset( $endpoints[ $route ] );
			}
		}
		return $endpoints;
	}
);
