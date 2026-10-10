<?php
/**
 * Rank Math as the one place for search title, description, social image and indexing.
 *
 * - Core's duplicate fields (عنوان SEO، وصف SEO، صورة المشاركة، منع الفهرسة) are hidden while
 *   Rank Math is active; the breadcrumb names and the Schema fields stay (they drive the page).
 * - Existing Core values are moved once into Rank Math's own fields, only where those are empty
 *   (screen «سيو هاوس ← نقل السيو إلى Rank Math», `wp seohouse rankmath-migrate`, and at the end
 *   of the site initialisation). Rank Math values an editor wrote are never changed.
 * - Until that move has run, a Core value is used only for a page whose Rank Math field is empty.
 * - Structured data: Rank Math's graph is the output; Core adds the nodes Rank Math cannot know
 *   (see sh_rankmath_graph() for which source owns which node).
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

/** Rank Math is installed and active (it may still be waiting for its registration step). */
function sh_rankmath_installed(): bool {
	return class_exists( 'RankMath' );
}

/**
 * Rank Math is actually running. While its registration step («Connect» or «Skip») has not been
 * completed it loads neither its editor box nor its front-end tags, so Core must not hand the
 * output over to it.
 */
function sh_rankmath_active(): bool {
	if ( ! class_exists( 'RankMath' ) ) {
		return false;
	}
	if ( class_exists( '\RankMath\Helper' ) && method_exists( '\RankMath\Helper', 'is_invalid_registration' ) ) {
		return ! \RankMath\Helper::is_invalid_registration();
	}
	return true;
}

/** Public post types that get the Rank Math box («SEO Controls»). */
function sh_rankmath_post_types(): array {
	return array( 'page', 'post', 'case_study', 'team_member' );
}

/** Types whose Rank Math box is switched off in Rank Math ← Titles & Meta. */
function sh_rankmath_controls_off(): array {
	$titles = get_option( 'rank-math-options-titles', array() );
	$off    = array();
	foreach ( sh_rankmath_post_types() as $t ) {
		if ( post_type_exists( $t ) && 'on' !== ( $titles[ "pt_{$t}_add_meta_box" ] ?? 'on' ) ) {
			$off[] = $t;
		}
	}
	return $off;
}

/** Switches the Rank Math box on for the site's public types; returns the types changed. */
function sh_rankmath_enable_controls(): array {
	$titles  = get_option( 'rank-math-options-titles', array() );
	$changed = array();
	foreach ( sh_rankmath_post_types() as $t ) {
		if ( post_type_exists( $t ) && 'on' !== ( $titles[ "pt_{$t}_add_meta_box" ] ?? '' ) ) {
			$titles[ "pt_{$t}_add_meta_box" ] = 'on';
			$changed[]                         = $t;
		}
	}
	if ( $changed ) {
		update_option( 'rank-math-options-titles', $titles );
	}
	return $changed;
}

// Once, when Rank Math first runs here: SEO Controls on for pages, articles, case studies and team.
add_action(
	'admin_init',
	static function () {
		if ( sh_rankmath_active() && ! get_option( 'sh_rankmath_controls_done' ) && current_user_can( 'manage_options' ) ) {
			sh_rankmath_enable_controls();
			update_option( 'sh_rankmath_controls_done', time(), false );
		}
	}
);

// Rank Math waiting for its registration step: say so where the editor looks for its box.
add_action(
	'admin_notices',
	static function () {
		if ( ! current_user_can( 'manage_options' ) || ! sh_rankmath_installed() || sh_rankmath_active() ) {
			return;
		}
		echo '<div class="notice notice-error"><p><strong>' . esc_html__( 'Rank Math مفعّلة لكنها متوقفة: لم تكتمل خطوة الحساب في معالج الإعداد.', 'seohouse-core' ) . '</strong> ' . esc_html__( 'حتى تكتمل لا يظهر صندوق Rank Math في المحرر ولا تُخرج وسوم البحث، ويتولى SEO House Core العنوان والوصف والبيانات المنظمة مؤقتًا.', 'seohouse-core' ) . '</p><p><a class="button button-primary" href="' . esc_url( admin_url( 'admin.php?page=rank-math-registration' ) ) . '">' . esc_html__( 'ربط حساب Rank Math', 'seohouse-core' ) . '</a> ';
		echo '<a class="button" href="' . esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=sh_rankmath_skip' ), 'sh_rankmath_skip' ) ) . '">' . esc_html__( 'تشغيل Rank Math بدون حساب (مثل «Skip» في المعالج)', 'seohouse-core' ) . '</a></p></div>';
	}
);

