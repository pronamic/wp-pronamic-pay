<?php

class Pronamic_Pay_Gateways_PayDutch_TestPaymentInfoParser extends WP_UnitTestCase {
	function test_init() {
		$filename = Pronamic_WordPress_IDeal_Plugin::$dirname . '/tests/data/Pronamic/Pay/Gateways/PayDutch/response-query.xml';
		
		$simplexml = simplexml_load_file( $filename );
		
		$this->assertInstanceOf( 'SimpleXMLElement', $simplexml );
		
		return $simplexml;
	}

	/**
	 * @depends test_init
	 */
	function test_parser( $simplexml ) {
		$payment_info = Pronamic_Gateways_PayDutch_XML_PaymentInfoParser::parse( $simplexml->paymentinfo );
		
		$this->assertInstanceOf( 'Pronamic_Gateways_PayDutch_PaymentInfo', $payment_info );
		
		return $payment_info;
	}

	/**
	 * @depends test_parser
	 */
	function test_values( $payment_info ) {	
		$expected = new Pronamic_Gateways_PayDutch_PaymentInfo();
		$expected->test            = false;
		$expected->id              = 'cdd622d5-5719-4482-93a9-4631f1263cba';
		$expected->description     = 'Order 3 for product X';
		$expected->amount          = 2.99;
		$expected->state           = Pronamic_Gateways_PayDutch_States::SUCCESS;
		$expected->reference       = 'Reference123';
		$expected->methodcode      = '0101';
		$expected->methodname      = 'WeDeal';
		$expected->consumername    = 'P Dutch ';
		$expected->consumercity    = 'Heerlen';
		$expected->consumeraccount = '123456789';
		$expected->consumercountry = 'NL';
		$expected->created         = '01/13/2009 4:29:57 AM';

		$this->assertEquals( $expected, $payment_info );
		
		return $banklist;
	}
}
