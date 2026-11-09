<?php
/**
 * Arabic + English (Polylang). Arabic is the default language at the site root; English lives under /en/.
 * Without Polylang everything here is inert and the site is Arabic as before.
 *
 * - Language helpers for the theme and Core: sh_lang(), sh_is_en(), sh_dir(), sh_arrow(), sh_translation(),
 *   sh_tr_id(), sh_page_url().
 * - English values of «إعدادات سيو هاوس» texts (option sh_options_en), applied on English pages only.
 * - Page links stored in fields point at the same page in the visitor's language when it is published.
 * - Polylang's own hreflang is off: the theme prints one set (inc/seo-hreflang.php) from sh_i18n_alternates().
 * - REST (seohouse/v1, administrators): i18n/setup, i18n/menus, i18n/options, content (POST) and
 *   content/robots — used to set up and fill the English version remotely (see docs/round-2.9.0-english.md).
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

const SH_LANG_DEFAULT = 'ar';
const SH_LANGS        = array(
	'ar' => array( 'locale' => 'ar', 'name' => 'العربية', 'rtl' => true, 'term_group' => 0, 'flag' => 'sa' ),
	'en' => array( 'locale' => 'en_US', 'name' => 'English', 'rtl' => false, 'term_group' => 1, 'flag' => 'us' ),
);

/* ------------------------------------------------------------------ helpers */

function sh_i18n_active(): bool {
	return function_exists( 'pll_current_language' ) && function_exists( 'pll_get_post' ) && function_exists( 'PLL' );
}

/** Language of the current front-end request: 'en' or 'ar'. */
function sh_lang(): string {
	if ( ! sh_i18n_active() ) {
		return SH_LANG_DEFAULT;
	}
	return 'en' === pll_current_language() ? 'en' : SH_LANG_DEFAULT;
}

function sh_is_en(): bool {
	return 'en' === sh_lang();
}

function sh_dir(): string {
	return sh_is_en() ? 'ltr' : 'rtl';
}

/** Arrow for "forward" (next / go) or "back" in the reading direction of the current language. */
function sh_arrow( string $to = 'next' ): string {
	$forward_is_left = 'rtl' === sh_dir();
	return ( 'next' === $to ) === $forward_is_left ? '←' : '→';
}

/** Published translation of a post in $lang (default: the current language), or 0. */
function sh_translation( int $id, string $lang = '' ): int {
	if ( $id <= 0 || ! sh_i18n_active() ) {
		return 0;
	}
	$t = (int) pll_get_post( $id, $lang ? $lang : sh_lang() );
	return $t && 'publish' === get_post_status( $t ) ? $t : 0;
}

/** The post in the current language when its translation is published, otherwise the same ID. */
function sh_tr_id( int $id ): int {
	$t = sh_translation( $id );
	return $t ? $t : $id;
}

/** Permalink, in the current language, of the page published at an Arabic path ('services/seo'); '' when none. */
function sh_page_url( string $path ): string {
	$page = get_page_by_path( trim( $path, '/' ), OBJECT, 'page' );
	if ( ! $page || 'publish' !== $page->post_status ) {
		return '';
	}
	return (string) get_permalink( sh_tr_id( (int) $page->ID ) );
}

/** Home URL in the current language. */
function sh_home_url(): string {
	return sh_is_en() && function_exists( 'pll_home_url' ) ? (string) pll_home_url( 'en' ) : home_url( '/' );
}

/** Language slug of a post ('ar' when Polylang is off or the post has none). */
function sh_post_lang( int $id ): string {
	$l = sh_i18n_active() ? (string) pll_get_post_language( $id ) : '';
	return $l ? $l : SH_LANG_DEFAULT;
}

/** Published, public, not noindex and canonical to itself: a page search engines may be pointed at. */
function sh_i18n_indexable( int $id ): bool {
	$p = get_post( $id );
	if ( ! $p || 'publish' !== $p->post_status || '' !== $p->post_password ) {
		return false;
	}
	if ( in_array( 'noindex', (array) get_post_meta( $id, 'rank_math_robots', true ), true ) ) {
		return false;
	}
	$custom = trim( (string) get_post_meta( $id, 'rank_math_canonical_url', true ) );
	return '' === $custom || untrailingslashit( $custom ) === untrailingslashit( (string) get_permalink( $id ) );
}

/**
 * Indexable language versions of a post: lang => [id, url]. Empty when the post itself is not
 * indexable or has no indexable translation (nothing to announce).
 *
 * @return array<string,array{0:int,1:string}>
 */
function sh_i18n_alternates( int $id ): array {
	if ( ! sh_i18n_active() || ! sh_i18n_indexable( $id ) ) {
		return array();
	}
	$out = array();
	foreach ( (array) pll_get_post_translations( $id ) as $lang => $tid ) {
		if ( isset( SH_LANGS[ $lang ] ) && sh_i18n_indexable( (int) $tid ) ) {
			$out[ $lang ] = array( (int) $tid, (string) get_permalink( (int) $tid ) );
		}
	}
	return count( $out ) > 1 ? $out : array();
}

/**
 * Requests that do not come through a language URL (the lead form's REST call and its no-JS POST):
 * messages to the visitor follow the form's page — the "lang" field, or the language of "page".
 */
function sh_i18n_locale_for( array $in ): void {
	$lang = sanitize_key( (string) ( $in['lang'] ?? '' ) );
	if ( '' === $lang && ! empty( $in['page'] ) ) {
		$lang = sh_post_lang( (int) $in['page'] );
	}
	if ( 'en' === $lang && 'en_US' !== determine_locale() ) {
		switch_to_locale( 'en_US' );
	}
}

/* ------------------------------------------------------------------ front end */

// Rank Math's Polylang integration appends the language's home path (en/) to the canonical of the posts
// page as if it were the home URL: /en/blog/en/. The English blog's canonical is its own address.
add_filter(
	'rank_math/frontend/canonical',
	static function ( $canonical ) {
		if ( ! sh_i18n_active() || ! sh_is_en() || ! is_home() || is_front_page() ) {
			return $canonical;
		}
		$url   = (string) get_permalink( (int) get_option( 'page_for_posts' ) );
		$paged = (int) get_query_var( 'paged' );
		return $paged > 1 ? trailingslashit( $url ) . 'page/' . $paged . '/' : $url;
	},
	99
);

// An English address that no longer exists is not sent to a similarly named page (WordPress's guess would
// land on the Arabic article with the same slug): it stays a 404. Recorded moves (sh_i18n_moved) still redirect.
add_filter(
	'do_redirect_guess_404_permalink',
	static fn( $guess ) => sh_i18n_active() && sh_is_en() ? false : $guess
);

// the site name on English pages (titles of archives and of anything without its own SEO title)
add_filter(
	'option_blogname',
	static function ( $name ) {
		if ( is_admin() || ! did_action( 'pll_language_defined' ) || ! sh_is_en() ) {
			return $name;
		}
		$en = trim( (string) get_option( 'options_sh_company_name_en', '' ) );
		return '' !== $en ? $en : 'SEO House';
	}
);

// one hreflang set, printed by the theme from sh_i18n_alternates() (Polylang's would include noindex drafts)
add_filter( 'pll_rel_hreflang_attributes', '__return_empty_array', 99 );

