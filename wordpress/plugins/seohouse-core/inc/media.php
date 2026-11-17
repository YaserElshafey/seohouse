<?php
/**
 * Images: JPEG/PNG uploads (editor uploads and the content importer) are stored as WebP,
 * and generated sizes are WebP too. The original is kept only when WebP is not smaller
 * or the server cannot write WebP. Filter "sh_convert_webp" → false disables it.
 * 2.7.3: the original is deleted only after the WebP is read back with the same dimensions;
 * transparent images get a checkerboard behind them in the admin media views (a white logo on
 * WordPress's light-grey tiles looked like an empty thumbnail), and the attachment edit screen has
 * a «فحص الصورة» box that reads the stored files (exists, type, size, transparency, white share).
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
	// 2.7.3: keep the original unless the WebP is a readable image of the same size
	$src_size = wp_getimagesize( $src );
	$new_size = wp_getimagesize( $saved['path'] );
	if ( ! $new_size || ! $src_size || (int) $new_size[0] !== (int) $src_size[0] || (int) $new_size[1] !== (int) $src_size[1] || 'image/webp' !== ( $new_size['mime'] ?? '' ) ) {
		wp_delete_file( $saved['path'] );
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

/* ------------------------------------------------------------------ admin: visible transparent images */

function sh_media_transparent_css(): void {
		$checker = 'background-color:#8f98a3;background-image:conic-gradient(#b9c0c8 25%,#8f98a3 0 50%,#b9c0c8 0 75%,#8f98a3 0);background-size:16px 16px;';
		$types   = array( 'png', 'webp', 'svg+xml', 'gif', 'avif' );
		$sel     = array();
		foreach ( $types as $t ) {
			$sel[] = '.attachment-preview.subtype-' . str_replace( '+', '\\+', $t ) . ' .thumbnail';
		}
		echo '<style id="sh-media-transparent">' . implode( ',', $sel ) . ',.media-modal .attachment-details .thumbnail-image img[src$=".png"],.media-modal .attachment-details .thumbnail-image img[src$=".webp"],.media-modal .attachment-details .thumbnail-image img[src$=".svg"],.attachment-media-view .details-image[src$=".png"],.attachment-media-view .details-image[src$=".webp"],.attachment-media-view .details-image[src$=".svg"],#customize-control-site_icon .thumbnail img,#customize-control-site_icon .app-icon-preview,#customize-control-site_icon .site-icon-preview .browser-icon-preview,.site-icon-preview .app-icon-preview,.wp_attachment_image img.thumbnail,.crop-content img{' . $checker . '}</style>'; // phpcs:ignore WordPress.Security.EscapeOutput -- static CSS
}
add_action( 'admin_head', 'sh_media_transparent_css' );
add_action( 'customize_controls_print_styles', 'sh_media_transparent_css' );

/* ------------------------------------------------------------------ admin: «فحص الصورة» on the attachment edit screen */

/**
 * Facts about a stored image file: exists, bytes, detected type, dimensions, and (for a raster
 * image GD can read) the share of transparent pixels and of white among the visible ones.
 *
 * @return array<string,mixed>
 */
function sh_media_file_facts( string $path ): array {
	$out = array( 'exists' => file_exists( $path ) );
	if ( ! $out['exists'] ) {
		return $out;
	}
	$out['bytes'] = (int) filesize( $path );
	$out['mime']  = function_exists( 'mime_content_type' ) ? (string) mime_content_type( $path ) : '';
	$size         = wp_getimagesize( $path );
	$out['w']     = $size ? (int) $size[0] : 0;
	$out['h']     = $size ? (int) $size[1] : 0;
	if ( ! $size || ! function_exists( 'imagecreatefromstring' ) || $out['bytes'] > 8 * MB_IN_BYTES ) {
		return $out;
	}
	$im = @imagecreatefromstring( (string) file_get_contents( $path ) ); // phpcs:ignore WordPress.PHP.NoSilencedErrors, WordPress.WP.AlternativeFunctions
	if ( ! $im ) {
		$out['readable'] = false;
		return $out;
	}
	$out['readable'] = true;
	$w               = imagesx( $im );
	$h               = imagesy( $im );
	$step            = max( 1, (int) floor( max( $w, $h ) / 64 ) );
	$all             = 0;
	$clear           = 0;
	$white           = 0;
	for ( $y = 0; $y < $h; $y += $step ) {
		for ( $x = 0; $x < $w; $x += $step ) {
			$c = imagecolorsforindex( $im, imagecolorat( $im, $x, $y ) );
			++$all;
			if ( $c['alpha'] >= 120 ) {
				++$clear;
			} elseif ( $c['red'] > 235 && $c['green'] > 235 && $c['blue'] > 235 ) {
				++$white;
			}
		}
	}
	imagedestroy( $im );
	$out['transparent'] = $all ? round( 100 * $clear / $all ) : 0;
	$out['white']       = ( $all - $clear ) ? round( 100 * $white / ( $all - $clear ) ) : 0;
	return $out;
}

