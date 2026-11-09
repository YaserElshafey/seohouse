<?php
/**
 * Case study drafts and page sections through REST (2.9.15). Admin only (manage_options +
 * edit_others_pages).
 *
 * POST seohouse/v1/content/case: creates or updates an Arabic case study.
 *   Body: id (to update one created here: never a published one), title, slug, status (draft|publish,
 *   default draft), menu_order, fields {ACF field name: value; lists as arrays of rows, images as
 *   attachment IDs}, seo {rank_math_title, rank_math_description, rank_math_focus_keyword}, retire
 *   (IDs of published cases to retire once this one is approved: marked only, nothing changes now).
 *   Unknown field names are reported, not stored.
 *
 * POST seohouse/v1/content/section: values of one section of a page («s_<section>»), merged into what
 *   is stored. Body: id, section (e.g. "rankings"), values {sub field: value}, dry_run (default true).
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

function sh_core_case_save( array $in ) {
	if ( ! sh_core_acf_ready() ) {
		return new WP_Error( 'sh_acf', 'ACF مطلوبة.', array( 'status' => 409 ) );
	}
	$id = (int) ( $in['id'] ?? 0 );
	// a published case is changed only when asked explicitly (allow_published: true), e.g. to correct a field
	if ( $id && ( 'case_study' !== get_post_type( $id ) || ( 'publish' === get_post_status( $id ) && empty( $in['allow_published'] ) ) ) ) {
		return new WP_Error( 'sh_id', 'id: دراسة حالة غير منشورة أُنشئت من هذا المسار (أو allow_published).', array( 'status' => 400 ) );
	}
	$title = trim( wp_strip_all_tags( (string) ( $in['title'] ?? '' ) ) );
	$slug  = sanitize_title( (string) ( $in['slug'] ?? '' ) );
	if ( '' === $title || '' === $slug ) {
		return new WP_Error( 'sh_args', 'title وslug مطلوبان.', array( 'status' => 400 ) );
	}
	$args = array(
		'post_type'   => 'case_study',
		'post_title'  => $title,
		'post_name'   => $slug,
		'menu_order'  => (int) ( $in['menu_order'] ?? 0 ),
		'post_status' => 'publish' === ( $in['status'] ?? 'draft' ) ? 'publish' : 'draft',
	);
	$want = $args['post_status'];
	$new  = ! $id;
	if ( $new ) {
		$args['post_status'] = 'draft'; // fields first; published (if asked) at the end
		$id                  = wp_insert_post( wp_slash( $args ), true );
		if ( is_wp_error( $id ) ) {
			return $id;
		}
		if ( function_exists( 'pll_set_post_language' ) ) {
			pll_set_post_language( $id, 'ar' );
		}
	} else {
		unset( $args['post_status'] );
		$args['ID'] = $id;
		$r          = wp_update_post( wp_slash( $args ), true );
		if ( is_wp_error( $r ) ) {
			return $r;
		}
	}
	$known   = function_exists( 'sh_i18n_type_fields' ) ? sh_i18n_type_fields( 'case_study' ) : array();
	$unknown = array();
	foreach ( (array) ( $in['fields'] ?? array() ) as $name => $value ) {
		if ( ! isset( $known[ $name ] ) ) {
			$unknown[] = $name;
			continue;
		}
		update_field( $known[ $name ]['key'], $value, $id );
	}
	$seo = (array) ( $in['seo'] ?? array() );
	foreach ( array( 'rank_math_title', 'rank_math_description', 'rank_math_focus_keyword' ) as $m ) {
		if ( array_key_exists( $m, $seo ) ) {
			$v = trim( wp_strip_all_tags( (string) $seo[ $m ] ) );
			if ( '' === $v ) {
				delete_post_meta( $id, $m );
			} else {
				update_post_meta( $id, $m, $v );
			}
		}
	}
	// published cases this one replaces (or that leave with it): marked, kept as they are until approval;
	// the results preview (?sh_preview=1) already leaves them out
	foreach ( array_map( 'intval', (array) ( $in['retire'] ?? array() ) ) as $old ) {
		if ( $old && $old !== $id && 'case_study' === get_post_type( $old ) ) {
			update_post_meta( $old, '_sh_retire_on_approval', $id );
		}
	}
	if ( get_post_status( $id ) !== $want ) {
		wp_update_post( array( 'ID' => $id, 'post_status' => $want ) );
	}
	if ( function_exists( 'sh_search_index' ) ) {
		sh_search_index( $id );
	}
	return array(
		'id'      => $id,
		'created' => $new,
		'status'  => get_post_status( $id ),
		'url'     => (string) get_permalink( $id ),
		'preview' => 'publish' === get_post_status( $id ) ? '' : (string) get_preview_post_link( $id ),
		'unknown' => $unknown,
	);
}

function sh_core_section_save( array $in ) {
	$id  = (int) ( $in['id'] ?? 0 );
	$sec = sanitize_key( (string) ( $in['section'] ?? '' ) );
	if ( ! $id || 'page' !== get_post_type( $id ) || '' === $sec || ! sh_core_acf_ready() ) {
		return new WP_Error( 'sh_args', 'id (صفحة) وsection مطلوبان.', array( 'status' => 400 ) );
	}
	$field = null;
	foreach ( sh_core_section_fields( $id ) as $f ) {
		if ( 's_' . $sec === $f['name'] ) {
			$field = $f;
		}
	}
	if ( ! $field ) {
		return new WP_Error( 'sh_section', 'لا يوجد هذا القسم في قالب الصفحة.', array( 'status' => 400 ) );
	}
	$subs    = array_column( (array) ( $field['sub_fields'] ?? array() ), 'name' );
	$values  = (array) ( $in['values'] ?? array() );
	$unknown = array_values( array_diff( array_keys( $values ), $subs ) );
	if ( $unknown ) {
		return new WP_Error( 'sh_fields', 'حقول غير موجودة في القسم: ' . implode( '، ', $unknown ), array( 'status' => 400 ) );
	}
	// stored values in the same shape the import writes (names; lists as rows; images as IDs)
	$current = get_field( $field['key'], $id );
	$current = is_array( $current ) ? $current : array();
	$named   = array();
	foreach ( (array) $field['sub_fields'] as $sf ) {
		if ( array_key_exists( $sf['name'], $values ) ) {
			$named[ $sf['name'] ] = $values[ $sf['name'] ];
		} elseif ( array_key_exists( $sf['name'], $current ) ) {
			$named[ $sf['name'] ] = $current[ $sf['name'] ];
		}
	}
	$dry = ! isset( $in['dry_run'] ) || ! empty( $in['dry_run'] );
	if ( ! $dry ) {
		update_field( $field['key'], $named, $id );
		sh_core_sections( $id, true );
	}
	return array(
		'id'      => $id,
		'section' => $sec,
		'dry_run' => $dry,
		'fields'  => array_keys( $values ),
	);
}

/**
 * Approval of the new case studies (2.9.17): published cases to take down become drafts, their old
 * addresses move permanently (301, Rank Math «Redirections» when the module runs, otherwise Core's own
 * redirect for addresses that answer 404), and the new presentation is switched on for visitors.
 * Body: retire [IDs], redirects [{from: path, to: URL}], v2 (bool).
 */