/**
 * English page addresses mirror the Arabic ones: the English version of /services/seo/ksa/ is
 * /en/services/seo/ksa/ (Polylang's free version cannot give two pages the same slug, so the English
 * pages keep their own slugs in the database and their public address is built from the Arabic page).
 * An English page without a published Arabic page, and the English front page (/en/), keep Polylang's.
 * Articles and team members whose internal slug ends in "-en" are mirrored the same way
 * (/en/blog/<slug>/, /en/team/<slug>/); an English item with its own slug (case studies) keeps it.
 */
function sh_i18n_mirror_link( int $id ): string {
	if ( ! sh_i18n_active() || 'en' !== (string) pll_get_post_language( $id ) ) {
		return '';
	}
	$type = (string) get_post_type( $id );
	if ( 'page' !== $type && ! str_ends_with( (string) get_post_field( 'post_name', $id ), '-en' ) ) {
		return '';
	}
	$front = (int) get_option( 'page_on_front' );
	if ( $front && (int) pll_get_post( $front, 'en' ) === $id ) {
		return '';
	}
	$ar = (int) pll_get_post( $id, 'ar' );
	if ( ! $ar || 'publish' !== get_post_status( $ar ) ) {
		return '';
	}
	$home = trailingslashit( (string) get_option( 'home' ) );
	$ar_link = (string) get_permalink( $ar );
	if ( ! str_starts_with( $ar_link, $home ) ) {
		return '';
	}
	return $home . 'en/' . ltrim( substr( $ar_link, strlen( $home ) ), '/' );
}

add_filter(
	'page_link',
	static function ( $link, $id ) {
		$mirror = sh_i18n_mirror_link( (int) $id );
		return '' !== $mirror ? $mirror : $link;
	},
	30,
	2
);
foreach ( array( 'post_link', 'post_type_link' ) as $sh_hook ) {
	add_filter(
		$sh_hook,
		static function ( $link, $post ) {
			$mirror = $post instanceof WP_Post ? sh_i18n_mirror_link( (int) $post->ID ) : '';
			return '' !== $mirror ? $mirror : $link;
		},
		30,
		2
	);
}

/** Category addresses mirror the Arabic ones too: an English term whose slug ends in "-en". */
add_filter(
	'term_link',
	static function ( $link, $term, $taxonomy ) {
		if ( ! sh_i18n_active() || 'category' !== $taxonomy || ! function_exists( 'pll_get_term' ) || ! str_ends_with( (string) $term->slug, '-en' ) || 'en' !== (string) pll_get_term_language( $term->term_id ) ) {
			return $link;
		}
		$ar = (int) pll_get_term( $term->term_id, 'ar' );
		if ( ! $ar ) {
			return $link;
		}
		$home    = trailingslashit( (string) get_option( 'home' ) );
		$ar_link = (string) get_term_link( $ar, $taxonomy );
		return str_starts_with( $ar_link, $home ) ? $home . 'en/' . ltrim( substr( $ar_link, strlen( $home ) ), '/' ) : $link;
	},
	30,
	3
);

// /en/<Arabic path>/ opens the English version of that Arabic page, article, team member or category.
add_filter(
	'request',
	static function ( $qv ) {
		if ( ! sh_i18n_active() ) {
			return $qv;
		}
		$lang = (string) ( $qv['lang'] ?? '' );
		if ( 'en' !== $lang && 'en' !== (string) pll_current_language() ) {
			return $qv;
		}
		$to_en = static function ( int $ar ): int {
			if ( ! $ar || 'ar' !== (string) pll_get_post_language( $ar ) ) {
				return 0;
			}
			$en = (int) pll_get_post( $ar, 'en' );
			return $en && 'publish' === get_post_status( $en ) ? $en : 0;
		};
		if ( ! empty( $qv['pagename'] ) ) {
			$ar = get_page_by_path( (string) $qv['pagename'], OBJECT, 'page' );
			$en = $ar ? $to_en( (int) $ar->ID ) : 0; // not an Arabic path (an older English address): WordPress finds the page and it is redirected
			if ( $en ) {
				unset( $qv['pagename'] );
				$qv['page_id'] = $en;
			}
			return $qv;
		}
		foreach ( array( 'team_member' => 'team_member', 'name' => 'post' ) as $var => $type ) {
			if ( empty( $qv[ $var ] ) ) {
				continue;
			}
			$type  = 'name' === $var && ! empty( $qv['post_type'] ) && is_string( $qv['post_type'] ) ? $qv['post_type'] : $type;
			$found = array_values( array_filter( get_posts( array( 'name' => (string) $qv[ $var ], 'post_type' => $type, 'post_status' => 'publish', 'numberposts' => 5, 'fields' => 'ids' ) ), static fn( $i ) => 'ar' === (string) pll_get_post_language( (int) $i ) ) );
			$en    = $found ? $to_en( (int) $found[0] ) : 0;
			if ( $en && str_ends_with( (string) get_post_field( 'post_name', $en ), '-en' ) ) {
				// also the post type's own query var (case_study=<slug>), or WordPress looks for both and finds nothing (2.9.19)
				unset( $qv[ $var ], $qv['name'], $qv[ $type ] );
				$qv['p']         = $en;
				$qv['post_type'] = $type;
			} elseif ( $found && ! $en ) {
				// get_posts() ignores Polylang's "lang" (filters suppressed): the language is checked here
				$own = array_filter( get_posts( array( 'name' => (string) $qv[ $var ], 'post_type' => $type, 'post_status' => 'publish', 'numberposts' => 5, 'fields' => 'ids' ) ), static fn( $i ) => 'en' === (string) pll_get_post_language( (int) $i ) );
				if ( ! $own ) {
					// an Arabic article without an English version: /en/... is a 404 (Polylang would send it to the Arabic one)
					return array( 'error' => '404' );
				}
			}
			return $qv;
		}
		if ( ! empty( $qv['category_name'] ) && function_exists( 'pll_get_term' ) ) {
			// Polylang filters term lookups by the request's language: ask for the Arabic term explicitly
			$ar = get_terms( array( 'taxonomy' => 'category', 'slug' => sanitize_title( basename( (string) $qv['category_name'] ) ), 'lang' => 'ar', 'hide_empty' => false, 'number' => 1 ) );
			$ar = is_array( $ar ) && $ar ? $ar[0] : null;
			$en = $ar ? (int) pll_get_term( $ar->term_id, 'en' ) : 0;
			if ( $en ) {
				unset( $qv['category_name'] );
				$qv['cat'] = $en;
			}
		}
		return $qv;
	},
	20
);

