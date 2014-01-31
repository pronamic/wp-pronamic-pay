<?php

/**
 * Title: iThemes Exchange payment data
 * Description:
 * Copyright: Copyright (c) 2005 - 2014
 * Company: Pronamic
 * @author Stefan Boonstra
 * @version 1.0
 */
class Pronamic_IThemesExchange_PaymentData extends Pronamic_WP_Pay_PaymentData {

	/**
	 * Unique hash with which the transaction data can be retrieved
	 *
	 * @var string
	 */
	private $unique_hash;

	/**
	 * Transaction object
	 *
	 * @var stdClass
	 */
	private $transaction_object;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an Easy Digital Downloads iDEAL data proxy
	 *
	 * @param string   $unique_hash
	 * @param stdClass $transaction_object
	 */
	public function __construct( $unique_hash, stdClass $transaction_object ) {
		parent::__construct();

		$this->unique_hash        = $unique_hash;
		$this->transaction_object = $transaction_object;
	}

	//////////////////////////////////////////////////

	/**
	 * Get source ID
	 *
	 * @return string $source_id
	 */
	public function get_source_id() {
		return $this->unique_hash;
	}

	/**
	 * Get source indicator
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_source()
	 *
	 * @return string
	 */
	public function get_source() {
		return Pronamic_IThemesExchange_IDeal_AddOn::$slug;
	}

	/**
	 * Get title
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( 'iThemes Exchange order %s', 'pronamic_ideal' ), $this->unique_hash );
	}

	/**
	 * Get description
	 *
	 * @return string
	 */
	public function get_description() {
		return $this->transaction_object->description;
	}

	/**
	 * Get order ID
	 *
	 * @return string
	 */
	public function get_order_id() {
		return $this->unique_hash;
	}

	/**
	 * Get items
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::getItems()
	 *
	 * @return Pronamic_IDeal_Items
	 */
	public function getItems() {
		// Items
		$items = new Pronamic_IDeal_Items();

		// Item
		// We only add one total item, because iDEAL cant work with negative price items (discount)
		$item = new Pronamic_IDeal_Item();
		$item->setNumber( $this->unique_hash );
		$item->setDescription( $this->get_description() );
		$item->setPrice( $this->transaction_object->total );
		$item->setQuantity( 1 );

		$items->addItem( $item );

		return $items;
	}

	/**
	 * Get currency
	 *
	 * @return string
	 */
	public function get_currency_alphabetic_code() {
		return $this->transaction_object->currency;
	}

	/**
	 * Get email address
	 *
	 * @return string
	 */
	public function get_email() {
		return $this->transaction_object->shipping_address['email'];
	}

	/**
	 * Get customer name
	 *
	 * @return string
	 */
	public function getCustomerName() {
		$name = '';

		$shipping_address = $this->transaction_object->shipping_address;

		if ( is_array( $shipping_address ) ) {
			if ( isset( $shipping_address['first-name'] ) ) {
				$name .= $shipping_address['first-name'];

				if ( isset( $shipping_address['last-name'] ) ) {
					$name .= ' ' . $shipping_address['last-name'];
				}
			}
		}

		return $name;
	}

	/**
	 * Get address
	 *
	 * @return string
	 */
	public function getOwnerAddress() {
		$address  = $this->transaction_object->shipping_address['address1'];
		$address .= ' ' . $this->transaction_object->shipping_address['address2'];

		return $address;
	}

	/**
	 * Get city
	 *
	 * @return string
	 */
	public function getOwnerCity() {
		return $this->transaction_object->shipping_address['city'];
	}

	/**
	 * Get zip
	 *
	 * @return string
	 */
	public function getOwnerZip() {
		return $this->transaction_object->shipping_address['zip'];
	}

	//////////////////////////////////////////////////

	/**
	 * Get home URL
	 *
	 * @return string
	 */
	public function get_normal_return_url() {

		return home_url();
	}

	/**
	 * Get cancel URL
	 *
	 * @return string
	 */
	public function get_cancel_url() {

		return home_url();
	}

	/**
	 * Get success URL
	 *
	 * @return string
	 */
	public function get_success_url() {

		$page_url = it_exchange_get_transaction_confirmation_url( $this->unique_hash );

		if ( $page_url === false ) {
			return home_url();
		}

		return $page_url;
	}

	/**
	 * Get error URL
	 *
	 * @return string
	 */
	public function get_error_url() {

		return home_url();
	}
}
