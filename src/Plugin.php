<?php // phpcs:ignore

namespace PHPArtisan\ShopShape;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


use PHPArtisan\ShopShape\Controllers\Requirement;
use PHPArtisan\ShopShape\Controllers\License;
use PHPArtisan\ShopShape\Controllers\Log;
use PHPArtisan\ShopShape\Controllers\ProductAddon\Addon;

class Plugin {
	public static function activate(): void {
		License::instance();
		update_user_meta( get_current_user_id(), 'manageedit-productcolumnshidden', array( 'featured', 'date' ) );
		// enable plugin's auto-update
		self::enable_updates();
	}

	/**
	 * Enable plugin's auto-update on activation.
	 *
	 * @return void
	 */
	private static function enable_updates(): void {
		$auto_updates = (array) get_site_option( 'auto_update_plugins', array() );
		$plugin       = plugin_basename( FILE );
		if ( false === in_array( $plugin, $auto_updates, true ) ) {
			$auto_updates[] = $plugin;
			update_site_option( 'auto_update_plugins', $auto_updates );
		}
	}

	public static function deactivate(): void {
		License::instance();
		delete_user_meta( get_current_user_id(), 'manageedit-productcolumnshidden' );
		self::disable_updates();
	}

	/**
	 * Disable auto-update deactivation or uninstall
	 *
	 * @return void
	 */
	private static function disable_updates(): void {
		$auto_updates = (array) get_site_option( 'auto_update_plugins', array() );
		$plugin       = plugin_basename( FILE );
		$update       = array_diff( $auto_updates, array( $plugin ) );
		update_site_option( 'auto_update_plugins', $update );
	}

	public static function uninstall(): void {
		License::instance();
	}

	public static function init(): void {
		if ( Requirement::not_matched() ) {
			return;
		}
		try {
			Addon::instance();
		} catch ( \Exception $exception ) {
			Log::instance()->error(
				array(
					'error' => $exception->getMessage(),
					'trace' => $exception->getTrace(),
				)
			);
		}
	}
}