add_action(
	'admin_post_sh_rankmath_skip',
	static function () {
		check_admin_referer( 'sh_rankmath_skip' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( '', 403 );
		}
		update_option( 'rank_math_registration_skip', true ); // Rank Math's own "Skip" setting
		wp_safe_redirect( admin_url( 'admin.php?page=seohouse-rankmath&skipped=1' ) );
		exit;
	}
);

/** Core field name => what it becomes in Rank Math. */
function sh_rankmath_fields(): array {
	return array(
		'sh_seo_title'       => 'عنوان البحث',
		'sh_seo_description' => 'وصف البحث',
		'sh_seo_og_image'    => 'صورة المشاركة',
		'sh_seo_noindex'     => 'منع الفهرسة',
	);
}

/* ------------------------------------------------------------------ editor: one place to edit */

add_filter(
	'acf/prepare_field',
	static function ( $field ) {
		if ( ! is_array( $field ) ) {
			return $field;
		}
		$name = (string) ( $field['name'] ?? '' );
		// names arrive prefixed (acf[...]) on some screens: compare by key as well
		$key = (string) ( $field['key'] ?? '' );
		if ( 'field_sh_seo_rankmath_msg' === $key ) {
			return sh_rankmath_active() ? $field : false;
		}
		if ( sh_rankmath_active() && in_array( $key, array( 'field_sh_seo_title', 'field_sh_seo_description', 'field_sh_seo_og_image', 'field_sh_seo_noindex' ), true ) ) {
			return false; // edited in the Rank Math box
		}
		return $field;
	},
	20
);

/* ------------------------------------------------------------------ migration */

function sh_rankmath_migrated(): bool {
	return (bool) get_option( 'sh_rankmath_migrated' );
}

/** Rank Math's stored value for one of the four fields ('' / array() when empty). */
function sh_rankmath_current( int $id, string $field ) {
	switch ( $field ) {
		case 'sh_seo_title':
			return (string) get_post_meta( $id, 'rank_math_title', true );
		case 'sh_seo_description':
			return (string) get_post_meta( $id, 'rank_math_description', true );
		case 'sh_seo_og_image':
			return (string) get_post_meta( $id, 'rank_math_facebook_image', true );
		case 'sh_seo_noindex':
			$r = get_post_meta( $id, 'rank_math_robots', true );
			return is_array( $r ) ? array_values( $r ) : array();
	}
	return '';
}

/** Value Core would hand over for a post ('' when there is nothing to move). */
function sh_rankmath_core_value( WP_Post $p, string $field ) {
	$id = (int) $p->ID;
	switch ( $field ) {
		case 'sh_seo_title':
			return sh_core_plain( sh_core_field( 'sh_seo_title', $id, '' ) );
		case 'sh_seo_description':
			$d = sh_core_plain( sh_core_field( 'sh_seo_description', $id, '' ) );
			if ( '' === $d && in_array( $p->post_type, array( 'case_study', 'team_member' ), true ) ) {
				// these records have no body text for Rank Math's default (%excerpt%) to use
				$d = sh_seo_description_for( $p );
			}
			return $d;
		case 'sh_seo_og_image':
			$img = (int) sh_core_field( 'sh_seo_og_image', $id, 0 );
			return $img ? (string) wp_get_attachment_image_url( $img, 'full' ) : '';
		case 'sh_seo_noindex':
			return sh_core_field( 'sh_seo_noindex', $id, false ) ? array( 'noindex' ) : array();
	}
	return '';
}

/**
 * Rows of the move: one per post and field that has a Core value.
 *
 * @return array<int,array{id:int,title:string,type:string,field:string,label:string,core:mixed,rank_math:mixed,action:string}>
 *         action: 'fill' (Rank Math empty → will be filled) | 'same' | 'keep' (Rank Math has its own value)
 */
