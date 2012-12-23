<?php

/**
 * Title: Transaction
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvancedV3_Transaction {
	/**
	 * Transaction ID
	 * 
	 * @var string
	 */
	private $id;

	/**
	 * Create date
	 * 
	 * @var unknown_type
	 */
	private $create_date;

	/**
	 * Purchase ID
	 * 
	 * @var string
	 */
	private $purchase_id;

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
	private $consumer_name;

	/**
	 * Consumer IBAN
	 * 
	 * @var string
	 */
	private $consumer_iban;

	/**
	 * Consumer IBAN
	 * 
	 * @var string
	 */
	private $consumer_bic;

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
	public function get_id() {
		return $this->id;
	}

	/**
	 * Set the ID of this transaction
	 * 
	 * @param string $id
	 */
	public function set_id( $id ) {
		$this->id = $id;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the purchase ID of this transaction
	 * 
	 * the purchase number according to the online shop’s system
	 * 
	 * @return string
	 */
	public function get_purchase_id() {
		return $this->purchase_id;
	}

	/**
	 * Set the purchase id of this transaction
	 * 
	 * the purchase number according to the online shop’s system
	 * 
	 * @param string $id
	 */
	public function set_purchase_id( $id ) {
		$this->purchase_id = $id;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the amount of this transaction
	 * 
	 * @return string
	 */
	public function get_amount() {
		return $this->amount;
	}

	/**
	 * Set the amount of this transaction
	 * 
	 * @param string $amount
	 */
	public function set_amount( $amount ) {
		$this->amount = $amount;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the currency of this transaction
	 * 
	 * @return string
	 */
	public function get_currency() {
		return $this->currency;
	}

	/**
	 * Set the currency of this transaction
	 * 
	 * @param string $amount
	 */
	public function set_currency( $currency ) {
		$this->currency = $currency;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the expiration period of this transaction
	 * 
	 * @return string
	 */
	public function get_expiration_period() {
		return $this->expiration_period;
	}

	/**
	 * Set the expiration period of this transaction
	 * 
	 * @param string $expiration_period
	 */
	public function set_expiration_period( $expiration_period ) {
		$this->expiration_period = $expiration_period;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the language of this transaction
	 * 
	 * @return string
	 */
	public function get_language() {
		return $this->language;
	}

	/**
	 * Set the language of this transaction
	 * 
	 * @param string $language
	 */
	public function set_language( $language ) {
		$this->language = $language;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the description of this transaction
	 * 
	 * @return string
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Set the description of this transaction
	 * AN..max32 (AN = Alphanumerical, free text)
	 * 
	 * @param string $description
	 */
	public function set_description( $description ) {
		$this->description = substr( $description, 0, 32 );
	}

	//////////////////////////////////////////////////

	/**
	 * Get the entrance code of this transaction
	 * 
	 * a code determined by the online shop with which the purchase can be 
	 * authenticated upon redirection to the online shop (see section 4.2.2 
	 * for details).
	 * 
	 * @return string
	 */
	public function get_entrance_code() {
		return $this->entrance_code;
	}

	/**
	 * Set the entrancode
	 * ANS..max40 (ANS = Strictly alphanumerical (letters and numbers only))
	 * 
	 * a code determined by the online shop with which the purchase can be 
	 * authenticated upon redirection to the online shop (see section 4.2.2 
	 * for details).
	 * 
	 * @param string $entrance_code
	 */
	public function set_entrance_code( $entrance_code ) {
		$this->entrance_code = substr( $entrance_code, 0, 40 );
	}

	//////////////////////////////////////////////////

	/**
	 * Get the status of this transaction
	 * 
	 * @return string
	 */
	public function get_status() {
		return $this->status;
	}

	/**
	 * Set the status
	 * 
	 * @param string $status
	 */
	public function set_status( $status ) {
		$this->status = $status;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the consumer name
	 * 
	 * @return string
	 */
	public function get_consumer_name() {
		return $this->consumer_name;
	}

	/**
	 * Set the consumer name
	 * 
	 * @param string $status
	 */
	public function set_consumer_name( $name ) {
		$this->consumer_name = $name;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the consumer IBAN number
	 * 
	 * @return string
	 */
	public function get_consumer_iban() {
		return $this->consumer_iban;
	}

	/**
	 * Set the consumer IBAN number
	 * 
	 * @param string $iban
	 */
	public function set_consumer_iban( $iban ) {
		$this->consumer_iban = $iban;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the consumer IBAN number
	 * 
	 * @return string
	 */
	public function get_consumer_bic() {
		return $this->consumer_bic;
	}

	/**
	 * Set the consumer BIC number
	 * 
	 * @param string $bic
	 */
	public function set_consumer_bic( $bic ) {
		$this->consumer_bic = $bic;
	}
}
