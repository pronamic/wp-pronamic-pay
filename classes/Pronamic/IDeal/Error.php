<?php

/**
 * Title: Error
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_IDeal_Error {
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
	private $suggestedAction;

	/**
	 * Suggested expiration period
	 * 
	 * @var string
	 */
	private $suggestedExpirationPeriod;

	/**
	 * Consumer message
	 * 
	 * @var string
	 */
	private $consumerMessage;

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
	public function getCode() {
		return $this->code;
	}

	/**
	 * Set the code error
	 * 
	 * @param string $code
	 */
	public function setCode($code) {
		$this->code = $code;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the message of this error
	 * 
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * Set the message error
	 * 
	 * @param string $code
	 */
	public function setMessage($message) {
		$this->message = $message;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the detail of this error
	 * 
	 * @return string
	 */
	public function getDetail() {
		return $this->detail;
	}

	/**
	 * Set the detail error
	 * 
	 * @param string $detail
	 */
	public function setDetail($detail) {
		$this->detail = $detail;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the consumer message
	 * 
	 * @return string
	 */
	public function getConsumerMessage() {
		return $this->consumerMessage;
	}

	/**
	 * Set the consumer message
	 * 
	 * @param string $detail
	 */
	public function setConsumerMessage($consumerMessage) {
		$this->consumerMessage = $consumerMessage;
	}
}
