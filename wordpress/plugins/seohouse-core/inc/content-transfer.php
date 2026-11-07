<?php
/**
 * Page content as data, over REST (seohouse/v1, administrators only): the same shape as a content-pack
 * page (sections keyed by field name), read from what the site stores now.
 *
 *   GET content?ids=67,57     title, slug, parent, path, template, status, SEO fields and the sections
 *                             of each page. Images are attachment IDs, page links are page IDs (or the
 *                             URL typed in the field), list rows carry no record IDs, so the result can
 *                             be written back with update_field() to this page or to another page with
 *                             the same template.
 *   GET content/options       the texts of «إعدادات سيو هاوس» (identity, header, footer, company data,
 *                             booking, shared content). No tokens, tracking IDs or redirects.
 *
 * Read-only: nothing here writes.
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

/** Option fields that are never exported (credentials, tracking, redirects, colours). */
const SH_TRANSFER_SKIP_OPTIONS = array( 'sh_booking_token', 'sh_gtm_id', 'sh_ga4_id', 'sh_gsc_verification', 'sh_redirects', 'sh_lead_to', 'sh_booking_url' );

/**
 * A stored (unformatted) ACF value → the content-pack shape: groups and list rows keyed by sub-field
 * name, list record IDs dropped.
 *
 * @param array $field ACF field.
 * @param mixed $raw   Value as acf_get_value() returns it.
 * @return mixed
 */
function sh_transfer_value( array $field, $raw ) {
	$type = (string) ( $field['type'] ?? '' );
	if ( 'group' === $type ) {
		$out = array();
		foreach ( (array) ( $field['sub_fields'] ?? array() ) as $s ) {
			if ( in_array( $s['type'] ?? '', array( 'tab', 'accordion', 'message' ), true ) || '' === (string) ( $s['name'] ?? '' ) ) {
				continue;
			}
			$out[ $s['name'] ] = sh_transfer_value( $s, is_array( $raw ) ? ( $raw[ $s['key'] ] ?? $raw[ $s['name'] ] ?? null ) : null );
		}
		return $out;
	}
	if ( 'sh_rows' === $type || 'repeater' === $type ) {
		$rows = array();
		foreach ( (array) $raw as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$r = array();
			foreach ( (array) ( $field['sub_fields'] ?? array() ) as $s ) {
				if ( '' === (string) ( $s['name'] ?? '' ) ) {
					continue;
				}
				$r[ $s['name'] ] = sh_transfer_value( $s, $row[ $s['key'] ] ?? $row[ $s['name'] ] ?? null );
			}
			$rows[] = $r;
		}
		return $rows;
	}
	if ( in_array( $type, array( 'image', 'post_object' ), true ) ) {
		return is_numeric( $raw ) ? (int) $raw : $raw;
	}
	if ( 'page_link' === $type ) {
		return is_numeric( $raw ) ? (int) $raw : ( is_string( $raw ) ? $raw : null );
	}
	if ( 'true_false' === $type ) {
		return (int) (bool) $raw;
	}
	return $raw;
}

/** One page as content-pack data. */
function sh_transfer_page( WP_Post $p ): array {
	$sections = array();
	if ( sh_core_acf_ready() ) {
		foreach ( sh_core_section_fields( $p->ID ) as $f ) {
			$sections[] = array( 'acf_fc_layout' => substr( (string) $f['name'], 2 ) ) + (array) sh_transfer_value( $f, acf_get_value( $p->ID, $f ) );
		}
	}
	$seo = array();
	foreach ( array( 'sh_seo_title', 'sh_seo_description', 'sh_seo_noindex', 'sh_schema_type', 'sh_schema_service', 'sh_crumb' ) as $name ) {
		$seo[ $name ] = function_exists( 'get_field' ) ? get_field( $name, $p->ID, false ) : null;
	}
	foreach ( array( 'rank_math_title', 'rank_math_description', 'rank_math_robots', 'rank_math_focus_keyword' ) as $meta ) {
		$seo[ $meta ] = get_post_meta( $p->ID, $meta, true );
	}
	$tpl = (string) get_page_template_slug( $p );
	return array(
		'id'       => (int) $p->ID,
		'key'      => sh_core_page_key( $p ),
		'title'    => $p->post_title,
		'slug'     => rawurldecode( $p->post_name ),
		'parent'   => (int) $p->post_parent,
		'path'     => rawurldecode( wp_make_link_relative( (string) get_permalink( $p ) ) ),
		'template' => $tpl,
		'status'   => $p->post_status,
		'lang'     => function_exists( 'pll_get_post_language' ) ? (string) pll_get_post_language( $p->ID ) : '',
		'seo'      => $seo,
		'sections' => $sections,
	);
}

function sh_transfer_rest_export( WP_REST_Request $r ) {
	$ids = array_filter( array_map( 'intval', explode( ',', (string) $r['ids'] ) ) );
	if ( ! $ids || count( $ids ) > 50 ) {
		return new WP_Error( 'sh_ids', 'ids: من 1 إلى 50 رقم صفحة مفصولة بفواصل.', array( 'status' => 400 ) );
	}
	$out = array();
	foreach ( $ids as $id ) {
		$p = get_post( $id );
		if ( ! $p || 'page' !== $p->post_type ) {
			$out[] = array( 'id' => $id, 'error' => 'not a page' );
			continue;
		}
		if ( ! current_user_can( 'edit_post', $id ) ) {
			$out[] = array( 'id' => $id, 'error' => 'forbidden' );
			continue;
		}
		$out[] = sh_transfer_page( $p );
	}
	return new WP_REST_Response( array( 'pages' => $out ) );
}

function sh_transfer_rest_options(): WP_REST_Response {
	$out = array();
	if ( sh_core_acf_ready() ) {
		foreach ( acf_get_fields( 'group_sh_options' ) ?: array() as $f ) {
			if ( in_array( $f['type'], array( 'tab', 'accordion', 'message', 'color_picker', 'password' ), true ) || in_array( $f['name'], SH_TRANSFER_SKIP_OPTIONS, true ) || '' === (string) $f['name'] ) {
				continue;
			}
			$out[ $f['name'] ] = sh_transfer_value( $f, acf_get_value( 'options', $f ) );
		}
	}
	return new WP_REST_Response( array( 'options' => $out ) );
}

add_action(
	'rest_api_init',
	static function () {
		register_rest_route(
			'seohouse/v1',
			'/content',
			array(
				'methods'             => 'GET',
				'callback'            => 'sh_transfer_rest_export',
				'permission_callback' => static fn() => current_user_can( 'edit_pages' ) && current_user_can( 'edit_others_pages' ),
				'args'                => array( 'ids' => array( 'required' => true, 'type' => 'string' ) ),
			)
		);
		register_rest_route(
			'seohouse/v1',
			'/content/options',
			array(
				'methods'             => 'GET',
				'callback'            => 'sh_transfer_rest_options',
				'permission_callback' => static fn() => current_user_can( 'manage_options' ),
			)
		);
	}
);
