<?php

/**
 * Title: Transaction XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_PayDutch_XML_BankListParser extends Pronamic_Gateways_PayDutch_XML_Parser {
	/**
	 * Parse the specified XML element into an iDEAL transaction object
	 * 
	 * @param SimpleXMLElement $xml
	 */
	public static function parse( SimpleXMLElement $xml ) {
		$list = array();
		
		foreach ( $xml->issuer as $issuer ) {
			$id   = Pronamic_XML_Util::filter( $issuer->issuerid, FILTER_SANITIZE_STRING );
			$name = Pronamic_XML_Util::filter( $issuer->bankname, FILTER_SANITIZE_STRING );

			$list[$id] = $name;
		}
		
		return $list;
	}
}
