<?php

namespace Pronamic\IDeal;

/**
 * Title: Basic
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Basic extends IDeal {
	/**
	 * An payment type indicator for iDEAL
	 *
	 * @var string
	 */
	const PAYMENT_TYPE_IDEAL = 'ideal';

	//////////////////////////////////////////////////

	/**
	 * The expire date format (yyyy-MMddTHH:mm:ss.SSS Z)
	 * The Z stands for the time zone (CET).
	 * 
	 * @var string 
	 */
	const DATE_EXPIRE_FORMAT = 'Y-m-d\TH:i:s.000\Z';

	//////////////////////////////////////////////////

	/**
	 * Forbidden characters
	 * 
	 * @doc Manual iDEAL Lite.pdf (4.2 Explanation of the hash code)
	 * @var string
	 */
	const FORBIDDEN_CHARACHTERS = "\t\n\r ";

	//////////////////////////////////////////////////

	/**
	 * The URL for testing 
	 * 
	 * @var string
	 */
	private $paymentServerUrl;

	//////////////////////////////////////////////////

	/**
	 * The mercahnt ID
	 * 
	 * @var string
	 */
	private $merchantId;

	/**
	 * The sub ID
	 * 
	 * @var string
	 */
	private $subId;

	/**
	 * The hash key
	 * 
	 * @var string
	 */
	private $hashKey;

	/**
	 * The purchase ID
	 * 
	 * @var string
	 */
	private $purchaseId;

	/**
	 * The language
	 * 
	 * @var string
	 */
	private $language;

	/**
	 * Description
	 * 
	 * @var string
	 */
	private $description;

	/**
	 * The mode the iDEAL integration is running in (test or production)
	 *
	 * @var int
	 */
	private $mode = self::MODE_TEST;

	/**
	 * The currency
	 *
	 * @var string
	 */
	private $currency;

	/**
	 * Payment method
	 * 
	 * @var string
	 */
	private $paymentType;

	//////////////////////////////////////////////////

	/**
	 * The expire date
	 *
	 * @var Date
	 */
	private $expireDate;

	/**
	 * The expire date format
	 *
	 * @var string
	 */
	private $expireDateFormat;

	/**
	 * The expire date modifier
	 *
	 * @var string
	 */
	private $expireDateModifier;

	//////////////////////////////////////////////////

	/**
	 * The forbidden charachters
	 * 
	 * @var string
	 */
	private $forbiddenCharacters;

	//////////////////////////////////////////////////

	/**
	 * The items
	 * 
	 * @var array
	 */
	private $items;

	//////////////////////////////////////////////////

	/**
	 * The consumer is automatically directed to this URL after a successful payment.
	 *
	 * @var string
	 */
	private $successUrl;
	
	/**
	 * The consumer is automatically directed to this URL after the transaction has been cancelled.
	 *
	 * @var string
	 */
	private $cancelUrl;

	/**
	 * The consumer is directed to this URL if an error has occurred.
	 *
	 * @var string
	 */
	private $errorUrl;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize a iDEAL basic object
	 */
	public function __construct() {
		$this->items = array();

		$this->forbiddenCharacters = array();

		$this->setPaymentType(self::PAYMENT_TYPE_IDEAL);
		$this->setExpireDateFormat(self::DATE_EXPIRE_FORMAT);
		$this->setForbiddenCharachters(self::FORBIDDEN_CHARACHTERS);
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
	 * Get the merchant id
	 *
	 * @return an merchant id
	 */
	public function getMerchantId() {
		return $this->merchantId;
	}

	/**
	 * Set the merchant id
	 *
	 * @param merchant id
	 */
	public function setMerchantId($merchantId) {
		$this->merchantId = $merchantId;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the sub id
	 *
	 * @return an sub id
	 */
	public function getSubId() {
		return $this->subId;
	}

	/**
	 * Set the sub id
	 *
	 * @param sub id
	 */
	public function setSubId($subId) {
		$this->subId = $subId;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the hash key
	 *
	 * @return an sub id
	 */
	public function getHashKey() {
		return $this->hashKey;
	}

	/**
	 * Set the hash key
	 *
	 * @param string $hashKey
	 */
	public function setHashKey($hashKey) {
		$this->hashKey = $hashKey;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the purchase id
	 *
	 * @return an purchase id
	 */
	public function getPurchaseId() {
		return $this->purchaseId;
	}

	/**
	 * Set the purchase id
	 *
	 * @param sub id
	 */
	public function setPurchaseId($purchaseId) {
		$this->purchaseId = $purchaseId;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the language
	 *
	 * @return an language
	 */
	public function getLanguage() {
		return $this->language;
	}

	/**
	 * Set the language
	 *
	 * @param string $language
	 */
	public function setLanguage($language) {
		$this->language = $language;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the description
	 *
	 * @return an description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Set the description
	 *
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	//////////////////////////////////////////////////
	
	/**
	 * Get the currency
	 *
	 * @return string
	 */
	public function getCurrency() {
		return $this->currency;
	}
	
	/**
	 * Set the currency
	 *
	 * @return string
	 */
	public function setCurrency($currency) {
		$this->currency = $currency;
	}

	//////////////////////////////////////////////////
	
	/**
	 * Get the payment type
	 *
	 * @return an payment type
	 */
	public function getPaymentType() {
		return $this->paymentType;
	}
	
	/**
	 * Set the payment type
	 *
	 * @param string $paymentType an payment type
	 */
	public function setPaymentType($paymentType) {
		$this->paymentType = $paymentType;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the expire date
	 * 
	 * @param boolean $createNew indicator for creating a new expire date
	 * @return 
	 */
	public function getExpireDate($createNew = false) {
		if($this->expireDate == null || $createNew) {
			$this->expireDate = new \DateTime();
			$this->expireDate->modify($this->expireDateModifier);
		}

		return $this->expireDate;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the expire date format
	 * 
	 * @return string the expire date format
	 */
	public function getExpireDateFormat() {
		return $this->expireDateFormat;
	}

	/**
	 * Set the expire date formnat
	 * 
	 * @var string $expireDateFormat an expire date format
	 */
	public function setExpireDateFormat($expireDateFormat) {
		$this->expireDateFormat = $expireDateFormat;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the expire date modifier
	 * 
	 * @return the expire date modifier
	 */
	public function getExpireDateModifier() {
		return $this->expireDateModifier;
	}

	/**
	 * Set the expire date modifier
	 * 
	 * @var string $expireDateModifier an expire date modifier
	 */
	public function setExpireDateModifier($expireDateModifier) {
		$this->expireDateModifier = $expireDateModifier;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the forbidden characters
	 * 
	 * @return array
	 */
	public function getForbiddenCharacters() {
		return $this->forbiddenCharacters;
	}

	/**
	 * Set the forbidden characters
	 * 
	 * @var mixed an array or string with forbidden characters
	 */
	public function setForbiddenCharachters($forbiddenCharacters) {
		if(is_string($forbiddenCharacters)) {
			$this->forbiddenCharacters = str_split($forbiddenCharacters);
		} else if(is_array($forbiddenCharacters)) {
			$this->forbiddenCharacters = $forbiddenCharacters;
		} else {
			throw new Exception('Wrong arguments');
		}
	}

	//////////////////////////////////////////////////
	
	/**
	 * Get the success URL
	 *
	 * @return an URL
	 */
	public function getSuccessUrl() {
		return $this->successUrl;
	}

	/**
	 * Set the success URL
	 *
	 * @param string $successUrl
	 */
	public function setSuccessUrl($successUrl) {
		$this->successUrl = $successUrl;
	}

	//////////////////////////////////////////////////
	
	/**
	 * Get the cancel URL
	 *
	 * @return an URL
	 */
	public function getCancelUrl() {
		return $this->cancelUrl;
	}
	
	/**
	 * Set the cancel URL
	 *
	 * @param string $cancelUrl
	 */
	public function setCancelUrl($cancelUrl) {
		$this->cancelUrl = $cancelUrl;
	}

	//////////////////////////////////////////////////
	
	/**
	 * Get the error URL
	 *
	 * @return an URL
	 */
	public function getErrorUrl() {
		return $this->errorUrl;
	}
	
	/**
	 * Set the error URL
	 *
	 * @param string $errorUrl
	 */
	public function setErrorUrl($errorUrl) {
		$this->errorUrl = $errorUrl;
	}

	//////////////////////////////////////////////////

	/**
	 * Format the price according to the documentation in whole cents
	 * 
	 * @param float $price
	 * @return int
	 */
	public static function formatPrice($price) {
		return round($price * 100);
	}

	//////////////////////////////////////////////////

	/**
	 * Add item
	 */
	public function addItem(Basic\Item $item) {
		$this->items[] = $item;
	}

	/**
	 * Get the items
	 * 
	 * @return array
	 */
	public function getItems() {
		return $this->items;
	}

	//////////////////////////////////////////////////

	/**
	 * Calculate the total amount of all items
	 */
	public function getAmount() {
		$amount = 0;

		foreach($this->getItems() as $item) {
			$amount += $item->getAmount();
		}

		return $amount;
	}

	//////////////////////////////////////////////////

	/**
	 * Create hash string
	 */
	public function createHashString() {
		$string  = array();
		
		// SHA1 hashcode, used only with the hashcode approach (Chapter 4).
		$string[] = $this->getHashKey();

		// Your AcceptorID is provided in the registration process, also known as merchant id
		$string[] = $this->getMerchantId();

		// Provided in the registration process, value is normally '0' (zero)
		$string[] = $this->getSubId();

		// Total amount of transaction
		$string[] = self::formatPrice($this->getAmount());

		// The online shop's unique order number, also known as purchase id
		$string[] = $this->getPurchaseId(); 

		// ?? Fixed value = ideal
		$string[] = $this->getPaymentType(); 

		// yyyy-MMddTHH:mm:ss.SSS Z Time at which the transaction expires (maximum of 1 hour later). 
		// The consumer has time until then to pay with iDEAL.
		$string[] = $this->getExpireDate()->format($this->getExpireDateFormat());

		// Iterate through the items and concat
		foreach($this->getItems() as $item) {
			// Article number. <n> is 1 for the first product, 2 for the second, etc.
			// N.B. Note that for every product type the parameters 
			// itemNumber<n>, itemDescription<n>, itemQuantity<n> and itemPrice<n> are mandatory.
			$string[] = $item->getNumber();

			// Description of article <n>
			$string[] = $item->getDescription();
	
			// Number of items of article <n> that the consumer wants to buy
			$string[] = $item->getQuantity();

			// Price of article <n> in whole eurocents
			$string[] = self::formatPrice($item->getPrice()); // Price of article in whole cents
		}

		$concatString = implode('', $string);

		// The characters "\t", "\n", "\r", " " (spaces) may not exist in the string
		$forbiddenCharacters = $this->getForbiddenCharacters();
		$concatString = str_replace($forbiddenCharacters, '', $concatString);

		// Delete special HTML entities
		$concatString = html_entity_decode($concatString);

		return $concatString;
	}

	/**
	 * Create hash
	 * 
	 * @param unknown_type $form
	 */
	public function createHash() {
		return sha1($this->createHashString());
	}

	//////////////////////////////////////////////////

	/**
	 * Get the iDEAL HTML
	 */
	public function getHtmlFields() {
		$html  = '';
		$html .= sprintf('<input type="hidden" name="merchantID" value="%s" />', $this->getMerchantId());
		$html .= sprintf('<input type="hidden" name="subID" value="%s" />', $this->getSubId());

		$html .= sprintf('<input type="hidden" name="amount" value="%d" />', self::formatPrice($this->getAmount()));
		$html .= sprintf('<input type="hidden" name="purchaseID" value="%s" />', $this->getPurchaseId());
		$html .= sprintf('<input type="hidden" name="language" value="%s" />', $this->getLanguage());
		$html .= sprintf('<input type="hidden" name="currency" value="%s" />', $this->getCurrency());
		$html .= sprintf('<input type="hidden" name="description" value="%s" />', $this->getDescription());
		$html .= sprintf('<input type="hidden" name="hash" value="%s" />', $this->createHash());
		$html .= sprintf('<input type="hidden" name="paymentType" value="%s" />', $this->getPaymentType());
		$html .= sprintf('<input type="hidden" name="validUntil" value="%s" />', $this->getExpireDate()->format($this->getExpireDateFormat()));

		$serialNumber = 1;
		foreach($this->getItems() as $item) {
			$html .= sprintf('<input type="hidden" name="itemNumber%d" value="%s" />', $serialNumber, $item->getNumber());
			$html .= sprintf('<input type="hidden" name="itemDescription%d" value="%s" />', $serialNumber, $item->getDescription());
			$html .= sprintf('<input type="hidden" name="itemQuantity%d" value="%s" />', $serialNumber, $item->getQuantity());
			$html .= sprintf('<input type="hidden" name="itemPrice%d" value="%d" />', $serialNumber, self::formatPrice($item->getPrice()));

			$serialNumber++;
		}

		$html .= sprintf('<input type="hidden" name="urlCancel" value="%s" />', $this->getCancelUrl());
		$html .= sprintf('<input type="hidden" name="urlSuccess" value="%s" />', $this->getSuccessUrl());
		$html .= sprintf('<input type="hidden" name="urlError" value="%s" />', $this->getErrorUrl());

		return $html;
	}
}
