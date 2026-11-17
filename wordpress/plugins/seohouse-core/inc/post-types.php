<?php
/**
 * Post types. Each URL has one owner:
 *   /results/          → Page "نتائج الأعمال" (case_study has no archive)
 *   /results/{slug}/   → case_study
 *   /team/             → Page "فريق العمل" (team_member has no archive)
 *   /team/{slug}/      → team_member
 *   sh_lead            → private consultation requests (never public, not in REST)
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

function sh_core_register_post_types(): void {
	register_post_type(
		'case_study',
		array(
			'labels'              => array(
				'name'          => __( 'دراسات الحالة', 'seohouse-core' ),
				'singular_name' => __( 'دراسة حالة', 'seohouse-core' ),
				'add_new_item'  => __( 'إضافة دراسة حالة', 'seohouse-core' ),
				'edit_item'     => __( 'تعديل دراسة الحالة', 'seohouse-core' ),
				'all_items'     => __( 'كل الحالات', 'seohouse-core' ),
				'menu_name'     => __( 'نتائج الأعمال', 'seohouse-core' ),
			),
			'public'              => true,
			'has_archive'         => false,
			'rewrite'             => array( 'slug' => 'results', 'with_front' => false ),
			'menu_icon'           => 'dashicons-chart-line',
			'menu_position'       => 21,
			'supports'            => array( 'title', 'thumbnail', 'page-attributes', 'revisions', 'custom-fields' ),
			'show_in_rest'        => true,
			'exclude_from_search' => false,
		)
	);

	register_post_type(
		'team_member',
		array(
			'labels'        => array(
				'name'          => __( 'فريق العمل', 'seohouse-core' ),
				'singular_name' => __( 'عضو الفريق', 'seohouse-core' ),
				'add_new_item'  => __( 'إضافة عضو', 'seohouse-core' ),
				'edit_item'     => __( 'تعديل بيانات العضو', 'seohouse-core' ),
				'all_items'     => __( 'كل الأعضاء', 'seohouse-core' ),
			),
			'public'        => true,
			'has_archive'   => false,
			'rewrite'       => array( 'slug' => 'team', 'with_front' => false ),
			'menu_icon'     => 'dashicons-groups',
			'menu_position' => 22,
			'supports'      => array( 'title', 'thumbnail', 'page-attributes', 'revisions', 'custom-fields' ),
			'show_in_rest'  => true,
		)
	);

	register_post_type(
		'sh_lead',
		array(
			'labels'              => array(
				'name'          => __( 'طلبات الاستشارة', 'seohouse-core' ),
				'singular_name' => __( 'طلب استشارة', 'seohouse-core' ),
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => false,
			'menu_icon'           => 'dashicons-email-alt',
			'menu_position'       => 23,
			'supports'            => array( 'title' ),
			'capability_type'     => 'post',
			'capabilities'        => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'        => true,
			'rewrite'             => false,
			'query_var'           => false,
		)
	);

	// Items of "قائمة عناصر" fields (ACF free has no Repeater): one record per item, edited inline
	// on its owner's screen, never on its own.
	register_post_type(
		'sh_row',
		array(
			'labels'              => array(
				'name'          => __( 'عناصر القوائم', 'seohouse-core' ),
				'singular_name' => __( 'عنصر قائمة', 'seohouse-core' ),
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => false,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'show_in_rest'        => false,
			'hierarchical'        => false,
			'supports'            => array( 'title', 'custom-fields' ),
			'rewrite'             => false,
			'query_var'           => false,
			'can_export'          => true,
			'delete_with_user'    => false,
		)
	);
}
add_action( 'init', 'sh_core_register_post_types', 5 );

/**
 * /results/ is a page (design template), so the case study archive is off; the published
 * feed of case studies keeps its address /results/feed/.
 */
add_action(
	'init',
	static function () {
		add_rewrite_rule( '^results/feed/(feed|rdf|rss|rss2|atom)/?$', 'index.php?post_type=case_study&feed=$matches[1]', 'top' );
		add_rewrite_rule( '^results/feed/?$', 'index.php?post_type=case_study&feed=rss2', 'top' );
	},
	6
);

/** Deleting a page/post (or a revision/autosave) also deletes the list items it owns. */
add_action(
	'before_delete_post',
	static function ( $post_id ) {
		if ( ! class_exists( 'SH_Field_Rows' ) ) {
			return;
		}
		$owned = get_posts( array( 'post_type' => 'sh_row', 'post_status' => 'any', 'posts_per_page' => -1, 'fields' => 'ids', 'meta_key' => '_sh_owner', 'meta_value' => (string) $post_id ) ); // phpcs:ignore WordPress.DB.SlowDBQuery
		foreach ( $owned as $row ) {
			SH_Field_Rows::delete_record( (int) $row );
		}
	}
);

/**
 * Permalink structure and blog/category bases the project map requires.
 * Applied once by the setup tool (never on every request).
 */
function sh_core_apply_permalinks(): void {
	global $wp_rewrite;
	$wp_rewrite->set_permalink_structure( '/blog/%postname%/' );
	update_option( 'category_base', 'blog/category' );
	update_option( 'tag_base', 'blog/tag' );
	$wp_rewrite->set_category_base( 'blog/category' );
	$wp_rewrite->set_tag_base( 'blog/tag' );
	// re-register so taxonomy and CPT permastructs follow the new bases within this same request
	create_initial_taxonomies();
	sh_core_register_post_types();
	flush_rewrite_rules( false );
}

/** CPT singles must not inherit the /blog/ prefix of posts. (with_front=false above.) */

/** Sitemap: public content only; leads never; noindex pages removed. */
add_filter(
	'wp_sitemaps_post_types',
	static function ( $types ) {
		unset( $types['sh_lead'], $types['attachment'] );
		return $types;
	}
);
add_filter( 'wp_sitemaps_add_provider', static fn( $provider, $name ) => 'users' === $name ? false : $provider, 10, 2 );
add_filter( 'wp_sitemaps_taxonomies', static fn( $t ) => array_intersect_key( $t, array( 'category' => 1 ) ) );
add_filter(
	'wp_sitemaps_posts_query_args',
	static function ( $args ) {
		$args['meta_query'] = array(
			'relation' => 'OR',
			array( 'key' => 'sh_seo_noindex', 'compare' => 'NOT EXISTS' ),
			array( 'key' => 'sh_seo_noindex', 'value' => '1', 'compare' => '!=' ),
		);
		return $args;
	}
);
