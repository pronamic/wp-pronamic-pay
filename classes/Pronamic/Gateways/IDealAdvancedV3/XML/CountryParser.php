<?php

/**
 * Title: Issuer XML parser
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvancedV3_XML_CountryParser implements Pronamic_Gateways_IDealAdvancedV3_XML_Parser {
	/**
	 * Parse
	 *
	 * @param SimpleXMLElement $xml
	 * @return Pronamic_Gateways_IDealAdvanced_Directory
	 */
	public static function parse( SimpleXMLElement $xml ) {
		$country = new Pronamic_Gateways_IDealAdvancedV3_Country();

		$country->set_name( Pronamic_XML_Util::filter( $xml->countryNames ) );

		foreach ( $xml->Issuer as $element ) {
			$issuer = Pronamic_Gateways_IDealAdvancedV3_XML_IssuerParser::parse( $element );

			$country->add_issuer( $issuer );
		}

		return $country;
	}
}
