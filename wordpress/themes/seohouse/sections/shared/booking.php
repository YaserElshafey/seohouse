<?php
/**
 * Shared component: consultation request (design "Booking"), one step since 2.7.3.
 * The form posts the request to SEO House Core (stored as «طلب استشارة» + emailed to «بريد
 * استلام الطلبات»). After a successful save the form is replaced by the confirmation
 * «وصلنا طلبك، وسنتواصل معك لتحديد موعد الاستشارة». No booking calendar is loaded here.
 * Email and phone are separate and required; the phone may be local or international.
 *
 * @package SEOHouse
 * @var array $args { f: { service, eyebrow, title, text, points[] } }
 */

defined( 'ABSPATH' ) || exit;

$f        = $args['f'] ?? array();
$defaults = (array) sh_option( 'sh_booking', array() );
$eyebrow  = ! empty( $f['eyebrow'] ) ? $f['eyebrow'] : ( $defaults['eyebrow'] ?? '' );
$title    = ! empty( $f['title'] ) ? $f['title'] : ( $defaults['title'] ?? '' );
$text     = ! empty( $f['text'] ) ? $f['text'] : ( $defaults['text'] ?? '' );
$points   = ! empty( $f['points'] ) ? $f['points'] : ( $defaults['points'] ?? array() );
$services = ! empty( $defaults['services'] ) ? $defaults['services'] : array(
	array( 'key' => 'seo', 'label' => 'تحسين محركات البحث' ),
	array( 'key' => 'web', 'label' => 'تصميم وتطوير موقع' ),
	array( 'key' => 'stores', 'label' => 'تصميم متجر إلكتروني' ),
	array( 'key' => 'products', 'label' => 'إضافة المنتجات' ),
	array( 'key' => 'unsure', 'label' => 'لم أحدد بعد' ),
);
$picked   = (string) ( $f['service'] ?? 'seo' );
$status   = isset( $_GET['sh_lead'] ) ? sanitize_key( wp_unslash( $_GET['sh_lead'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification -- display only.
$input    = 'min-height: 46px; padding: 0px 14px; border: 1px solid var(--sh-line); border-radius: 10px; font: inherit; font-weight: 400; background: rgb(255, 255, 255); color: var(--sh-ink);';
$page_id  = (int) get_queried_object_id();
$success  = trim( (string) ( $defaults['success'] ?? '' ) );
$success  = '' !== $success ? $success : __( 'وصلنا طلبك، وسنتواصل معك لتحديد موعد الاستشارة', 'seohouse' );
?>
<section id="booking" data-screen-label="Booking" style="position: relative; overflow: hidden; background: var(--sh-bg); color: var(--sh-ink); scroll-margin-top: 96px; border-top: 1px solid rgba(40, 84, 232, 0.16);">
	<div aria-hidden="true" style="position: absolute; inset: 0px; background: radial-gradient(40% 70% at 100% 0%, rgba(40, 84, 232, 0.22), transparent 70%), radial-gradient(30% 50% at 0% 100%, rgba(40, 84, 232, 0.08), transparent 70%); pointer-events: none;"></div>
	<div data-bk-grid style="position: relative; max-width: 1180px; margin: 0px auto; padding: clamp(34px, 4.4vw, 60px) 20px; display: grid; gap: 24px 48px; align-items: center;">
		<div>
			<?php if ( $eyebrow ) : ?>
			<div style="font-size: 13.5px; font-weight: 600; color: var(--sh-link);"><?php echo esc_html( $eyebrow ); ?></div>
			<?php endif; ?>
			<?php if ( $title ) : ?>
			<h2 data-sec-h style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: clamp(24px, 2.4vw, 33px); line-height: 1.4; margin: 10px 0px 0px;"><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>
			<?php if ( $text ) : ?>
			<p style="font-size: 16.5px; line-height: 1.85; color: var(--sh-ink); margin: 12px 0px 0px; max-width: 30em; text-wrap: pretty;"><?php echo esc_html( $text ); ?></p>
			<?php endif; ?>
			<?php if ( $points ) : ?>
			<ul style="list-style: none; margin: 18px 0px 0px; padding: 0px; display: flex; flex-direction: column; gap: 9px;">
				<?php foreach ( $points as $p ) : ?>
					<?php if ( ! empty( $p['text'] ) ) : ?>
				<li style="display: flex; gap: 10px; font-size: 15px; color: var(--sh-ink);"><span aria-hidden="true" style="color: var(--sh-link);">✓</span><?php echo esc_html( $p['text'] ); ?></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>
		<div data-sh-booking style="background: rgb(247, 249, 253); color: var(--sh-ink); border-radius: 20px; padding: clamp(20px, 2.4vw, 28px); box-shadow: rgba(0, 0, 0, 0.9) 0px 30px 60px -40px;">
			<form data-bk-form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate style="display: flex; flex-direction: column; gap: 14px;"<?php echo 'ok' === $status ? ' hidden' : ''; ?>>
				<input type="hidden" name="action" value="sh_lead">
				<input type="hidden" name="source" value="booking">
				<input type="hidden" name="page_id" value="<?php echo esc_attr( $page_id ); ?>">
				<input type="hidden" name="service" value="<?php echo esc_attr( $picked ); ?>" data-bk-service>
				<input type="hidden" name="ts" value="<?php echo esc_attr( (string) time() ); ?>">
				<div aria-hidden="true" style="position: absolute; inset-inline-start: -10000px; width: 1px; height: 1px; overflow: hidden;"><label><?php esc_html_e( 'اترك هذا الحقل فارغًا', 'seohouse' ); ?><input type="text" name="company_website" tabindex="-1" autocomplete="off" value=""></label></div>
				<fieldset data-bk-services style="border: 0px; margin: 0px; padding: 0px; min-width: 0px;">
					<legend style="width: 100%; text-align: center; font-size: 14px; font-weight: 600; margin-bottom: 8px; padding: 0px;"><?php esc_html_e( 'الخدمة المطلوبة', 'seohouse' ); ?></legend>
					<div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 8px;">
						<?php
						foreach ( $services as $s ) :
							$on = $s['key'] === $picked;
							?>
						<button type="button" data-bk-pick="<?php echo esc_attr( $s['key'] ); ?>" aria-pressed="<?php echo $on ? 'true' : 'false'; ?>" style="min-height: 40px; padding: 0px 14px; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; transition: background 0.2s, border-color 0.2s;"><?php echo esc_html( $s['label'] ); ?></button>
						<?php endforeach; ?>
					</div>
				</fieldset>
				<div data-bk-row style="display: grid; gap: 12px;">
					<label style="display: flex; flex-direction: column; gap: 6px; font-size: 14px; font-weight: 600;"><?php esc_html_e( 'الاسم', 'seohouse' ); ?><input name="name" autocomplete="name" required maxlength="120" style="<?php echo esc_attr( $input ); ?>"></label>
					<label style="display: flex; flex-direction: column; gap: 6px; font-size: 14px; font-weight: 600;"><?php esc_html_e( 'البريد الإلكتروني', 'seohouse' ); ?><input name="email" type="email" dir="ltr" required maxlength="160" autocomplete="email" inputmode="email" style="<?php echo esc_attr( $input ); ?> text-align: start;"></label>
				</div>
				<div data-bk-row style="display: grid; gap: 12px;">
					<label style="display: flex; flex-direction: column; gap: 6px; font-size: 14px; font-weight: 600;"><?php esc_html_e( 'رقم الهاتف', 'seohouse' ); ?><input name="phone" type="tel" dir="ltr" required maxlength="24" autocomplete="tel" inputmode="tel" style="<?php echo esc_attr( $input ); ?> text-align: start;"></label>
					<label style="display: flex; flex-direction: column; gap: 6px; font-size: 14px; font-weight: 600;"><span><?php esc_html_e( 'رابط الموقع', 'seohouse' ); ?> <span style="font-weight: 400; color: var(--sh-text); font-size: 13px;"><?php esc_html_e( '(إن وجد)', 'seohouse' ); ?></span></span><input name="site" type="url" dir="ltr" placeholder="https://" maxlength="300" style="<?php echo esc_attr( $input ); ?> text-align: start;"></label>
				</div>
				<p role="alert" data-bk-error style="margin: 0px; font-size: 14px; color: #B42318;"<?php echo 'error' === $status ? '' : ' hidden'; ?>><?php echo 'error' === $status ? esc_html__( 'تعذر إرسال الطلب. راجع البيانات وحاول مرة أخرى.', 'seohouse' ) : ''; ?></p>
				<button type="submit" class="sh-hv-cta" style="min-height: 50px; border: 0px; border-radius: 12px; background: var(--sh-blue); color: rgb(255, 255, 255); font: inherit; font-weight: 700; font-size: 16px; cursor: pointer; transition: background 0.2s;"><?php esc_html_e( 'إرسال الطلب', 'seohouse' ); ?></button>
			</form>

			<div data-bk-step2 role="status" tabindex="-1"<?php echo 'ok' === $status ? '' : ' hidden'; ?> style="border: 1.5px dashed rgba(40, 84, 232, 0.45); border-radius: 14px; padding: 26px 22px; text-align: center; background: rgba(40, 84, 232, 0.04);">
				<div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 18px; line-height: 1.7;"><?php echo esc_html( $success ); ?></div>
			</div>
		</div>
	</div>
</section>
