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
	if ( ! $id ) {
		return null;
	}
	$to  = sh_is_en() ? 'ar' : 'en';
	$tid = sh_translation( $id, $to );
	if ( ! $tid || ( 'en' === $to && ! sh_i18n_indexable( $tid ) ) ) {
		return null;
	}
	return array(
		'url'   => (string) get_permalink( $tid ),
		'label' => 'en' === $to ? 'English' : 'العربية',
		'lang'  => $to,
	);
}

/** The switcher link (header, drawer). Empty when there is no other-language page. */
function sh_lang_switch_html( string $style ): string {
	$s = sh_lang_switch();
	if ( ! $s ) {
		return '';
	}
	return sprintf(
		'<a href="%1$s" hreflang="%2$s" lang="%2$s" data-sh-lang-switch style="%3$s">%4$s</a>',
		esc_url( $s['url'] ),
		esc_attr( $s['lang'] ),
		esc_attr( $style ),
		esc_html( $s['label'] )
	);
}
