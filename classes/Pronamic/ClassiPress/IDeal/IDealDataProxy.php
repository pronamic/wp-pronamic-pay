<?php

/**
 * Title: ClassiPress iDEAL data proxy
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_ClassiPress_IDeal_IDealDataProxy extends Pronamic_WordPress_IDeal_IDealDataProxy {
	/**
	 * Order values
	 * 
	 * @var array
	 */
	private $order_values;

	//////////////////////////////////////////////////

	/**
	 * Construct and intializes an ClassiPress iDEAL data proxy
	 * 
	 * @param array $order_values
	 */
	public function __construct( $order_values ) {
		$this->order_values = $order_values;
	}

	//////////////////////////////////////////////////

	/**
	 * Get source indicatir
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getSource()
	 * @return string
	 */
	public function getSource() {
		return 'classipress';
	}

	//////////////////////////////////////////////////

	/**
	 * Get description
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getDescription()
	 * @return string
	 */
	public function getDescription() {
		return sprintf( __( 'Advertisement %s', 'pronamic_ideal' ), $this->getOrderId() );
	}

	/**
	 * Get order ID
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getOrderId()
	 * @return string
	 */
	public function getOrderId() {
		return $this->order_values['oid'];
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
		$item->setNumber( $this->order_values['item_number'] );
		$item->setDescription( $this->order_values['item_name'] );
		$item->setPrice( $this->order_values['item_amount'] );
		$item->setQuantity( 1 );

		$items->addItem( $item );

		return $items;
	}

	//////////////////////////////////////////////////
	// Currency
	//////////////////////////////////////////////////

	public function getCurrencyAlphabeticCode() {
		return get_option( 'cp_curr_pay_type' );
	}

	//////////////////////////////////////////////////
	// Customer
	//////////////////////////////////////////////////

	public function getEMailAddress() {
		$user_id = $this->order_values['user_id'];
		
		return get_the_author_meta( 'user_email', $user_id );
	}

	public function getCustomerName() {
		$user_id = $this->order_values['user_id'];
		
		return get_the_author_meta( 'first_name', $user_id ) . ' ' . get_the_author_meta( 'last_name', $user_id );
	}

	public function getOwnerAddress() {
		return $this->order_values['cp_street'];
	}

	public function getOwnerCity() {
		return $this->order_values['cp_city'];
	}

	public function getOwnerZip() {
		return $this->order_values['cp_zipcode'];
	}

	//////////////////////////////////////////////////
	// URL's
	//////////////////////////////////////////////////
	
	public function getNormalReturnUrl() {
		return $this->order_values['notify_url'];
	}
	
	public function getCancelUrl() {
		return $this->order_values['notify_url'];
	}
	
	public function getSuccessUrl() {
		return $this->order_values['notify_url'];
	}

	public function getErrorUrl() {
		return $this->order_values['notify_url'];
	}
}
