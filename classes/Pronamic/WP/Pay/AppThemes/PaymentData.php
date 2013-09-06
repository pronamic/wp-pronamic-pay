<?php

/**
 * Title: WordPress AppThemes payment data
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WP_Pay_AppThemes_PaymentData extends Pronamic_WP_Pay_PaymentData {
	/**
	 * AppThemes order
	 * 
	 * @var APP_Order_Receipt
	 */
	private $order;
	
	//////////////////////////////////////////////////

	/**
	 * Constructs and intializes an AppThems payment data object
	 */
	public function __construct( $order ) {
		parent::__construct();
		
		$this->order = $order;
	}

	//////////////////////////////////////////////////

	/**
	 * Get source indicator
	 * 
	 * @see Pronamic_Pay_PaymentDataInterface::getSource()
	 * @return string
	 */
	public function getSource() {
		return 'appthemes';
	}

	//////////////////////////////////////////////////

	/**
	 * Get description
	 * 
	 * @see Pronamic_Pay_PaymentDataInterface::getDescription()
	 * @return string
	 */
	public function getDescription() {
		return $this->order->get_description();
	}

	/**
	 * Get order ID
	 * 
	 * @see Pronamic_Pay_PaymentDataInterface::getOrderId()
	 * @return string
	 */
	public function getOrderId() {
		return $this->order->get_id();
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
		$item->setDescription( $this->getDescription() );
		$item->setPrice( $this->order->get_total() );
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
		return $this->order->get_currency();
	}

	//////////////////////////////////////////////////
	// Customer
	//////////////////////////////////////////////////

	public function getEMailAddress() {
		$author_id = $this->order->get_author();
		
		return get_the_author_meta( 'user_email', $author_id );
	}

	public function getCustomerName() {
		$author_id = $this->order->get_author();
		
		return get_the_author_meta( 'first_name', $author_id ) . ' ' . get_the_author_meta( 'last_name', $author_id );
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
		return $this->order->get_return_url();
	}

	public function getCancelUrl() {
		return $this->order->get_cancel_url();
	}

	public function getSuccessUrl() {
		return $this->order->get_return_url();
	}

	public function getErrorUrl() {
		return $this->order->get_return_url();
	}
}
