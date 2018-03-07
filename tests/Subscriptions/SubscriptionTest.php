<?php
/**
 * Subscription test
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Subscriptions;

use WP_UnitTestCase;

/**
 * Subscription test
 *
 * @author Remco Tolsma
 * @version 1.0
 */
class SubscriptionTest extends WP_UnitTestCase {
	/**
	 * Test construct subscription object.
	 */
	public function test_construct() {
		$subscription = new Subscription();

		$this->assertInstanceOf( __NAMESPACE__ . '\Subscription', $subscription );
	}

	/**
	 * Test getting no subscription.
	 *
	 * @see https://github.com/easydigitaldownloads/easy-digital-downloads/blob/2.8.18/tests/tests-payment-class.php#L70-L79
	 */
	public function test_getting_no_subscription() {
		$subscription = new Subscription();

		$this->assertNull( $subscription->get_id() );
	}

	/** 
	 * Test setting and getting the subscription ID.
	 */
	public function test_set_and_get_id() {
		$subscription = new Subscription();

		$id = uniqid();

		$subscription->set_id( $id );

		$this->assertEquals( $id, $subscription->get_id() );
	}

	/** 
	 * Test setting and getting the subscription status.
	 */
	public function test_set_and_get_status() {
		$subscription = new Subscription();

		$status = 'completed';

		$subscription->set_status( $status );

		$this->assertEquals( $status, $subscription->get_status() );
	}
}
