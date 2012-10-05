<?php

/**
 * Title: iDEAl variant easy
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_IDeal_VariantEasy extends Pronamic_IDeal_Variant {
	/**
	 * Constructs and initializes an iDEAL easy variant
	 */
	public function __construct() {
		parent::__construct();

		$this->setMethod(Pronamic_IDeal_IDeal::METHOD_EASY);

		$this->feedback_payment_status = false;
	}
}
