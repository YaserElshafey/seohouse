<?php
/**
 * Structured data: the nodes Core builds from fields and visible content.
 *
 * Core is the site's only source of structured data: one complete graph printed in wp_head
 * (sh_schema_output()), whether or not an SEO plugin is active. Rank Math keeps the title,
 * description, canonical, robots, Open Graph and sitemaps; its own JSON-LD stays off even if its
 * Schema module is switched on (inc/rankmath.php). Nothing here reads Rank Math's objects or
 * settings: company, logo, people and content come from SEO House's fields. The page name and
 * description are the title and description the head prints (whichever plugin prints them).
 *
 * Sources — nothing is written in the code:
 * - Organization (@id home_url('/#organization')): «إعدادات سيو هاوس» ← الهوية (name, Latin name,
 *   logo on light background; image = «صورة الشركة في البيانات المنظمة», else the logo), الفوتر
 *   (description = the footer about text; sameAs = the official profiles in «روابط التواصل في الفوتر»,
 *   never a map, directions, embed or chat link), بيانات
 *   الشركة (phone, email, head office address as PostalAddress from «العنوان المنظم», founding year,
 *   markets → areaServed as Country; the markets are service areas, never branch addresses).
 * - Service: the page's hero (H1 → name, intro paragraph → description), «اسم الخدمة في Schema»
 *   (serviceType), the market of the SEO country pages (areaServed), the sector name (audience).
 * - ItemList: the links shown in the listing of a collection page, in the same order.
 * - Article (case study): title, summary, real images, the company as author (no person is credited
 *   on these pages); the client is never named.
 * - Person: a published team profile only.
 * - FAQPage: the visible FAQ section with «Schema» switched on.
 * No SearchAction, and no rating or review for the company.
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_head', 'sh_schema_output', 30 );

function sh_schema_output(): void {
	if ( ! apply_filters( 'sh_schema_enabled', ! is_404() && ! is_feed() ) ) {
		return;
	}
	$graph = sh_schema_graph();
	if ( ! $graph ) {
		return;
	}
	$json = wp_json_encode(
		array(
			'@context' => 'https://schema.org',
			'@graph'   => array_values( $graph ),
		),
		JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
	);
	echo '<script type="application/ld+json" class="seohouse-schema">' . str_replace( '</', '<\/', (string) $json ) . "</script>\n"; // phpcs:ignore WordPress.Security.EscapeOutput
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

/** The company: https://seohouse.agency/#organization on the live site. */
function sh_schema_org_ref(): array {
	return array( '@id' => sh_schema_id( 'organization' ) );
}

function sh_schema_image( $id ): string {
	$id = is_array( $id ) ? (int) ( $id['ID'] ?? ( $id['id'] ?? 0 ) ) : (int) $id;
	return $id ? (string) wp_get_attachment_image_url( $id, 'full' ) : '';
}

/** A link field value (URL, page ID or ACF link array) as a URL. */
function sh_schema_link_url( $v ): string {
	if ( is_numeric( $v ) ) {
		return (string) get_permalink( (int) $v );
	}
	if ( is_array( $v ) ) {
		return (string) ( $v['url'] ?? '' );
	}
	return trim( (string) $v );
}

/* ------------------------------------------------------------------ company */

/**
 * Country codes → names an editor may write in «الأسواق المخدومة». Only used to recognise the
 * country; the name printed is the one written in the field.
 */
function sh_schema_country_aliases(): array {
	return array(
		'SA' => array( 'السعودية', 'المملكة العربية السعودية', 'saudi arabia', 'ksa' ),
		'EG' => array( 'مصر', 'جمهورية مصر العربية', 'egypt' ),
		'AE' => array( 'الإمارات', 'الامارات', 'الإمارات العربية المتحدة', 'uae', 'united arab emirates' ),
	);
}

/**
 * «الأسواق المخدومة», in the field's order.
 *
 * @return array{countries:array<string,string>,other:string[]} code => name as written; unrecognised entries
 */
