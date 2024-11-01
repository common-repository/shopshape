<?php // phpcs:ignore

namespace PHPArtisan\ShopShape\Controllers\ProductAddon;

// If this file is called directly, abort.
if ( ! defined( 'PHPArtisan\ShopShape\SLUG' ) ) {
	die;
}

use PHPArtisan\ShopShape\Traits\Singleton;

final class Cart {
	use Singleton;

	private function __construct() {
		add_filter( 'woocommerce_add_cart_item', array( $this, 'calculate_cart_item_total' ), 20 );
		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'save_selected_addon' ) );
		add_filter( 'woocommerce_get_item_data', array( $this, 'display_selected_addon_data' ), 10, 2 );
		add_filter(
			'woocommerce_get_cart_item_from_session',
			array(
				$this,
				'retrieve_cart_item_from_session',
			),
			20,
			2
		);
	}

	public function calculate_cart_item_total( $cart_item ) {
		$addons = $cart_item['addons'];

		if ( empty( $addons ) ) {
			return $cart_item;
		}

		$total = $this->calculate_total_with_addons( $cart_item['data']->get_price(), $addons );

		$cart_item['data']->set_price( $total );

		return $cart_item;
	}

	private function calculate_total_with_addons( $base_price, $addons ) {
		$total = $base_price;

		foreach ( $addons as $name => $price ) {
			if ( $price > 0 ) {
				$total += (float) $price;
			}
		}

		return $total;
	}

	public function save_selected_addon( $cart_item ) {
		$selected_addon = $this->get_selected_addon();

		if ( empty( $selected_addon ) ) {
			return $cart_item;
		}

		$cart_item['addons'] = $selected_addon;

		return $cart_item;
	}

	private function get_selected_addon() {
		return $_REQUEST['selected_addon'] ?? array(); // phpcs:ignore
	}

	public function display_selected_addon_data( $data, $cart_item ) {
		$addons = $cart_item['addons'] ?? array();

		if ( empty( $addons ) ) {
			return $data;
		}

		$data[] = $this->format_addon_data( __( 'Product price', 'woocommerce' ), $cart_item['product_price'] );
		foreach ( $addons as $name => $price ) {
			$data[] = $this->format_addon_data( $name, $price );
		}

		return $data;
	}

	private function format_addon_data( $name, $price ): array {
		return array(
			'name'  => $name,
			'value' => wc_price( $price ),
		);
	}

	public function retrieve_cart_item_from_session( $session_data, $values ) {
		$addons = $values['addons'] ?? array();

		if ( empty( $addons ) ) {
			return $session_data;
		}

		$session_data['addons']        = $addons;
		$session_data['product_price'] = $session_data['data']->get_price();
		$total                         = $this->calculate_total_with_addons( $session_data['data']->get_price(), $addons );

		$session_data['data']->set_price( $total );

		return $session_data;
	}
}
