<?php

/**
 * Title: AppThemes iDEAL data proxy
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_AppThemes_IDeal_IDealDataProxy extends Pronamic_WordPress_IDeal_IDealDataProxy {
	/**
	 * Order
	 * 
	 * @var APP_Order
	 */
	private $order;

	/**
	 * Options
	 * 
	 * @var array
	 */
	private $options;

	//////////////////////////////////////////////////

	/**
	 * Construct and intializes an AppThemes iDEAL data proxy
	 * 
	 * @param APP_Order $order
	 * @param array $options
	 */
	public function __construct( $order, $options ) {
		parent::__construct();

		$this->order = $order;
		$this->options = $options;
	}

	//////////////////////////////////////////////////

	/**
	 * Get source indicatir
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getSource()
	 * @return string
	 */
	public function getSource() {
		return 'appthemes';
	}

	//////////////////////////////////////////////////

	/**
	 * Get description
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getDescription()
	 * @return string
	 */
	public function getDescription() {
		return sprintf(__('Advertisement %s', 'pronamic_ideal'), $this->getOrderId());
	}

	/**
	 * Get order ID
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getOrderId()
	 * @return string
	 */
	public function getOrderId() {
		return $this->order['post_id'];
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
		// We only add one total item, because iDEAL cant work with negative price items (discount)
		$item = new Pronamic_IDeal_Item();
		$item->setNumber( '' );
		$item->setDescription( '' );
		$item->setPrice( $this->order->get_total() );
		$item->setQuantity( 1 );
 
		$items->addItem( $item );

		return $items;
	}

	//////////////////////////////////////////////////
	// Currency
	//////////////////////////////////////////////////

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