function sh_schema_markets(): array {
	$out = array( 'countries' => array(), 'other' => array() );
	foreach ( preg_split( '/[،,]/u', (string) sh_core_option( 'sh_areas', '' ) ) as $name ) {
		$name = sh_core_plain( $name );
		if ( '' === $name ) {
			continue;
		}
		$code = '';
		foreach ( sh_schema_country_aliases() as $c => $aliases ) {
			if ( in_array( mb_strtolower( $name ), $aliases, true ) ) {
				$code = $c;
				break;
			}
		}
		if ( ! $code ) {
			$out['other'][] = $name;
		} elseif ( ! isset( $out['countries'][ $code ] ) ) {
			$out['countries'][ $code ] = $name;
		}
	}
	return $out;
}

/** Country nodes for the given codes (all markets when null), only those in «الأسواق المخدومة». */
function sh_schema_country_nodes( ?array $codes = null ): array {
	$nodes = array();
	foreach ( sh_schema_markets()['countries'] as $code => $name ) {
		if ( null === $codes || in_array( $code, $codes, true ) ) {
			$nodes[] = array(
				'@type' => 'Country',
				'name'  => $name,
			);
		}
	}
	return $nodes;
}

/** Market of a page: the SEO country pages serve one country, the general SEO page serves the three. */
function sh_schema_market_codes( string $key ): array {
	$pages = array(
		'seo-ksa'   => 'SA',
		'seo-egypt' => 'EG',
		'seo-uae'   => 'AE',
	);
	if ( 'seo' === $key ) {
		return array_values( $pages );
	}
	return isset( $pages[ $key ] ) ? array( $pages[ $key ] ) : array();
}

/**
 * Why a link from «روابط التواصل في الفوتر» is not an identity profile for sameAs ('' = it is one):
 * maps and directions, embeds, chat links and the site's own pages are not.
 */
function sh_schema_profile_problem( string $url ): string {
	$url = trim( $url );
	if ( ! preg_match( '#^https?://#i', $url ) ) {
		return 'not a web address';
	}
	$host = strtolower( (string) wp_parse_url( $url, PHP_URL_HOST ) );
	$path = strtolower( (string) wp_parse_url( $url, PHP_URL_PATH ) );
	$own  = strtolower( (string) wp_parse_url( home_url( '/' ), PHP_URL_HOST ) );
	$is   = static fn( string $d ) => $host === $d || str_ends_with( $host, '.' . $d );
	if ( '' === $host || $host === $own ) {
		return 'the site itself';
	}
	if ( $is( 'goo.gl' ) || $is( 'g.page' ) || ( preg_match( '/(^|\.)google\.[a-z.]+$/', $host ) && ( str_starts_with( $path, '/maps' ) || str_starts_with( $host, 'maps.' ) ) ) ) {
		return 'Google Maps link';
	}
	if ( str_contains( $path, '/embed' ) || str_contains( $path, '/dir/' ) ) {
		return 'embed or directions link';
	}
	if ( $is( 'wa.me' ) || $is( 'whatsapp.com' ) || $is( 't.me' ) ) {
		return 'chat link';
	}
	return '';
}

/** Official profiles from «روابط التواصل في الفوتر». */
function sh_schema_profiles(): array {
	$same = array();
	foreach ( (array) sh_core_option( 'sh_socials', array() ) as $s ) {
		$u = esc_url_raw( trim( (string) ( $s['url'] ?? '' ) ) );
		if ( $u && '' === sh_schema_profile_problem( $u ) ) {
			$same[] = $u;
		}
	}
	return array_values( array_unique( $same ) );
}

