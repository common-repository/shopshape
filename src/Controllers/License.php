<?php // phpcs:ignore

namespace PHPArtisan\ShopShape\Controllers;

// If this file is called directly, abort.
if ( ! defined( 'PHPArtisan\ShopShape\SLUG' ) ) {
	exit;
}

use PHPArtisan\ShopShape\Traits\Singleton;
use const PHPArtisan\ShopShape\SLUG;
use const PHPArtisan\ShopShape\VERSION;

/**
 * LicenseApi
 */
final class License {
	use Singleton;

	private const DOMAIN   = 'https://phprtsan.com/';
	private const ENDPOINT = 'wp-json/phprtsan-plugins/v1.0.0/license';

	/**
	 * LicenseApi constructor.
	 *
	 */
	private function __construct() {
		$site_url = wp_parse_url( home_url(), PHP_URL_HOST );

		$data = array(
			'domain'  => $site_url,
			'event'   => $this->get_current_action(),
			'version' => VERSION,
			'name'    => SLUG,
		);

		$headers = array(
			'user-agent'    => SLUG . ';' . password_hash( $site_url, PASSWORD_BCRYPT ),
			'X-Plugin-Name' => SLUG,
			'Accept'        => 'application/json',
			'Content-Type'  => 'application/json',
			'Origin'        => $site_url,
			'Referer'       => $site_url,
			'Cache-Control' => 'no-cache',
		);

		$response = wp_remote_post(
			self::DOMAIN . self::ENDPOINT,
			array(
				'timeout'     => 30,
				'redirection' => 5,
				'httpversion' => '1.0',
				'headers'     => $headers,
				'body'        => wp_json_encode( $data ),
				'sslverify'   => false,
				'cookies'     => array(),
			)
		);
		if ( false === is_wp_error( $response ) ) {
			$response = wp_remote_retrieve_body( $response );

			update_option( SLUG . '-license-key', $response );
		} else {
			Log::instance()->write( 'error', $response->get_error_message() );
		}
	}

	/**
	 * Get the current action.
	 *
	 * @return string The current action.
	 */
	private function get_current_action(): string {
		$backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS ); // phpcs:ignore

		return $backtrace[3]['function'];
	}
}
