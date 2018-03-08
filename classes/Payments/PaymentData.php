<?php
/**
 * Payment Data
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Payments;

use Pronamic\WordPress\Pay\GoogleAnalyticsEcommerce;
use WP_User;

/**
 * WordPress payment data
 *
 * @author Remco Tolsma
 * @version 1.0
 */
abstract class PaymentData extends AbstractPaymentData {
	/**
	 * The current user.
	 *
	 * @var WP_User
	 */
	private $user;

	/**
	 * Constructs and intializes an WordPress iDEAL data proxy.
	 */
	public function __construct() {
		parent::__construct();

		$this->user = wp_get_current_user();
	}

	/**
	 * Get the ISO 639 language code.
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_language()
	 * @return string
	 */
	public function get_language() {
		$locale = get_locale();

		return substr( $locale, 0, 2 );
	}

	/**
	 * Get the language ISO 639 and ISO 3166 country code.
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_language_and_country()
	 * @return string
	 */
	public function get_language_and_country() {
		return get_locale();
	}

	/**
	 * Get email.
	 *
	 * @return string
	 */
	public function get_email() {
		$email = null;

		if ( is_user_logged_in() ) {
			$email = $this->user->user_email;
		}

		return $email;
	}

	/**
	 * Get first name.
	 *
	 * @return string
	 */
	public function get_first_name() {
		if ( is_user_logged_in() ) {
			return $this->user->user_firstname;
		}
	}

	/**
	 * Get last name.
	 *
	 * @return string
	 */
	public function get_last_name() {
		if ( is_user_logged_in() ) {
			return $this->user->user_lastname;
		}
	}

	/**
	 * Get customer name.
	 *
	 * @return string
	 */
	public function get_customer_name() {
		$parts = array(
			$this->get_first_name(),
			$this->get_last_name(),
		);

		$parts = array_filter( $parts );

		if ( empty( $parts ) ) {
			return $this->user->display_name;
		}

		$name = trim( implode( ' ', $parts ) );

		return $name;
	}

	/**
	 * Get Google Analytics client ID.
	 *
	 * @return string
	 */
	public function get_analytics_client_id() {
		$property_id = get_option( 'pronamic_pay_google_analytics_property' );

		// Only use Client ID if Analytics property has been set.
		if ( empty( $property_id ) ) {
			return;
		}

		return GoogleAnalyticsEcommerce::get_cookie_client_id();
	}

	/**
	 * Get URL for the specified name.
	 *
	 * @param string $name The name to get the URL for.
	 * @return string
	 */
	private function get_url( $name ) {
		$url = home_url( '/' );

		$permalink = get_permalink( pronamic_pay_get_page_id( $name ) );

		if ( $permalink ) {
			$url = $permalink;
		}

		return $url;
	}

	/**
	 * Get normal return URL.
	 *
	 * @return string
	 */
	public function get_normal_return_url() {
		return $this->get_url( 'unknown' );
	}

	/**
	 * Get cancel URL.
	 *
	 * @return string
	 */
	public function get_cancel_url() {
		return $this->get_url( 'cancel' );
	}

	/**
	 * Get success URL.
	 *
	 * @return string
	 */
	public function get_success_url() {
		return $this->get_url( 'completed' );
	}

	/**
	 * Get error URL.
	 *
	 * @return string
	 */
	public function get_error_url() {
		return $this->get_url( 'error' );
	}

	/**
	 * Get blog name.
	 *
	 * @return string
	 */
	public function get_blogname() {
		$blogname = get_option( 'blogname' );

		if ( empty( $blogname ) ) {
			return '';
		}

		// @see https://github.com/WordPress/WordPress/blob/3.8.1/wp-includes/pluggable.php#L1085
		// The blogname option is escaped with esc_html on the way into the database in sanitize_option
		// we want to reverse this for the gateways.
		$blogname = wp_specialchars_decode( $blogname, ENT_QUOTES );

		return $blogname;
	}

	/**
	 * Get subscription source ID.
	 *
	 * @return string
	 */
	public function get_subscription_source_id() {
		return $this->get_source_id();
	}

	/**
	 * Get subscription ID.
	 *
	 * @see https://github.com/woothemes/woocommerce/blob/v2.1.3/includes/abstracts/abstract-wc-payment-gateway.php#L52
	 * @return string
	 */
	public function get_subscription_id() {
		if ( ! $this->get_subscription() ) {
			return;
		}

		$payment = get_pronamic_payment_by_meta( '_pronamic_payment_source_id', $this->get_subscription_source_id() );

		if ( ! $payment ) {
			return;
		}

		return $payment->get_meta( 'subscription_id' );
	}
}
