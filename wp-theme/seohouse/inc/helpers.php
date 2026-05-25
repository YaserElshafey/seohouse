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
 * Get case study chart bars (returns array of heights 0-100).
 */
function sh_case_chart_bars( int $post_id ): array {
    $bars = sh_field( 'chart_bars', $post_id );
    if ( is_array( $bars ) && ! empty( $bars ) ) {
        return array_map( function ( $bar ) {
            return is_array( $bar ) ? (int) ( $bar['bar_height'] ?? 50 ) : (int) $bar;
        }, $bars );
    }
    return [ 20, 28, 24, 42, 55, 65, 74, 82, 92, 100 ];
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
