<?php

/**
 * Title: Sisow
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Sisow_Sisow {
	/**
	 * Sisow REST API endpoint URL
	 *
	 * @var string
	 */
	const API_URL = 'https://www.sisow.nl/Sisow/iDeal/RestHandler.ashx';

	/////////////////////////////////////////////////

	/**
	 * Sisow merchant ID
	 *
	 * @var string
	 */
	private $merchant_id;

	/**
	 * Sisow merchant key
	 *
	 * @var string
	 */
	private $merchant_key;

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
	 * Constructs and initializes an Sisow client object
	 *
	 * @param string $merchant_id
	 * @param string $merchant_key
	 */
	public function __construct( $merchant_id, $merchant_key ) {
		$this->merchant_id  = $merchant_id;
		$this->merchant_key = $merchant_key;
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
	 * Send request with the specified action and parameters
	 *
	 * @param string $action
	 * @param array $parameters
	 */
	private function send_request( $method, array $parameters = array() ) {
		$url = self::API_URL . '/' . $method;

		return Pronamic_WP_Util::remote_get_body( $url, 200, array(
			'method'    => 'POST',
			'sslverify' => false,
			'body'      => $parameters,
		) );
	}

	//////////////////////////////////////////////////

	/**
	 * Parse the specified document and return parsed result
	 *
	 * @param SimpleXMLElement $document
	 */
	private function parse_document( SimpleXMLElement $document ) {
		$this->error = null;

		$name = $document->getName();

		switch ( $name ) {
			case 'errorresponse':
				$sisow_error = Pronamic_Gateways_Sisow_XML_ErrorParser::parse( $document->error );

				$this->error = new WP_Error( 'ideal_sisow_error', $sisow_error->message, $sisow_error );

				return $sisow_error;
			case 'transactionrequest':
				$transaction = Pronamic_Gateways_Sisow_XML_TransactionParser::parse( $document->transaction );

				return $transaction;
			case 'statusresponse':
				$transaction = Pronamic_Gateways_Sisow_XML_TransactionParser::parse( $document->transaction );

				return $transaction;
			default:
				return new WP_Error(
					'ideal_sisow_error',
					sprintf( __( 'Unknwon Sisow message (%s)', 'pronamic_ideal' ), $name )
				);
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Get directory
	 *
	 * @return array an array with issuers
	 */
	public function get_directory() {
		$directory = false;

		if ( $this->test_mode ) {
			$directory = array( '99' => __( 'Sisow Bank (test)', 'pronamic_ideal' ) );
		} else {
			$result = $this->send_request( Pronamic_Gateways_Sisow_Methods::DIRECTORY_REQUEST );

			$xml = Pronamic_WP_Util::simplexml_load_string( $result );

			if ( is_wp_error( $xml ) ) {
				$this->error = $xml;
			} else {
				$directory = array();

				foreach ( $xml->directory->issuer as $issuer ) {
					$id   = (string) $issuer->issuerid;
					$name = (string) $issuer->issuername;

					$directory[ $id ] = $name;
				}
			}
		}

		return $directory;
	}

	//////////////////////////////////////////////////

	/**
	 * Create an SHA1 for an transaction request
	 *
	 * @param string $purchase_id
	 * @param string $entrance_code
	 * @param float $amount
	 * @param string $shop_id
	 * @param string $merchant_id
	 * @param string $merchant_key
	 */
	public static function create_transaction_sha1( $purchase_id, $entrance_code, $amount, $shop_id, $merchant_id, $merchant_key ) {
		return sha1(
			$purchase_id .
			$entrance_code .
			Pronamic_WP_Util::amount_to_cents( $amount ) .
			$shop_id .
			$merchant_id .
			$merchant_key
		);
	}

	//////////////////////////////////////////////////

	/**
	 * Create an transaction with the specified parameters
	 *
	 * @param string $issuer_id
	 * @param string $purchase_id
	 * @param float $amount
	 * @param string $description
	 * @param string $entrance_code
	 * @param string $return_url
	 *
	 * @return Pronamic_Gateways_Sisow_Transaction
	 */
	public function create_transaction( $issuer_id, $purchase_id, $amount, $description, $entrance_code, $return_url ) {
		$result = false;

		$parameters = array();

		$parameters['merchantid']   = $this->merchant_id;
		$parameters['issuerid']     = $issuer_id;
		$parameters['purchaseid']   = $purchase_id;
		$parameters['amount']       = Pronamic_WP_Util::amount_to_cents( $amount );
		$parameters['description']  = $description;
		$parameters['entrancecode'] = $entrance_code;
		$parameters['returnurl']    = $return_url;
		$parameters['cancelurl']    = $return_url;
		$parameters['callbackurl']  = $return_url;
		$parameters['notifyurl']    = $return_url;
		$parameters['sha1']         = self::create_transaction_sha1( $purchase_id, $entrance_code, $amount, '', $this->merchant_id, $this->merchant_key );

		$response = $this->send_request( Pronamic_Gateways_Sisow_Methods::TRANSACTION_REQUEST, $parameters );

		$xml = Pronamic_WP_Util::simplexml_load_string( $response );

		if ( is_wp_error( $xml ) ) {
			$this->error = $xml;
		} else {
			$message = $this->parse_document( $xml );

			if ( $message instanceof Pronamic_Gateways_Sisow_Transaction ) {
				$result = $message;
			}
		}

		return $result;
	}

	//////////////////////////////////////////////////

	/**
	 * Create an SHA1 for an status request
	 *
	 * @param string $transaction_id
	 * @param string $shop_id
	 * @param string $merchant_id
	 * @param string $merchant_key
	 */
	public static function create_status_sha1( $transaction_id, $shop_id, $merchant_id, $merchant_key ) {
		return sha1(
			$transaction_id .
			$shop_id .
			$merchant_id .
			$merchant_key
		);
	}

	//////////////////////////////////////////////////

	public function get_status( $transaction_id ) {
		$parameters = array();

		$parameters['merchantid']   = $this->merchant_id;
		$parameters['trxid']        = $transaction_id;
		$parameters['sha1']         = self::create_status_sha1( $transaction_id, '', $this->merchant_id, $this->merchant_key );

		$result = $this->send_request( Pronamic_Gateways_Sisow_Methods::STATUS_REQUEST, $parameters );

		$xml = Pronamic_WP_Util::simplexml_load_string( $result );

		if ( is_wp_error( $xml ) ) {
			$this->error = $xml;
		} else {
			$result = $this->parse_document( $xml );
		}

		return $result;
	}
}
