<?php

/**
 * Title: Event Espresso payment data
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WP_Pay_EventEspresso_PaymentData extends Pronamic_WP_Pay_PaymentData {
	/**
	 * Data
	 *
	 * @var array
	 */
	private $data;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an Event Espresso iDEAL data proxy
	 *
	 * @param array $data
	 */
	public function __construct( $data ) {
		parent::__construct();

		$data = apply_filters( 'filter_hook_espresso_prepare_payment_data_for_gateways', $data );
		$data = apply_filters( 'filter_hook_espresso_get_total_cost', $data );

		$this->data = $data;
	}

	//////////////////////////////////////////////////

	/**
	 * Get source indicator
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_source()
	 * @return string
	 */
	public function get_source() {
		return 'event-espresso';
	}

	//////////////////////////////////////////////////

	/**
	 * Get description
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_description()
	 * @return string
	 */
	public function get_description() {
		return sprintf( __( 'Attendee %s', 'pronamic_ideal' ), $this->data['attendee_id'] );
	}

	/**
	 * Get order ID
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_order_id()
	 * @return string
	 */
	public function get_order_id() {
		return $this->data['attendee_id'];
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
		$item->setNumber( $this->data['attendee_id'] );
		$item->setDescription( sprintf( __( 'Attendee %s', 'pronamic_ideal' ), $this->data['attendee_id'] ) );
		$item->setPrice( $this->data['total_cost'] );
		$item->setQuantity( 1 );

		$items->addItem( $item );

		return $items;
	}

	//////////////////////////////////////////////////
	// Currency
	//////////////////////////////////////////////////

	/**
	 * Get currency
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_currency_alphabetic_code()
	 * @return string
	 */
	public function get_currency_alphabetic_code() {
		return 'EUR';
	}

	//////////////////////////////////////////////////
	// Customer
	//////////////////////////////////////////////////

	public function get_email() {
		return $this->data['email'];
	}

	public function getCustomerName() {
		return $this->data['fname'] . ' ' . $this->data['lname'];
	}

	public function getOwnerAddress() {
		return $this->data['address'];
	}

	public function getOwnerCity() {
		return $this->data['city'];
	}

	public function getOwnerZip() {
		return $this->data['zip'];
	}

	//////////////////////////////////////////////////
	// URL's
	//////////////////////////////////////////////////

	public function get_notify_url() {
		global $org_options;

		return add_query_arg(
			array(
				'attendee_id'     => $this->data['attendee_id'],
				'registration_id' => $this->data['registration_id'],
				'event_id'        => $this->data['event_id'],
			),
			get_permalink( $org_options['notify_url'] )
		);
	}

	private function get_return_url() {
		global $org_options;

		return add_query_arg(
			array(
				'attendee_id'     => $this->data['attendee_id'],
				'registration_id' => $this->data['registration_id'],
				'event_id'        => $this->data['event_id'],
			),
			get_permalink( $org_options['return_url'] )
		);
	}

	private function get_cancel_return() {
		global $org_options;

		return get_permalink( $org_options['cancel_return'] );
	}

	//////////////////////////////////////////////////

	public function get_normal_return_url() {
		return $this->get_return_url();
	}

	public function get_cancel_url() {
		return $this->get_cancel_return();
	}

	public function get_success_url() {
		return $this->get_return_url();
	}

	public function get_error_url() {
		return $this->get_return_url();
	}
}
