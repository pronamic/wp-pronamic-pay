<?php

/**
 * Title: Issuer XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvanced_XML_DirectoryParser extends Pronamic_Gateways_IDealAdvanced_XML_Parser {
	public static function parse(SimpleXMLElement $xml) {
		$directory = new Pronamic_Gateways_IDealAdvanced_Directory();

		$timestamp = (string) $xml->directoryDateTimeStamp;
		$directory->setDate(new DateTime($timestamp));
		
		foreach($xml->Issuer as $element) {
			$issuer = Pronamic_IDeal_XML_IssuerParser::parse($element);
			$directory->addIssuer($issuer);
		}

		return $directory;
	}
}
