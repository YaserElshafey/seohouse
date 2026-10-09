<?php
/**
 * Document head and site header (design: shared header + mega menu + mobile drawer).
 *
 * @package SEOHouse
 */

defined( 'ABSPATH' ) || exit;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="sh-skip" href="#main"><?php esc_html_e( 'تخطَّ إلى المحتوى', 'seohouse' ); ?></a>
<div dir="rtl" class="sh-root" style="background: <?php echo is_front_page() ? 'var(--sh-bg)' : 'var(--sh-surface)'; /* design v5: grey canvas on the front page only */ ?>; color: var(--sh-ink); font-size: 16px; line-height: 1.7; min-height: 100vh;">
<?php
get_template_part( 'parts/site-header' );
get_template_part( 'parts/drawer' );
