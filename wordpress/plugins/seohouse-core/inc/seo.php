<?php
/**
 * SEO output: document title, meta description, canonical, robots, Open Graph.
 * One output source: when Rank Math, Yoast SEO, SEOPress or AIOSEO is active they own these
 * tags and Core outputs none of them (it only passes its field values to them where supported).
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

function sh_seo_plugin_active(): bool {
	return class_exists( 'RankMath' ) || defined( 'WPSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' ) || defined( 'AIOSEO_VERSION' );
}

/** Post whose SEO fields apply to the current view. */
function sh_seo_object_id(): int {
	if ( is_home() ) {
		return (int) get_option( 'page_for_posts' );
	}
	if ( is_front_page() ) {
		return (int) get_option( 'page_on_front' );
	}
	return is_singular() ? (int) get_queried_object_id() : 0;
}

function sh_seo_description(): string {
	$id = sh_seo_object_id();
	if ( $id ) {
		$d = sh_core_field( 'sh_seo_description', $id, '' );
		if ( $d ) {
			return sh_core_plain( $d );
		}
		$p = get_post( $id );
		if ( $p && 'case_study' === $p->post_type ) {
			return sh_core_plain( sh_core_field( 'summary', $id, '' ) );
		}
		if ( $p && 'team_member' === $p->post_type ) {
			$bio = sh_core_plain( sh_core_field( 'bio', $id, '' ) );
			if ( $bio ) {
				return wp_html_excerpt( $bio, 160, '…' );
			}
			$role = sh_core_plain( sh_core_field( 'role', $id, '' ) );
			return $role ? sprintf( '%s — %s، %s', get_the_title( $p ), $role, sh_core_option( 'sh_company_name', get_bloginfo( 'name' ) ) ) : '';
		}
		if ( $p && 'post' === $p->post_type ) {
			$intro = sh_core_field( 'intro', $id, '' );
			return sh_core_plain( $intro ? $intro : get_the_excerpt( $p ) );
		}
	}
	if ( is_category() ) {
		return sh_core_plain( category_description() );
	}
	return '';
}

function sh_seo_canonical(): string {
	if ( is_search() || is_404() ) {
		return '';
	}
	if ( is_front_page() ) {
		return home_url( '/' );
	}
	if ( is_home() ) {
		$paged = max( 1, (int) get_query_var( 'paged' ) );
		$base  = get_permalink( (int) get_option( 'page_for_posts' ) );
		return $paged > 1 ? trailingslashit( $base ) . user_trailingslashit( 'page/' . $paged, 'paged' ) : $base;
	}
	if ( is_category() ) {
		$paged = max( 1, (int) get_query_var( 'paged' ) );
		$base  = get_category_link( get_queried_object() );
		return $paged > 1 ? trailingslashit( $base ) . user_trailingslashit( 'page/' . $paged, 'paged' ) : $base;
	}
	if ( is_singular() ) {
		return (string) get_permalink( get_queried_object_id() );
	}
	return '';
}

function sh_seo_noindex(): bool {
	if ( is_search() || is_404() || is_author() || is_date() || is_tag() || is_attachment() ) {
		return true;
	}
	$id = sh_seo_object_id();
	if ( $id && sh_core_field( 'sh_seo_noindex', $id, false ) ) {
		return true;
	}
	if ( is_singular() && 'publish' !== get_post_status( get_queried_object_id() ) ) {
		return true;
	}
	return false;
}

add_action( 'plugins_loaded', 'sh_seo_register_output', 20 );

function sh_seo_register_output(): void {
if ( ! sh_seo_plugin_active() ) {

	add_filter(
		'pre_get_document_title',
		static function ( $title ) {
			$id = sh_seo_object_id();
			if ( $id ) {
				$t = sh_core_field( 'sh_seo_title', $id, '' );
				if ( $t ) {
					return wp_strip_all_tags( $t );
				}
			}
			return $title;
		}
	);
	add_filter( 'document_title_separator', static fn() => '|' );

	remove_action( 'wp_head', 'rel_canonical' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );

	add_filter(
		'wp_robots',
		static function ( $robots ) {
			if ( sh_seo_noindex() ) {
				unset( $robots['max-image-preview'] );
				$robots['noindex'] = true;
				$robots['follow']  = true;
			}
			// Staging/local environments are never indexable (settings are not copied to production).
			if ( 'production' !== wp_get_environment_type() ) {
				$robots['noindex']  = true;
				$robots['nofollow'] = true;
			}
			return $robots;
		}
	);

	add_action(
		'wp_head',
		static function () {
			$desc  = sh_seo_description();
			$canon = sh_seo_canonical();
			if ( $desc ) {
				printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
			}
			if ( $canon ) {
				printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $canon ) );
			}
			if ( sh_seo_noindex() ) {
				return; // no social preview tags for non-indexable views
			}
			$id    = sh_seo_object_id();
			$title = wp_get_document_title();
			$img   = $id ? (int) sh_core_field( 'sh_seo_og_image', $id, 0 ) : 0;
			if ( ! $img && $id && has_post_thumbnail( $id ) ) {
				$img = (int) get_post_thumbnail_id( $id );
			}
			$src = $img ? wp_get_attachment_image_src( $img, 'large' ) : false;
			echo '<meta property="og:locale" content="ar_AR">' . "\n";
			printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( sh_core_option( 'sh_company_name', get_bloginfo( 'name' ) ) ) );
			printf( '<meta property="og:type" content="%s">' . "\n", is_singular( 'post' ) ? 'article' : 'website' );
			printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
			if ( $desc ) {
				printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $desc ) );
			}
			if ( $canon ) {
				printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $canon ) );
			}
			if ( $src ) {
				printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $src[0] ) );
				printf( '<meta property="og:image:width" content="%d"><meta property="og:image:height" content="%d">' . "\n", (int) $src[1], (int) $src[2] );
			}
			echo '<meta name="twitter:card" content="' . ( $src ? 'summary_large_image' : 'summary' ) . '">' . "\n";
			$gsc = sh_core_option( 'sh_gsc_verification', '' );
			if ( $gsc ) {
				printf( '<meta name="google-site-verification" content="%s">' . "\n", esc_attr( $gsc ) );
			}
		},
		3
	);
} else {
	// Pass Core's fields to Rank Math / Yoast so editors keep one place to edit.
	$title_cb = static function ( $title ) {
		$id = sh_seo_object_id();
		$t  = $id ? sh_core_field( 'sh_seo_title', $id, '' ) : '';
		return $t ? wp_strip_all_tags( $t ) : $title;
	};
	$desc_cb  = static function ( $d ) {
		$x = sh_seo_description();
		return $x ? $x : $d;
	};
	add_filter( 'rank_math/frontend/title', $title_cb );
	add_filter( 'rank_math/frontend/description', $desc_cb );
	add_filter( 'wpseo_title', $title_cb );
	add_filter( 'wpseo_metadesc', $desc_cb );
}
}

/** Real 404 status for unknown URLs is WordPress default; search uses the "s" parameter only. */
add_action(
	'template_redirect',
	static function () {
		// /search/ is not a real route in the project map.
		if ( ! is_admin() && preg_match( '#^/search/?$#', (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ) ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			global $wp_query;
			$wp_query->set_404();
			status_header( 404 );
		}
	},
	1
);
