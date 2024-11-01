<?php // phpcs:ignore

namespace PHPArtisan\ShopShape\Controllers\ProductAddon;

// If this file is called directly, abort.

if ( ! defined( 'PHPArtisan\ShopShape\SLUG' ) ) {
	die;
}

use PHPArtisan\ShopShape\Controllers\Log;
use PHPArtisan\ShopShape\Traits\Singleton;
use WP_Term;
use const PHPArtisan\ShopShape\FILE;

final class SinglePage {
	use Singleton;


	public function __construct() {
		add_action(
			'template_redirect',
			function () {
				if ( ! is_product() ) {
					return;
				}
				add_action( 'wp_enqueue_scripts', array( $this, 'add_style' ) );
				add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'display_addons' ) );
			}
		);
	}

	public function add_style() {
		wp_register_style( 'shopshape-addons', plugins_url( 'assets/single-page.css', FILE ), array(), time() );
		wp_register_script( 'shopshape-addons', plugins_url( 'assets/single-page.js', FILE ), array( 'jquery' ), time(), true );
	}

	/**
	 * Display addons on the single product page.
	 *
	 * @return void
	 */
	public function display_addons() {
		global $product;

		wp_enqueue_style( Addon::KEY );
		wp_enqueue_script( Addon::KEY );

		wp_localize_script(
			Addon::KEY,
			'shopshape',
			array(
				'price_html' => wc_price( 0 ),
			)
		);

		$addons = get_the_terms( $product->get_id(), Addon::KEY );

		if ( is_wp_error( $addons ) ) {
			Log::instance()->write( 'single-product-page', $addons->get_error_message() );

			return;
		}

		if ( empty( $addons ) ) {
			Log::instance()->write( 'single-product-page', __( 'No addons found', 'shopshape' ) );

			return;
		}

		$with_children    = array();
		$without_children = array();

		foreach ( $addons as $addon ) {
			if ( $addon instanceof WP_Term ) {
				$child_addons = get_term_children( $addon->term_id, Addon::KEY );
				if ( empty( $child_addons ) && 0 === $addon->parent ) {
					$without_children[ $addon->slug ] = $this->addon_option( $addon );
				} elseif ( empty( $child_addons ) && ! empty( $addon->parent ) ) {
					$parent                                       = get_term_by( 'id', $addon->parent, Addon::KEY );
					$with_children[ $parent->slug ]['name']       = $parent->name;
					$with_children[ $parent->slug ]['children'][] = $this->addon_option( $addon );
				} else {
					$with_children[ $addon->slug ]['name'] = $addon->name;
					foreach ( $child_addons as $child_addon_id ) {
						$child_addon                                 = get_term_by( 'id', $child_addon_id, Addon::KEY );
						$with_children[ $addon->slug ]['children'][] = $this->addon_option( $addon, $child_addon );
					}
				}
			}
		}

		ob_start();
		include plugin_dir_path( FILE ) . 'templates/addon-list.php';
		echo ob_get_clean();
	}

	/**
	 * Display addon option.
	 *
	 * @param WP_Term $addon The main addon term.
	 * @param WP_Term|null $child_addon The child addon term.
	 *
	 * @return array
	 */
	private function addon_option( WP_Term $addon, WP_Term $child_addon = null ): array {
		$addon_title = $child_addon ? esc_html( $child_addon->name ) : esc_html( $addon->name );
		$addon_price = $child_addon ? get_term_meta( $child_addon->term_id, 'price', true ) : get_term_meta( $addon->term_id, 'price', true );

		return array(
			'name'  => $addon_title,
			'price' => (float) $addon_price,
		);
	}
}
