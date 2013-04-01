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
	private $paymentServerUrl;

	//////////////////////////////////////////////////

	/**
	 * The amount
	 *
	 * @var int
	 */
	private $amount;

	//////////////////////////////////////////////////

	/**
	 * Fields
	 * 
	 * @var array
	 */
	private $fields;

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
	public function getPaymentServerUrl() {
		return $this->paymentServerUrl;
	}
	
	/**
	 * Set the payment server URL
	 *
	 * @param string $url an URL
	 */
	public function setPaymentServerUrl($url) {
		$this->paymentServerUrl = $url;
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


	
	// Calculation parameters
	//////////////////////////////////////////////////

	/**
	 * Get calculations parameters IN
	 * 
	 * @return array
	 */
	public function getCalculationsParametersIn() {
		return $this->calculationsParametersIn;
	}

	/**
	 * Set calculations parameters IN
	 * 
	 * @param array $parameters
	 */
	public function setCalculationsParametersIn(array $parameters) {
		$this->calculationsParametersIn = $parameters;
	}

	//////////////////////////////////////////////////

	/**
	 * Get calculations parameters OUT
	 * 
	 * @return array
	 */
	public function getCalculationsParametersOut() {
		return $this->calculationsParametersOut;
	}

	/**
	 * Set calculations parameters OUT
	 * 
	 * @param array $parameters
	 */
	public function setCalculationsParametersOut(array $parameters) {
		$this->calculationsParametersOut = $parameters;
	}

	//////////////////////////////////////////////////
	// Fields
	//////////////////////////////////////////////////

	/**
	 * Get field by the specifiek name
	 * 
	 * @param string $name
	 */
	public function getField($name) {
		$value = null;

		if(isset($this->fields[$name])) {
			$value = $this->fields[$name];
		}

		return $value;
	}

	/**
	 * Set field
	 * 
	 * @param string $name
	 * @param string $value
	 */
	public function setField($name, $value) {
		$this->fields[$name] = $value;
	}

	//////////////////////////////////////////////////
	// Fields helper functinos
	//////////////////////////////////////////////////

	/**
	 * Get the PSP id
	 *
	 * @return an PSP id
	 */
	// public function getPspId() {
  //		return $this->getField(Pronamic_Gateways_Buckaroo_Parameters::PSPID);
	// }

	/**
	 * Set the PSP id
	 * 
	 * Your affiliation name in our system, chosen by yourself when opening your account 
	 * with us. This is a unique identifier and can’t ever be changed. 
	 *
	 * @param string PSP id
	 */
// 	public function setPspId($pspId) {
// 		$this->setField(Pronamic_Gateways_Buckaroo_Parameters::PSPID, $pspId);
// 	}

 //////////////////////////////////////////////////
  // Added for Buckaroo

	public function getMerchantId() {
		return $this->getField(Pronamic_Gateways_Buckaroo_Parameters::MERCHANTID);
	}

	public function setMerchantId($merchantId) {
		$this->setField(Pronamic_Gateways_Buckaroo_Parameters::MERCHANTID, $merchantId);
	}



	public function gethashKey() {
	 // Buckaroo Debugging 
//	  echo " </br> <strong>Dit is het BuckaroohashKey function getHashKey  </strong> $hashKey </br>";
		return $this->getField(Pronamic_Gateways_Buckaroo_Parameters::HASHKEY);
	}

	public function sethashKey($hashKey) {
		$this->setField(Pronamic_Gateways_Buckaroo_Parameters::HASHKEY, $hashKey);
	}
  	
	// End Added for Buckaroo
	//////////////////////////

	//////////////////////////////////////////////////

	/**
	 * Get the order id
	 *
	 * @return an order id
	 */
	public function getOrderId() {
		return $this->getField(Pronamic_Gateways_Buckaroo_Parameters::ORDERID);
	}

	/**
	 * Set the order id
	 *
	 * @param string $orderId
	 */
	public function setOrderId($orderId) {
		$this->setField(Pronamic_Gateways_Buckaroo_Parameters::ORDERID, $orderId);
	}

	//////////////////////////////////////////////////

	/**
	 * Get the language
	 *
	 * @return an language
	 */
	public function getLanguage() {
		return $this->getField(Pronamic_Gateways_Buckaroo_Parameters::LANGUAGE);
	}

	/**
	 * Set the language
	 * 
	 * The format is "language_Country".
	 * The language value is based on ISO 639-1.
	 * The country value is based on ISO 3166-1.
	 *
	 * @param string $language
	 */
	public function setLanguage($language) {
		$this->setField(Pronamic_Gateways_Buckaroo_Parameters::LANGUAGE, $language);
	}

	//////////////////////////////////////////////////
	
	/**
	 * Get the currency
	 *
	 * @return string
	 */
	public function getCurrency() {
		return $this->getField(Pronamic_Gateways_Buckaroo_Parameters::CURRENCY);
	}
	
	/**
	 * Set the currency
	 * 
	 * Currency of the amount in alphabetic ISO code as can be found on 
	 * http://www.currency-iso.org/iso_index/iso_tables/iso_tables_a1.htm
	 *
	 * @return string $currency
	 */
	public function setCurrency($currency) {
		$this->setField(Pronamic_Gateways_Buckaroo_Parameters::CURRENCY, $currency);
	}

	//////////////////////////////////////////////////

	/**
	 * Get the amount
	 * 
	 * @return float
	 */
	public function getAmount() {
		return $this->amount;
	}

	/**
	 * Set the amount
	 * 
	 * @param float $amount
	 */
	public function setAmount($amount) {
		$this->amount = $amount;

		$this->setField(Pronamic_Gateways_Buckaroo_Parameters::AMOUNT, $amount);
	}

	//////////////////////////////////////////////////

	/**
	 * Get order description
	 * 
	 * The com field is sometimes transmitted to the acquirer (depending on the acquirer), 
	 * in order to be shown on the account statements of the merchant or the customer. 
	 * 
	 * @return string
	 */
	public function getOrderDescription() {
		return $this->getField(Pronamic_Gateways_Buckaroo_Parameters::COM);
	}

	/**
	 * Set order description
	 * 
	 * The com field is sometimes transmitted to the acquirer (depending on the acquirer), 
	 * in order to be shown on the account statements of the merchant or the customer. 
	 * 
	 * @param string $description
	 */
	public function setOrderDescription($description) {
		$this->setField(Pronamic_Gateways_Buckaroo_Parameters::COM, $description);
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
