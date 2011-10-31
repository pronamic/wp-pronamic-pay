<?php

/**
 * Title: Transaction
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_IDeal_Transaction {
	/**
	 * Status indicator for success
	 * 
	 * @var string
	 */
	const STATUS_SUCCESS = 'Success';

	/**
	 * Status indicator for cancelled
	 * 
	 * @var string
	 */
	const STATUS_CANCELLED = 'Cancelled';
	
	/**
	 * Status indicator for expired
	 * 
	 * @var string
	 */
	const STATUS_EXPIRED = 'Expired';
	
	/**
	 * Status indicator for failure
	 * 
	 * @var string
	 */
	const STATUS_FAILURE = 'Failure';
	
	/**
	 * Status indicator for open
	 * 
	 * @var string
	 */
	const STATUS_OPEN = 'Open';

	//////////////////////////////////////////////////

	/**
	 * Transaction ID
	 * 
	 * @var string
	 */
	private $id;

	/**
	 * Purchase ID
	 * 
	 * @var string
	 */
	private $purchaseId;

	/**
	 * Amount
	 * 
	 * @var string
	 */
	private $amount;

	/**
	 * Currency
	 * 
	 * @var string
	 */
	private $currency;

	/**
	 * Timeframe during which the transaction is allowed to take
	 * place. Notation PnYnMnDTnHnMnS, where every n
	 * indicates the number of years, months, days, hours, minutes
	 * and seconds respectively. E.g. PT1H indicates an expiration
	 * period of 1 hour. PT3M30S indicates a period of 3 and a half
	 * minutes. Maximum allowed is PT1H; minimum allowed is
	 * PT1M.
	 * 
	 * @var string
	 */
	private $expirationPeriod;

	/**
	 * Language
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
	 * Mandatory code to identify the customer when he/she is
	 * redirected back to the merchantReturnURL
	 * 
	 * @var string
	 */
	private $entranceCode;

	//////////////////////////////////////////////////

	/**
	 * The status of this transaction
	 * 
	 * @var string
	 */
	private $status;

	//////////////////////////////////////////////////

	/**
	 * The consumer name
	 * 
	 * @var string
	 */
	private $consumerName;

	/**
	 * The consumer account number
	 * 
	 * @var string
	 */
	private $consumerAccountNumber;

	/**
	 * The consumer city
	 * 
	 * @var string
	 */
	private $consumerCity;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an transaction
	 */
	public function __construct() {
		
	}

	//////////////////////////////////////////////////

	/**
	 * Get the ID of this transaction
	 * 
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set the ID of this transaction
	 * 
	 * @param string $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the purchase ID of this transaction
	 * 
	 * @return string
	 */
	public function getPurchaseId() {
		return $this->purchaseId;
	}

	/**
	 * Set the purchase id of this transaction
	 * 
	 * @param string $id
	 */
	public function setPurchaseId($id) {
		$this->purchaseId = $id;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the amount of this transaction
	 * 
	 * @return string
	 */
	public function getAmount() {
		return $this->amount;
	}

	/**
	 * Set the amount of this transaction
	 * 
	 * @param string $amount
	 */
	public function setAmount($amount) {
		$this->amount = $amount;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the currency of this transaction
	 * 
	 * @return string
	 */
	public function getCurrency() {
		return $this->currency;
	}

	/**
	 * Set the currency of this transaction
	 * 
	 * @param string $amount
	 */
	public function setCurrency($currency) {
		$this->currency = $currency;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the expiration period of this transaction
	 * 
	 * @return string
	 */
	public function getExpirationPeriod() {
		return $this->expirationPeriod;
	}

	/**
	 * Set the expiration period of this transaction
	 * 
	 * @param string $expirationPeriod
	 */
	public function setExpirationPeriod($expirationPeriod) {
		$this->expirationPeriod = $expirationPeriod;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the language of this transaction
	 * 
	 * @return string
	 */
	public function getLanguage() {
		return $this->language;
	}

	/**
	 * Set the language of this transaction
	 * 
	 * @param string $language
	 */
	public function setLanguage($language) {
		$this->language = $language;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the description of this transaction
	 * 
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Set the description of this transaction
	 * AN..max32 (AN = Alphanumerical, free text)
	 * 
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = substr($description, 0, 32);
	}

	//////////////////////////////////////////////////

	/**
	 * Get the entrance code of this transaction
	 * 
	 * @return string
	 */
	public function getEntranceCode() {
		return $this->entranceCode;
	}

	/**
	 * Set the entrancode
	 * ANS..max40 (ANS = Strictly alphanumerical (letters and numbers only))
	 * 
	 * @param string $entranceCode
	 */
	public function setEntranceCode($entranceCode) {
		$this->entranceCode = substr($entranceCode, 0, 40);
	}

	//////////////////////////////////////////////////

	/**
	 * Get the status of this transaction
	 * 
	 * @return string
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * Set the status
	 * 
	 * @param string $status
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the consumer name
	 * 
	 * @return string
	 */
	public function getConsumerName() {
		return $this->consumerName;
	}

	/**
	 * Set the consumer name
	 * 
	 * @param string $status
	 */
	public function setConsumerName($name) {
		$this->consumerName = $name;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the consumer name
	 * 
	 * @return string
	 */
	public function getConsumerAccountNumber() {
		return $this->consumerAccountNumber;
	}

	/**
	 * Set the consumer name
	 * 
	 * @param string $status
	 */
	public function setConsumerAccountNumber($accountNumber) {
		$this->consumerAccountNumber = $accountNumber;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the consumer city
	 * 
	 * @return string
	 */
	public function getConsumerCity() {
		return $this->consumerCity;
	}

	/**
	 * Set the consumer city
	 * 
	 * @param string $status
	 */
	public function setConsumerCity($city) {
		$this->consumerCity = $city;
	}
}
