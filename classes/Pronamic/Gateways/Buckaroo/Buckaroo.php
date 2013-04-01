<?php

/**
 * Title: iDEAL Internet Kassa gateway
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Buckaroo_Buckaroo {
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
		$this->fields = array();
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
	// URL's
	//////////////////////////////////////////////////

	/**
	 * Get accept URL
	 * 
	 * URL of the web page to show the customer when the payment is authorized. 
	 * 
	 * @reutnr string
	 */
	public function getAcceptUrl() {
		return $this->getField(Pronamic_Gateways_Buckaroo_Parameters::ACCEPT_URL);
	}

	/**
	 * Set accept URL
	 * 
	 * URL of the web page to show the customer when the payment is authorized. 
	 * 
	 * @param string $url
	 */
	public function setAcceptUrl($url) {
		$this->setField(Pronamic_Gateways_Buckaroo_Parameters::ACCEPT_URL, $url);
	}

	//////////////////////////////////////////////////

	/**
	 * Get cancel URL
	 * 
	 * URL of the web page to show the customer when he cancels the payment. 
	 * 
	 * @return string
	 */
	public function getCancelUrl() {
		return $this->getField(Pronamic_Gateways_Buckaroo_Parameters::CANCEL_URL);
	}

	/**
	 * Set cancel URL
	 * 
	 * URL of the web page to show the customer when he cancels the payment. 
	 * 
	 * @param string $url
	 */
	public function setCancelUrl($url) {
		$this->setField(Pronamic_Gateways_Buckaroo_Parameters::CANCEL_URL, $url);
	}

	//////////////////////////////////////////////////

	/**
	 * Get exception URL
	 * 
	 * URL of the web page to show the customer when the payment result is uncertain.
	 * 
	 * @return string
	 */
	public function getExceptionUrl() {
		return $this->getField(Pronamic_Gateways_Buckaroo_Parameters::EXCEPTION_URL);
	}

	/**
	 * Set exception URL
	 * 
	 * URL of the web page to show the customer when the payment result is uncertain.
	 * 
	 * @param string $url
	 */
	public function setExceptionUrl($url) {
		$this->setField(Pronamic_Gateways_Buckaroo_Parameters::EXCEPTION_URL, $url);
	}

	//////////////////////////////////////////////////

	/**
	 * Get decline URL
	 * 
	 * URL of the web page to show the customer when the acquirer rejects the authorisation more 
	 * than the maximum of authorised tries (10 by default, but can be changed in the technical 
	 * information page). 
	 * 
	 * @return string
	 */
	public function getDeclineUrl() {
		return $this->getField(Pronamic_Gateways_Buckaroo_Parameters::DECLINE_URL);
	}

	/**
	 * Set decline URL
	 * 
	 * URL of the web page to show the customer when the acquirer rejects the authorisation more 
	 * than the maximum of authorised tries (10 by default, but can be changed in the technical 
	 * information page). 
	 * 
	 * @param string $url
	 */
	public function setDeclineUrl($url) {
		$this->setField(Pronamic_Gateways_Buckaroo_Parameters::DECLINE_URL, $url);
	}

	//////////////////////////////////////////////////
	// Signature functions
	//////////////////////////////////////////////////

	public function getSignature( $data, $secreteKey ) {
		$string = '';

		$data = array_change_key_case( $data, CASE_LOWER );
		
		ksort( $data );
		
		foreach ( $data as $key => $value ) {
			$string .= $key . '=' . $value;
		}
		
		$string .= $secretKey;
		
		return hash( 'sha1', $string );
	}
	
	/**
	 * Get HTML fields
	 * 
	 * @return string
	 */
	public function getHtmlFields() {
		$data = array(
			'brq_websitekey'           => $this->gethashKey(),
			'brq_invoicenumber'        => $this->getOrderId(),
			'brq_amount'               => $this->getAmount(),
			'brq_currency'             => $this->getCurrency(),
			'brq_culture'              => $this->getLanguage(),
			'brq_description'          => $this->getOrderDescription(),
			'brq_payment_method'       => 'ideal',
			'brq_service_ideal_action' => 'Pay',
			'brq_return'               => $this->getAcceptUrl(),
			'brq_returnreject'         => $this->getDeclineUrl(),
			'brq_returnerror'          => $this->getExceptionUrl(),
			'brq_returncancel'         => $this->getCancelUrl()
		);
		
		$signature = $this->getSignature( $data, $this->getMerchantId() );
		
		$data['brq_signature'] = $signature;

		return Pronamic_IDeal_IDeal::htmlHiddenFields( $data );
	}
	
	//////////////////////////////////////////////////

	/**
	 * Verify request  Buckaroo
	 */
	public function verifyRequest( $data ) {
		$result = false;

		if ( isset( $data['brq_signature'] ) ) {
			$signature = $data['brq_signature'];
     
			unset( $data['brq_signature'] );

			$signatureCheck = $this->getSignature( $data, $this->getMerchantId() );

			if ( strcasecmp( $signature, $signatureCheck ) === 0 ) {
				$result = filter_var_array( $data, array(
					Pronamic_Gateways_Buckaroo_Parameters::ORDERID  => FILTER_SANITIZE_STRING,
					Pronamic_Gateways_Buckaroo_Parameters::AMOUNT   => FILTER_VALIDATE_FLOAT, 
					Pronamic_Gateways_Buckaroo_Parameters::CURRENCY => FILTER_SANITIZE_STRING,
					'brq_payment'                                   => FILTER_SANITIZE_STRING, 
					'brq_statusmessage'                             => FILTER_SANITIZE_STRING, 
					'brq_statuscode'                                => FILTER_VALIDATE_INT,
					'brq_SERVICE_ideal_consumerIBAN'                => FILTER_SANITIZE_STRING, 
					'brq_SERVICE_ideal_consumerIssuer'              => FILTER_SANITIZE_STRING, 
					'brq_signature'                                 => FILTER_SANITIZE_STRING 
				) );
			} 
		}

		return $result;
	}
}
