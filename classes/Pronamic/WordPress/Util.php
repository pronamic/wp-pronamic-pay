<?php

/**
 * Title: WordPress iDEAL plugin
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WordPress_Util {
	/**
	 * SimpleXML load string
	 * 
	 * @param string $string
	 * @return SimpleXMLElement || WP_Error
	 */
	public static function simplexml_load_string( $string ) {
		$result = false;
		
		// Suppress all XML errors
		$use_errors = libxml_use_internal_errors( true );
		
		// Load
		$xml = simplexml_load_string( $string );
		
		if ( $xml !== false ) {
			$result = $xml;
		} else {
			$error = new WP_Error( 'simplexml_load_error', __( 'Could not load the XML string.', 'pronamic_ideal' ) );

			foreach ( libxml_get_errors() as $error ) {
				$error->add( 'libxml_error', $error->message, $error );
			}

			libxml_clear_errors();
			
			$result = $error;
		}
		
		// Set back to previous value
		libxml_use_internal_errors( $use_errors );
		
		return $result;
	}

	//////////////////////////////////////////////////

	/**
	 * Amount to cents
	 * 
	 * @param float $price
	 * @return number
	 */
	public static function amount_to_cents( $price ) {
		return round( $price * 100 );
	}
}
