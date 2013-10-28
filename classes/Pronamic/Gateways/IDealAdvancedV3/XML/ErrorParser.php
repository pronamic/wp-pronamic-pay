<?php

/**
 * Title: Error XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvancedV3_XML_ErrorParser extends Pronamic_Gateways_IDealAdvancedV3_XML_Parser {
	public static function parse( SimpleXMLElement $xml ) {
		$error = new Pronamic_Gateways_IDealAdvancedV3_Error();

		$error->set_code( Pronamic_XML_Util::filter( $xml->errorCode ) );
		$error->set_message( Pronamic_XML_Util::filter( $xml->errorMessage ) );
		$error->set_detail( Pronamic_XML_Util::filter( $xml->errorDetail ) );
		$error->set_suggested_action( Pronamic_XML_Util::filter( $xml->suggestedAction ) );
		$error->set_consumer_message( Pronamic_XML_Util::filter( $xml->consumerMessage ) );
		
		return $error;
	}
}
