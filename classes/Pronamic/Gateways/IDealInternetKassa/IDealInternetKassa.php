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
	//////////////////////////////////////////////////
	// Parameters
	//////////////////////////////////////////////////

	/**
	 * Indicator for the PSPID parameter
	 * 
	 * @var string
	 */
	const PARAMETER_PSPID = 'PSPID';

	/**
	 * Indicator for the ORDERID parameter
	 * 
	 * @var string
	 */
	const PARAMETER_ORDERID = 'ORDERID';

	/**
	 * Indicator for the AMOUNT parameter
	 * 
	 * @var string
	 */
	const PARAMETER_AMOUNT = 'AMOUNT';

	/**
	 * Indicator for the CURRENCY parameter
	 * 
	 * @var string
	 */
	const PARAMETER_CURRENCY = 'CURRENCY';

	/**
	 * Indicator for the AMOUNT parameter
	 * 
	 * @var string
	 */
	const PARAMETER_LANGUAGE = 'LANGUAGE';

	//////////////////////////////////////////////////
	// Parameters
	//////////////////////////////////////////////////

	/**
	 * Indicator for the STATUS parameter
	 * 
	 * @var string
	 */
	const PARAMETER_STATUS = 'STATUS';

	//////////////////////////////////////////////////

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
	 * The PSP ID
	 * 
	 * @var string
	 */
	private $pspId;

	/**
	 * The order ID
	 * 
	 * @var string
	 */
	private $orderId;

	/**
	 * The amount
	 *
	 * @var int
	 */
	private $amount;

	/**
	 * The currency
	 *
	 * @var string
	 */
	private $currency;

	/**
	 * The language
	 * 
	 * @var string
	 */
	private $language;

	//////////////////////////////////////////////////

	/**
	 * Name of the customer
	 * 
	 * @var string
	 */
	private $customerName;

	/**
	 * E-mailaddress
	 * 
	 * @var string
	 */
	private $eMailAddress;

	//////////////////////////////////////////////////
	
	/**
	 * Owner address
	 * 
	 * @var string
	 */
	private $ownerAddress;

	/**
	 * Owner city
	 * 
	 * @var string
	 */
	private $ownerCity;

	/**
	 * Owner ZIP
	 * 
	 * @var string
	 */
	private $ownerZip;

	//////////////////////////////////////////////////

	/**
	 * Calculations parameters SHA-IN
	 * 
	 * @var array
	 */
	public $calculationsParametersIn;

	/**
	 * Calculations parameters SHA-OUT
	 * 
	 * @var array
	 */
	public $calculationsParametersOut;

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

		$this->calculationsParametersIn = array();
		$this->calculationsParametersOut = array();

		$this->hashAlgorithm = self::HASH_ALGORITHM_SHA_1;
	}

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
	 * Get the PSP id
	 *
	 * @return an PSP id
	 */
	public function getPspId() {
		return $this->getField(self::PARAMETER_PSPID);
	}

	/**
	 * Set the PSP id
	 *
	 * @param PSP id
	 */
	public function setPspId($pspId) {
		$this->setField(self::PARAMETER_PSPID, $pspId);
	}

	//////////////////////////////////////////////////

	/**
	 * Get the order id
	 *
	 * @return an order id
	 */
	public function getOrderId() {
		return $this->getField(self::PARAMETER_ORDERID);
	}

	/**
	 * Set the order id
	 *
	 * @param sub id
	 */
	public function setOrderId($orderId) {
		$this->setField(self::PARAMETER_ORDERID, $orderId);
	}

	//////////////////////////////////////////////////

	/**
	 * Get the language
	 *
	 * @return an language
	 */
	public function getLanguage() {
		return $this->getField(self::PARAMETER_LANGUAGE);
	}

	/**
	 * Set the language
	 *
	 * @param string $language
	 */
	public function setLanguage($language) {
		$this->setField(self::PARAMETER_LANGUAGE, $language);
	}

	//////////////////////////////////////////////////
	
	/**
	 * Get the currency
	 *
	 * @return string
	 */
	public function getCurrency() {
		return $this->getField(self::PARAMETER_CURRENCY);
	}
	
	/**
	 * Set the currency
	 *
	 * @return string
	 */
	public function setCurrency($currency) {
		$this->setField(self::PARAMETER_CURRENCY, $currency);
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

		$this->setField(self::PARAMETER_AMOUNT, Pronamic_IDeal_IDeal::formatPrice($amount));
	}

	//////////////////////////////////////////////////

	public function getCustomerName() {
		return $this->customerName;
	}

	public function setCustomerName($customerName) {
		$this->customerName = $customerName;
	}

	//////////////////////////////////////////////////

	public function getEMailAddress() {
		return $this->eMailAddress;
	}

	public function setEMailAddress($eMailAddress) {
		$this->eMailAddress = $eMailAddress;
	}

	//////////////////////////////////////////////////

	public function getOwnerAddress() {
		return $this->ownerAddress;
	}

	public function setOwnerAddress($ownerAddress) {
		$this->ownerAddress = $ownerAddress;
	}

	//////////////////////////////////////////////////

	public function getOwnerCity() {
		return $this->ownerCity;
	}

	public function setOwnerCity($ownerCity) {
		$this->ownerCity = $ownerCity;
	}

	//////////////////////////////////////////////////

	public function getOwnerZip() {
		return $this->ownerZip;
	}

	public function setOwnerZip($ownerZip) {
		$this->ownerZip = $ownerZip;
	}

	//////////////////////////////////////////////////

	public function getPassPhraseIn() {
		return $this->passPhraseIn;
	}

	public function setPassPhraseIn($passPhraseIn) {
		$this->passPhraseIn = $passPhraseIn;
	}

	public function getPassPhraseOut() {
		return $this->passPhraseOut;
	}

	public function setPassPhraseOut($passPhraseOut) {
		$this->passPhraseOut = $passPhraseOut;
	}

	//////////////////////////////////////////////////

	public function getHashAlgorithm() {
		return $this->hashAlgorithm;
	}

	public function setHashAlgorithm($hashAlgorithm) {
		$this->hashAlgorithm = $hashAlgorithm;
	}

	//////////////////////////////////////////////////

	public function getShaInFields($fields) {
		$calculationsParameters = array_flip($this->calculationsParametersIn);

		return array_intersect_key($fields, $calculationsParameters);
	}

	public function getShaOutFields($fields) {
		$calculationsParameters = array_flip($this->calculationsParametersOut);

		return array_intersect_key($fields, $calculationsParameters);
	}

	public function getShaSign($fields, $passprahse) {
		// This string is constructed by concatenating the values of the fields sent with the order (sorted
		// alphabetically, in the format ‘parameter=value’), separated by a passphrase.		
		$string = '';

		// all parameters need to be put alphabetically
		ksort($fields);

		foreach($fields as $name => $value) {
			// all parameters need to be put alphabetically
			if(!empty($value)) {
				// all parameters need to be put alphabetically
				$name = strtoupper($name);
		
				$string .= $name . '=' . $value . $passprahse;
			}
		}

		// SHA sign
		$result = hash($this->hashAlgorithm, $string);

		// String to uppercase
		$result = strtoupper($result);

		return $result;
	}

	//////////////////////////////////////////////////

	public function getShaInSign() {
		$fields = $this->getShaInFields($this->parameters);

		return $this->getShaSign($fields, $this->getPassPhraseIn());
	}

	public function getShaOutSign($fields) {
		$fields = $this->getShaOutFields($fields);

		return $this->getShaSign($fields, $this->getPassPhraseOut());
	}

	//////////////////////////////////////////////////

	/**
	 * Get the iDEAL HTML
	 */
	public function getHtmlFields() {
		return Pronamic_IDeal_IDeal::htmlHiddenFields(array(
			// general parameters
			'PSPID' => $this->getPspId() ,
			'orderID' => $this->getOrderId() , 
			'amount' => Pronamic_IDeal_IDeal::formatPrice($this->getAmount()) , 
			'currency' => $this->getCurrency() , 
			'language' => $this->getLanguage() , 
		
			'CN' => $this->getCustomerName() , 
			'EMAIL' => $this->getEMailAddress() ,

			'ownerZIP' => $this->getEMailAddress() , 
			'owneraddress' => $this->getEMailAddress() , 
			'ownercty' => $this->getEMailAddress() , 
			'ownertown' => $this->getEMailAddress() , 
			'ownertelno' => $this->getEMailAddress() , 

			// check before the payment: see Security: Check before the Payment
			'SHASign' => $this->getShaInSign() , 

			// post payment redirection: see Transaction Feedback to the Customer
			'accepturl' => '' , 
			'declineurl' => '' , 
			'exceptionurl' => '' , 
			'cancelurl' => '' 
		));
	}

	//////////////////////////////////////////////////

	public function validate() {
		if(isset($_GET)) {
			$fields = array_change_key_case($_GET, CASE_UPPER);

			$hash = $this->getShaOutSign($fields);

			$shasign = filter_input(INPUT_GET, 'SHASIGN', FILTER_SANITIZE_STRING);

			if($shasign == $hash) {
				$status = filter_input(INPUT_GET, self::PARAMETER_STATUS, FILTER_VALIDATE_INT);

				var_dump($status);
			} 
		}
	}
}
