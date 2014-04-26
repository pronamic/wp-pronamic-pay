<?php

/**
 * Title: Issuer
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvanced_Issuer {
	/**
	 * Indicator for the short list
	 *
	 * @var string
	 */
	const LIST_SHORT = 'Short';

	/**
	 * Indicator for the long list
	 *
	 * @var string
	 */
	const LIST_LONG = 'Long';

	//////////////////////////////////////////////////

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
	 * Name of the list the issuer is on
	 *
	 * @var string
	 */
	private $list;

	/**
	 * The authentication URL
	 *
	 * @var string
	 */
	public $authenticationUrl;

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
	public function getId() {
		return $this->id;
	}

	/**
	 * Set the ID of this issuer
	 *
	 * @param string $id
	 */
	public function setId( $id ) {
		$this->id = $id;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the name of this issuer
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Set the name of this issuer
	 *
	 * @param string $name
	 */
	public function setName( $name ) {
		$this->name = $name;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the name of the list this issuer is on
	 *
	 * @return string
	 */
	public function getList() {
		return $this->list;
	}

	/**
	 * Set the name of the list this issuer is on
	 *
	 * @param string $list
	 */
	public function setList( $list ) {
		$this->list = $list;
	}
}
