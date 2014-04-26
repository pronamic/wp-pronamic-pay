<?php

/**
 * Title: iDEAL Sisow error
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Sisow_Transaction {
	/**
	 * Transaction ID
	 *
	 * @var string
	 */
	public $id;

	/**
	 * The status of the transaction
	 *
	 * @var string
	 */
	public $status;

	/**
	 * The amount of the transaction
	 *
	 * @var float
	 */
	public $amount;

	/**
	 * Purchase ID
	 *
	 * @var string
	 */
	public $purchase_id;

	/**
	 * Description
	 *
	 * @var string
	 */
	public $description;

	/**
	 * Entrance code
	 *
	 * @var string
	 */
	public $entrance_code;

	/**
	 * Issuer ID
	 *
	 * @var string
	 */
	public $issuer_id;

	/**
	 * Timestamp
	 *
	 * @var DateTime
	 */
	public $timestamp;

	/////////////////////////////////////////////////
	// Consumer
	/////////////////////////////////////////////////

	/**
	 * Consumer name
	 *
	 * @var string
	 */
	public $consumer_name;

	/**
	 * Consumer account
	 *
	 * @var string
	 */
	public $consumer_account;

	/**
	 * Consumer city
	 *
	 * @var string
	 */
	public $consumer_city;

	/////////////////////////////////////////////////
	// Other
	/////////////////////////////////////////////////

	/**
	 * Issuer URL
	 *
	 * @var string
	 */
	public $issuer_url;

	/////////////////////////////////////////////////

	/**
	 * Constructs and initializes an Sisow error object
	 */
	public function __construct() {

	}

	//////////////////////////////////////////////////

	// @todo getters and setters

	//////////////////////////////////////////////////

	/**
	 * Create an string representation of this object
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->id . ' ' . $this->issuer_url;
	}
}
