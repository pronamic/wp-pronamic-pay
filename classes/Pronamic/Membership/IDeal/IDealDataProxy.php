<?php

class Pronamic_Membership_IDeal_IDealDataProxy extends Pronamic_WordPress_IDeal_IDealDataProxy {
	public $user;

	public $subscription;
	public $membership;

	public function __construct( M_Subscription $subscription, M_Membership $membership ) {
		parent::__construct();

		$this->subscription = $subscription;
		$this->membership = $membership;
	}

	public function getSource() {
		return 'membership';
	}

	public function get_source_id() {
		return $this->membership->id . '$' . $this->subscription->sub_id() . '$' . time();
	}

	public function getOrderId() {
		return $this->membership->id . '$' . $this->subscription->sub_id() . '$' . time();
	}

	public function getDescription() {
		return $this->subscription->sub_description() . ' | ' . $this->getOrderId();
	}

	public function getItems() {
		$pricing_array = $this->subscription->get_pricingarray();

		$items = new Pronamic_IDeal_Items();

		$item = new Pronamic_IDeal_Item();
		$item->setNumber( $this->getOrderId() );
		$item->setDescription( $this->getDescription() );
		$item->setPrice( number_format( $pricing_array[0]['amount'], 2 ) );
		$item->setQuantity( 1 );

		$items->addItem( $item );

		return $items;
	}

	//////////////////////
	// CURRENCY
	//////////////////////

	public function getCurrencyAlphabeticCode() {
		global $M_options;

		if ( empty( $M_options['paymentcurrency'] ) )
			$M_options['paymentcurrency'] = 'EUR';
	}

	//////////////////
	// CUSTOMER
	//////////////////

	public function getEMailAddress() {
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

	public function getNormalReturnUrl() {

	}

	public function getCancelUrl() {

	}

	public function getSuccessUrl() {

	}

	public function getErrorUrl() {

	}
}