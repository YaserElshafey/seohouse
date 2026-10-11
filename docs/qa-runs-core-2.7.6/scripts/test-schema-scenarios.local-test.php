<?php
// LOCAL TEST ONLY (never packaged): schema scenarios selected per request with ?shs=<name>.
//   rm-off     Rank Math not loaded → Core prints its own graph
//   rm-company Rank Math set like a live company site (Local SEO type Organization, own name/logo/phone/
//              social), schema on for case studies (Article) and posts; phone differs from Core on purpose
//   ti-rating  the reviews shortcode prints a widget with an AggregateRating JSON-LD block
$sh_s = isset( $_GET['shs'] ) ? (string) $_GET['shs'] : ( $_SERVER['HTTP_X_SHS'] ?? '' ); // phpcs:ignore
if ( getenv( 'SHS' ) && '' === $sh_s ) { // WP-CLI
	$sh_loop = getenv( 'SHS' );
} else {
	$sh_loop = $sh_s;
}
if ( $sh_loop ) { // loopback requests (the schema check) use the same scenario
	add_filter( 'http_request_args', static function ( $a ) use ( $sh_loop ) { $a['headers']['X-SHS'] = $sh_loop; return $a; } );
}
if ( 'rm-off' === $sh_s ) {
	add_filter( 'option_active_plugins', static fn( $p ) => array_values( array_diff( (array) $p, array( 'seo-by-rank-math/rank-math.php' ) ) ) );
}
if ( 'rm-company' === $sh_s ) {
	add_filter(
		'option_rank-math-options-titles',
		static function ( $o ) {
			$o = (array) $o;
			$o['knowledgegraph_type']                = 'company';
			$o['knowledgegraph_name']                = 'سيو هاوس';
			$o['knowledgegraph_logo']                = home_url( '/wp-content/themes/seohouse/assets/img/logo-white.png' );
			$o['url']                                = home_url( '/' );
			$o['phone']                              = '+20 100 000 0000';
			$o['local_business_type']                = 'ProfessionalService';
			$o['social_url_facebook']                = 'https://www.facebook.com/seohouse.agency';
			$o['pt_case_study_default_rich_snippet'] = 'article';
			$o['pt_case_study_default_article_type'] = 'Article';
			$o['local_address']                      = array( 'streetAddress' => '234 شارع عبد السلام عارف', 'addressLocality' => 'الإسكندرية', 'addressCountry' => 'EG' );
			$o['email']                              = 'info@seohouse.agency';
			return $o;
		}
	);
	// Rank Math printing its own phone on the company node (as Local SEO does on some setups)
	add_filter( 'rank_math/json_ld', static function ( $d ) { if ( isset( $d['publisher'] ) ) { $d['publisher']['telephone'] = '+20 100 000 0000'; $d['publisher']['@type'] = 'ProfessionalService'; $d['publisher']['aggregateRating'] = array( '@type' => 'AggregateRating', 'ratingValue' => '5', 'reviewCount' => '3' ); } return $d; }, 50 );
	add_filter( 'option_rank_math_modules', static fn( $m ) => array_values( array_unique( array_merge( (array) $m, array( 'local-seo' ) ) ) ) );
	add_filter(
		'option_rank-math-options-general',
		static function ( $o ) {
			$o = (array) $o;
			$o['open_graph_image'] = home_url( '/wp-content/themes/seohouse/assets/img/logo-white.png' );
			return $o;
		}
	);
}
if ( 'ti-rating' === $sh_s ) {
	add_filter( 'acf/load_value/name=sh_reviews_shortcode', static fn() => '[trustindex no-registration=google]' );
	add_action(
		'init',
		static function () {
			add_shortcode( 'trustindex', static fn() => '<div data-test-trustindex>widget ★★★★★ 4.9</div><script type="application/ld+json">{"@context":"https://schema.org","@type":"LocalBusiness","name":"X","aggregateRating":{"@type":"AggregateRating","ratingValue":"4.9","reviewCount":"20"}}</script><script type="application/ld+json">{"@context":"https://schema.org","@type":"Thing","name":"keep-me"}</script>' );
		},
		20
	);
}
if ( 'empty-opt' === $sh_s ) { // optional company data missing
	foreach ( array( 'sh_address', 'sh_founded', 'sh_company_name_en', 'sh_socials', 'sh_footer_text', 'sh_areas' ) as $f ) {
		add_filter( "acf/load_value/name=$f", static fn() => '' );
	}
}
if ( isset( $_GET['shdump'] ) ) { // log Rank Math's graph just before Core's filter (priority 100)
	add_filter( 'rank_math/json_ld', static function ( $d ) { error_log( 'SHDUMP ' . wp_json_encode( array_map( static fn( $n ) => is_array( $n ) ? array( 'type' => $n['@type'] ?? '', 'image' => $n['image'] ?? null, 'primary' => $n['primaryImageOfPage'] ?? null ) : null, $d ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) ); return $d; }, 99 );
}
if ( 'default-img' === $sh_s ) { // Rank Math using the site-wide default image for an article without its own
	add_filter( 'rank_math/json_ld', static function ( $d ) {
		$u = home_url( '/wp-content/themes/seohouse/assets/img/logo-white.png' );
		if ( is_singular( array( 'case_study', 'post' ) ) ) {
			$d['primaryImage'] = array( '@type' => 'ImageObject', '@id' => $u, 'url' => $u );
			if ( isset( $d['WebPage'] ) ) { $d['WebPage']['primaryImageOfPage'] = array( '@id' => $u ); }
			foreach ( $d as $k => $n ) { if ( is_array( $n ) && in_array( $n['@type'] ?? '', array( 'Article', 'BlogPosting' ), true ) ) { $d[ $k ]['image'] = array( '@id' => $u ); } }
			if ( is_singular( 'case_study' ) && ! isset( $d['richSnippet'] ) ) { /* case studies: Core graph */ }
		}
		return $d;
	}, 50 );
}
if ( 'rm-schema-off' === $sh_s ) { // Rank Math active, Schema module switched off (the live setup)
	add_filter( 'option_rank_math_modules', static fn( $m ) => array_values( array_diff( (array) $m, array( 'rich-snippet' ) ) ) );
}
if ( 'dup' === $sh_s ) { // another plugin printing its own company schema
	add_action( 'wp_head', static function () { echo '<script type="application/ld+json" class="other-plugin">{"@context":"https://schema.org","@type":"Organization","name":"X"}</script>' . "\n"; }, 40 );
}
if ( 'none' === $sh_s ) { // Core's output switched off
	add_filter( 'sh_schema_enabled', '__return_false' );
}
