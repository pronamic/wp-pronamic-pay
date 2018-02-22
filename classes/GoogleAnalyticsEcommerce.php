<?php

namespace Pronamic\WordPress\Pay;

use Pronamic\WordPress\Pay\Core\Gateway;
use Pronamic\WordPress\Pay\Core\Statuses;
use Pronamic\WordPress\Pay\Payments\Payment;

/**
 * Title: Pronamic Pay Google Analytics e-commerce
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Reüel van der Steege
 * @version unreleased
 * @since unreleased
 */
class GoogleAnalyticsEcommerce {
	/**
	 * Google Analytics Measurement Protocol API endpoint URL.
	 *
	 * @see https://developers.google.com/analytics/devguides/collection/protocol/v1/devguide
	 */
	const API_URL = 'https://www.google-analytics.com/collect';

	/**
	 * Measurement Protocol API version.
	 */
	const API_VERSION = 1;

	/**
	 * Anonymous client ID.
	 *
	 * @var string
	 */
	private $client_id = '';

	/**
	 * Constructs an analytics e-commerce object
	 */
	public function __construct() {
		// Actions
		add_action( 'pronamic_payment_status_update', array( $this, 'maybe_send_transaction' ), 10, 2 );
	}

	//////////////////////////////////////////////////

	public function maybe_send_transaction( $payment, $can_redirect ) {
		// Only process successful payments
		if ( Statuses::SUCCESS !== $payment->get_status() ) {
			return;
		}

		// Ignore free orders
		$amount = $payment->get_amount();

		if ( empty( $amount ) ) {
			return;
		}

		// Check if Google Analytics property ID has been set
		$property_id = get_option( 'pronamic_pay_google_analytics_property' );

		if ( empty( $property_id ) ) {
			return;
		}

		// Ignore test mode payments
		if ( Gateway::MODE_TEST === get_post_meta( $payment->config_id, '_pronamic_gateway_mode', true ) ) {
			return;
		}

		$this->send_transaction( $payment );
	}

	/**
	 * Send transaction.
	 *
	 * @see https://developers.google.com/analytics/devguides/collection/protocol/v1/devguide#ecom
	 *
	 * Parameters:
	 * v=1              // Version.
	 * &tid=UA-XXXX-Y   // Tracking ID / Property ID.
	 * &cid=555         // Anonymous Client ID.
	 *
	 * &t=transaction   // Transaction hit type.
	 * &ti=12345        // transaction ID. Required.
	 * &ta=westernWear  // Transaction affiliation.
	 * &tr=50.00        // Transaction revenue.
	 * &ts=32.00        // Transaction shipping.
	 * &tt=12.00        // Transaction tax.
	 * &cu=EUR          // Currency code.
	 *
	 * @param $payment Payment
	 */
	private function send_transaction( $payment ) {
		$defaults = array(
			'v'   => self::API_VERSION,
			'tid' => get_option( 'pronamic_pay_google_analytics_property' ),
			'cid' => $this->get_client_id( $payment ),
			'ti'  => $payment->get_id(),
			'cu'  => $payment->get_currency(),
		);

		// Transaction Hit
		$transaction = wp_parse_args(
			array(
				't'  => 'transaction',
				'tr' => $payment->get_amount(),
			), $defaults
		);

		wp_remote_post(
			self::API_URL, array(
				'user-agent' => filter_input( INPUT_SERVER, 'HTTP_USER_AGENT' ),
				'body'       => http_build_query( $transaction ),
				'blocking'   => false,
			)
		);

		// Item Hit
		$item = wp_parse_args(
			array(
				't'  => 'item',
				'in' => sprintf(
					'%s #%s',
					$payment->get_source_description(),
					$payment->get_source_id()
				),
				'ip' => $payment->get_amount(),
				'iq' => 1,
			), $defaults
		);

		wp_remote_post(
			self::API_URL, array(
				'user-agent' => filter_input( INPUT_SERVER, 'HTTP_USER_AGENT' ),
				'body'       => http_build_query( $item ),
				'blocking'   => false,
			)
		);
	}

	//////////////////////////////////////////////////

	/**
	 * Get the Client ID.
	 *
	 * @return string
	 */
	private function get_client_id( Payment $payment ) {
		$client_id = $payment->get_analytics_client_id();

		if ( ! empty( $client_id ) ) {
			return $client_id;
		}

		if ( ! empty( $this->client_id ) ) {
			return $this->client_id;
		}

		// Check cookie `_ga` for Client ID
		$this->client_id = GoogleAnalyticsEcommerce::get_cookie_client_id();

		if ( empty( $this->client_id ) ) {
			// Generate UUID
			// Borrowed from https://github.com/ins0/google-measurement-php-client/blob/master/src/Racecore/GATracking/GATracking.php
			$this->client_id = sprintf(
				'%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
				// 32 bits for "time_low"
				mt_rand( 0, 0xffff ),
				mt_rand( 0, 0xffff ),
				// 16 bits for "time_mid"
				mt_rand( 0, 0xffff ),
				// 16 bits for "time_hi_and_version",
				// four most significant bits holds version number 4
				mt_rand( 0, 0x0fff ) | 0x4000,
				// 16 bits, 8 bits for "clk_seq_hi_res",
				// 8 bits for "clk_seq_low",
				// two most significant bits holds zero and one for variant DCE1.1
				mt_rand( 0, 0x3fff ) | 0x8000,
				// 48 bits for "node"
				mt_rand( 0, 0xffff ),
				mt_rand( 0, 0xffff ),
				mt_rand( 0, 0xffff )
			);
		}

		return $this->client_id;
	}

	public static function is_uuid( $uuid ) {
		return preg_match( '#^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$#i', $uuid );
	}

	public static function get_cookie_client_id() {
		$client_id = null;

		$ga_cookie = filter_input( INPUT_COOKIE, '_ga', FILTER_SANITIZE_STRING );

		if ( empty( $ga_cookie ) ) {
			// No `_ga` cookie available
			return $client_id;
		}

		$ga = explode( '.', $ga_cookie );

		if ( isset( $ga[2] ) && GoogleAnalyticsEcommerce::is_uuid( $ga[2] ) ) {
			// Use UUID from cookie
			$client_id = $ga[2];
		} elseif ( isset( $ga[2], $ga[3] ) ) {
			// Older Google Client ID
			$client_id = sprintf( '%s.%s', $ga[2], $ga[3] );
		}

		return $client_id;
	}
}
