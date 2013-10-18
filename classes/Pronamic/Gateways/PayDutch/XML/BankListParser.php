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
			$id   = (string) $issuer->issuerid;
			$name = (string) $issuer->bankname;

			$list[$id] = $name;
		}
		
		return $list;
	}
}
