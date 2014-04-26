<?php

/**
 * Title: Adyen gateway
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 * @see https://github.com/adyenpayments/php/blob/master/generatepaymentform.php
 */
class Pronamic_Gateways_Adyen_Adyen {
	/**
	 * The payment server URL
	 *
	 * @var string
	 */
	private $payment_server_url;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize a iDEAL kassa object
	 */
	public function __construct() {

	}

	//////////////////////////////////////////////////

	/**
	 * Get the payment server URL
	 *
	 * @return the payment server URL
	 */
	public function get_payment_server_url() {
		return $this->payment_server_url;
	}

	/**
	 * Set the payment server URL
	 *
	 * @param string $url an URL
	 */
	public function set_payment_server_url( $url ) {
		$this->payment_server_url = $url;
	}

	//////////////////////////////////////////////////

	public function get_merchant_reference() {
		return $this->merchant_reference;
	}

	public function set_merchant_reference( $merchant_reference ) {
		$this->merchant_reference = $merchant_reference;
	}

	//////////////////////////////////////////////////

	public function get_payment_amount() {
		return $this->payment_amount;
	}

	public function set_payment_amount( $payment_amount ) {
		$this->payment_amount = $payment_amount;
	}

	//////////////////////////////////////////////////

	public function get_currency_code() {
		return $this->currency_code;
	}

	public function set_currency_code( $currency_code ) {
		$this->currency_code = $currency_code;
	}

	//////////////////////////////////////////////////

	public function get_ship_before_date() {
		return $this->ship_before_date;
	}

	public function set_ship_before_date( DateTime $date ) {
		$this->ship_before_date = $date;
	}

	//////////////////////////////////////////////////

	public function get_skin_code() {
		return $this->skin_code;
	}

	public function set_skin_code( $skin_code ) {
		$this->skin_code = $skin_code;
	}

	//////////////////////////////////////////////////

	public function get_merchant_account() {
		return $this->merchant_account;
	}

	public function set_merchant_account( $merchant_account ) {
		$this->merchant_account = $merchant_account;
	}

	//////////////////////////////////////////////////

	public function get_shopper_locale() {
		return $this->shopper_locale;
	}

	public function set_shopper_locale( $shopper_locale ) {
		$this->shopper_locale = $shopper_locale;
	}

	//////////////////////////////////////////////////

	public function get_order_data() {
		return $this->order_data;
	}

	public function set_order_data( $order_data ) {
		$this->order_data = $order_data;
	}

	//////////////////////////////////////////////////

	public function get_session_validity() {
		return $this->session_validity;
	}

	public function set_session_validity( DateTime $session_validity = null ) {
		$this->session_validity = $session_validity;
	}

	//////////////////////////////////////////////////

	public function get_shopper_email() {
		return $this->shopper_email;
	}

	public function set_shopper_email( $email ) {
		$this->shopper_email = $email;
	}

	//////////////////////////////////////////////////

	public function get_shopper_reference() {
		return $this->shopper_reference;
	}

	public function set_shopper_reference( $reference ) {
		$this->shopper_reference = $reference;
	}

	//////////////////////////////////////////////////

	public function get_shared_secret() {
		return $this->shared_secret;
	}

	public function set_shared_secret( $shared_secret ) {
		$this->shared_secret = $shared_secret;
	}

	//////////////////////////////////////////////////

	public function get_signature() {
		$data = array(
			Pronamic_WP_Util::amount_to_cents( $this->get_payment_amount() ),
			$this->get_currency_code(),
			Pronamic_WP_Util::format_date( 'Y-m-d', $this->get_ship_before_date() ),
			$this->get_merchant_reference(),
			$this->get_skin_code(),
			$this->get_merchant_account(),
			Pronamic_WP_Util::format_date( DATE_ATOM, $this->get_session_validity() ),
			$this->get_shopper_email(),
			$this->get_shopper_reference(),
		);

		$data = implode( '', $data );

		$signature = base64_encode( hash_hmac( 'sha1', $data, $this->get_shared_secret(), true ) );

		return $signature;
	}

	//////////////////////////////////////////////////

	/**
	 * Get HTML fields
	 *
	 * @return string
	 */
	public function get_html_fields() {
		return Pronamic_IDeal_IDeal::htmlHiddenFields( array(
			Pronamic_Gateways_Adyen_Parameters::MERCHANT_REFERENCE => $this->get_merchant_reference(),
			Pronamic_Gateways_Adyen_Parameters::PAYMENT_AMOUNT     => Pronamic_WP_Util::amount_to_cents( $this->get_payment_amount() ),
			Pronamic_Gateways_Adyen_Parameters::CURRENCY_CODE      => $this->get_currency_code(),
			Pronamic_Gateways_Adyen_Parameters::SHIP_BEFORE_DATE   => Pronamic_WP_Util::format_date( 'Y-m-d', $this->get_ship_before_date() ),
			Pronamic_Gateways_Adyen_Parameters::SKIN_CODE          => $this->get_skin_code(),
			Pronamic_Gateways_Adyen_Parameters::MERCHANT_ACCOUNT   => $this->get_merchant_account(),
			Pronamic_Gateways_Adyen_Parameters::SHOPPER_LOCALE     => $this->get_shopper_locale(),
			Pronamic_Gateways_Adyen_Parameters::ORDER_DATA         => base64_encode( gzencode( $this->get_order_data() ) ),
			Pronamic_Gateways_Adyen_Parameters::SESSION_VALIDITY   => Pronamic_WP_Util::format_date( DATE_ATOM, $this->get_session_validity() ),
			Pronamic_Gateways_Adyen_Parameters::MERCHANT_SIGNATURE => $this->get_signature(),
			Pronamic_Gateways_Adyen_Parameters::SHOPPER_EMAIL      => $this->get_shopper_email(),
			Pronamic_Gateways_Adyen_Parameters::SHOPPER_REFERENCE  => $this->get_shopper_reference()
		) );
	}
}