function sh_rankmath_plan(): array {
	$rows  = array();
	$posts = get_posts(
		array(
			'post_type'      => array( 'page', 'post', 'case_study', 'team_member' ),
			'post_status'    => array( 'publish', 'draft', 'pending', 'private', 'future' ),
			'posts_per_page' => -1,
			'orderby'        => array( 'post_type' => 'ASC', 'menu_order' => 'ASC', 'title' => 'ASC' ),
		)
	);
	foreach ( $posts as $p ) {
		foreach ( sh_rankmath_fields() as $field => $label ) {
			$core = sh_rankmath_core_value( $p, $field );
			if ( '' === $core || array() === $core ) {
				continue;
			}
			$rm = sh_rankmath_current( (int) $p->ID, $field );
			if ( 'sh_seo_noindex' === $field ) {
				$action = in_array( 'noindex', $rm, true ) ? 'same' : ( $rm ? 'keep' : 'fill' );
			} else {
				$action = '' === $rm ? 'fill' : ( $rm === $core ? 'same' : 'keep' );
			}
			$rows[] = array(
				'id'        => (int) $p->ID,
				'title'     => get_the_title( $p ),
				'type'      => $p->post_type,
				'field'     => $field,
				'label'     => $label,
				'core'      => $core,
				'rank_math' => $rm,
				'action'    => $action,
			);
		}
	}
	return $rows;
}

/** Writes the 'fill' rows into Rank Math's fields. Returns the number of values written. */
function sh_rankmath_apply( ?array $plan = null ): int {
	$n = 0;
	foreach ( null === $plan ? sh_rankmath_plan() : $plan as $r ) {
		if ( 'fill' !== $r['action'] ) {
			continue;
		}
		$id = (int) $r['id'];
		// re-check just before writing: never replace a value that appeared meanwhile
		$now = sh_rankmath_current( $id, $r['field'] );
		if ( '' !== $now && array() !== $now ) {
			continue;
		}
		switch ( $r['field'] ) {
			case 'sh_seo_title':
				update_post_meta( $id, 'rank_math_title', wp_slash( $r['core'] ) );
				break;
			case 'sh_seo_description':
				update_post_meta( $id, 'rank_math_description', wp_slash( $r['core'] ) );
				break;
			case 'sh_seo_og_image':
				$img = (int) sh_core_field( 'sh_seo_og_image', $id, 0 );
				update_post_meta( $id, 'rank_math_facebook_image', esc_url_raw( $r['core'] ) );
				update_post_meta( $id, 'rank_math_facebook_image_id', $img );
				break;
			case 'sh_seo_noindex':
				update_post_meta( $id, 'rank_math_robots', array( 'noindex' ) );
				break;
		}
		++$n;
	}
	update_option( 'sh_rankmath_migrated', array( 'time' => time(), 'written' => $n ), false );
	return $n;
}

/** Core's design title and description of a post, as handed to Rank Math ('' when none). */
function sh_rankmath_design_values( int $id ): array {
	$p = get_post( $id );
	if ( ! $p || ! sh_rankmath_active() ) {
		return array();
	}
	return array(
		'rank_math_title'       => (string) sh_rankmath_core_value( $p, 'sh_seo_title' ),
		'rank_math_description' => (string) sh_rankmath_core_value( $p, 'sh_seo_description' ),
	);
}

/**
 * After a content update changed a design value: a Rank Math field that still holds the previous
 * design value (copied there automatically, never edited) follows to the new one. A value typed
 * in Rank Math, or written by «نقل عناوين وأوصاف SEO», differs from the old design value and stays.
 */
function sh_rankmath_follow_design( int $id, array $old ): void {
	$new = sh_rankmath_design_values( $id );
	foreach ( $old as $key => $was ) {
		$now = (string) get_post_meta( $id, $key, true );
		if ( '' !== $was && $now === $was && '' !== ( $new[ $key ] ?? '' ) && $new[ $key ] !== $was ) {
			update_post_meta( $id, $key, wp_slash( $new[ $key ] ) );
		}
	}
}

/* ------------------------------------------------------------------ front end */

add_action(
	'plugins_loaded',
	static function () {
		if ( ! sh_rankmath_active() ) {
			return;
		}
		// Before the move has run, a Core value fills only an empty Rank Math field.
		add_filter(
			'rank_math/frontend/title',
			static function ( $title ) {
				$id = sh_seo_object_id();
				if ( ! $id || sh_rankmath_migrated() || '' !== (string) get_post_meta( $id, 'rank_math_title', true ) ) {
					return $title;
				}
				$t = sh_core_plain( sh_core_field( 'sh_seo_title', $id, '' ) );
				return $t ? $t : $title;
			}
		);
		add_filter(
			'rank_math/frontend/description',
			static function ( $desc ) {
				$id = sh_seo_object_id();
				if ( ! $id || sh_rankmath_migrated() || '' !== (string) get_post_meta( $id, 'rank_math_description', true ) ) {
					return $desc;
				}
				$p = get_post( $id );
				$d = $p ? sh_rankmath_core_value( $p, 'sh_seo_description' ) : '';
				return $d ? $d : $desc;
			}
		);
		add_filter( 'rank_math/json_ld', 'sh_rankmath_graph', 100, 2 );
	},
	20
);

