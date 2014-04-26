<?php

class Pronamic_Pay_Gateways_PayDutch_TestTransactionRequest extends WP_UnitTestCase {
	function test_request_message() {
		$filename = Pronamic_WP_Pay_Plugin::$dirname . '/tests/data/Pronamic/Pay/Gateways/PayDutch/request-transaction.xml';

		$expected = Pronamic_Gateways_PayDutch_XML_Message::new_dom_document();
		$expected->load( $filename );

		$transaction_request = new Pronamic_Gateways_PayDutch_TransactionRequest( 'personalaccountname', 'personalpassword' );
		$transaction_request->reference = 'Reference123';
		$transaction_request->description = 'Order 3 for product X';
		$transaction_request->amount = 2.99;
		$transaction_request->method_code = '0101';
		$transaction_request->issuer_id = '0121';
		$transaction_request->test = true;
		$transaction_request->success_url = 'https://www.myshop.nl/pay/success/';
		$transaction_request->fail_url = 'https://www.myshop.nl/pay/failed/';

		$message = new Pronamic_Gateways_PayDutch_XML_TransactionRequestMessage( $transaction_request );
		$actual  = $message->get_document();

		$this->assertEquals( $expected, $actual );
	}
}
