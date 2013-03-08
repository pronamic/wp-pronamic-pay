<?php

/**
 * Title: Basic
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealBasic_IDealBasic {
	/**
	 * An payment type indicator for iDEAL
	 *
	 * @var string
	 */
	const PAYMENT_TYPE_IDEAL = 'ideal';

	//////////////////////////////////////////////////

	/**
	 * The expire date format (yyyy-MMddTHH:mm:ss.SSSZ)
	 * The Z stands for the time zone (CET).
	 * 
	 * @var string 
	 */
	const DATE_EXPIRE_FORMAT = 'Y-m-d\TH:i:s.000\Z';

	/**
	 * The default expire date modifier
	 * 
	 * @var string
	 */
	const EXPIRE_DATE_MODIFIER = '+30 minutes';

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
	private $mode = Pronamic_IDeal_IDeal::MODE_TEST;

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
		$this->items = new Pronamic_IDeal_Items();

		$this->forbiddenCharacters = array();

		$this->setPaymentType( self::PAYMENT_TYPE_IDEAL );
		$this->setExpireDateFormat( self::DATE_EXPIRE_FORMAT );
		$this->setExpireDateModifier( self::EXPIRE_DATE_MODIFIER );
		$this->setForbiddenCharachters( self::FORBIDDEN_CHARACHTERS );
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
	public function setPaymentServerUrl( $url ) {
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
	public function setMerchantId( $merchantId ) {
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
	public function setSubId( $subId ) {
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
	 * N..max50
	 *
	 * @param string $hashKey
	 */
	public function setHashKey( $hashKey ) {
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
	 * AN..max16 (AN = Alphanumeric, free text)
	 *
	 * @param sub id
	 */
	public function setPurchaseId( $purchaseId ) {
		$this->purchaseId = substr( $purchaseId, 0, 16 );
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
	public function setLanguage( $language ) {
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
	 * AN..max32 (AN = Alphanumeric, free text)
	 *
	 * @param string $description
	 */
	public function setDescription( $description ) {
		$this->description = substr( $description, 0, 32 );
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
	public function setCurrency( $currency ) {
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
	 * AN..max10
	 *
	 * @param string $paymentType an payment type
	 */
	public function setPaymentType( $paymentType ) {
		$this->paymentType = $paymentType;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the expire date
	 * 
	 * @param boolean $createNew indicator for creating a new expire date
	 * @return 
	 */
	public function getExpireDate( $createNew = false ) {
		if ( $this->expireDate == null || $createNew ) {
			$this->expireDate = new DateTime( null, new DateTimeZone( Pronamic_IDeal_IDeal::TIMEZONE ) );
			$this->expireDate->modify( $this->expireDateModifier );
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
	public function setExpireDateFormat( $expireDateFormat ) {
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
	public function setExpireDateModifier( $expireDateModifier ) {
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
	public function setForbiddenCharachters( $forbiddenCharacters ) {
		if ( is_string( $forbiddenCharacters) ) {
			$this->forbiddenCharacters = str_split( $forbiddenCharacters );
		} else if ( is_array( $forbiddenCharacters ) ) {
			$this->forbiddenCharacters = $forbiddenCharacters;
		} else {
			throw new Exception( 'Wrong arguments' );
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
	public function setSuccessUrl( $successUrl ) {
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
	public function setCancelUrl( $cancelUrl ) {
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
	public function setErrorUrl ($errorUrl ) {
		$this->errorUrl = $errorUrl;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the items
	 * 
	 * @return Pronamic_IDeal_Items
	 */
	public function getItems() {
		return $this->items;
	}
	
	/**
	 * Set the items
	 * 
	 * @param Pronamic_IDeal_Items $items
	 */
	public function setItems( Pronamic_IDeal_Items $items ) {
		$this->items = $items;
	}

	//////////////////////////////////////////////////

	/**
	 * Calculate the total amount of all items
	 */
	public function getAmount() {
		return $this->items->getAmount();
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
		$string[] = Pronamic_WordPress_Util::amount_to_cents( $this->getAmount() );

		// The online shop's unique order number, also known as purchase id
		$string[] = $this->getPurchaseId(); 

		// ?? Fixed value = ideal
		$string[] = $this->getPaymentType(); 

		// yyyy-MMddTHH:mm:ss.SSS Z Time at which the transaction expires (maximum of 1 hour later). 
		// The consumer has time until then to pay with iDEAL.
		$string[] = $this->getExpireDate()->format( $this->getExpireDateFormat() );

		// Iterate through the items and concat
		foreach ( $this->getItems() as $item ) {
			// Article number. <n> is 1 for the first product, 2 for the second, etc.
			// N.B. Note that for every product type the parameters 
			// itemNumber<n>, itemDescription<n>, itemQuantity<n> and itemPrice<n> are mandatory.
			$string[] = $item->getNumber();

			// Description of article <n>
			$string[] = $item->getDescription();
	
			// Number of items of article <n> that the consumer wants to buy
			$string[] = $item->getQuantity();

			// Price of article <n> in whole eurocents
			$string[] = Pronamic_WordPress_Util::amount_to_cents( $item->getPrice() ); // Price of article in whole cents
		}

		$concatString = implode('', $string);

		// The characters "\t", "\n", "\r", " " (spaces) may not exist in the string
		$forbiddenCharacters = $this->getForbiddenCharacters();
		$concatString = str_replace( $forbiddenCharacters, '', $concatString );

		// Delete special HTML entities
		$concatString = html_entity_decode( $concatString, ENT_COMPAT, 'UTF-8' );

		return $concatString;
	}

	/**
	 * Create hash
	 * 
	 * @param unknown_type $form
	 */
	public function createHash() {
		return sha1( $this->createHashString() );
	}

	//////////////////////////////////////////////////

	/**
	 * Get the iDEAL HTML
	 */
	public function getHtmlFields() {
		$fields = array();

		$fields['merchantID']  = $this->getMerchantId();
		$fields['subID']       = $this->getSubId();

		$fields['amount']      = Pronamic_WordPress_Util::amount_to_cents( $this->getAmount() );
		$fields['purchaseID']  = $this->getPurchaseId();
		$fields['language']    = $this->getLanguage();
		$fields['currency']    = $this->getCurrency();
		$fields['description'] = $this->getDescription();
		$fields['hash']        = $this->createHash();
		$fields['paymentType'] = $this->getPaymentType();
		$fields['validUntil']  = $this->getExpireDate()->format( $this->getExpireDateFormat() );

		$serial_number = 1;
		foreach ( $this->getItems() as $item) {
			$fields['itemNumber' . $serial_number]      = $item->getNumber();
			$fields['itemDescription' . $serial_number] = $item->getDescription();
			$fields['itemQuantity' . $serial_number]    = $item->getQuantity();
			$fields['itemPrice' . $serial_number]       = Pronamic_WordPress_Util::amount_to_cents( $item->getPrice() );

			$serial_number++;
		}

		$fields['urlCancel']   = $this->getCancelUrl();
		$fields['urlSuccess']  = $this->getSuccessUrl();
		$fields['urlError']    = $this->getErrorUrl();

		return Pronamic_IDeal_IDeal::htmlHiddenFields( $fields );
	}
}