/**
 * Rank Math's graph completed in place (one node per entity, existing @ids and links kept):
 *
 * | Node                    | Source                                                              |
 * |-------------------------|---------------------------------------------------------------------|
 * | Organization            | Rank Math's company node (Titles & Meta ← Local SEO), kept as it is; |
 * |                         | its @id is home_url('/#organization') and its type Organization;    |
 * |                         | properties Rank Math leaves empty come from «إعدادات سيو هاوس»     |
 * |                         | (sh_schema_organization()). No rating or review on it.              |
 * | WebSite                 | Rank Math, without the SearchAction                                 |
 * | WebPage                 | Rank Math; Core sets its type (AboutPage, ContactPage,             |
 * |                         | CollectionPage, ProfilePage) and mainEntity                         |
 * | BreadcrumbList          | Core: the trail shown on the page (replaces Rank Math's)            |
 * | BlogPosting             | Rank Math; Core sets the headline to the H1, the author to the      |
 * |                         | credited team profile, and keeps only the article's real image      |
 * | Article (case study)    | Rank Math when its schema is on for case studies, completed the     |
 * |                         | same way; otherwise Core's (sh_schema_case_article())               |
 * | Service                 | Core, for service, sector and SEO country pages                     |
 * | ItemList                | Core, the listing shown on each collection page                     |
 * | FAQPage                 | Core, from the visible FAQ section                                  |
 * | Person (team profile)   | Core, on the profile page and as the author of articles             |
 *
 * When Rank Math adds no graph to a post type (its default for case studies and team profiles),
 * Core's graph for that page is merged in. A schema the editor added in Rank Math's Schema tab
 * always wins: Core then adds no node of that type and leaves the editor's node as it is.
 */
