<?php

/**
 * Title: ClassiPress iDEAL data proxy
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_ClassiPress_IDeal_IDealDataProxy extends Pronamic_WP_Pay_PaymentData {
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
	 * @see Pronamic_Pay_PaymentDataInterface::get_source()
	 * @return string
	 */
	public function get_source() {
		return 'classipress';
	}

	//////////////////////////////////////////////////

	/**
	 * Get description
	 * 
	 * @see Pronamic_Pay_PaymentDataInterface::get_description()
	 * @return string
	 */
	public function get_description() {
		return sprintf( __( 'Advertisement %s', 'pronamic_ideal' ), $this->get_order_id() );
	}

	/**
	 * Get order ID
	 * 
	 * @see Pronamic_Pay_PaymentDataInterface::get_order_id()
	 * @return string
	 */
	public function get_order_id() {
		$order_id = null;
		
		if ( isset( $this->order_values['oid'] ) ) {
			$order_id = $this->order_values['oid'];
		} elseif ( isset( $this->order_values['txn_id'] ) ) {
			$order_id = $this->order_values['txn_id'];
		}

		return $order_id;
	}

	/**
	 * Get items
	 * 
	 * @see Pronamic_Pay_PaymentDataInterface::get_items()
	 * @return Pronamic_IDeal_Items
	 */
	public function get_items() {
		// Items
		$items = new Pronamic_IDeal_Items();

		// Item
		// We only add one total item, because iDEAL cant work with negative price items (discount)
		$amount = 0;
		if ( isset( $this->order_values['mc_gross'] ) ) {
			$amount = $this->order_values['mc_gross'];
		} elseif ( isset( $this->order_values['item_amount'] ) ) {
			$amount = $this->order_values['item_amount'];
		}
		
		$item = new Pronamic_IDeal_Item();
		$item->setNumber( $this->order_values['item_number'] );
		$item->setDescription( $this->order_values['item_name'] );
		$item->setPrice( $amount );
		$item->setQuantity( 1 );

		$items->addItem( $item );

		return $items;
	}

	//////////////////////////////////////////////////
	// Currency
	//////////////////////////////////////////////////

	public function get_currency_alphabetic_code() {
		return get_option( 'cp_curr_pay_type' );
	}

	//////////////////////////////////////////////////
	// Customer
	//////////////////////////////////////////////////

	public function get_email() {
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
			if ( Pronamic_ClassiPress_Order::is_advertisement( $this->order_values ) ) {
				// Advertisement
				// @see https://bitbucket.org/Pronamic/classipress/src/bc1334736c6e/includes/theme-functions.php?at=3.2.1#cl-2380
				$url = add_query_arg(
					array(
						'invoice' => $this->order_values['txn_id'],
						'aid'     => $this->order_values['ad_id']
					),
					home_url( '/' )
				);
			} else {
				// Advertisement package
				// @see https://bitbucket.org/Pronamic/classipress/src/bc1334736c6e/includes/theme-functions.php?at=3.2.1#cl-2408
				$url = add_query_arg(
					array(
						'invoice' => $this->order_values['txn_id'],
						'uid'     => $this->order_values['user_id']
					),
					home_url( '/' )
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
			 * if we do this the 'return_url' isn't directly available
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
			if ( Pronamic_ClassiPress_Order::is_advertisement( $this->order_values ) ) {
				// Advertisement
				// @see https://bitbucket.org/Pronamic/classipress/src/bc1334736c6e/includes/theme-functions.php?at=3.2.1#cl-2381
				$url = add_query_arg(
					array(
						'pid' => $this->order_values['txn_id'],
						'aid' => $this->order_values['ad_id']
					),
					CP_ADD_NEW_CONFIRM_URL
				);
			} else {
				// Advertisement package
				// @see https://bitbucket.org/Pronamic/classipress/src/bc1334736c6e/includes/theme-functions.php?at=3.2.1#cl-2409
				$url = add_query_arg(
					array(
						'oid' => $this->order_values['txn_id'],
						// In some ClassiPress installation the 'wp_cp_order_info' table doesn't have an 'user_id' column
						'uid' => isset( $this->order_values['user_id'] ) ? $this->order_values['user_id'] : false
					),
					CP_MEMBERSHIP_PURCHASE_CONFIRM_URL
				);
			}
		}
		
		return $url;
	}

	//////////////////////////////////////////////////
	
	public function get_normal_return_url() {
		return $this->get_notify_url();
	}
	
	public function get_cancel_url() {
		return $this->get_notify_url();
	}
	
	public function get_success_url() {
		return $this->get_return_url();
	}

	public function get_error_url() {
		return $this->get_notify_url();
	}
}
