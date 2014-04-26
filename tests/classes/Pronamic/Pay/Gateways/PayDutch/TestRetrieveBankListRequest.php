<?php

class Pronamic_Pay_Gateways_PayDutch_TestRetriveBankListRequest extends WP_UnitTestCase {
	function test_request_message() {
		$filename = Pronamic_WP_Pay_Plugin::$dirname . '/tests/data/Pronamic/Pay/Gateways/PayDutch/request-retrievebanklist.xml';

		$expected = Pronamic_Gateways_PayDutch_XML_Message::new_dom_document();
		$expected->load( $filename );

		$message = new Pronamic_Gateways_PayDutch_XML_RetrieveBankListRequestMessage( '0101', true );
		$actual  = $message->get_document();

		$this->assertEquals( $expected, $actual );
	}
}
