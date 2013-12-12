<?php

/**
 * Title: Issuer XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvanced_XML_IssuerParser implements Pronamic_Gateways_IDealAdvanced_XML_Parser {
	/**
	 * Parse
	 * 
	 * @param SimpleXMLElement $xml
	 */
	public static function parse( SimpleXMLElement $xml ) {
		$issuer = new Pronamic_Gateways_IDealAdvanced_Issuer();

		if ( $xml->issuerID ) {
			$issuer->setId( Pronamic_XML_Util::filter( $xml->issuerID ) );
		}

		if ( $xml->issuerName ) {
			$issuer->setName( Pronamic_XML_Util::filter( $xml->issuerName ) );
		}
		
		if ( $xml->issuerList ) {
			$issuer->setList( Pronamic_XML_Util::filter( $xml->issuerList ) );
		}
		
		if ( $xml->issuerAuthenticationURL ) {
			$issuer->authenticationUrl = Pronamic_XML_Util::filter( $xml->issuerAuthenticationURL );
		}

		return $issuer;
	}
}
