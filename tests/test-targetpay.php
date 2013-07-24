<?php

class Pronamic_Gateways_TargetPay_Test extends WP_UnitTestCase {
	function test_parse_status_ok() {
		$status_string = '000000 OK';

		$status = Pronamic_Gateways_TargetPay_TargetPay::parse_status_string( $status_string );

		$this->assertInstanceOf( 'Pronamic_Gateways_TargetPay_Status', $status );

		$this->assertEquals( '000000', $status->code );
		$this->assertEquals( 'OK', $status->description );
	}

	function test_parse_status_ok_cinfo() {
		$status_string = '00000 OK|123456789|Pronamic|Drachten';

		$status = Pronamic_Gateways_TargetPay_TargetPay::parse_status_string( $status_string );

		$this->assertInstanceOf( 'Pronamic_Gateways_TargetPay_Status', $status );

		$this->assertEquals( '00000', $status->code );
		$this->assertEquals( 'OK', $status->description );
		$this->assertEquals( '123456789', $status->account_number );
		$this->assertEquals( 'Pronamic', $status->account_name );
		$this->assertEquals( 'Drachten', $status->account_city );
	}

	function test_parse_status_tp0010() {
		$status_string = 'TP0010 Transaction has not been completed, try again later';

		$status = Pronamic_Gateways_TargetPay_TargetPay::parse_status_string( $status_string );

		$this->assertInstanceOf( 'Pronamic_Gateways_TargetPay_Status', $status );

		$this->assertEquals( 'TP0010', $status->code );
		$this->assertEquals( 'Transaction has not been completed, try again later', $status->description );
	}
}
