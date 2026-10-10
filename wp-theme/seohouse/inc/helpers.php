<?php
defined( 'ABSPATH' ) || exit;

/**
 * Safe ACF field getter with fallback.
 * Works even when ACF is not installed.
 */
function sh_field( string $key, $post_id = null, $default = '' ) {
    if ( function_exists( 'get_field' ) ) {
        $value = get_field( $key, $post_id );
        return ( $value !== null && $value !== false && $value !== '' ) ? $value : $default;
    }
    return $default;
}

/**
 * Get a theme option field (ACF options page).
 */
function sh_option( string $key, $default = '' ) {
    return sh_field( $key, 'option', $default );
}

/**
 * Get permalink for a page by path, with fallback.
 */
function sh_page_url( string $path, string $fallback = '' ): string {
    $page = get_page_by_path( $path );
    if ( $page ) {
        return get_permalink( $page->ID );
    }
    return $fallback ?: home_url( '/' . trim( $path, '/' ) . '/' );
}

/**
 * Get permalink for a published page by path.
 * Unlike sh_page_url(), this returns '' (not a fallback URL) when the page
 * cannot be confirmed as existing and published. Use this wherever a "not
 * found" state should suppress a link rather than produce a potential 404.
 */
function sh_safe_url( string $path ): string {
    $page = get_page_by_path( $path );
    if ( $page && 'publish' === get_post_status( $page->ID ) ) {
        return (string) get_permalink( $page->ID );
    }
    return '';
}

/**
 * Get the permalink for a published country SEO page by its saved `seo_market`
 * ACF value (e.g. 'egypt', 'saudi_arabia', 'uae').
 *
 * Primary lookup: ACF meta query — slug-independent.
 * Backward compat: if no ACF value is saved yet, falls back to a slug path
 * only when the page at that path actually exists and is published.
 *
 * Returns empty string when no qualifying page is found so callers can
 * handle the missing-page case without outputting a broken URL.
 */
function sh_market_permalink( string $market_key, string $slug_path = '' ): string {
    // Primary: find a published page whose seo_market meta matches
    $pages = get_posts( [
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'meta_query'     => [ [ 'key' => 'seo_market', 'value' => $market_key ] ],
    ] );
    if ( ! empty( $pages ) ) {
        return (string) get_permalink( $pages[0] );
    }

    // Backward compat: check the legacy slug path only if the page exists
    if ( $slug_path ) {
        $page = get_page_by_path( $slug_path );
        if ( $page && 'publish' === get_post_status( $page->ID ) ) {
            return (string) get_permalink( $page->ID );
        }
    }

    return '';
}

/**
 * Get the permalink for a published service child page by its stable `service_card_key`
 * ACF value (e.g. 'salla', 'wordpress').
 *
 * Returns an array:
 *   'url'    => string  — permalink, or '' when unavailable
 *   'status' => 'ok' | 'missing' | 'duplicate'
 *
 * Primary: ACF meta query (slug-independent).
 * Backward compat: falls back to a verified slug path when no key is saved yet.
 */
function sh_service_permalink( string $service_key, string $slug_path = '' ): array {
    $pages = get_posts( [
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'posts_per_page' => 2,
        'fields'         => 'ids',
        'meta_query'     => [ [ 'key' => 'service_card_key', 'value' => $service_key ] ],
    ] );

    if ( count( $pages ) > 1 ) {
        return [ 'url' => '', 'status' => 'duplicate' ];
    }
    if ( count( $pages ) === 1 ) {
        return [ 'url' => (string) get_permalink( $pages[0] ), 'status' => 'ok' ];
    }

    // Backward compat: verify the legacy slug path exists and is published
    if ( $slug_path ) {
        $page = get_page_by_path( $slug_path );
        if ( $page && 'publish' === get_post_status( $page->ID ) ) {
            return [ 'url' => (string) get_permalink( $page->ID ), 'status' => 'ok' ];
        }
    }

    return [ 'url' => '', 'status' => 'missing' ];
}

/**
 * Render an SVG icon safely.
 */
function sh_svg( string $svg ): string {
    return wp_kses( $svg, [
        'svg'      => [ 'viewBox' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'width' => true, 'height' => true, 'class' => true, 'xmlns' => true ],
        'path'     => [ 'd' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true ],
        'circle'   => [ 'cx' => true, 'cy' => true, 'r' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true ],
        'rect'     => [ 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true, 'fill' => true, 'stroke' => true ],
        'line'     => [ 'x1' => true, 'y1' => true, 'x2' => true, 'y2' => true, 'stroke' => true, 'stroke-width' => true ],
        'polyline' => [ 'points' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true ],
        'polygon'  => [ 'points' => true, 'fill' => true, 'stroke' => true ],
    ] );
}

/**
 * Output navigation breadcrumb.
 */
function sh_breadcrumb( array $items = [] ): void {
    echo '<div class="breadcrumb">';
    echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'الرئيسية', 'seohouse' ) . '</a>';
    $sep = '<svg class="bc-sep" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>';
    foreach ( $items as $label => $url ) {
        echo $sep;
        if ( $url ) {
            echo '<a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a>';
        } else {
            echo '<span style="color:rgba(255,255,255,.6)">' . esc_html( $label ) . '</span>';
        }
    }
    echo '</div>';
}


