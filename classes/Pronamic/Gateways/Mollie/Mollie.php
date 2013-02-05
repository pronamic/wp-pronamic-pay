<?php

/**
 * Title: Mollie
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Mollie_Mollie {
	/**
	 * Mollie API endpoint URL
	 * 
	 * @var string
	 */
	const API_URL = 'https://secure.mollie.nl//xml/ideal/';
	
	/////////////////////////////////////////////////

	/**
	 * Mollie partner ID
	 * 
	 * @var string
	 */
	private $partner_id;

	/**
	 * Mollie profile key
	 * 
	 * @var string
	 */
	private $profile_key;

	/////////////////////////////////////////////////

	/**
	 * Indicator to use test mode or not
	 * 
	 * @var boolean
	 */
	private $test_mode;

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
	 * @param string $partner_id
	 */
	public function __construct( $partner_id ) {
		$this->partner_id = $partner_id;
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
	 * Set test mode
	 * 
	 * @param boolean $test_mode
	 */
	public function set_test_mode( $test_mode ) {
		$this->test_mode = $test_mode;
	}
	
	//////////////////////////////////////////////////

	/**
	 * Get the default parameters wich are required in every Mollie request
	 * 
	 * @param string $action
	 * @param array $parameters
	 */
	private function get_parameters( $action, array $parameters = array() ) {
		$parameters['a']         = $action;
		$parameters['partnerid'] = $this->partner_id;

		if ( $this->test_mode ) {
			$parameters['testmode'] = 'true';
		}
		
		return $parameters;
	}
	
	//////////////////////////////////////////////////

	/**
	 * Send request with the specified action and parameters
	 * 
	 * @param string $action
	 * @param array $parameters
	 */
	private function send_request( $action, array $parameters = array() ) {
		$parameters = $this->get_parameters( $action, $parameters );
		
		// WordPress functions uses URL encoding
		// @see http://codex.wordpress.org/Function_Reference/build_query
		// @see http://codex.wordpress.org/Function_Reference/add_query_arg
		$url = Pronamic_WordPress_Util::build_url( self::API_URL, $parameters );

		return Pronamic_WordPress_Util::remote_get_body( $url, 200, array(
			'sslverify' => false
		) );
	}
	
	//////////////////////////////////////////////////

	/**
	 * Get banks
	 * 
	 * @return Ambigous <boolean, multitype:string >
	 */
	public function get_banks() {
		$banks = false;

		$result = $this->send_request( Pronamic_Gateways_Mollie_Actions::BANK_LIST );

		if ( is_wp_error( $result ) ) {
			$this->error = $result;
		} else {
			$xml = Pronamic_WordPress_Util::simplexml_load_string( $result );

			if ( is_wp_error( $xml ) ) {
				$this->error = $xml;
			} else {
				$banks = array();

				foreach ( $xml->bank as $bank ) {
					$id   = (string) $bank->bank_id;
					$name = (string) $bank->bank_name;

					$banks[$id] = $name;
				}
			}
		}
		
		return $banks;
	}
	
	//////////////////////////////////////////////////

	/**
	 * Create payment with the specified details
	 * 
	 * @param string $bank_id
	 * @param float $amount
	 * @param string $description
	 * @param string $return_url
	 * @param string $report_url
	 * @return stdClass
	 */
	public function create_payment( $bank_id, $amount, $description, $return_url, $report_url ) {
		$result = false;

		$parameters = array (
			'bank_id'     => $bank_id,
			'amount'      => $amount,
			'description' => $description,
			'reporturl'   => $report_url,
			'returnurl'   => $return_url
		);

		if ( $this->profile_key ) {
			$parameters['profile_key'] = $this->profile_key;
		}

		$result = $this->send_request( Pronamic_Gateways_Mollie_Actions::FETCH, $parameters );

		if ( $result !== false ) {
			$xml = Pronamic_WordPress_Util::simplexml_load_string( $result );

			if ( is_wp_error( $xml ) ) {
				$this->error = $xml;
			} else {
				$order = new stdClass();
				
				$order->transaction_id = (string) $xml->order->transaction_id;
				$order->amount         = (string) $xml->order->amount;
				$order->currency       = (string) $xml->order->currency;
				$order->url            = (string) $xml->order->URL;
				$order->message        = (string) $xml->order->message;
				
				$result = $order;
			}
		}

		return $result;
	}
	
	//////////////////////////////////////////////////

	/**
	 * Check payment with the specified transaction ID
	 * 
	 * @param string $transaction_id
	 * @return stdClass
	 */
	public function check_payment( $transaction_id ) {
		$result = false;

		$parameters = array (
			'transaction_id' => $transaction_id
		);

		$result = $this->send_request( Pronamic_Gateways_Mollie_Actions::CHECK, $parameters );

		if ( $result !== false ) {
			$xml = Pronamic_WordPress_Util::simplexml_load_string( $result );

			if ( is_wp_error( $xml ) ) {
				$this->error = $xml;
			} else {
				$order = new stdClass();
				
				$order->transaction_id = (string) $xml->order->transaction_id;
				$order->amount         = (string) $xml->order->amount;
				$order->payed          = filter_var( $xml->order->currency, FILTER_VALIDATE_BOOLEAN );
				$order->status         = (string) $xml->order->status;

				$order->consumer          = new stdClass();
				$order->consumer->name    = (string) $xml->order->consumer->consumerName;
				$order->consumer->account = (string) $xml->order->consumer->consumerAccount;
				$order->consumer->city    = (string) $xml->order->consumer->consumerCity;
				
				$result = $order;
			}
		}

		return $result;
	}
}
