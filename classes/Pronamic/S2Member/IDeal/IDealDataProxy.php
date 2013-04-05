<?php

/**
 * Title: s2Member iDEAL data proxy
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Leon Rowland
 * @since 1.2.6
 */
class Pronamic_S2Member_IDeal_IDealDataProxy extends Pronamic_WordPress_IDeal_IDealDataProxy {
	public $user;

	public $data = array();

	public function __construct( $data ) {
		parent::__construct();

		$this->data = $data;

		$this->user = Pronamic_S2Member_Bridge_Order::getUserFromUID( $this->data['orderID'] );
	}

	public function getOrderId() {
		return $this->data['orderID'];
	}

	public function getDescription() {
		return str_replace( '{{order_id}}', $this->getOrderId(), $this->data['description'] );
	}

	public function getItems() {
		$items = new Pronamic_IDeal_Items();

		$item = new Pronamic_IDeal_Item();
		$item->setNumber( $this->getOrderId() );
		$item->setDescription( $this->getDescription() );
		$item->setPrice( $this->data['cost'] );
		$item->setQuantity( 1 );

		$items->addItem( $item );

		return $items;
	}

	public function getSource() {
		return 's2member';
	}

	public function get_source_id() {
		return $this->data['orderID'];
	}

	//////////////////////////////////////////////////
	// Currency
	//////////////////////////////////////////////////

	/**
	 * Get currency
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
		return $this->user->first_name . ' ' . $this->user->last_name;
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

	public function getNormalReturnUrl() {
		return '';
	}

	public function getCancelUrl() {
		return '';
	}

	public function getSuccessUrl() {
		return add_query_arg(
			array(
				'key' => $this->get_entrance_code(),
				'order' => $this->getOrderId()
			),
			home_url()
		);
	}

	public function getErrorUrl() {
		return '';
	}
}
