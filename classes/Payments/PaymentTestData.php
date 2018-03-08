<?php
/**
 * Payment Test Data
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Payments;

use DateInterval;
use DatePeriod;
use DateTime;
use Pronamic\WordPress\Pay\CreditCard;
use Pronamic\WordPress\Pay\Subscriptions\Subscription;
use Pronamic\WordPress\Pay\Core\Util as Core_Util;
use WP_User;

/**
 * WordPress payment test data
 *
 * @author Remco Tolsma
 * @version 1.0
 */
class PaymentTestData extends PaymentData {
	/**
	 * WordPress user.
	 *
	 * @var WP_User
	 */
	private $user;

	/**
	 * Amount.
	 *
	 * @var float
	 */
	private $amount;

	/**
	 * Constructs and initializes an iDEAL test data proxy.
	 *
	 * @param WP_User $user   A WordPress user.
	 * @param float   $amount The amount to test.
	 */
	public function __construct( WP_User $user, $amount ) {
		parent::__construct();

		$this->user   = $user;
		$this->amount = $amount;
	}

	/**
	 * Get source indicator.
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_source()
	 * @return string
	 */
	public function get_source() {
		return 'test';
	}

	/**
	 * Get description.
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_description()
	 * @return string
	 */
	public function get_description() {
		return sprintf( __( 'Test %s', 'pronamic_ideal' ), $this->get_order_id() );
	}

	/**
	 * Get order ID.
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_order_id()
	 * @return string
	 */
	public function get_order_id() {
		return time();
	}

	/**
	 * Get items.
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_items()
	 * @return Items
	 */
	public function get_items() {
		// Items.
		$items = new Items();

		// Item.
		$item = new Item();
		$item->setNumber( $this->get_order_id() );
		$item->setDescription( sprintf( __( 'Test %s', 'pronamic_ideal' ), $this->get_order_id() ) );
		$item->setPrice( $this->amount );
		$item->setQuantity( 1 );

		$items->addItem( $item );

		return $items;
	}

	/**
	 * Get currency alphabetic code.
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_currency_alphabetic_code()
	 * @return string
	 */
	public function get_currency_alphabetic_code() {
		return 'EUR';
	}

	/**
	 * Get address.
	 *
	 * @return string
	 */
	public function get_address() {
		return '';
	}

	/**
	 * Get city.
	 *
	 * @return string
	 */
	public function get_city() {
		return '';
	}

	/**
	 * Get ZIP.
	 *
	 * @return string
	 */
	public function get_zip() {
		return '';
	}

	/**
	 * Get subscription.
	 *
	 * @since 1.2.1
	 * @see https://github.com/woothemes/woocommerce/blob/v2.1.3/includes/abstracts/abstract-wc-payment-gateway.php#L52
	 * @see https://github.com/wp-premium/woocommerce-subscriptions/blob/2.0.18/includes/class-wc-subscriptions-renewal-order.php#L371-L398
	 * @return string|bool
	 */
	public function get_subscription() {
		$test_subscription = filter_input( INPUT_POST, 'pronamic_pay_test_subscription', FILTER_VALIDATE_BOOLEAN );

		if ( ! $test_subscription ) {
			return false;
		}

		$interval = filter_input( INPUT_POST, 'pronamic_pay_test_repeat_interval', FILTER_VALIDATE_INT );

		if ( empty( $interval ) ) {
			return false;
		}

		$interval_period = filter_input( INPUT_POST, 'pronamic_pay_test_repeat_frequency', FILTER_SANITIZE_STRING );

		if ( empty( $interval_period ) ) {
			return false;
		}

		// Ends on.
		$ends_on = filter_input( INPUT_POST, 'pronamic_pay_ends_on', FILTER_SANITIZE_STRING );

		$times = null;

		switch ( $ends_on ) {
			case 'count':
				$count = filter_input( INPUT_POST, 'pronamic_pay_ends_on_count', FILTER_VALIDATE_INT );

				if ( ! empty( $count ) ) {
					$times = $count;
				}

				break;
			case 'date':
				$end_date = filter_input( INPUT_POST, 'pronamic_pay_ends_on_date', FILTER_SANITIZE_STRING );

				if ( ! empty( $end_date ) ) {
					/* translators: 1: interval, 2: interval period */
					$interval_spec = sprintf( 'P%1$s%2$s', $interval, Core_Util::to_period( $interval_period ) );

					$period = new DatePeriod(
						new DateTime(),
						new DateInterval( $interval_spec ),
						new DateTime( $end_date )
					);

					$times = iterator_count( $period );
				}

				break;
		}

		// Subscription.
		$subscription = new Subscription();

		$subscription->currency        = $this->get_currency();
		$subscription->description     = $this->get_description();
		$subscription->amount          = $this->get_amount();
		$subscription->frequency       = $times;
		$subscription->interval        = $interval;
		$subscription->interval_period = Core_Util::to_period( $interval_period );

		return $subscription;
	}

	/**
	 * Get credit card.
	 *
	 * @return CreditCard
	 */
	public function get_credit_card() {
		$credit_card = new CreditCard();

		// @see http://www.paypalobjects.com/en_US/vhelp/paypalmanager_help/credit_card_numbers.htm
		// Test card to simulate a 3-D Secure registered card.
		// $credit_card->set_number( '5555555555554444' );
		// $credit_card->set_number( '4111111111111111' );
		// $credit_card->set_number( '4000000000000002' );
		$credit_card->set_number( '5300000000000006' );

		$credit_card->set_expiration_month( 12 );

		$credit_card->set_expiration_year( date( 'Y' ) + 5 );

		$credit_card->set_security_code( '123' );

		$credit_card->set_name( 'Pronamic' );

		return $credit_card;
	}
}
