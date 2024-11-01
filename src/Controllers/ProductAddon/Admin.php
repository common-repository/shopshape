<?php // phpcs:ignore

namespace PHPArtisan\ShopShape\Controllers\ProductAddon;

// If this file is called directly, abort.
use PHPArtisan\ShopShape\Traits\Singleton;
use WP_Term;

if ( ! defined( 'PHPArtisan\ShopShape\SLUG' ) ) {
	exit;
}

class Admin {
	use Singleton;


	private function __construct() {
		// Add form.
		add_action( 'shopshape-addons_add_form_fields', array( $this, 'create_price_field' ) );
		add_action( 'shopshape-addons_edit_form_fields', array( $this, 'edit_price_field' ) );
		add_action( 'created_term', array( $this, 'save_addon_fields' ), 10, 3 );
		add_action( 'edit_term', array( $this, 'save_addon_fields' ), 10, 3 );
		add_action( 'quick_edit_custom_box', array( $this, 'quickedit_field' ), 10, 3 );
		add_action( 'edited_term', array( $this, 'save_quickedit' ) );

		// Add columns.
		add_filter( 'manage_edit-shopshape-addons_columns', array( $this, 'price_header' ) );
		add_filter( 'manage_shopshape-addons_custom_column', array( $this, 'price_row' ), 10, 3 );
		add_filter( 'ajax_term_search_results', array( $this, 'ajax_search_results' ), 10, 2 );

		// Add actions.
	}

	public function quickedit_field( $column_name, $screen, $name ) {
		if ( $column_name === 'price' && $screen === 'edit-tags' && $name === Addon::KEY ) {
			?>
		<fieldset class="inline-edit-col-left">
			<div class="inline-edit-col">
				<label class="inline-edit-group">
					<span class="title">Price</span>
					<span class="input-text-wrap">
						<input type="text" name="price" class="ptitle" value="">
					</span>
				</label>
			</div>
		</fieldset>
			<?php
		}
	}
	public function save_quickedit( $term_id ) {
		if ( isset( $_POST['price'] ) ) {
			$custom_field_value = sanitize_text_field( $_POST['price'] );
			update_term_meta( $term_id, 'price', $custom_field_value );
		}
	}
	/**
	 * Create a price field in the addon creation form.
	 */
	public function create_price_field() {
		?>
		<div class="form-field term-price-wrap">
			<label for="price"><?php esc_html_e( 'Price', 'shopshape' ); ?></label>
			<input type="number" name="price" id="price" value="0" step="5">
			<p id="price-description"><?php esc_html_e( 'Add the price of this addon', 'shopshape' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Create a price field in the addon edit form.
	 */
	public function edit_price_field( $term ) {
		?>
		<tr class="form-field form-required term-name-wrap">
			<th scope="row">
				<label for="price"><?php esc_html_e( 'Price', 'shopshape' ); ?></label>
			</th>
			<td>
				<input name="price" id="price" type="number"
						value="<?php echo esc_attr( get_term_meta( $term->term_id, 'price', true ) ); ?>"
						size="40"
						aria-required="true" aria-describedby="price-description">
				<p class="description"
					id="price-description"><?php esc_html_e( 'Add the price of this addon', 'shopshape' ); ?></p>
			</td>
		</tr>
		<?php
	}

	/**
	 * Save addon fields when the term is updated.
	 *
	 * @param int $term_id The term ID.
	 * @param int $term_taxonomy_id
	 * @param string $taxonomy The taxonomy name.
	 */
	public function save_addon_fields( int $term_id, int $term_taxonomy_id, string $taxonomy ) {
		if ( 'shopshape-addons' === $taxonomy ) {
			$price = isset( $_POST['price'] ) ? sanitize_text_field( $_POST['price'] ) : ''; // phpcs:ignore
			update_term_meta( $term_id, 'price', $price );
		}
	}

	public function price_header( $columns ) {
		$columns['price'] = 'Price';

		return $columns;
	}

	public function price_row( $columns, $column, $id ) {
		if ( 'price' !== $column ) {
			return $columns;
		}

		return get_term_meta( $id, 'price', true );
	}

	/**
	 * Filters the Ajax term search results.
	 *
	 * @param WP_Term $term Term object.
	 * @param array $results Array of results.
	 *
	 * @return string[]
	 */
	public function ajax_search_results( WP_Term $term, array $results ): array {
		if ( 'shopshape-addons' !== $term->name ) {
			return $results;
		}

		return array_map(
			function ( $result ) {
				$price = (int) $result->value ?? 0;

				return "{$result->name}({$price})";
			},
			$this->get_term_with_meta( $term->name, $_GET['q'], 'price' ) // phpcs:ignore
		);
	}

	private function get_term_with_meta( $taxonomy, $search_term, $meta_key ) {
		global $wpdb;

		return $wpdb->get_results(
			$wpdb->prepare(
				"SELECT t.name AS name, tm.meta_value AS value
					FROM {$wpdb->terms} t
					JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
					LEFT JOIN {$wpdb->termmeta} tm ON t.term_id = tm.term_id AND tm.meta_key = %s
					WHERE tt.taxonomy = %s AND t.name LIKE %s",
				$meta_key,
				$taxonomy,
				sprintf( '%%%s%%', $wpdb->esc_like( $search_term ) )
			),
		);
	}
}
