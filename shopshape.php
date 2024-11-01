<?php // phpcs:ignore

/**
 * Shop Shape
 *
 * @package           PHPArtisan\ShopShape
 * @author            PHP Artisan
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Shop Shape
 * Plugin URI:        https://wordpress.org/plugins/shopshape/
 * Description:       A powerful toolkit designed to elevate your e-commerce experience.
 * Version:           1.0.0
 * Requires at least: 5.3
 * Requires PHP:      7.4
 * Author:            Pluximo
 * Author URI:        https://pluximo.com/
 * Text Domain:       shopshape
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

/*
|--------------------------------------------------------------------------
| If this file is called directly, abort.
|--------------------------------------------------------------------------
*/
if ( ! defined( 'WPINC' ) ) {
	exit;
}

/*
|--------------------------------------------------------------------------
| Load class autoloader
|--------------------------------------------------------------------------
*/
require_once __DIR__ . '/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Define default constants
|--------------------------------------------------------------------------
*/
define( 'PHPArtisan\ShopShape\SLUG', 'shopshape' );
define( 'PHPArtisan\ShopShape\VERSION', '1.0.0' );
define( 'PHPArtisan\ShopShape\FILE', __FILE__ );


/*
|--------------------------------------------------------------------------
| Activation, deactivation and uninstall event.
|--------------------------------------------------------------------------
*/
register_activation_hook( __FILE__, array( \PHPArtisan\ShopShape\Plugin::class, 'activate' ) );
register_deactivation_hook( __FILE__, array( \PHPArtisan\ShopShape\Plugin::class, 'deactivate' ) );
register_uninstall_hook( __FILE__, array( \PHPArtisan\ShopShape\Plugin::class, 'uninstall' ) );

/*
|--------------------------------------------------------------------------
| Start the plugin
|--------------------------------------------------------------------------
*/
add_action( 'setup_theme', array( \PHPArtisan\ShopShape\Plugin::class, 'init' ) );
