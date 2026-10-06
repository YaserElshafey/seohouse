<?php
/**
 * Styles and scripts. CSS is split into: local fonts, the shared design base,
 * theme components, and one small file per design template (loaded only there).
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

/** Design key of the current view (matches content-pack/pages/<key>.json and assets/css/pages/<key>.css). */
function sh_view_key(): string {
	static $key = null;
	if ( null !== $key ) {
		return $key;
	}
	$key = '';
	if ( is_404() ) {
		$key = '404';
	} elseif ( is_search() ) {
		$key = 'search';
	} elseif ( is_singular( 'case_study' ) ) {
		$key = 'case-template';
	} elseif ( is_singular( 'team_member' ) ) {
		$key = 'team-member';
	} elseif ( is_singular( 'post' ) ) {
		$key = 'single-post';
	} elseif ( is_category() || is_tag() || is_author() || is_date() ) {
		$key = 'blog-category';
	} elseif ( is_home() ) {
		$key = 'blog';
	} elseif ( is_singular( 'page' ) ) {
		$tpl = get_page_template_slug();
		if ( $tpl && preg_match( '#^page-templates/([a-z0-9-]+)\.php$#', $tpl, $m ) ) {
			$key = $m[1];
		}
	}
	return $key;
}

function sh_asset_version( string $rel ): string {
	$file = SH_THEME_DIR . '/assets/' . $rel;
	return file_exists( $file ) ? SH_THEME_VERSION . '.' . filemtime( $file ) : SH_THEME_VERSION;
}

add_action(
	'wp_enqueue_scripts',
	static function () {
		$files = array( 'css/fonts.css', 'css/design-base.css', 'css/theme.css' );
		$key   = sh_view_key();
		if ( $key && file_exists( SH_THEME_DIR . "/assets/css/pages/{$key}.css" ) ) {
			$files[] = "css/pages/{$key}.css";
		}
		/*
		 * The whole stylesheet for a view is ~25–35 KB (≈6 KB compressed), so it is printed inline:
		 * no render-blocking requests. Filter "sh_inline_css" → false loads the files instead.
		 */
		if ( apply_filters( 'sh_inline_css', true ) ) {
			wp_register_style( 'sh-theme', false, array(), SH_THEME_VERSION );
			wp_enqueue_style( 'sh-theme' );
			wp_add_inline_style( 'sh-theme', sh_inline_css( $files ) );
		} else {
			$dep = array();
			foreach ( $files as $i => $rel ) {
				$handle = 'sh-css-' . $i;
				wp_enqueue_style( $handle, sh_asset( $rel ), $dep, sh_asset_version( $rel ) );
				$dep = array( $handle );
			}
			wp_register_style( 'sh-theme', false, $dep, SH_THEME_VERSION );
			wp_enqueue_style( 'sh-theme' );
		}
		$tokens = sh_design_token_css();
		if ( $tokens ) {
			wp_add_inline_style( 'sh-theme', $tokens );
		}

		wp_enqueue_script( 'sh-site', sh_asset( 'js/site.js' ), array(), sh_asset_version( 'js/site.js' ), array( 'strategy' => 'defer', 'in_footer' => true ) );
		wp_localize_script(
			'sh-site',
			'SH',
			array(
				'rest'     => esc_url_raw( rest_url( 'seohouse/v1/' ) ),
				'contact'  => esc_url_raw( home_url( '/contact/' ) ),
				'booking'  => sh_booking_config(),
				'i18n'     => array(
					'sending' => __( 'جارٍ الإرسال…', 'seohouse' ),
					'failed'  => __( 'تعذر إرسال الطلب الآن. حاول مرة أخرى أو تواصل معنا عبر صفحة التواصل.', 'seohouse' ),
				),
			)
		);
	}
);

