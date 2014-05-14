<?php

/**
 * @see http://pronamic.nl/wp-content/uploads/2013/04/BPE-3.0-Gateway-HTML.1.02.pdf
 * @author remco
 *
 */
class Pronamic_Pay_Gateways_Buckaroo_TestSignatureSorting extends WP_UnitTestCase {
	/**
	 * Test signature sorting
	 */
	function test_signature_sorting() {
		/**
		 * Sort these parameters alphabetically on the parameter name (brq_amount comes before brq_websitekey).
		 *
		 * Note: sorting must be case insensitive (brq_active comes before BRQ_AMOUNT) but casing in parameter names and values must be preserved.
		 */
		$data = array(
			'brq_websitekey' => '123456',
			'brq_amount'     => '50.00',
			'BRQ_AMOUNT'     => '25.00',
			'brq_active'     => 'true',
		);

		$expected = array(
			'brq_active'     => 'true',
			'BRQ_AMOUNT'     => '25.00',
			'brq_amount'     => '50.00',
			'brq_websitekey' => '123456',
		);

		// Sort
		$data = Pronamic_Gateways_Buckaroo_Security::sort( $data );

		// Keys
		$keys_data     = implode( "\n", array_keys( $data ) );
		$keys_expected = implode( "\n", array_keys( $expected ) );

		// Assert
		$this->assertEquals( $keys_expected, $keys_data );
	}
}
