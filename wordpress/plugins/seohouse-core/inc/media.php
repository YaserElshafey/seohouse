<?php
/**
 * Images: JPEG/PNG uploads (editor uploads and the content importer) are stored as WebP,
 * and generated sizes are WebP too. The original is kept only when WebP is not smaller
 * or the server cannot write WebP. Filter "sh_convert_webp" → false disables it.
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

function sh_webp_enabled(): bool {
	return (bool) apply_filters( 'sh_convert_webp', true ) && wp_image_editor_supports( array( 'mime_type' => 'image/webp' ) );
}

add_filter(
	'image_editor_output_format',
	static function ( $formats ) {
		if ( sh_webp_enabled() ) {
			$formats['image/jpeg'] = 'image/webp';
			$formats['image/png']  = 'image/webp';
		}
		return $formats;
	}
);

/**
 * @param array $upload { file, url, type }
 */
function sh_webp_convert_upload( array $upload ): array {
	if ( empty( $upload['file'] ) || ! in_array( $upload['type'] ?? '', array( 'image/jpeg', 'image/png' ), true ) || ! sh_webp_enabled() ) {
		return $upload;
	}
	$src    = $upload['file'];
	$editor = wp_get_image_editor( $src );
	if ( is_wp_error( $editor ) ) {
		return $upload;
	}
	$editor->set_quality( (int) apply_filters( 'sh_webp_quality', 82 ) );
	$dest  = preg_replace( '/\.(jpe?g|png)$/i', '', $src );
	$dest  = wp_unique_filename( dirname( $src ), basename( $dest ) . '.webp' );
	$dest  = trailingslashit( dirname( $src ) ) . $dest;
	$saved = $editor->save( $dest, 'image/webp' );
	if ( is_wp_error( $saved ) || empty( $saved['path'] ) || ! file_exists( $saved['path'] ) ) {
		return $upload;
	}
	if ( filesize( $saved['path'] ) >= filesize( $src ) ) {
		wp_delete_file( $saved['path'] );
		return $upload;
	}
	wp_delete_file( $src );
	$upload['file'] = $saved['path'];
	$upload['url']  = trailingslashit( dirname( $upload['url'] ) ) . basename( $saved['path'] );
	$upload['type'] = 'image/webp';
	return $upload;
}
add_filter( 'wp_handle_upload', 'sh_webp_convert_upload', 5 );
add_filter( 'wp_handle_sideload', 'sh_webp_convert_upload', 5 );
