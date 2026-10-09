<?php
require_once ABSPATH . WPINC . '/class-wp-image-editor.php'; require_once ABSPATH . WPINC . '/class-wp-image-editor-gd.php';
// Simulates a server whose WebP writer produces a broken file: the upload must keep the original.
class SH_Test_Bad_Editor extends WP_Image_Editor_GD {
	public function save( $destfilename = null, $mime_type = null ) {
		if ( 'image/webp' === $mime_type ) { file_put_contents( $destfilename, 'not an image' ); return array( 'path' => $destfilename, 'file' => basename( $destfilename ), 'mime-type' => 'image/webp' ); }
		return parent::save( $destfilename, $mime_type );
	}
}
add_filter( 'wp_image_editors', fn() => array( 'SH_Test_Bad_Editor' ) );
$up  = wp_upload_dir();
$src = $up['path'] . '/convfail-test.png';
copy( '/home/claude/wptest-fixture/media-in2/SEO-House-Icon-blue.png', $src );
$res = sh_webp_convert_upload( array( 'file' => $src, 'url' => $up['url'] . '/convfail-test.png', 'type' => 'image/png' ) );
echo 'returned type=', $res['type'], ' file=', basename( $res['file'] ), ' exists=', file_exists( $res['file'] ) ? 'yes' : 'NO', ' broken webp left=', file_exists( $up['path'] . '/convfail-test.webp' ) ? 'YES' : 'no', "\n";
unlink( $src );
