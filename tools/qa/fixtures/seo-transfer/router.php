<?php
// Document root /srv/shwpseo: the main site at the root, the review copy in /new/ (like seohouse.agency and seohouse.agency/new/).
$root = '/srv/shwpseo';
$path = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$sh_wpdir   = (str_starts_with($path, '/new/') || $path === '/new') ? '/new' : '';
if (is_file($root . $path)) {
    if (str_ends_with($path, '.php')) { $_SERVER['SCRIPT_NAME'] = $path; $_SERVER['SCRIPT_FILENAME'] = $root . $path; chdir(dirname($root . $path)); require $root . $path; return true; }
    return false;
}
if (is_dir($root . $path) && is_file(rtrim($root . $path, '/') . '/index.php')) { $f = rtrim($path, '/') . '/index.php'; $_SERVER['SCRIPT_NAME'] = $f; $_SERVER['SCRIPT_FILENAME'] = $root . $f; chdir(dirname($root . $f)); require $root . $f; return true; }
$_SERVER['SCRIPT_NAME'] = $sh_wpdir . '/index.php'; $_SERVER['SCRIPT_FILENAME'] = $root . $sh_wpdir . '/index.php'; chdir($root . ($sh_wpdir ?: '/'));
require $root . $sh_wpdir . '/index.php';