function sh_core_cases_approve( array $in ) {
	$out = array( 'retired' => array(), 'redirects' => array(), 'v2' => null );
	foreach ( array_map( 'intval', (array) ( $in['retire'] ?? array() ) ) as $old ) {
		if ( 'case_study' === get_post_type( $old ) && 'publish' === get_post_status( $old ) ) {
			wp_update_post( array( 'ID' => $old, 'post_status' => 'draft' ) );
			delete_post_meta( $old, '_sh_retire_on_approval' );
			$out['retired'][] = $old;
		}
	}
	$own = (array) get_option( 'sh_case_redirects', array() );
	foreach ( (array) ( $in['redirects'] ?? array() ) as $r ) {
		$from = '/' . trim( rawurldecode( (string) wp_parse_url( (string) ( $r['from'] ?? '' ), PHP_URL_PATH ) ), '/' ) . '/';
		$to   = esc_url_raw( (string) ( $r['to'] ?? '' ) );
		if ( '//' === $from || '' === $to ) {
			continue;
		}
		$id = 0;
		if ( class_exists( '\RankMath\Helper' ) && \RankMath\Helper::is_module_active( 'redirections' ) && class_exists( '\RankMath\Redirections\Redirection' ) ) {
			$id = (int) \RankMath\Redirections\Redirection::from(
				array(
					'url_to'      => $to,
					'header_code' => 301,
					'status'      => 'active',
					'sources'     => array( array( 'pattern' => trim( $from, '/' ), 'comparison' => 'exact' ) ),
				)
			)->save();
		}
		$own[ $from ] = $to; // also kept here: answers when the address would be a 404 and no other rule did
		$out['redirects'][] = array( 'from' => $from, 'to' => $to, 'rank_math_id' => $id );
	}
	update_option( 'sh_case_redirects', $own, false );
	if ( isset( $in['v2'] ) ) {
		update_option( 'sh_cases_v2', empty( $in['v2'] ) ? 0 : 1 );
		$out['v2'] = (bool) get_option( 'sh_cases_v2' );
	}
	return $out;
}

add_action(
	'template_redirect',
	static function () {
		if ( ! is_404() ) {
			return;
		}
		$map  = (array) get_option( 'sh_case_redirects', array() );
		$path = '/' . trim( rawurldecode( (string) wp_parse_url( (string) ( $_SERVER['REQUEST_URI'] ?? '' ), PHP_URL_PATH ) ), '/' ) . '/'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		if ( isset( $map[ $path ] ) ) {
			wp_safe_redirect( $map[ $path ], 301, 'SEO House Core' );
			exit;
		}
	},
	5
);

add_action(
	'rest_api_init',
	static function () {
		$admin = static fn() => current_user_can( 'manage_options' ) && current_user_can( 'edit_others_pages' );
		register_rest_route(
			'seohouse/v1',
			'/cases/approve',
			array(
				'methods'             => 'POST',
				'callback'            => static fn( WP_REST_Request $r ) => rest_ensure_response( sh_core_cases_approve( (array) $r->get_json_params() ) ),
				'permission_callback' => $admin,
			)
		);
		register_rest_route(
			'seohouse/v1',
			'/content/case',
			array(
				'methods'             => 'POST',
				'callback'            => static fn( WP_REST_Request $r ) => rest_ensure_response( sh_core_case_save( (array) $r->get_json_params() ) ),
				'permission_callback' => $admin,
			)
		);
		register_rest_route(
			'seohouse/v1',
			'/content/section',
			array(
				'methods'             => 'POST',
				'callback'            => static fn( WP_REST_Request $r ) => rest_ensure_response( sh_core_section_save( (array) $r->get_json_params() ) ),
				'permission_callback' => $admin,
			)
		);
	}
);
