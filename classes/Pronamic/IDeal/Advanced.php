<?php

namespace Pronamic\IDeal;

/**
 * Title: Advanced
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Advanced extends IDeal {
	private $publicCertificates;

	public function __construct() {
		$this->publicCertificates = array();
	}

	public function addCertificate($file) {
		$this->publicCertificates[] = $file;
	}
}
