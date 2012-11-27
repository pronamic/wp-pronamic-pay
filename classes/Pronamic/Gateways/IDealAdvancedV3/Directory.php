<?php

/**
 * Title: Directory
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvancedV3_Directory {
	/**
	 * The date the issuer list was modified
	 * 
	 * @var string
	 */
	private $date;

	/**
	 * The countries in this directory
	 * 
	 * @var array
	 */
	private $countries;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an directory
	 */
	public function __construct() {
		$this->countries = array();
	}

	//////////////////////////////////////////////////

	/**
	 * Set the specified date
	 * 
	 * @param DateTime $date
	 */
	public function set_date( DateTime $date ) {
		$this->date = $date;
	}

	//////////////////////////////////////////////////

	/**
	 * Add the specified country to this directory
	 * 
	 * @param Country $country
	 */
	public function add_country( Pronamic_Gateways_IDealAdvancedV3_Country $country ) {
		$this->countries[] = $country;
	}

	/**
	 * Get the countries within this directory
	 * 
	 * @return array
	 */
	public function get_countries() {
		return $this->countries;
	}
}
