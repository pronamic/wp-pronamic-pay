<?php

/**
 * Title: Ogone error
* Description:
* Copyright: Copyright (c) 2005 - 2011
* Company: Pronamic
* @author Remco Tolsma
* @version 1.0
*/
class Pronamic_Gateways_Ogone_Error {
	/**
	 * Error code
	 * 
	 * @var string
	 */
	public $code;

	/**
	 * Error explanation
	 * 
	 * @var string
	 */
	public $explanation;
	
	//////////////////////////////////////////////////

	/**
	 * Constructs and intializes an Ogone error
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
		return $this->code . ' ' . $this->explanation;
	}
}
