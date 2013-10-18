<?php

/**
 * Title: Buckaroo gateway
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Buckaroo_Buckaroo {
	const GATEWAY_URL = 'https://checkout.buckaroo.nl/html/';
	
	const GATEWAY_TEST_URL = 'https://testcheckout.buckaroo.nl/html/';
	
	//////////////////////////////////////////////////

	/**
	 * Indicator for the iDEAL payment method
	 * 
	 * @var string
	 */
	const PAYMENT_METHOD_IDEAL = 'ideal';
	
	//////////////////////////////////////////////////

	/**
	 * The payment server URL 
	 * 
	 * @var string
	 */
	private $payment_server_url;

	//////////////////////////////////////////////////

	/**
	 * The amount
	 *
	 * @var int
	 */
	private $amount;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize a iDEAL kassa object
	 */
	public function __construct() {
		$this->set_payment_method( self::PAYMENT_METHOD_IDEAL );
		$this->set_payment_server_url( self::GATEWAY_URL );
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

	public function get_website_key() {
		return $this->website_key;
	}

	public function set_website_key( $website_key ) {
		$this->website_key = $website_key;
	}

	//////////////////////////////////////////////////

	public function get_secret_key() {
		return $this->secret_key;
	}

	public function set_secret_key( $secret_key ) {
		$this->secret_key = $secret_key;
	}

	//////////////////////////////////////////////////

	public function get_payment_method() {
		return $this->payment_method;
	}

	public function set_payment_method( $payment_method ) {
		$this->payment_method = $payment_method;
	}

	//////////////////////////////////////////////////

	public function get_culture() {
		return $this->culture;
	}

	public function set_culture( $culture ) {
		$this->culture = $culture;
	}

	//////////////////////////////////////////////////

	public function get_currency() {
		return $this->currency;
	}

	public function set_currency( $currency ) {
		$this->currency = $currency;
	}
	
	//////////////////////////////////////////////////
	// Payment
	//////////////////////////////////////////////////

	public function get_invoice_number() {
		return $this->invoice_number;
	}

	public function set_invoice_number( $invoice_number ) {
		$this->invoice_number = $invoice_number;
	}

	//////////////////////////////////////////////////

	public function get_description() {
		return $this->description;
	}

	public function set_description( $description ) {
		$this->description = $description;
	}

	//////////////////////////////////////////////////

	public function get_amount() {
		return $this->amount;
	}

	public function set_amount( $amount ) {
		$this->amount = $amount;
	}

	//////////////////////////////////////////////////
	// URL's
	//////////////////////////////////////////////////

	/**
	 * Get return URL 
	 * 
	 * @reutnr string
	 */
	public function get_return_url() {
		return $this->return_url;
	}

	/**
	 * Set return URL
	 * 
	 * @param string $url
	 */
	public function set_return_url( $url ) {
		$this->return_url = $url;
	}

	//////////////////////////////////////////////////

	/**
	 * Get return reject URL 
	 * 
	 * @return string
	 */
	public function get_return_reject_url() {
		return $this->return_reject_url;
	}

	/**
	 * Set return reject URL
	 * 
	 * @param string $url
	 */
	public function set_return_reject_url( $url ) {
		$this->return_reject_url = $url;
	}

	//////////////////////////////////////////////////

	/**
	 * Get return error URL
	 * 
	 * @return string
	 */
	public function get_return_error_url() {
		return $this->return_error_url;
	}

	/**
	 * Set return error URL
	 * 
	 * @param string $url
	 */
	public function set_return_error_url( $url ) {
		$this->return_error_url = $url;
	}

	//////////////////////////////////////////////////

	/**
	 * Get return cancel URL
	 * 
	 * @return string
	 */
	public function get_return_cancel_url() {
		return $this->return_cancel_url;
	}

	/**
	 * Set return cancel URL
	 * 
	 * @param string $url
	 */
	public function set_return_cancel_url($url) {
		$this->return_cancel_url = $url;
	}

	//////////////////////////////////////////////////
	// Signature functions
	//////////////////////////////////////////////////

	public static function get_signature( $data, $secret_key ) {
		$string = '';

		ksort( $data );
		
		foreach ( $data as $key => $value ) {
			$string .= $key . '=' . $value;
		}
		
		$string .= $secret_key;
		
		return hash( 'sha1', $string );
	}

	//////////////////////////////////////////////////
	
	/**
	 * Get HTML fields
	 * 
	 * @return string
	 */
	public function get_html_fields() {
		$data = array(
			Pronamic_Gateways_Buckaroo_Parameters::WEBSITE_KEY       => $this->get_website_key(),
			Pronamic_Gateways_Buckaroo_Parameters::INVOICE_NUMBER    => $this->get_invoice_number(),
			Pronamic_Gateways_Buckaroo_Parameters::AMOUNT            => number_format( $this->get_amount(), 2, '.', '' ),
			Pronamic_Gateways_Buckaroo_Parameters::CURRENCY          => $this->get_currency(),
			Pronamic_Gateways_Buckaroo_Parameters::CULTURE           => $this->get_culture(),
			Pronamic_Gateways_Buckaroo_Parameters::DESCRIPTION       => $this->get_description(),
			Pronamic_Gateways_Buckaroo_Parameters::PAYMENT_METHOD    => $this->get_payment_method(),
			Pronamic_Gateways_Buckaroo_Parameters::RETURN_URL        => $this->get_return_url(),
			Pronamic_Gateways_Buckaroo_Parameters::RETURN_REJECT_URL => $this->get_return_reject_url(),
			Pronamic_Gateways_Buckaroo_Parameters::RETURN_ERROR_URL  => $this->get_return_error_url(),
			Pronamic_Gateways_Buckaroo_Parameters::RETURN_CANCEL_URL => $this->get_return_cancel_url()
		);
		
		$signature = self::get_signature( $data, $this->get_secret_key() );
		
		$data[Pronamic_Gateways_Buckaroo_Parameters::SIGNATURE] = $signature;

		return Pronamic_IDeal_IDeal::htmlHiddenFields( $data );
	}
	
	//////////////////////////////////////////////////

	/**
	 * Verify request  Buckaroo
	 */
	public function verify_request( $data ) {
		$result = false;

		/* 
		 * Please note: When verifying a received signature, first url-decode 
		 * all the field values. A signature is always calculated over the 
		 * non-encoded values (i.e The value “J.+de+Tester” should be decoded 
		 * to “J. de Tester”).
		 */
		$signature_key = null;

		foreach ( $data as $key => &$value ) {
			$value = urldecode( $value );

			if ( strcasecmp( $key, Pronamic_Gateways_Buckaroo_Parameters::SIGNATURE ) == 0 ) {
				$signature_key = $key;
			}
		}

		if ( isset( $data[$signature_key] ) ) {
			$signature = $data[$signature_key];
     
			unset( $data[$signature_key] );

			$signature_check = self::get_signature( $data, $this->get_secret_key() );

			if ( strcasecmp( $signature, $signature_check ) === 0 ) {
				$data = array_change_key_case( $data, CASE_LOWER );

				$result = filter_var_array( $data, array(
					Pronamic_Gateways_Buckaroo_Parameters::PAYMENT                       => FILTER_SANITIZE_STRING, 
					Pronamic_Gateways_Buckaroo_Parameters::PAYMENT_METHOD                => FILTER_SANITIZE_STRING, 
					Pronamic_Gateways_Buckaroo_Parameters::STATUS_CODE                   => FILTER_VALIDATE_INT,
					Pronamic_Gateways_Buckaroo_Parameters::STATUS_CODE_DETAIL            => FILTER_SANITIZE_STRING,
					Pronamic_Gateways_Buckaroo_Parameters::STATUS_MESSAGE                => FILTER_SANITIZE_STRING,
					Pronamic_Gateways_Buckaroo_Parameters::INVOICE_NUMBER                => FILTER_SANITIZE_STRING,
					Pronamic_Gateways_Buckaroo_Parameters::AMOUNT                        => FILTER_VALIDATE_FLOAT, 
					Pronamic_Gateways_Buckaroo_Parameters::CURRENCY                      => FILTER_SANITIZE_STRING,
					Pronamic_Gateways_Buckaroo_Parameters::TIMESTAMP                     => FILTER_SANITIZE_STRING, 
					Pronamic_Gateways_Buckaroo_Parameters::SERVICE_IDEAL_CONSUMER_ISSUER => FILTER_SANITIZE_STRING,
					Pronamic_Gateways_Buckaroo_Parameters::SERVICE_IDEAL_CONSUMER_NAME   => FILTER_SANITIZE_STRING,
					Pronamic_Gateways_Buckaroo_Parameters::SERVICE_IDEAL_CONSUMER_IBAN   => FILTER_SANITIZE_STRING,
					Pronamic_Gateways_Buckaroo_Parameters::SERVICE_IDEAL_CONSUMER_BIC    => FILTER_SANITIZE_STRING,
					Pronamic_Gateways_Buckaroo_Parameters::TRANSACTIONS                  => FILTER_SANITIZE_STRING 
				) );
			}
		}

		return $result;
	}
}
