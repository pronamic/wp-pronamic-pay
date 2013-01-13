<?php

/**
 * Title: Notification
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealBasic_Notification {
	/**
	 * The date of this notification
	 * 
	 * @var DateTime
	 */
	private $date;
	
	/**
	 * The transaction ID of this notification
	 *
	 * @var string
	 */
	private $transaction_id;
	
	/**
	 * The purchase ID of this notification
	 *
	 * @var string
	 */
	private $purchase_id;
	
	/**
	 * The status of this notification
	 *
	 * @var string
	 */
	private $status;
	
	/////////////////////////////////////////////////

	/**
	 * Constructs and intializes an notification object
	 */
	public function __construct( ) {
		
	}
	
	/////////////////////////////////////////////////

	/**
	 * Get the date of this notification
	 * 
	 * @return DateTime
	 */
	public function get_date() {
		return $this->date;
	}

	/**
	 * Set the date of this notification
	 * 
	 * @param DateTime $date
	 */
	public function set_date( DateTime $date ) {
		$this->date = $date;
	}
	
	/////////////////////////////////////////////////

	/**
	 * Get the transaction ID
	 * 
	 * @return string
	 */
	public function get_transaction_id() {
		return $this->transaction_id;
	}

	/**
	 * Set the transaction ID
	 * 
	 * @param string $transaction_id
	 */
	public function set_transaction_id( $transaction_id ) {
		$this->transaction_id = $transaction_id;
	}
	
	/////////////////////////////////////////////////

	/**
	 * Get the purchase ID
	 * 
	 * @return string
	 */
	public function get_purchase_id() {
		return $this->purchase_id;
	}

	/**
	 * Set the purchase ID
	 * 
	 * @param string $purchase_id
	 */
	public function set_purchase_id( $purchase_id ) {
		$this->purchase_id = $purchase_id;
	}
	
	/////////////////////////////////////////////////

	/**
	 * Get the status
	 * 
	 * @return string
	 */
	public function get_status() {
		return $this->purchase_id;
	}

	/**
	 * Set the status
	 * 
	 * @param string $status
	 */
	public function set_status( $status ) {
		$this->status = $status;
	}
}
