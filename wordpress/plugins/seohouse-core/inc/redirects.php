<?php
/**
 * Redirects for published URLs of the previous site (إعدادات سيو هاوس ← التحويلات).
 *
 * - A redirect applies only when the request would otherwise be a 404, so it never hides a
 *   published page that now lives on the same path.
 * - Targets are chosen pages (page_link), so they follow slug changes.
 * - Only equivalent destinations belong here (same topic and purpose); the list is reviewed by
 *   the site owner. `wp seohouse launch-check` verifies every published URL before launch.
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

/** Normalised site-relative path (works when WordPress lives in a folder such as /new/). */
function sh_redirect_path( string $url ): string {
	return sh_core_site_path( $url );
}

add_action(
	'template_redirect',
	static function () {
		if ( ! is_404() || ! function_exists( 'get_field' ) ) {
			return;
		}
		$req = sh_core_request_path();
		foreach ( (array) sh_core_option( 'sh_redirects', array() ) as $r ) {
			if ( empty( $r['from'] ) || empty( $r['to'] ) || sh_redirect_path( (string) $r['from'] ) !== $req ) {
				continue;
			}
			$to = is_numeric( $r['to'] ) ? get_permalink( (int) $r['to'] ) : (string) $r['to'];
			if ( $to && sh_redirect_path( $to ) !== $req ) {
				wp_safe_redirect( $to, 301, 'SEO House Core' );
				exit;
			}
		}
	},
	1
);

/*
 * Menu items pointing to content that is not published (the legal pages kept as drafts until
 * their text is approved) are left out for visitors, so no menu ever links to a 404. They show
 * again by themselves once the page is published. The menu editor still lists them.
 */
add_filter(
	'wp_get_nav_menu_items',
	static function ( $items ) {
		if ( is_admin() || ! is_array( $items ) ) {
			return $items;
		}
		$drop = array();
		foreach ( $items as $i => $item ) {
			if ( 'post_type' === ( $item->type ?? '' ) && (int) $item->object_id && 'publish' !== get_post_status( (int) $item->object_id ) ) {
				$drop[] = (int) $item->ID;
				unset( $items[ $i ] );
			}
		}
		if ( $drop ) {
			// children of a hidden item go with it
			foreach ( $items as $i => $item ) {
				if ( in_array( (int) $item->menu_item_parent, $drop, true ) ) {
					unset( $items[ $i ] );
				}
			}
		}
		return array_values( $items );
	},
	20
);

/* ------------------------------------------------------------------ redirects of the previous site that are missing */

/**
 * Redirects in the content pack (data/options.json → sh_redirects) whose old address has no entry
 * in «إعدادات سيو هاوس ← التحويلات». After a move, or when the settings were edited before the
 * redirect list existed, the old site's 301 can be missing and its address answers 404.
 *
 * @return array<int,array{from:string,route:string,note:string}>
 */
function sh_redirects_missing(): array {
	if ( ! class_exists( 'SH_Importer' ) || ! function_exists( 'get_field' ) ) {
		return array();
	}
	$opts = json_decode( (string) @file_get_contents( SH_Importer::default_dir() . '/data/options.json' ), true ); // phpcs:ignore
	$have = array();
	foreach ( (array) sh_core_option( 'sh_redirects', array() ) as $r ) {
		if ( ! empty( $r['from'] ) ) {
			$have[ sh_redirect_path( (string) $r['from'] ) ] = true;
		}
	}
	$out = array();
	foreach ( (array) ( $opts['sh_redirects'] ?? array() ) as $r ) {
		$from = (string) ( $r['from'] ?? '' );
		if ( '' !== $from && empty( $have[ sh_redirect_path( $from ) ] ) ) {
			$out[] = array( 'from' => $from, 'route' => (string) ( $r['to']['__route'] ?? '' ), 'note' => (string) ( $r['note'] ?? '' ) );
		}
	}
	return $out;
}

/** Adds the missing ones after the existing entries (which stay as they are). Returns the count added. */
function sh_redirects_add_missing(): int {
	$missing = sh_redirects_missing();
	if ( ! $missing ) {
		return 0;
	}
	$g   = json_decode( (string) @file_get_contents( SH_CORE_DIR . 'acf-json/group_sh_options.json' ), true ); // phpcs:ignore
	$key = '';
	$walk = static function ( array $fields ) use ( &$walk, &$key ) {
		foreach ( $fields as $f ) {
			if ( 'sh_redirects' === ( $f['name'] ?? '' ) ) {
				$key = $f['key'];
			}
			if ( ! empty( $f['sub_fields'] ) ) {
				$walk( $f['sub_fields'] );
			}
		}
	};
	$walk( (array) ( $g['fields'] ?? array() ) );
	if ( '' === $key ) {
		return 0;
	}
	$rows = array();
	foreach ( (array) sh_core_option( 'sh_redirects', array() ) as $r ) {
		$rows[] = array( 'from' => (string) ( $r['from'] ?? '' ), 'to' => is_numeric( $r['to'] ?? '' ) ? (int) $r['to'] : (string) ( $r['to'] ?? '' ), 'note' => (string) ( $r['note'] ?? '' ) );
	}
	update_option( 'sh_redirects_before_add', array( 'time' => time(), 'rows' => $rows ), false );
	$added = 0;
	foreach ( $missing as $m ) {
		$target = get_page_by_path( trim( $m['route'], '/' ) );
		if ( ! $target || 'publish' !== $target->post_status ) {
			continue; // only to a published page
		}
		$rows[] = array( 'from' => $m['from'], 'to' => (int) $target->ID, 'note' => $m['note'] );
		++$added;
	}
	if ( $added ) {
		update_field( $key, $rows, 'option' );
	}
	return $added;
}