/** Logo for a light background (Google shows it on white): «الشعار على الخلفية الفاتحة» → the theme's. */
function sh_schema_logo(): array {
	$url = '';
	$w   = 0;
	$h   = 0;
	$id  = (int) sh_core_option( 'sh_logo_light', 0 );
	if ( $id ) {
		$src = wp_get_attachment_image_src( $id, 'full' );
		if ( $src ) {
			[ $url, $w, $h ] = $src;
		}
	}
	if ( ! $url && defined( 'SH_THEME_DIR' ) && defined( 'SH_THEME_URI' ) && is_readable( SH_THEME_DIR . '/assets/img/logo-blue.webp' ) ) {
		$url  = SH_THEME_URI . '/assets/img/logo-blue.webp';
		$size = @getimagesize( SH_THEME_DIR . '/assets/img/logo-blue.webp' ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
		if ( $size ) {
			[ $w, $h ] = $size;
		}
	}
	return $url ? sh_schema_clean(
		array(
			'@type'      => 'ImageObject',
			'@id'        => sh_schema_id( 'logo' ),
			'url'        => $url,
			'contentUrl' => $url,
			'width'      => (int) $w > 0 ? (int) $w : null, // size from the file; none when unknown
			'height'     => (int) $h > 0 ? (int) $h : null,
		)
	) : array();
}

/**
 * Image of the company: «صورة الشركة في البيانات المنظمة» (an image from the media library, size read
 * from the file's metadata); empty → the logo itself, referenced by its @id so the same ImageObject
 * serves both properties.
 */
function sh_schema_company_image( array $logo ): array {
	$id = (int) sh_core_option( 'sh_company_image', 0 );
	if ( $id ) {
		$src = wp_get_attachment_image_src( $id, 'full' );
		if ( $src && ! empty( $src[0] ) ) {
			return sh_schema_clean(
				array(
					'@type'      => 'ImageObject',
					'@id'        => sh_schema_id( 'image' ),
					'url'        => $src[0],
					'contentUrl' => $src[0],
					'width'      => (int) $src[1] > 0 ? (int) $src[1] : null, // size from the file; none when unknown
					'height'     => (int) $src[2] > 0 ? (int) $src[2] : null,
				)
			);
		}
	}
	return ! empty( $logo['@id'] ) ? array( '@id' => $logo['@id'] ) : array();
}

/**
 * Head office address. The parts in «بيانات الشركة ← العنوان المنظم» make a PostalAddress (a part left
 * empty is not printed; the country only as a two-letter ISO code). Until any part is filled, the
 * address text shown on the contact page is printed as before.
 *
 * @return array|string
 */
function sh_schema_address() {
	$country = strtoupper( trim( (string) sh_core_option( 'sh_address_country', '' ) ) );
	$parts   = array(
		'streetAddress'   => sh_core_plain( sh_core_option( 'sh_address_street', '' ) ),
		'addressLocality' => sh_core_plain( sh_core_option( 'sh_address_locality', '' ) ),
		'addressRegion'   => sh_core_plain( sh_core_option( 'sh_address_region', '' ) ),
		'postalCode'      => sh_core_plain( sh_core_option( 'sh_address_postal', '' ) ),
		'addressCountry'  => preg_match( '/^[A-Z]{2}$/', $country ) ? $country : '',
	);
	if ( ! array_filter( $parts ) ) {
		return sh_core_plain( sh_core_option( 'sh_address', '' ) );
	}
	return sh_schema_clean( array( '@type' => 'PostalAddress' ) + $parts );
}

/** The company from «إعدادات سيو هاوس». Always type Organization. */
function sh_schema_organization(): array {
	$founded = trim( (string) sh_core_option( 'sh_founded', '' ) );
	$logo    = sh_schema_logo();
	return sh_schema_clean(
		array(
			'@type'         => 'Organization',
			'@id'           => sh_schema_id( 'organization' ),
			'name'          => sh_core_plain( sh_core_option( 'sh_company_name', get_bloginfo( 'name' ) ) ),
			'alternateName' => sh_core_plain( sh_core_option( 'sh_company_name_en', '' ) ),
			'url'           => home_url( '/' ),
			'logo'          => $logo,
			'image'         => sh_schema_company_image( $logo ),
			'description'   => sh_core_plain( sh_core_option( 'sh_footer_text', '' ) ),
			// a year alone is a valid ISO 8601 date; no day or month is made up
			'foundingDate'  => preg_match( '/^\d{4}$/', $founded ) ? $founded : '',
			'telephone'     => sh_core_plain( sh_core_option( 'sh_phone', '' ) ),
			'email'         => sanitize_email( (string) sh_core_option( 'sh_email', '' ) ),
			'address'       => sh_schema_address(),
			'areaServed'    => sh_schema_country_nodes(),
			'sameAs'        => sh_schema_profiles(),
		)
	);
}

function sh_schema_website(): array {
	return array(
		'@type'      => 'WebSite',
		'@id'        => sh_schema_id( 'website' ),
		'url'        => home_url( '/' ),
		'name'       => sh_core_plain( sh_core_option( 'sh_company_name', get_bloginfo( 'name' ) ) ),
		'inLanguage' => 'ar',
		'publisher'  => sh_schema_org_ref(),
	);
}

/* ------------------------------------------------------------------ page content */

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

/** The page's hero as shown: title (the H1), intro paragraph, eyebrow. Empty when hidden or absent. */
function sh_schema_hero( int $id ): array {
	foreach ( sh_core_sections( $id ) as $row ) {
		if ( is_array( $row ) && 'hero' === ( $row['acf_fc_layout'] ?? '' ) ) {
			return ! empty( $row['sh_hide'] ) ? array() : array(
				'title'   => sh_core_plain( $row['title'] ?? '' ),
				'text'    => sh_core_plain( $row['text'] ?? '' ),
				'eyebrow' => sh_core_plain( $row['eyebrow'] ?? '' ),
			);
		}
	}
	return array();
}

/** Service described by a service, sector or SEO country page. */
function sh_schema_service( int $id, string $url, array $org_ref ): array {
	$hero     = sh_schema_hero( $id );
	$key      = sh_core_page_key( $id );
	$audience = '';
	if ( str_starts_with( $key, 'sector-' ) ) {
		// the sector's name as the page shows it: hero eyebrow, else the last breadcrumb label
		$audience = (string) ( $hero['eyebrow'] ?? '' );
		if ( '' === $audience && function_exists( 'sh_breadcrumb_trail' ) && (int) get_queried_object_id() === $id ) {
			$trail    = sh_breadcrumb_trail();
			$last     = end( $trail );
			$audience = $last ? sh_core_plain( $last['label'] ?? '' ) : '';
		}
	}
	$codes = sh_schema_market_codes( $key );
	return sh_schema_clean(
		array(
			'@type'       => 'Service',
			'@id'         => $url . '#service',
			'name'        => '' !== ( $hero['title'] ?? '' ) ? $hero['title'] : sh_core_plain( get_the_title( $id ) ),
			'serviceType' => sh_core_plain( sh_core_field( 'sh_schema_service', $id, '' ) ),
			'description' => $hero['text'] ?? '',
			'url'         => $url,
			'provider'    => $org_ref,
			'areaServed'  => $codes ? sh_schema_country_nodes( $codes ) : array(),
			'audience'    => '' !== $audience ? array(
				'@type'        => 'BusinessAudience',
				'audienceType' => $audience,
			) : array(),
		)
	);
}

/** Team members as /team/ shows them (published, admin order). */
function sh_schema_team_ids(): array {
	return get_posts(
		array(
			'post_type'      => 'team_member',
			'post_status'    => 'publish',
			'posts_per_page' => 60,
			'fields'         => 'ids',
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
			'no_found_rows'  => true,
		)
	);
}

/**
 * Entries of the listing shown on a collection page, in the page's order.
 *
 * @return array<int,array{name:string,url:string}>
 */
function sh_schema_listing( int $id ): array {
	$key  = sh_core_page_key( $id );
	$rows = array();
	$add  = static function ( $name, $link ) use ( &$rows ) {
		$rows[] = array(
			'name' => sh_core_plain( $name ),
			'url'  => sh_schema_link_url( $link ),
		);
	};
	if ( (int) get_option( 'page_for_posts' ) === $id ) {
		$posts = is_home() ? (array) $GLOBALS['wp_query']->posts : array();
		foreach ( $posts as $p ) {
			$add( get_the_title( $p ), get_permalink( $p ) );
		}
	} elseif ( in_array( $key, array( 'services', 'sectors' ), true ) ) {
		foreach ( sh_core_sections( $id ) as $s ) {
			if ( ! is_array( $s ) || ! empty( $s['sh_hide'] ) ) {
				continue;
			}
			$layout = $s['acf_fc_layout'] ?? '';
			if ( 'services' === $key && 'families' === $layout ) {
				foreach ( (array) ( $s['items'] ?? array() ) as $family ) {
					foreach ( (array) ( $family['items'] ?? array() ) as $it ) {
						$add( $it['label'] ?? '', $it['link'] ?? '' );
					}
				}
				$add( $s['label_2'] ?? '', $s['link'] ?? '' );
			}
			if ( 'sectors' === $key && 'list' === $layout ) {
				foreach ( (array) ( $s['items'] ?? array() ) as $it ) {
					$add( $it['heading'] ?? '', $it['link'] ?? '' );
				}
			}
		}
	} elseif ( 'results' === $key ) {
		$ids = get_posts(
			array(
				'post_type'      => 'case_study',
				'post_status'    => 'publish',
				'posts_per_page' => 50,
				'fields'         => 'ids',
				'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
				'no_found_rows'  => true,
			)
		);
		foreach ( $ids as $c ) {
			$add( get_the_title( $c ), get_permalink( $c ) );
		}
	} elseif ( 'team' === $key ) {
		foreach ( sh_schema_team_ids() as $m ) {
			$add( get_the_title( $m ), get_permalink( $m ) );
		}
	}
	$seen = array();
	return array_values(
		array_filter(
			$rows,
			static function ( $r ) use ( &$seen ) {
				if ( '' === $r['name'] || '' === $r['url'] || isset( $seen[ $r['url'] ] ) ) {
					return false;
				}
				return $seen[ $r['url'] ] = true;
			}
		)
	);
}

function sh_schema_item_list( array $rows, string $url ): array {
	$items = array();
	foreach ( array_values( $rows ) as $i => $r ) {
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => $i + 1,
			'name'     => $r['name'],
			'url'      => $r['url'],
		);
	}
	return $items ? array(
		'@type'           => 'ItemList',
		'@id'             => sh_schema_id( 'list', $url ),
		'itemListElement' => $items,
	) : array();
}

