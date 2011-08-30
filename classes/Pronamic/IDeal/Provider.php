<?php

/**
 * Title: Variant
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_IDeal_Provider {
	/**
	 * The unique ID of this iDEAL provider
	 * 
	 * @var string
	 */
	private $id;

	/**
	 * The name of this iDEAL provider
	 * 
	 * @var string
	 */
	private $name;

	/**
	 * The URL of this provider
	 * 
	 * @var string
	 */
	private $url;

	//////////////////////////////////////////////////

	/**
	 * The variants
	 * 
	 * @var array
	 */
	private $variants;
	
	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an provider
	 */
	public function __construct() {
		$this->variants = array();
	}

	//////////////////////////////////////////////////

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	//////////////////////////////////////////////////

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	//////////////////////////////////////////////////

	public function getUrl() {
		return $this->url;
	}

	public function setUrl($url) {
		$this->url = $url;
	}

	//////////////////////////////////////////////////

	public function addVariant($variant) {
		$this->variants[] = $variant;
	}

	public function getVariants() {
		return $this->variants;
	}
}
