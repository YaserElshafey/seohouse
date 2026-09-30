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
		wp_enqueue_style( 'sh-fonts', sh_asset( 'css/fonts.css' ), array(), sh_asset_version( 'css/fonts.css' ) );
		wp_enqueue_style( 'sh-base', sh_asset( 'css/design-base.css' ), array( 'sh-fonts' ), sh_asset_version( 'css/design-base.css' ) );
		wp_enqueue_style( 'sh-theme', sh_asset( 'css/theme.css' ), array( 'sh-base' ), sh_asset_version( 'css/theme.css' ) );
		$key = sh_view_key();
		if ( $key && file_exists( SH_THEME_DIR . "/assets/css/pages/{$key}.css" ) ) {
			wp_enqueue_style( 'sh-page', sh_asset( "css/pages/{$key}.css" ), array( 'sh-theme' ), sh_asset_version( "css/pages/{$key}.css" ) );
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

/** Preload the two Arabic fonts used above the fold. */
add_action(
	'wp_head',
	static function () {
		foreach ( array( 'alexandria-arabic.woff2', 'ibm-plex-sans-arabic-400-arabic.woff2' ) as $f ) {
			printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( sh_asset( 'fonts/' . $f ) ) );
		}
	},
	2
);

/**
 * Colour tokens from "إعدادات سيو هاوس ← التصميم". Only approved roles can be changed;
 * values are validated hex colours.
 */
function sh_design_token_css(): string {
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

/** Booking integration settings exposed to the form script. */
function sh_booking_config(): array {
	$url = (string) sh_option( 'sh_booking_url', '' );
	return array(
		'provider' => (string) sh_option( 'sh_booking_provider', 'none' ),
		'url'      => $url ? esc_url_raw( $url ) : '',
	);
}
