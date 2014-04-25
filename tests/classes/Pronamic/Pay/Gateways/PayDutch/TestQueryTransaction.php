<?php

class Pronamic_Pay_Gateways_PayDutch_TestQueryRequest extends WP_UnitTestCase {
	function test_request_message() {
		$filename = Pronamic_WordPress_IDeal_Plugin::$dirname . '/tests/data/Pronamic/Pay/Gateways/PayDutch/request-query.xml';

		$expected = Pronamic_Gateways_PayDutch_XML_Message::new_dom_document();
		$expected->load( $filename );

		$merchant = new Pronamic_Gateways_PayDutch_Merchant( 'personalAccountName', 'personalPassword' );
		$merchant->reference = 'Reference123';
		$merchant->test = true;

		$message = new Pronamic_Gateways_PayDutch_XML_QueryRequestMessage( $merchant );
		$actual  = $message->get_document();

		$this->assertEquals( $expected, $actual );
	}
}
