<?php
// Test copy of the main site: its two custom types, as on seohouse.agency (/results/…, /sectors/…).
add_action( 'init', function () {
	register_post_type( 'case_study', array( 'label' => 'نتائج الأعمال', 'labels' => array( 'name' => 'نتائج الأعمال', 'singular_name' => 'نتيجة' ), 'public' => true, 'has_archive' => true, 'rewrite' => array( 'slug' => 'results', 'with_front' => false ), 'supports' => array( 'title', 'editor', 'excerpt' ) ) );
	register_post_type( 'sector', array( 'label' => 'القطاعات', 'labels' => array( 'name' => 'القطاعات', 'singular_name' => 'قطاع' ), 'public' => true, 'has_archive' => true, 'rewrite' => array( 'slug' => 'sectors', 'with_front' => false ), 'supports' => array( 'title', 'editor', 'excerpt' ) ) );
} );
