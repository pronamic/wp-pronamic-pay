<?php

/**
 * Title: Buckaroo security class
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Buckaroo_Security {
	/**
	 * Buckaroo check if the specified string is the specified key
	 * 
	 * @param string $string
	 * @param string $value
	 * @return boolean true if match, false otherwise
	 */
	private static function string_equals( $string, $value ) {
		return strcasecmp( $string, $value ) == 0;
	}
	
	/**
	 * Buckaroo check if the key starts with an prefix
	 * 
	 * @param string $string
	 * @param string $prefix
	 * @return boolean true if match, false otherwise
	 */
	private static function string_starts_with( $string, $prefix ) {
		$string = substr( $string, 0, strlen( $prefix ) );

		return strcasecmp( $string, $prefix ) == 0;
	}

	//////////////////////////////////////////////////

	/**
	 * Find the signature from an data array
	 * 
	 * @param array $data
	 * @return null or signature value
	 */
	public static function get_signature( $data ) {
		$result = null;

		foreach ( $data as $key => $value ) {
			if ( self::string_equals( $key, Pronamic_Gateways_Buckaroo_Parameters::SIGNATURE ) ) {
				return $value;
			}
		}
		
		return $result;
	}

	//////////////////////////////////////////////////

	/**
	 * Filter the data for generating an signature
	 * 
	 * @param array $data
	 * @return array
	 */
	public static function filter_data( $data, $url_decode = false ) {
		$filter = array();

		// List all parameters prefixed with brq_, add_ or cust_, except brq_signature
		foreach ( $data as $key => $value ) {
			if ( 
				( self::string_starts_with( $key, 'brq_' ) || self::string_starts_with( $key, 'add_' ) || self::string_starts_with( $key, 'cust_' ) ) 
					&& 
				! self::string_equals( $key, Pronamic_Gateways_Buckaroo_Parameters::SIGNATURE )
			) {
				if ( $url_decode ) {
					$value = urldecode( $value );
				}

				$filter[$key] = $value;
			}
		}
		
		return $filter;
	}

	//////////////////////////////////////////////////

	/**
	 * Create signature
	 * 
	 * @param array $data
	 * @param string $secret_key
	 * @param boolean $url_decode
	 * @return string
	 */
	public static function create_signature( $data, $secret_key, $url_decode = false ) {
		$string = '';

		// 1. List all parameters prefixed with brq_, add_ or cust_, except brq_signature
		// Please note: When verifying a received signature, first url-decode all the field values. 
		// A signature is always calculated over the non-encoded values (i.e The value “J.+de+Tester” should be decoded to “J. de Tester”).
		$data = self::filter_data( $data, $url_decode );

		// 2. Sort these parameters alphabetically on the parameter name
		ksort( $data );

		// 3. Concatenate all the parameters
		foreach ( $data as $key => $value ) {
			$string .= $key . '=' . $value;
		}

		// 4. Add the pre-shared secret key at the end of the string
		$string .= $secret_key;

		// 5. Calculate a SHA-1 hash over this string.
		$hash = hash( 'sha1', $string );
		
		// Return the hash in hexadecimal format
		return $hash;
	}
}