function sh_rankmath_graph( $data, $jsonld = null ) {
	if ( ! is_array( $data ) || is_feed() ) {
		return $data;
	}
	$data = sh_rankmath_company( $data, $jsonld );
	foreach ( $data as $k => $node ) {
		if ( is_array( $node ) && in_array( 'WebSite', (array) ( $node['@type'] ?? array() ), true ) ) {
			unset( $data[ $k ]['potentialAction'] ); // no SearchAction
			if ( empty( $node['url'] ) ) {
				$data[ $k ]['url'] = home_url( '/' );
			}
		}
	}
	$url = function_exists( 'sh_seo_canonical' ) ? sh_seo_canonical() : '';
	if ( is_404() || ! $url || ( ! is_singular() && ! is_home() && ! is_front_page() ) ) {
		return sh_schema_merge_by_id( $data );
	}

	// Rank Math adds no graph for a post type whose schema it has switched off (its default for
	// case studies and team profiles): Core's own graph covers that page, as without Rank Math.
	if ( is_singular() && empty( $data['WebPage'] ) && ! array_filter( array_keys( $data ), static fn( $k ) => is_string( $k ) && str_starts_with( $k, 'schema-' ) ) ) {
		unset( $data['BreadcrumbList'] );
		foreach ( sh_schema_graph() as $i => $node ) {
			$data[ 'sh' . $i ] = $node;
		}
		return sh_schema_merge_by_id( $data );
	}

	// schemas the editor set in Rank Math (stored as "schema-<id>")
	$editor_types = array();
	foreach ( $data as $k => $node ) {
		if ( is_string( $k ) && str_starts_with( $k, 'schema-' ) && is_array( $node ) ) {
			$editor_types = array_merge( $editor_types, (array) ( $node['@type'] ?? array() ) );
		}
	}
	$has = static fn( string $type ) => in_array( $type, $editor_types, true );

	$org_ref = sh_schema_org_ref();
	$id      = is_home() ? (int) get_option( 'page_for_posts' ) : ( is_front_page() ? (int) get_option( 'page_on_front' ) : (int) get_queried_object_id() );
	$post    = get_post( $id );
	if ( ! $post ) {
		return sh_schema_merge_by_id( $data );
	}
	if ( 'page' === $post->post_type && 'none' === sh_schema_page_type( $id ) ) {
		return sh_schema_merge_by_id( $data );
	}

	// Rank Math's default Article for pages and team profiles describes them wrongly
	if ( in_array( $post->post_type, array( 'page', 'team_member' ), true ) && ! empty( $data['richSnippet'] ) ) {
		$t = (array) ( $data['richSnippet']['@type'] ?? array() );
		if ( array_intersect( $t, array( 'Article', 'BlogPosting', 'NewsArticle' ) ) ) {
			unset( $data['richSnippet'] );
		}
	}

	// breadcrumb: the visible trail
	$crumbs = sh_schema_breadcrumbs( $url );
	unset( $data['BreadcrumbList'] );
	if ( $crumbs ) {
		$data['BreadcrumbList'] = $crumbs;
		if ( ! empty( $data['WebPage'] ) ) {
			$data['WebPage']['breadcrumb'] = array( '@id' => $crumbs['@id'] );
		}
	}

	$add  = array();
	$main = null;
	if ( 'page' === $post->post_type ) {
		$type  = sh_schema_page_type( $id );
		$types = array( 'about' => 'AboutPage', 'contact' => 'ContactPage', 'collection' => 'CollectionPage' );
		if ( ! empty( $data['WebPage'] ) ) {
			$data['WebPage']['@type'] = $types[ $type ] ?? 'WebPage';
			if ( is_front_page() || in_array( $type, array( 'about', 'contact' ), true ) ) {
				$data['WebPage']['about'] = $org_ref;
			}
		}
		if ( 'about' === $type ) {
			$main = $org_ref['@id'];
		}
		if ( 'service' === $type && ! $has( 'Service' ) ) {
			$service        = sh_schema_service( $id, $url, $org_ref );
			$add['Service'] = $service;
			$main           = $service['@id'];
		}
		if ( 'collection' === $type && ! $has( 'ItemList' ) ) {
			$list = sh_schema_item_list( sh_schema_listing( $id ), $url );
			if ( $list ) {
				$add['ItemList'] = $list;
				$main            = $list['@id'];
			}
		}
		// one FAQ source: none when the editor or Rank Math already has one
		$faq_found = $has( 'FAQPage' ) || array_filter( $data, static fn( $n ) => is_array( $n ) && in_array( 'FAQPage', (array) ( $n['@type'] ?? array() ), true ) );
		$faq       = $faq_found ? array() : sh_schema_faq( $id, $url );
		if ( $faq ) {
			$add['FAQPage'] = $faq;
		}
	} elseif ( 'team_member' === $post->post_type && ! $has( 'Person' ) ) {
		$person = sh_schema_person( $id );
		if ( $person ) {
			$add['Person'] = $person;
			$main          = $person['@id'];
			if ( ! empty( $data['WebPage'] ) ) {
				$data['WebPage']['@type'] = 'ProfilePage';
			}
		}
	} elseif ( in_array( $post->post_type, array( 'post', 'case_study' ), true ) ) {
		$data = sh_rankmath_article( $data, $post, $url, $add );
	}
	if ( $main && ! empty( $data['WebPage'] ) ) {
		$data['WebPage']['mainEntity'] = array( '@id' => $main );
	}
	foreach ( $add as $k => $node ) {
		$data[ 'sh' . $k ] = $node;
	}
	return sh_schema_merge_by_id( $data );
}

/**
 * Rank Math's company node as the company: @id home_url('/#organization') (references follow when
 * Rank Math used another one, e.g. "/#person" while its Local SEO type is "Person"), type
 * Organization, no rating or review. Rank Math's own values stay; what it leaves empty comes from
 * «إعدادات سيو هاوس»; sameAs lists are joined. Pages where Rank Math prints no company node (archives
 * drop its logo; case studies and team profiles get no Rank Math graph) receive the same name, logo
 * and profiles from Rank Math's settings, so the company reads the same on every page.
 */
