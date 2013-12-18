<?php

/**
 * Title: Transaction XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvanced_XML_TransactionParser implements Pronamic_Gateways_IDealAdvanced_XML_Parser {
	/**
	 * Parse the specified XML element into an iDEAL transaction object
	 * 
	 * @param SimpleXMLElement $xml
	 */
	public static function parse( SimpleXMLElement $xml ) {
		$transaction = new Pronamic_Gateways_IDealAdvanced_Transaction();

		if ( $xml->transactionID ) {
			$transaction->setId( Pronamic_XML_Util::filter( $xml->transactionID ) );
		}

		if ( $xml->purchaseID ) {
			$transaction->setPurchaseId( Pronamic_XML_Util::filter( $xml->purchaseID ) );
		}

		if ( $xml->status ) {
			$transaction->setStatus( Pronamic_XML_Util::filter( $xml->status ) );
		}

		if ( $xml->consumerName ) {
			$transaction->setConsumerName( Pronamic_XML_Util::filter( $xml->consumerName ) );
		}

		if ( $xml->consumerAccountNumber ) {
			$transaction->setConsumerAccountNumber( Pronamic_XML_Util::filter( $xml->consumerAccountNumber ) );
		}

		if ( $xml->consumerCity ) {
			$transaction->setConsumerCity( Pronamic_XML_Util::filter( $xml->consumerCity ) );
		}

		return $transaction;
	}
}
