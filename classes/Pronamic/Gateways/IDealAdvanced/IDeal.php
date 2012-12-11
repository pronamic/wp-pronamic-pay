<?php

/**
 * Title: IDeal
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvanced_IDeal {
	/**
	 * Format the price according to the documentation in whole cents
	 * 
	 * @param float $price
	 * @return int
	 */
	public static function format_amount( $amount ) {
		// Total amount of transaction in whole eurocents
		// page 24 - http://pronamic.nl/wp-content/uploads/2011/12/iDEAL_Advanced_PHP_EN_V2.2.pdf
		return round( $amount * 100 );
	}
}
