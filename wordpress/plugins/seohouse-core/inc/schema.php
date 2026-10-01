<?php
/**
 * Structured data: one JSON-LD @graph per view, built only from fields and visible content.
 *
 * - Organization + WebSite come from "إعدادات سيو هاوس" (company data tab).
 * - The page node type comes from the template / the page's "نوع الصفحة في Schema" field.
 * - BreadcrumbList uses sh_breadcrumb_trail(), the same data as the visible breadcrumb.
 * - FAQPage uses the rows of the visible FAQ sections only.
 * - When an SEO plugin is active it owns structured data and Core outputs none
 *   (filter "sh_schema_enabled" can override this).
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_head', 'sh_schema_output', 30 );

function sh_schema_output(): void {
	$enabled = ! sh_seo_plugin_active() && ! is_404() && ! is_feed();
	if ( ! apply_filters( 'sh_schema_enabled', $enabled ) ) {
		return;
	}
	$graph = sh_schema_graph();
	if ( ! $graph ) {
		return;
	}
	$json = wp_json_encode(
		array(
			'@context' => 'https://schema.org',
			'@graph'   => $graph,
		),
		JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
	);
	echo "<script type=\"application/ld+json\">" . str_replace( '</', '<\/', (string) $json ) . "</script>\n"; // phpcs:ignore WordPress.Security.EscapeOutput
}

/** Removes empty values so no node carries placeholders. */
function sh_schema_clean( array $node ): array {
	foreach ( $node as $k => $v ) {
		if ( is_array( $v ) ) {
			$v          = sh_schema_clean( $v );
			$node[ $k ] = $v;
		}
		if ( null === $v || '' === $v || array() === $v ) {
			unset( $node[ $k ] );
		}
	}
	return $node;
}

function sh_schema_id( string $frag, string $url = '' ): string {
	return ( $url ? $url : home_url( '/' ) ) . '#' . $frag;
}

function sh_schema_image( $id ): string {
	$id = is_array( $id ) ? (int) ( $id['ID'] ?? ( $id['id'] ?? 0 ) ) : (int) $id;
	return $id ? (string) wp_get_attachment_image_url( $id, 'full' ) : '';
}

function sh_schema_organization(): array {
	$same = array();
	foreach ( (array) sh_core_option( 'sh_socials', array() ) as $s ) {
		if ( ! empty( $s['url'] ) ) {
			$same[] = esc_url_raw( $s['url'] );
		}
	}
	$areas = array_values( array_filter( array_map( 'trim', preg_split( '/[،,]/u', (string) sh_core_option( 'sh_areas', '' ) ) ) ) );
	$logo  = sh_schema_image( sh_core_option( 'sh_logo', 0 ) );
	if ( ! $logo && defined( 'SH_THEME_URI' ) ) {
		$logo = SH_THEME_URI . '/assets/img/logo-white.png';
	}
	$founded = (string) sh_core_option( 'sh_founded', '' );
	return sh_schema_clean(
		array(
			'@type'         => 'Organization',
			'@id'           => sh_schema_id( 'organization' ),
			'name'          => (string) sh_core_option( 'sh_company_name', get_bloginfo( 'name' ) ),
			'alternateName' => (string) sh_core_option( 'sh_company_name_en', '' ),
			'url'           => home_url( '/' ),
			'logo'          => $logo,
			'email'         => (string) sh_core_option( 'sh_email', '' ),
			'telephone'     => (string) sh_core_option( 'sh_phone', '' ),
			'foundingDate'  => $founded,
			'areaServed'    => $areas,
			'sameAs'        => $same,
		)
	);
}

function sh_schema_website(): array {
	return array(
		'@type'           => 'WebSite',
		'@id'             => sh_schema_id( 'website' ),
		'url'             => home_url( '/' ),
		'name'            => (string) sh_core_option( 'sh_company_name', get_bloginfo( 'name' ) ),
		'inLanguage'      => 'ar',
		'publisher'       => array( '@id' => sh_schema_id( 'organization' ) ),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => array(
				'@type'       => 'EntryPoint',
				'urlTemplate' => home_url( '/?s={search_term_string}' ),
			),
			'query-input' => 'required name=search_term_string',
		),
	);
}

function sh_schema_breadcrumbs( string $url ): array {
	$trail = function_exists( 'sh_breadcrumb_trail' ) ? sh_breadcrumb_trail() : array();
	if ( count( $trail ) < 2 ) {
		return array();
	}
	$items = array();
	foreach ( array_values( $trail ) as $i => $c ) {
		$items[] = sh_schema_clean(
			array(
				'@type'    => 'ListItem',
				'position' => $i + 1,
				'name'     => sh_core_plain( $c['label'] ),
				'item'     => $c['url'] ? $c['url'] : '',
			)
		);
	}
	return array(
		'@type'           => 'BreadcrumbList',
		'@id'             => sh_schema_id( 'breadcrumb', $url ),
		'itemListElement' => $items,
	);
}

