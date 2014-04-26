<?php

/**
 * Title: Ogone data default helper class
 * Description:
 * Copyright: Copyright (c) 2005 - 2013
 * Company: Pronamic
 * @author Remco Tolsma
 * @since 1.4.0
 */
class Pronamic_Pay_Gateways_Ogone_DataGeneralHelper {
	/**
	 * Data
	 *
	 * @var Pronamic_Pay_Gateways_Ogone_Data
	 */
	private $data;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize a Ogone data default helper class
	 */
	public function __construct( Pronamic_Pay_Gateways_Ogone_Data $data ) {
		$this->data = $data;
	}

	//////////////////////////////////////////////////
	// Helper functions
	//////////////////////////////////////////////////

	/**
	 * Set PSP ID
	 *
	 * @param int $number
	 * @return Pronamic_Pay_Gateways_Ogone_DataGeneralHelper
	 */
	public function set_psp_id( $number ) {
		$this->data->set_field( 'PSPID', $number );

		return $this;
	}

	//////////////////////////////////////////////////

	/**
	 * Set order ID
	 *
	 * @param string $order_id
	 * @return Pronamic_Pay_Gateways_Ogone_DataDefaultHelper
	 */
	public function set_order_id( $order_id ) {
		$this->data->set_field( 'ORDERID', $order_id );

		return $this;
	}

	/**
	 * Set order description
	 *
	 * @param string $description
	 * @return Pronamic_Pay_Gateways_Ogone_DataDefaultHelper
	 */
	public function set_order_description( $description ) {
		$this->data->set_field( 'COM', $description );

		return $this;
	}

	//////////////////////////////////////////////////

	/**
	 * Set amount
	 *
	 * @param float $amount
	 * @return Pronamic_Pay_Gateways_Ogone_DataDefaultHelper
	 */
	public function set_amount( $amount ) {
		$this->data->set_field( 'AMOUNT', Pronamic_WP_Util::amount_to_cents( $amount ) );

		return $this;
	}

	/**
	 * Set currency
	 *
	 * @param string $currency
	 * @return Pronamic_Pay_Gateways_Ogone_DataDefaultHelper
	 */
	public function set_currency( $currency ) {
		$this->data->set_field( 'CURRENCY', $currency );

		return $this;
	}

	//////////////////////////////////////////////////

	/**
	 * Set customer name
	 *
	 * @param string $name
	 * @return Pronamic_Pay_Gateways_Ogone_DataDefaultHelper
	 */
	public function set_customer_name( $name ) {
		$this->data->set_field( 'CN', $name );

		return $this;
	}

	/**
	 * Set email address
	 *
	 * @param string $email
	 * @return Pronamic_Pay_Gateways_Ogone_DataDefaultHelper
	 */
	public function set_email_address( $email ) {
		$this->data->set_field( 'EMAIL', $email );

		return $this;
	}
}
