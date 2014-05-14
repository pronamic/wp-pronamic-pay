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
	 * Find the signature from an data array
	 *
	 * @param array $data
	 * @return null or signature value
	 */
	public static function get_signature( $data ) {
		$result = null;

		foreach ( $data as $key => $value ) {
			if ( Pronamic_Gateways_Buckaroo_Util::string_equals( $key, Pronamic_Gateways_Buckaroo_Parameters::SIGNATURE ) ) {
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
	public static function filter_data( $data ) {
		$filter = array();

		// List all parameters prefixed with brq_, add_ or cust_, except brq_signature
		foreach ( $data as $key => $value ) {
			if (
				( Pronamic_Gateways_Buckaroo_Util::string_starts_with( $key, 'brq_' ) || Pronamic_Gateways_Buckaroo_Util::string_starts_with( $key, 'add_' ) || Pronamic_Gateways_Buckaroo_Util::string_starts_with( $key, 'cust_' ) )
					&&
				( ! Pronamic_Gateways_Buckaroo_Util::string_equals( $key, Pronamic_Gateways_Buckaroo_Parameters::SIGNATURE ) )
			) {
				$filter[ $key ] = $value;
			}
		}

		return $filter;
	}

	//////////////////////////////////////////////////

	/**
	 * Create signature
	 *
	 * Please note: When verifying a received signature, first url-decode all the field values.
	 * A signature is always calculated over the non-encoded values (i.e The value “J.+de+Tester” should be decoded to “J. de Tester”).
	 *
	 * @param array $data
	 * @param string $secret_key
	 * @param boolean $url_decode
	 * @return string
	 */
	public static function create_signature( $data, $secret_key ) {
		$string = '';

		// 1. List all parameters prefixed with brq_, add_ or cust_, except brq_signature
		$data = self::filter_data( $data );

		// 2. Sort these parameters alphabetically on the parameter name
		uksort( $data, 'strcasecmp' );

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
