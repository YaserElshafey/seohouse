<?php
/**
 * Section «بيانات التواصل والخريطة» — contact page (2.7.7, rearranged in 2.7.8).
 * The «بيانات التواصل» card (phone, email, address, hours — «إعدادات سيو هاوس ← بيانات الشركة») next to
 * the Google map on wide screens, above it on small ones. Without a saved map link the card is shown
 * alone, centred. The map URL is accepted only as a Google Maps embed link (sh_map_embed_src()); the
 * field's content is never printed as code. No external directions button (Google's own controls stay
 * inside the map).
 * @sh-manual
 *
 * @package SEOHouse
 * @var array $args { f: layout values }
 */
defined( 'ABSPATH' ) || exit;
$f       = $args['f'] ?? array();
$company = sh_company_contact();
$map     = sh_map_embed_src( (string) ( $f['map_embed'] ?? '' ) );
$rows    = array_filter(
	array(
		'phone'   => array( __( 'الهاتف', 'seohouse' ), $company['phone'], sh_tel_href( $company['phone'] ) ),
		'email'   => array( __( 'البريد الإلكتروني', 'seohouse' ), $company['email'], $company['email'] ? 'mailto:' . $company['email'] : '' ),
		'address' => array( __( 'العنوان', 'seohouse' ), $company['address'], '' ),
		'hours'   => array( __( 'مواعيد العمل', 'seohouse' ), $company['hours'], '' ),
	),
	static fn( $r ) => '' !== $r[1]
);
if ( ! $rows && '' === $map ) {
	return;
}
// card title: this section's field, else the one saved in «المسار» by 2.7.7, else the default
$title = trim( (string) ( $f['contact_title'] ?? '' ) );
if ( '' === $title ) {
	foreach ( sh_sections_rows() as $row ) {
		if ( 'flow' === ( $row['acf_fc_layout'] ?? '' ) ) {
			$title = trim( (string) ( $row['contact_title'] ?? '' ) );
		}
	}
}
$title   = '' !== $title ? $title : __( 'بيانات التواصل', 'seohouse' );
$bad_map = '' === $map && '' !== trim( (string) ( $f['map_embed'] ?? '' ) );
?>
<section data-screen-label="Location" class="sh-ct-loc<?php echo $map ? '' : ' sh-ct-loc--nomap'; ?>" aria-labelledby="sh-ct-loc-title">
	<div class="sh-ct-loc__inner">
		<?php if ( $rows ) : ?>
		<div class="sh-ct-contact">
			<h2 id="sh-ct-loc-title" class="sh-ct-contact__title"><?php echo esc_html( $title ); ?></h2>
			<ul class="sh-ct-contact__list">
				<?php foreach ( $rows as $kind => $r ) : ?>
				<li class="sh-ct-contact__row">
					<span class="sh-ct-contact__icon" aria-hidden="true"><?php echo sh_contact_icon( $kind ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
					<span class="sh-ct-contact__text">
						<span class="sh-ct-contact__label"><?php echo esc_html( $r[0] ); ?></span>
						<?php if ( $r[2] ) : ?>
						<a class="sh-ct-contact__value sh-ct-contact__value--link" href="<?php echo esc_url( $r[2], array( 'tel', 'mailto' ) ); ?>" dir="ltr"><?php echo esc_html( $r[1] ); ?></a>
						<?php elseif ( 'phone' === $kind || 'email' === $kind ) : ?>
						<span class="sh-ct-contact__value" dir="ltr"><?php echo esc_html( $r[1] ); ?></span>
						<?php else : ?>
						<span class="sh-ct-contact__value"><?php echo nl2br( esc_html( $r[1] ) ); ?></span>
						<?php endif; ?>
					</span>
				</li>
				<?php endforeach; ?>
			</ul>
			<?php if ( $bad_map && current_user_can( 'edit_pages' ) ) : ?>
			<p class="sh-admin-hint" role="note"><?php esc_html_e( 'يظهر هذا التنبيه للمحررين فقط: رابط الخريطة غير مقبول. استخدم رابط «تضمين خريطة» من خرائط Google (يبدأ بـ https://www.google.com/maps/embed).', 'seohouse' ); ?></p>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<?php if ( $map ) : ?>
		<div class="sh-ct-loc__map">
			<iframe src="<?php echo esc_url( $map ); ?>" title="<?php echo esc_attr( sprintf( /* translators: 1: company, 2: address */ __( 'خريطة موقع %1$s: %2$s', 'seohouse' ), sh_site_name(), preg_replace( '/\s+/u', ' ', $company['address'] ) ) ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
		</div>
		<?php endif; ?>
	</div>
</section>
