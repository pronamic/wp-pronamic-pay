<?php

/**
 * Title: Mollie
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Mollie_Client {
	/**
	 * Mollie API endpoint URL
	 * 
	 * @var string
	 */
	const API_URL = 'https://api.mollie.nl/v1/';
	
	/////////////////////////////////////////////////

	/**
	 * Mollie API Key ID
	 * 
	 * @var string
	 */
	private $api_key;

	/////////////////////////////////////////////////

	/**
	 * Error
	 * 
	 * @var WP_Error
	 */
	private $error;

	/////////////////////////////////////////////////

	/**
	 * Constructs and initializes an Mollie client object
	 * 
	 * @param string $api_key
	 */
	public function __construct( $api_key ) {
		$this->api_key = $api_key;
	}

	/////////////////////////////////////////////////

	/**
	 * Error
	 * 
	 * @return WP_Error
	 */
	public function get_error() {
		return $this->error;
	}
	
	//////////////////////////////////////////////////

	/**
	 * Send request with the specified action and parameters
	 * 
	 * @param string $action
	 * @param array $parameters
	 */
	private function send_request( $resource, array $data = array() ) {
		$url = self::API_URL . $resource . '/';

		return wp_remote_request( $url, array(
			'method'    => 'POST',
			'sslverify' => false,
			'headers'   => array(
				'Authorization' => 'Bearer ' . $this->api_key,
			),
			'data'      => $data,
		) );
	}

	/////////////////////////////////////////////////

	public function create_payment( $amount, $description ) {
		$result = $this->send_request( 'payments', array(
			'amount'      => number_format( $amount, 2, '.', '' ),
			'description' => $description,
		) );
		
		var_dump( $result );
	}
}
