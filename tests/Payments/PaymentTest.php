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
	 * Test getting no payment.
	 *
	 * @see https://github.com/easydigitaldownloads/easy-digital-downloads/blob/2.8.18/tests/tests-payment-class.php#L70-L79
	 */
	public function test_getting_no_payment() {
		$payment = new Payment();

		$this->assertNull( $payment->get_id() );
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

	/**
	 * Test setting and getting the payment consumer name.
	 */
	public function test_set_and_get_consumer_name() {
		$payment = new Payment();

		$name = 'John Doe';

		$payment->set_consumer_name( $name );

		$this->assertEquals( $name, $payment->consumer_name );
	}

	/**
	 * Test setting and getting the payment consumer account number.
	 */
	public function test_set_and_get_consumer_account_number() {
		$payment = new Payment();

		$number = '1086.34.779';

		$payment->set_consumer_account_number( $number );

		$this->assertEquals( $number, $payment->consumer_account_number );
	}

	/**
	 * Test setting and getting the payment consumer IBAN.
	 */
	public function test_set_and_get_consumer_iban() {
		$payment = new Payment();

		$iban = 'NL56 RABO 0108 6347 79';

		$payment->set_consumer_iban( $iban );

		$this->assertEquals( $iban, $payment->consumer_iban );
	}

	/**
	 * Test setting and getting the payment consumer BIC.
	 */
	public function test_set_and_get_consumer_bic() {
		$payment = new Payment();

		$bic = 'RABONL2U';

		$payment->set_consumer_bic( $bic );

		$this->assertEquals( $bic, $payment->consumer_bic );
	}

	/**
	 * Test setting and getting the payment consumer city.
	 */
	public function test_set_and_get_consumer_city() {
		$payment = new Payment();

		$city = 'Drachten';

		$payment->set_consumer_city( $city );

		$this->assertEquals( $city, $payment->consumer_city );
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
