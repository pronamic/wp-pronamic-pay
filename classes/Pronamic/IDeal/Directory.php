<?php

/**
 * Title: Directory
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_IDeal_Directory {
	/**
	 * The date the issuer list was modified
	 * 
	 * @var string
	 */
	private $date;

	/**
	 * The issuers in this directory
	 * 
	 * @var array
	 */
	private $issuers;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an directory
	 */
	public function __construct() {
		$this->issuers = array();
	}

	//////////////////////////////////////////////////

	/**
	 * Set the specified date
	 * 
	 * @param DateTime $date
	 */
	public function setDate(DateTime $date) {
		$this->date = $date;
	}

	//////////////////////////////////////////////////

	/**
	 * Add the specified user to this directory
	 * 
	 * @param Issuer $issuer
	 */
	public function addIssuer(Pronamic_IDeal_Issuer $issuer) {
		$this->issuers[] = $issuer;
	}

	/**
	 * Get the issuers within this directory
	 * 
	 * @return array
	 */
	public function getIssuers() {
		return $this->issuers;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the issuers lists within this directory
	 * 
	 * @return array
	 */
	public function getLists() {
		$lists = array();

		$issuers = $this->getIssuers();
		foreach($issuers as $issuer) {
			$name = $issuer->getList();

			if(!isset($lists[$name])) {
				$lists[$name] = array();
			}
			
			$lists[$name][] = $issuer;
		}
		
		return $lists;
	}
}
