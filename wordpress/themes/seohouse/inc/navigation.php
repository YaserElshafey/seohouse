<?php
/**
 * Menu data for the header, mega menu, mobile drawer and footer.
 * Menus are managed in Appearance → Menus. Extra per-item fields (icon, "view all" label,
 * panel layout) come from SEO House Core (ACF on menu items); the item description is the
 * WordPress menu item description.
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

/** Icon paths used in the services mega menu (from the design). */
function sh_nav_icons(): array {
	return array(
		'search' => array( 'label' => 'بحث', 'd' => 'M10 3a7 7 0 1 0 0 14 7 7 0 0 0 0-14M15.5 15.5 21 21' ),
		'window' => array( 'label' => 'موقع', 'd' => 'M3 5h18v14H3zM3 9.5h18M6.5 7h.01' ),
		'bag'    => array( 'label' => 'متجر', 'd' => 'M4 8h16l-1.2 11H5.2zM9 8V5.5h6V8' ),
		'upload' => array( 'label' => 'رفع', 'd' => 'M12 16V6m-3.5 3.5L12 6l3.5 3.5M5 18.5h14' ),
	);
}

function sh_current_path(): string {
	$path = wp_parse_url( home_url( add_query_arg( array() ) ), PHP_URL_PATH );
	if ( isset( $_SERVER['REQUEST_URI'] ) ) {
		$path = wp_parse_url( esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH );
	}
	$path = rawurldecode( (string) $path );
	$home = rawurldecode( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ) );
	if ( $home && '/' !== $home && str_starts_with( $path, $home ) ) {
		$path = '/' . ltrim( substr( $path, strlen( $home ) ), '/' );
	}
	return trailingslashit( $path );
}

function sh_url_path( string $url ): string {
	$p    = rawurldecode( (string) wp_parse_url( $url, PHP_URL_PATH ) );
	$home = rawurldecode( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ) );
	if ( $home && '/' !== $home && str_starts_with( $p, $home ) ) {
		$p = '/' . ltrim( substr( $p, strlen( $home ) ), '/' );
	}
	return trailingslashit( $p ? $p : '/' );
}

function sh_path_active( string $url ): bool {
	$p   = sh_url_path( $url );
	$cur = sh_current_path();
	return '/' === $p ? '/' === $cur : str_starts_with( $cur, $p );
}

/**
 * Menu tree for a location.
 *
 * @return array<int,array>|null null when the location has no menu assigned.
 */
function sh_menu_tree( string $location ): ?array {
	$locations = get_nav_menu_locations();
	if ( empty( $locations[ $location ] ) ) {
		return null;
	}
	$items = wp_get_nav_menu_items( $locations[ $location ] );
	if ( ! $items ) {
		return array();
	}
	_wp_menu_item_classes_by_context( $items );
	$by_parent = array();
	foreach ( $items as $it ) {
		// links to unpublished content (e.g. legal pages awaiting approved text) are not shown
		if ( 'post_type' === $it->type && 'publish' !== get_post_status( (int) $it->object_id ) ) {
			continue;
		}
		$by_parent[ (int) $it->menu_item_parent ][] = $it;
	}
	$build = static function ( $parent ) use ( &$build, $by_parent ) {
		$out = array();
		foreach ( $by_parent[ $parent ] ?? array() as $it ) {
			$meta  = function_exists( 'get_field' ) ? (array) get_fields( $it->ID ) : array();
			$out[] = array(
				'id'        => (int) $it->ID,
				'label'     => $it->title,
				'url'       => $it->url,
				'desc'      => trim( (string) $it->description ),
				'icon'      => (string) ( $meta['sh_menu_icon'] ?? '' ),
				'all_label' => (string) ( $meta['sh_menu_all_label'] ?? '' ),
				'layout'    => (string) ( $meta['sh_menu_layout'] ?? '' ),
				'children'  => $build( (int) $it->ID ),
			);
		}
		return $out;
	};
	return $build( 0 );
}

/** Primary navigation with active flags. */
function sh_nav_primary(): array {
	$tree = sh_menu_tree( 'primary' );
	if ( null === $tree ) {
		$tree = sh_nav_default();
	}
	foreach ( $tree as &$n ) {
		$n['active'] = sh_path_active( $n['url'] );
		foreach ( $n['children'] as $c ) {
			if ( sh_path_active( $c['url'] ) ) {
				$n['active'] = true;
			}
		}
		if ( '/' !== sh_url_path( $n['url'] ) || ! $n['children'] ) {
			// keep
		}
		$n['key'] = sanitize_title( $n['label'] ) ? 'm' . $n['id'] : 'm' . md5( $n['label'] );
	}
	return $tree;
}

/** Structure used only when no menu is assigned to "primary" (fresh install before content setup). */
function sh_nav_default(): array {
	$u = static fn( $p ) => home_url( $p );
	$i = 0;
	$mk = static function ( $label, $path, $children = array(), $extra = array() ) use ( $u, &$i ) {
		return array_merge( array( 'id' => ++$i, 'label' => $label, 'url' => $u( $path ), 'desc' => '', 'icon' => '', 'all_label' => '', 'layout' => '', 'children' => $children ), $extra );
	};
	return array(
		$mk( 'الرئيسية', '/' ),
		$mk( 'الخدمات', '/services/', array(), array( 'all_label' => 'جميع الخدمات' ) ),
		$mk( 'نتائج الأعمال', '/results/' ),
		$mk( 'عن الشركة', '/about/' ),
		$mk( 'فريق العمل', '/team/' ),
		$mk( 'القطاعات', '/sectors/' ),
		$mk( 'المدونة', '/blog/' ),
		$mk( 'الأسعار', '/pricing/' ),
		$mk( 'اتصل بنا', '/contact/' ),
	);
}

/** Footer columns: top-level items are column titles. */
function sh_nav_footer(): array {
	$tree = sh_menu_tree( 'footer' );
	return $tree ? $tree : array();
}

function sh_nav_legal(): array {
	$tree = sh_menu_tree( 'legal' );
	return $tree ? $tree : array();
}

/** Header CTA: points to the booking section when the page has one, otherwise to the contact page. */
function sh_header_cta(): array {
	$label  = (string) sh_option( 'sh_cta_label', 'احجز استشارة مجانية' );
	$target = (string) sh_option( 'sh_cta_fallback', '' );
	$url    = sh_page_has_booking() ? '#booking' : ( $target ? sh_link( $target ) : home_url( '/contact/' ) );
	return array( 'label' => $label, 'url' => $url );
}
