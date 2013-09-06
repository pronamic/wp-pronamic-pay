<?php

/**
 * Title: Ogone security class
* Description:
* Copyright: Copyright (c) 2005 - 2011
* Company: Pronamic
* @author Remco Tolsma
* @version 1.0
*/
class Pronamic_Gateways_Ogone_Security {
	/**
	 * The Ogone calculations parameters in
	 * 
	 * @var array
	 */
	private static $calculations_parameters_in;

	/**
	 * The Ogone calucations parameters out
	 * 
	 * @var array
	 */
	private static $calculations_parameters_out;

	/////////////////////////////////////////////////

	/**
	 * Get calculations parameters in
	 */
	public static function get_calculations_parameters_in() {
		if ( ! isset( self::$calculations_parameters_in ) ) {
			self::$calculations_parameters_in = array();

			$file = Pronamic_WordPress_IDeal_Plugin::$dirname . '/other/calculations-parameters-sha-in.txt';
			if ( is_readable( $file ) ) {
				self::$calculations_parameters_in = file( $file, FILE_IGNORE_NEW_LINES );
			}
		}
		
		return self::$calculations_parameters_in;
	}

	/////////////////////////////////////////////////

	/**
	 * Get calculations parameters in
	 */
	public static function get_calculations_parameters_out() {
		if ( ! isset( self::$calculations_parameters_out ) ) {
			self::$calculations_parameters_out = array();

			$file = Pronamic_WordPress_IDeal_Plugin::$dirname . '/other/calculations-parameters-sha-out.txt';
			if ( is_readable( $file ) ) {
				self::$calculations_parameters_out = file( $file, FILE_IGNORE_NEW_LINES );
			}
		}
		
		return self::$calculations_parameters_out;
	}
}
