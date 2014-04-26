<?php

/**
 * Title: Ogone data default helper class
 * Description:
 * Copyright: Copyright (c) 2005 - 2013
 * Company: Pronamic
 * @author Remco Tolsma
 * @since 1.4.0
 */
class Pronamic_Pay_Gateways_Ogone_DataCreditCardHelper {
	/**
	 * Data
	 *
	 * @var array
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
	 * Set credit card number
	 *
	 * @param int $number
	 * @return Pronamic_Pay_Gateways_Ogone_DataDefaultHelper
	 */
	public function set_number( $number ) {
		$this->data->set_field( 'CARDNO', $number );

		return $this;
	}

	/**
	 * Set expiration date
	 *
	 * @param DateTime $date
	 * @return Pronamic_Pay_Gateways_Ogone_DataDefaultHelper
	 */
	public function set_expiration_date( DateTime $date ) {
		$this->data->set_field( 'ED', $date->format( Pronamic_Pay_Gateways_Ogone_Ogone::EXPIRATION_DATE_FORMAT ) );

		return $this;
	}

	/**
	 * Set security code
	 *
	 * @param string $security_code
	 * @return Pronamic_Pay_Gateways_Ogone_DataDefaultHelper
	 */
	public function set_security_code( $security_code ) {
		$this->data->set_field( 'CVC', $security_code );

		return $this;
	}
}
