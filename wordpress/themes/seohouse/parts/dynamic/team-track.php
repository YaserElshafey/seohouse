<?php
/**
 * Homepage hero (mobile): horizontal photo tracks. Track 0 shows the first half of the team,
 * track 1 the second half, so the two rows never show the same people. The track moves by half its
 * width (shRtl), so it holds two identical copies; when a half of the team is too short to cover the
 * widest mobile row (min_px, at item_px per photo), its photos repeat inside each copy, so no gap
 * opens while it moves.
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
if ( ! $list ) {
	$list = $members; // a team of one: the only photo in both rows
}
$style = (string) ( $args['img_style'] ?? '' );
$item  = max( 1, (int) ( $args['item_px'] ?? 0 ) );
$times = isset( $args['min_px'], $args['item_px'] ) ? max( 1, (int) ceil( (int) $args['min_px'] / ( $item * count( $list ) ) ) ) : 1;
$copy  = array_merge( ...array_fill( 0, $times, $list ) ); // one copy, wide enough for the row
foreach ( array( false, true ) as $dup ) {
	foreach ( $copy as $n => $m ) {
		$hidden = $dup || $n >= count( $list ); // only the first appearance of a person is read out
		echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput
			get_post_thumbnail_id( $m ),
			'thumbnail',
			false,
			array(
				'alt'          => $hidden ? '' : get_the_title( $m ),
				'aria-hidden'  => $hidden ? 'true' : 'false',
				'loading'      => 'lazy',
				'decoding'     => 'async',
				'data-mob-face' => '',
				'style'        => $style,
			)
		);
	}
}
