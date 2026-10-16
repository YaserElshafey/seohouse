<?php
/**
 * Homepage hero: vertical photo columns (design data-hero-cols). Each column holds its photos
 * twice so the CSS loop is seamless; the copy is hidden from assistive technology.
 * Photos are shuffled once per page load by site.js (data-sh-shuffle), not on every re-render.
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

$members = sh_team_members( true );
$n       = max( 1, (int) ( $args['columns'] ?? 3 ) );
if ( ! $members ) {
	return;
}
$cols = array_fill( 0, $n, array() );
foreach ( $members as $i => $m ) {
	$cols[ $i % $n ][] = $m;
}
foreach ( $cols as $c => $list ) :
	?>
<div data-col data-sh-shuffle-group>
	<?php
	foreach ( array( false, true ) as $dup ) :
		foreach ( $list as $m ) :
			echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput
				get_post_thumbnail_id( $m ),
				'medium_large',
				false,
				array(
					'alt'         => $dup ? '' : get_the_title( $m ),
					'aria-hidden' => $dup ? 'true' : 'false',
					'loading'     => ( 0 === $c && ! $dup ) ? 'eager' : 'lazy',
					'decoding'    => 'async',
					'sizes'       => '(max-width: 1023px) 1px, 190px',
					'data-sh-face' => '',
				)
			);
		endforeach;
	endforeach;
	?>
</div>
	<?php
endforeach;
