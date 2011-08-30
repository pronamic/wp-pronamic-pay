<?php

/**
 * Title: Issuer XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_IDeal_XML_IssuerParser extends Pronamic_IDeal_XML_Parser {
	public static function parse(SimpleXMLElement $xml, $issuer = null) {
		if(!$issuer instanceof Pronamic_IDeal_Issuer) {
			$issuer = new Issuer();
		}

		if($xml->issuerID) {
			$issuer->setId((string) $xml->issuerID);
		}

		if($xml->issuerName) {
			$issuer->setName((string) $xml->issuerName);
		}
		
		if($xml->issuerList) {
			$issuer->setList((string) $xml->issuerList);
		}
		
		if($xml->issuerAuthenticationURL) {
			$issuer->authenticationUrl = (string) $xml->issuerAuthenticationURL;
		}	

		return $issuer;
	}
}
