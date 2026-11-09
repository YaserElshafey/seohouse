<?php
/**
 * Site hardening (2.9.13), from the security review of the live site:
 *
 * - Security response headers on front-end pages (HSTS over HTTPS, nosniff, same-origin framing,
 *   referrer policy, permissions policy).
 * - XML-RPC off (the site and its deploys use REST only): no methods, no X-Pingback header.
 * - No WordPress version in the page head or feeds.
 * - /author/<login>/ never confirms whether a login exists: every such address goes to the team page.
 *   ?author=<ID> keeps leading to that writer's team profile (it shows no login).
 * - Sign-in throttle: 10 failed sign-ins from one address (IPv6: its /64) block that address's
 *   sign-ins for 15 minutes. Application passwords (REST deploys) are not counted or blocked here.
 * - Server rules in the site's root .htaccess, written on request through
 *   POST seohouse/v1/hardening (admin only) between «# BEGIN SEO House Hardening» markers: no directory
 *   listings; xmlrpc.php, readme.html, license.txt, wp-config-sample.php and the theme's file list are
 *   refused. Right after writing, the home page, a stylesheet and the REST index are requested; if any
 *   answers with a server error the block is removed again and the route reports it.
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

/** The visitor's network for counting: the IPv4 address, or the /64 network of an IPv6 address. */
function sh_core_client_net(): string {
	$ip = (string) ( $_SERVER['REMOTE_ADDR'] ?? '' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	if ( false !== filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 ) ) {
		$bin = inet_pton( $ip );
		if ( false !== $bin ) {
			return bin2hex( substr( $bin, 0, 8 ) ) . '::/64';
		}
	}
	return $ip;
}

/* ------------------------------------------------------------------ response headers */

add_action(
	'send_headers',
	static function () {
		if ( is_admin() || headers_sent() ) {
			return;
		}
		if ( is_ssl() ) {
			header( 'Strict-Transport-Security: max-age=31536000' );
		}
		header( 'X-Content-Type-Options: nosniff' );
		header( 'X-Frame-Options: SAMEORIGIN' );
		header( 'Referrer-Policy: strict-origin-when-cross-origin' );
		header( 'Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=(), usb=()' );
	}
);

/* ------------------------------------------------------------------ XML-RPC and version */

add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter( 'xmlrpc_methods', static fn() => array() );
add_filter(
	'wp_headers',
	static function ( $headers ) {
		unset( $headers['X-Pingback'] );
		return $headers;
	}
);
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );

/* ------------------------------------------------------------------ author logins */

