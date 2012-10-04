<?php

/**
 * Title: iDEAL variant TargetPay
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_IDeal_VariantTargetPay extends Pronamic_IDeal_Variant {
	/**
	 * Constructs and intializes an iDEAL advanced variant
	 */
	public function __construct() {
		parent::__construct();

		$this->setMethod(Pronamic_IDeal_IDeal::METHOD_ADVANCED);
		
		$this->method_id = Pronamic_IDeal_Variant::METHOD_HTTP_REDIRECT;
	}
}