/**
 * Get team member initials from name.
 */
function sh_initials( string $name ): string {
    $parts = explode( ' ', trim( $name ) );
    if ( count( $parts ) >= 2 ) {
        return mb_substr( $parts[0], 0, 1 ) . '.' . mb_substr( $parts[1], 0, 1 );
    }
    return mb_substr( $name, 0, 2 );
}

/**
 * CSS class for a result/metric value: 'pos' if starts with +, 'neg' if starts with -.
 */
function sh_value_class( string $v ): string {
    $v = ltrim( $v );
    if ( $v !== '' && $v[0] === '+' ) return 'pos';
    if ( $v !== '' && $v[0] === '-' ) return 'neg';
    return '';
}

/**
 * Render client logos section query.
 */
function sh_get_clients( int $per_page = -1 ): WP_Query {
    return new WP_Query( [
        'post_type'      => 'client',
        'posts_per_page' => $per_page,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'meta_query'     => [
            'relation' => 'OR',
            [
                'key'     => 'is_visible',
                'value'   => '1',
                'compare' => '=',
            ],
            [
                'key'     => 'is_visible',
                'compare' => 'NOT EXISTS',
            ],
        ],
    ] );
}

/**
 * Render platforms query.
 */
function sh_get_platforms(): WP_Query {
    return new WP_Query( [
        'post_type'      => 'platform',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'meta_query'     => [
            'relation' => 'OR',
            [
                'key'     => 'is_visible',
                'value'   => '1',
                'compare' => '=',
            ],
            [
                'key'     => 'is_visible',
                'compare' => 'NOT EXISTS',
            ],
        ],
    ] );
}

/**
 * Build a tel: href from a display phone number ("+20 105 086 1791" → "+201050861791").
 */
function sh_tel_href( string $phone ): string {
    $digits = preg_replace( '/\D/', '', $phone );
    if ( '' === $digits ) {
        return '';
    }
    return 'tel:' . ( str_starts_with( ltrim( $phone ), '+' ) ? '+' : '' ) . $digits;
}

/**
 * Parse a URL and check it is https on one of the allowed hosts.
 * Returns the parsed parts, or null when the URL is not acceptable.
 */
function sh_parse_allowed_url( string $url, array $hosts ): ?array {
    $url = trim( html_entity_decode( $url, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) );
    if ( '' === $url || preg_match( '/[\s"\'<>`]/', $url ) ) {
        return null;
    }
    $parts = wp_parse_url( $url );
    if ( empty( $parts['scheme'] ) || 'https' !== strtolower( $parts['scheme'] ) || empty( $parts['host'] ) ) {
        return null;
    }
    if ( ! empty( $parts['user'] ) || ! empty( $parts['pass'] ) || ! empty( $parts['port'] ) ) {
        return null;
    }
    $parts['host'] = strtolower( $parts['host'] );
    if ( ! in_array( $parts['host'], $hosts, true ) ) {
        return null;
    }
    $parts['url'] = $url;
    return $parts;
}

/**
 * Validate a Google Maps embed URL entered in the admin.
 *
 * Accepts either the bare embed URL or the full <iframe> code copied from
 * Google Maps → Share → Embed a map; only the src is extracted and only a
 * https://www.google.com/maps/embed… URL is accepted. Anything else returns ''.
 * The caller builds the <iframe> itself — the raw field HTML is never output.
 */
function sh_maps_embed_src( string $raw ): string {
    $raw = trim( $raw );
    if ( '' === $raw ) {
        return '';
    }
    if ( stripos( $raw, '<iframe' ) !== false ) {
        if ( ! preg_match( '/\ssrc\s*=\s*(["\'])(.*?)\1/is', $raw, $m ) ) {
            return '';
        }
        $raw = $m[2];
    }
    $parts = sh_parse_allowed_url( $raw, [ 'www.google.com', 'google.com', 'maps.google.com' ] );
    if ( ! $parts ) {
        return '';
    }
    $path  = $parts['path'] ?? '';
    $query = $parts['query'] ?? '';
    $is_embed = str_starts_with( $path, '/maps/embed' )
        || ( '/maps' === rtrim( $path, '/' ) && preg_match( '/(^|&)output=embed(&|$)/', $query ) );
    return $is_embed ? esc_url_raw( $parts['url'], [ 'https' ] ) : '';
}

/**
 * Validate a Google Maps directions / place link entered in the admin.
 * Accepts google.com/maps…, maps.google.com, maps.app.goo.gl and goo.gl/maps links.
 */
function sh_maps_link_url( string $raw ): string {
    $parts = sh_parse_allowed_url( $raw, [ 'www.google.com', 'google.com', 'maps.google.com', 'maps.app.goo.gl', 'goo.gl' ] );
    if ( ! $parts ) {
        return '';
    }
    $path = $parts['path'] ?? '';
    $ok   = match ( $parts['host'] ) {
        'maps.google.com', 'maps.app.goo.gl' => true,
        'goo.gl'                             => str_starts_with( $path, '/maps' ),
        default                              => str_starts_with( $path, '/maps' ),
    };
    return $ok ? esc_url_raw( $parts['url'], [ 'https' ] ) : '';
}
