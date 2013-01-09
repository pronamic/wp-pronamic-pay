<?php

/**
 * Title: Issuer XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealBasic_XML_NotifcationParser {
	/**
	 * Parse
	 * 
	 * @param SimpleXMLElement $xml
	 * @param Pronamic_Gateways_IDealAdvanced_Issuer $issuer
	 */
	public static function parse( SimpleXMLElement $xml, $notification = null ) {
		if ( $notification == null ) {
			$notification = new Pronamic_Gateways_IDealBasic_Notification();
		}

		if ( $xml->createDateTimeStamp ) {
			$notification->date = new DateTime( '@' . $xml->createDateTimeStamp );
		}

		if ( $xml->purchaseID ) {
			$issuer->set_name( (string) $xml->issuerName );
		}
		
		if ( $xml->status ) {
			$issuer->set_authentication_url( (string) $xml->issuerAuthenticationURL );
		}

		return $issuer;
	}
}
