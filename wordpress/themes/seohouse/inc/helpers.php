<?php
/**
 * Output helpers used by the generated section templates.
 * All values are escaped at output.
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

/** Inline markup allowed inside short text fields (headings, labels). */
function sh_inline_allowed(): array {
	return array(
		'bdi'    => array( 'dir' => true ),
		'br'     => array(),
		'strong' => array(),
		'b'      => array(),
		'em'     => array(),
		'small'  => array( 'style' => true ),
		'sup'    => array(),
		'sub'    => array(),
		'span'   => array( 'style' => true, 'dir' => true, 'class' => true ),
		'a'      => array( 'href' => true, 'style' => true, 'class' => true ),
	);
}

function sh_inline( $html ): string {
	return wp_kses( (string) $html, sh_inline_allowed() );
}

/**
 * Normalise a link field value (page_link returns a URL; text links may be "/path/" or "#id").
 */
function sh_link( $value ): string {
	if ( is_array( $value ) ) {
		$value = $value['url'] ?? '';
	}
	if ( is_numeric( $value ) ) {
		return (string) get_permalink( (int) $value );
	}
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return '';
	}
	if ( '#' === $value[0] || preg_match( '#^(https?:|mailto:|tel:)#i', $value ) ) {
		return $value;
	}
	if ( '/' === $value[0] ) {
		return home_url( $value );
	}
	return $value;
}

/** Section anchor id: editor override or the design default. */
function sh_anchor( array $f, string $default ): string {
	$v = isset( $f['sh_anchor'] ) ? sanitize_title( (string) $f['sh_anchor'] ) : '';
	return $v ? $v : $default;
}

/**
 * Attachment image with the design's inline attributes.
 *
 * @param int|array $id       Attachment ID (or ACF image array).
 * @param array     $attrs    Attributes from the design (style, class, loading, data-*).
 * @param string    $alt_fallback Alt text from the design when the attachment has none.
 */
function sh_image( $id, array $attrs = array(), string $alt_fallback = '' ): string {
	if ( is_array( $id ) ) {
		$id = $id['ID'] ?? ( $id['id'] ?? 0 );
	}
	$id = (int) $id;
	if ( ! $id ) {
		return '';
	}
	$alt = trim( (string) get_post_meta( $id, '_wp_attachment_image_alt', true ) );
	if ( '' === $alt ) {
		$attrs['alt'] = $alt_fallback;
	}
	if ( empty( $attrs['loading'] ) ) {
		$attrs['loading'] = 'lazy';
	}
	$attrs['decoding'] = 'async';
	return wp_get_attachment_image( $id, 'full', false, $attrs );
}

/** Approved design SVG (platform logos) shipped with the theme. */
function sh_svg_img( $file, string $alt = '', array $attrs = array() ): string {
	$file = ltrim( (string) $file, '/' );
	if ( '' === $file || ! preg_match( '#^assets/platforms/[a-z0-9/_-]+\.svg$#i', $file ) || ! file_exists( SH_THEME_DIR . '/' . $file ) ) {
		return '';
	}
	$out = '<img src="' . esc_url( SH_THEME_URI . '/' . $file ) . '" alt="' . esc_attr( $alt ) . '"';
	foreach ( $attrs as $k => $v ) {
		$out .= ' ' . esc_attr( $k ) . ( '' === $v ? '' : '="' . esc_attr( $v ) . '"' );
	}
	if ( ! isset( $attrs['loading'] ) ) {
		$out .= ' loading="lazy"';
	}
	return $out . '>';
}

/** Inline SVG icon from the design icon library (inc/generated/icons.php). */
function sh_icon( $id ): string {
	static $icons = null;
	if ( null === $icons ) {
		$file  = SH_THEME_DIR . '/inc/generated/icons.php';
		$icons = file_exists( $file ) ? include $file : array();
	}
	return isset( $icons[ $id ] ) ? $icons[ $id ]['svg'] : '';
}

/** Choices for icon select fields (used by Core when it builds option fields). */
function sh_icon_choices(): array {
	$file  = SH_THEME_DIR . '/inc/generated/icons.php';
	$icons = file_exists( $file ) ? include $file : array();
	return wp_list_pluck( $icons, 'label' );
}

/** Site logo (options → theme default). */
function sh_logo_url(): string {
	$id = (int) sh_option( 'sh_logo', 0 );
	if ( $id ) {
		$src = wp_get_attachment_image_url( $id, 'full' );
		if ( $src ) {
			return $src;
		}
	}
	return SH_THEME_URI . '/assets/img/logo-white.png';
}

function sh_site_name(): string {
	return (string) sh_option( 'sh_company_name', get_bloginfo( 'name' ) );
}

/** Theme asset URL. */
function sh_asset( string $path ): string {
	return SH_THEME_URI . '/assets/' . ltrim( $path, '/' );
}

/** Returns an escaped attribute string from an array. */
function sh_attrs( array $attrs ): string {
	$out = '';
	foreach ( $attrs as $k => $v ) {
		if ( null === $v || false === $v ) {
			continue;
		}
		$out .= ' ' . esc_attr( $k ) . ( true === $v || '' === $v ? '' : '="' . esc_attr( (string) $v ) . '"' );
	}
	return $out;
}
