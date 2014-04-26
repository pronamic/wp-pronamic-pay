<?php

/**
 * Title: Error
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvancedV3_Error {
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
	 * Get the consumer message
	 *
	 * @return string
	 */
	public function get_suggested_action() {
		return $this->suggested_action;
	}

	/**
	 * Set the consumer message
	 *
	 * @param string $detail
	 */
	public function set_suggested_action( $suggested_action ) {
		$this->suggested_action = $suggested_action;
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