/* ------------------------------------------------------------------ people and articles */

/** A team profile that is published (a hidden, draft or deleted profile is never described). */
function sh_schema_member_ok( int $member_id ): bool {
	return $member_id > 0 && 'team_member' === get_post_type( $member_id ) && 'publish' === get_post_status( $member_id ) && '' === (string) get_post_field( 'post_password', $member_id );
}

/**
 * Writer of an article as its page credits it.
 *
 * @return array{0:int,1:int} [published team profile or 0, profile the article points to (may be deleted or hidden)]
 */
function sh_schema_post_writer( WP_Post $post ): array {
	$linked = function_exists( 'sh_post_author_member' ) ? (int) sh_post_author_member( $post ) : (int) sh_core_field( 'author_member', $post->ID, 0 );
	return array( sh_schema_member_ok( $linked ) ? $linked : 0, $linked );
}

/** Person of a published team profile; its @id is the profile URL + #person on every page. */
function sh_schema_person( int $member_id ): array {
	if ( ! sh_schema_member_ok( $member_id ) ) {
		return array();
	}
	$same = array();
	$li   = (string) sh_core_field( 'linkedin', $member_id, '' );
	if ( $li ) {
		$same[] = esc_url_raw( $li );
	}
	foreach ( (array) sh_core_field( 'links', $member_id, array() ) as $l ) {
		if ( ! empty( $l['url'] ) && ! empty( $l['label'] ) ) {
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
			'url'      => $url,
			'sameAs'   => array_values( array_unique( array_filter( $same ) ) ),
			'worksFor' => sh_schema_org_ref(),
		)
	);
}

