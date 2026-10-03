<?php
/**
 * WP-CLI: wp seohouse import | verify | status
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

class SH_CLI_Command {

	/**
	 * Set up the site from the design content pack.
	 *
	 * ## OPTIONS
	 *
	 * [--source=<dir>]
	 * : Content pack directory (default: SH_CONTENT_DIR or uploads/seohouse-content).
	 *
	 * [--dry-run]
	 * : Report what would happen without writing.
	 *
	 * [--only=<groups>]
	 * : Comma list: settings,pages,team,cases,posts,menus,options.
	 *
	 * [--update[=<targets>]]
	 * : Re-apply pack values to existing records that were not edited since the import.
	 *   Comma list of groups (pages,team,cases,posts,menus,options), keys (page:seo-technical) or "all" (default when no value is given).
	 *
	 * [--force]
	 * : Also overwrite records edited in the admin (only with --update).
	 *
	 * ## EXAMPLES
	 *
	 *     wp seohouse import --dry-run
	 *     wp seohouse import
	 *     wp seohouse import --only=pages --update=page:pricing
	 *
	 * @when after_wp_load
	 */
	public function import( $args, $assoc ) {
		$imp = new SH_Importer(
			$assoc['source'] ?? SH_Importer::default_dir(),
			array(
				'dry_run' => isset( $assoc['dry-run'] ),
				'only'    => isset( $assoc['only'] ) ? explode( ',', $assoc['only'] ) : array(),
				'update'  => isset( $assoc['update'] ) ? ( in_array( $assoc['update'], array( true, '', '1' ), true ) ? array( 'all' ) : explode( ',', (string) $assoc['update'] ) ) : array(), // bare --update = all
				'force'   => isset( $assoc['force'] ),
			)
		);
		$ok = $imp->run();
		$this->print_log( $imp );
		$ok ? WP_CLI::success( 'اكتمل التجهيز.' ) : WP_CLI::error( 'انتهى مع أخطاء (انظر failed).', false );
	}

	/**
	 * Compare design values in the pack with the values WordPress returns.
	 *
	 * [--source=<dir>]
	 * : Content pack directory.
	 *
	 * [--details]
	 * : Show sample mismatches.
	 *
	 * @when after_wp_load
	 */
	public function verify( $args, $assoc ) {
		$imp  = new SH_Importer( $assoc['source'] ?? SH_Importer::default_dir() );
		$rows = array();
		$bad  = 0;
		foreach ( $imp->verify() as $r ) {
			$rows[] = array( 'page' => $r[0], 'values' => $r[1], 'differences' => $r[2] );
			$bad   += $r[2];
			if ( isset( $assoc['details'] ) && $r[3] ) {
				foreach ( $r[3] as $path => $pair ) {
					WP_CLI::log( "  {$r[0]} :: {$path}\n    design: " . mb_substr( (string) $pair[0], 0, 120 ) . "\n    stored: " . mb_substr( (string) $pair[1], 0, 120 ) );
				}
			}
		}
		WP_CLI\Utils\format_items( 'table', $rows, array( 'page', 'values', 'differences' ) );
		$bad ? WP_CLI::warning( "فروق: {$bad} (قد تكون تعديلات مقصودة من المحرر)." ) : WP_CLI::success( 'القيم مطابقة للتصميم.' );
	}

	/**
	 * Show the last import and records created by the tool.
	 *
	 * @when after_wp_load
	 */
	public function status() {
		global $wpdb;
		$last = get_option( 'sh_content_last_import' );
		WP_CLI::log( $last ? 'آخر تجهيز: ' . wp_date( 'Y-m-d H:i', $last['time'] ) . ' — ' . $last['fingerprint'] : 'لم يُشغّل التجهيز بعد.' );
		$rows = $wpdb->get_results( $wpdb->prepare( "SELECT SUBSTRING_INDEX(meta_value, ':', 1) AS grp, COUNT(*) AS n FROM {$wpdb->postmeta} WHERE meta_key = %s GROUP BY grp", SH_Importer::META_KEY ), ARRAY_A ); // phpcs:ignore
		WP_CLI\Utils\format_items( 'table', $rows, array( 'grp', 'n' ) );
	}

	/**
	 * Checks every published URL of the previous site (content pack data/legacy-urls.json)
	 * against this installation: each must answer 200, or 301 to a page that answers 200.
	 * Run before launch; any failure means a published link would become a 404.
	 *
	 * ## OPTIONS
	 *
	 * [--source=<dir>]
	 * : Content pack folder (default: uploads/seohouse-content).
	 *
	 * [--base=<url>]
	 * : Site address to test (default: home_url()).
	 *
	 * [--format=<format>]
	 * : table, csv or json. Default: table.
	 *
	 * @subcommand launch-check
	 * @when after_wp_load
	 */
	public function launch_check( $args, $assoc ) {
		$dir  = $assoc['source'] ?? SH_Importer::default_dir();
		$file = trailingslashit( $dir ) . 'data/legacy-urls.json';
		if ( ! file_exists( $file ) ) {
			WP_CLI::error( 'لا يوجد ملف الروابط المنشورة: ' . $file );
		}
		$base = untrailingslashit( $assoc['base'] ?? home_url() );
		$rows = array();
		$fail = 0;
		foreach ( (array) json_decode( (string) file_get_contents( $file ), true ) as $u ) { // phpcs:ignore WordPress.WP.AlternativeFunctions
			$url  = $base . implode( '/', array_map( 'rawurlencode', explode( '/', $u['path'] ) ) );
			$res  = wp_remote_get( $url, array( 'redirection' => 0, 'timeout' => 30, 'sslverify' => false ) );
			$code = is_wp_error( $res ) ? 0 : (int) wp_remote_retrieve_response_code( $res );
			$to   = '';
			$ok   = 200 === $code;
			if ( in_array( $code, array( 301, 308 ), true ) ) {
				$to    = (string) wp_remote_retrieve_header( $res, 'location' );
				$final = wp_remote_get( $to, array( 'redirection' => 0, 'timeout' => 30, 'sslverify' => false ) );
				$ok    = ! is_wp_error( $final ) && 200 === (int) wp_remote_retrieve_response_code( $final );
				$to    = rawurldecode( (string) wp_parse_url( $to, PHP_URL_PATH ) );
			}
			$fail  += $ok ? 0 : 1;
			$rows[] = array(
				'path'     => $u['path'],
				'expected' => $u['resolution'] . ( ! empty( $u['object'] ) ? ' ' . $u['object'] : '' ) . ( ! empty( $u['target'] ) ? ' → ' . $u['target'] : '' ),
				'http'     => $code,
				'redirect' => $to,
				'result'   => $ok ? 'OK' : 'FAIL',
				'note'     => $ok ? '' : ( 'draft' === ( $u['status'] ?? '' ) ? 'مسودة بانتظار الاعتماد والنشر' : ( 'MISSING' === $u['resolution'] ? 'لا وجهة' : '' ) ),
			);
		}
		WP_CLI\Utils\format_items( $assoc['format'] ?? 'table', $rows, array( 'path', 'expected', 'http', 'redirect', 'result', 'note' ) );
		if ( $fail ) {
			WP_CLI::warning( sprintf( '%d من %d رابطًا منشورًا لن يعمل عند الإطلاق.', $fail, count( $rows ) ) );
			WP_CLI::halt( 1 );
		}
		WP_CLI::success( sprintf( 'كل الروابط المنشورة (%d) تعمل.', count( $rows ) ) );
	}

	/**
	 * Applies the approved search descriptions (content pack data/seo-proposals.json):
	 * pages → «وصف SEO» field, categories → category description. Empty fields only, unless --force.
	 * Run only after the proposals are approved.
	 *
	 * ## OPTIONS
	 *
	 * [--source=<dir>]
	 * : Content pack folder (default: uploads/seohouse-content).
	 *
	 * [--dry-run]
	 * : Show what would change.
	 *
	 * [--force]
	 * : Replace descriptions that are already filled.
	 *
	 * @subcommand apply-proposals
	 * @when after_wp_load
	 */
	public function apply_proposals( $args, $assoc ) {
		$file = trailingslashit( $assoc['source'] ?? SH_Importer::default_dir() ) . 'data/seo-proposals.json';
		$data = file_exists( $file ) ? json_decode( (string) file_get_contents( $file ), true ) : null; // phpcs:ignore WordPress.WP.AlternativeFunctions
		if ( ! is_array( $data ) ) {
			WP_CLI::error( 'لا يوجد ملف المقترحات: ' . $file );
		}
		$dry   = ! empty( $assoc['dry-run'] );
		$force = ! empty( $assoc['force'] );
		$seo   = json_decode( (string) file_get_contents( SH_CORE_DIR . 'acf-json/group_sh_seo.json' ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		$fkey  = '';
		foreach ( $seo['fields'] as $f ) {
			if ( 'sh_seo_description' === $f['name'] ) {
				$fkey = $f['key'];
			}
		}
		foreach ( (array) ( $data['pages'] ?? array() ) as $source => $text ) {
			$ids = get_posts( array( 'post_type' => 'any', 'post_status' => 'any', 'fields' => 'ids', 'posts_per_page' => 1, 'meta_key' => SH_Importer::META_KEY, 'meta_value' => $source ) ); // phpcs:ignore WordPress.DB.SlowDBQuery
			if ( ! $ids ) {
				WP_CLI::warning( "$source: الصفحة غير موجودة" );
				continue;
			}
			// with Rank Math its own description field is the one the editor sees
			$rm  = function_exists( 'sh_rankmath_active' ) && sh_rankmath_active();
			$now = $rm ? (string) get_post_meta( $ids[0], 'rank_math_description', true ) : (string) get_field( $fkey, $ids[0], false );
			if ( '' !== trim( $now ) && ! $force ) {
				WP_CLI::log( "skipped  $source — يوجد وصف: $now" );
				continue;
			}
			if ( ! $dry ) {
				if ( $rm ) {
					update_post_meta( $ids[0], 'rank_math_description', wp_slash( $text ) );
				} else {
					update_field( $fkey, $text, $ids[0] );
				}
			}
			WP_CLI::log( ( $dry ? 'would set ' : 'set      ' ) . "$source — $text" );
		}
		foreach ( (array) ( $data['categories'] ?? array() ) as $slug => $text ) {
			$term = get_term_by( 'slug', $slug, 'category' );
			if ( ! $term ) {
				WP_CLI::warning( "category:$slug غير موجود" );
				continue;
			}
			if ( '' !== trim( (string) $term->description ) && ! $force ) {
				WP_CLI::log( "skipped  category:$slug — يوجد وصف" );
				continue;
			}
			if ( ! $dry ) {
				wp_update_term( $term->term_id, 'category', array( 'description' => $text ) );
			}
			WP_CLI::log( ( $dry ? 'would set ' : 'set      ' ) . "category:$slug — $text" );
		}
		WP_CLI::success( $dry ? 'تجربة فقط؛ لم يُحفظ شيء.' : 'طُبّقت المقترحات المعتمدة.' );
	}

	private function print_log( SH_Importer $imp ): void {
		foreach ( $imp->log as $l ) {
			if ( 'skipped' === $l[0] && ! WP_CLI::get_config( 'debug' ) ) {
				continue;
			}
			WP_CLI::log( str_pad( $l[0], 10 ) . ' ' . $l[1] . ( $l[2] ? ' — ' . $l[2] : '' ) );
		}
		WP_CLI::log( '' );
		foreach ( $imp->counts as $k => $v ) {
			WP_CLI::log( str_pad( $k, 10 ) . ' ' . $v );
		}
	}

	/**
	 * Moves Core's search title, description, social image and noindex into Rank Math's own
	 * fields — only where those are empty. Values written in Rank Math are never changed.
	 *
	 * ## OPTIONS
	 *
	 * [--dry-run]
	 * : List what would be moved.
	 *
	 * @subcommand rankmath-migrate
	 */
	public function rankmath_migrate( $args, $assoc ) {
		if ( ! sh_rankmath_active() ) {
			WP_CLI::error( 'Rank Math غير مفعّلة.' );
		}
		$plan = sh_rankmath_plan();
		foreach ( $plan as $r ) {
			$v = is_array( $r['core'] ) ? implode( ',', $r['core'] ) : $r['core'];
			WP_CLI::log( sprintf( '%-5s %-10s %-20s %s — %s', $r['action'], $r['type'], $r['field'], $r['title'], $v ) );
		}
		$c = array_count_values( wp_list_pluck( $plan, 'action' ) );
		if ( ! empty( $assoc['dry-run'] ) ) {
			WP_CLI::success( sprintf( 'تجريبي: سيُنقل %d، مطابق %d، يبقى %d', $c['fill'] ?? 0, $c['same'] ?? 0, $c['keep'] ?? 0 ) );
			return;
		}
		WP_CLI::success( sprintf( 'نُقلت %d قيمة (مطابق %d، بقي %d كما هو).', sh_rankmath_apply( $plan ), $c['same'] ?? 0, $c['keep'] ?? 0 ) );
	}

	/**
	 * Copies every published article of the main WordPress site to this site (read-only on the
	 * main site). Matches by slug, backs up replaced drafts, never duplicates.
	 *
	 * ## OPTIONS
	 *
	 * [--dry-run]
	 * : List what would happen.
	 *
	 * [--source-config=<path>]
	 * : The main site's wp-config.php (default: the folder above this site).
	 *
	 * [--force]
	 * : Also overwrite articles edited here after an earlier migration.
	 *
	 * @subcommand migrate-posts
	 */
	public function migrate_posts( $args, $assoc ) {
		$m = new SH_Post_Migration( array( 'dry_run' => ! empty( $assoc['dry-run'] ), 'force' => ! empty( $assoc['force'] ), 'config' => $assoc['source-config'] ?? '' ) );
		if ( ! $m->connect() ) {
			WP_CLI::error( $m->error );
		}
		if ( empty( $assoc['dry-run'] ) && '/blog/%postname%/' !== get_option( 'permalink_structure' ) ) {
			sh_core_apply_permalinks();
			WP_CLI::log( 'permalinks → /blog/%postname%/' );
		}
		$ok = $m->run();
		foreach ( $m->log as $l ) {
			WP_CLI::log( sprintf( '%-10s %s — %s', $l[0], $l[1], $l[2] ) );
		}
		WP_CLI::log( wp_json_encode( $m->counts ) );
		$ok ? WP_CLI::success( empty( $assoc['dry-run'] ) ? 'اكتمل النقل.' : 'معاينة فقط.' ) : WP_CLI::error( 'انتهى مع أخطاء.' );
	}
}

WP_CLI::add_command( 'seohouse', 'SH_CLI_Command' );
