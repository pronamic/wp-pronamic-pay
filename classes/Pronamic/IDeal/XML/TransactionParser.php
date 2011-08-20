<?php

namespace Pronamic\IDeal\XML;

use Pronamic\IDeal\Transaction;

/**
 * Title: Transaction XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class TransactionParser extends Parser {
	public static function parse(\SimpleXMLElement $xml, $transaction = null) {
		if(!$transaction instanceof Transaction) {
			$transaction = new Transaction();
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
