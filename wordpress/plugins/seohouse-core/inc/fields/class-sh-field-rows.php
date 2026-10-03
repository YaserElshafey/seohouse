<?php
/**
 * ACF field type "sh_rows" (قائمة عناصر): repeating items for ACF (free).
 *
 * Every item is a structured record — a post of the internal type `sh_row` — whose values are
 * ordinary ACF values saved with acf_update_value() against the sub-field definitions. The list
 * field itself stores the ordered record IDs. Editors add, remove, reorder (drag) and collapse
 * items inline on the owner's edit screen.
 *
 * Values returned to templates (get_field) have the same shape as an ACF repeater:
 * a list of rows keyed by sub-field name. The unformatted value (get_field( …, false )) is the
 * list of rows keyed by sub-field key, each with its record ID in `_row_id`.
 *
 * Works with ACF (free) 6.x; also with ACF PRO and Secure Custom Fields, which include
 * everything ACF free has.
 *
 * @package SEOHouseCore
 */

defined( 'ABSPATH' ) || exit;

class SH_Field_Rows extends acf_field {

	public function initialize() {
		$this->name     = 'sh_rows';
		$this->label    = __( 'قائمة عناصر (سيو هاوس)', 'seohouse-core' );
		$this->category = 'layout';
		$this->defaults = array(
			'sub_fields'   => array(),
			'min'          => 0,
			'max'          => 0,
			'layout'       => 'block',
			'button_label' => '',
			'collapsed'    => '',
		);
		// sub fields are registered as ACF fields of their own (validated, with type defaults)
		$this->add_field_filter( 'acf/prepare_field_for_export', array( $this, 'prepare_field_for_export' ) );
		$this->add_field_filter( 'acf/prepare_field_for_import', array( $this, 'prepare_field_for_import' ) );
	}

	/* ------------------------------------------------------------ definition */

	public function load_field( $field ) {
		$sub = acf_get_fields( $field );
		if ( $sub ) {
			$field['sub_fields'] = $sub;
		}
		return $field;
	}

	public function prepare_field_for_import( $field ) {
		if ( empty( $field['sub_fields'] ) ) {
			return $field;
		}
		$subs = acf_extract_var( $field, 'sub_fields' );
		foreach ( $subs as $i => $s ) {
			$subs[ $i ]['parent']     = $field['key'];
			$subs[ $i ]['menu_order'] = $i;
		}
		return array_merge( array( $field ), $subs );
	}

	public function prepare_field_for_export( $field ) {
		if ( ! empty( $field['sub_fields'] ) ) {
			$field['sub_fields'] = acf_prepare_fields_for_export( $field['sub_fields'] );
		}
		return $field;
	}

	public function render_field_settings( $field ) {
		acf_render_field_setting( $field, array( 'label' => __( 'أقل عدد', 'seohouse-core' ), 'type' => 'number', 'name' => 'min' ) );
		acf_render_field_setting( $field, array( 'label' => __( 'أكبر عدد', 'seohouse-core' ), 'type' => 'number', 'name' => 'max' ) );
		acf_render_field_setting( $field, array( 'label' => __( 'نص زر الإضافة', 'seohouse-core' ), 'type' => 'text', 'name' => 'button_label' ) );
	}

	/** Sub fields as stored on an item record (plain names). */
	private function subs( array $field ): array {
		$out = array();
		foreach ( (array) ( $field['sub_fields'] ?? array() ) as $s ) {
			$s['name'] = $s['_name'] ?? $s['name'];
			$out[]     = $s;
		}
		return $out;
	}

	/* ------------------------------------------------------------ records */

	/** Owner key used on item records ("123", "options", "term_5"…). */
	private static function owner_key( $post_id ): string {
		return (string) $post_id;
	}

