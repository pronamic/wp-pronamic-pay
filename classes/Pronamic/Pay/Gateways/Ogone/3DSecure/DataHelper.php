<?php

/**
 * Title: Ogone 3-D Secure data helper
 * Description:
 * Copyright: Copyright (c) 2005 - 2013
 * Company: Pronamic
 * @author Remco Tolsma
 * @since 1.4.0
 */
class Pronamic_Pay_Gateways_Ogone_3DSecure_DataHelper {
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
	 * Set 3-D Secure flag
	 *
	 * @param string $http_accept
	 * @return Pronamic_Pay_Gateways_Ogone_DirectLink_DataHelper
	 */
	public function set_3d_secure_flag( $flag ) {
		$this->data->set_field( 'FLAG3D', $flag ? 'Y' : 'N' );

		return $this;
	}

	/**
	 * Set HTTP Accept
	 *
	 * @param string $http_accept
	 * @return Pronamic_Pay_Gateways_Ogone_DirectLink_DataHelper
	 */
	public function set_http_accept( $http_accept ) {
		$this->data->set_field( 'HTTP_ACCEPT', $http_accept );

		return $this;
	}

	/**
	 * Set HTTP User-Agent
	 *
	 * @param string $user_agent
	 * @return Pronamic_Pay_Gateways_Ogone_DirectLink_DataHelper
	 */
	public function set_http_user_agent( $user_agent ) {
		$this->data->set_field( 'HTTP_USER_AGENT', $user_agent );

		return $this;
	}

	/**
	 * Set window
	 *
	 * @param string $window
	 * @return Pronamic_Pay_Gateways_Ogone_DirectLink_DataHelper
	 */
	public function set_window( $window ) {
		$this->data->set_field( 'WIN3DS', $window );

		return $this;
	}

	/**
	 * Set language
	 *
	 * @param string $language
	 * @return Pronamic_Pay_Gateways_Ogone_DirectLink_DataHelper
	 */
	public function set_language( $language ) {
		$this->data->set_field( 'LANGUAGE', $language );

		return $this;
	}
}
