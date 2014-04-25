<?php

class Pronamic_Gateways_Sisow_Test extends WP_UnitTestCase {
	function test_transaction_request_sha1() {
		// http://pronamic.nl/wp-content/uploads/2013/02/sisow-rest-api-v3.2.1.pdf #page 10
		$sha1 = Pronamic_Gateways_Sisow_Sisow::create_transaction_sha1(
			'123456789', // purchase_id
			'uniqueentrance', // entrance_code
			10, // amount
			null, // shop_id
			'0123456', // merchant_id
			'b36d8259346eaddb3c03236b37ad3a1d7a67cec6' // merchant_key
		);

		$this->assertEquals( 'cb2461bd40ed1a77a6d837a560bfcbc3e03d6c3c', $sha1 );
	}

	function test_status_request_sha1() {
		// http://pronamic.nl/wp-content/uploads/2013/02/sisow-rest-api-v3.2.1.pdf #page 14
		$sha1 = Pronamic_Gateways_Sisow_Sisow::create_status_sha1(
			'0050000513407955', // transaction_id
			null, // shop_id
			'0123456', // merchant_id
			'b36d8259346eaddb3c03236b37ad3a1d7a67cec6' // merchant_key
		);

		$this->assertEquals( '03fa4fda5cacfe5e2ba123a47690d99f07c6fbd1', $sha1 );
	}
}
