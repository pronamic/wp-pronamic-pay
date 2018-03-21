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
	 * Test set and get.
	 *
	 * @dataProvider get_and_set_provider
	 */
	public function test_set_and_get( $set_function, $get_function, $value ) {
		$subscription = new Subscription();

		$subscription->$set_function( $value );

		$this->assertEquals( $value, $subscription->$get_function() );
	}

	public function get_and_set_provider() {
		return array(
			array( 'set_id',             'get_id',             uniqid()    ),
			array( 'set_status',         'get_status',         'completed' ),
			array( 'set_transaction_id', 'get_transaction_id', uniqid()    ),
		);
	}

	/**
	 * Test set.
	 *
	 * @dataProvider set_provider
	 */
	public function test_set( $set_function, $property, $value ) {
		$subscription = new Subscription();

		$subscription->$set_function( $value );

		$this->assertEquals( $value, $subscription->$property );
	}

	public function set_provider() {
		return array(
			array( 'set_consumer_name', 'consumer_name', 'John Doe' ),
			array( 'set_consumer_iban', 'consumer_iban', 'NL56 RABO 0108 6347 79' ),
			array( 'set_consumer_bic',  'consumer_bic',  'RABONL2U' ),
		);
	}

	/**
	 * Test get.
	 *
	 * @dataProvider get_provider
	 */
	public function test_get( $property, $get_function, $value ) {
		$subscription = new Subscription();

		$subscription->$property = $value;

		$this->assertEquals( $value, $subscription->$get_function() );
	}

	public function get_provider() {
		return array(
			array( 'key',                 'get_key',             uniqid() ),
			array( 'source',              'get_source',          'woocommerce' ),
			array( 'source_id',           'get_source_id',       '1234' ),
			array( 'frequency',           'get_frequency',       'daily' ),
			array( 'interval',            'get_interval',        '1' ),
			array( 'interval_period',     'get_interval_period', 'Y' ),
			array( 'description',         'get_description',     'Lorem ipsum dolor sit amet, consectetur.' ),
			array( 'currency',            'get_currency',        'EUR' ),
			array( 'amount',              'get_amount',          89.95 ),
		);
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
}
