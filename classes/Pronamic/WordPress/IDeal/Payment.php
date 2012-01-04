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

	/**
	 * The transaction
	 * 
	 * @var unknown_type
	 */
	public $transaction;

	/**
	 * The name of the source wich initiated this iDEAL payment
	 * 
	 * @var string
	 */
	private $source;

	/**
	 * The unique ID of the external source
	 * 
	 * @var string
	 */
	private $sourceId;

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
		$this->date = new DateTime('now', new DateTimeZone('UTC'));
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
	public function setId($id) {
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
	public function setDate(DateTime $date) {
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
		return $this->sourceId;
	}
	
	/**
	 * Set the source and ID of this payment
	 * 
	 * @param string $source
	 * @param string $id
	 */
	public function setSource($source, $id) {
		$this->source = $source;
		$this->sourceId = $id;
	}
}
