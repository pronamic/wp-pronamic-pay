<?php

/**
 * Title: IDeal
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvancedV3_IDeal {
	/**
	 * Format the price according to the documentation in whole cents
	 * 
	 * @param float $price
	 * @return int
	 */
	public static function format_amount( $amount ) {
		// The amount payable in euro (with a period (.) used as decimal separator).
		// page 18 - http://pronamic.nl/wp-content/uploads/2012/12/iDEAL-Merchant-Integration-Guide-ENG-v3.3.1.pdf
		return number_format( $amount, 2, '.', '' );
	}
}
