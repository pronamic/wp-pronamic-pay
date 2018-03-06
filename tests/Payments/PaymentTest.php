<?php
/**
 * Payment test
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Payments;

use stdClass;
use WP_UnitTestCase;

/**
 * Payment test
 *
 * @author Remco Tolsma
 * @version 1.0
 */
class PaymentTest extends WP_UnitTestCase {
	/**
	 * Test construct payment object.
	 */
	public function test_construct() {
		$payment = new Payment();

		$this->assertInstanceOf( __NAMESPACE__ . '\Payment', $payment );
	}

	/** 
	 * Test setting and getting the payment ID.
	 */
	public function test_set_and_get_id() {
		$payment = new Payment();

		$id = uniqid();

		$payment->set_id( $id );

		$this->assertEquals( $id, $payment->get_id() );
	}

	/** 
	 * Test setting and getting the payment transaction ID.
	 */
	public function test_set_and_get_transaction_id() {
		$payment = new Payment();

		$transaction_id = uniqid();

		$payment->set_transaction_id( $transaction_id );

		$this->assertEquals( $transaction_id, $payment->get_transaction_id() );
	}

	/** 
	 * Test setting and getting the payment status.
	 */
	public function test_set_and_get_status() {
		$payment = new Payment();

		$status = 'completed';

		$payment->set_status( $status );

		$this->assertEquals( $status, $payment->get_status() );
	}
}
