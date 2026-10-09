<?php
/**
 * Homepage hero (mobile): horizontal photo tracks. Track 0 shows the first half of the team,
 * track 1 the second half; each repeated once for a seamless loop.
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;

$members = sh_team_members( true );
if ( ! $members ) {
	return;
}
$half  = (int) ceil( count( $members ) / 2 );
$list  = 0 === (int) ( $args['track'] ?? 0 ) ? array_slice( $members, 0, $half ) : array_slice( $members, $half );
$style = (string) ( $args['img_style'] ?? '' );
foreach ( array( false, true ) as $dup ) {
	foreach ( $list as $m ) {
		echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput
			get_post_thumbnail_id( $m ),
			'thumbnail',
			false,
			array(
				'alt'          => $dup ? '' : get_the_title( $m ),
				'aria-hidden'  => $dup ? 'true' : 'false',
				'loading'      => 'lazy',
				'decoding'     => 'async',
				'data-mob-face' => '',
				'style'        => $style,
			)
		);
	}
}
