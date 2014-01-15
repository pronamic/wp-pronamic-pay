<?php

/**
 * Title: Buckaroo utility class
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Buckaroo_Util {
	/**
	 * Buckaroo check if the specified string is the specified key
	 * 
	 * @param string $string
	 * @param string $value
	 * @return boolean true if match, false otherwise
	 */
	public static function string_equals( $string, $value ) {
		return strcasecmp( $string, $value ) == 0;
	}
	
	/**
	 * Buckaroo check if the key starts with an prefix
	 * 
	 * @param string $string
	 * @param string $prefix
	 * @return boolean true if match, false otherwise
	 */
	public static function string_starts_with( $string, $prefix ) {
		$string = substr( $string, 0, strlen( $prefix ) );

		return strcasecmp( $string, $prefix ) == 0;
	}

	//////////////////////////////////////////////////

	/**
	 * URL decode array
	 * 
	 * @param array $data
	 * @return array
	 */
	public static function urldecode( array $data ) {
		foreach ( $data as $key => &$value ) {
			$value = urldecode( $value );
		}
		
		return $data;
	}
}
