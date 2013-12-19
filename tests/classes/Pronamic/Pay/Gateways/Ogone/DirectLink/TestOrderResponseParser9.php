<?php

class Pronamic_Pay_Gateways_Ogone_DirectLink_TestOrderResponseParser9 extends WP_UnitTestCase {
	function test_init() {
		$filename = Pronamic_WordPress_IDeal_Plugin::$dirname . '/tests/data/Pronamic/Pay/Gateways/Ogone/DirectLink/response-status-9.xml';
		
		$simplexml = simplexml_load_file( $filename );
		
		$this->assertInstanceOf( 'SimpleXMLElement', $simplexml );
		
		return $simplexml;
	}

	/**
	 * @depends test_init
	 */
	function test_parser( $simplexml ) {
		$order_response = Pronamic_Gateways_Ogone_XML_OrderResponseParser::parse( $simplexml );
		
		$this->assertInstanceOf( 'Pronamic_Pay_Gateways_Ogone_DirectLink_OrderResponse', $order_response );
		
		return $order_response;
	}

	/**
	 * @depends test_parser
	 */
	function test_values( $order_response ) {
		$expected = new Pronamic_Pay_Gateways_Ogone_DirectLink_OrderResponse();
		$expected->order_id      = '54';
		$expected->pay_id        = '23286404';
		$expected->nc_error      = '0';
		$expected->status        = '9';
		$expected->nc_error_plus = '!';

		$this->assertEquals( $expected, $order_response );
		
		return $banklist;
	}
}
