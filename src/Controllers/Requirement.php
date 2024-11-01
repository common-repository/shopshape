<?php // phpcs:ignore

namespace PHPArtisan\ShopShape\Controllers;

// If this file is called directly, abort.
if ( ! defined( 'PHPArtisan\ShopShape\SLUG' ) ) {
	die;
}

final class Requirement {

	public static function not_matched(): bool {
		return self::check_theme() || self::check_plugin();
	}

	private static function check_theme(): bool {
		// check block based theme is activate or not
		if ( wp_is_block_theme() ) {
			if ( is_admin() ) {
				add_action(
					'admin_notices',
					function () {
						echo wp_kses_post(
							sprintf(
								'<div class="notice notice-error"><p><strong>%s</strong> %s <a href="/wp-admin/theme-install.php?browse=block-themes"><strong>%s</strong></a> %s <strong>%s</strong> %s <a href="/wp-admin/theme-install.php?browse=popular"><strong>%s</strong></a></a></p></div>',
								__( 'Shop Shape', 'shopshape' ),
								__( 'is not compatible with', 'shopshape' ),
								__( 'Block', 'shopshape' ),
								__( 'themes. Please uninstall', 'shopshape' ),
								__( 'Shop Shape', 'shopshape' ),
								__( 'or', 'shopshape' ),
								__( 'Switch to another theme', 'shopshape' ),
							)
						);
					}
				);
			}

			return true;
		}

		return false;
	}

	private static function check_plugin(): bool {
		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
			if ( is_admin() ) {
				add_action(
					'admin_notices',
					function () {
						echo wp_kses_post(
							sprintf(
								'<div class="notice notice-error"><p>%s <a href="/wp-admin/plugin-install.php?s=woocommerce&tab=search&type=term"><strong>%s</strong></a> %s</p></div>',
								__( 'Please install and activate the ', 'shopshape' ),
								__( 'WooCommerce', 'shopshape' ),
								__( 'plugin for ShopShape', 'shopshape' )
							)
						);
					}
				);
			}

			return true;
		}

		return false;
	}
}
