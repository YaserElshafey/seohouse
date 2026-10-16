<?php
/**
 * Breadcrumb trail: one source for the visible breadcrumb (theme) and BreadcrumbList schema.
 * Labels: "sh_crumb" (label as current page) and "sh_crumb_ancestor" (label when shown as a parent).
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

function sh_crumb_label( $post, bool $as_ancestor = false ): string {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}
	if ( 'case_study' === $post->post_type ) {
		$c = sh_core_field( 'crumb', $post->ID, '' );
		return $c ? $c : get_the_title( $post );
	}
	if ( $as_ancestor ) {
		$a = sh_core_field( 'sh_crumb_ancestor', $post->ID, '' );
		if ( $a ) {
			return $a;
		}
	}
	$c = sh_core_field( 'sh_crumb', $post->ID, '' );
	return $c ? $c : get_the_title( $post );
}

/**
 * @return array<int,array{label:string,url:string}> Last item is the current page (url = its permalink).
 */
function sh_breadcrumb_trail(): array {
	static $trail = null;
	if ( null !== $trail ) {
		return $trail;
	}
	$home  = array( 'label' => __( 'الرئيسية', 'seohouse-core' ), 'url' => home_url( '/' ) );
	$trail = array( $home );

	$page_crumb = static function ( $id, $ancestor ) {
		return array( 'label' => sh_crumb_label( $id, $ancestor ), 'url' => get_permalink( $id ) );
	};
	$by_path = static function ( $path ) {
		$p = get_page_by_path( trim( $path, '/' ) );
		return $p ? $p->ID : 0;
	};

	if ( is_front_page() ) {
		$trail = array();
	} elseif ( is_singular( 'page' ) ) {
		$id = get_queried_object_id();
		// The approved design shows the nearest parent only (e.g. خدمات السيو › السيو التقني).
		$depth = (int) apply_filters( 'sh_breadcrumb_ancestor_depth', 1 );
		foreach ( array_reverse( array_slice( get_post_ancestors( $id ), 0, max( 0, $depth ) ) ) as $a ) {
			$trail[] = $page_crumb( $a, true );
		}
		$trail[] = $page_crumb( $id, false );
	} elseif ( is_singular( 'case_study' ) || is_singular( 'team_member' ) ) {
		$parent = $by_path( is_singular( 'case_study' ) ? 'results' : 'team' );
		if ( $parent ) {
			$trail[] = $page_crumb( $parent, true );
		}
		$trail[] = array( 'label' => sh_crumb_label( get_queried_object_id() ), 'url' => get_permalink() );
	} elseif ( is_singular( 'post' ) || is_home() || is_category() ) {
		$blog = (int) get_option( 'page_for_posts' );
		if ( $blog ) {
			$trail[] = $page_crumb( $blog, ! is_home() );
		}
		if ( is_singular( 'post' ) ) {
			$cats = get_the_category();
			if ( $cats ) {
				$trail[] = array( 'label' => $cats[0]->name, 'url' => get_category_link( $cats[0] ) );
			}
			$trail[] = array( 'label' => get_the_title(), 'url' => get_permalink() );
		} elseif ( is_category() ) {
			$trail[] = array( 'label' => single_cat_title( '', false ), 'url' => get_category_link( get_queried_object() ) );
		}
		if ( is_home() && is_paged() ) {
			$trail[ count( $trail ) - 1 ]['url'] = get_permalink( $blog );
		}
	} elseif ( is_search() ) {
		$trail[] = array( 'label' => __( 'نتائج البحث', 'seohouse-core' ), 'url' => '' );
	} elseif ( is_404() ) {
		$trail[] = array( 'label' => __( 'صفحة غير موجودة', 'seohouse-core' ), 'url' => '' );
	} else {
		$trail[] = array( 'label' => wp_get_document_title(), 'url' => '' );
	}
	return $trail;
}