/** FAQ rows from the visible FAQ sections of a page. */
function sh_schema_faq( int $post_id, string $url ): array {
	if ( ! function_exists( 'get_field' ) ) {
		return array();
	}
	$q = array();
	foreach ( sh_core_sections( $post_id ) as $row ) {
		if ( ! is_array( $row ) || 'faq' !== ( $row['acf_fc_layout'] ?? '' ) || ! empty( $row['sh_hide'] ) || empty( $row['schema'] ) ) {
			continue;
		}
		foreach ( (array) ( $row['items'] ?? array() ) as $it ) {
			if ( empty( $it['question'] ) || empty( $it['answer'] ) ) {
				continue;
			}
			$q[] = array(
				'@type'          => 'Question',
				'name'           => sh_core_plain( $it['question'] ),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => sh_core_plain( $it['answer'] ),
				),
			);
		}
	}
	return $q ? array(
		'@type'      => 'FAQPage',
		'@id'        => sh_schema_id( 'faq', $url ),
		'mainEntity' => $q,
	) : array();
}

function sh_schema_item_list( array $ids, string $url ): array {
	$items = array();
	foreach ( array_values( $ids ) as $i => $id ) {
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => $i + 1,
			'url'      => get_permalink( $id ),
			'name'     => sh_core_plain( get_the_title( $id ) ),
		);
	}
	return $items ? array(
		'@type'           => 'ItemList',
		'@id'             => sh_schema_id( 'list', $url ),
		'itemListElement' => $items,
	) : array();
}

/** Team profile linked to a post (field → team profile of the WP author). */
function sh_schema_author_member( WP_Post $post ): int {
	if ( function_exists( 'sh_post_author_member' ) ) {
		return (int) sh_post_author_member( $post );
	}
	return (int) sh_core_field( 'author_member', $post->ID, 0 );
}

function sh_schema_person( int $member_id ): array {
	$same = array();
	$li   = (string) sh_core_field( 'linkedin', $member_id, '' );
	if ( $li ) {
		$same[] = esc_url_raw( $li );
	}
	foreach ( (array) sh_core_field( 'links', $member_id, array() ) as $l ) {
		if ( ! empty( $l['url'] ) ) {
			$same[] = esc_url_raw( $l['url'] );
		}
	}
	$url = (string) get_permalink( $member_id );
	return sh_schema_clean(
		array(
			'@type'    => 'Person',
			'@id'      => sh_schema_id( 'person', $url ),
			'name'     => sh_core_plain( get_the_title( $member_id ) ),
			'jobTitle' => sh_core_plain( sh_core_field( 'role', $member_id, '' ) ),
			'image'    => (string) get_the_post_thumbnail_url( $member_id, 'full' ),
			'url'      => 'publish' === get_post_status( $member_id ) ? $url : '',
			'sameAs'   => array_values( array_unique( $same ) ),
			'worksFor' => array( '@id' => sh_schema_id( 'organization' ) ),
		)
	);
}

/** Page type for a page: explicit field, else from the template. */
function sh_schema_page_type( int $id ): string {
	$t = (string) sh_core_field( 'sh_schema_type', $id, 'auto' );
	if ( $t && 'auto' !== $t ) {
		return $t;
	}
	$key = sh_core_page_key( $id );
	$map = array(
		'about'   => 'about',
		'contact' => 'contact',
		'results' => 'collection',
		'team'    => 'collection',
		'sectors' => 'collection',
	);
	return $map[ $key ] ?? 'webpage';
}

/**
 * @return array<int,array> Graph nodes for the current view.
 */
