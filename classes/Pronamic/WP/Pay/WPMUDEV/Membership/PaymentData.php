<?php

/**
 * Title: WordPress pay WPMU DEV Membership payment data
 * Description: 
 * Copyright: Copyright (c) 2005 - 2014
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WP_Pay_WPMUDEV_Membership_PaymentData extends Pronamic_WP_Pay_PaymentData {
	/**
	 * Subscription
	 * 
	 * @var Membership_Model_Subscription
	 */
	public $subscription;

	/**
	 * Membership
	 * 
	 * @var Membership_Model_Member
	 */
	public $membership;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize payment data object
	 * 
	 * @param mixed $subscription 
	 *      Membership         v3.4.4.1 = M_Subscription
	 *      Membership Premium v3.5.1.1 = Membership_Model_Subscription
	 * @param mixed $membership
	 *      Membership         v3.4.4.1 = M_Membership
	 *      Membership Premium v3.5.1.1 = Membership_Model_Member
	 */
	public function __construct( Membership_Model_Subscription $subscription, Membership_Model_Member $membership ) {
		parent::__construct();

		$this->subscription = $subscription;
		$this->membership = $membership;
	}

	//////////////////////////////////////////////////
	// WPMU DEV Membership specific data
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

		// Coupon
		$coupon = membership_get_current_coupon();
		
		if ( ! empty( $pricing_array ) && ! empty( $coupon ) ) {
			$pricing_array = $coupon->apply_coupon_pricing( $pricing_array );
		}

		$items = new Pronamic_IDeal_Items();

		$item = new Pronamic_IDeal_Item();
		$item->setNumber( $this->get_order_id() );
		$item->setDescription( $this->get_description() );
		$item->setPrice( $pricing_array[0]['amount'] );
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
		return M_get_returnurl_permalink();
	}

	public function get_cancel_url() {

	}

	public function get_success_url() {
		return M_get_registrationcompleted_permalink();
	}

	public function get_error_url() {

	}
}