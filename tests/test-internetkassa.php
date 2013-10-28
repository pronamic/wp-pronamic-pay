<?php

class Pronamic_Gateways_InternetKassa_Test extends WP_UnitTestCase {
	function test_signature_in_empty() {
		$client = new Pronamic_Pay_Gateways_Ogone_OrderStandard_Client();

		$signature_in = $client->getSignatureIn();

		$this->assertEquals( 'DA39A3EE5E6B4B0D3255BFEF95601890AFD80709', $signature_in );
	}
	
	function test_signature_in_from_documentation() {
		// http://pronamic.nl/wp-content/uploads/2012/02/ABNAMRO_e-Com-BAS_EN.pdf #page 11
		$client = new Pronamic_Pay_Gateways_Ogone_OrderStandard_Client();

		$client->setAmount( 15 );
		$client->setCurrency( 'EUR' );
		$client->setLanguage( 'en_US' );
		$client->setOrderId( '1234' );
		$client->setPspId( 'MyPSPID' );
		$client->setPassPhraseIn( 'Mysecretsig1875!?' );

		$signature_in = $client->getSignatureIn();

		$this->assertEquals( 'F4CC376CD7A834D997B91598FA747825A238BE0A', $signature_in );
	}
	
	function test_signature_out_from_documentation() {
		// http://pronamic.nl/wp-content/uploads/2012/02/ABNAMRO_e-Com-BAS_EN.pdf #page 16
		$_GET = array(
			'ACCEPTANCE' => '1234',
			'AMOUNT'     => '15.00',
			'BRAND'      => 'VISA',
			'CARDNO'     => 'xxxxxxxxxxxx1111',
			'CURRENCY'   => 'EUR',
			'NCERROR'    => '0',
			'ORDERID'    => '12',
			'PAYID'      => '32100123',
			'PM'         => 'CreditCard',
			'STATUS'     => '9',
			'SHASIGN'    => '8DC2A769700CA4B3DF87FE8E3B6FD162D6F6A5FA'
		);

		$client = new Pronamic_Pay_Gateways_Ogone_OrderStandard_Client();

		$client->setPspId( 'MyPSPID' );
		$client->setPassPhraseOut( 'Mysecretsig1875!?' );

		$result = $client->verifyRequest( $_GET );

		$this->assertNotSame( false, $result );
	}
}
