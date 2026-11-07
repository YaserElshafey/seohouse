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
	'ar' => array( 'locale' => 'ar', 'name' => 'العربية', 'rtl' => true, 'term_group' => 0, 'flag' => 'arab' ),
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

// one hreflang set, printed by the theme from sh_i18n_alternates() (Polylang's would include noindex drafts)
add_filter( 'pll_rel_hreflang_attributes', '__return_empty_array', 99 );

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

/* ------------------------------------------------------------------ REST: setup */

/** Polylang options for this site: language in the URL directory, Arabic without prefix, no browser redirects. */
function sh_i18n_setup(): array {
	if ( ! sh_i18n_active() ) {
		return array( 'ok' => false, 'message' => 'Polylang غير مفعّلة.' );
	}
	$model = PLL()->model;
	$done  = array();

	// 1. Arabic menus keep their places: copy the theme's current locations into Polylang's Arabic slots
	//    before any language exists (Polylang reads menu locations per language on the front end).
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

	// 4. Everything that has no language yet is Arabic.
	$model->set_language_in_mass();
	$model->options->save();
	flush_rewrite_rules( false );

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
			'/content/robots',
			array(
				'methods'             => 'POST',
				'callback'            => static function ( WP_REST_Request $r ) {
					$index = rest_sanitize_boolean( $r['index'] );
					$out   = array();
					foreach ( array_filter( array_map( 'intval', (array) $r['ids'] ) ) as $id ) {
						if ( 'page' !== get_post_type( $id ) || 'en' !== sh_post_lang( $id ) ) {
							$out[ $id ] = 'skipped (not an English page)';
							continue;
						}
						sh_i18n_set_index( $id, $index );
						$out[ $id ] = $index ? 'index' : 'noindex';
					}
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
