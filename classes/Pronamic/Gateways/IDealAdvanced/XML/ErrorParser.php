<?php

/**
 * Title: Error XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvanced_XML_ErrorParser extends Pronamic_Gateways_IDealAdvanced_XML_Parser {
	/**
	 * Parse iDEAL Advanced error from the specified XML element
	 * 
	 * @param SimpleXMLElement $xml
	 * @return Pronamic_Gateways_IDealAdvanced_Error
	 */
	public static function parse( SimpleXMLElement $xml ) {
		$error = new Pronamic_Gateways_IDealAdvanced_Error();

		if ( $xml->errorCode ) {
			$error->set_code( (string) $xml->errorCode );
		}
		
		if ( $xml->errorMessage ) {
			$error->set_message( (string) $xml->errorMessage );
		}
		
		if ( $xml->errorDetail ) {
			$error->set_detail( (string) $xml->errorDetail );
		}
		
		if ( $xml->suggestedAction ) {
			$error->set_suggested_action( (string) $xml->suggestedAction );
		}
		
		if ( $xml->suggestedExpirationPeriod ) {
			$error->set_suggested_expiration_period( (string) $xml->suggestedExpirationPeriod );
		}
		
		if ( $xml->consumerMessage ) {
			$error->set_consumer_message( (string) $xml->consumerMessage );
		}
		
		return $error;
	}
}
