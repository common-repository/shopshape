<?php // phpcs:ignore

namespace PHPArtisan\ShopShape\Controllers\ProductAddon;

// If this file is called directly, abort.

if ( ! defined( 'PHPArtisan\ShopShape\SLUG' ) ) {
	die;
}

use PHPArtisan\ShopShape\Traits\Singleton;

final class Addon {
	use Singleton;

	const KEY = 'shopshape-addons';

	private function __construct() {
		add_action(
			'init',
			function () {
				register_taxonomy( self::KEY, array( 'product' ), $this->args() );
			}
		);
		if ( is_admin() ) {
			Admin::instance();
		}
		SinglePage::instance();
		Cart::instance();
		Checkout::instance();
	}

	/**
	 * @return array
	 */
	public function args(): array {
		return array(
			'hierarchical'      => true,
			'labels'            => $this->labels(),
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => self::KEY ),
			'show_in_rest'      => true,
			'rest_base'         => self::KEY,
		);
	}

	/**
	 * @return array
	 */
	public function labels(): array {
		return array(
			'name'                       => _x( 'Product addons', 'taxonomy general name', 'shopshape' ),
			'singular_name'              => _x( 'Addon', 'taxonomy singular name', 'shopshape' ),
			'search_items'               => __( 'Search Addons', 'shopshape' ),
			'all_items'                  => __( 'All Addons', 'shopshape' ),
			'edit_item'                  => __( 'Edit Addon', 'shopshape' ),
			'update_item'                => __( 'Update Addon', 'shopshape' ),
			'add_new_item'               => __( 'Add New Addon', 'shopshape' ),
			'new_item_name'              => __( 'New Addon Name', 'shopshape' ),
			'menu_name'                  => __( 'Addons', 'shopshape' ),
			'parent_item'                => __( 'Parent Addon', 'shopshape' ),
			'parent_item_colon'          => __( 'Parent Addon:', 'shopshape' ),
			'view_item'                  => __( 'View Addon', 'shopshape' ),
			'popular_items'              => __( 'Popular Addons', 'shopshape' ),
			'separate_items_with_commas' => __( 'Separate addons with commas', 'shopshape' ),
			'add_or_remove_items'        => __( 'Add or remove addons', 'shopshape' ),
			'choose_from_most_used'      => __( 'Choose from the most used addons', 'shopshape' ),
			'not_found'                  => __( 'No addons found.', 'shopshape' ),
			'no_terms'                   => __( 'No addons', 'shopshape' ),
			'back_to_items'              => __( '← Back to Addons', 'shopshape' ),
		);
	}
}
