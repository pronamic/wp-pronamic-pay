<?php

/**
 * Title: iDEAl variant advanced
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_IDeal_VariantAdvanced extends Pronamic_IDeal_Variant {
	/**
	 * Constructs and intializes an iDEAL advanced variant
	 */
	public function __construct() {
		parent::__construct();

		$this->setMethod(Pronamic_IDeal_IDeal::METHOD_ADVANCED);
	}
}
