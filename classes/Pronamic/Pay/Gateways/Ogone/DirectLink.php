<?php

/**
 * Title: Ogone DirectLink
 * Description: 
 * Copyright: Copyright (c) 2005 - 2013
 * Company: Pronamic
 * @author Remco Tolsma
 * @since 1.4.0
 */
class Pronamic_Pay_Gateways_Ogone_DirectLink {
	/**
	 * Ogone DirectLink test API endpoint URL
	 * 
	 * @var string
	 */
	const API_TEST_URL = 'https://secure.ogone.com/ncol/test/orderdirect.asp';
	
	/**
	 * Ogone DirectLink production API endpoint URL
	 *
	 * @var string
	 */
	const API_PRODUCTION_URL = 'https://secure.ogone.com/ncol/prod/orderdirect.asp';

	/////////////////////////////////////////////////

	/**
	 * Constructs and initializes an Ogone DirectLink object
	 */
	public function __construct() {
		
	}
}
