<?php

class Pronamic_WP_Pay_Membership_PaymentData extends Pronamic_WP_Pay_PaymentData {
	public $user;

	public $subscription;
	public $membership;

	//////////////////////////////////////////////////

	public function __construct( M_Subscription $subscription, M_Membership $membership ) {
		parent::__construct();

		$this->subscription = $subscription;
		$this->membership = $membership;
	}

	//////////////////////////////////////////////////
	// s2Member specific data
	//////////////////////////////////////////////////

	public function get_subscription_id() {
		return $this->subscription->sub_id();
	}

	//////////////////////////////////////////////////

	public function get_source() {
		return 'membership';
	}

	public function get_source_id() {
		return null;
	}

	public function get_order_id() {
		return null;
	}

	public function get_description() {
		return $this->subscription->sub_name();
	}

	public function getItems() {
		$pricing_array = $this->subscription->get_pricingarray();

		$items = new Pronamic_IDeal_Items();

		$item = new Pronamic_IDeal_Item();
		$item->setNumber( $this->get_order_id() );
		$item->setDescription( $this->get_description() );
		$item->setPrice( number_format( $pricing_array[0]['amount'], 2 ) );
		$item->setQuantity( 1 );

		$items->addItem( $item );

		return $items;
	}

	//////////////////////////////////////////////////
	// Currency
	//////////////////////////////////////////////////

	public function get_currency_alphabetic_code() {
		global $M_options;

		if ( empty( $M_options['paymentcurrency'] ) )
			$M_options['paymentcurrency'] = 'EUR';

		return $M_options['paymentcurrency'];
	}

	//////////////////////////////////////////////////
	// Customer
	//////////////////////////////////////////////////

	public function get_email() {
		return $this->membership->user_email;
	}

	public function getCustomerName() {
		return $this->membership->first_name . ' ' . $this->membership->last_name;
	}

	public function getOwnerAddress() {
		return '';
	}

	public function getOwnerCity() {
		return '';
	}

	public function getOwnerZip() {
		return '';
	}

	//////////////////

	public function get_normal_return_url() {

	}

	public function get_cancel_url() {

	}

	public function get_success_url() {

	}

	public function get_error_url() {

	}
}