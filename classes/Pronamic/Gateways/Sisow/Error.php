<?php

/**
 * Title: iDEAL Sisow error
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Sisow_Error {
	/**
	 * Sisow error code
	 *
	 * @var string
	 */
	public $code;

	/**
	 * Sisow error message
	 *
	 * @var string
	 */
	public $message;

	/////////////////////////////////////////////////

	/**
	 * Constructs and initializes an Sisow error object
	 *
	 * @param string $code
	 * @param string $message
	 */
	public function __construct( $code, $message ) {
		$this->code    = $code;
		$this->message = $message;
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
		return $this->code . ' ' . $this->message;
	}
}
