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
		
	}

	/**
	 * @depends test_parser
	 */
	function test_values( $response ) {
		
	}
}