function sh_schema_graph(): array {
	$org   = sh_schema_organization();
	$graph = array( $org, sh_schema_website() );
	$url   = function_exists( 'sh_seo_canonical' ) ? sh_seo_canonical() : '';
	if ( ! $url ) {
		$url = is_search() ? (string) get_search_link() : home_url( add_query_arg( array() ) );
	}
	$desc = function_exists( 'sh_seo_description' ) ? sh_seo_description() : '';
	$page = array(
		'@type'       => 'WebPage',
		'@id'         => $url . '#webpage',
		'url'         => $url,
		'name'        => sh_core_plain( wp_get_document_title() ),
		'description' => $desc,
		'inLanguage'  => 'ar',
		'isPartOf'    => array( '@id' => sh_schema_id( 'website' ) ),
	);
	$crumbs = sh_schema_breadcrumbs( $url );
	if ( $crumbs ) {
		$page['breadcrumb'] = array( '@id' => $crumbs['@id'] );
	}
	$extra = array();

	if ( is_front_page() ) {
		$page['about'] = array( '@id' => sh_schema_id( 'organization' ) );
	} elseif ( is_singular( 'page' ) || is_home() ) {
		$id   = is_home() ? (int) get_option( 'page_for_posts' ) : (int) get_queried_object_id();
		$type = is_home() ? 'collection' : sh_schema_page_type( $id );
		if ( 'none' === $type ) {
			return array();
		}
		$types        = array(
			'about'      => 'AboutPage',
			'contact'    => 'ContactPage',
			'collection' => 'CollectionPage',
		);
		$page['@type'] = $types[ $type ] ?? 'WebPage';
		if ( in_array( $type, array( 'about', 'contact' ), true ) ) {
			$page['about'] = array( '@id' => sh_schema_id( 'organization' ) );
		}
		if ( 'service' === $type ) {
			$name    = sh_core_plain( sh_core_field( 'sh_schema_service', $id, '' ) );
			$extra[] = sh_schema_clean(
				array(
					'@type'       => 'Service',
					'@id'         => $url . '#service',
					'serviceType' => $name ? $name : sh_core_plain( get_the_title( $id ) ),
					'url'         => $url,
					'description' => $desc,
					'provider'    => array( '@id' => sh_schema_id( 'organization' ) ),
					'areaServed'  => $org['areaServed'] ?? array(),
				)
			);
			$page['mainEntity'] = array( '@id' => $url . '#service' );
		}
		if ( 'collection' === $type ) {
			$key = sh_core_page_key( $id );
			$ids = array();
			if ( is_home() ) {
				$ids = wp_list_pluck( $GLOBALS['wp_query']->posts, 'ID' );
			} elseif ( in_array( $key, array( 'results', 'team' ), true ) ) {
				$ids = get_posts(
					array(
						'post_type'      => 'results' === $key ? 'case_study' : 'team_member',
						'posts_per_page' => 50,
						'fields'         => 'ids',
						'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
					)
				);
			}
			$list = sh_schema_item_list( $ids, $url );
			if ( $list ) {
				$extra[]            = $list;
				$page['mainEntity'] = array( '@id' => $list['@id'] );
			}
		}
		$faq = sh_schema_faq( $id, $url );
		if ( $faq ) {
			$extra[] = $faq;
		}
	} elseif ( is_category() ) {
		$page['@type'] = 'CollectionPage';
		$list          = sh_schema_item_list( wp_list_pluck( $GLOBALS['wp_query']->posts, 'ID' ), $url );
		if ( $list ) {
			$extra[]            = $list;
			$page['mainEntity'] = array( '@id' => $list['@id'] );
		}
	} elseif ( is_singular( 'post' ) || is_singular( 'case_study' ) ) {
		$post   = get_queried_object();
		$is_post = 'post' === $post->post_type;
		$author = array( '@id' => sh_schema_id( 'organization' ) );
		if ( $is_post ) {
			$m = sh_schema_author_member( $post );
			if ( $m ) {
				$person  = sh_schema_person( $m );
				$extra[] = $person;
				$author  = array( '@id' => $person['@id'] );
			}
		}
		$cats    = $is_post ? get_the_category( $post->ID ) : array();
		$extra[] = sh_schema_clean(
			array(
				'@type'            => $is_post ? 'BlogPosting' : 'Article',
				'@id'              => $url . '#article',
				'headline'         => sh_core_plain( get_the_title( $post ) ),
				'description'      => $desc,
				'datePublished'    => get_the_date( 'c', $post ),
				'dateModified'     => get_the_modified_date( 'c', $post ),
				'inLanguage'       => 'ar',
				'image'            => (string) get_the_post_thumbnail_url( $post, 'full' ),
				'articleSection'   => $cats ? $cats[0]->name : '',
				'author'           => $author,
				'publisher'        => array( '@id' => sh_schema_id( 'organization' ) ),
				'mainEntityOfPage' => array( '@id' => $url . '#webpage' ),
			)
		);
	} elseif ( is_singular( 'team_member' ) ) {
		$person             = sh_schema_person( (int) get_queried_object_id() );
		$page['@type']      = 'ProfilePage';
		$page['mainEntity'] = array( '@id' => $person['@id'] );
		$extra[]            = $person;
	} elseif ( is_search() ) {
		$page['@type'] = 'SearchResultsPage';
	} else {
		return array();
	}

	$graph[] = sh_schema_clean( $page );
	if ( $crumbs ) {
		$graph[] = $crumbs;
	}
	return (array) apply_filters( 'sh_schema_graph', array_merge( $graph, $extra ) );
}
