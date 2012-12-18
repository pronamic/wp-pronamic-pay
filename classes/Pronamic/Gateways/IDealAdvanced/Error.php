<?php

/**
 * Title: Error
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvanced_Error {
	/**
	 * Code
	 * 
	 * @var string
	 */
	private $code;

	/**
	 * Message
	 * 
	 * @var string
	 */
	private $message;

	/**
	 * Detail
	 * 
	 * @var string
	 */
	private $detail;

	/**
	 * Suggested action
	 * 
	 * @var string
	 */
	private $suggested_action;

	/**
	 * Suggested expiration period
	 * 
	 * @var string
	 */
	private $suggested_expiration_period;

	/**
	 * Consumer message
	 * 
	 * @var string
	 */
	private $consumer_message;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an error
	 */
	public function __construct() {
		
	}

	//////////////////////////////////////////////////

	/**
	 * Get the code of this error
	 * 
	 * @return string
	 */
	public function get_code() {
		return $this->code;
	}

	/**
	 * Set the code error
	 * 
	 * @param string $code
	 */
	public function set_code( $code ) {
		$this->code = $code;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the message of this error
	 * 
	 * @return string
	 */
	public function get_message() {
		return $this->message;
	}

	/**
	 * Set the message error
	 * 
	 * @param string $code
	 */
	public function set_message( $message ) {
		$this->message = $message;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the detail of this error
	 * 
	 * @return string
	 */
	public function get_detail() {
		return $this->detail;
	}

	/**
	 * Set the detail error
	 * 
	 * @param string $detail
	 */
	public function set_detail( $detail ) {
		$this->detail = $detail;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the suggested action of this error
	 * 
	 * @return string
	 */
	public function get_suggested_action() {
		return $this->suggested_action;
	}

	/**
	 * Set the suggested action of this error
	 * 
	 * @param string $suggested_action
	 */
	public function set_suggested_action( $suggested_action ) {
		$this->suggested_action = $suggested_action;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the suggested action of this error
	 * 
	 * @return string
	 */
	public function get_suggested_expiration_period() {
		return $this->suggested_expiration_period;
	}

	/**
	 * Set the suggested action of this error
	 * 
	 * @param string $suggested_action
	 */
	public function set_suggested_expiration_period( $suggested_expiration_period ) {
		$this->suggested_expiration_period = $suggested_expiration_period;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the consumer message
	 * 
	 * @return string
	 */
	public function get_consumer_message() {
		return $this->consumer_message;
	}

	/**
	 * Set the consumer message
	 * 
	 * @param string $detail
	 */
	public function set_consumer_message( $consumer_message ) {
		$this->consumer_message = $consumer_message;
	}
}
