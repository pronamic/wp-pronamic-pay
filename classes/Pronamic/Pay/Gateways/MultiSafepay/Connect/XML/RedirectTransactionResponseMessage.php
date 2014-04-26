<?php

/**
 * Title: Transaction XML parser
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Pay_Gateways_MultiSafepay_Connect_XML_RedirectTransactionResponseMessage {
	/**
	 * Parse the specified XML element into an iDEAL transaction object
	 *
	 * @param SimpleXMLElement $xml
	 * @param Pronamic_Gateways_IDealAdvanced_Transaction $transaction
	 */
	public static function parse( SimpleXMLElement $xml ) {
		$message = new Pronamic_Pay_Gateways_MultiSafepay_Connect_XML_RedirectTransactionResponseMessage();

		$message->result = Pronamic_XML_Util::filter( $xml['result'] );
		$message->transaction = Pronamic_Pay_Gateways_MultiSafepay_Connect_XML_TransactionParser::parse( $xml->transaction );

		return $message;
	}
}
