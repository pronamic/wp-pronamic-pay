<?php

/**
 * Title: Transaction XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvanced_XML_TransactionParser extends Pronamic_Gateways_IDealAdvanced_XML_Parser {
	/**
	 * Parse the specified XML element into an iDEAL transaction object
	 * 
	 * @param SimpleXMLElement $xml
	 * @param Pronamic_IDeal_Transaction $transaction
	 */
	public static function parse(SimpleXMLElement $xml, $transaction = null) {
		if(!$transaction instanceof Pronamic_IDeal_Transaction) {
			$transaction = new Pronamic_IDeal_Transaction();
		}

		if($xml->transactionID) {
			$transaction->setId((string) $xml->transactionID);
		}

		if($xml->purchaseID) {
			$transaction->setPurchaseId((string) $xml->purchaseID);
		}

		if($xml->status) {
			$transaction->setStatus((string) $xml->status);
		}

		if($xml->consumerName) {
			$transaction->setConsumerName((string) $xml->consumerName);
		}

		if($xml->consumerAccountNumber) {
			$transaction->setConsumerAccountNumber((string) $xml->consumerAccountNumber);
		}

		if($xml->consumerCity) {
			$transaction->setConsumerCity((string) $xml->consumerCity);
		}

		return $transaction;
	}
}