add_action(
	'add_meta_boxes_attachment',
	static function ( $post ) {
		if ( ! wp_attachment_is_image( $post ) ) {
			return;
		}
		add_meta_box(
			'sh_media_check',
			__( 'فحص الصورة (سيو هاوس)', 'seohouse-core' ),
			static function ( $post ) {
				$file = (string) get_attached_file( $post->ID );
				$meta = wp_get_attachment_metadata( $post->ID );
				$dir  = dirname( $file );
				$rows = array( __( 'الأصل', 'seohouse-core' ) => array( $file, (string) wp_get_attachment_url( $post->ID ) ) );
				if ( ! empty( $meta['original_image'] ) ) {
					$rows[ __( 'الأصل قبل التصغير', 'seohouse-core' ) ] = array( $dir . '/' . $meta['original_image'], (string) wp_get_original_image_url( $post->ID ) );
				}
				foreach ( (array) ( $meta['sizes'] ?? array() ) as $name => $sz ) {
					$rows[ $name ] = array( $dir . '/' . $sz['file'], (string) wp_get_attachment_image_url( $post->ID, $name ) );
				}
				echo '<p>' . esc_html__( 'نوع المرفق المسجّل:', 'seohouse-core' ) . ' <code>' . esc_html( (string) get_post_mime_type( $post ) ) . '</code> — ' . esc_html__( 'محرر الصور على الخادم يدعم WebP:', 'seohouse-core' ) . ' <strong>' . ( wp_image_editor_supports( array( 'mime_type' => 'image/webp' ) ) ? esc_html__( 'نعم', 'seohouse-core' ) : esc_html__( 'لا', 'seohouse-core' ) ) . '</strong></p>';
				echo '<table class="widefat striped"><thead><tr><th>' . esc_html__( 'الملف', 'seohouse-core' ) . '</th><th>' . esc_html__( 'موجود', 'seohouse-core' ) . '</th><th>' . esc_html__( 'النوع الفعلي', 'seohouse-core' ) . '</th><th>' . esc_html__( 'الأبعاد', 'seohouse-core' ) . '</th><th>' . esc_html__( 'الحجم', 'seohouse-core' ) . '</th><th>' . esc_html__( 'شفاف / أبيض', 'seohouse-core' ) . '</th></tr></thead><tbody>';
				$first = null;
				foreach ( $rows as $label => $r ) {
					$f = sh_media_file_facts( $r[0] );
					if ( null === $first ) {
						$first = $f;
					}
					$tw = isset( $f['transparent'] ) ? $f['transparent'] . '% / ' . $f['white'] . '%' : ( isset( $f['readable'] ) && ! $f['readable'] ? __( 'تعذّرت القراءة', 'seohouse-core' ) : '—' );
					echo '<tr><td><a href="' . esc_url( $r[1] ) . '" target="_blank" rel="noopener">' . esc_html( $label ) . '</a><br><small dir="ltr">' . esc_html( basename( $r[0] ) ) . '</small></td><td>' . ( $f['exists'] ? '✓' : '<strong style="color:#b32d2e">✗</strong>' ) . '</td><td dir="ltr">' . esc_html( $f['mime'] ?? '' ) . '</td><td dir="ltr">' . esc_html( ! empty( $f['w'] ) ? $f['w'] . '×' . $f['h'] : '—' ) . '</td><td dir="ltr">' . esc_html( isset( $f['bytes'] ) ? size_format( $f['bytes'] ) : '—' ) . '</td><td>' . esc_html( $tw ) . '</td></tr>';
				}
				echo '</tbody></table>';
				if ( $first && ! empty( $first['exists'] ) && isset( $first['transparent'] ) && $first['transparent'] >= 30 && $first['white'] >= 80 ) {
					echo '<p><strong>' . esc_html__( 'الملف سليم: صورة بيضاء على خلفية شفافة.', 'seohouse-core' ) . '</strong> ' . esc_html__( 'تبدو فارغة على خلفية فاتحة؛ تظهر الآن فوق المربعات الرمادية في المكتبة، وتظهر في الموقع على الخلفيات الداكنة.', 'seohouse-core' ) . '</p>';
				} elseif ( $first && empty( $first['exists'] ) ) {
					echo '<p><strong style="color:#b32d2e">' . esc_html__( 'ملف الأصل غير موجود على الخادم في المسار المسجّل.', 'seohouse-core' ) . '</strong></p>';
				}
				echo '<p class="description">' . esc_html__( 'للفحص فقط: لا يغيّر الملفات ولا البيانات.', 'seohouse-core' ) . '</p>';
			},
			'attachment',
			'normal',
			'default'
		);
	}
);