// Older English addresses move permanently to the current one. English requests only; Arabic URLs are
// never redirected here. A page or item answering at another address than its own is redirected; an
// address that no longer answers is redirected when it is recorded in sh_i18n_moved (old path => ID),
// which the imports fill whenever they change an English address.
add_action(
	'template_redirect',
	static function () {
		if ( ! sh_i18n_active() || 'en' !== sh_lang() || is_admin() ) {
			return;
		}
		$uri  = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_unslash( $_SERVER['REQUEST_URI'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$path = rawurldecode( (string) wp_parse_url( $uri, PHP_URL_PATH ) );
		$qs   = (string) wp_parse_url( $uri, PHP_URL_QUERY );
		$to   = '';
		if ( is_404() ) {
			$old = (array) get_option( 'sh_i18n_moved', array() );
			$key = '/' . trim( $path, '/' ) . '/';
			$to  = isset( $old[ $key ] ) && 'publish' === get_post_status( (int) $old[ $key ] ) ? (string) get_permalink( (int) $old[ $key ] ) : '';
		} elseif ( is_singular() ) {
			$want = sh_i18n_mirror_link( (int) get_queried_object_id() );
			if ( '' !== $want && untrailingslashit( rawurldecode( (string) wp_parse_url( $want, PHP_URL_PATH ) ) ) !== untrailingslashit( $path ) ) {
				$to = $want;
			}
		}
		if ( '' !== $to ) {
			wp_safe_redirect( '' !== $qs ? $to . '?' . $qs : $to, 301, 'SEO House Core' );
			exit;
		}
	},
	1
);

// a page link stored as an Arabic page ID opens the English page on English pages, once that page is published
add_filter(
	'acf/load_value/type=page_link',
	static function ( $value ) {
		if ( is_admin() || ! sh_is_en() ) {
			return $value;
		}
		if ( is_numeric( $value ) ) {
			return sh_tr_id( (int) $value );
		}
		if ( is_array( $value ) ) {
			return array_map( static fn( $v ) => is_numeric( $v ) ? sh_tr_id( (int) $v ) : $v, $value );
		}
		return $value;
	},
	20
);

/** English values for «إعدادات سيو هاوس» (same names and shape as the options; only texts that differ). */
function sh_options_en(): array {
	static $o = null;
	if ( null === $o ) {
		$o = (array) get_option( 'sh_options_en', array() );
	}
	return $o;
}

/** $over laid over $base: strings replace, arrays merge by key / position, missing keys keep the base. */
function sh_i18n_merge( $base, $over ) {
	if ( ! is_array( $over ) ) {
		return ( null === $over || '' === $over ) ? $base : $over;
	}
	$base = is_array( $base ) ? $base : array();
	foreach ( $over as $k => $v ) {
		$base[ $k ] = sh_i18n_merge( $base[ $k ] ?? null, $v );
	}
	return $base;
}

add_filter(
	'acf/format_value',
	static function ( $value, $post_id, $field ) {
		if ( ( is_admin() && ! wp_doing_ajax() ) || ! in_array( $post_id, array( 'options', 'option' ), true ) || ! sh_is_en() ) {
			return $value;
		}
		$o = sh_options_en();
		$n = (string) ( $field['name'] ?? '' );
		return '' !== $n && array_key_exists( $n, $o ) ? sh_i18n_merge( $value, $o[ $n ] ) : $value;
	},
	20,
	3
);

// Site search covers pages and posts (in the visitor's language) and case studies and team members, which
// have no language: Polylang filters a mixed query by language as a whole, so those are let through here.
add_action(
	'pre_get_posts',
	static function ( WP_Query $q ) {
		if ( is_admin() || ! $q->is_main_query() || ! $q->is_search() || ! sh_i18n_active() ) {
			return;
		}
		$tq = $q->get( 'tax_query' );
		if ( ! is_array( $tq ) ) {
			return;
		}
		foreach ( $tq as $i => $clause ) {
			if ( is_array( $clause ) && 'language' === ( $clause['taxonomy'] ?? '' ) ) {
				$tq[ $i ] = array(
					'relation' => 'OR',
					$clause,
					array( 'taxonomy' => 'language', 'operator' => 'NOT EXISTS' ),
				);
			}
		}
		$q->set( 'tax_query', $tq );
	},
	99
);

// The other language's address (Polylang links, Rank Math og:locale:alternate) only once that page is
// released: an English page under review stays unannounced, and English has no archives yet.
add_filter(
	'pll_translation_url',
	static function ( $url, $lang ) {
		if ( 'en' !== $lang || sh_is_en() ) {
			return $url;
		}
		$id = is_front_page() ? (int) get_option( 'page_on_front' ) : ( is_singular() ? (int) get_queried_object_id() : 0 );
		$t  = $id ? sh_translation( $id, 'en' ) : 0;
		return $t && sh_i18n_indexable( $t ) ? $url : '';
	},
	20,
	2
);

/* ------------------------------------------------------------------ REST: setup */

/** Polylang options for this site: language in the URL directory, Arabic without prefix, no browser redirects. */
function sh_i18n_setup(): array {
	if ( ! sh_i18n_active() ) {
		return array( 'ok' => false, 'message' => 'Polylang غير مفعّلة.' );
	}
	$model = PLL()->model;
	$done  = array();

	// 2. Options, stated (a wrong one would move or redirect Arabic URLs). redirect_lang: the English front
	//    page is /en/, not /en/<its slug>/ (the Arabic one stays /).
	foreach ( array( 'force_lang' => 1, 'hide_default' => true, 'rewrite' => true, 'browser' => false, 'redirect_lang' => true, 'media_support' => false ) as $k => $v ) {
		if ( $model->options->get( $k ) !== $v ) {
			$model->options->set( $k, $v );
			$done[] = $k;
		}
	}
	$model->options->save();

	// 3. Languages: Arabic first (becomes the default), then English.
	foreach ( SH_LANGS as $slug => $l ) {
		if ( ! $model->get_language( $slug ) ) {
			$r = $model->languages->add( array( 'slug' => $slug ) + $l );
			if ( is_wp_error( $r ) ) {
				return array( 'ok' => false, 'message' => $slug . ': ' . $r->get_error_message(), 'done' => $done );
			}
			$done[] = 'language ' . $slug;
		}
	}
	if ( SH_LANG_DEFAULT !== $model->options->get( 'default_lang' ) ) {
		$model->languages->update_default( SH_LANG_DEFAULT );
		$done[] = 'default ar';
	}
	$model->clean_languages_cache();

	// 4. Arabic menus keep their places: the theme's current locations go into Polylang's Arabic slots
	//    (on the front end Polylang reads menu locations per language; it keeps slots of existing languages only).
	$theme   = get_option( 'stylesheet' );
	$mods    = (array) get_option( 'theme_mods_' . $theme, array() );
	$current = (array) ( $mods['nav_menu_locations'] ?? array() );
	$menus   = (array) $model->options->get( 'nav_menus' );
	foreach ( $current as $loc => $menu_id ) {
		if ( $menu_id && empty( $menus[ $theme ][ $loc ][ SH_LANG_DEFAULT ] ) ) {
			$menus[ $theme ][ $loc ][ SH_LANG_DEFAULT ] = (int) $menu_id;
			$done[] = 'menu ' . $loc . ' → ar';
		}
	}
	$model->options->set( 'nav_menus', $menus );

	// 5. Case studies and team members are translated too (their English versions are listed on English pages).
	$types = (array) $model->options->get( 'post_types' );
	$want  = array_values( array_unique( array_merge( $types, array( 'case_study', 'team_member' ) ) ) );
	if ( $want !== $types ) {
		$model->options->set( 'post_types', $want );
		$model->options->save();
		$done[] = 'post types case_study, team_member';
	}

	// 6. Everything that has no language yet is Arabic.
	$model->set_language_in_mass();
	$model->options->save();
	delete_option( 'rewrite_rules' ); // rebuilt on the next request, with Polylang's rules for the newly translated types
	sh_i18n_refresh_sitemaps();

	return array(
		'ok'        => true,
		'done'      => $done,
		'languages' => wp_list_pluck( $model->get_languages_list(), 'locale', 'slug' ),
		'default'   => $model->options->get( 'default_lang' ),
		'nav_menus' => $model->options->get( 'nav_menus' ),
		'no_lang'   => array_map( static fn( $v ) => is_array( $v ) ? count( $v ) : (int) $v, (array) $model->get_objects_with_no_lang( 100 ) ),
	);
}

/* ------------------------------------------------------------------ REST: content */

/**
 * Creates or updates the English version of an Arabic page from content-pack data.
 *
 * Body (JSON): source (Arabic page ID), slug, parent_source (Arabic ID of the parent page, 0 = top level),
 * title, sections (as GET content returns them), seo {rank_math_title, rank_math_description,
 * rank_math_focus_keyword, sh_crumb, sh_schema_service}, index (bool, default false → noindex).
 * The Arabic page is never written; only its translation link is added.
 */
function sh_i18n_import_page( array $in ) {
	if ( ! sh_i18n_active() || ! sh_core_acf_ready() ) {
		return new WP_Error( 'sh_i18n', 'Polylang وACF مطلوبتان.', array( 'status' => 409 ) );
	}
	$source = get_post( (int) ( $in['source'] ?? 0 ) );
	if ( ! $source || 'page' !== $source->post_type || SH_LANG_DEFAULT !== sh_post_lang( $source->ID ) ) {
		return new WP_Error( 'sh_source', 'source: رقم صفحة عربية.', array( 'status' => 400 ) );
	}
	$slug = sanitize_title( (string) ( $in['slug'] ?? '' ) );
	if ( '' === $slug ) {
		return new WP_Error( 'sh_slug', 'slug مطلوب.', array( 'status' => 400 ) );
	}
	$parent = 0;
	if ( ! empty( $in['parent_source'] ) ) {
		$parent = (int) pll_get_post( (int) $in['parent_source'], 'en' );
		if ( ! $parent ) {
			return new WP_Error( 'sh_parent', 'الصفحة الأب ليس لها نسخة إنجليزية بعد.', array( 'status' => 409 ) );
		}
	}
	$tpl = (string) get_page_template_slug( $source );
	$id  = (int) pll_get_post( $source->ID, 'en' );
	$new = ! $id;
	$args = array(
		'post_type'    => 'page',
		'post_title'   => wp_strip_all_tags( (string) ( $in['title'] ?? $source->post_title ) ),
		'post_name'    => $slug,
		'post_parent'  => $parent,
		'post_content' => '',
		'menu_order'   => (int) $source->menu_order,
	);
	if ( $new ) {
		$args['post_status'] = 'draft';
		$id                  = wp_insert_post( wp_slash( $args ), true );
		if ( is_wp_error( $id ) ) {
			return $id;
		}
		pll_set_post_language( $id, 'en' );
	} else {
		$before     = (string) get_permalink( $id );
		$args['ID'] = $id;
		$r          = wp_update_post( wp_slash( $args ), true );
		if ( is_wp_error( $r ) ) {
			return $r;
		}
	}
	update_post_meta( $id, '_wp_page_template', $tpl );
	update_post_meta( $id, '_sh_source_key', 'page:' . sh_core_page_key( $source ) . ':en' );
	$translations       = (array) pll_get_post_translations( $source->ID );
	$translations['ar'] = $source->ID;
	$translations['en'] = $id;
	pll_save_post_translations( $translations );
	PLL()->model->clean_languages_cache(); // languages keep their front page / posts page: a new translation of either must be seen
	if ( ! $new ) {
		sh_i18n_remember_path( $id, $before );
	}

	// sections, by the field keys of the template's field group
	$keys = array();
	foreach ( sh_core_section_fields( $id ) as $f ) {
		$keys[ substr( (string) $f['name'], 2 ) ] = $f['key'];
	}
	$written = 0;
	$missing = array();
	foreach ( (array) ( $in['sections'] ?? array() ) as $s ) {
		$layout = (string) ( $s['acf_fc_layout'] ?? '' );
		if ( ! isset( $keys[ $layout ] ) ) {
			$missing[] = $layout;
			continue;
		}
		unset( $s['acf_fc_layout'] );
		update_field( $keys[ $layout ], $s, $id );
		++$written;
	}
	sh_core_sections( $id, true );

	// SEO
	$seo = (array) ( $in['seo'] ?? array() );
	foreach ( array( 'rank_math_title', 'rank_math_description', 'rank_math_focus_keyword' ) as $m ) {
		$v = trim( wp_strip_all_tags( (string) ( $seo[ $m ] ?? '' ) ) );
		'' === $v ? delete_post_meta( $id, $m ) : update_post_meta( $id, $m, $v );
	}
	update_field( 'sh_seo_title', (string) ( $seo['rank_math_title'] ?? '' ), $id );
	update_field( 'sh_seo_description', (string) ( $seo['rank_math_description'] ?? '' ), $id );
	foreach ( array( 'sh_crumb', 'sh_schema_service', 'sh_schema_type' ) as $f ) {
		if ( array_key_exists( $f, $seo ) && null !== $seo[ $f ] ) {
			update_field( $f, (string) $seo[ $f ], $id );
		}
	}
	sh_i18n_set_index( $id, ! empty( $in['index'] ) );

	if ( $new || 'publish' !== get_post_status( $id ) ) {
		wp_update_post( array( 'ID' => $id, 'post_status' => 'publish' ) );
	}
	if ( function_exists( 'sh_search_index' ) ) {
		sh_search_index( $id );
	}
	sh_i18n_refresh_sitemaps();
	$p = get_post( $id );
	return array(
		'id'       => $id,
		'source'   => $source->ID,
		'created'  => $new,
		'url'      => (string) get_permalink( $id ),
		'slug'     => rawurldecode( $p->post_name ),
		'slug_ok'  => $p->post_name === $slug,
		'sections' => $written,
		'missing'  => $missing,
		'index'    => sh_i18n_indexable( $id ),
	);
}

/** Records an English address that an import is about to change (see sh_i18n_moved). */
function sh_i18n_remember_path( int $id, string $before ): void {
	$after = (string) get_permalink( $id );
	if ( '' === $before || $before === $after ) {
		return;
	}
	$path  = '/' . trim( rawurldecode( (string) wp_parse_url( $before, PHP_URL_PATH ) ), '/' ) . '/';
	$moved = (array) get_option( 'sh_i18n_moved', array() );
	$moved[ $path ] = $id;
	update_option( 'sh_i18n_moved', $moved, false );
}

/** Post types whose English versions are written by content/item. */
const SH_I18N_ITEM_TYPES = array( 'post', 'case_study', 'team_member' );

/** Field name => ACF field for the content groups of a post type (not page sections). */
function sh_i18n_type_fields( string $type ): array {
	$out = array();
	foreach ( acf_get_field_groups( array( 'post_type' => $type ) ) as $g ) {
		if ( str_starts_with( (string) $g['key'], 'group_sh_page_' ) ) {
			continue;
		}
		foreach ( (array) acf_get_fields( $g ) as $f ) {
			if ( '' !== (string) ( $f['name'] ?? '' ) && ! in_array( $f['type'], array( 'tab', 'accordion', 'message' ), true ) ) {
				$out[ $f['name'] ] = $f;
			}
		}
	}
	return $out;
}

/** Post IDs in a stored value (relationship, post object) → their English versions when there are any. */
function sh_i18n_map_ids( $v ) {
	if ( is_array( $v ) ) {
		return array_map( 'sh_i18n_map_ids', $v );
	}
	if ( is_numeric( $v ) && (int) $v > 0 ) {
		$en = (int) pll_get_post( (int) $v, 'en' );
		return $en ? $en : (int) $v;
	}
	return $v;
}

/**
 * Creates or updates the English version of an Arabic article, case study or team member.
 *
 * Body (JSON): source (Arabic ID), slug, title, content (articles: block markup), excerpt,
 * fields {name: value} (as GET content returns them), seo {rank_math_title, rank_math_description,
 * rank_math_focus_keyword}, index (bool). Featured image, author, date and menu order follow the Arabic
 * item; categories become their English versions (create them first with i18n/terms).
 */
function sh_i18n_import_item( array $in ) {
	if ( ! sh_i18n_active() || ! sh_core_acf_ready() ) {
		return new WP_Error( 'sh_i18n', 'Polylang وACF مطلوبتان.', array( 'status' => 409 ) );
	}
	$source = get_post( (int) ( $in['source'] ?? 0 ) );
	if ( ! $source || ! in_array( $source->post_type, SH_I18N_ITEM_TYPES, true ) || SH_LANG_DEFAULT !== sh_post_lang( $source->ID ) ) {
		return new WP_Error( 'sh_source', 'source: رقم مقال أو دراسة حالة أو عضو فريق عربي.', array( 'status' => 400 ) );
	}
	if ( ! pll_is_translated_post_type( $source->post_type ) ) {
		return new WP_Error( 'sh_type', 'نوع المحتوى غير مفعّل للترجمة (شغّل i18n/setup).', array( 'status' => 409 ) );
	}
	$slug = sanitize_title( (string) ( $in['slug'] ?? '' ) );
	if ( '' === $slug ) {
		return new WP_Error( 'sh_slug', 'slug مطلوب.', array( 'status' => 400 ) );
	}
	$id   = (int) pll_get_post( $source->ID, 'en' );
	$new  = ! $id;
	$args = array(
		'post_type'     => $source->post_type,
		'post_title'    => wp_strip_all_tags( (string) ( $in['title'] ?? $source->post_title ) ),
		'post_name'     => $slug,
		'post_content'  => (string) ( $in['content'] ?? '' ),
		'post_excerpt'  => (string) ( $in['excerpt'] ?? '' ),
		'post_author'   => (int) $source->post_author,
		'post_date'     => $source->post_date,
		'post_date_gmt' => $source->post_date_gmt,
		'menu_order'    => (int) $source->menu_order,
	);
	if ( $new ) {
		$args['post_status'] = 'draft';
		$id                  = wp_insert_post( wp_slash( $args ), true );
		if ( is_wp_error( $id ) ) {
			return $id;
		}
		pll_set_post_language( $id, 'en' );
	} else {
		$before     = (string) get_permalink( $id );
		$args['ID'] = $id;
		$r          = wp_update_post( wp_slash( $args ), true );
		if ( is_wp_error( $r ) ) {
			return $r;
		}
	}
	$translations       = (array) pll_get_post_translations( $source->ID );
	$translations['ar'] = $source->ID;
	$translations['en'] = $id;
	pll_save_post_translations( $translations );
	if ( ! $new ) {
		sh_i18n_remember_path( $id, $before );
	}
	$thumb = (int) get_post_thumbnail_id( $source->ID );
	if ( $thumb ) {
		set_post_thumbnail( $id, $thumb );
	} else {
		delete_post_thumbnail( $id );
	}
	if ( 'post' === $source->post_type ) {
		$cats = array();
		foreach ( wp_get_post_categories( $source->ID ) as $c ) {
			$en_c = (int) pll_get_term( (int) $c, 'en' );
			if ( $en_c ) {
				$cats[] = $en_c;
			}
		}
		wp_set_post_categories( $id, $cats );
	}

	$known   = sh_i18n_type_fields( $source->post_type );
	$written = array();
	$skipped = array();
	foreach ( (array) ( $in['fields'] ?? array() ) as $name => $value ) {
		if ( ! isset( $known[ $name ] ) ) {
			$skipped[] = $name;
			continue;
		}
		if ( in_array( $known[ $name ]['type'], array( 'post_object', 'relationship' ), true ) ) {
			$value = sh_i18n_map_ids( $value );
		}
		update_field( $known[ $name ]['key'], $value, $id );
		$written[] = $name;
	}
	$seo = (array) ( $in['seo'] ?? array() );
	foreach ( array( 'rank_math_title', 'rank_math_description', 'rank_math_focus_keyword' ) as $m ) {
		$v = trim( wp_strip_all_tags( (string) ( $seo[ $m ] ?? '' ) ) );
		if ( '' === $v ) {
			delete_post_meta( $id, $m );
		} else {
			update_post_meta( $id, $m, $v );
		}
	}
	sh_i18n_set_index( $id, ! empty( $in['index'] ) );
	if ( $new || 'publish' !== get_post_status( $id ) ) {
		wp_update_post( array( 'ID' => $id, 'post_status' => 'publish' ) );
	}
	if ( function_exists( 'sh_search_index' ) ) {
		sh_search_index( $id );
	}
	sh_i18n_refresh_sitemaps();
	$p = get_post( $id );
	return array(
		'id'      => $id,
		'source'  => $source->ID,
		'created' => $new,
		'url'     => (string) get_permalink( $id ),
		'slug'    => rawurldecode( $p->post_name ),
		'slug_ok' => $p->post_name === $slug,
		'fields'  => count( $written ),
		'skipped' => $skipped,
		'index'   => sh_i18n_indexable( $id ),
	);
}

/**
 * A standalone English article (written for English search, not a translation of an Arabic one): no
 * translation link, so no hreflang and no language switch on it.
 *
 * Body (JSON): id (to update an article created here), title, slug, content (block markup), excerpt,
 * categories (English category IDs), new_categories [{name, slug, description}] (created in English when
 * missing), author (user ID), member (English team member ID for the byline),
 * thumbnail (attachment ID), fields {intro, reading, toc, sidebar_cta, updated_label}, seo
 * {rank_math_title, rank_math_description, rank_math_focus_keyword}, index (bool), status (publish|draft).
 */
function sh_i18n_standalone_article( array $in ) {
	if ( ! sh_i18n_active() || ! sh_core_acf_ready() ) {
		return new WP_Error( 'sh_i18n', 'Polylang وACF مطلوبتان.', array( 'status' => 409 ) );
	}
	$id = (int) ( $in['id'] ?? 0 );
	if ( $id && ( 'post' !== get_post_type( $id ) || 'en' !== sh_post_lang( $id ) || count( (array) pll_get_post_translations( $id ) ) > 1 ) ) {
		return new WP_Error( 'sh_id', 'id: مقال إنجليزي مستقل.', array( 'status' => 400 ) );
	}
	$slug = sanitize_title( (string) ( $in['slug'] ?? '' ) );
	if ( '' === $slug || '' === trim( (string) ( $in['title'] ?? '' ) ) ) {
		return new WP_Error( 'sh_args', 'title وslug مطلوبان.', array( 'status' => 400 ) );
	}
	$author = (int) ( $in['author'] ?? 0 );
	$args   = array(
		'post_type'    => 'post',
		'post_title'   => wp_strip_all_tags( (string) $in['title'] ),
		'post_name'    => $slug,
		'post_content' => (string) ( $in['content'] ?? '' ),
		'post_excerpt' => (string) ( $in['excerpt'] ?? '' ),
		'post_author'  => $author && get_userdata( $author ) ? $author : get_current_user_id(),
	);
	$cats = array_values( array_filter( array_map( 'intval', (array) ( $in['categories'] ?? array() ) ), static fn( $c ) => 'en' === (string) pll_get_term_language( $c ) ) );
	// new_categories: [{name, slug, description}] — standalone English categories (no Arabic counterpart)
	foreach ( (array) ( $in['new_categories'] ?? array() ) as $c ) {
		$c_slug = sanitize_title( (string) ( $c['slug'] ?? '' ) );
		$c_name = trim( wp_strip_all_tags( (string) ( $c['name'] ?? '' ) ) );
		if ( '' === $c_slug || '' === $c_name || str_ends_with( $c_slug, '-en' ) ) {
			continue;
		}
		$have = get_terms( array( 'taxonomy' => 'category', 'slug' => $c_slug, 'lang' => '', 'hide_empty' => false, 'number' => 1 ) );
		$term = is_array( $have ) && $have ? (int) $have[0]->term_id : 0;
		if ( $term && 'en' !== (string) pll_get_term_language( $term ) ) {
			return new WP_Error( 'sh_cat', 'new_categories: slug مستخدم لتصنيف عربي: ' . $c_slug, array( 'status' => 409 ) );
		}
		if ( ! $term ) {
			$r = wp_insert_term( $c_name, 'category', array( 'slug' => $c_slug, 'description' => (string) ( $c['description'] ?? '' ) ) );
			if ( is_wp_error( $r ) ) {
				return $r;
			}
			$term = (int) $r['term_id'];
			pll_set_term_language( $term, 'en' );
		}
		$cats[] = $term;
	}
	$new = ! $id;
	if ( $new ) {
		$args['post_status'] = 'draft';
		$id                  = wp_insert_post( wp_slash( $args ), true );
		if ( is_wp_error( $id ) ) {
			return $id;
		}
		pll_set_post_language( $id, 'en' );
	} else {
		$args['ID'] = $id;
		$r          = wp_update_post( wp_slash( $args ), true );
		if ( is_wp_error( $r ) ) {
			return $r;
		}
	}
	wp_set_post_categories( $id, $cats );
	if ( ! empty( $in['thumbnail'] ) && wp_attachment_is_image( (int) $in['thumbnail'] ) ) {
		set_post_thumbnail( $id, (int) $in['thumbnail'] );
	}
	$known = sh_i18n_type_fields( 'post' );
	foreach ( (array) ( $in['fields'] ?? array() ) as $name => $value ) {
		if ( isset( $known[ $name ] ) && 'author_member' !== $name ) {
			update_field( $known[ $name ]['key'], $value, $id );
		}
	}
	$member = (int) ( $in['member'] ?? 0 );
	if ( $member && 'team_member' === get_post_type( $member ) && 'en' === sh_post_lang( $member ) && isset( $known['author_member'] ) ) {
		update_field( $known['author_member']['key'], $member, $id );
	}
	$seo = (array) ( $in['seo'] ?? array() );
	foreach ( array( 'rank_math_title', 'rank_math_description', 'rank_math_focus_keyword' ) as $m ) {
		$v = trim( wp_strip_all_tags( (string) ( $seo[ $m ] ?? '' ) ) );
		if ( '' === $v ) {
			delete_post_meta( $id, $m );
		} else {
			update_post_meta( $id, $m, $v );
		}
	}
	sh_i18n_set_index( $id, ! empty( $in['index'] ) );
	$status = 'draft' === ( $in['status'] ?? 'publish' ) ? 'draft' : 'publish';
	if ( get_post_status( $id ) !== $status ) {
		wp_update_post( array( 'ID' => $id, 'post_status' => $status ) );
	}
	if ( function_exists( 'sh_search_index' ) ) {
		sh_search_index( $id );
	}
	sh_i18n_refresh_sitemaps();
	return array(
		'id'      => $id,
		'created' => $new,
		'status'  => get_post_status( $id ),
		'url'     => (string) get_permalink( $id ),
		'index'   => sh_i18n_indexable( $id ),
	);
}

/** English versions of Arabic categories: [{source, name, description}] (slug: Arabic slug + "-en"). */
function sh_i18n_import_terms( array $items ): array {
	$out = array();
	foreach ( $items as $it ) {
		$ar = get_term( (int) ( $it['source'] ?? 0 ), 'category' );
		if ( ! $ar || is_wp_error( $ar ) || 'ar' !== (string) pll_get_term_language( $ar->term_id ) ) {
			$out[] = array( 'source' => (int) ( $it['source'] ?? 0 ), 'error' => 'not an Arabic category' );
			continue;
		}
		$name = sanitize_text_field( (string) ( $it['name'] ?? '' ) );
		$desc = sanitize_textarea_field( (string) ( $it['description'] ?? '' ) );
		$en   = (int) pll_get_term( $ar->term_id, 'en' );
		if ( $en ) {
			wp_update_term( $en, 'category', array( 'name' => $name, 'description' => $desc ) );
		} else {
			$r = wp_insert_term( $name, 'category', array( 'slug' => $ar->slug . '-en', 'description' => $desc ) );
			if ( is_wp_error( $r ) ) {
				$out[] = array( 'source' => $ar->term_id, 'error' => $r->get_error_message() );
				continue;
			}
			$en = (int) $r['term_id'];
			pll_set_term_language( $en, 'en' );
		}
		pll_save_term_translations( array( 'ar' => $ar->term_id, 'en' => $en ) );
		$out[] = array( 'source' => $ar->term_id, 'id' => $en, 'url' => (string) get_term_link( $en, 'category' ) );
	}
	sh_i18n_refresh_sitemaps();
	return $out;
}

/**
 * Rank Math rebuilds its sitemaps: its cached files are deleted now. (Cache_Watcher::clear() only queues
 * the deletion for the end of a request in which the sitemap module runs, which a REST call is not.)
 */
function sh_i18n_refresh_sitemaps(): void {
	if ( class_exists( '\\RankMath\\Sitemap\\Cache' ) && method_exists( '\\RankMath\\Sitemap\\Cache', 'invalidate_storage' ) ) {
		\RankMath\Sitemap\Cache::invalidate_storage();
	}
}

/** noindex (Rank Math robots + Core flag) on or off for one page. */
function sh_i18n_set_index( int $id, bool $index ): void {
	if ( $index ) {
		update_post_meta( $id, 'rank_math_robots', array( 'index' ) );
		update_field( 'sh_seo_noindex', 0, $id );
	} else {
		update_post_meta( $id, 'rank_math_robots', array( 'noindex' ) );
		update_field( 'sh_seo_noindex', 1, $id );
	}
}

/**
 * English menu for a theme location, from a tree of items:
 * [{label, page (page ID, Arabic or English) | url, desc, icon, all_label, layout, children:[…]}]
 * Items whose page has no published English version are left out (reported in "skipped").
 */
function sh_i18n_import_menu( array $in ) {
	if ( ! sh_i18n_active() ) {
		return new WP_Error( 'sh_i18n', 'Polylang غير مفعّلة.', array( 'status' => 409 ) );
	}
	$loc  = sanitize_key( (string) ( $in['location'] ?? '' ) );
	$name = sanitize_text_field( (string) ( $in['name'] ?? '' ) );
	if ( ! $loc || ! $name || ! isset( get_registered_nav_menus()[ $loc ] ) && ! array_key_exists( $loc, (array) get_theme_support( 'menus' ) ) && ! in_array( $loc, array( 'primary', 'footer', 'legal' ), true ) ) {
		return new WP_Error( 'sh_menu', 'location/name غير صحيحين.', array( 'status' => 400 ) );
	}
	$menu = wp_get_nav_menu_object( $name );
	if ( $menu ) {
		foreach ( (array) wp_get_nav_menu_items( $menu->term_id, array( 'post_status' => 'any' ) ) as $it ) {
			wp_delete_post( $it->ID, true );
		}
		$menu_id = (int) $menu->term_id;
	} else {
		$menu_id = wp_create_nav_menu( $name );
		if ( is_wp_error( $menu_id ) ) {
			return $menu_id;
		}
	}
	$skipped = array();
	$count   = 0;
	$add     = static function ( array $items, int $parent ) use ( &$add, $menu_id, &$skipped, &$count ) {
		foreach ( $items as $i => $it ) {
			$label = sanitize_text_field( (string) ( $it['label'] ?? '' ) );
			$args  = array(
				'menu-item-title'       => $label,
				'menu-item-description' => sanitize_textarea_field( (string) ( $it['desc'] ?? '' ) ),
				'menu-item-parent-id'   => $parent,
				'menu-item-position'    => $i + 1,
				'menu-item-status'      => 'publish',
			);
			if ( ! empty( $it['page'] ) ) {
				$pid = (int) $it['page'];
				$en  = 'en' === sh_post_lang( $pid ) ? $pid : (int) pll_get_post( $pid, 'en' );
				if ( ! $en || 'publish' !== get_post_status( $en ) ) {
					$skipped[] = $label;
					continue;
				}
				$args += array( 'menu-item-type' => 'post_type', 'menu-item-object' => 'page', 'menu-item-object-id' => $en );
			} else {
				$args += array( 'menu-item-type' => 'custom', 'menu-item-url' => esc_url_raw( (string) ( $it['url'] ?? '#' ) ) );
			}
			$item_id = wp_update_nav_menu_item( $menu_id, 0, $args );
			if ( is_wp_error( $item_id ) ) {
				$skipped[] = $label;
				continue;
			}
			++$count;
			foreach ( array( 'icon' => 'sh_menu_icon', 'all_label' => 'sh_menu_all_label', 'layout' => 'sh_menu_layout' ) as $k => $field ) {
				if ( isset( $it[ $k ] ) && '' !== (string) $it[ $k ] && function_exists( 'update_field' ) ) {
					update_field( $field, (string) $it[ $k ], $item_id );
				}
			}
			if ( ! empty( $it['children'] ) ) {
				$add( (array) $it['children'], (int) $item_id );
			}
		}
	};
	$add( (array) ( $in['items'] ?? array() ), 0 );
	if ( function_exists( 'pll_set_term_language' ) ) {
		// menus are not a translated taxonomy; the location map below is what makes this the English menu
	}
	$model = PLL()->model;
	$theme = get_option( 'stylesheet' );
	$map   = (array) $model->options->get( 'nav_menus' );
	$map[ $theme ][ $loc ]['en'] = $menu_id;
	$model->options->set( 'nav_menus', $map );
	$model->options->save();
	return array( 'menu' => $menu_id, 'location' => $loc, 'items' => $count, 'skipped' => $skipped );
}

/** Arabic menus as trees (labels, page IDs, extra fields), to write their English versions. */
function sh_i18n_export_menus(): array {
	$theme = get_option( 'stylesheet' );
	$mods  = (array) get_option( 'theme_mods_' . $theme, array() );
	$out   = array();
	foreach ( (array) ( $mods['nav_menu_locations'] ?? array() ) as $loc => $menu_id ) {
		$items = $menu_id ? (array) wp_get_nav_menu_items( (int) $menu_id ) : array();
		$by    = array();
		foreach ( $items as $it ) {
			$by[ (int) $it->menu_item_parent ][] = $it;
		}
		$build = static function ( int $parent ) use ( &$build, $by ) {
			$o = array();
			foreach ( $by[ $parent ] ?? array() as $it ) {
				$row = array( 'label' => $it->title, 'desc' => trim( (string) $it->description ) );
				if ( 'post_type' === $it->type ) {
					$row['page'] = (int) $it->object_id;
				} else {
					$row['url'] = $it->url;
				}
				foreach ( array( 'icon' => 'sh_menu_icon', 'all_label' => 'sh_menu_all_label', 'layout' => 'sh_menu_layout' ) as $k => $field ) {
					$row[ $k ] = function_exists( 'get_field' ) ? (string) get_field( $field, $it->ID ) : '';
				}
				$row['children'] = $build( (int) $it->ID );
				$o[]             = $row;
			}
			return $o;
		};
		$out[ $loc ] = array( 'menu' => (int) $menu_id, 'items' => $build( 0 ) );
	}
	return $out;
}

/** Company fields of «إعدادات سيو هاوس» that can be set over REST (content/options). */
const SH_REMOTE_OPTION_FIELDS = array( 'sh_legal_name', 'sh_cr_number', 'sh_cr_office', 'sh_tax_number' );

/**
 * Exact text replacements inside one page's sections, or the fields of an article, case study or team
 * member (both languages), e.g. filling placeholders of a legal page. Only whole occurrences of each "find" string in text values change; nothing else is written.
 *
 * @return array|WP_Error
 */
function sh_i18n_replace_text( int $id, array $pairs, bool $dry ) {
	$p = get_post( $id );
	if ( ! $p || ! in_array( $p->post_type, array_merge( array( 'page' ), SH_I18N_ITEM_TYPES ), true ) || ! sh_core_acf_ready() ) {
		return new WP_Error( 'sh_page', 'id: رقم صفحة أو مقال أو دراسة حالة أو عضو فريق.', array( 'status' => 400 ) );
	}
	$pairs = array_values( array_filter( $pairs, static fn( $x ) => is_array( $x ) && '' !== (string) ( $x['find'] ?? '' ) ) );
	$found = array_fill( 0, count( $pairs ), 0 );
	$walk  = static function ( $v ) use ( &$walk, $pairs, &$found ) {
		if ( is_array( $v ) ) {
			foreach ( $v as $k => $x ) {
				$v[ $k ] = $walk( $x );
			}
			return $v;
		}
		if ( ! is_string( $v ) ) {
			return $v;
		}
		foreach ( $pairs as $i => $pr ) {
			$n = substr_count( $v, (string) $pr['find'] );
			if ( $n ) {
				$found[ $i ] += $n;
				$v            = str_replace( (string) $pr['find'], (string) ( $pr['replace'] ?? '' ), $v );
			}
		}
		return $v;
	};
	$changed = array();
	$defs = 'page' === $p->post_type ? sh_core_section_fields( $id ) : array_values( sh_i18n_type_fields( $p->post_type ) );
	foreach ( $defs as $f ) {
		$before = sh_transfer_value( $f, acf_get_value( $id, $f ) );
		$after  = $walk( $before );
		if ( $after !== $before ) {
			$changed[] = 'page' === $p->post_type ? substr( (string) $f['name'], 2 ) : (string) $f['name'];
			if ( ! $dry ) {
				update_field( $f['key'], $after, $id );
			}
		}
	}
	if ( ! $dry && $changed ) {
		sh_core_sections( $id, true );
		if ( function_exists( 'sh_search_index' ) ) {
			sh_search_index( $id );
		}
	}
	return array( 'id' => $id, 'dry_run' => $dry, 'sections' => $changed, 'found' => $found );
}

add_action(
	'rest_api_init',
	static function () {
		$admin = static fn() => current_user_can( 'manage_options' ) && current_user_can( 'edit_others_pages' );
		register_rest_route(
			'seohouse/v1',
			'/i18n/setup',
			array(
				'methods'             => 'POST',
				'callback'            => static function () {
					$r = sh_i18n_setup();
					return new WP_REST_Response( $r, $r['ok'] ? 200 : 409 );
				},
				'permission_callback' => $admin,
			)
		);
		register_rest_route(
			'seohouse/v1',
			'/i18n/menus',
			array(
				array(
					'methods'             => 'GET',
					'callback'            => static fn() => new WP_REST_Response( sh_i18n_export_menus() ),
					'permission_callback' => $admin,
				),
				array(
					'methods'             => 'POST',
					'callback'            => static fn( WP_REST_Request $r ) => rest_ensure_response( sh_i18n_import_menu( (array) $r->get_json_params() ) ),
					'permission_callback' => $admin,
				),
			)
		);
		register_rest_route(
			'seohouse/v1',
			'/i18n/options',
			array(
				array(
					'methods'             => 'GET',
					'callback'            => static fn() => new WP_REST_Response( (array) get_option( 'sh_options_en', array() ) ),
					'permission_callback' => $admin,
				),
				array(
					'methods'             => 'POST',
					'callback'            => static function ( WP_REST_Request $r ) {
						$v = (array) $r->get_json_params();
						array_walk_recursive( $v, static function ( &$x ) { $x = is_string( $x ) ? sanitize_textarea_field( $x ) : $x; } );
						update_option( 'sh_options_en', $v, true );
						return new WP_REST_Response( array( 'saved' => array_keys( $v ) ) );
					},
					'permission_callback' => $admin,
				),
			)
		);
		register_rest_route(
			'seohouse/v1',
			'/content',
			array(
				'methods'             => 'POST',
				'callback'            => static fn( WP_REST_Request $r ) => rest_ensure_response( sh_i18n_import_page( (array) $r->get_json_params() ) ),
				'permission_callback' => $admin,
			)
		);
		register_rest_route(
			'seohouse/v1',
			'/i18n/moved',
			array(
				'methods'             => 'POST',
				'callback'            => static function ( WP_REST_Request $r ) {
					$moved = (array) get_option( 'sh_i18n_moved', array() );
					foreach ( (array) $r->get_json_params() as $path => $id ) {
						$path = '/' . trim( sanitize_text_field( (string) $path ), '/' ) . '/';
						if ( str_starts_with( $path, '/en/' ) && 'en' === sh_post_lang( (int) $id ) ) {
							$moved[ $path ] = (int) $id;
						}
					}
					update_option( 'sh_i18n_moved', $moved, false );
					return new WP_REST_Response( $moved );
				},
				'permission_callback' => $admin,
			)
		);
		register_rest_route(
			'seohouse/v1',
			'/content/article',
			array(
				'methods'             => 'POST',
				'callback'            => static fn( WP_REST_Request $r ) => rest_ensure_response( sh_i18n_standalone_article( (array) $r->get_json_params() ) ),
				'permission_callback' => $admin,
			)
		);
		register_rest_route(
			'seohouse/v1',
			'/content/item',
			array(
				'methods'             => 'POST',
				'callback'            => static fn( WP_REST_Request $r ) => rest_ensure_response( sh_i18n_import_item( (array) $r->get_json_params() ) ),
				'permission_callback' => $admin,
			)
		);
		register_rest_route(
			'seohouse/v1',
			'/i18n/terms',
			array(
				'methods'             => 'POST',
				'callback'            => static fn( WP_REST_Request $r ) => new WP_REST_Response( sh_i18n_import_terms( (array) ( $r->get_json_params()['items'] ?? array() ) ) ),
				'permission_callback' => $admin,
			)
		);
		register_rest_route(
			'seohouse/v1',
			'/content/replace',
			array(
				'methods'             => 'POST',
				'callback'            => static function ( WP_REST_Request $r ) {
					$in = (array) $r->get_json_params();
					return rest_ensure_response( sh_i18n_replace_text( (int) ( $in['id'] ?? 0 ), (array) ( $in['pairs'] ?? array() ), ! isset( $in['dry_run'] ) || ! empty( $in['dry_run'] ) ) );
				},
				'permission_callback' => $admin,
			)
		);
		register_rest_route(
			'seohouse/v1',
			'/content/options',
			array(
				'methods'             => 'POST',
				'callback'            => static function ( WP_REST_Request $r ) {
					$saved = array();
					foreach ( (array) $r->get_json_params() as $k => $v ) {
						if ( in_array( $k, SH_REMOTE_OPTION_FIELDS, true ) && is_scalar( $v ) ) {
							update_field( $k, sanitize_text_field( (string) $v ), 'option' );
							$saved[ $k ] = (string) get_field( $k, 'option', false );
						}
					}
					return new WP_REST_Response( array( 'saved' => $saved ) );
				},
				'permission_callback' => $admin,
			)
		);
		register_rest_route(
			'seohouse/v1',
			'/content/robots',
			array(
				'methods'             => 'POST',
				'callback'            => static function ( WP_REST_Request $r ) {
					$index = rest_sanitize_boolean( $r['index'] );
					$out   = array();
					foreach ( array_filter( array_map( 'intval', (array) $r['ids'] ) ) as $id ) {
						if ( ! in_array( get_post_type( $id ), array_merge( array( 'page' ), SH_I18N_ITEM_TYPES ), true ) || 'en' !== sh_post_lang( $id ) ) {
							$out[ $id ] = 'skipped (not an English page or item)';
							continue;
						}
						sh_i18n_set_index( $id, $index );
						$out[ $id ] = $index ? 'index' : 'noindex';
					}
					sh_i18n_refresh_sitemaps();
					return new WP_REST_Response( $out );
				},
				'permission_callback' => $admin,
				'args'                => array(
					'ids'   => array( 'required' => true ),
					'index' => array( 'required' => true ),
				),
			)
		);
	}
);
