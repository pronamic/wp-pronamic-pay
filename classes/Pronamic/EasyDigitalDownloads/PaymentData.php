<?php

/**
 * Title: Easy Digital Downloads payment data
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Stefan Boonstra
 * @version 1.0
 */
class Pronamic_EasyDigitalDownloads_PaymentData extends Pronamic_WP_Pay_PaymentData {
	/**
	 * Payment ID
	 *
	 * @var int
	 */
	private $payment_id;

	/**
	 * Payment data
	 *
	 * @var mixed
	 */
	private $payment_data;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an Easy Digital Downloads iDEAL data proxy
	 *
	 * @param int   $payment_id
	 * @param mixed $payment_data
	 */
	public function __construct( $payment_id, $payment_data ) {
		parent::__construct();

		$this->payment_id   = $payment_id;
		$this->payment_data = $payment_data;
	}

	//////////////////////////////////////////////////

	/**
	 * Get source ID
	 *
	 * @return int $source_id
	 */
	public function get_source_id() {
		return $this->payment_id;
	}

	/**
	 * Get source indicator
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_source()
	 * @return string
	 */
	public function get_source() {
		return 'easydigitaldownloads';
	}

	//////////////////////////////////////////////////

	public function get_title() {
		return sprintf( __( 'Easy Digital Downloads order %s', 'pronamic_ideal' ), $this->payment_id );
	}

	/**
	 * Get description
	 *
	 * @return string
	 */
	public function get_description() {
		$description = '';

		if ( count( $this->payment_data['cart_details'] ) > 0 ) {
			foreach ( $this->payment_data['cart_details'] as $cart_details ) {
				$description .= $cart_details['name'] . ', ';
			}

			$description = substr( $description, 0, -2 );
		}

		return $description;
	}

	/**
	 * Get order ID
	 *
	 * @return string
	 */
	public function get_order_id() {
		return $this->payment_id;
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
		$item = new Pronamic_IDeal_Item();
		$item->setNumber( $this->payment_id );
		$item->setDescription( $this->get_description() );
		$item->setPrice( $this->payment_data['price'] );
		$item->setQuantity( 1 );

		$items->addItem( $item );

		return $items;
	}

	//////////////////////////////////////////////////

	/**
	 * Get currency
	 *
	 * @return string
	 */
	public function get_currency_alphabetic_code() {
		return edd_get_option( 'currency' );
	}

	//////////////////////////////////////////////////

	public function get_email() {
		return $this->payment_data['user_email'];
	}

	public function getCustomerName() {
		$name = '';

		if ( is_array( $this->payment_data['user_info'] ) ) {
			if ( isset( $this->payment_data['user_info']['first_name'] ) ) {
				$name .= $this->payment_data['user_info']['first_name'];

				if ( isset( $this->payment_data['user_info']['last_name'] ) ) {
					$name .= ' ' . $this->payment_data['user_info']['last_name'];
				}
			}
		}

		return $name;
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

	public function get_normal_return_url() {
		return home_url();
	}

	public function get_cancel_url() {
		$page_id = edd_get_option( 'failure_page' );

		if ( is_numeric( $page_id ) ) {
			return get_permalink( $page_id );
		}

		return home_url();
	}

	public function get_success_url() {
		$page_id = edd_get_option( 'success_page' );

		if ( is_numeric( $page_id ) ) {
			return get_permalink( $page_id );
		}

		return home_url();
	}

	public function get_error_url() {
		$page_id = edd_get_option( 'failure_page' );

		if ( is_numeric( $page_id ) ) {
			return get_permalink( $page_id );
		}

		return home_url();
	}
}
