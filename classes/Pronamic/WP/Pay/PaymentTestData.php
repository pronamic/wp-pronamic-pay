<?php

/**
 * Title: WordPress payment test data
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
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
	 * @see Pronamic_Pay_PaymentDataInterface::getSource()
	 * @return string
	 */
	public function getSource() {
		return 'test';
	}

	//////////////////////////////////////////////////

	/**
	 * Get description
	 * 
	 * @see Pronamic_Pay_PaymentDataInterface::getDescription()
	 * @return string
	 */
	public function getDescription() {
		return sprintf( __( 'Test %s', 'pronamic_ideal' ), $this->getOrderId() );
	}

	/**
	 * Get order ID
	 * 
	 * @see Pronamic_Pay_PaymentDataInterface::getOrderId()
	 * @return string
	 */
	public function getOrderId() {
		return time();
	}

	/**
	 * Get items
	 * 
	 * @see Pronamic_Pay_PaymentDataInterface::getItems()
	 * @return Pronamic_IDeal_Items
	 */
	public function getItems() {
		// Items
		$items = new Pronamic_IDeal_Items();

		// Item
		$item = new Pronamic_IDeal_Item();
		$item->setNumber( $this->getOrderId() );
		$item->setDescription( sprintf( __( 'Test %s', 'pronamic_ideal' ), $this->getOrderId() ) );
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
	 * @see Pronamic_Pay_PaymentDataInterface::getCurrencyAlphabeticCode()
	 * @return string
	 */
	public function getCurrencyAlphabeticCode() {
		return 'EUR';
	}

	//////////////////////////////////////////////////
	// Customer
	//////////////////////////////////////////////////

	public function get_email() {
		return $this->user->user_email;
	}

	public function getCustomerName() {
		return $this->user->display_name;
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

	//////////////////////////////////////////////////
	// URL's
	// @todo we could also use $this->merchant->cart_data['transaction_results_url']
	// @see http://plugins.trac.wordpress.org/browser/wp-e-commerce/tags/3.8.8.3/wpsc-includes/merchant.class.php#L184
	//////////////////////////////////////////////////

	public function get_normal_return_url() {
		return home_url( '/' );
	}

	public function get_cancel_url() {
		return home_url( '/' );
	}

	public function get_success_url() {
		return home_url( '/' );
	}

	public function get_error_url() {
		return home_url( '/' );
	}
}
