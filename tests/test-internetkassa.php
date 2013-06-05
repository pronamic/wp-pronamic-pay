<?php

class Pronamic_Gateways_InternetKassa_Test extends WP_UnitTestCase {
	function test_signature_in_empty() {
		$internet_kassa = new Pronamic_Gateways_IDealInternetKassa_IDealInternetKassa();

		$file = dirname( Pronamic_WordPress_IDeal_Plugin::$file ) . '/other/calculations-parameters-sha-in.txt';
		$internet_kassa->setCalculationsParametersIn( file( $file, FILE_IGNORE_NEW_LINES ) );
		
		$file = dirname( Pronamic_WordPress_IDeal_Plugin::$file ) . '/other/calculations-parameters-sha-out.txt';
		$internet_kassa->setCalculationsParametersOut( file( $file, FILE_IGNORE_NEW_LINES ) );

		$signature_in = $internet_kassa->getSignatureIn();

		$this->assertEquals( 'DA39A3EE5E6B4B0D3255BFEF95601890AFD80709', $signature_in );
	}
	
	function test_signature_in_from_documentation() {
		// http://pronamic.nl/wp-content/uploads/2012/02/ABNAMRO_e-Com-BAS_EN.pdf
		$internet_kassa = new Pronamic_Gateways_IDealInternetKassa_IDealInternetKassa();

		$file = dirname( Pronamic_WordPress_IDeal_Plugin::$file ) . '/other/calculations-parameters-sha-in.txt';
		$internet_kassa->setCalculationsParametersIn( file( $file, FILE_IGNORE_NEW_LINES ) );
		
		$file = dirname( Pronamic_WordPress_IDeal_Plugin::$file ) . '/other/calculations-parameters-sha-out.txt';
		$internet_kassa->setCalculationsParametersOut( file( $file, FILE_IGNORE_NEW_LINES ) );

		$internet_kassa->setAmount( 15 );
		$internet_kassa->setCurrency( 'EUR' );
		$internet_kassa->setLanguage( 'en_US' );
		$internet_kassa->setOrderId( '1234' );
		$internet_kassa->setPspId( 'MyPSPID' );
		$internet_kassa->setPassPhraseIn( 'Mysecretsig1875!?' );

		$signature_in = $internet_kassa->getSignatureIn();

		$this->assertEquals( 'F4CC376CD7A834D997B91598FA747825A238BE0A', $signature_in );
	}
}
