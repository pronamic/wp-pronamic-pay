<?php

use Pronamic\WordPress\Pay\Payments\PaymentData;

/**
 * Title: WordPress payment test data
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WP_Pay_PaymentTestData extends PaymentData {
	/**
	 * WordPress uer
	 *
	 * @var WP_User
	 */
	private $user;

	/**
	 * Amount
	 *
	 * @var float
	 */
	private $amount;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an iDEAL test data proxy
	 */
	public function __construct( WP_User $user, $amount ) {
		parent::__construct();

		$this->user   = $user;
		$this->amount = $amount;
	}

	//////////////////////////////////////////////////

	/**
	 * Get source indicator
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_source()
	 * @return string
	 */
	public function get_source() {
		return 'test';
	}

	//////////////////////////////////////////////////

	/**
	 * Get description
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_description()
	 * @return string
	 */
	public function get_description() {
		return sprintf( __( 'Test %s', 'pronamic_ideal' ), $this->get_order_id() );
	}

	/**
	 * Get order ID
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_order_id()
	 * @return string
	 */
	public function get_order_id() {
		return time();
	}

	/**
	 * Get items
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_items()
	 * @return Pronamic_IDeal_Items
	 */
	public function get_items() {
		// Items
		$items = new Pronamic_IDeal_Items();

		// Item
		$item = new Pronamic_IDeal_Item();
		$item->setNumber( $this->get_order_id() );
		$item->setDescription( sprintf( __( 'Test %s', 'pronamic_ideal' ), $this->get_order_id() ) );
		$item->setPrice( $this->amount );
		$item->setQuantity( 1 );

		$items->addItem( $item );

		return $items;
	}

	//////////////////////////////////////////////////
	// Currency
	//////////////////////////////////////////////////

	/**
	 * Get currency alphabetic code
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_currency_alphabetic_code()
	 * @return string
	 */
	public function get_currency_alphabetic_code() {
		return 'EUR';
	}

	//////////////////////////////////////////////////
	// Customer
	//////////////////////////////////////////////////

	public function get_address() {
		return '';
	}

	public function get_city() {
		return '';
	}

	public function get_zip() {
		return '';
	}

	//////////////////////////////////////////////////
	// Subscription
	//////////////////////////////////////////////////

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

		$times = filter_input( INPUT_POST, 'pronamic_pay_ends_on_count', FILTER_VALIDATE_INT );

		if ( empty( $times ) ) {
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

		// Subscription
		$subscription = new Pronamic_Pay_Subscription();

		$subscription->currency        = $this->get_currency();
		$subscription->description     = $this->get_description();
		$subscription->amount          = $this->get_amount();
		$subscription->frequency       = $times;
		$subscription->interval        = $interval;
		$subscription->interval_period = Pronamic_WP_Pay_Util::to_period( $interval_period );

		return $subscription;
	}

	//////////////////////////////////////////////////
	// Creditcard
	//////////////////////////////////////////////////

	public function get_credit_card() {
		$credit_card = new Pronamic_Pay_CreditCard();

		// @see http://www.paypalobjects.com/en_US/vhelp/paypalmanager_help/credit_card_numbers.htm
		// Test card to simulate a 3-D Secure registered card
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
