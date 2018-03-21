<?php
/**
 * Abstract Data Store Custom Post Type
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay;

use DateTimeInterface;
use DateTimeZone;

/**
 * Abstract Data Store Custom Post Type
 *
 * @see https://woocommerce.com/2017/04/woocommerce-3-0-release/
 * @see https://woocommerce.wordpress.com/2016/10/27/the-new-crud-classes-in-woocommerce-2-7/
 * @author Remco Tolsma
 * @version 3.7.0
 * @since 3.7.0
 */
abstract class AbstractDataStoreCPT {
	/**
	 * Meta key prefix.
	 *
	 * @var string
	 */
	protected $meta_key_prefix = '';

	/**
	 * Get a prefixed meta key for the specified key.
	 *
	 * @param string $key A key.
	 * @return string
	 */
	private function get_meta_key( $key ) {
		return $this->meta_key_prefix . $key;
	}

	/**
	 * Get meta for the specified post ID and key.
	 *
	 * @param int    $id  Post ID.
	 * @param string $key Key.
	 * @return string
	 */
	public function get_meta( $id, $key ) {
		$meta_key = $this->get_meta_key( $key );

		$value = get_post_meta( $id, $meta_key, true );

		if ( '' === $value ) {
			return null;
		}

		return $value;
	}

	/**
	 * Get date from meta.
	 *
	 * @param int    $id  Post ID.
	 * @param string $key Key.
	 *
	 * @throws \Exception In case of an error.
	 *
	 * @return DateTime|null
	 */
	public function get_meta_date( $id, $key ) {
		$value = $this->get_meta( $id, $key );

		if ( empty( $value ) ) {
			return null;
		}

		$date = new DateTime( $value, new DateTimeZone( 'UTC' ) );

		return $date;
	}

	/**
	 * Update meta.
	 *
	 * @param int    $id    Post ID.
	 * @param string $key   Key.
	 * @param mixed  $value Value.
	 */
	public function update_meta( $id, $key, $value ) {
		if ( empty( $value ) ) {
			return false;
		}

		if ( $value instanceof DateTimeInterface ) {
			$value = $value->format( 'Y-m-d H:i:s' );
		}

		$meta_key = $this->get_meta_key( $key );

		$result = update_post_meta( $id, $meta_key, $value );

		return $result;
	}
}
