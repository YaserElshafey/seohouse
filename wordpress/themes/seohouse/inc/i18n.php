<?php
/**
 * Language helpers for templates. The real ones live in SEO House Core (inc/i18n.php, Polylang);
 * without Core or Polylang these fallbacks keep the site Arabic and right-to-left as before.
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'sh_lang' ) ) {
	function sh_lang(): string {
		return 'ar';
	}
}
if ( ! function_exists( 'sh_is_en' ) ) {
	function sh_is_en(): bool {
		return false;
	}
}
if ( ! function_exists( 'sh_dir' ) ) {
	function sh_dir(): string {
		return 'rtl';
	}
}
if ( ! function_exists( 'sh_arrow' ) ) {
	function sh_arrow( string $to = 'next' ): string {
		return 'next' === $to ? '←' : '→';
	}
}
if ( ! function_exists( 'sh_tr_id' ) ) {
	function sh_tr_id( int $id ): int {
		return $id;
	}
}
if ( ! function_exists( 'sh_home_url' ) ) {
	function sh_home_url(): string {
		return home_url( '/' );
	}
}
if ( ! function_exists( 'sh_page_url' ) ) {
	function sh_page_url( string $path ): string {
		$page = get_page_by_path( trim( $path, '/' ), OBJECT, 'page' );
		return $page && 'publish' === $page->post_status ? (string) get_permalink( $page ) : '';
	}
}

/**
 * The link to the same page in the other language, or null.
 * On Arabic pages it appears only once the English page is indexable (released); English pages
 * always link back to their Arabic page.
 *
 * @return array{url:string,label:string,lang:string}|null
 */
function sh_lang_switch(): ?array {
	if ( ! function_exists( 'sh_translation' ) || ! function_exists( 'sh_i18n_active' ) || ! sh_i18n_active() ) {
		return null;
	}
	$id = 0;
	if ( is_front_page() ) {
		$id = (int) get_option( 'page_on_front' );
	} elseif ( is_home() ) {
		$id = (int) get_option( 'page_for_posts' );
	} elseif ( is_singular() ) {
		$id = (int) get_queried_object_id();
	}
	$to  = sh_is_en() ? 'ar' : 'en';
	$tid = $id ? sh_translation( $id, $to ) : 0;
	$url = $tid && ( 'ar' === $to || sh_i18n_indexable( $tid ) ) ? (string) get_permalink( $tid ) : '';
	if ( '' === $url ) {
		// no counterpart (404, search, an article written for one language, a category): the closest
		// page in the other language: its search, its blog, or its home page (2.8.7)
		$home = function_exists( 'pll_home_url' ) ? (string) pll_home_url( $to ) : '';
		if ( '' === $home || ( 'en' === $to && function_exists( 'sh_translation' ) && ! sh_translation( (int) get_option( 'page_on_front' ), 'en' ) ) ) {
			return null;
		}
		$blog = sh_translation( (int) get_option( 'page_for_posts' ), $to );
		if ( is_search() ) {
			$url = add_query_arg( 's', rawurlencode( get_search_query( false ) ), $home );
		} elseif ( $blog && 'publish' === get_post_status( $blog ) && ( is_singular( 'post' ) || is_category() || is_home() ) ) {
			$url = (string) get_permalink( $blog );
		} else {
			$url = $home;
		}
	}
	return array(
		'url'   => $url,
		'label' => 'en' === $to ? 'EN' : 'AR',
		'name'  => 'en' === $to ? 'English' : 'العربية',
		'lang'  => $to,
	);
}

/** The switcher link (header, drawer). Empty when there is no other-language page. */
function sh_lang_switch_html( string $style ): string {
	$s = sh_lang_switch();
	if ( ! $s ) {
		return '';
	}
	$globe = '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"></circle><path d="M3 12h18M12 3c2.5 2.6 3.8 5.6 3.8 9s-1.3 6.4-3.8 9c-2.5-2.6-3.8-5.6-3.8-9S9.5 5.6 12 3"></path></svg>';
	return sprintf(
		'<a href="%1$s" hreflang="%2$s" lang="%2$s" aria-label="%3$s" title="%3$s" class="sh-lang-switch" data-sh-lang-switch style="%4$s">%5$s<span>%6$s</span></a>',
		esc_url( $s['url'] ),
		esc_attr( $s['lang'] ),
		esc_attr( $s['name'] ),
		esc_attr( $style ),
		$globe,
		esc_html( $s['label'] )
	);
}

/**
 * On English pages, an article's featured image whose stored alt text is Arabic (media is shared by both
 * languages) is described by the English article title.
 */
add_filter(
	'wp_get_attachment_image_attributes',
	static function ( $attr, $attachment ) {
		if ( ! sh_is_en() || ! is_singular() || empty( $attr['alt'] ) || ! preg_match( '/\p{Arabic}/u', (string) $attr['alt'] ) ) {
			return $attr;
		}
		$id = (int) get_queried_object_id();
		if ( (int) get_post_thumbnail_id( $id ) === (int) $attachment->ID ) {
			$attr['alt'] = wp_strip_all_tags( get_the_title( $id ) );
		}
		return $attr;
	},
	10,
	2
);
