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
		$response = Pronamic_Gateways_MultiSafepay_Connect_XML_RedirectTransactionResponseParser::parse( $simplexml );
		
		$this->assertInstanceOf( 'Pronamic_Pay_Gateways_MultiSafepay_Connect_OrderResponse', $response );
		
		return $response;
	}

	/**
	 * @depends test_parser
	 */
	function test_values( $response ) {
		$expected = new Pronamic_Gateways_MultiSafepay_Connect_RedirectTransactionResponse();
		$expected->order_id      = '52';
		$expected->pay_id        = '0';
		$expected->nc_error      = '50001123';
		$expected->status        = '0';
		$expected->nc_error_plus = 'Card type not active for the merchant';

		$this->assertEquals( $expected, $order_response );
		
		return $banklist;
	}
}
