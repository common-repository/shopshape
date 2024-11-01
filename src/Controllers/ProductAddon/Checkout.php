<?php // phpcs:ignore

namespace PHPArtisan\ShopShape\Controllers\ProductAddon;

// If this file is called directly, abort.
if ( ! defined( 'PHPArtisan\ShopShape\SLUG' ) ) {
	die;
}

use PHPArtisan\ShopShape\Traits\Singleton;
use WC_Order_Item;
use WC_Order_Item_Product;

final class Checkout {
	use Singleton;

	/**
	 * Hook into WooCommerce to add addons during order line item creation.
	 */
	private function __construct() {
		add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'add_addons_to_order' ), 10, 3 );
		add_filter( 'woocommerce_order_item_display_meta_value', array( $this, 'display_addons_on_order' ), 30, 3 );
	}

	/**
	 * Add addons to the order line item during creation.
	 *
	 * @param WC_Order_Item_Product $item The order item.
	 * @param string $cart_item_key Cart item key.
	 * @param array $values Values from the cart item.
	 */
	public function add_addons_to_order( WC_Order_Item_Product $item, string $cart_item_key, array $values ): void {
		$addons = $values['addons'] ?? array();

		if ( empty( $addons ) ) {
			return;
		}
		foreach ( $addons as $name => $price ) {
			$item->add_meta_data( $name, wc_price( $price ) );
		}

	}

	/**
	 * Display addons on the order.
	 *
	 * @param string $display_value Display value.
	 * @param mixed $meta Meta value.
	 * @param WC_Order_Item $item The order item.
	 *
	 * @return string Modified display value.
	 */
	public function display_addons_on_order( string $display_value, $meta, WC_Order_Item $item ): string {
		$addons = $item->get_meta( 'addons' );

		if ( ! empty( $addons ) ) {
			$display_value .= ' (' . implode( ', ', $addons ) . ')';
		}

		return $display_value;
	}
}
