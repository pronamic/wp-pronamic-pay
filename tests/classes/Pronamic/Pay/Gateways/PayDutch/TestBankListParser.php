<?php

class Pronamic_Pay_Gateways_PayDutch_TestBankListParser extends WP_UnitTestCase {
	function test_init() {
		$filename = Pronamic_WP_Pay_Plugin::$dirname . '/tests/data/Pronamic/Pay/Gateways/PayDutch/response-retrievebanklist.xml';

		$simplexml = simplexml_load_file( $filename );

		$this->assertInstanceOf( 'SimpleXMLElement', $simplexml );

		return $simplexml;
	}

	/**
	 * @depends test_init
	 */
	function test_parser( $simplexml ) {
		$banklist = Pronamic_Gateways_PayDutch_XML_BankListParser::parse( $simplexml );

		$this->assertInternalType( 'array', $banklist );

		return $banklist;
	}

	/**
	 * @depends test_parser
	 */
	function test_values( $banklist ) {
		$expected = array(
			'0121' => 'Test Issuer',
			'0151' => 'Test Issuer 2',
		);

		$this->assertEquals( $expected, $banklist );

		return $banklist;
	}
}
