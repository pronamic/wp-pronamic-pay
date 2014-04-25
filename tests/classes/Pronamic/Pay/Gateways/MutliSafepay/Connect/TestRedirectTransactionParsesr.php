<?php

class Pronamic_Pay_Gateways_MultiSafepay_Connect_TestRedirectTransactionParser extends WP_UnitTestCase {
	function test_init() {
		$filename = Pronamic_WordPress_IDeal_Plugin::$dirname . '/tests/data/Pronamic/Pay/Gateways/MultiSafepay/Connect/redirect-transaction-response.xml';

		$simplexml = simplexml_load_file( $filename );

		$this->assertInstanceOf( 'SimpleXMLElement', $simplexml );

		return $simplexml;
	}

	/**
	 * @depends test_init
	 */
	function test_parser( $simplexml ) {
		$message = Pronamic_Pay_Gateways_MultiSafepay_Connect_XML_RedirectTransactionResponseMessage::parse( $simplexml );

		$this->assertInstanceOf( 'Pronamic_Pay_Gateways_MultiSafepay_Connect_XML_RedirectTransactionResponseMessage', $message );

		return $message;
	}

	/**
	 * @depends test_parser
	 */
	function test_values( $message ) {
		$expected = new Pronamic_Pay_Gateways_MultiSafepay_Connect_XML_RedirectTransactionResponseMessage();
		$expected->result = 'ok';

		$transaction = new Pronamic_Pay_Gateways_MultiSafepay_Connect_Transaction();
		$transaction->id = 'ABCD1234';
		$transaction->payment_url = 'http://www.multisafepay.com/pay/...&lang=en';

		$expected->transaction = $transaction;

		$this->assertEquals( $expected, $message );
	}
}
