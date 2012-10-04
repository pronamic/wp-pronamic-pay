<?php

/**
 * Title: iDEAl variant basic
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_IDeal_VariantBasic extends  Pronamic_IDeal_Variant {
	/**
	 * Constructs and initializes an iDEAL basic variant
	 */
	public function __construct() {
		parent::__construct();

		$this->setMethod(Pronamic_IDeal_IDeal::METHOD_BASIC);

		$this->feedback_payment_status = false;
	}
}