function sh_rankmath_company( array $data, $jsonld = null ): array {
	$org_id = sh_schema_id( 'organization' );
	$ref    = '';
	foreach ( $data as $node ) {
		if ( is_array( $node ) && in_array( 'WebSite', (array) ( $node['@type'] ?? array() ), true ) && ! empty( $node['publisher']['@id'] ) ) {
			$ref = (string) $node['publisher']['@id'];
		}
	}
	$key = null;
	foreach ( $data as $k => $node ) {
		if ( is_array( $node ) && isset( $node['@type'] ) && ( ( $ref && ( $node['@id'] ?? '' ) === $ref ) || ( ! $ref && 'publisher' === $k ) ) ) {
			$key = $k;
			break;
		}
	}
	// what Rank Math's own company node carries on every page (name, logo, profiles)
	$rm = array();
	if ( is_object( $jsonld ) ) {
		if ( method_exists( $jsonld, 'get_organization_name' ) ) {
			$rm['name'] = (string) $jsonld->get_organization_name();
		}
		if ( method_exists( $jsonld, 'add_prop' ) ) {
			$jsonld->add_prop( 'image', $rm );
		}
		if ( method_exists( $jsonld, 'get_social_profiles' ) ) {
			$rm['sameAs'] = (array) $jsonld->get_social_profiles();
		}
		$rm = sh_schema_clean( $rm );
	}
	$core = $rm + sh_schema_organization();
	if ( isset( $rm['sameAs'] ) ) {
		$core['sameAs'] = array_values( array_unique( array_merge( $rm['sameAs'], sh_schema_profiles() ) ) );
	}
	if ( null === $key ) {
		$data['publisher'] = array( '@type' => 'Organization', '@id' => $org_id ) + $core;
		return $data;
	}
	$old = (string) ( $data[ $key ]['@id'] ?? '' );
	if ( '' !== $old && $old !== $org_id ) {
		$data = sh_schema_replace_id( $data, $old, $org_id );
	}
	$node          = $data[ $key ];
	$node['@id']   = $org_id;
	$node['@type'] = 'Organization';
	unset( $node['aggregateRating'], $node['review'] );
	foreach ( $core as $p => $v ) {
		if ( '@' === $p[0] ) {
			continue;
		}
		if ( ! isset( $node[ $p ] ) || '' === $node[ $p ] || array() === $node[ $p ] ) {
			$node[ $p ] = $v;
		} elseif ( 'sameAs' === $p ) {
			$node[ $p ] = array_values( array_unique( array_merge( (array) $node[ $p ], (array) $v ) ) );
		}
	}
	$data[ $key ] = $node;
	return $data;
}

/**
 * Article / BlogPosting of a post or case study, completed in place: headline = the H1 shown,
 * author = what the page credits, image = the article's real image only.
 */
function sh_rankmath_article( array $data, WP_Post $post, string $url, array &$add ): array {
	$org_ref = sh_schema_org_ref();
	$keys    = array();
	foreach ( $data as $k => $node ) {
		if ( is_array( $node ) && ! ( is_string( $k ) && str_starts_with( $k, 'schema-' ) ) && array_intersect( (array) ( $node['@type'] ?? array() ), array( 'Article', 'BlogPosting', 'NewsArticle' ) ) ) {
			$keys[] = $k;
		}
	}
	if ( ! $keys ) {
		if ( 'case_study' === $post->post_type ) {
			$add['Article'] = sh_schema_case_article( $post, $url, $org_ref, (string) ( $data['WebPage']['description'] ?? '' ) );
		}
		return $data;
	}

	// author
	$author = null; // null = keep Rank Math's; array() = none
	if ( 'case_study' === $post->post_type ) {
		$author = $org_ref;
	} else {
		[ $member, $linked ] = sh_schema_post_writer( $post );
		if ( $member ) {
			$person              = sh_schema_person( $member );
			$add['AuthorPerson'] = $person;
			$author              = array( '@id' => $person['@id'] );
		} elseif ( $linked ) {
			$author = array(); // the article points to a deleted or hidden profile: no author is made up
		} elseif ( '' === trim( (string) get_the_author_meta( 'display_name', (int) $post->post_author ) ) ) {
			$author = $org_ref; // the page credits the company's team
		}
	}

	$real    = sh_schema_post_image( $post );
	$real_id = $real ? (int) attachment_url_to_postid( $real ) : 0;
	foreach ( $keys as $k ) {
		$title                    = sh_core_plain( get_the_title( $post ) );
		$data[ $k ]['headline']   = $title;
		$data[ $k ]['name']       = $title;
		$data[ $k ]['publisher']  = $org_ref;
		if ( 'case_study' === $post->post_type ) {
			$summary = sh_core_plain( sh_core_field( 'summary', $post->ID, '' ) );
			if ( '' !== $summary ) {
				$data[ $k ]['description'] = $summary;
			}
		}
		if ( null !== $author ) {
			$old = (string) ( $data[ $k ]['author']['@id'] ?? '' );
			if ( $author ) {
				$data[ $k ]['author'] = $author;
			} else {
				unset( $data[ $k ]['author'] );
			}
			$data = sh_schema_drop_unreferenced( $data, $old );
		}
		// image: the article's own image, never a site-wide default
		$img = $data[ $k ]['image'] ?? null;
		$cur = is_array( $img ) ? (string) ( $img['@id'] ?? ( $img['url'] ?? '' ) ) : (string) $img;
		if ( '' !== $cur ) {
			$same = $real && ( $cur === $real || ( $real_id && (int) attachment_url_to_postid( $cur ) === $real_id ) );
			if ( ! $same ) {
				unset( $data[ $k ]['image'] );
				if ( ( $data['WebPage']['primaryImageOfPage']['@id'] ?? '' ) === $cur ) {
					unset( $data['WebPage']['primaryImageOfPage'] );
				}
				$data = sh_schema_drop_unreferenced( $data, $cur );
			}
		}
		if ( empty( $data[ $k ]['image'] ) && $real ) {
			$data[ $k ]['image'] = array(
				'@type' => 'ImageObject',
				'url'   => $real,
			);
		}
	}
	return $data;
}

