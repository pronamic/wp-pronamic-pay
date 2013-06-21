<?php

/**
 * Title: Qantani gateway
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Qantani_Qantani {
	/**
	 * Qantani API endpoint URL
	 * 
	 * @var string
	 */
	const API_URL = 'https://www.qantanipayments.com/api/';

	/**
	 * Version
	 * 
	 * @var string
	 */
	const VERSION = '1.0.5';

	//////////////////////////////////////////////////

	/**
	 * The payment server URL 
	 * 
	 * @var string
	 */
	private $payment_server_url;

	//////////////////////////////////////////////////

	private $error;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize a iDEAL kassa object
	 */
	public function __construct() {
		$this->payment_server_url = self::API_URL;
	}

	//////////////////////////////////////////////////

	public function get_error() {
		return $this->error;
	}

	//////////////////////////////////////////////////
	
	/**
	 * Get the payment server URL
	 *
	 * @return the payment server URL
	 */
	public function get_payment_server_url() {
		return $this->payment_server_url;
	}
	
	/**
	 * Set the payment server URL
	 *
	 * @param string $url an URL
	 */
	public function set_payment_server_url( $url ) {
		$this->payment_server_url = $url;
	}
	
	//////////////////////////////////////////////////

	public function get_merchant_id() {
		return $this->merchant_id;
	}

	public function set_merchant_id( $id ) {
		$this->merchant_id = $id;
	}
	
	//////////////////////////////////////////////////

	public function get_merchant_key() {
		return $this->merchant_key;
	}

	public function set_merchant_key( $key ) {
		$this->merchant_key = $key;
	}
	
	//////////////////////////////////////////////////

	public function get_merchant_secret() {
		return $this->merchant_secret;
	}

	public function set_merchant_secret( $secret ) {
		$this->merchant_secret = $secret;
	}
	
	//////////////////////////////////////////////////

	/**
	 * Send request with the specified action and parameters
	 * 
	 * @param string $action
	 * @param array $parameters
	 */
	private function send_request( $data ) {
		$url = $this->get_payment_server_url();

		return Pronamic_WordPress_Util::remote_get_body( $url, 200, array(
			'method'    => 'POST',
			'sslverify' => false,
			'body'      => array(
				'data' => $data		
			)
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
		
		$document = $this->get_document( Pronamic_Gateways_Qantani_Actions::IDEAL_GET_BANKS );

		$result = $this->send_request( $document->saveXML() );
	var_dump($result);
		if ( is_wp_error( $result ) ) {
			$this->error = $result;
		} else {
			$xml = Pronamic_WordPress_Util::simplexml_load_string( $result );
	
			if ( is_wp_error( $xml ) ) {
				$this->error = $xml;
			} else {
				if ( $xml->Status == 'OK' ) {
					foreach ( $xml->Banks->Bank as $bank ) {
						$id   = (string) $bank->Id;
						$name = (string) $bank->Name;
	
						$banks[$id] = $name;
					}
				}
			}
		}
	
		return $banks;
	}

	//////////////////////////////////////////////////

	public static function create_checksum( array $parameters, $secret ) {
		ksort( $parameters );
		
		$string = implode( $parameters );
		
		$string .= $secret;
		
		$checksum = sha1( $string );
		
		return $checksum;
	}

	//////////////////////////////////////////////////
	
	/**
	 * Get HTML fields
	 * 
	 * @return string
	 */
	private function get_document( $name, $parameters = array() ) {
		$document = new DOMDocument( '1.0', 'UTF-8' );
		
		$transaction = $document->createElement( 'Transaction' );
		$document->appendChild( $transaction );
		
		// Action
		$action = $document->createElement( 'Action' );
		$transaction->appendChild( $action );
			
			$name = $document->createElement( 'Name', $name );
			$action->appendChild( $name );
			
			$version = $document->createElement( 'Version', 1 );
			$action->appendChild( $version );
	
			$client_version = $document->createElement( 'ClientVersion',  self::VERSION );
			$action->appendChild( $client_version );
		
		// Parameters
		$parameters_element = $document->createElement( 'Parameters' );
		$transaction->appendChild( $parameters_element );
		
		foreach ( $parameters as $key => $value ) {
			$element = $document->createElement( $key, $value );

			$parameters_element->appendChild( $element );
		}
		
		// Merchant
		$merchant = $document->createElement( 'Merchant' );
		$transaction->appendChild( $merchant );
			
			$id = $document->createElement( 'ID', $this->get_merchant_id() );
			$merchant->appendChild( $id );
			
			$key = $document->createElement( 'Key', $this->get_merchant_key() );
			$merchant->appendChild( $key );
	
			$checksum = $document->createElement( 'Checksum', $this->create_checksum( $parameters, $this->merchant_secret ) );
			$merchant->appendChild( $checksum );
	
		return $document;
	}
}
