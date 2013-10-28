<?php

/**
 * Title: WordPress payment input data
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WP_Pay_PaymentInputData extends Pronamic_WP_Pay_PaymentData {
	/**
	 * Input type
	 * 
	 * @var int
	 */
	private $type;
	
	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an iDEAL test data proxy
	 */
	public function __construct( $type = INPUT_POST ) {
		parent::__construct();

		$this->type = $type;
	}

	//////////////////////////////////////////////////

	/**
	 * Get source indicator
	 * 
	 * @see Pronamic_Pay_PaymentDataInterface::getSource()
	 * @return string
	 */
	public function getSource() {
		return filter_input( $this->type, 'source', FILTER_SANITIZE_STRING );
	}

	//////////////////////////////////////////////////

	/**
	 * Get description
	 * 
	 * @see Pronamic_Pay_PaymentDataInterface::get_description()
	 * @return string
	 */
	public function get_description() {
		return filter_input( $this->type, 'description', FILTER_SANITIZE_STRING );
	}

	/**
	 * Get order ID
	 * 
	 * @see Pronamic_Pay_PaymentDataInterface::get_order_id()
	 * @return string
	 */
	public function get_order_id() {
		return filter_input( $this->type, 'order_id', FILTER_SANITIZE_STRING );
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
		
		$amount = filter_input( $this->type, 'amount', FILTER_VALIDATE_FLOAT );

		// Item
		$item = new Pronamic_IDeal_Item();
		$item->setNumber( $this->get_order_id() );
		$item->setDescription( sprintf( __( 'Test %s', 'pronamic_ideal' ), $this->get_order_id() ) );
		$item->setPrice( $amount );
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
		return filter_input( $this->type, 'currency', FILTER_SANITIZE_STRING );
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
}
