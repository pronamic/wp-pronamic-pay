<?php

/**
 * Title: Error XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Sisow_XML_ErrorParser extends Pronamic_Gateways_Sisow_XML_Parser {
	public static function parse( SimpleXMLElement $xml ) {
		$error = new Pronamic_Gateways_Sisow_Error( (string) $xml->errorcode, (string) $xml->errormessage );

		return $error;
	}
}
