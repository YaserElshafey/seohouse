<?php
/**
 * Shared component: consultation request (design "Booking", two steps).
 * Step 1 posts the request to SEO House Core (stored + emailed). Step 2 shows the
 * connected scheduler («إعدادات سيو هاوس ← الاستشارة ← أداة الحجز»), or — while none is
 * connected — a receipt titled «تم إرسال الطلب». No fake calendar; «تم تأكيد الموعد» is shown
 * only after Core confirmed the booking with the scheduler (see site.js and Core leads.php).
 * 2.7.2: separate required email and phone (with country code) instead of «الجوال أو البريد».
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
$step2    = (array) ( $defaults['step2'] ?? array() );
$status   = isset( $_GET['sh_lead'] ) ? sanitize_key( wp_unslash( $_GET['sh_lead'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification -- display only.
$input    = 'min-height: 46px; padding: 0px 14px; border: 1px solid var(--sh-line); border-radius: 10px; font: inherit; font-weight: 400; background: rgb(255, 255, 255); color: var(--sh-ink);';
$page_id  = (int) get_queried_object_id();
$sched    = sh_booking_config();
$has_cal  = '' !== $sched['url'];
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
			<div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; font-size: 13px; color: var(--sh-text);">
				<span data-bk-label style="font-weight: 600; color: var(--sh-link);" data-l1="<?php esc_attr_e( 'بياناتك والخدمة', 'seohouse' ); ?>" data-l2="<?php echo esc_attr( $has_cal ? __( 'اختيار الموعد', 'seohouse' ) : __( 'تم إرسال الطلب', 'seohouse' ) ); ?>"><?php esc_html_e( 'بياناتك والخدمة', 'seohouse' ); ?></span><span data-bk-counter style="white-space: nowrap;" data-c1="<?php esc_attr_e( 'الخطوة 1 من 2', 'seohouse' ); ?>" data-c2="<?php esc_attr_e( 'الخطوة 2 من 2', 'seohouse' ); ?>"><?php esc_html_e( 'الخطوة 1 من 2', 'seohouse' ); ?></span>
			</div>
			<div aria-hidden="true" style="margin-top: 10px; height: 3px; border-radius: 3px; background: var(--sh-line);"><div data-bk-bar style="height: 3px; border-radius: 3px; background: var(--sh-blue); width: 50%; transition: width 0.3s;"></div></div>

			<form data-bk-form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate style="margin-top: 18px; display: flex; flex-direction: column; gap: 14px;"<?php echo 'ok' === $status ? ' hidden' : ''; ?>>
				<input type="hidden" name="action" value="sh_lead">
				<input type="hidden" name="source" value="booking">
				<input type="hidden" name="page_id" value="<?php echo esc_attr( $page_id ); ?>">
				<input type="hidden" name="service" value="<?php echo esc_attr( $picked ); ?>" data-bk-service>
				<input type="hidden" name="ts" value="<?php echo esc_attr( (string) time() ); ?>">
				<div aria-hidden="true" style="position: absolute; inset-inline-start: -10000px; width: 1px; height: 1px; overflow: hidden;"><label><?php esc_html_e( 'اترك هذا الحقل فارغًا', 'seohouse' ); ?><input type="text" name="company_website" tabindex="-1" autocomplete="off" value=""></label></div>
				<fieldset style="border: 0px; margin: 0px; padding: 0px;">
					<legend style="font-size: 14px; font-weight: 600; margin-bottom: 8px;"><?php esc_html_e( 'الخدمة المطلوبة', 'seohouse' ); ?></legend>
					<div style="display: flex; flex-wrap: wrap; gap: 8px;">
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
					<label style="display: flex; flex-direction: column; gap: 6px; font-size: 14px; font-weight: 600;"><?php esc_html_e( 'رقم الهاتف', 'seohouse' ); ?><input name="phone" type="tel" dir="ltr" required maxlength="24" autocomplete="tel" inputmode="tel" aria-describedby="bk-phone-hint-<?php echo esc_attr( $page_id ); ?>" style="<?php echo esc_attr( $input ); ?> text-align: start;"><span id="bk-phone-hint-<?php echo esc_attr( $page_id ); ?>" style="font-weight: 400; font-size: 12.5px; color: var(--sh-text);"><?php esc_html_e( 'مع رمز الدولة، يبدأ بـ + أو 00', 'seohouse' ); ?></span></label>
					<label style="display: flex; flex-direction: column; gap: 6px; font-size: 14px; font-weight: 600;"><span><?php esc_html_e( 'رابط الموقع', 'seohouse' ); ?> <span style="font-weight: 400; color: var(--sh-text); font-size: 13px;"><?php esc_html_e( '(إن وجد)', 'seohouse' ); ?></span></span><input name="site" type="url" dir="ltr" placeholder="https://" maxlength="300" style="<?php echo esc_attr( $input ); ?> text-align: start;"></label>
				</div>
				<p role="alert" data-bk-error style="margin: 0px; font-size: 14px; color: #B42318;"<?php echo 'error' === $status ? '' : ' hidden'; ?>><?php echo 'error' === $status ? esc_html__( 'تعذر إرسال الطلب. راجع البيانات وحاول مرة أخرى.', 'seohouse' ) : ''; ?></p>
				<button type="submit" class="sh-hv-cta" style="min-height: 50px; border: 0px; border-radius: 12px; background: var(--sh-blue); color: rgb(255, 255, 255); font: inherit; font-weight: 700; font-size: 16px; cursor: pointer; transition: background 0.2s;"><?php echo esc_html( $has_cal ? __( 'متابعة لاختيار الموعد', 'seohouse' ) : __( 'إرسال الطلب', 'seohouse' ) ); ?></button>
			</form>

			<div data-bk-step2 style="margin-top: 18px;"<?php echo 'ok' === $status ? '' : ' hidden'; ?>>
				<?php if ( $has_cal ) : ?>
				<div data-bk-sched hidden>
					<p data-bk-sched-head style="margin: 0px; font-size: 14.5px; line-height: 1.8; color: var(--sh-ink);"><strong><?php esc_html_e( 'تم حفظ طلبك.', 'seohouse' ); ?></strong> <?php echo esc_html( sprintf( /* translators: %d: minutes */ __( 'اختر يومًا ووقتًا متاحًا لمكالمة مدتها %d دقيقة.', 'seohouse' ), (int) $sched['duration'] ) ); ?></p>
					<p data-bk-tz style="margin: 4px 0px 10px; font-size: 13px; color: var(--sh-text);" data-tpl="<?php esc_attr_e( 'الأوقات معروضة بتوقيت جهازك: %s — يمكن تغييره من داخل التقويم.', 'seohouse' ); ?>"></p>
					<div role="status" data-bk-confirmed hidden tabindex="-1" style="margin-bottom: 12px; border-radius: 12px; padding: 14px 16px; background: rgba(0, 117, 42, 0.08); box-shadow: rgba(0, 117, 42, 0.35) 0px 0px 0px 1px inset; color: var(--sh-ink); font-size: 14.5px; line-height: 1.8;"><strong><?php esc_html_e( 'تم تأكيد الموعد', 'seohouse' ); ?></strong> <span data-bk-when></span></div>
					<p role="status" data-bk-pending hidden style="margin: 0px 0px 12px; font-size: 13.5px; color: var(--sh-text);"><?php esc_html_e( 'سجّلنا اختيارك، وسيصلك تأكيد الموعد من أداة الحجز على بريدك.', 'seohouse' ); ?></p>
				</div>
				<?php endif; ?>
				<div data-bk-embed hidden></div>
				<?php if ( $has_cal ) : ?>
				<p data-bk-open hidden style="margin: 10px 0px 0px; font-size: 13.5px;"><a href="<?php echo esc_url( $sched['url'] ); ?>" target="_blank" rel="noopener" style="color: var(--sh-link);"><?php esc_html_e( 'لا يظهر التقويم؟ افتح صفحة الحجز في نافذة جديدة', 'seohouse' ); ?></a></p>
				<?php endif; ?>
				<div data-bk-receipt style="border: 1.5px dashed rgba(40, 84, 232, 0.45); border-radius: 14px; padding: 22px; text-align: center; background: rgba(40, 84, 232, 0.04);">
					<div style="font-family: Alexandria, sans-serif; font-weight: 700; font-size: 17px;"><?php echo esc_html( $step2['title'] ?? __( 'وصلنا طلبك', 'seohouse' ) ); ?></div>
					<p style="font-size: 14.5px; color: var(--sh-text); margin: 8px 0px 0px; line-height: 1.8;"><?php echo esc_html( $step2['text'] ?? __( 'سجّلنا طلب الاستشارة. نراجع موقعك ونتواصل معك لتحديد موعد المكالمة.', 'seohouse' ) ); ?></p>
				</div>
				<button type="button" data-bk-back style="margin-top: 14px; min-height: 44px; padding: 0px 16px; border: 1px solid var(--sh-line); border-radius: 10px; background: transparent; font: inherit; font-size: 14.5px; cursor: pointer; color: var(--sh-ink);"><?php esc_html_e( 'تعديل البيانات', 'seohouse' ); ?></button>
			</div>
		</div>
	</div>
</section>
