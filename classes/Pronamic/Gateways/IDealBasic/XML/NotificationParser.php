<?php

/**
 * Title: Issuer XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealBasic_XML_NotificationParser {
	/**
	 * Parse
	 * 
	 * @param SimpleXMLElement $xml
	 * @param Pronamic_Gateways_IDealBasic_Notification $notification
	 * @return Pronamic_Gateways_IDealBasic_Notification
	 */
	public static function parse( SimpleXMLElement $xml, $notification = null ) {
		if ( $notification == null ) {
			$notification = new Pronamic_Gateways_IDealBasic_Notification();
		}

		if ( $xml->createDateTimeStamp ) {
			$notification->set_date( new DateTime( $xml->createDateTimeStamp ) );
		}

		if ( $xml->transactionID ) {
			$notification->set_transaction_id( (string) $xml->transactionID );
		}

		if ( $xml->purchaseID ) {
			$notification->set_purchase_id( (string) $xml->purchaseID );
		}

		if ( $xml->status ) {
			$notification->set_status( (string) $xml->status );
		}

		return $notification;
	}
}
