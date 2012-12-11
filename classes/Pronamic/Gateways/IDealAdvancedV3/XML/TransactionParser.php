<?php

/**
 * Title: Transaction XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvancedV3_XML_TransactionParser extends Pronamic_Gateways_IDealAdvancedV3_XML_Parser {
	/**
	 * Parse the specified XML element into an iDEAL transaction object
	 * 
	 * @param SimpleXMLElement $xml
	 * @param Pronamic_Gateways_IDealAdvanced_Transaction $transaction
	 */
	public static function parse( SimpleXMLElement $xml, $transaction = null ) {
		if ( !$transaction instanceof Pronamic_Gateways_IDealAdvancedV3_Transaction ) {
			$transaction = new Pronamic_Gateways_IDealAdvancedV3_Transaction();
		}

		if ( $xml->transactionID ) {
			$transaction->set_id( (string) $xml->transactionID );
		}

		if ( $xml->purchaseID ) {
			$transaction->set_purchase_id( (string) $xml->purchaseID );
		}

		if ( $xml->status ) {
			$transaction->set_status( (string) $xml->status );
		}

		if ( $xml->consumerName ) {
			$transaction->set_consumer_name( (string) $xml->consumerName );
		}

		if ( $xml->consumerIBAN ) {
			$transaction->set_consumer_iban( (string) $xml->consumerIBAN );
		}

		if ( $xml->consumerBIC ) {
			$transaction->set_consumer_bic( (string) $xml->consumerBIC );
		}

		return $transaction;
	}
}