/**
 * Real image of an article: its featured image; for a case study without one, the first report
 * screenshot shown on the page (confirmed for that case). '' when there is none.
 */
function sh_schema_post_image( WP_Post $post ): string {
	$url = (string) get_the_post_thumbnail_url( $post, 'full' );
	if ( $url || 'case_study' !== $post->post_type ) {
		return $url;
	}
	foreach ( (array) sh_core_field( 'gallery', $post->ID, array() ) as $g ) {
		if ( is_array( $g ) && ! empty( $g['image'] ) && ! empty( $g['verified'] ) ) {
			$url = sh_schema_image( $g['image'] );
			if ( $url ) {
				return $url;
			}
		}
	}
	return '';
}

/** Case study as an Article by the company (these pages credit no person). */
function sh_schema_case_article( WP_Post $post, string $url, array $org_ref, string $desc = '' ): array {
	$summary = sh_core_plain( sh_core_field( 'summary', $post->ID, '' ) );
	return sh_schema_clean(
		array(
			'@type'            => 'Article',
			'@id'              => $url . '#article',
			'headline'         => sh_core_plain( get_the_title( $post ) ),
			'description'      => '' !== $summary ? $summary : $desc,
			'datePublished'    => get_the_date( 'c', $post ),
			'dateModified'     => get_the_modified_date( 'c', $post ),
			'inLanguage'       => 'ar',
			'image'            => sh_schema_post_image( $post ),
			'author'           => $org_ref,
			'publisher'        => $org_ref,
			'mainEntityOfPage' => array( '@id' => $url . '#webpage' ),
		)
	);
}

