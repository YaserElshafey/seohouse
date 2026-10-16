<?php
// php -S router for the local test site: static files as-is, everything else through WordPress.
$p = urldecode( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) );
$f = $_SERVER['DOCUMENT_ROOT'] . $p;
if ( $p !== '/' && is_file( $f ) && ! str_ends_with( $f, '.php' ) ) { return false; }
if ( str_ends_with( $p, '.php' ) && is_file( $f ) ) { return false; }
$_SERVER['SCRIPT_NAME'] = '/index.php';
require $_SERVER['DOCUMENT_ROOT'] . '/index.php';
