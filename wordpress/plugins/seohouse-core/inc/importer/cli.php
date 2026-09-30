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
	 * [--update=<targets>]
	 * : Re-apply pack values to existing records that were not edited since the import.
	 *   Comma list of groups (pages,team,cases,posts,menus,options), keys (page:seo-technical) or "all".
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
				'update'  => isset( $assoc['update'] ) ? explode( ',', $assoc['update'] ) : array(),
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
}

WP_CLI::add_command( 'seohouse', 'SH_CLI_Command' );
