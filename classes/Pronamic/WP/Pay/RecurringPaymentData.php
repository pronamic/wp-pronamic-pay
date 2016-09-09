<?php

/**
 * Title: WordPress recurring payment data
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Reüel van der Steege
 * @version 1.0
 */
class Pronamic_WP_Pay_RecurringPaymentData extends Pronamic_WP_Pay_PaymentData {
	/**
	 * Constructs and intializes an WordPress iDEAL data proxy
	 */
	public function __construct( $subscription_id ) {
		parent::__construct();

		$this->subscription = new Pronamic_WP_Pay_Subscription( $subscription_id );
		$this->payment      = $this->subscription->get_first_payment();
		$this->recurring    = true;
	}

	//////////////////////////////////////////////////

	/**
	 * Get source indicator
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_source()
	 * @return string
	 */
	public function get_source() {
		return $this->subscription->get_source();
	}

	/**
	 * Get source ID
	 *
	 * @see Pronamic_Pay_AbstractPaymentData::get_source_id()
	 */
	public function get_source_id() {
		return $this->subscription->get_source_id();
	}

	//////////////////////////////////////////////////

	/**
	 * Get description
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_description()
	 * @return string
	 */
	public function get_description() {
		return $this->subscription->get_description();
	}

	/**
	 * Get order ID
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_order_id()
	 * @return string
	 */
	public function get_order_id() {
		return $this->payment->get_order_id();
	}

	/**
	 * Get items
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_items()
	 * @return Pronamic_IDeal_Items
	 */
	public function get_items() {
		$item = new Pronamic_IDeal_Item();
		$item->setNumber( 1 );
		$item->setDescription( $this->subscription->get_description() );
		$item->setPrice( $this->subscription->get_amount() );
		$item->setQuantity( 1 );

		$items = new Pronamic_IDeal_Items();
		$items->addItem( $item );

		return $items;
	}

	//////////////////////////////////////////////////
	// Currency
	//////////////////////////////////////////////////

	public function get_currency_alphabetic_code() {
		return $this->subscription->get_currency();
	}

	//////////////////////////////////////////////////
	// Customer
	//////////////////////////////////////////////////

	public function get_email() {
		return $this->payment->get_email();
	}

	public function get_customer_name() {
		return $this->payment->get_customer_name();
	}

	public function get_address() {
		return $this->payment->get_address();
	}

	public function get_city() {
		return $this->payment->get_city();
	}

	public function get_zip() {
		return $this->payment->get_zip();
	}

	public function get_country() {
		return $this->payment->get_country();
	}

	public function get_telephone_number() {
		return $this->payment->get_telephone_number();
	}

	//////////////////////////////////////////////////
	// Payment method
	//////////////////////////////////////////////////

	public function get_payment_method() {
		return $this->payment->get_payment_method();
	}

	//////////////////////////////////////////////////
	// Issuer
	//////////////////////////////////////////////////

	public function get_issuer_id() {
		return $this->payment->get_issuer();
	}

	//////////////////////////////////////////////////
	// Subscription
	//////////////////////////////////////////////////

	public function get_subscription() {
		return $this->subscription;
	}

	//////////////////////////////////////////////////
	// Subscription
	//////////////////////////////////////////////////

	public function get_subscription_id() {
		return $this->subscription->get_id();
	}
}
