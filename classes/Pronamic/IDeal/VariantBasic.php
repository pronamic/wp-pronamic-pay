<?php

namespace Pronamic\IDeal;

/**
 * Title: Variant
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class VariantBasic extends Variant {
	public function __construct() {
		parent::__construct();

		$this->setMethod(IDeal::METHOD_BASIC);
	}
}