/* ------------------------------------------------------------------ page type */

/**
 * Page type: the collection pages are always CollectionPage («بدون» still switches a page off);
 * otherwise the page's «نوع الصفحة في Schema», else from the template.
 */
function sh_schema_page_type( int $id ): string {
	$t   = (string) sh_core_field( 'sh_schema_type', $id, 'auto' );
	$key = sh_core_page_key( $id );
	if ( 'none' === $t ) {
		return 'none';
	}
	if ( in_array( $key, array( 'services', 'sectors', 'results', 'team' ), true ) || (int) get_option( 'page_for_posts' ) === $id ) {
		return 'collection';
	}
	if ( $t && 'auto' !== $t ) {
		return $t;
	}
	$map = array(
		'about'   => 'about',
		'contact' => 'contact',
	);
	if ( isset( $map[ $key ] ) ) {
		return $map[ $key ];
	}
	// service, sector and country pages describe a service the company provides
	if ( preg_match( '/^(seo|seo-[a-z-]+|web-[a-z-]+|web-design|stores|stores-[a-z]+|products|sector-[a-z-]+)$/', $key ) ) {
		return 'service';
	}
	return 'webpage';
}

/* ------------------------------------------------------------------ the graph */

/**
 * The page description as the head prints it. With Rank Math active that is the description Rank
 * Math prints (read as it passes through its rank_math/frontend/description filter, before this
 * graph is built); otherwise Core's own.
 */
function sh_schema_page_description(): string {
	$printed = $GLOBALS['sh_schema_printed_description'] ?? null;
	if ( is_string( $printed ) ) {
		return sh_core_plain( $printed );
	}
	return function_exists( 'sh_seo_description' ) ? sh_seo_description() : '';
}

add_filter(
	'rank_math/frontend/description',
	static function ( $desc ) {
		$GLOBALS['sh_schema_printed_description'] = (string) $desc;
		return $desc;
	},
	PHP_INT_MAX
);

/**
 * @return array<int,array> Graph nodes for the current view.
 */
