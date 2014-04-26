<?php

/**
 * Title: Ogone DirectLink
 * Description:
 * Copyright: Copyright (c) 2005 - 2013
 * Company: Pronamic
 * @author Remco Tolsma
 * @since 1.4.0
 */
class Pronamic_Pay_Gateways_MultiSafepay_Connect_Client {
	/**
	 * Error
	 *
	 * @var WP_Error
	 */
	private $error;

	/////////////////////////////////////////////////

	/**
	 * API URL
	 *
	 * @var string
	 */
	public $api_url;

	/////////////////////////////////////////////////

	/**
	 * Constructs and initializes an Ogone DirectLink client
	 */
	public function __construct() {
		$this->api_url = Pronamic_Pay_Gateways_MultiSafepay_MultiSafepay::API_PRODUCTION_URL;
	}

	/////////////////////////////////////////////////

	/**
	 * Get error
	 *
	 * @return WP_Error
	 */
	public function get_error() {
		return $this->error;
	}

	/////////////////////////////////////////////////

	private function parse_xml( $xml ) {
		switch ( $xml->getName() ) {
			case Pronamic_Pay_Gateways_MultiSafepay_Connect_XML_RedirectTransactionRequestMessage::NAME:
				return Pronamic_Pay_Gateways_MultiSafepay_Connect_XML_RedirectTransactionResponseMessage::parse( $xml );;
			case Pronamic_Pay_Gateways_MultiSafepay_Connect_XML_StatusRequestMessage::NAME:
				return Pronamic_Pay_Gateways_MultiSafepay_Connect_XML_StatusResponseMessage::parse( $xml );;
		}

		return false;
	}

	/////////////////////////////////////////////////

	private function reuqest( $message ) {
		$return = false;

		$result = Pronamic_WP_Util::remote_get_body( $this->api_url, 200, array(
			'method'    => 'POST',
			'sslverify' => false,
			'body'      => (string) $message,
		) );

		if ( is_wp_error( $result ) ) {
			$this->error = $result;
		} else {
			$xml = Pronamic_WP_Util::simplexml_load_string( $result );

			if ( is_wp_error( $xml ) ) {
				$this->error = $xml;
			} else {
				$return = $this->parse_xml( $xml );
			}
		}

		return $return;
	}

	/////////////////////////////////////////////////

	/**
	 * Start transaction
	 *
	 * @param array $data
	 */
	public function start_transaction( $message ) {
		$return = false;

		$response = $this->reuqest( $message );

		if ( $response ) {
			$transaction = $response->transaction;

			$return = $transaction;
		}

		return $return;
	}

	/////////////////////////////////////////////////

	/**
	 * Get status
	 *
	 * @param array $data
	 */
	public function get_status( $message ) {
		$return = false;

		$response = $this->reuqest( $message );

		if ( $response ) {
			$return = $response;
		}

		return $return;
	}
}
