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

use Pronamic\WordPress\Money\Money;
use Pronamic\WordPress\Pay\CreditCard;
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
	 * Test set and get.
	 *
	 * @dataProvider get_and_set_provider
	 */
	public function test_set_and_get( $set_function, $get_function, $value ) {
		$payment = new Payment();

		$payment->$set_function( $value );

		$this->assertEquals( $value, $payment->$get_function() );
	}

	public function get_and_set_provider() {
		return array(
			array( 'set_amount',         'get_amount',         new Money( 89.95, 'EUR' ) ),
			array( 'set_id',             'get_id',             uniqid()    ),
			array( 'set_transaction_id', 'get_transaction_id', uniqid()    ),
			array( 'set_status',         'get_status',         'completed' ),
		);
	}

	/**
	 * Test set.
	 *
	 * @dataProvider set_provider
	 */
	public function test_set( $set_function, $property, $value ) {
		$payment = new Payment();

		$payment->$set_function( $value );

		$this->assertEquals( $value, $payment->$property );
	}

	public function set_provider() {
		return array(
			array( 'set_consumer_name',           'consumer_name',           'John Doe' ),
			array( 'set_consumer_account_number', 'consumer_account_number', '1086.34.779' ),
			array( 'set_consumer_iban',           'consumer_iban',           'NL56 RABO 0108 6347 79' ),
			array( 'set_consumer_bic',            'consumer_bic',            'RABONL2U' ),
			array( 'set_consumer_city',           'consumer_city',           'Drachten' ),
		);
	}

	/**
	 * Test get.
	 *
	 * @dataProvider get_provider
	 */
	public function test_get( $property, $get_function, $value ) {
		$payment = new Payment();

		$payment->$property = $value;

		$this->assertEquals( $value, $payment->$get_function() );
	}

	public function get_provider() {
		return array(
			array( 'order_id',            'get_order_id',            1234 ),
			array( 'method',              'get_method',              'ideal' ),
			array( 'issuer',              'get_issuer',              'ideal_KNABNL2H' ),
			array( 'language',            'get_language',            'nl' ),
			array( 'locale',              'get_locale',              'nl_NL' ),
			array( 'description',         'get_description',         'Lorem ipsum dolor sit amet, consectetur.' ),
			array( 'email',               'get_email',               'john.doe@example.com' ),
			array( 'first_name',          'get_first_name',          'John' ),
			array( 'last_name',           'get_last_name',           'Doe' ),
			array( 'customer_name',       'get_customer_name',       'John Doe' ),
			array( 'address',             'get_address',             'Burgemeester Wuiteweg 39b' ),
			array( 'city',                'get_city',                'Drachten' ),
			array( 'zip',                 'get_zip',                 '9203 KA' ),
			array( 'country',             'get_country',             'NL' ),
			array( 'telephone_number',    'get_telephone_number',    '1234567890' ),
			array( 'analytics_client_id', 'get_analytics_client_id', 'GA1.2.1234567890.1234567890' ),
			array( 'entrance_code',       'get_entrance_code',       uniqid() ),
		);
	}

	/**
	 * Test getting no payment.
	 *
	 * @see https://github.com/easydigitaldownloads/easy-digital-downloads/blob/2.8.18/tests/tests-payment-class.php#L70-L79
	 */
	public function test_getting_no_payment() {
		$payment = new Payment();

		$this->assertNull( $payment->get_id() );
	}

	/**
	 * Test setting and getting the payment credit card.
	 */
	public function test_set_and_get_credit_card() {
		$payment = new Payment();

		$credit_card = new CreditCard();
		$credit_card->set_number( '5300000000000006' );
		$credit_card->set_expiration_month( 12 );
		$credit_card->set_expiration_year( date( 'Y' ) + 5 );
		$credit_card->set_security_code( '123' );
		$credit_card->set_name( 'Pronamic' );

		$payment->set_credit_card( $credit_card );

		$this->assertEquals( $credit_card, $payment->get_credit_card() );
	}
}
