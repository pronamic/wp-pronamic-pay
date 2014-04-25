<?php

class Pronamic_Gateways_Qantani_Test extends WP_UnitTestCase {
	function test_checksum() {
		// http://pronamic.nl/wp-content/uploads/2013/05/documentation-for-qantani-xml-v1.pdf #page 1
		$parameters = array(
			'Id'     => '5',
			'Name'   => 'Qantani',
			'Action' => 'Pay',
		);

		$secret = '12345';

		$checksum = Pronamic_Gateways_Qantani_Qantani::create_checksum( $parameters, $secret );

		$this->assertEquals( 'f9e9ed53d1c0565a97d6b031bacebb4a2b4f9d4d', $checksum );
	}
}