	private function record_ids( $value, $post_id, array $field ): array {
		$ids = array();
		foreach ( (array) $value as $id ) {
			$id = (int) $id;
			if ( $id > 0 && 'sh_row' === get_post_type( $id ) ) {
				$ids[] = $id;
			}
		}
		return $ids;
	}

	/**
	 * Read the stored list of row IDs before replacing or deleting it.
	 * acf_get_metadata_by_field() is absent from some supported ACF PRO releases;
	 * acf_get_metadata() is available in those releases and uses the field's
	 * already-resolved name (including its group prefix, when nested).
	 */
	private function stored_row_ids( $post_id, array $field ): array {
		$value = function_exists( 'acf_get_metadata_by_field' )
			? acf_get_metadata_by_field( $post_id, $field )
			: acf_get_metadata( $post_id, $field['name'] );
		return $this->record_ids( $value, $post_id, $field );
	}

	private function create_record( $post_id, array $field, int $order ): int {
		$id = wp_insert_post(
			array(
				'post_type'   => 'sh_row',
				'post_status' => 'publish',
				'post_title'  => $field['key'],
				'post_parent' => is_numeric( $post_id ) ? (int) $post_id : 0,
				'menu_order'  => $order,
				'meta_input'  => array(
					'_sh_owner' => self::owner_key( $post_id ),
					'_sh_field' => $field['key'],
				),
			),
			true
		);
		return is_wp_error( $id ) ? 0 : (int) $id;
	}

	/** Deletes an item record and the records of its own nested lists. */
	public static function delete_record( int $id ): void {
		if ( 'sh_row' !== get_post_type( $id ) ) {
			return;
		}
		foreach ( get_posts( array( 'post_type' => 'sh_row', 'post_status' => 'any', 'posts_per_page' => -1, 'fields' => 'ids', 'meta_key' => '_sh_owner', 'meta_value' => (string) $id ) ) as $child ) { // phpcs:ignore WordPress.DB.SlowDBQuery
			self::delete_record( (int) $child );
		}
		wp_delete_post( $id, true );
	}

	/* ------------------------------------------------------------ values */

	/**
	 * DB value: ordered record IDs → rows keyed by sub-field key (+ `_row_id`).
	 */
	public function load_value( $value, $post_id, $field ) {
		$ids = $this->record_ids( $value, $post_id, $field );
		if ( ! $ids ) {
			return array();
		}
		_prime_post_caches( $ids, false, true );
		$rows = array();
		foreach ( $ids as $id ) {
			$row = array( '_row_id' => $id );
			foreach ( $this->subs( $field ) as $s ) {
				$row[ $s['key'] ] = acf_get_value( $id, $s );
			}
			$rows[] = $row;
		}
		return $rows;
	}

	public function format_value( $value, $post_id, $field, $escape_html = false ) {
		if ( empty( $value ) || ! is_array( $value ) ) {
			return array();
		}
		$out = array();
		foreach ( $value as $row ) {
			$id = (int) ( $row['_row_id'] ?? 0 );
			$r  = array();
			foreach ( $this->subs( $field ) as $s ) {
				$r[ $s['name'] ] = acf_format_value( $row[ $s['key'] ] ?? null, $id, $s, $escape_html );
			}
			$out[] = $r;
		}
		return $out;
	}

