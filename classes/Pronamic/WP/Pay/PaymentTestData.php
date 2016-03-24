<?php

/**
 * Title: WordPress payment test data
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WP_Pay_PaymentTestData extends Pronamic_WP_Pay_PaymentData {
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

	public function getOwnerAddress() {
		return '';
	}

	public function getOwnerCity() {
		return '';
	}

	public function getOwnerZip() {
		return '';
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
