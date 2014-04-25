<?php

class Pronamic_Pay_Gateways_MultiSafepay_Connect_TestStatusParser extends WP_UnitTestCase {
	function test_init() {
		$filename = Pronamic_WordPress_IDeal_Plugin::$dirname . '/tests/data/Pronamic/Pay/Gateways/MultiSafepay/Connect/status-response.xml';

		$simplexml = simplexml_load_file( $filename );

		$this->assertInstanceOf( 'SimpleXMLElement', $simplexml );

		return $simplexml;
	}

	/**
	 * @depends test_init
	 */
	function test_parser( $simplexml ) {
		$message = Pronamic_Pay_Gateways_MultiSafepay_Connect_XML_StatusResponseMessage::parse( $simplexml );

		$this->assertInstanceOf( 'Pronamic_Pay_Gateways_MultiSafepay_Connect_XML_StatusResponseMessage', $message );

		return $message;
	}

	/**
	 * @depends test_parser
	 */
	function test_values( $message ) {
		$expected = new Pronamic_Pay_Gateways_MultiSafepay_Connect_XML_StatusResponseMessage();
		$expected->result = 'ok';

		// E-wallet
		$ewallet = new stdClass();
		$ewallet->id = '12345';
		$ewallet->status = 'completed';
		$ewallet->created = '20070723171623';
		$ewallet->modified = '20070903155907';
		$ewallet->reason_code = null;
		$ewallet->reason = null;

		$expected->ewallet = $ewallet;

		// Customer
		$customer = new stdClass();
		$customer->currency = 'EUR';
		$customer->amount = '1000';
		$customer->exchange_rate = '1';
		$customer->first_name = 'First';
		$customer->last_name = 'Last';
		$customer->city = 'City';
		$customer->state = null;
		$customer->country = 'NL';

		$expected->customer = $customer;

		// Transaction
		$transaction = new stdClass();
		$transaction->id = 'ABCD1234';
		$transaction->currency = 'EUR';
		$transaction->amount = '1000';
		$transaction->description = 'My Description';
		$transaction->var1 = null;
		$transaction->var2 = null;
		$transaction->var3 = null;
		$transaction->items = 'My Items';

		$expected->transaction = $transaction;

		// Payment Details
		$payment_details = new stdClass();
		$payment_details->type = 'IDEAL';
		$payment_details->account_iban = null;
		$payment_details->account_bic = null;
		$payment_details->account_id = null;
		$payment_details->account_holder_name = null;
		$payment_details->external_transaction_id = null;

		$expected->payment_details = $payment_details;

		$this->assertEquals( $expected, $message );
	}
}