	/**
	 * Accepts rows from the edit form (keyed by sub-field key, with `_row_id`) or from code
	 * (update_field: keyed by sub-field name). Existing records are reused — by ID when the
	 * form sends it, otherwise by position — so saving again never duplicates items.
	 */
	public function update_value( $value, $post_id, $field ) {
		$owner    = self::owner_key( $post_id );
		// only records owned by this post may be reused or deleted (a copied page may still point
		// at the original's records: those are left untouched and replaced by its own)
		$existing = array_values( array_filter( $this->stored_row_ids( $post_id, $field ), static fn( $id ) => get_post_meta( $id, '_sh_owner', true ) === $owner ) );
		$rows     = is_array( $value ) ? $value : array();
		unset( $rows[ self::token( $field ) ] );
		$rows  = array_values( array_filter( $rows, 'is_array' ) );
		$keep  = array();
		$pool  = $existing;

		foreach ( $rows as $i => $row ) {
			$id = (int) ( $row['_row_id'] ?? 0 );
			if ( $id && ! in_array( $id, $existing, true ) ) {
				$id = 0; // stale or foreign ID (another page, a revision): its values are copied into a new record
			}
			if ( ! $id ) {
				// reuse the first existing record not claimed by an explicit ID
				foreach ( $pool as $k => $cand ) {
					if ( ! in_array( $cand, $keep, true ) && ! $this->claimed( $cand, $rows ) ) {
						$id = $cand;
						unset( $pool[ $k ] );
						break;
					}
				}
			}
			if ( ! $id ) {
				$id = $this->create_record( $post_id, $field, $i );
			}
			if ( ! $id ) {
				continue;
			}
			wp_update_post( array( 'ID' => $id, 'menu_order' => $i ) );
			foreach ( $this->subs( $field ) as $s ) {
				if ( array_key_exists( $s['key'], $row ) ) {
					$v = $row[ $s['key'] ];
				} elseif ( array_key_exists( $s['name'], $row ) ) {
					$v = $row[ $s['name'] ];
				} else {
					continue;
				}
				acf_update_value( $v, $id, $s );
			}
			$keep[] = $id;
		}
		foreach ( array_diff( $existing, $keep ) as $gone ) {
			self::delete_record( (int) $gone );
		}
		return $keep;
	}

	private function claimed( int $id, array $rows ): bool {
		foreach ( $rows as $r ) {
			if ( (int) ( $r['_row_id'] ?? 0 ) === $id ) {
				return true;
			}
		}
		return false;
	}

	public function delete_value( $post_id, $key, $field ) {
		foreach ( $this->stored_row_ids( $post_id, $field ) as $id ) {
			if ( get_post_meta( $id, '_sh_owner', true ) === self::owner_key( $post_id ) ) {
				self::delete_record( $id );
			}
		}
	}

	public function validate_value( $valid, $value, $field, $input ) {
		$rows = is_array( $value ) ? $value : array();
		unset( $rows[ self::token( $field ) ] );
		$count = count( array_filter( $rows, 'is_array' ) );
		if ( $field['min'] && $count < (int) $field['min'] ) {
			/* translators: %d: minimum number of items */
			return sprintf( __( 'أضف %d عناصر على الأقل.', 'seohouse-core' ), (int) $field['min'] );
		}
		if ( $field['max'] && $count > (int) $field['max'] ) {
			/* translators: %d: maximum number of items */
			return sprintf( __( 'الحد الأقصى %d عناصر.', 'seohouse-core' ), (int) $field['max'] );
		}
		foreach ( $rows as $i => $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			foreach ( $this->subs( $field ) as $s ) {
				if ( array_key_exists( $s['key'], $row ) ) {
					acf_validate_value( $row[ $s['key'] ], $s, "{$input}[{$i}][{$s['key']}]" );
				}
			}
		}
		return $valid;
	}

	/* ------------------------------------------------------------ editor UI */

	/** Placeholder index of the clone template; unique per list so nested templates are not renamed with their parent. */
	private static function token( array $field ): string {
		return 'shclone_' . preg_replace( '/[^a-z0-9_]/i', '', (string) $field['key'] );
	}

