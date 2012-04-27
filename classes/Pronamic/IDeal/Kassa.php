<?php

/**
 * Title: Kassa
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_IDeal_Kassa extends Pronamic_IDeal_IDeal {
	const PARAMETER_PSPID = 'PSPID';

	const PARAMETER_ORDERID = 'ORDERID';

	const PARAMETER_AMOUNT = 'AMOUNT';

	const PARAMETER_CURRENCY = 'CURRENCY';

	const PARAMETER_LANGUAGE = 'LANGUAGE';

	//////////////////////////////////////////////////

	/**
	 * The URL for testing 
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
	public $calculationsParametersShaIn;

	/**
	 * Calculations parameters SHA-OUT
	 * 
	 * @var array
	 */
	public $calculationsParametersShaOut;

	//////////////////////////////////////////////////

	/**
	 * Parameters
	 * 
	 * @var array
	 */
	private $parameters;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize a iDEAL kassa object
	 */
	public function __construct() {
		$this->parameters = array();

		$this->calculationsParametersShaIn = array();
		$this->calculationsParametersShaOut = array();
	}

	//////////////////////////////////////////////////

	public function getParameter($name) {
		$value = null;

		if(isset($this->parameters[$name])) {
			$value = $this->parameters[$name];
		}

		return $value;
	}

	public function setParameter($name, $value) {
		$this->parameters[$name] = $value;
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
		return $this->getParameter(self::PARAMETER_PSPID);
	}

	/**
	 * Set the PSP id
	 *
	 * @param PSP id
	 */
	public function setPspId($pspId) {
		$this->setParameter(self::PARAMETER_PSPID, $pspId);
	}

	//////////////////////////////////////////////////

	/**
	 * Get the order id
	 *
	 * @return an order id
	 */
	public function getOrderId() {
		return $this->getParameter(self::PARAMETER_ORDERID);
	}

	/**
	 * Set the order id
	 *
	 * @param sub id
	 */
	public function setOrderId($orderId) {
		$this->setParameter(self::PARAMETER_ORDERID, $orderId);
	}

	//////////////////////////////////////////////////

	/**
	 * Get the language
	 *
	 * @return an language
	 */
	public function getLanguage() {
		return $this->getParameter(self::PARAMETER_LANGUAGE);
	}

	/**
	 * Set the language
	 *
	 * @param string $language
	 */
	public function setLanguage($language) {
		$this->setParameter(self::PARAMETER_LANGUAGE, $language);
	}

	//////////////////////////////////////////////////
	
	/**
	 * Get the currency
	 *
	 * @return string
	 */
	public function getCurrency() {
		return $this->getParameter(self::PARAMETER_CURRENCY);
	}
	
	/**
	 * Set the currency
	 *
	 * @return string
	 */
	public function setCurrency($currency) {
		$this->setParameter(self::PARAMETER_CURRENCY, $currency);
	}

	//////////////////////////////////////////////////

	/**
	 * Get the amount
	 * 
	 * @return float
	 */
	public function getAmount() {
		return $this->getParameter(self::PARAMETER_AMOUNT);
	}

	/**
	 * Set the amount
	 * 
	 * @param float $amount
	 */
	public function setAmount($amount) {
		$this->setParameter(self::PARAMETER_AMOUNT, $amount);
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

	public function getPassPhrase() {
		return 'bow21650par6973';
	}

	public function getShaInFields() {
		return array_intersect_key($this->parameters, $this->calculationsParametersShaIn);
	}

	public function getShaOutFields() {
		return array_intersect_key($this->parameters, $this->calculationsParametersShaOut);
	}

	public function getShaSign($fields) {
		// This string is constructed by concatenating the values of the fields sent with the order (sorted
		// alphabetically, in the format ‘parameter=value’), separated by a passphrase.
		
		$string = '';
		$passprahse = $this->getPassPhrase();

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
		$result = sha1($string);

		return $result;
	}

	//////////////////////////////////////////////////

	public function getShaInSign() {
		return $this->getShaSign($this->getShaInFields());
	}

	public function getShaOutSign() {
		return $this->getShaSign($this->getShaOutFields());
	}

	//////////////////////////////////////////////////

	/**
	 * Get the iDEAL HTML
	 */
	public function getHtmlFields() {
		$html  = '';

		// general parameters
		$html .= sprintf('<input type="hidden" name="PSPID" value="%s" />', $this->getPspId());

		$html .= sprintf('<input type="hidden" name="orderID" value="%s" />', $this->getOrderId());
		$html .= sprintf('<input type="hidden" name="amount" value="%d" />', Pronamic_IDeal_IDeal::formatPrice($this->getAmount()));
		$html .= sprintf('<input type="hidden" name="currency" value="%s" />', $this->getCurrency());
		$html .= sprintf('<input type="hidden" name="language" value="%s" />', $this->getLanguage());

		$html .= sprintf('<input type="hidden" name="CN" value="%s" />', $this->getCustomerName());
		$html .= sprintf('<input type="hidden" name="EMAIL" value="%s" />', $this->getEMailAddress());

		$html .= sprintf('<input type="hidden" name="ownerZIP" value="%s" />', $this->getOwnerZip());
		$html .= sprintf('<input type="hidden" name="owneraddress" value="%s" />', $this->getOwnerAddress());
		$html .= sprintf('<input type="hidden" name="ownercty" value="%s" />', '');
		$html .= sprintf('<input type="hidden" name="ownertown" value="%s" />', $this->getOwnerCity());
		$html .= sprintf('<input type="hidden" name="ownertelno" value="%s" />', '');
		
		// check before the payment: see Security: Check before the Payment
		$html .= sprintf('<input type="hidden" name="SHASign" value="%s" />', $this->getShaInSign());

		// post payment redirection: see Transaction Feedback to the Customer
		$html .= sprintf('<input type="hidden" name="accepturl" value="%s" />', '');
		$html .= sprintf('<input type="hidden" name="﻿declineurl" value="%s" />', '');
		$html .= sprintf('<input type="hidden" name="﻿﻿exceptionurl" value="%s" />', '');
		$html .= sprintf('<input type="hidden" name="﻿﻿cancelurl" value="%s" />', '');
		
		return $html;
	}

	//////////////////////////////////////////////////

	public function validate() {
		$type = INPUT_POST;

		
	}
}
