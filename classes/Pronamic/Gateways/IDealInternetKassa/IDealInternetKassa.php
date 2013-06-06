<?php

/**
 * Title: iDEAL Internet Kassa gateway
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealInternetKassa_IDealInternetKassa {
	/**
	 * Indicator for hash algorithm SHA-1
	 * 
	 * @var string
	 */
	const HASH_ALGORITHM_SHA_1 = 'sha1';

	/**
	 * Indicator for hash algorithm SHA-256
	 * 
	 * @var string
	 */
	const HASH_ALGORITHM_SHA_256 = 'sha256';

	/**
	 * Indicator for hash algorithm SHA-512
	 * 
	 * @var string
	 */
	const HASH_ALGORITHM_SHA_512 = 'sha512';

	//////////////////////////////////////////////////

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
	 * Signature parameters IN
	 * 
	 * @var array
	 */
	private $calculationsParametersIn;

	/**
	 * Signature parameters OUT
	 * 
	 * @var array
	 */
	private $calculationsParametersOut;

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

		$this->calculationsParametersIn  = array();
		$this->calculationsParametersOut = array();

		$file = Pronamic_WordPress_IDeal_Plugin::$dirname . '/other/calculations-parameters-sha-in.txt';
		if ( is_readable( $file ) ) {
			$this->setCalculationsParametersIn( file( $file, FILE_IGNORE_NEW_LINES ) );
		}

		$file = Pronamic_WordPress_IDeal_Plugin::$dirname . '/other/calculations-parameters-sha-out.txt';
		if ( is_readable( $file ) ) {
			$ideal->setCalculationsParametersOut( file( $file, FILE_IGNORE_NEW_LINES ) );
		}

		$this->hash_algorithm = self::HASH_ALGORITHM_SHA_1;
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

	/**
	 * Get hash algorithm
	 * 
	 * @return string
	 */
	public function get_hash_algorithm() {
		return $this->hash_algorithm;
	}

	/**
	 * Set hash algorithm
	 * 
	 * @param string $hashAlgorithm
	 */
	public function set_hash_algorithm( $hash_algorithm ) {
		$this->hash_algorithm = $hash_algorithm;
	}

	//////////////////////////////////////////////////

	/**
	 * Get password phrase IN
	 * 
	 * @return string
	 */
	public function getPassPhraseIn() {
		return $this->passPhraseIn;
	}

	/**
	 * Set password phrase IN
	 * 
	 * @param string $passPhraseIn
	 */
	public function setPassPhraseIn($passPhraseIn) {
		$this->passPhraseIn = $passPhraseIn;
	}

	//////////////////////////////////////////////////

	/**
	 * Get password phrase OUT
	 * 
	 * @return string
	 */
	public function getPassPhraseOut() {
		return $this->passPhraseOut;
	}

	/**
	 * Set password phrase OUT
	 * 
	 * @param string $passPhraseOut
	 */
	public function setPassPhraseOut($passPhraseOut) {
		$this->passPhraseOut = $passPhraseOut;
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
	public function getPspId() {
		return $this->getField(Pronamic_Gateways_IDealInternetKassa_Parameters::PSPID);
	}

	/**
	 * Set the PSP id
	 * 
	 * Your affiliation name in our system, chosen by yourself when opening your account 
	 * with us. This is a unique identifier and can’t ever be changed. 
	 *
	 * @param string PSP id
	 */
	public function setPspId($pspId) {
		$this->setField(Pronamic_Gateways_IDealInternetKassa_Parameters::PSPID, $pspId);
	}

	//////////////////////////////////////////////////

	/**
	 * Get the order id
	 *
	 * @return an order id
	 */
	public function getOrderId() {
		return $this->getField(Pronamic_Gateways_IDealInternetKassa_Parameters::ORDERID);
	}

	/**
	 * Set the order id
	 *
	 * @param string $orderId
	 */
	public function setOrderId($orderId) {
		$this->setField(Pronamic_Gateways_IDealInternetKassa_Parameters::ORDERID, $orderId);
	}

	//////////////////////////////////////////////////

	/**
	 * Get the language
	 *
	 * @return an language
	 */
	public function getLanguage() {
		return $this->getField(Pronamic_Gateways_IDealInternetKassa_Parameters::LANGUAGE);
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
		$this->setField(Pronamic_Gateways_IDealInternetKassa_Parameters::LANGUAGE, $language);
	}

	//////////////////////////////////////////////////
	
	/**
	 * Get the currency
	 *
	 * @return string
	 */
	public function getCurrency() {
		return $this->getField(Pronamic_Gateways_IDealInternetKassa_Parameters::CURRENCY);
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
		$this->setField(Pronamic_Gateways_IDealInternetKassa_Parameters::CURRENCY, $currency);
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

		$this->setField(Pronamic_Gateways_IDealInternetKassa_Parameters::AMOUNT, Pronamic_WordPress_Util::amount_to_cents($amount));
	}

	//////////////////////////////////////////////////

	/**
	 * Get customer name
	 * 
	 * @return string
	 */
	public function getCustomerName() {
		return $this->getField(Pronamic_Gateways_IDealInternetKassa_Parameters::CUSTOMER_NAME);
	}

	/**
	 * Set customer name
	 * 
	 * Special characters are allowed, but quotes must be avoided. Most acquirers don’t check the 
	 * customer name since names can be written in different ways. 
	 * 
	 * @param string $customerName
	 */
	public function setCustomerName($customerName) {
		$this->setField(Pronamic_Gateways_IDealInternetKassa_Parameters::CUSTOMER_NAME, $customerName);
	}

	//////////////////////////////////////////////////

	/**
	 * Get e-mailaddress
	 * 
	 * @return string
	 */
	public function getEMailAddress() {
		return $this->getField(Pronamic_Gateways_IDealInternetKassa_Parameters::EMAIL);
	}

	/**
	 * Set e-mailaddress
	 * 
	 * @param string $eMailAddress
	 */
	public function setEMailAddress($eMailAddress) {
		$this->setField(Pronamic_Gateways_IDealInternetKassa_Parameters::EMAIL, $eMailAddress);
	}

	//////////////////////////////////////////////////

	/**
	 * Get owner addresss
	 * 
	 * @return string
	 */
	public function getOwnerAddress() {
		return $this->getField(Pronamic_Gateways_IDealInternetKassa_Parameters::OWNER_ADDRESS);
	}

	/**
	 * Set owner address
	 * 
	 * @param string $ownerAddress
	 */
	public function setOwnerAddress($ownerAddress) {
		$this->setField(Pronamic_Gateways_IDealInternetKassa_Parameters::OWNER_ADDRESS, $ownerAddress);
	}

	//////////////////////////////////////////////////

	/**
	 * Get owner country
	 * 
	 * @return string
	 */
	public function getOwnerCountry() {
		return $this->getField(Pronamic_Gateways_IDealInternetKassa_Parameters::OWNER_COUNTRY);
	}

	/**
	 * Set owner country
	 * 
	 * Country in ISO 3166-1-alpha-2 code as can be found on http://www.iso.org/iso/country_codes/iso_3166_code_lists.htm 
	 * 
	 * @param string $ownerCountry
	 */
	public function setOwnerCountry($ownerCountry) {
		$this->setField(Pronamic_Gateways_IDealInternetKassa_Parameters::OWNER_COUNTRY, $ownerCountry);
	}

	//////////////////////////////////////////////////

	/**
	 * Get owner ZIP
	 * 
	 * @return string
	 */
	public function getOwnerZip() {
		return $this->getField(Pronamic_Gateways_IDealInternetKassa_Parameters::OWNER_ZIP);
	}

	/**
	 * Set owner ZIP
	 * 
	 * @param string $ownerZip
	 */
	public function setOwnerZip($ownerZip) {
		$this->setField(Pronamic_Gateways_IDealInternetKassa_Parameters::OWNER_ZIP, $ownerZip);
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
		return $this->getField(Pronamic_Gateways_IDealInternetKassa_Parameters::COM);
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
		$this->setField(Pronamic_Gateways_IDealInternetKassa_Parameters::COM, $description);
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
		return $this->getField(Pronamic_Gateways_IDealInternetKassa_Parameters::ACCEPT_URL);
	}

	/**
	 * Set accept URL
	 * 
	 * URL of the web page to show the customer when the payment is authorized. 
	 * 
	 * @param string $url
	 */
	public function setAcceptUrl($url) {
		$this->setField(Pronamic_Gateways_IDealInternetKassa_Parameters::ACCEPT_URL, $url);
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
		return $this->getField(Pronamic_Gateways_IDealInternetKassa_Parameters::CANCEL_URL);
	}

	/**
	 * Set cancel URL
	 * 
	 * URL of the web page to show the customer when he cancels the payment. 
	 * 
	 * @param string $url
	 */
	public function setCancelUrl($url) {
		$this->setField(Pronamic_Gateways_IDealInternetKassa_Parameters::CANCEL_URL, $url);
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
		return $this->getField(Pronamic_Gateways_IDealInternetKassa_Parameters::EXCEPTION_URL);
	}

	/**
	 * Set exception URL
	 * 
	 * URL of the web page to show the customer when the payment result is uncertain.
	 * 
	 * @param string $url
	 */
	public function setExceptionUrl($url) {
		$this->setField(Pronamic_Gateways_IDealInternetKassa_Parameters::EXCEPTION_URL, $url);
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
		return $this->getField(Pronamic_Gateways_IDealInternetKassa_Parameters::DECLINE_URL);
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
		$this->setField(Pronamic_Gateways_IDealInternetKassa_Parameters::DECLINE_URL, $url);
	}

	//////////////////////////////////////////////////
	// Signature functions
	//////////////////////////////////////////////////

	/**
	 * Get signature fields IN
	 * 
	 * @param array $fields
	 * @return array
	 */
	private function getSignatureFieldsIn($fields) {
		$calculationsParameters = array_flip( $this->calculationsParametersIn );

		return array_intersect_key( $fields, $calculationsParameters );
	}

	/**
	 * Get signature fields OUT
	 * 
	 * @param array $fields
	 * @return array
	 */
	private function getSignatureFieldsOut( $fields ) {
		$calculationsParameters = array_flip( $this->calculationsParametersOut );

		return array_intersect_key( $fields, $calculationsParameters );
	}

	//////////////////////////////////////////////////

	/**
	 * Get signature
	 * 
	 * @param array $fields
	 * @param string $passprahse
	 * @return string
	 */
	private function getSignature( $fields, $passprahse ) {
		// This string is constructed by concatenating the values of the fields sent with the order (sorted
		// alphabetically, in the format ‘parameter=value’), separated by a passphrase.		
		$string = '';

		// All parameters need to be put alphabetically
		ksort( $fields );

		// Loop
		foreach ( $fields as $name => $value ) {
			$value = (string) $value;

			// Use of empty will fail, value can be string '0'
			if ( strlen( $value ) > 0 ) {
				$name = strtoupper( $name );
		
				$string .= $name . '=' . $value . $passprahse;
			}
		}

		// Hash
		$result = hash( $this->hash_algorithm, $string );

		// String to uppercase
		$result = strtoupper( $result );

		return $result;
	}

	//////////////////////////////////////////////////

	/**
	 * Get signature IN
	 * 
	 * @return string
	 */
	public function getSignatureIn() {
		$fields = $this->getSignatureFieldsIn( $this->fields );

		return $this->getSignature( $fields, $this->getPassPhraseIn() );
	}

	/**
	 * Get signature OUT
	 * 
	 * @param array $fields
	 */
	public function getSignatureOut( $fields ) {
		$fields = $this->getSignatureFieldsOut( $fields );

		return $this->getSignature( $fields, $this->getPassPhraseOut() );
	}

	//////////////////////////////////////////////////

	/**
	 * Get HTML fields
	 * 
	 * @return string
	 */
	public function getHtmlFields() {
		return Pronamic_IDeal_IDeal::htmlHiddenFields( array(
			// general parameters
			'PSPID'        => $this->getPspId(),
			'orderID'      => $this->getOrderId(), 
			'amount'       => Pronamic_WordPress_Util::amount_to_cents( $this->getAmount() ), 
			'currency'     => $this->getCurrency(),
			'language'     => $this->getLanguage(),
		
			'CN'           => $this->getCustomerName(), 
			'EMAIL'        => $this->getEMailAddress(),

			'owneraddress' => $this->getOwnerAddress(),
			'ownerZIP'     => $this->getOwnerZip(),
			'ownertown'    => '',
			'ownercty'     => $this->getOwnerCountry(),  
			'ownertelno'   => '',

			'COM'          => $this->getOrderDescription(), 

			// check before the payment: see Security: Check before the Payment
			'SHASign'      => $this->getSignatureIn(),

			// layout information: see Look and Feel of the Payment Page
			// ?

			// post payment redirection: see Transaction Feedback to the Customer
			'accepturl'    => $this->getAcceptUrl(),
			'declineurl'   => $this->getDeclineUrl(),
			'exceptionurl' => $this->getExceptionUrl(),
			'cancelurl'    => $this->getCancelUrl()
		) );
	}

	//////////////////////////////////////////////////

	/**
	 * Verify request
	 */
	public function verifyRequest( $data ) {
		$result = false;

		$data = array_change_key_case( $data, CASE_UPPER );

		if ( isset( $data['SHASIGN'] ) ) {
			$signature = $data['SHASIGN'];

			$signatureOut = $this->getSignatureOut( $data );

			if ( strcasecmp( $signature, $signatureOut ) === 0 ) {
				$result = filter_var_array( $data, array(
					Pronamic_Gateways_IDealInternetKassa_Parameters::ORDERID  => FILTER_SANITIZE_STRING,
					Pronamic_Gateways_IDealInternetKassa_Parameters::AMOUNT   => FILTER_VALIDATE_FLOAT, 
					Pronamic_Gateways_IDealInternetKassa_Parameters::CURRENCY => FILTER_SANITIZE_STRING,
					'PM'         => FILTER_SANITIZE_STRING, 
					'ACCEPTANCE' => FILTER_SANITIZE_STRING, 
					'STATUS'     => FILTER_VALIDATE_INT,
					'CARDNO'     => FILTER_SANITIZE_STRING, 
					'PAYID'      => FILTER_VALIDATE_INT,
					'NCERROR'    => FILTER_SANITIZE_STRING, 
					'BRAND'      => FILTER_SANITIZE_STRING, 
					'SHASIGN'    => FILTER_SANITIZE_STRING 
				) );
			} 
		}
		
		return $result;
	}
}
