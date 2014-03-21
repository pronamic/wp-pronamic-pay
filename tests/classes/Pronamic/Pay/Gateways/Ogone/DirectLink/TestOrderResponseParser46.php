<?php

class Pronamic_Pay_Gateways_Ogone_DirectLink_TestOrderResponseParser46 extends WP_UnitTestCase {
	function test_init() {
		$filename = Pronamic_WordPress_IDeal_Plugin::$dirname . '/tests/data/Pronamic/Pay/Gateways/Ogone/DirectLink/response-status-46.xml';
		
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
		$filename = Pronamic_WordPress_IDeal_Plugin::$dirname . '/tests/data/Pronamic/Pay/Gateways/Ogone/DirectLink/response-status-46-html-answer.html';

		$expected = new Pronamic_Pay_Gateways_Ogone_DirectLink_OrderResponse();
		$expected->order_id      = '1387195001';
		$expected->pay_id        = '26187584';
		$expected->nc_error      = '0';
		$expected->status        = '46';
		$expected->nc_error_plus = 'Identification requested';
		$expected->html_answer   = file_get_contents( $filename );

		$this->assertEquals( $expected, $order_response );
	}
}
