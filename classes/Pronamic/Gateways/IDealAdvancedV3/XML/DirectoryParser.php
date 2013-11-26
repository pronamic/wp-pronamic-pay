<?php

/**
 * Title: Issuer XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvancedV3_XML_DirectoryParser implements Pronamic_Gateways_IDealAdvancedV3_XML_Parser {
	/**
	 * Parse
	 * 
	 * @param SimpleXMLElement $xml
	 * @return Pronamic_Gateways_IDealAdvanced_Directory
	 */
	public static function parse( SimpleXMLElement $xml ) {
		$directory = new Pronamic_Gateways_IDealAdvancedV3_Directory();

		$timestamp = Pronamic_XML_Util::filter( $xml->directoryDateTimestamp );
		$directory->set_date( new DateTime( $timestamp ) );
		
		foreach ( $xml->Country as $element ) {
			$country = Pronamic_Gateways_IDealAdvancedV3_XML_CountryParser::parse( $element );

			$directory->add_country( $country );
		}

		return $directory;
	}
}