/* ------------------------------------------------------------------ admin: preview and move */

add_action(
	'admin_menu',
	static function () {
		if ( sh_rankmath_active() ) {
			add_submenu_page( 'seohouse-settings', __( 'نقل السيو إلى Rank Math', 'seohouse-core' ), __( 'نقل السيو إلى Rank Math', 'seohouse-core' ), 'manage_options', 'seohouse-rankmath', 'sh_rankmath_admin_page' );
		}
	},
	30
);

function sh_rankmath_admin_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$done = null;
	if ( isset( $_POST['sh_rankmath_apply'] ) ) {
		check_admin_referer( 'sh_rankmath_apply' );
		$done = sh_rankmath_apply();
	}
	if ( isset( $_POST['sh_rankmath_controls'] ) ) {
		check_admin_referer( 'sh_rankmath_controls' );
		$on = sh_rankmath_enable_controls();
		echo '<div class="notice notice-success"><p>' . esc_html( $on ? 'فُعّل صندوق Rank Math لـ: ' . implode( '، ', $on ) : 'صندوق Rank Math مفعّل لكل الأنواع.' ) . '</p></div>';
	}
	$plan   = sh_rankmath_plan();
	$counts = array_count_values( wp_list_pluck( $plan, 'action' ) );
	$fmt    = static fn( $v ) => is_array( $v ) ? implode( ', ', $v ) : (string) $v;
	$names  = array( 'fill' => 'سيُنقل (حقل Rank Math فارغ)', 'same' => 'مطابق', 'keep' => 'يبقى كما هو (قيمة حرّرها Rank Math)' );
	echo '<div class="wrap"><h1>' . esc_html__( 'نقل عنوان ووصف البحث إلى Rank Math', 'seohouse-core' ) . '</h1>';
	if ( null !== $done ) {
		echo '<div class="notice notice-success"><p>' . esc_html( sprintf( 'نُقلت %d قيمة إلى حقول Rank Math الفارغة. لم تتغير أي قيمة موجودة.', $done ) ) . '</p></div>';
	}
	if ( isset( $_GET['skipped'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		echo '<div class="notice notice-success"><p>' . esc_html__( 'Rank Math تعمل الآن بدون حساب. صندوقها يظهر في المحرر.', 'seohouse-core' ) . '</p></div>';
	}
	$labels = array( 'page' => 'الصفحات', 'post' => 'المقالات', 'case_study' => 'دراسات الحالة', 'team_member' => 'فريق العمل' );
	$off    = sh_rankmath_controls_off();
	echo '<h2>' . esc_html__( 'صندوق Rank Math في المحرر (SEO Controls)', 'seohouse-core' ) . '</h2><table class="widefat" style="max-width:40em"><tbody>';
	foreach ( sh_rankmath_post_types() as $t ) {
		if ( post_type_exists( $t ) ) {
			echo '<tr><td>' . esc_html( $labels[ $t ] ) . '</td><td>' . ( in_array( $t, $off, true ) ? '<strong>' . esc_html__( 'مطفأ', 'seohouse-core' ) . '</strong>' : esc_html__( 'ظاهر', 'seohouse-core' ) ) . '</td></tr>';
		}
	}
	echo '</tbody></table>';
	if ( $off ) {
		echo '<form method="post">';
		wp_nonce_field( 'sh_rankmath_controls' );
		submit_button( __( 'أظهر صندوق Rank Math لكل الأنواع', 'seohouse-core' ), 'secondary', 'sh_rankmath_controls', false );
		echo '</form>';
	}
	echo '<p style="max-width:62em">' . esc_html__( 'مع تفعيل Rank Math يُحرَّر عنوان البحث ووصفه وصورة المشاركة والفهرسة من صندوق Rank Math في محرر الصفحة فقط، وتُخفى الحقول المكررة من «السيو ومسار التنقل». هذه الصفحة تنقل القيم الموجودة في حقول سيو هاوس مرة واحدة إلى حقول Rank Math الفارغة فقط؛ القيم التي حرّرتها في Rank Math تبقى كما هي.', 'seohouse-core' ) . '</p>';
	$last = get_option( 'sh_rankmath_migrated' );
	if ( is_array( $last ) ) {
		echo '<p>' . esc_html( sprintf( 'آخر نقل: %s (%d قيمة).', wp_date( 'Y-m-d H:i', (int) $last['time'] ), (int) $last['written'] ) ) . '</p>';
	}
	echo '<p><strong>' . esc_html( sprintf( 'سيُنقل: %d — مطابق: %d — يبقى: %d', $counts['fill'] ?? 0, $counts['same'] ?? 0, $counts['keep'] ?? 0 ) ) . '</strong></p>';
	echo '<form method="post">';
	wp_nonce_field( 'sh_rankmath_apply' );
	submit_button( __( 'انقل القيم إلى حقول Rank Math الفارغة', 'seohouse-core' ), 'primary', 'sh_rankmath_apply', false, empty( $counts['fill'] ) ? array( 'disabled' => 'disabled' ) : array() );
	echo '</form>';
	echo '<table class="widefat striped" style="margin-top:16px"><thead><tr><th>الصفحة</th><th>الحقل</th><th>قيمة سيو هاوس</th><th>قيمة Rank Math الحالية</th><th>الإجراء</th></tr></thead><tbody>';
	foreach ( $plan as $r ) {
		echo '<tr><td><a href="' . esc_url( (string) get_edit_post_link( $r['id'] ) ) . '">' . esc_html( $r['title'] ) . '</a> <small>(' . esc_html( $r['type'] ) . ')</small></td><td>' . esc_html( $r['label'] ) . '</td><td>' . esc_html( $fmt( $r['core'] ) ) . '</td><td>' . esc_html( $fmt( $r['rank_math'] ) ) . '</td><td>' . esc_html( $names[ $r['action'] ] ) . '</td></tr>';
	}
	echo '</tbody></table>';
	echo '<h2 id="sh-schema">' . esc_html__( 'البيانات المنظمة', 'seohouse-core' ) . '</h2><p style="max-width:62em">' . esc_html__( 'Rank Math يخرج الشركة وWebSite وWebPage والمقالات، وسيو هاوس يكمّلها داخل نفس المخطط: نوع الصفحة (عن الشركة، اتصل بنا، صفحات التجميع، الملف الشخصي)، وService لصفحات الخدمات والقطاعات والدول، وItemList للقوائم الظاهرة، وFAQPage من قسم الأسئلة الظاهر، وBreadcrumbList من مسار التنقل، وPerson لأعضاء الفريق وكتّاب المقالات. بيانات الشركة التي يتركها Rank Math فارغة تُكمل من «إعدادات سيو هاوس»، وما يحدده Rank Math يبقى كما هو. أي Schema تضيفه من تبويب Schema في Rank Math يبقى ولا يُضاف مقابله.', 'seohouse-core' ) . '</p>';
	echo '<p><a class="button" href="' . esc_url( admin_url( 'admin.php?page=seohouse-schema' ) ) . '">' . esc_html__( 'فحص البيانات المنظمة', 'seohouse-core' ) . '</a></p></div>';
}

/* ------------------------------------------------------------------ sitemap cache after a move or an update */

/**
 * Rank Math keeps the XML sitemaps it built in files (uploads/rank-math). A site move that copies
 * files over the old site (All-in-One WP Migration, FTP) leaves the old site's cached sitemaps
 * there, and Rank Math keeps serving them: addresses of the previous site, old dates. When Core is
 * installed or updated, the cache is emptied once so Rank Math builds the sitemaps from this site's
 * content. Nothing else changes (no content, no SEO values, no Rank Math settings).
 */
function sh_rankmath_sitemap_cache_clear(): bool {
	if ( ! class_exists( '\RankMath\Sitemap\Cache' ) || ! method_exists( '\RankMath\Sitemap\Cache', 'invalidate_storage' ) ) {
		return false;
	}
	\RankMath\Sitemap\Cache::invalidate_storage();
	return true;
}

add_action(
	'admin_init',
	static function () {
		if ( get_option( 'sh_core_sitemap_cache_version' ) === SH_CORE_VERSION || ! sh_rankmath_active() ) {
			return;
		}
		if ( sh_rankmath_sitemap_cache_clear() ) {
			update_option( 'sh_core_sitemap_cache_version', SH_CORE_VERSION, false );
		}
	}
);
