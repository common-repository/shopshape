<?php // phpcs:ignore

namespace PHPArtisan\ShopShape\Controllers;

// If this file is called directly, abort.
if ( ! defined( 'PHPArtisan\ShopShape\SLUG' ) ) {
	die;
}

use PHPArtisan\ShopShape\Traits\FileSystem;
use PHPArtisan\ShopShape\Traits\Singleton;


final class Log {
	use Singleton;
	use FileSystem;

	public function error() {
		return $this->write( 'error', func_get_args() );
	}

	/**
	 * It's writing the log to a file.
	 *
	 * @param string $type of message.
	 * @param mixed $message message to write.
	 *
	 */
	public function write( string $type, $message, bool $timestamp = true, bool $append = true ) {
		$file = $this->file( $type );

		if ( is_array( $message ) || is_object( $message ) || is_iterable( $message ) ) {
			$message = wp_json_encode( $message, JSON_PRETTY_PRINT );
		} else {
			$decoded = json_decode( $message, true );
			if ( json_last_error() === JSON_ERROR_NONE ) {
				$message = wp_json_encode( $decoded, JSON_PRETTY_PRINT );
			}
		}
		if ( $timestamp ) {
			$date    = sprintf( '[%s]::', gmdate( 'd-M-Y h:i:s A' ) );
			$message = $date . sanitize_textarea_field( $message );
		}

		if ( $append ) {
			$message = $this->read( $type ) . PHP_EOL . sanitize_textarea_field( $message );
		}

		return $this->write_file( $file, $message );
	}

	/**
	 * It's reading the log file and returning the content.
	 *
	 * @param string $type log type.
	 *
	 * @return string logs
	 */
	public function read( string $type ): string {
		$file = $this->file( $type );

		return $this->read_file( $file );
	}

	/**
	 * It's deleting the log file.
	 *
	 * @param string $type log type.
	 *
	 * @return bool
	 */
	public function delete( string $type ): bool {
		$file = $this->file( $type );

		return $this->delete_file( $file );
	}

	/**
	 * Retrieves an array of files.
	 *
	 * @return array The array of files.
	 */
	public function files(): array {
		return glob( $this->get_dir() . '*.log' );
	}


	public function info() {
		$this->write( 'info', func_get_args() );
	}

	public function event( $message ) {
		$this->write( 'events', sprintf( '%s by %s on %s', $message, wp_get_current_user()->user_email, get_site_url() ) );
	}
}
