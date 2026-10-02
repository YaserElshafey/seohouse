<?php
/**
 * «تهيئة الموقع» (إعدادات سيو هاوس ← تهيئة الموقع): fills the site with the approved design content.
 *
 * The content pack ships inside SEO House Core, so no upload is needed:
 *   1. «معاينة التهيئة» — dry run, nothing is written;
 *   2. «تهيئة الموقع»   — runs the import in short steps (images, records, page content, menus,
 *                          settings) with a progress bar, so it never hits a server time limit;
 *   3. a check of the result (front page, menus, images, settings, drafts) is shown afterwards.
 * Running it again never duplicates content and never overwrites what was edited in the admin.
 * A newer content pack can be uploaded (optional). Administrators only; every action has a nonce.
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

const SH_SETUP_SLUG = 'seohouse-content-setup';

add_action(
	'admin_menu',
	static function () {
		add_submenu_page( 'seohouse-settings', __( 'تهيئة الموقع', 'seohouse-core' ), __( 'تهيئة الموقع', 'seohouse-core' ), 'manage_options', SH_SETUP_SLUG, 'sh_import_admin_page' );
	},
	30
);

/** Right after activating Core on a site that was never initialised, open «تهيئة الموقع» once. */
add_action(
	'admin_init',
	static function () {
		if ( ! get_option( 'sh_setup_redirect' ) || wp_doing_ajax() || ! current_user_can( 'manage_options' ) || isset( $_GET['activate-multi'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			return;
		}
		delete_option( 'sh_setup_redirect' );
		if ( ! get_option( 'sh_content_last_import' ) ) {
			wp_safe_redirect( admin_url( 'admin.php?page=' . SH_SETUP_SLUG ) );
			exit;
		}
	}
);

/** Until the site has been initialised, every admin screen shows where to do it. */
add_action(
	'admin_notices',
	static function () {
		if ( ! current_user_can( 'manage_options' ) || get_option( 'sh_content_last_import' ) || ( isset( $_GET['page'] ) && SH_SETUP_SLUG === $_GET['page'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			return;
		}
		$url = admin_url( 'admin.php?page=' . SH_SETUP_SLUG );
		echo '<div class="notice notice-warning"><p style="font-size:14px"><strong>' . esc_html__( 'الموقع لم يُهيّأ بعد بمحتوى التصميم المعتمد.', 'seohouse-core' ) . '</strong> ';
		echo esc_html__( 'الصفحات والقوائم والصور والإعدادات جاهزة داخل SEO House Core، وتُنشأ بخطوة واحدة.', 'seohouse-core' ) . '</p>';
		echo '<p><a class="button button-primary" href="' . esc_url( $url ) . '">' . esc_html__( 'تهيئة الموقع الآن', 'seohouse-core' ) . '</a></p></div>';
	}
);

/** Upload limit of this server in bytes. */
function sh_setup_upload_limit(): int {
	return (int) wp_max_upload_size();
}

/** Optional: a newer content pack uploaded as ZIP replaces nothing until it is imported. */
function sh_setup_handle_upload(): string {
	if ( empty( $_FILES['sh_pack'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- verified by the caller.
		return __( 'لم يصل أي ملف. إن كان حجم الملف أكبر من حد الرفع في الخادم فلن يصل؛ استخدم الحزمة المضمّنة.', 'seohouse-core' );
	}
	$f = $_FILES['sh_pack']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification
	if ( UPLOAD_ERR_OK !== (int) $f['error'] ) {
		$size = size_format( sh_setup_upload_limit() );
		$msg  = array(
			UPLOAD_ERR_INI_SIZE  => sprintf( __( 'الملف أكبر من حد الرفع في الخادم (%s).', 'seohouse-core' ), $size ),
			UPLOAD_ERR_FORM_SIZE => sprintf( __( 'الملف أكبر من حد الرفع في الخادم (%s).', 'seohouse-core' ), $size ),
			UPLOAD_ERR_PARTIAL   => __( 'وصل الملف ناقصًا. أعد المحاولة.', 'seohouse-core' ),
			UPLOAD_ERR_NO_FILE   => __( 'اختر ملف ZIP أولًا.', 'seohouse-core' ),
		);
		return $msg[ (int) $f['error'] ] ?? __( 'تعذر رفع الملف.', 'seohouse-core' );
	}
	if ( ! str_ends_with( strtolower( (string) $f['name'] ), '.zip' ) ) {
		return __( 'ارفع ملف ZIP لحزمة المحتوى (seohouse-content.zip).', 'seohouse-core' );
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	if ( ! WP_Filesystem() ) {
		return __( 'لا يمكن الكتابة في مجلد الرفع على هذا الخادم.', 'seohouse-core' );
	}
	global $wp_filesystem;
	$up  = wp_upload_dir( null, false );
	$dir = trailingslashit( $up['basedir'] ) . 'seohouse-content';
	$tmp = $dir . '-tmp';
	$wp_filesystem->delete( $tmp, true );
	$res = unzip_file( $f['tmp_name'], $tmp );
	if ( is_wp_error( $res ) ) {
		return $res->get_error_message();
	}
	$root = file_exists( $tmp . '/manifest.json' ) ? $tmp : dirname( (string) ( glob( $tmp . '/*/manifest.json' )[0] ?? '' ) );
	if ( ! $root || ! file_exists( $root . '/manifest.json' ) ) {
		$wp_filesystem->delete( $tmp, true );
		return __( 'الملف لا يحتوي حزمة محتوى سيو هاوس (manifest.json غير موجود).', 'seohouse-core' );
	}
	$wp_filesystem->delete( $dir, true );
	$wp_filesystem->move( $root, $dir, true );
	$wp_filesystem->delete( $tmp, true );
	return '';
}

/* ====================================================================== AJAX steps */

function sh_setup_ajax_guard(): void {
	check_ajax_referer( 'sh_setup', 'nonce' );
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => __( 'غير مسموح.', 'seohouse-core' ) ), 403 );
	}
	if ( function_exists( 'set_time_limit' ) ) {
		@set_time_limit( 120 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
	}
	wp_raise_memory_limit( 'admin' );
}

/** Log lines worth showing (everything except "skipped"). */
function sh_setup_log( SH_Importer $imp ): array {
	return array_values( array_filter( $imp->log, static fn( $l ) => 'skipped' !== $l[0] ) );
}

add_action(
	'wp_ajax_sh_setup_preview',
	static function () {
		sh_setup_ajax_guard();
		$imp = new SH_Importer( SH_Importer::default_dir(), array( 'dry_run' => true ) );
		$ok  = $imp->run();
		wp_send_json_success( array( 'ok' => $ok, 'counts' => $imp->counts, 'log' => sh_setup_log( $imp ) ) );
	}
);

add_action(
	'wp_ajax_sh_setup_plan',
	static function () {
		sh_setup_ajax_guard();
		$imp = new SH_Importer( SH_Importer::default_dir() );
		wp_send_json_success( array( 'steps' => $imp->plan() ) );
	}
);

add_action(
	'wp_ajax_sh_setup_step',
	static function () {
		sh_setup_ajax_guard();
		$imp   = new SH_Importer( SH_Importer::default_dir() );
		$steps = $imp->plan();
		$i     = isset( $_POST['step'] ) ? absint( $_POST['step'] ) : 0;
		if ( ! isset( $steps[ $i ] ) ) {
			wp_send_json_error( array( 'message' => 'step' ) );
		}
		if ( get_transient( 'sh_setup_lock' ) ) {
			wp_send_json_error( array( 'message' => __( 'التهيئة تعمل في نافذة أخرى. انتظر حتى تنتهي.', 'seohouse-core' ) ) );
		}
		set_transient( 'sh_setup_lock', 1, 120 );
		$ok = $imp->run_step( $steps[ $i ] );
		delete_transient( 'sh_setup_lock' );
		wp_send_json_success( array( 'ok' => $ok, 'counts' => $imp->counts, 'log' => sh_setup_log( $imp ) ) );
	}
);

add_action(
	'wp_ajax_sh_setup_permalinks',
	static function () {
		sh_setup_ajax_guard();
		sh_core_apply_permalinks();
		wp_send_json_success( array( 'checks' => sh_setup_checks() ) );
	}
);

add_action(
	'wp_ajax_sh_setup_check',
	static function () {
		sh_setup_ajax_guard();
		wp_send_json_success( array( 'checks' => sh_setup_checks() ) );
	}
);

/**
 * What a site owner looks at after the import.
 *
 * @return array<int,array{0:bool,1:string,2:string,3?:string}> ok, label, detail, fix action
 */
function sh_setup_checks(): array {
	$imp    = new SH_Importer( SH_Importer::default_dir() );
	$out    = array();
	$front  = (int) get_option( 'page_on_front' );
	$blog   = (int) get_option( 'page_for_posts' );
	$out[]  = array( 'page' === get_option( 'show_on_front' ) && $front && 'publish' === get_post_status( $front ), 'الصفحة الرئيسية', $front ? get_the_title( $front ) . ' — ' . get_permalink( $front ) : 'غير محددة' );
	$out[]  = array( $blog && 'publish' === get_post_status( $blog ), 'صفحة المقالات', $blog ? get_permalink( $blog ) : 'غير محددة' );
	$pages  = (int) ( new WP_Query( array( 'post_type' => 'page', 'post_status' => 'publish', 'posts_per_page' => 1, 'fields' => 'ids', 'meta_key' => SH_Importer::META_KEY, 'meta_compare' => 'EXISTS' ) ) )->found_posts; // phpcs:ignore WordPress.DB.SlowDBQuery
	$out[]  = array( $pages >= 30, 'صفحات التصميم المنشورة', (string) $pages );
	$bad    = 0;
	foreach ( $imp->verify() as $r ) {
		$bad += (int) $r[2];
	}
	$out[]  = array( 0 === $bad, 'مطابقة نصوص وصور وروابط الأقسام مع التصميم', $bad ? $bad . ' اختلافًا (قد تكون تعديلاتك)' : 'مطابقة' );
	$locs   = get_nav_menu_locations();
	foreach ( array( 'primary' => 'القائمة الرئيسية', 'footer' => 'قائمة الفوتر', 'legal' => 'روابط أسفل الفوتر' ) as $loc => $label ) {
		$n     = ! empty( $locs[ $loc ] ) ? count( (array) wp_get_nav_menu_items( $locs[ $loc ] ) ) : 0;
		$out[] = array( $n > 0, $label, $n . ' عنصر' );
	}
	$media  = (int) ( new WP_Query( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => 1, 'fields' => 'ids', 'meta_key' => SH_Importer::META_KEY, 'meta_compare' => 'EXISTS' ) ) )->found_posts; // phpcs:ignore WordPress.DB.SlowDBQuery
	$need   = count( $imp->pack_assets() );
	$out[]  = array( $media >= $need, 'الصور في مكتبة الوسائط', $media . ' / ' . $need );
	$logos  = (array) sh_core_option( 'sh_client_logos', array() );
	$out[]  = array( (bool) sh_core_option( 'sh_company_name', '' ) && count( $logos ) > 0, 'إعدادات سيو هاوس', 'اسم الشركة: ' . sh_core_option( 'sh_company_name', '—' ) . ' · شعارات العملاء: ' . count( $logos ) );
	$struct = (string) get_option( 'permalink_structure' );
	$okperm = '/blog/%postname%/' === $struct && 'blog/category' === get_option( 'category_base' );
	$out[]  = array( $okperm, 'الروابط الدائمة', $okperm ? '/blog/%postname%/ — المقالات على /blog/… والتصنيفات على /blog/category/…' : sprintf( 'الحالية %s — روابط المقالات المنشورة سابقًا (/blog/…) لن تعمل حتى تُطبَّق بنية المشروع.', $struct ? $struct : 'الافتراضية' ), $okperm ? '' : 'permalinks' );
	$drafts = array();
	foreach ( array( 'page' => array( 'privacy-policy', 'terms' ), 'post' => array( 'fix-404-not-found', 'how-to-build-backlinks-correctly', 'why-is-my-website-not-showing-in-search-engines' ) ) as $type => $slugs ) {
		foreach ( $slugs as $slug ) {
			$p        = get_posts( array( 'post_type' => $type, 'name' => $slug, 'post_status' => 'any', 'posts_per_page' => 1 ) );
			$drafts[] = $p ? $p[0]->post_status : 'missing';
		}
	}
	$out[]  = array( ! in_array( 'missing', $drafts, true ), 'الصفحات القانونية والمقالات غير المعتمدة', implode( '، ', array_map( static fn( $s ) => 'draft' === $s ? 'مسودة' : $s, $drafts ) ) );
	return $out;
}

/* ====================================================================== screen */

function sh_import_admin_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$notice = '';
	$error  = '';
	if ( isset( $_POST['sh_setup_upload'] ) && check_admin_referer( 'sh_setup_upload' ) ) {
		$error  = sh_setup_handle_upload();
		$notice = $error ? '' : __( 'رُفعت الحزمة. اضغط «معاينة التهيئة» ثم «تهيئة الموقع».', 'seohouse-core' );
	}
	$dir      = SH_Importer::default_dir();
	$manifest = SH_Importer::manifest_of( $dir );
	$last     = get_option( 'sh_content_last_import' );
	$bundled  = str_starts_with( $dir, SH_CORE_DIR );

	echo '<div class="wrap" id="sh-setup"><h1>' . esc_html__( 'تهيئة الموقع بمحتوى التصميم المعتمد', 'seohouse-core' ) . '</h1>';
	if ( $error ) {
		echo '<div class="notice notice-error"><p>' . esc_html( $error ) . '</p></div>';
	}
	if ( $notice ) {
		echo '<div class="notice notice-success"><p>' . esc_html( $notice ) . '</p></div>';
	}
	if ( ! sh_core_acf_ready() ) {
		echo '<div class="notice notice-error"><p>' . esc_html__( 'فعّل إضافة Advanced Custom Fields أولًا (الإصدار المجاني يكفي)، ثم عُد إلى هذه الصفحة.', 'seohouse-core' ) . '</p></div></div>';
		return;
	}
	if ( ! $manifest ) {
		echo '<div class="notice notice-error"><p>' . esc_html__( 'حزمة المحتوى غير موجودة. أعد تثبيت SEO House Core من الحزمة الكاملة.', 'seohouse-core' ) . '</p></div></div>';
		return;
	}

	echo '<p style="font-size:14px;max-width:60em">' . esc_html__( 'تنشئ التهيئة كل صفحات التصميم وأقسامها، وفريق العمل، ودراسات الحالة، والمقالات، والصور، والقوائم، وإعدادات سيو هاوس، وتضبط الصفحة الرئيسية والروابط الدائمة. لا تحتاج إلى تعبئة أي حقل يدويًا.', 'seohouse-core' ) . '</p>';
	echo '<table class="widefat" style="max-width:60em"><tbody>';
	echo '<tr><th style="width:14em">' . esc_html__( 'حزمة المحتوى', 'seohouse-core' ) . '</th><td>' . esc_html( ( $manifest['version'] ?? '' ) . ' — ' . ( $bundled ? __( 'مضمّنة في SEO House Core', 'seohouse-core' ) : __( 'مرفوعة', 'seohouse-core' ) ) ) . ' <code dir="ltr">' . esc_html( $manifest['designFingerprint'] ?? '' ) . '</code></td></tr>';
	echo '<tr><th>' . esc_html__( 'الحالة', 'seohouse-core' ) . '</th><td>' . ( $last ? esc_html( sprintf( __( 'هُيّئ في %1$s (حزمة %2$s)', 'seohouse-core' ), wp_date( 'Y-m-d H:i', $last['time'] ), $last['version'] ?? '' ) ) : '<strong>' . esc_html__( 'لم يُهيّأ بعد', 'seohouse-core' ) . '</strong>' ) . '</td></tr>';
	echo '</tbody></table>';

	echo '<h2>' . esc_html__( 'الخطوة 1: معاينة (تشغيل تجريبي)', 'seohouse-core' ) . '</h2>';
	echo '<p>' . esc_html__( 'يعرض ما سيُنشأ أو يُحدَّث، دون أن يكتب أي شيء.', 'seohouse-core' ) . '</p>';
	echo '<p><button type="button" class="button button-large" id="sh-preview">' . esc_html__( 'معاينة التهيئة', 'seohouse-core' ) . '</button></p>';
	echo '<div id="sh-preview-out"></div>';

	echo '<h2>' . esc_html__( 'الخطوة 2: التهيئة', 'seohouse-core' ) . '</h2>';
	echo '<p>' . esc_html__( 'تعمل على مراحل قصيرة (الصور، ثم الصفحات، ثم القوائم والإعدادات). لا تغلق الصفحة حتى يكتمل الشريط. إن انقطعت، اضغط الزر مرة أخرى: تكمل الناقص دون تكرار، ولا تكتب فوق ما عدّلته من لوحة التحكم. الصفحتان القانونيتان والمقالات غير المعتمدة تُنشأ مسودات.', 'seohouse-core' ) . '</p>';
	echo '<p><button type="button" class="button button-primary button-hero" id="sh-run">' . esc_html( $last ? __( 'إعادة التهيئة / استكمال الناقص', 'seohouse-core' ) : __( 'تهيئة الموقع', 'seohouse-core' ) ) . '</button></p>';
	echo '<div id="sh-progress" hidden style="max-width:60em"><div style="background:#dcdcde;border-radius:4px;height:14px;overflow:hidden"><div id="sh-bar" style="background:#2271b1;height:100%;width:0;transition:width .3s"></div></div><p id="sh-step"></p></div>';
	echo '<div id="sh-run-out"></div>';

	echo '<h2>' . esc_html__( 'الخطوة 3: فحص النتيجة', 'seohouse-core' ) . '</h2>';
	echo '<p><button type="button" class="button" id="sh-check">' . esc_html__( 'فحص الموقع', 'seohouse-core' ) . '</button> <span>' . esc_html__( 'يعمل تلقائيًا بعد التهيئة.', 'seohouse-core' ) . '</span></p>';
	echo '<div id="sh-check-out"></div>';
	echo '<p id="sh-links"' . ( $last ? '' : ' hidden' ) . '><a class="button" href="' . esc_url( home_url( '/' ) ) . '" target="_blank">' . esc_html__( 'عرض الموقع', 'seohouse-core' ) . '</a> <a class="button" href="' . esc_url( admin_url( 'edit.php?post_type=page' ) ) . '">' . esc_html__( 'الصفحات', 'seohouse-core' ) . '</a> <a class="button" href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'القوائم', 'seohouse-core' ) . '</a> <a class="button" href="' . esc_url( admin_url( 'admin.php?page=seohouse-settings' ) ) . '">' . esc_html__( 'إعدادات سيو هاوس', 'seohouse-core' ) . '</a></p>';

	echo '<hr><details><summary style="cursor:pointer">' . esc_html__( 'خيارات متقدمة: رفع حزمة محتوى أحدث (اختياري)', 'seohouse-core' ) . '</summary>';
	echo '<p>' . esc_html( sprintf( __( 'لا تحتاج إليها عادة: الحزمة مضمّنة في SEO House Core. تُستخدم فقط إذا سُلّمت حزمة أحدث. حد الرفع في هذا الخادم: %s.', 'seohouse-core' ), size_format( sh_setup_upload_limit() ) ) ) . '</p>';
	echo '<form method="post" enctype="multipart/form-data">';
	wp_nonce_field( 'sh_setup_upload' );
	echo '<input type="file" name="sh_pack" accept=".zip"> <button class="button" name="sh_setup_upload" value="1">' . esc_html__( 'رفع الحزمة', 'seohouse-core' ) . '</button></form></details>';
	echo '</div>';

	$cfg = array(
		'ajax'  => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'sh_setup' ),
	);
	?>
<script>
(function () {
	var C = <?php echo wp_json_encode( $cfg ); ?>;
	var L = { created: 'أُنشئ', updated: 'حُدّث', protected: 'محمي (عُدّل من لوحة التحكم)', failed: 'فشل', info: 'معلومة' };
	function $(id) { return document.getElementById(id); }
	function post(action, data) {
		var fd = new FormData(); fd.append('action', action); fd.append('nonce', C.nonce);
		for (var k in (data || {})) fd.append(k, data[k]);
		return fetch(C.ajax, { method: 'POST', body: fd, credentials: 'same-origin' }).then(function (r) {
			return r.text().then(function (t) {
				try { var j = JSON.parse(t); } catch (e) { throw new Error('استجابة غير متوقعة من الخادم (' + r.status + '). ' + t.replace(/<[^>]+>/g, ' ').slice(0, 200)); }
				if (!j.success) throw new Error((j.data && j.data.message) || 'تعذر التنفيذ');
				return j.data;
			});
		});
	}
	function esc(s) { var d = document.createElement('div'); d.textContent = s == null ? '' : String(s); return d.innerHTML; }
	function counts(c) { return '<p><strong>أُنشئ:</strong> ' + c.created + ' &nbsp; <strong>حُدّث:</strong> ' + c.updated + ' &nbsp; <strong>بلا تغيير:</strong> ' + c.skipped + ' &nbsp; <strong>محمي:</strong> ' + c.protected + ' &nbsp; <strong style="color:' + (c.failed ? '#b32d2e' : 'inherit') + '">فشل:</strong> ' + c.failed + '</p>'; }
	function table(log) {
		var rows = log.filter(function (l) { return l[0] !== 'info' || l[1] === 'source'; }).map(function (l) { return '<tr><td>' + esc(L[l[0]] || l[0]) + '</td><td dir="ltr">' + esc(l[1]) + '</td><td>' + esc(l[2]) + '</td></tr>'; });
		return rows.length ? '<details' + (log.some(function (l) { return l[0] === 'failed'; }) ? ' open' : '') + '><summary style="cursor:pointer">السجل (' + rows.length + ')</summary><table class="widefat striped" style="max-width:60em"><tbody>' + rows.join('') + '</tbody></table></details>' : '';
	}
	function sum(a, b) { var o = {}; ['created', 'updated', 'skipped', 'protected', 'failed'].forEach(function (k) { o[k] = (a[k] || 0) + (b[k] || 0); }); return o; }
	function busy(on) { ['sh-preview', 'sh-run', 'sh-check'].forEach(function (id) { $(id).disabled = on; }); }

	$('sh-preview').addEventListener('click', function () {
		busy(true); $('sh-preview-out').innerHTML = '<p>جارٍ المعاينة…</p>';
		post('sh_setup_preview').then(function (d) {
			$('sh-preview-out').innerHTML = '<div class="notice notice-info inline"><p><strong>تشغيل تجريبي — لم يُكتب شيء.</strong></p>' + counts(d.counts) + '</div>' + table(d.log);
		}).catch(function (e) { $('sh-preview-out').innerHTML = '<div class="notice notice-error inline"><p>' + esc(e.message) + '</p></div>'; }).finally(function () { busy(false); });
	});

	function render(checks) {
		$('sh-check-out').innerHTML = '<table class="widefat striped" style="max-width:60em"><tbody>' + checks.map(function (c) {
			var fix = c[3] === 'permalinks' ? ' <button type="button" class="button button-small" data-fix="permalinks">تطبيق بنية روابط المشروع</button>' : '';
			return '<tr data-ok="' + (c[0] ? '1' : '0') + '"><td style="width:9em;font-weight:600;color:' + (c[0] ? '#00701a' : '#b32d2e') + '">' + (c[0] ? 'سليم' : 'يحتاج مراجعة') + '</td><td style="width:18em">' + esc(c[1]) + '</td><td>' + esc(c[2]) + fix + '</td></tr>';
		}).join('') + '</tbody></table>';
		$('sh-links').hidden = false;
		var b = document.querySelector('[data-fix="permalinks"]');
		if (b) b.addEventListener('click', function () {
			if (!window.confirm('تغيير بنية الروابط الدائمة إلى /blog/%postname%/؟ تتغير روابط المقالات الحالية إلى /blog/…')) return;
			b.disabled = true; post('sh_setup_permalinks').then(function (d) { render(d.checks); }).catch(function (e) { window.alert(e.message); b.disabled = false; });
		});
	}
	function check() {
		$('sh-check-out').innerHTML = '<p>جارٍ الفحص…</p>';
		return post('sh_setup_check').then(function (d) {
			render(d.checks);
		}).catch(function (e) { $('sh-check-out').innerHTML = '<div class="notice notice-error inline"><p>' + esc(e.message) + '</p></div>'; });
	}
	$('sh-check').addEventListener('click', function () { busy(true); check().finally(function () { busy(false); }); });

	function run() {
		busy(true); $('sh-progress').hidden = false; $('sh-run-out').innerHTML = ''; $('sh-bar').style.width = '2%';
		var total = { created: 0, updated: 0, skipped: 0, protected: 0, failed: 0 }, log = [];
		post('sh_setup_plan').then(function (d) {
			var steps = d.steps, i = 0;
			function next() {
				if (i >= steps.length) {
					$('sh-bar').style.width = '100%'; $('sh-step').textContent = 'اكتملت التهيئة.';
					$('sh-run-out').innerHTML = '<div class="notice notice-' + (total.failed ? 'warning' : 'success') + ' inline"><p><strong>' + (total.failed ? 'اكتملت التهيئة مع أخطاء — راجع السجل ثم اضغط الزر مرة أخرى.' : 'اكتملت التهيئة.') + '</strong></p>' + counts(total) + '</div>' + table(log);
					return check();
				}
				$('sh-step').textContent = 'المرحلة ' + (i + 1) + ' من ' + steps.length + ': ' + steps[i].label;
				return post('sh_setup_step', { step: i }).then(function (r) {
					total = sum(total, r.counts); log = log.concat(r.log.filter(function (l) { return !(l[0] === 'info' && l[1] === 'source' && log.length); }));
					i++; $('sh-bar').style.width = Math.round(i / steps.length * 100) + '%';
					return next();
				});
			}
			return next();
		}).catch(function (e) {
			$('sh-run-out').innerHTML = '<div class="notice notice-error inline"><p><strong>توقفت التهيئة:</strong> ' + esc(e.message) + '</p><p>ما تم إنشاؤه محفوظ. اضغط «تهيئة الموقع» مرة أخرى لإكمال الناقص.</p></div>' + table(log);
		}).finally(function () { busy(false); });
	}
	$('sh-run').addEventListener('click', function () {
		if (!window.confirm('بدء تهيئة الموقع بمحتوى التصميم المعتمد؟ لن يُكتب فوق أي تعديل أجريته من لوحة التحكم.')) return;
		run();
	});
})();
</script>
	<?php
}