function sh_schema_graph(): array {
	$org_ref = sh_schema_org_ref();
	$graph   = array( sh_schema_organization(), sh_schema_website() );
	$url     = function_exists( 'sh_seo_canonical' ) ? sh_seo_canonical() : '';
	if ( ! $url ) {
		$url = is_search() ? (string) get_search_link() : home_url( add_query_arg( array() ) );
	}
	$desc = sh_schema_page_description();
	$page = array(
		'@type'       => 'WebPage',
		'@id'         => $url . '#webpage',
		'url'         => $url,
		'name'        => sh_core_plain( wp_get_document_title() ),
		'description' => $desc,
		'inLanguage'  => 'ar',
		'isPartOf'    => array( '@id' => sh_schema_id( 'website' ) ),
	);
	if ( is_singular() || is_front_page() || is_home() ) {
		$obj = is_home() ? get_post( (int) get_option( 'page_for_posts' ) ) : ( is_front_page() ? get_post( (int) get_option( 'page_on_front' ) ) : get_queried_object() );
		if ( $obj instanceof WP_Post ) {
			$page['datePublished'] = get_the_date( 'c', $obj );
			$page['dateModified']  = get_the_modified_date( 'c', $obj );
		}
	}
	$crumbs = sh_schema_breadcrumbs( $url );
	if ( $crumbs ) {
		$page['breadcrumb'] = array( '@id' => $crumbs['@id'] );
	}
	$extra = array();

	if ( is_front_page() ) {
		$page['about'] = $org_ref;
	} elseif ( is_singular( 'page' ) || is_home() ) {
		$id   = is_home() ? (int) get_option( 'page_for_posts' ) : (int) get_queried_object_id();
		$type = sh_schema_page_type( $id );
		if ( 'none' === $type ) {
			return array();
		}
		$types         = array(
			'about'      => 'AboutPage',
			'contact'    => 'ContactPage',
			'collection' => 'CollectionPage',
		);
		$page['@type'] = $types[ $type ] ?? 'WebPage';
		if ( in_array( $type, array( 'about', 'contact' ), true ) ) {
			$page['about'] = $org_ref;
		}
		if ( 'about' === $type ) {
			$page['mainEntity'] = $org_ref;
		}
		if ( 'service' === $type ) {
			$service            = sh_schema_service( $id, $url, $org_ref );
			$extra[]            = $service;
			$page['mainEntity'] = array( '@id' => $service['@id'] );
		}
		if ( 'collection' === $type ) {
			$list = sh_schema_item_list( sh_schema_listing( $id ), $url );
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
		$rows          = array();
		foreach ( (array) $GLOBALS['wp_query']->posts as $p ) {
			$rows[] = array(
				'name' => sh_core_plain( get_the_title( $p ) ),
				'url'  => (string) get_permalink( $p ),
			);
		}
		$list = sh_schema_item_list( $rows, $url );
		if ( $list ) {
			$extra[]            = $list;
			$page['mainEntity'] = array( '@id' => $list['@id'] );
		}
	} elseif ( is_singular( 'case_study' ) ) {
		$extra[] = sh_schema_case_article( get_queried_object(), $url, $org_ref, $desc );
	} elseif ( is_singular( 'post' ) ) {
		$post               = get_queried_object();
		[ $member, $linked ] = sh_schema_post_writer( $post );
		$author             = array();
		if ( $member ) {
			$person  = sh_schema_person( $member );
			$extra[] = $person;
			$author  = array( '@id' => $person['@id'] );
		} elseif ( ! $linked ) {
			// no profile: the page credits the WordPress author, else the company's team
			$name   = trim( (string) get_the_author_meta( 'display_name', (int) $post->post_author ) );
			$author = '' !== $name ? array(
				'@type' => 'Person',
				'name'  => $name,
			) : $org_ref;
		}
		$cats    = get_the_category( $post->ID );
		$extra[] = sh_schema_clean(
			array(
				'@type'            => 'BlogPosting',
				'@id'              => $url . '#article',
				'headline'         => sh_core_plain( get_the_title( $post ) ),
				'description'      => $desc,
				'datePublished'    => get_the_date( 'c', $post ),
				'dateModified'     => get_the_modified_date( 'c', $post ),
				'inLanguage'       => 'ar',
				'image'            => sh_schema_post_image( $post ),
				'articleSection'   => $cats ? $cats[0]->name : '',
				'author'           => $author, // none when the article points to a deleted or hidden profile
				'publisher'        => $org_ref,
				'mainEntityOfPage' => array( '@id' => $url . '#webpage' ),
			)
		);
	} elseif ( is_singular( 'team_member' ) ) {
		$person = sh_schema_person( (int) get_queried_object_id() );
		if ( $person ) {
			$page['@type']      = 'ProfilePage';
			$page['mainEntity'] = array( '@id' => $person['@id'] );
			$extra[]            = $person;
		}
	} elseif ( is_search() ) {
		$page['@type'] = 'SearchResultsPage';
	} else {
		return array();
	}

	$graph[] = sh_schema_clean( $page );
	if ( $crumbs ) {
		$graph[] = $crumbs;
	}
	return (array) apply_filters( 'sh_schema_graph', array_values( sh_schema_merge_by_id( array_values( array_filter( array_merge( $graph, $extra ) ) ) ) ) );
}

/* ------------------------------------------------------------------ graph helpers */

/** Replaces every reference to (and the @id of) $old with $new, at any depth. */
function sh_schema_replace_id( array $node, string $old, string $new ): array {
	foreach ( $node as $k => $v ) {
		if ( '@id' === $k && $v === $old ) {
			$node[ $k ] = $new;
		} elseif ( is_array( $v ) ) {
			$node[ $k ] = sh_schema_replace_id( $v, $old, $new );
		}
	}
	return $node;
}

/** Number of references to $id inside the graph (top-level node @ids excluded). */
function sh_schema_ref_count( array $data, string $id ): int {
	$n    = 0;
	$walk = static function ( $v, int $depth ) use ( &$walk, &$n, $id ) {
		if ( ! is_array( $v ) ) {
			return;
		}
		foreach ( $v as $k => $x ) {
			if ( '@id' === $k && $x === $id && $depth > 1 ) {
				++$n;
			} elseif ( is_array( $x ) ) {
				$walk( $x, $depth + 1 );
			}
		}
	};
	$walk( $data, 0 );
	return $n;
}

/** Removes the top-level node $id when nothing refers to it any more (never the company). */
function sh_schema_drop_unreferenced( array $data, string $id ): array {
	if ( '' === $id || sh_schema_id( 'organization' ) === $id || sh_schema_ref_count( $data, $id ) > 0 ) {
		return $data;
	}
	foreach ( $data as $k => $node ) {
		if ( is_array( $node ) && ( $node['@id'] ?? '' ) === $id ) {
			unset( $data[ $k ] );
		}
	}
	return $data;
}

/**
 * One node per entity: top-level nodes sharing an @id are merged into the first one. The first
 * node's values are kept (Rank Math's come first); missing properties are filled from the others
 * and sameAs lists are joined.
 */
function sh_schema_merge_by_id( array $data ): array {
	$first = array();
	foreach ( $data as $k => $node ) {
		if ( ! is_array( $node ) || empty( $node['@id'] ) || ! isset( $node['@type'] ) ) {
			continue;
		}
		$id = (string) $node['@id'];
		if ( ! isset( $first[ $id ] ) ) {
			$first[ $id ] = $k;
			continue;
		}
		$keep = $data[ $first[ $id ] ];
		foreach ( $node as $p => $v ) {
			if ( ! isset( $keep[ $p ] ) || '' === $keep[ $p ] || array() === $keep[ $p ] ) {
				$keep[ $p ] = $v;
			} elseif ( 'sameAs' === $p ) {
				$keep[ $p ] = array_values( array_unique( array_merge( (array) $keep[ $p ], (array) $v ) ) );
			}
		}
		$data[ $first[ $id ] ] = $keep;
		unset( $data[ $k ] );
	}
	return $data;
}