// Before WordPress looks the login up (an unknown login would answer 404, a known one would redirect).
add_filter(
	'request',
	static function ( $qv ) {
		if ( is_admin() || empty( $qv['author_name'] ) || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return $qv;
		}
		$team = get_page_by_path( 'team' );
		$id   = $team && 'publish' === $team->post_status ? (int) $team->ID : 0;
		$path  = (string) wp_parse_url( (string) ( $_SERVER['REQUEST_URI'] ?? '' ), PHP_URL_PATH ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$is_en = 'en' === ( $qv['lang'] ?? '' ) || 0 === strpos( $path, '/en/' );
		if ( $id && $is_en && function_exists( 'pll_get_post' ) ) {
			$en = (int) pll_get_post( $id, 'en' );
			$id = $en && 'publish' === get_post_status( $en ) ? $en : $id;
		}
		wp_safe_redirect( $id ? (string) get_permalink( $id ) : home_url( '/' ), 301, 'SEO House' );
		exit;
	},
	1
);

/* ------------------------------------------------------------------ sign-in throttle */

function sh_core_login_key(): string {
	return 'sh_login_fail_' . substr( hash_hmac( 'sha256', sh_core_client_net(), wp_salt( 'nonce' ) ), 0, 20 );
}

add_action(
	'wp_login_failed',
	static function () {
		$k = sh_core_login_key();
		set_transient( $k, (int) get_transient( $k ) + 1, 15 * MINUTE_IN_SECONDS );
	}
);

// Last in the chain, so a blocked address cannot sign in even with the right password.
add_filter(
	'authenticate',
	static function ( $user, $username ) {
		if ( '' === (string) $username || (int) get_transient( sh_core_login_key() ) < 10 ) {
			return $user;
		}
		return new WP_Error( 'sh_login_blocked', __( 'محاولات دخول كثيرة من هذا الجهاز. حاول مرة أخرى بعد 15 دقيقة.', 'seohouse-core' ) );
	},
	PHP_INT_MAX,
	2
);

add_action(
	'wp_login',
	static function () {
		delete_transient( sh_core_login_key() );
	}
);

/* ------------------------------------------------------------------ server rules (.htaccess) */

function sh_core_hardening_rules(): array {
	$deny = array(
		"\t<IfModule mod_authz_core.c>",
		"\t\tRequire all denied",
		"\t</IfModule>",
		"\t<IfModule !mod_authz_core.c>",
		"\t\tOrder allow,deny",
		"\t\tDeny from all",
		"\t</IfModule>",
	);
	return array_merge(
		array( 'Options -Indexes', '<FilesMatch "^(xmlrpc\.php|readme\.html|license\.txt|wp-config-sample\.php|theme-files\.json)$">' ),
		$deny,
		array( '</FilesMatch>' )
	);
}

/** Server errors on the pages that must keep working ('' when all answer below 500). */
function sh_core_hardening_probe(): string {
	$urls = array(
		home_url( '/' ),
		SH_CORE_URL . 'assets/field-rows.css?ver=' . rawurlencode( SH_CORE_VERSION ),
		rest_url(),
	);
	foreach ( $urls as $u ) {
		$r = wp_remote_get(
			$u,
			array(
				'timeout'     => 20,
				'redirection' => 0,
				'sslverify'   => false,
			)
		);
		$code = is_wp_error( $r ) ? 0 : (int) wp_remote_retrieve_response_code( $r );
		if ( is_wp_error( $r ) || $code >= 500 ) {
			return $u . ' → ' . ( is_wp_error( $r ) ? $r->get_error_message() : $code );
		}
	}
	return '';
}

function sh_core_hardening_state(): array {
	$file = get_home_path() . '.htaccess';
	$body = file_exists( $file ) ? (string) file_get_contents( $file ) : ''; // phpcs:ignore WordPress.WP.AlternativeFunctions
	return array(
		'file'     => $file,
		'writable' => file_exists( $file ) ? wp_is_writable( $file ) : wp_is_writable( dirname( $file ) ),
		'active'   => false !== strpos( $body, '# BEGIN SEO House Hardening' ) && false !== strpos( $body, 'Options -Indexes' ),
	);
}

add_action(
	'rest_api_init',
	static function () {
		register_rest_route(
			'seohouse/v1',
			'/hardening',
			array(
				array(
					'methods'             => 'GET',
					'permission_callback' => static fn() => current_user_can( 'manage_options' ),
					'callback'            => static fn() => rest_ensure_response( sh_core_hardening_state() ),
				),
				array(
					'methods'             => 'POST',
					'permission_callback' => static fn() => current_user_can( 'manage_options' ),
					'callback'            => static function ( WP_REST_Request $req ) {
						require_once ABSPATH . 'wp-admin/includes/misc.php';
						require_once ABSPATH . 'wp-admin/includes/file.php';
						$on    = false !== $req->get_param( 'enable' ) && 'false' !== (string) $req->get_param( 'enable' );
						$state = sh_core_hardening_state();
						if ( ! $state['writable'] ) {
							return new WP_Error( 'sh_htaccess', '.htaccess غير قابل للكتابة.', array( 'status' => 409 ) );
						}
						if ( ! insert_with_markers( $state['file'], 'SEO House Hardening', $on ? sh_core_hardening_rules() : array() ) ) {
							return new WP_Error( 'sh_htaccess', 'تعذرت الكتابة في .htaccess.', array( 'status' => 500 ) );
						}
						$problem = $on ? sh_core_hardening_probe() : '';
						if ( '' !== $problem ) {
							insert_with_markers( $state['file'], 'SEO House Hardening', array() );
						}
						return rest_ensure_response( sh_core_hardening_state() + array( 'rolled_back' => '' !== $problem, 'problem' => $problem ) );
					},
				),
			)
		);
	}
);
