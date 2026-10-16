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

/**
 * «تفاصيل الخدمة ←»-style card link text, escaped. The words go in <span data-lcard-text> (underlined on
 * hover/focus) and a trailing arrow typed in the field in <span data-lcard-arrow> (nudged forward); the
 * text itself is unchanged.
 */
function sh_more_label( $text ): string {
	$text = (string) $text;
	if ( preg_match( '/^(.*?)(\s*)([←→])$/u', $text, $m ) ) {
		return '<span data-lcard-text>' . esc_html( $m[1] ) . '</span>' . $m[2] . '<span data-lcard-arrow>' . $m[3] . '</span>';
	}
	return '<span data-lcard-text>' . esc_html( $text ) . '</span>';
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

/** Official sites of the platforms and tools shipped with the design (fallback when the library has no link). */
const SH_PLATFORM_SITES = array(
	'wordpress.svg'     => 'https://wordpress.org/',
	'woocommerce.svg'   => 'https://woocommerce.com/',
	'shopify.svg'       => 'https://www.shopify.com/',
	'salla.svg'         => 'https://salla.sa/',
	'webflow.svg'       => 'https://webflow.com/',
	'react.svg'         => 'https://react.dev/',
	'nextjs.svg'        => 'https://nextjs.org/',
	'gsc.svg'           => 'https://search.google.com/search-console/about',
	'ga4.svg'           => 'https://marketingplatform.google.com/about/analytics/',
	'looker.svg'        => 'https://lookerstudio.google.com/',
	'screamingfrog.svg' => 'https://www.screamingfrog.co.uk/seo-spider/',
);

/**
 * One logo of the «منصات وأدوات نعمل عليها» track: the logo alone, linked to the platform.
 * Link: the platform's link in the library («إعدادات سيو هاوس ← المنصات والأدوات»), else its official site.
 * The name (section label, else library name) is the link's accessible name; an item without a logo
 * keeps its name visible. Copies in the duplicate half of the track are hidden from assistive tech.
 */
function sh_platform_logo_link( string $logo, int $image, string $label, bool $dup = false ): string {
	$p    = ( '' !== $logo && function_exists( 'sh_core_platform' ) ) ? sh_core_platform( ltrim( $logo, '/' ) ) : null;
	$name = '' !== trim( $label ) ? trim( $label ) : (string) ( $p['name'] ?? '' );
	$url  = $p ? (string) $p['url'] : '';
	if ( '' === $url ) {
		$url = SH_PLATFORM_SITES[ basename( '' !== $logo ? $logo : (string) ( $p['value'] ?? '' ) ) ] ?? '';
	}
	$src = '';
	if ( $image ) {
		$src = (string) wp_get_attachment_url( $image );
	}
	if ( '' === $src && $p ) {
		$src = sh_core_platform_logo_url( $p );
	}
	if ( '' === $src && preg_match( '#^assets/platforms/[a-z0-9/_-]+\.svg$#i', ltrim( $logo, '/' ) ) && file_exists( SH_THEME_DIR . '/' . ltrim( $logo, '/' ) ) ) {
		$src = SH_THEME_URI . '/' . ltrim( $logo, '/' );
	}
	$inner = '' !== $src
		? '<img src="' . esc_url( $src ) . '" alt="" loading="lazy" decoding="async" data-plat-logo>'
		: '<span>' . esc_html( $name ) . '</span>';
	$tag   = '' !== $url ? 'a' : 'span';
	$attrs = '' !== $url ? ' href="' . esc_url( $url ) . '" target="_blank" rel="noopener"' . ( $dup ? ' tabindex="-1"' : '' ) : '';
	$a11y  = '' !== $src ? ( '' !== $url ? ' aria-label="' . esc_attr( $name ) . '"' : ' role="img" aria-label="' . esc_attr( $name ) . '"' ) : '';
	return '<' . $tag . ' data-plat-chip data-plat-logo-only' . $attrs . $a11y . ( '' !== $name ? ' title="' . esc_attr( $name ) . '"' : '' ) . '>' . $inner . '</' . $tag . '>';
}

/**
 * «شاهد جميع المراجعات على Google» under the reviews widget (every page with a reviews section).
 * Link, first found: the page's reviews section (all_link) → «إعدادات سيو هاوس ← تقييمات جوجل ←
 * رابط جميع المراجعات» → a Google Maps / Business Profile link saved in «الفوتر ← روابط التواصل في الفوتر» (sh_socials).
 * Never guessed: no saved link, no button.
 *
 * @return array{url:string,label:string}
 */
function sh_google_reviews_link( int $post_id = 0 ): array {
	$post_id = $post_id ? $post_id : (int) get_queried_object_id();
	$url     = '';
	$label   = '';
	foreach ( sh_sections_rows( $post_id ) as $row ) {
		if ( is_array( $row ) && str_starts_with( (string) ( $row['acf_fc_layout'] ?? '' ), 'reviews' ) ) {
			$url   = trim( (string) ( $row['all_link'] ?? '' ) );
			$label = trim( (string) ( $row['all_label'] ?? '' ) );
			break;
		}
	}
	if ( '' === $url ) {
		$url = trim( (string) sh_option( 'sh_reviews_all_link', '' ) );
	}
	if ( '' === $url ) {
		foreach ( (array) sh_option( 'sh_socials', array() ) as $row ) {
			$u = trim( (string) ( is_array( $row ) ? ( $row['url'] ?? '' ) : '' ) );
			if ( preg_match( '#^https?://(?:maps\.app\.goo\.gl|goo\.gl/maps|g\.page|share\.google|(?:www\.|maps\.)?google\.[a-z.]+/maps|search\.google\.com/local|g\.co/kgs)/#i', $u ) ) {
				$url = $u;
				break;
			}
		}
	}
	if ( '' === $label ) {
		$label = trim( (string) sh_option( 'sh_reviews_all_label', '' ) );
	}
	return array(
		'url'   => preg_match( '#^https?://#i', $url ) ? $url : '',
		'label' => '' !== $label ? $label : __( 'شاهد جميع المراجعات على Google', 'seohouse' ),
	);
}

function sh_google_reviews_button( int $post_id = 0 ): string {
	$l = sh_google_reviews_link( $post_id );
	if ( '' === $l['url'] ) {
		return '';
	}
	return '<div style="margin-top: 22px; text-align: center;"><a href="' . esc_url( $l['url'] ) . '" target="_blank" rel="noopener" data-reviews-all class="hv-54a5cb" style="display: inline-flex; align-items: center; gap: 8px; min-height: 44px; font-size: 15px; font-weight: 600; color: var(--sh-link); border-bottom: 1px solid rgba(40, 84, 232, 0.45);">' . esc_html( $l['label'] ) . ' <span aria-hidden="true">↗</span><span class="screen-reader-text">' . esc_html__( '(يفتح في نافذة جديدة)', 'seohouse' ) . '</span></a></div>';
}

/**
 * Footer «نبذة الفوتر» (text from «إعدادات سيو هاوس ← الهوية»): escaped, with its first «شركة سيو»
 * linked to the general SEO page (get_permalink of /services/seo/, when published).
 */
function sh_footer_about_html( string $text ): string {
	$html   = esc_html( $text );
	$phrase = esc_html__( 'شركة سيو', 'seohouse' );
	$page   = get_page_by_path( 'services/seo', OBJECT, 'page' );
	$pos    = mb_strpos( $html, $phrase );
	if ( ! $page || 'publish' !== $page->post_status || false === $pos ) {
		return $html;
	}
	$link = '<a href="' . esc_url( get_permalink( $page ) ) . '" style="color: inherit; text-decoration: underline; text-underline-offset: 3px;">' . $phrase . '</a>';
	return mb_substr( $html, 0, $pos ) . $link . mb_substr( $html, $pos + mb_strlen( $phrase ) );
}

/** One line after the article body: «تبحث عن شركة سيو …؟» linking to the general SEO page. */
function sh_article_seo_line(): string {
	$page = get_page_by_path( 'services/seo', OBJECT, 'page' );
	if ( ! $page || 'publish' !== $page->post_status ) {
		return '';
	}
	return '<p data-art-seo-line>' . sprintf(
		/* translators: %s: link «شركة سيو» */
		esc_html__( 'تبحث عن %s لتحسين أداء موقعك؟ تعرّف على خدمات سيو هاوس.', 'seohouse' ),
		'<a href="' . esc_url( get_permalink( $page ) ) . '">' . esc_html__( 'شركة سيو', 'seohouse' ) . '</a>'
	) . '</p>';
}

/**
 * Company contact data, one source: «إعدادات سيو هاوس ← بيانات الشركة» (also used by the structured data).
 *
 * @return array{phone:string,email:string,address:string,hours:string}
 */
function sh_company_contact(): array {
	$email = sanitize_email( (string) sh_option( 'sh_email', '' ) );
	return array(
		'phone'   => trim( (string) sh_option( 'sh_phone', '' ) ),
		'email'   => is_email( $email ) ? $email : '',
		'address' => trim( (string) sh_option( 'sh_address', '' ) ),
		'hours'   => trim( (string) sh_option( 'sh_hours', '' ) ),
	);
}

/** tel: link of a phone number as typed (Arabic-Indic digits converted; only + and digits kept). */
function sh_tel_href( string $phone ): string {
	$phone = strtr( $phone, array_combine( array( '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩' ), range( 0, 9 ) ) );
	$num   = preg_replace( '/[^0-9+]/', '', $phone );
	$num   = '+' === substr( $num, 0, 1 ) ? '+' . str_replace( '+', '', substr( $num, 1 ) ) : str_replace( '+', '', $num );
	return strlen( preg_replace( '/\D/', '', $num ) ) >= 6 ? 'tel:' . $num : '';
}

/**
 * Google Maps embed URL from the «رابط تضمين الخريطة» field: the embed link itself, or the src of the
 * <iframe> code Google gives («تضمين خريطة ← نسخ HTML»). Only https Google Maps embed URLs are accepted;
 * nothing else from the field is ever printed.
 */
function sh_map_embed_src( string $raw ): string {
	$raw = trim( $raw );
	if ( '' === $raw ) {
		return '';
	}
	if ( stripos( $raw, '<iframe' ) !== false ) {
		if ( ! preg_match( '/\ssrc\s*=\s*(["\'])(.*?)\1/is', $raw, $m ) ) {
			return '';
		}
		$raw = html_entity_decode( $m[2], ENT_QUOTES );
	}
	$p = wp_parse_url( $raw );
	if ( ! $p || 'https' !== strtolower( (string) ( $p['scheme'] ?? '' ) ) ) {
		return '';
	}
	$host  = strtolower( (string) ( $p['host'] ?? '' ) );
	$path  = (string) ( $p['path'] ?? '' );
	$query = (string) ( $p['query'] ?? '' );
	$ok    = ( in_array( $host, array( 'www.google.com', 'google.com' ), true ) && str_starts_with( $path, '/maps/embed' ) )
		|| ( 'maps.google.com' === $host && ( str_starts_with( $path, '/maps/embed' ) || ( str_starts_with( $path, '/maps' ) && preg_match( '/(^|&)output=embed(&|$)/', $query ) ) ) );
	return $ok ? esc_url_raw( $raw, array( 'https' ) ) : '';
}

/** «رابط الاتجاهات»: an https Google Maps link (google.TLD/maps, maps.app.goo.gl, goo.gl/maps, g.page), else ''. */
function sh_map_directions_url( string $raw ): string {
	$raw = trim( $raw );
	$p   = wp_parse_url( $raw );
	if ( ! $p || 'https' !== strtolower( (string) ( $p['scheme'] ?? '' ) ) ) {
		return '';
	}
	$host = strtolower( (string) ( $p['host'] ?? '' ) );
	$path = (string) ( $p['path'] ?? '' );
	$ok   = 'maps.app.goo.gl' === $host || 'g.page' === $host || 'maps.google.com' === $host
		|| ( 'goo.gl' === $host && str_starts_with( $path, '/maps' ) )
		|| ( preg_match( '/^(www\.)?google\.[a-z.]+$/', $host ) && str_starts_with( $path, '/maps' ) );
	return $ok ? esc_url_raw( $raw, array( 'https' ) ) : '';
}

/** Small line icon for the contact card (the theme icon library has no telephone icon; same 24px grid and stroke). */
function sh_contact_icon( string $kind ): string {
	$library = array( 'email' => 'i817572ba', 'address' => 'i8a72afdc', 'hours' => 'i5b10b0a6' );
	if ( isset( $library[ $kind ] ) ) {
		return sh_icon( $library[ $kind ] );
	}
	return '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6.6 3.5h2.6l1.5 4.2-2 1.4a12 12 0 0 0 6.2 6.2l1.4-2 4.2 1.5v2.6a2 2 0 0 1-2.2 2A17 17 0 0 1 4.6 5.7a2 2 0 0 1 2-2.2z"/></svg>';
}

/**
 * Brand marks for the footer social links (official glyphs from Simple Icons 9.21, CC0-1.0).
 * Platform from the link's host; links of other kinds (e.g. a Google Maps link used by the reviews
 * button) are not shown in the footer row.
 *
 * @return array{name:string,path:string}|null
 */
function sh_social_platform( string $url ): ?array {
	$host = strtolower( (string) wp_parse_url( $url, PHP_URL_HOST ) );
	$map  = array(
		'facebook.com'  => array( 'name' => __( 'فيسبوك', 'seohouse' ), 'path' => 'M9.101 23.691v-7.98H6.627v-3.667h2.474v-1.58c0-4.085 1.848-5.978 5.858-5.978.401 0 .955.042 1.468.103a8.68 8.68 0 0 1 1.141.195v3.325a8.623 8.623 0 0 0-.653-.036 26.805 26.805 0 0 0-.733-.009c-.707 0-1.259.096-1.675.309a1.686 1.686 0 0 0-.679.622c-.258.42-.374.995-.374 1.752v1.297h3.919l-.386 2.103-.287 1.564h-3.246v8.245C19.396 23.238 24 18.179 24 12.044c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.628 3.874 10.35 9.101 11.647Z' ),
		'fb.com'        => 'facebook.com',
		'instagram.com' => array( 'name' => __( 'إنستجرام', 'seohouse' ), 'path' => 'M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z' ),
		'linkedin.com'  => array( 'name' => __( 'لينكدإن', 'seohouse' ), 'path' => 'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z' ),
	);
	foreach ( $map as $domain => $hit ) { // the domain itself or any subdomain (www., m., eg. …)
		if ( $host === $domain || str_ends_with( $host, '.' . $domain ) ) {
			return is_string( $hit ) ? $map[ $hit ] : $hit;
		}
	}
	return null;
}

/** Footer social icons row (links from «إعدادات سيو هاوس ← الفوتر ← روابط التواصل في الفوتر»). */
function sh_footer_social_icons(): string {
	$items = '';
	foreach ( (array) sh_option( 'sh_socials', array() ) as $s ) {
		$url = is_array( $s ) ? trim( (string) ( $s['url'] ?? '' ) ) : '';
		if ( ! preg_match( '#^https?://#i', $url ) ) {
			continue;
		}
		$p = sh_social_platform( $url );
		if ( ! $p ) {
			continue;
		}
		$label  = trim( (string) ( $s['label'] ?? '' ) );
		$label  = '' !== $label ? $label : $p['name'];
		$items .= '<li><a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer me" aria-label="' . esc_attr( sprintf( /* translators: %s: platform */ __( '%s (يفتح في نافذة جديدة)', 'seohouse' ), $label ) ) . '" title="' . esc_attr( $label ) . '"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="' . esc_attr( $p['path'] ) . '"/></svg></a></li>';
	}
	return '' !== $items ? '<ul class="sh-foot-social" aria-label="' . esc_attr__( 'روابط التواصل', 'seohouse' ) . '">' . $items . '</ul>' : '';
}
