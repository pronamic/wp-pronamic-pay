<?php

/**
 * Title: PayDutch
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_PayDutch_PayDutch {
	/**
	 * Mollie API endpoint URL
	 * 
	 * @var string
	 */
	const API_URL = 'https://www.paydutch.nl/api/processreq.aspx';
	
	/////////////////////////////////////////////////

	/**
	 * PayDutch username
	 * 
	 * @var string
	 */
	private $username;

	/**
	 * PayDutch password
	 * 
	 * @var string
	 */
	private $password;

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
	 * @param string $username
	 * @param string $password
	 */
	public function __construct( $username, $password ) {
		$this->username = $username;
		$this->password = $password;
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
	
	
	/////////////////////////////////////////////////

	/**
	 * Request the specified message 
	 * 
	 * @param Pronamic_Gateways_PayDutch_XML_RequestMessage $message
	 */
	private function request( Pronamic_Gateways_PayDutch_XML_RequestMessage $message ) {
		return Pronamic_WordPress_Util::remote_get_body( self::API_URL, 200, array(
			'method'    => 'POST',
			'sslverify' => false,
			'headers'   => array( 'Content-Type' => 'text/xml' ),
			'body'      => (string) $message
		) );
	}

	/////////////////////////////////////////////////

	/**
	 * Get payment methods
	 */
	public function get_payment_methods() {
		$merchant = new Pronamic_Gateways_PayDutch_Merchant( $this->username, $this->password );
		$message = new Pronamic_Gateways_PayDutch_XML_ListMethodRequestMessage( $merchant );

		$result = $this->request( $message );
		
		if ( is_wp_error( $result ) ) {
			$this->error = $result;
		} else {
			$xml = Pronamic_WordPress_Util::simplexml_load_string( $result );
			
			if ( is_wp_error( $xml ) ) {
				$this->error = $xml;
			} else {
				
			}
		}
	}

	/**
	 * Get bank list
	 */
	public function get_bank_list() {
		$list = null;

		$message = new Pronamic_Gateways_PayDutch_XML_RetrieveBankListRequestMessage();

		$result = $this->request( $message );

		if ( is_wp_error( $result ) ) {
			$this->error = $result;
		} else {
			$xml = Pronamic_WordPress_Util::simplexml_load_string( $result );
			
			if ( is_wp_error( $xml ) ) {
				$this->error = $xml;
			} else {
				$list = Pronamic_Gateways_PayDutch_XML_BankListParser::parse( $xml );
			}
		}

		return $list;
	}

	/**
	 * Get an transaction request
	 * 
	 * @return Pronamic_Gateways_PayDutch_TransactionRequest
	 */
	public function get_transaction_request() {
		$transaction_request = new Pronamic_Gateways_PayDutch_TransactionRequest( $this->username, $this->password );
		
		return $transaction_request;
	}

	/**
	 * Transaction
	 */
	public function request_transaction( $transaction_request ) {
		$result = null;
	
		$message = new Pronamic_Gateways_PayDutch_XML_TransactionRequestMessage( $transaction_request );

		$response = $this->request( $message );
		
		if ( is_wp_error( $response ) ) {
			$this->error = $response;
		} else {
			$url = filter_var( $response, FILTER_VALIDATE_URL );
			
			if ( $url !== false ) {
				$result = new stdClass();

				$query = parse_url( $url, PHP_URL_QUERY );
				$query = parse_str( $query, $data );

				$id = null;
				if ( isset( $data['ID'] ) ) {
					$id = $data['ID'];
				}
				
				$result->url = $url;
				$result->id  = $id;
			} else {
				$this->error = new WP_Error( 'paydutch_error', (string) $response, $response );
			}
		}

		return $result;
	}

	public function get_payment_status( $reference ) {
		$result = null;
		
		$merchant = new Pronamic_Gateways_PayDutch_Merchant( $this->username, $this->password );
		$merchant->reference = $reference;
		$merchant->test = true;

		$message = new Pronamic_Gateways_PayDutch_XML_QueryRequestMessage( $merchant );

		$result = $this->request( $message );

		if ( is_wp_error( $result ) ) {
			$this->error = $result;
		} else {
			$xml = Pronamic_WordPress_Util::simplexml_load_string( $result );

			if ( is_wp_error( $xml ) ) {
				$this->error = $xml;
			} else {
				$result = Pronamic_Gateways_PayDutch_XML_PaymentInfoParser::parse( $xml->paymentinfo );
			}	
		}
		
		return $result;
	}

	/////////////////////////////////////////////////

	/**
	 * Transform an PayDutch state
	 * 
	 * @param string $status
	 */
	public static function transform_state( $state ) {
		switch ( $state ) {
			case Pronamic_Gateways_PayDutch_States::REGISTER:
				return Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_OPEN;
			// @todo
		}
	}
}
