<?php

/**
 * Title: Country
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvancedV3_Country {
	/**
	 * The date the issuer list was modified
	 * 
	 * @var string
	 */
	private $name;

	/**
	 * The countries in this directory
	 * 
	 * @var array
	 */
	private $issuers;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an country
	 */
	public function __construct() {
		$this->issuers = array();
	}

	//////////////////////////////////////////////////

	/**
	 * Get the name
	 * 
	 * @return string
	 */
	public function get_name( ) {
		return $this->name;
	}

	/**
	 * Set the name
	 * 
	 * @param string $name
	 */
	public function set_name( $name ) {
		$this->name = $name;
	}

	//////////////////////////////////////////////////

	/**
	 * Add the specified issuer to this country
	 * 
	 * @param Country $country
	 */
	public function add_issuer( Pronamic_Gateways_IDealAdvancedV3_Issuer $issuer ) {
		$this->issuers[] = $issuer;
	}

	/**
	 * Get the issuers within this directory
	 * 
	 * @return array
	 */
	public function get_issuers() {
		return $this->issuers;
	}
}
