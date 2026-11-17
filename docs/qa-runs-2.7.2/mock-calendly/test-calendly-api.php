<?php
// LOCAL TEST ONLY: answers api.calendly.com requests like Calendly API v2 (no network).
// Options: test_calendly_down (WP_Error), test_calendly_canceled (invitee canceled), test_calendly_start.
add_filter( 'pre_http_request', static function ( $pre, $args, $url ) {
	if ( ! str_starts_with( $url, 'https://api.calendly.com/' ) ) {
		return $pre;
	}
	if ( get_option( 'test_calendly_down' ) ) {
		return new WP_Error( 'http_request_failed', 'cURL error 28: Connection timed out (simulated)' );
	}
	if ( ( $args['headers']['Authorization'] ?? '' ) !== 'Bearer test-token' ) {
		return array( 'response' => array( 'code' => 401 ), 'body' => '{"title":"Unauthenticated"}', 'headers' => array(), 'cookies' => array() );
	}
	$ok = static fn( $r ) => array( 'response' => array( 'code' => 200 ), 'body' => wp_json_encode( array( 'resource' => $r ) ), 'headers' => array(), 'cookies' => array() );
	if ( preg_match( '#scheduled_events/EVT-([a-f0-9]+)/invitees/INV-([a-f0-9]+)$#', $url, $m ) ) {
		$ids   = get_posts( array( 'post_type' => 'sh_lead', 'post_status' => 'private', 'fields' => 'ids', 'meta_key' => '_sh_ref', 'meta_value' => $m[2] ) );
		$email = $ids ? get_post_meta( $ids[0], '_sh_email', true ) : 'someone@example.com';
		return $ok( array( 'uri' => $url, 'email' => $email, 'status' => get_option( 'test_calendly_canceled' ) ? 'canceled' : 'active', 'timezone' => 'Africa/Cairo', 'event' => 'https://api.calendly.com/scheduled_events/EVT-' . $m[1], 'tracking' => array( 'utm_content' => $m[2] ) ) );
	}
	if ( preg_match( '#scheduled_events/EVT-[a-f0-9]+$#', $url ) ) {
		$start = get_option( 'test_calendly_start', '2026-10-08T11:00:00.000000Z' );
		return $ok( array( 'uri' => $url, 'status' => 'active', 'start_time' => $start, 'end_time' => gmdate( 'Y-m-d\TH:i:s.000000\Z', strtotime( $start ) + 1800 ) ) );
	}
	return array( 'response' => array( 'code' => 404 ), 'body' => '{}', 'headers' => array(), 'cookies' => array() );
}, 10, 3 );
