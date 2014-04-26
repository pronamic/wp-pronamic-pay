<?php

/**
 * @see http://pronamic.nl/wp-content/uploads/2013/04/BPE-3.0-Gateway-HTML.1.02.pdf
 * @author remco
 *
 */
class Pronamic_Pay_Gateways_Buckaroo_TestSignature extends WP_UnitTestCase {
	/**
	 * @dataProvider provider_case_mix
	 */
	function test_get_signature( $data ) {
		$signature = Pronamic_Gateways_Buckaroo_Security::get_signature( $data );

		$this->assertEquals( '84e9802d60d727ade4a845c43033051d5758ce25', $signature );
	}

	/**
	 * @dataProvider provider_case_mix
	 */
	function test_signature_filter( $data ) {
		$data = Pronamic_Gateways_Buckaroo_Security::filter_data( $data );

		$this->assertArrayNotHasKey( 'random_1234567890', $data );
	}

	/////////////////////////////////////////////////

	/**
	 * @dataProvider provider
	 */
	function test_create_signature( $data ) {
		$secret_key = '29E9BEB3F3428B2BCAA678DEC489A86A';

		$data = Pronamic_Gateways_Buckaroo_Util::urldecode( $data );

		$signature = Pronamic_Gateways_Buckaroo_Security::get_signature( $data );

		$signature_check = Pronamic_Gateways_Buckaroo_Security::create_signature( $data, $secret_key, true );

		$this->assertEquals( $signature, $signature_check );
	}

	/////////////////////////////////////////////////

	public function provider() {
		$data = array(
			'BRQ_AMOUNT'                       => '50.00',
			'BRQ_CURRENCY'                     => 'EUR',
			'BRQ_CUSTOMER_NAME'                => 'J.+de+Tèster',
			'BRQ_INVOICENUMBER'                => '1234567890',
			'BRQ_PAYMENT'                      => 'F978A56A36D04217BD93157E2B14A578',
			'BRQ_PAYMENT_METHOD'               => 'ideal',
			'BRQ_SERVICE_IDEAL_CONSUMERBIC'    => 'RABONL2U',
			'BRQ_SERVICE_IDEAL_CONSUMERIBAN'   => 'NL44RABO0123456789',
			'BRQ_SERVICE_IDEAL_CONSUMERISSUER' => 'Rabobank',
			'BRQ_SERVICE_IDEAL_CONSUMERNAME'   => 'J.+de+Tèster',
			'BRQ_STATUSCODE'                   => '190',
			'BRQ_STATUSCODE_DETAIL'            => 'S001',
			'BRQ_STATUSMESSAGE'                => 'Payment+successfully+processed',
			'BRQ_TEST'                         => 'true',
			'BRQ_TIMESTAMP'                    => '2014-01-01+12:00:00',
			'BRQ_TRANSACTIONS'                 => '098F6BCD4621D373CADE4E832627B4F6',
			'BRQ_WEBSITEKEY'                   => 'fpK0odPM3A',
			'BRQ_SIGNATURE'                    => '84e9802d60d727ade4a845c43033051d5758ce25',
		);

		return array(
			array( $data ),
		);
	}

	public function provider_case_mix() {
		$data_mixcase = array(
			'Brq_amount'                       => '55.00',
			'Brq_currency'                     => 'EUR',
			'Brq_customer_name'                => 'J.+de+Tèster',
			'Brq_invoicenumber'                => '1389773524',
			'Brq_payment'                      => 'F978A56A36D04217BD93157E2B14A578',
			'Brq_payment_method'               => 'ideal',
			'Brq_service_ideal_consumerbic'    => 'RABONL2U',
			'Brq_service_ideal_consumeriban'   => 'NL44RABO0123456789',
			'Brq_service_ideal_consumerissuer' => 'Rabobank',
			'Brq_service_ideal_consumername'   => 'J.+de+Tèster',
			'Brq_statuscode'                   => '190',
			'Brq_statuscode_detail'            => 'S001',
			'Brq_statusmessage'                => 'Payment+successfully+processed',
			'Brq_test'                         => 'true',
			'Brq_timestamp'                    => '2014-01-01+12:00:00',
			'Brq_transactions'                 => '098F6BCD4621D373CADE4E832627B4F6',
			'Brq_websitekey'                   => 'fpK0odPM3A',
			'Brq_signature'                    => '84e9802d60d727ade4a845c43033051d5758ce25',
			'random_1234567890'                => 'random_1234567890',
		);

		$data_uppercase = array_change_key_case( $data_mixcase, CASE_UPPER );
		$data_lowercase = array_change_key_case( $data_mixcase, CASE_LOWER );

		return array(
			array( $data_mixcase ),
			array( $data_uppercase ),
			array( $data_lowercase ),
		);
	}
}
