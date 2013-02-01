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
		parent::__construct();

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

	/**
	 * Get notify URL
	 * 
	 * @return string
	 */
	private function get_notify_url() {
		// @see https://bitbucket.org/Pronamic/classipress/src/bc1334736c6e/includes/theme-functions.php?at=3.2.1#cl-2380
		if ( isset( $this->order_values['notify_url'] ) ) {
			$url = $this->order_values['notify_url'];
		} else {
			/*
			 * We query the order info sometimes directly from the database,
			 * if we do this the 'notify_url' isn't directly available
			 */
			if ( isset( $order['ad_id'] ) && ! empty( $order['ad_id'] ) ) {
				// Advertisement
				// @see https://bitbucket.org/Pronamic/classipress/src/bc1334736c6e/includes/theme-functions.php?at=3.2.1#cl-2380
				$url = add_query_arg(
					array(
						'invoice' => $order_vals['txn_id'],
						'aid'     => $order_vals['ad_id']
					),
					site_url( '/' )
				);
			} else {
				// Advertisement package
				// https://bitbucket.org/Pronamic/classipress/src/bc1334736c6e/includes/theme-functions.php?at=3.2.1#cl-2408
				$url = add_query_arg(
					array(
						'invoice' => $order_vals['txn_id'],
						'uid'     => $order_vals['user_id']
					),
					site_url( '/' )
				);
			}
		}
		
		return $url;
	}

	/**
	 * Get notify URL
	 * 
	 * @return string
	 */
	private function get_return_url() {
		// @see https://bitbucket.org/Pronamic/classipress/src/bc1334736c6e/includes/theme-functions.php?at=3.2.1#cl-2381
		if ( isset( $this->order_values['return_url'] ) ) {
			$url = $this->order_values['return_url'];
		} else {
			/*
			 * We query the order info sometimes directly from the database,
			 * if we do this the 'notify_url' isn't directly available
			 * 
			 * ClassiPress has order information about adding an advertisement,
			 * but also has order information about advertisement packages.
			 *
			 * If the advertisement post ID is empty we know the order
			 * information is about an advertisement package.
			 *
			 * ClassiPress is doing in similar check in the following file:
			 * @see https://bitbucket.org/Pronamic/classipress/src/bc1334736c6e/includes/gateways/gateway.php?at=3.2.1#cl-31
			 */
			if ( isset( $order['ad_id'] ) && ! empty( $order['ad_id'] ) ) {
				// Advertisement
				$url = add_query_arg(
					array(
						'oid' => $this->order_values['txn_id'],
						'uid' => $this->order_values['user_id']
					),
					CP_MEMBERSHIP_PURCHASE_CONFIRM_URL
				);
			} else {
				// Advertisement package
				$url = add_query_arg(
					array(
						'oid' => $this->order_values['txn_id'],
						'uid' => $this->order_values['user_id']
					),
					CP_MEMBERSHIP_PURCHASE_CONFIRM_URL
				);
			}
		}
		
		return $url;
	}

	//////////////////////////////////////////////////
	
	public function getNormalReturnUrl() {
		return $this->get_return_url();
	}
	
	public function getCancelUrl() {
		return $this->get_return_url();
	}
	
	public function getSuccessUrl() {
		return $this->get_return_url();
	}

	public function getErrorUrl() {
		return $this->get_return_url();
	}
}
