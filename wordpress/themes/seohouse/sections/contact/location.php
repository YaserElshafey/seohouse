<?php
/**
 * Section «موقعنا» — contact page (2.7.7).
 * Address and hours come from «إعدادات سيو هاوس ← بيانات الشركة». The map and the «الاتجاهات» button appear
 * only once their links are saved in this section; until then the address is shown alone (no empty space).
 * The map URL is accepted only as a Google Maps embed link (see sh_map_embed_src()); the field's content
 * is never printed as code.
 * @sh-manual
 *
 * @package SEOHouse
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f       = $args['f'] ?? array();
$company = sh_company_contact();
$map     = sh_map_embed_src( (string) ( $f['map_embed'] ?? '' ) );
$dir_url = sh_map_directions_url( (string) ( $f['directions_url'] ?? '' ) );
$title   = '' !== trim( (string) ( $f['title'] ?? '' ) ) ? (string) $f['title'] : __( 'موقعنا', 'seohouse' );
$label   = '' !== trim( (string) ( $f['directions_label'] ?? '' ) ) ? (string) $f['directions_label'] : __( 'الاتجاهات', 'seohouse' );
if ( '' === $company['address'] && '' === $map ) {
	return;
}
$bad_map = '' === $map && '' !== trim( (string) ( $f['map_embed'] ?? '' ) );
$bad_dir = '' === $dir_url && '' !== trim( (string) ( $f['directions_url'] ?? '' ) );
?>
<section data-screen-label="Location" class="sh-ct-loc<?php echo $map ? '' : ' sh-ct-loc--nomap'; ?>" aria-labelledby="sh-ct-loc-title">
	<div class="sh-ct-loc__inner">
		<div class="sh-ct-loc__info">
			<h2 id="sh-ct-loc-title" class="sh-ct-loc__title"><?php echo esc_html( $title ); ?></h2>
			<?php if ( '' !== $company['address'] ) : ?>
			<p class="sh-ct-loc__row"><span class="sh-ct-contact__icon" aria-hidden="true"><?php echo sh_contact_icon( 'address' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span><span><?php echo nl2br( esc_html( $company['address'] ) ); ?></span></p>
			<?php endif; ?>
			<?php if ( $dir_url ) : ?>
			<a class="sh-ct-loc__btn sh-hv-cta" href="<?php echo esc_url( $dir_url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $label ); ?> <span aria-hidden="true">↗</span><span class="screen-reader-text"><?php esc_html_e( '(يفتح في نافذة جديدة)', 'seohouse' ); ?></span></a>
			<?php endif; ?>
			<?php if ( ( $bad_map || $bad_dir ) && current_user_can( 'edit_pages' ) ) : ?>
			<p class="sh-admin-hint" role="note"><?php esc_html_e( 'يظهر هذا التنبيه للمحررين فقط: رابط الخريطة أو الاتجاهات غير مقبول. استخدم رابط «تضمين خريطة» من خرائط Google (يبدأ بـ https://www.google.com/maps/embed) ورابط مشاركة من خرائط Google للاتجاهات.', 'seohouse' ); ?></p>
			<?php endif; ?>
		</div>
		<?php if ( $map ) : ?>
		<div class="sh-ct-loc__map">
			<iframe src="<?php echo esc_url( $map ); ?>" title="<?php echo esc_attr( sprintf( /* translators: 1: company, 2: address */ __( 'خريطة موقع %1$s: %2$s', 'seohouse' ), sh_site_name(), preg_replace( '/\s+/u', ' ', $company['address'] ) ) ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
		</div>
		<?php endif; ?>
	</div>
</section>
