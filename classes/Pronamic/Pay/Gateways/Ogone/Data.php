<?php

/**
 * Title: iDEAL Internet Kassa gateway
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Pay_Gateways_Ogone_Data {
	/**
	 * Fields
	 *
	 * @var array
	 */
	private $fields;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize a iDEAL kassa object
	 */
	public function __construct() {
		$this->fields = array();
	}

	//////////////////////////////////////////////////
	// Fields
	//////////////////////////////////////////////////

	/**
	 * Get all the fields
	 *
	 * @return array
	 */
	public function get_fields() {
		return $this->fields;
	}

	/**
	 * Get field by the specifiek name
	 *
	 * @param string $name
	 */
	public function get_field( $name ) {
		$value = null;

		if ( isset( $this->fields[ $name ] ) ) {
			$value = $this->fields [$name ];
		}

		return $value;
	}

	/**
	 * Set field
	 *
	 * @param string $name
	 * @param string $value
	 * @return Pronamic_Pay_Gateways_Ogone_Data
	 */
	public function set_field( $name, $value ) {
		$this->fields[ $name ] = $value;
		
		return $this;
	}
}
