<?php

/**
 * Title: iDEAL Qantani error
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 * @see https://www.mollie.nl/support/documentatie/betaaldiensten/ideal/en/
 */
class Pronamic_Gateways_Qantani_Error {
	/**
	 * Qantani error ID
	 *
	 * @var string
	 */
	private $id;

	/**
	 * Qantani error description
	 *
	 * @var string
	 */
	private $description;

	/////////////////////////////////////////////////

	/**
	 * Constructs and initializes an Qantani error object
	 *
	 * @param string $id
	 * @param string $description
	 */
	public function __construct( $id, $description ) {
		$this->id          = $id;
		$this->description = $description;
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
		return $this->id . ' ' . $this->description;
	}
}
