<?php

/**
 * Title: Issuer
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvancedV3_Issuer {
	/**
	 * ID of the issuer
	 * 
	 * @var string
	 */
	private $id;

	/**
	 * Name of the issuer
	 * 
	 * @var string
	 */
	private $name;

	/**
	 * Authentication URL
	 * 
	 * @var string
	 */
	private $authentication_url;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an issuer
	 */
	public function __construct() {
		
	}

	//////////////////////////////////////////////////

	/**
	 * Get the ID of this issuer
	 * 
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Set the ID of this issuer
	 * 
	 * @param string $id
	 */
	public function set_id( $id ) {
		$this->id = $id;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the name of this issuer
	 * 
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Set the name of this issuer
	 * 
	 * @param string $name
	 */
	public function set_name( $name ) {
		$this->name = $name;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the name of this issuer
	 * 
	 * @return string
	 */
	public function get_authentication_url() {
		return $this->authentication_url;
	}

	/**
	 * Set the name of this issuer
	 * 
	 * @param string $name
	 */
	public function set_authentication_url( $authentication_url ) {
		$this->authentication_url = $authentication_url;
	}
}
