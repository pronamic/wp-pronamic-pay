<?php

/**
 * Title: Error XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Sisow_XML_TransactionParser extends Pronamic_Gateways_Sisow_XML_Parser {
	public static function parse( SimpleXMLElement $xml ) {
		$transaction = new Pronamic_Gateways_Sisow_Transaction();

		// Transaction request
		if ( isset( $xml->trxid ) ) {
			$transaction->id               = (string) $xml->trxid;
		}
		
		if ( isset( $xml->issuerurl ) ) {
			$transaction->issuer_url       = urldecode( (string) $xml->issuerurl );
		}

		// Status response
		if ( isset( $xml->status ) ) {
			$transaction->status           = (string) $xml->status;
		}

		if ( isset( $xml->amount ) ) {
			$transaction->amount           = Pronamic_WP_Util::cents_to_amount( (string) $xml->amount );
		}

		if ( isset( $xml->purchaseid ) ) {
			$transaction->purchase_id      = (string) $xml->purchaseid;
		}

		if ( isset( $xml->description ) ) {
			$transaction->description      = (string) $xml->description;
		}
		
		if ( isset( $xml->entrancecode ) ) {
			$transaction->entrance_code    = (string) $xml->entrancecode;
		}
		
		if ( isset( $xml->issuerid ) ) {
			$transaction->issuer_id        = (string) $xml->issuerid;
		}

		if ( isset( $xml->timestamp ) ) {
			$transaction->timestamp        = new DateTime( (string) $xml->timestamp );
		}

		if ( isset( $xml->consumername ) ) {
			$transaction->consumer_name    = (string) $xml->consumername;
		}
		
		if ( isset( $xml->consumeraccount ) ) {
			$transaction->consumer_account = (string) $xml->consumeraccount;
		}
		
		if ( isset( $xml->consumercity ) ) {
			$transaction->consumer_city    = (string) $xml->consumercity;
		}

		return $transaction;
	}
}
