<?php

/**
 * Title: WordPress iDEAL payment
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WordPress_IDeal_Payment {
	/**
	 * The unique ID of this iDEAL payment
	 * 
	 * @var string
	 */
	public $id;

	/**
	 * The used iDEAL configuration for this iDEAL payment
	 * 
	 * @var unknown_type
	 */
	public $configuration;

	//////////////////////////////////////////////////

	public $purchase_id;

	public $transaction_id;

	public $amount;

	public $currency;

	public $expiration_period;

	public $language;

	public $entrance_code;

	public $description;

	public $consumer_name;

	public $consumer_account_number;

	public $consumer_iban;

	public $consumer_bic;

	public $consumer_city;

	public $status;

	public $status_requests;
	
	public $email;

	//////////////////////////////////////////////////

	/**
	 * The name of the source wich initiated this iDEAL payment
	 * 
	 * @var string
	 */
	public $source;

	/**
	 * The unique ID of the external source
	 * 
	 * @var string
	 */
	public $source_id;

	//////////////////////////////////////////////////

	/**
	 * The date of this iDEAL payment
	 * 
	 * @var DateTime
	 */
	private $date;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize an payment
	 */
	public function __construct() {
		$this->date = new DateTime( 'now', new DateTimeZone( 'UTC' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Get the ID of this payment
	 * 
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set the ID of this payment
	 * 
	 * @param string $id
	 */
	public function setId( $id ) {
		$this->id = $id;
	}

	//////////////////////////////////////////////////
	
	/**
	 * Get the date of this payment
	 * 
	 * @return DateTime
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * Set the date of this payment
	 * 
	 * @param DateTime $date
	 */
	public function setDate( DateTime $date ) {
		$this->date = $date;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the source of this payment
	 * 
	 * @return string
	 */
	public function getSource() {
		return $this->source;
	}

	/**
	 * Get the source ID of this payment
	 * 
	 * @return string
	 */
	public function getSourceId() {
		return $this->source_id;
	}
	
	/**
	 * Set the source and ID of this payment
	 * 
	 * @param string $source
	 * @param string $id
	 */
	public function setSource( $source, $id ) {
		$this->source = $source;
		$this->source_id = $id;
	}
	
	/**
	 * Returns this payments set email
	 * 
	 * @access public
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}
	
	/**
	 * Sets this payments associated email
	 * 
	 * @access public
	 * @param string $email
	 * @return \Pronamic_WordPress_IDeal_Payment
	 */
	public function setEmail( $email ) {
		$this->email = $email;
		return $this;
	}
}
