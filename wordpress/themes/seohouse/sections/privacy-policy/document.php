<?php
/**
 * Section "Document" — SEO House - Privacy Policy.
 * Maintained by hand: rendered by parts/legal-document.php (2.7.1) — the «الأقسام» field, else the
 * page's editor content; saved numbers shown once; editors see where the text goes when both are empty.
 * @sh-manual
 *
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
get_template_part( 'parts/legal-document', null, array( 'f' => $args['f'] ?? array(), 'anchor' => 'p', 'numbers' => false, 'toc_style' => 'card' ) );
