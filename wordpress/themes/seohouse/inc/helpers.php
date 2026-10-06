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
 * Body text of a document section (privacy policy, terms): plain text typed in a textarea.
 * A blank line starts a new paragraph; lines starting with "- " or "• " form a list. Inline
 * markup of sh_inline_allowed() (links, bold) is kept.
 */
function sh_doc_text( $text ): string {
	$text = trim( str_replace( "\r\n", "\n", (string) $text ) );
	if ( '' === $text ) {
		return '';
	}
	$out = '';
	foreach ( preg_split( '/\n\s*\n/', $text ) as $block ) {
		$lines = array_values( array_filter( array_map( 'trim', explode( "\n", $block ) ), 'strlen' ) );
		$items = array_filter( $lines, static fn( $l ) => (bool) preg_match( '/^(?:-|•)\s+/u', $l ) );
		if ( $items && count( $items ) === count( $lines ) ) {
			$out .= '<ul style="margin: 0px 0px 14px; padding-inline-start: 22px; display: flex; flex-direction: column; gap: 6px;">';
			foreach ( $lines as $l ) {
				$out .= '<li>' . sh_inline( preg_replace( '/^(?:-|•)\s+/u', '', $l ) ) . '</li>';
			}
			$out .= '</ul>';
			continue;
		}
		$out .= '<p style="margin: 0px 0px 14px;">' . implode( '<br>', array_map( 'sh_inline', $lines ) ) . '</p>';
	}
	return $out;
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
 * @param string    $size     Registered image size (srcset still lists the other sizes).
 */
function sh_image( $id, array $attrs = array(), string $alt_fallback = '', string $size = 'full' ): string {
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
	return wp_get_attachment_image( $id, $size, false, $attrs );
}

/** Platform or tool logo: the dashboard library (Core) first, then the design SVG shipped with the theme. */
function sh_svg_img( $file, string $alt = '', array $attrs = array(), int $image = 0 ): string {
	$file = ltrim( (string) $file, '/' );
	$src  = '';
	$url  = '';
	// platforms and tools library (Core): the logo and link chosen in the dashboard
	$p = ( '' !== $file && function_exists( 'sh_core_platform' ) ) ? sh_core_platform( $file ) : null;
	if ( $p ) {
		$src = sh_core_platform_logo_url( $p );
		$url = (string) $p['url'];
	}
	// a logo picked from the media library in the section itself (this page only)
	if ( $image ) {
		$own = (string) wp_get_attachment_url( $image );
		if ( '' !== $own ) {
			$src = $own;
			if ( '' === $alt && ! $p ) {
				$alt = (string) get_post_meta( $image, '_wp_attachment_image_alt', true );
			}
		}
	}
	// otherwise the design's own file shipped with the theme
	if ( '' === $src && '' !== $file && preg_match( '#^assets/platforms/[a-z0-9/_-]+\.svg$#i', $file ) && file_exists( SH_THEME_DIR . '/' . $file ) ) {
		$src = SH_THEME_URI . '/' . $file;
	}
	if ( '' === $src ) {
		return '';
	}
	$out = '<img src="' . esc_url( $src ) . '" alt="' . esc_attr( $alt ) . '"';
	foreach ( $attrs as $k => $v ) {
		$out .= ' ' . esc_attr( $k ) . ( '' === $v ? '' : '="' . esc_attr( $v ) . '"' );
	}
	$out .= ' decoding="async">';
	if ( $url ) {
		$label = '' !== $alt ? $alt : (string) ( $p['name'] ?? '' );
		$out   = '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener" aria-label="' . esc_attr( $label ) . '" style="display:contents">' . $out . '</a>';
	}
	return $out;
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
	// aliases keep saved values of an older design working; they are not offered as new choices
	$icons = array_filter( $icons, static fn( $icon ) => empty( $icon['alias_of'] ) );
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
	return SH_THEME_URI . '/assets/img/logo-white.webp';
}

/** Logo for light backgrounds (header): options «الشعار على الخلفية الفاتحة» → theme default. */
function sh_logo_light_url(): string {
	$id = (int) sh_option( 'sh_logo_light', 0 );
	if ( $id ) {
		$src = wp_get_attachment_image_url( $id, 'full' );
		if ( $src ) {
			return $src;
		}
	}
	return SH_THEME_URI . '/assets/img/logo-blue.webp';
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

/**
 * Style attributes for a tab element that switches between the design's selected/unselected styles
 * (site.js swaps data-style-on / data-style-off).
 */
function sh_tab_style( bool $on, string $style_on, string $style_off ): string {
	return sprintf( ' style="%s" data-style-on="%s" data-style-off="%s"', esc_attr( $on ? $style_on : $style_off ), esc_attr( $style_on ), esc_attr( $style_off ) );
}
