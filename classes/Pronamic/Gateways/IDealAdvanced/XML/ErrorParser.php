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

		$error->setCode( (string) $xml->errorCode );
		$error->setMessage( (string) $xml->errorMessage );
		$error->setDetail( (string) $xml->errorDetail );
		$error->setConsumerMessage( (string) $xml->consumerMessage );
		
		return $error;
	}
}
