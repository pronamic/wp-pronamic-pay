<?php

/**
 * Title: Ogone DirectLink data helper
 * Description:
 * Copyright: Copyright (c) 2005 - 2013
 * Company: Pronamic
 * @author Remco Tolsma
 * @since 1.4.0
 */
class Pronamic_Pay_Gateways_Ogone_DirectLink_DataHelper {
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
	 * Set user id
	 *
	 * @param string $user_id
	 * @return Pronamic_Pay_Gateways_Ogone_DirectLink_DataHelper
	 */
	public function set_user_id( $user_id ) {
		$this->data->set_field( 'USERID', $user_id );

		return $this;
	}

	/**
	 * Set password
	 *
	 * @param string $password
	 * @return Pronamic_Pay_Gateways_Ogone_DirectLink_DataHelper
	 */
	public function set_password( $password ) {
		$this->data->set_field( 'PSWD', $password );

		return $this;
	}
}
