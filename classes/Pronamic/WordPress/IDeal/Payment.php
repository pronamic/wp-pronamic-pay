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
	public $id;

	public $configuration;

	public $transaction;
	
	private $source;

	private $sourceId;
	
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

		$this->transaction->setPurchaseId($id);
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