	public function input_admin_enqueue_scripts() {
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'sh-field-rows', SH_CORE_URL . 'assets/field-rows.js', array( 'acf-input', 'jquery-ui-sortable' ), SH_CORE_VERSION, true );
		wp_enqueue_style( 'sh-field-rows', SH_CORE_URL . 'assets/field-rows.css', array( 'acf-input' ), SH_CORE_VERSION );
	}

	public function render_field( $field ) {
		$subs  = $this->subs( $field );
		$rows  = is_array( $field['value'] ) ? array_values( $field['value'] ) : array();
		$label = $field['button_label'] ? $field['button_label'] : __( 'إضافة عنصر', 'seohouse-core' );
		$title_key = $field['collapsed'] ? $field['collapsed'] : ( $subs[0]['key'] ?? '' );
		printf(
			'<div class="sh-rows" data-min="%d" data-max="%d" data-title-key="%s">',
			(int) $field['min'],
			(int) $field['max'],
			esc_attr( $title_key )
		);
		echo '<input type="hidden" name="' . esc_attr( $field['name'] ) . '" value="">';
		echo '<ol class="sh-rows__list">';
		foreach ( $rows as $i => $row ) {
			$this->render_row( $field, $subs, 'row-' . $i, $row, $i + 1 );
		}
		echo '</ol>';
		// the template is inside a disabled fieldset: its inputs are never submitted
		echo '<fieldset class="sh-rows__tpl" disabled hidden><ol>';
		$this->render_row( $field, $subs, self::token( $field ), array(), 0, true );
		echo '</ol></fieldset>';
		echo '<p class="sh-rows__empty"' . ( $rows ? ' hidden' : '' ) . '>' . esc_html__( 'لا توجد عناصر.', 'seohouse-core' ) . '</p>';
		echo '<button type="button" class="button sh-rows__add">' . esc_html( $label ) . '</button>';
		echo '</div>';
	}

	private function render_row( array $field, array $subs, string $index, array $row, int $num, bool $clone = false ): void {
		$prefix = $field['name'] . '[' . $index . ']';
		$title  = '';
		foreach ( $subs as $s ) {
			$v = $row[ $s['key'] ] ?? '';
			if ( is_string( $v ) && '' !== trim( wp_strip_all_tags( $v ) ) ) {
				$title = wp_strip_all_tags( $v );
				break;
			}
		}
		echo '<li class="sh-rows__row' . ( $clone ? ' acf-clone' : ' -collapsed' ) . '" data-id="' . esc_attr( $index ) . '">';
		echo '<div class="sh-rows__bar">';
		echo '<span class="sh-rows__handle" title="' . esc_attr__( 'اسحب لتغيير الترتيب', 'seohouse-core' ) . '" aria-hidden="true">⋮⋮</span>';
		echo '<span class="sh-rows__num">' . ( $num ? (int) $num : '' ) . '</span>';
		echo '<button type="button" class="sh-rows__toggle" aria-expanded="' . ( $clone ? 'true' : 'false' ) . '"><span class="sh-rows__title">' . esc_html( wp_html_excerpt( $title, 80, '…' ) ) . '</span></button>';
		echo '<button type="button" class="sh-rows__up" aria-label="' . esc_attr__( 'تحريك لأعلى', 'seohouse-core' ) . '">↑</button>';
		echo '<button type="button" class="sh-rows__down" aria-label="' . esc_attr__( 'تحريك لأسفل', 'seohouse-core' ) . '">↓</button>';
		echo '<button type="button" class="sh-rows__remove" aria-label="' . esc_attr__( 'حذف العنصر', 'seohouse-core' ) . '">✕</button>';
		echo '</div><div class="sh-rows__body acf-fields -top">';
		echo '<input type="hidden" name="' . esc_attr( $prefix . '[_row_id]' ) . '" value="' . esc_attr( (string) (int) ( $row['_row_id'] ?? 0 ) ) . '">';
		foreach ( $subs as $s ) {
			$s['prefix'] = $prefix;
			if ( array_key_exists( $s['key'], $row ) ) {
				$s['value'] = $row[ $s['key'] ];
			} elseif ( isset( $s['default_value'] ) ) {
				$s['value'] = $s['default_value'];
			}
			acf_render_field_wrap( $s, 'div', 'label' );
		}
		echo '</div></li>';
	}
}
