<?php
/**
 * Dynamic regions inside design sections: client logos, reviews slot, and
 * WordPress-driven lists (team, case studies, posts) used by several templates.
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

/**
 * Reviews shortcode for a page: the page's own override, otherwise the shared one
 * («سيو هاوس ← تقييمات جوجل»). Only a single registered shortcode is accepted
 * (e.g. [trustindex no-registration=google]); anything else counts as empty.
 */
function sh_reviews_code( int $post_id = 0 ): string {
	$post_id = $post_id ? $post_id : (int) get_queried_object_id();
	if ( function_exists( 'sh_core_reviews_for' ) ) {
		return sh_core_reviews_for( $post_id )['code']; // «سيو هاوس ← تقييمات جوجل»
	}
	return '';
}

/**
 * A live data source replaces the design example when connected.
 * Returns true when the design example must be skipped. Reviews never fall back to
 * example testimonials: without a shortcode the whole section is left out (sh_render_sections).
 */
function sh_dynamic_slot( string $name ): bool {
	if ( 'reviews-slot' === $name ) {
		static $done = array();
		$code = sh_reviews_code();
		$id   = (int) get_queried_object_id();
		if ( '' !== $code && empty( $done[ $id ] ) ) {
			$done[ $id ] = true; // one reviews widget per page
			echo '<div class="sh-reviews-live" data-reviews-live>' . do_shortcode( $code ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput -- output of a registered shortcode chosen by an editor.
			echo sh_google_reviews_button( $id ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
		}
		return true;
	}
	return false;
}

/** Client logos from "إعدادات سيو هاوس ← المحتوى المشترك". */
function sh_client_logos(): array {
	$rows = sh_option( 'sh_client_logos', array() );
	return is_array( $rows ) ? array_values( array_filter( $rows, static fn( $r ) => ! empty( $r['logo'] ) ) ) : array();
}

/**
 * Marquee track: the logo list twice (second copy hidden from assistive tech and focus)
 * so the loop is seamless, as in the design.
 */
function sh_logo_track( array $style ): void {
	$logos = sh_client_logos();
	foreach ( array( false, true ) as $dup ) {
		foreach ( $logos as $l ) {
			$img = sh_image(
				$l['logo'],
				// displayed at most 180×32 px (130×24 on small screens); below the hero, so low priority
				array( 'data-logo-img' => '', 'style' => $style['img'] ?? '', 'loading' => 'lazy', 'fetchpriority' => 'low', 'sizes' => '(max-width: 859px) 130px, 180px' ),
				(string) ( $l['name'] ?? '' ),
				'medium'
			);
			if ( $dup ) {
				$img = preg_replace( '/alt="[^"]*"/', 'alt=""', $img );
			}
			$url = ! empty( $l['url'] ) ? esc_url( $l['url'] ) : '';
			echo '<div aria-hidden="' . ( $dup ? 'true' : 'false' ) . '" data-logo-cell style="' . esc_attr( $style['cell'] ?? '' ) . '">';
			if ( $url && ! $dup ) {
				echo '<a href="' . $url . '" rel="noopener" target="_blank" style="display:contents">' . $img . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput
			} else {
				echo $img; // phpcs:ignore WordPress.Security.EscapeOutput -- wp_get_attachment_image output.
			}
			echo '</div>';
		}
	}
}

/* ------------------------------------------------------------------ records */

/** Team members in admin order; $home_only limits to members shown in the homepage photos. */
function sh_team_members( bool $home_only = false ): array {
	$args = array(
		'post_type'      => 'team_member',
		'post_status'    => 'publish',
		'posts_per_page' => 60,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
		'no_found_rows'  => true,
	);
	$posts = get_posts( $args );
	if ( $home_only && function_exists( 'get_field' ) ) {
		$posts = array_values( array_filter( $posts, static fn( $p ) => false !== get_field( 'home_photo', $p->ID ) && has_post_thumbnail( $p ) ) );
	}
	return $posts;
}

/** Case studies: the curated list from settings, otherwise the latest published. */
function sh_case_studies( int $limit = 0 ): array {
	$ids = (array) sh_option( 'sh_featured_cases', array() );
	if ( $limit && $ids ) {
		$posts = array_filter( array_map( 'get_post', $ids ), static fn( $p ) => $p && 'publish' === $p->post_status );
		return array_slice( array_values( $posts ), 0, $limit );
	}
	return get_posts(
		array(
			'post_type'      => 'case_study',
			'post_status'    => 'publish',
			'posts_per_page' => $limit ? $limit : 50,
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
			'no_found_rows'  => true,
		)
	);
}

/** Team member profile linked to a post (explicit field → author's linked profile → none). */
function sh_post_author_member( $post = null ): int {
	$post = get_post( $post );
	if ( ! $post ) {
		return 0;
	}
	$m = (int) sh_field( 'author_member', $post->ID, 0 );
	if ( $m ) {
		return $m;
	}
	$found = get_posts(
		array(
			'post_type'      => 'team_member',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => 'author', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'     => (int) $post->post_author, // phpcs:ignore WordPress.DB.SlowDBQuery
		)
	);
	return $found ? (int) $found[0] : 0;
}

/** Reading time in minutes (field override or ~200 Arabic words/minute). */
function sh_reading_minutes( $post = null ): int {
	$post = get_post( $post );
	$set  = (int) sh_field( 'reading', $post->ID, 0 );
	if ( $set ) {
		return $set;
	}
	$words = count( preg_split( '/\s+/u', trim( wp_strip_all_tags( $post->post_content ) ) ) );
	return max( 1, (int) round( $words / 200 ) );
}

/** "9 دقائق قراءة" with Arabic number agreement. */
function sh_reading_label( int $m ): string {
	if ( 1 === $m ) {
		return 'دقيقة قراءة';
	}
	if ( 2 === $m ) {
		return 'دقيقتان قراءة';
	}
	return $m . ( $m <= 10 ? ' دقائق قراءة' : ' دقيقة قراءة' );
}

function sh_post_date( $post = null, string $which = 'published' ): string {
	$post = get_post( $post );
	return 'modified' === $which ? get_the_modified_date( 'j F Y', $post ) : get_the_date( 'j F Y', $post );
}

function sh_primary_category( $post = null ): ?WP_Term {
	$cats = get_the_category( get_post( $post )->ID );
	return $cats ? $cats[0] : null;
}

/** Templates that print the booking section themselves (header CTA → #booking). */
add_filter( 'sh_page_has_booking', static fn( $has ) => $has || is_singular( array( 'team_member', 'post' ) ) );
