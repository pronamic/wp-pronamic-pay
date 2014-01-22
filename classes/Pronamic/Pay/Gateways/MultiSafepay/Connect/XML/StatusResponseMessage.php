<?php

/**
 * Title: Transaction XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Pay_Gateways_MultiSafepay_Connect_XML_StatusResponseMessage {
	/**
	 * Parse the specified XML element into an iDEAL transaction object
	 * 
	 * @param SimpleXMLElement $xml
	 * @param Pronamic_Gateways_IDealAdvanced_Transaction $transaction
	 */
	public static function parse( SimpleXMLElement $xml ) {
		$message = new Pronamic_Pay_Gateways_MultiSafepay_Connect_XML_StatusResponseMessage();
		
		$message->result = Pronamic_XML_Util::filter( $xml['result'] );

		// E-wallet
		if ( $xml->ewallet ) {
			$ewallet = new stdClass();
			$ewallet->id = Pronamic_XML_Util::filter( $xml->ewallet->id );
			$ewallet->status = Pronamic_XML_Util::filter( $xml->ewallet->status );
			$ewallet->created = Pronamic_XML_Util::filter( $xml->ewallet->created );
			$ewallet->modified = Pronamic_XML_Util::filter( $xml->ewallet->modified );
			$ewallet->reason_code = Pronamic_XML_Util::filter( $xml->ewallet->reasoncode );
			$ewallet->reason = Pronamic_XML_Util::filter( $xml->ewallet->reason );
			
			$message->ewallet = $ewallet;
		}

		// Customer
		if ( $xml->customer ) {
			$customer = new stdClass();
			$customer->currency = Pronamic_XML_Util::filter( $xml->customer->currency );
			$customer->amount = Pronamic_XML_Util::filter( $xml->customer->amount );
			$customer->exchange_rate = Pronamic_XML_Util::filter( $xml->customer->exchange_rate );
			$customer->first_name = Pronamic_XML_Util::filter( $xml->customer->firstname );
			$customer->last_name = Pronamic_XML_Util::filter( $xml->customer->lastname );
			$customer->last_name = Pronamic_XML_Util::filter( $xml->customer->lastname );
			$customer->city = Pronamic_XML_Util::filter( $xml->customer->city );
			$customer->state = Pronamic_XML_Util::filter( $xml->customer->state );
			$customer->country = Pronamic_XML_Util::filter( $xml->customer->country );
			
			$message->customer = $customer;
		}

		// Transaction
		if ( $xml->transaction ) {
			$transaction = new stdClass();
			$transaction->id = Pronamic_XML_Util::filter( $xml->transaction->id );
			$transaction->currency = Pronamic_XML_Util::filter( $xml->transaction->currency );
			$transaction->amount = Pronamic_XML_Util::filter( $xml->transaction->amount );
			$transaction->description = Pronamic_XML_Util::filter( $xml->transaction->description );
			$transaction->var1 = Pronamic_XML_Util::filter( $xml->transaction->var1 );
			$transaction->var2 = Pronamic_XML_Util::filter( $xml->transaction->var2 );
			$transaction->var3 = Pronamic_XML_Util::filter( $xml->transaction->var3 );
			$transaction->items = Pronamic_XML_Util::filter( $xml->transaction->items );
			
			$message->transaction = $transaction;
		}
		
		// Payment details
		if ( $xml->paymentdetails ) {
			$payment_details = new stdClass();
			$payment_details->type = Pronamic_XML_Util::filter( $xml->paymentdetails->type );
			$payment_details->account_iban = Pronamic_XML_Util::filter( $xml->paymentdetails->accountiban );
			$payment_details->account_bic = Pronamic_XML_Util::filter( $xml->paymentdetails->accountbic );
			$payment_details->account_id = Pronamic_XML_Util::filter( $xml->paymentdetails->accountid );
			$payment_details->account_holder_name = Pronamic_XML_Util::filter( $xml->paymentdetails->accountholdername );
			$payment_details->external_transaction_id = Pronamic_XML_Util::filter( $xml->paymentdetails->externaltransactionid );

			$message->payment_details = $payment_details;
		}

		return $message;
	}
}
