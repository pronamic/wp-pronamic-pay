<?php

/**
 * Title: WP e-Commerce iDEAL data proxy
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WordPress_IDeal_IDealTestDataProxy extends Pronamic_WordPress_IDeal_IDealDataProxy {
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
		$this->user  = $user;
		$this->amount = $amount;
	}

	//////////////////////////////////////////////////

	/**
	 * Get source indicator
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getSource()
	 * @return string
	 */
	public function getSource() {
		return 'test';
	}

	//////////////////////////////////////////////////

	/**
	 * Get description
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getDescription()
	 * @return string
	 */
	public function getDescription() {
		return sprintf( __( 'Test %s', 'pronamic_ideal' ), $this->getOrderId() );
	}

	/**
	 * Get order ID
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getOrderId()
	 * @return string
	 */
	public function getOrderId() {
		return time();
	}

	/**
	 * Get items
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getItems()
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
	 * @see Pronamic_IDeal_IDealDataProxy::getCurrencyAlphabeticCode()
	 * @return string
	 */
	public function getCurrencyAlphabeticCode() {
		return 'EUR';
	}

	//////////////////////////////////////////////////
	// Customer
	//////////////////////////////////////////////////

	public function getEMailAddress() {
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

	public function getNormalReturnUrl() {
		return admin_url();
	}

	public function getCancelUrl() {
		return admin_url();
	}

	public function getSuccessUrl() {
		return admin_url();
	}

	public function getErrorUrl() {
		return admin_url();
	}
}
