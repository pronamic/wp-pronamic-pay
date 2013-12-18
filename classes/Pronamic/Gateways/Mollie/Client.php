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
	private function send_request( $end_point, $method = 'POST', array $data = array() ) {
		$url = self::API_URL . $end_point;

		return wp_remote_request( $url, array(
			'method'    => $method,
			'sslverify' => false,
			'headers'   => array(
				'Authorization' => 'Bearer ' . $this->api_key,
			),
			'body'      => $data,
		) );
	}

	/////////////////////////////////////////////////

	public function create_payment( Pronamic_Gateways_Mollie_PaymentRequest $request ) {
		$result = null;

		$data = $request->get_array();

		$response = $this->send_request( 'payments/', 'POST', $data );
		
		$response_code = wp_remote_retrieve_response_code( $response ) ;

		if ( $response_code == 201 ) {
			$body = wp_remote_retrieve_body( $response );

			// NULL is returned if the json cannot be decoded or if the encoded data is deeper than the recursion limit. 
			$result = json_decode( $body );
		} else {
			$this->error = new WP_Error( 'mollie_error', $response_code, $response_code );
		}

		return $result;
	}

	public function get_payment( $id ) {
		$result = null;
		
		$response = $this->send_request( 'payments/' . $id, 'GET' );
		
		$response_code = wp_remote_retrieve_response_code( $response ) ;

		if ( $response_code == 200 ) {
			$body = wp_remote_retrieve_body( $response );

			// NULL is returned if the json cannot be decoded or if the encoded data is deeper than the recursion limit. 
			$result = json_decode( $body );
		}

		return $result;
	}
}
