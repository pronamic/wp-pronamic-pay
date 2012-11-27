<?php

/**
 * Title: Issuer XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvancedV3_XML_IssuerParser extends Pronamic_Gateways_IDealAdvanced_XML_Parser {
	/**
	 * Parse
	 * 
	 * @param SimpleXMLElement $xml
	 * @param Pronamic_Gateways_IDealAdvanced_Issuer $issuer
	 */
	public static function parse( SimpleXMLElement $xml, $issuer = null ) {
		if ( ! $issuer instanceof Pronamic_Gateways_IDealAdvancedV3_Issuer ) {
			$issuer = new Pronamic_Gateways_IDealAdvancedV3_Issuer();
		}

		if ( $xml->issuerID ) {
			$issuer->set_id( (string) $xml->issuerID );
		}

		if ( $xml->issuerName ) {
			$issuer->set_name( (string) $xml->issuerName );
		}

		return $issuer;
	}
}
