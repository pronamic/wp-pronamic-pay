<?php

/**
 * Title: Ogone signature composer
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Pay_Gateways_Ogone_Security {
	public static function get_calculation_fields( $calculation_fields, $fields ) {
		$calculation_fields = array_flip( $calculation_fields );

		return array_intersect_key( $fields, $calculation_fields );
	}

	public static function get_signature( $fields, $passphrase, $hash_algorithm ) {
		// This string is constructed by concatenating the values of the fields sent with the order (sorted
		// alphabetically, in the format ‘parameter=value’), separated by a passphrase.
		$string = '';

		// All parameters need to be put alphabetically
		ksort( $fields );

		// Loop
		foreach ( $fields as $name => $value ) {
			$value = (string) $value;

			// Use of empty will fail, value can be string '0'
			if ( strlen( $value ) > 0 ) {
				$name = strtoupper( $name );

				$string .= $name . '=' . $value . $passphrase;
			}
		}

		// Hash
		$result = hash( $hash_algorithm, $string );

		// String to uppercase
		$result = strtoupper( $result );

		return $result;
	}
}
