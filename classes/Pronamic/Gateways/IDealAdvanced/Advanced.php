<?php

/**
 * Title: Advanced
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvanced_IDealAdvanced extends Pronamic_IDeal_IDeal {
	/**
	 * The public certificates
	 * 
	 * @var array
	 */
	private $publicCertificates;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an iDEAL advanced object
	 */
	public function __construct() {
		$this->publicCertificates = array();
	}

	//////////////////////////////////////////////////

	/**
	 * Add the specified certificate
	 * 
	 * @param string $file
	 */
	public function addCertificate($file) {
		$this->publicCertificates[] = $file;
	}
}