/** Concatenated, lightly minified CSS (cached per file set and modification time). */
function sh_inline_css( array $files ): string {
	$sig = '';
	foreach ( $files as $rel ) {
		$sig .= $rel . sh_asset_version( $rel );
	}
	$cache_key = 'sh_css_' . md5( $sig . SH_THEME_URI . SH_THEME_VERSION . 'min2' );
	$css       = wp_cache_get( $cache_key, 'seohouse' );
	if ( false === $css ) {
		$css = get_transient( $cache_key );
	}
	if ( false !== $css ) {
		return (string) $css;
	}
	$css = '';
	foreach ( $files as $rel ) {
		$css .= (string) file_get_contents( SH_THEME_DIR . '/assets/' . $rel ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	}
	$css = str_replace( "url('../fonts/", "url('" . SH_THEME_URI . '/assets/fonts/', $css );
	$css = preg_replace( '#/\*.*?\*/#s', '', $css );
	// whitespace is collapsed outside quoted strings only: [style*="color: rgb(255, 255, 255)"]
	// must keep its spaces to match the inline style attribute as printed
	$parts = preg_split( '/("(?:\\\\.|[^"\\\\])*"|\'(?:\\\\.|[^\'\\\\])*\')/', $css, -1, PREG_SPLIT_DELIM_CAPTURE );
	foreach ( $parts as $i => $part ) {
		if ( 0 === $i % 2 ) {
			$part        = preg_replace( '/\s+/', ' ', $part );
			$parts[ $i ] = preg_replace( '/\s*([{};,>])\s*/', '$1', $part );
		}
	}
	$css = implode( '', $parts );
	$css = str_replace( ';}', '}', trim( $css ) );
	wp_cache_set( $cache_key, $css, 'seohouse' );
	set_transient( $cache_key, $css, WEEK_IN_SECONDS );
	return $css;
}

/** Preload the Arabic faces used above the fold (heading + the three body weights), so text does not reflow late. */
add_action(
	'wp_head',
	static function () {
		foreach ( (array) apply_filters( 'sh_preload_fonts', array( 'alexandria-arabic.woff2', 'ibm-plex-sans-arabic-400-arabic.woff2', 'ibm-plex-sans-arabic-600-arabic.woff2', 'ibm-plex-sans-arabic-700-arabic.woff2' ) ) as $f ) {
			printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( sh_asset( 'fonts/' . $f ) ) );
		}
	},
	2
);

/**
 * The colour settings of "إعدادات سيو هاوس ← التصميم" describe the 2.x palette (dark background,
 * text on dark, lime accent…). The approved light design has other roles, and a value saved with the
 * 2.x defaults (e.g. text #EDF1FA) would make body text unreadable on the light pages. They are
 * therefore no longer printed; the saved values stay in the database untouched.
 */
const SH_LEGACY_COLOR_FIELDS = array( 'field_sh_opt_c_ink', 'field_sh_opt_c_blue', 'field_sh_opt_c_sky', 'field_sh_opt_c_lime', 'field_sh_opt_c_paper', 'field_sh_opt_c_text', 'field_sh_opt_c_muted' );

foreach ( SH_LEGACY_COLOR_FIELDS as $sh_color_field ) {
	add_filter(
		'acf/prepare_field/key=' . $sh_color_field,
		static function ( $field ) {
			if ( is_array( $field ) ) {
				$field['instructions'] = __( 'من التصميم السابق: لا يؤثر على ألوان الموقع في التصميم المعتمد الحالي. القيمة المحفوظة باقية كما هي.', 'seohouse' );
			}
			return $field;
		}
	);
}
unset( $sh_color_field );

/**
 * Colour tokens from "إعدادات سيو هاوس ← التصميم" (2.x roles). Not applied since 2.7.0 (see above);
 * kept for the filter 'sh_apply_legacy_colors' should a site need them back.
 */
function sh_design_token_css(): string {
	if ( ! apply_filters( 'sh_apply_legacy_colors', false ) ) {
		return '';
	}
	$map = array(
		'ink'   => 'sh_color_ink',
		'blue'  => 'sh_color_blue',
		'sky'   => 'sh_color_sky',
		'lime'  => 'sh_color_lime',
		'paper' => 'sh_color_paper',
		'text'  => 'sh_color_text',
		'muted' => 'sh_color_muted',
	);
	$css = '';
	foreach ( $map as $token => $opt ) {
		$hex = sanitize_hex_color( (string) sh_option( $opt, '' ) );
		if ( ! $hex || 7 !== strlen( $hex ) ) {
			continue;
		}
		$rgb  = implode( ', ', array_map( 'hexdec', str_split( substr( $hex, 1 ), 2 ) ) );
		$css .= "--sh-{$token}: {$hex}; --sh-{$token}-rgb: {$rgb}; ";
	}
	return $css ? ':root { ' . $css . '}' : '';
}

/**
 * Booking integration settings exposed to the form script («إعدادات سيو هاوس ← الاستشارة»).
 * No URL unless a booking tool is chosen: «غير مربوطة» never shows a scheduler.
 */
function sh_booking_config(): array {
	$provider = (string) sh_option( 'sh_booking_provider', 'none' );
	$url      = 'none' === $provider || '' === $provider ? '' : (string) sh_option( 'sh_booking_url', '' );
	$minutes  = (int) sh_option( 'sh_booking_duration', 30 );
	return array(
		'provider' => $provider,
		'url'      => preg_match( '#^https?://#i', $url ) ? esc_url_raw( $url ) : '',
		'duration' => $minutes > 0 ? $minutes : 30,
	);
}
