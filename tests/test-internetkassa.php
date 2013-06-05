<?php

class Pronamic_Gateways_InternetKassa_Test extends WP_UnitTestCase {
	function test_signature_in() {
		$internet_kassa = new Pronamic_Gateways_IDealInternetKassa_IDealInternetKassa();

		$signature_in = $internet_kassa->getSignatureIn();

		$this->assertEquals( 'DA39A3EE5E6B4B0D3255BFEF95601890AFD80709', $signature_in );

		$signature_in = $internet_kassa->getSignatureIn();

		$this->assertEquals( 'DA39A3EE5E6B4B0D3255BFEF95601890AFD80709', $signature_in );
	}
}
