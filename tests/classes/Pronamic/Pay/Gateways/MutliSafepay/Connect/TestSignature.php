<?php

/**
 * @see http://pronamic.nl/wp-content/uploads/2013/04/BPE-3.0-Gateway-HTML.1.02.pdf
 * @author remco
 *
 */
class Pronamic_Pay_Gateways_MultiSafepay_Connect_TestSignature extends WP_UnitTestCase {
	function test_signature() {
		$amount         = 50;
		$currency       = 'EUR';
		$account        = 'Test';
		$site_id        = '1234';
		$transaction_id = '1234567890';

		$signature = Pronamic_Pay_Gateways_MultiSafepay_Connect_Signature::generate( $amount, $currency, $account, $site_id, $transaction_id );
		
		$expected = 'af90311cf82cbf9bb4fcda5a54005b92';

		$this->assertEquals( $expected, $signature );
	}
}
