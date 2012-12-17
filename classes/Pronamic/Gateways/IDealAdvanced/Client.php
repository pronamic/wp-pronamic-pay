<?php

/**
 * Title: iDEAL client
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvanced_Client {
	/**
	 * The acquirer URL
	 * 
	 * @var string
	 */
	private $acquirer_url;
	
	//////////////////////////////////////////////////

	/**
	 * Directory request URL
	 * 
	 * @var string
	 */
	public $directory_request_url;

	/**
	 * Transaction request URL
	 * 
	 * @var string
	 */
	public $transaction_request_url;
	
	/**
	 * Status request URL
	 * 
	 * @var string
	 */
	public $status_request_url;
	
	//////////////////////////////////////////////////

	/**
	 * Merchant ID
	 * 
	 * @var string
	 */
	public $merchant_id;
	
	/**
	 * Sub ID
	 * 
	 * @var string
	 */
	public $sub_id;
	
	//////////////////////////////////////////////////

	/**
	 * The private key file
	 * 
	 * @var string
	 */
	private $privateKey;

	/**
	 * The private key password
	 * 
	 * @var string
	 */
	private $privateKeyPassword;

	/**
	 * The private certificate file
	 * 
	 * @var string
	 */
	private $privateCertificate;
	
	//////////////////////////////////////////////////

	/**
	 * The public certificate file
	 * 
	 * @var string
	 */
	private $publicCertificates;
	
	//////////////////////////////////////////////////

	/**
	 * Holds the error of the last operation
	 * 
	 * @var Error
	 */
	private $error;
	
	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an iDEAL client
	 */
	public function __construct() {
		$this->publicCertificates = array();
	}

	//////////////////////////////////////////////////

	/**
	 * Set the acquirer URL
	 * 
	 * @param string $acquirerUrl
	 */
	public function setAcquirerUrl($url) {
		$this->acquirer_url = $url;

		$this->directory_request_url   = $url;
		$this->transaction_request_url = $url;
		$this->status_request_url      = $url;
	}

	//////////////////////////////////////////////////

	public function setPrivateKey($key) {
		$this->privateKey = $key;
	}

	public function setPrivateKeyPassword($password) {
		$this->privateKeyPassword = $password;
	}

	public function setPrivateCertificate($certificate) {
		$this->privateCertificate = $certificate;
	}

	//////////////////////////////////////////////////

	public function addPublicCertificate($file) {
		$this->publicCertificates[] = $file;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the error
	 * 
	 * @return WP_Error
	 */
	public function get_error() {
		return $this->error;
	}
	
	//////////////////////////////////////////////////

	/**
	 * Send an message
	 */
	private function send_message( $url, $message ) {
		$result = false;

		// Sign
		$message->sign( $this->privateKey, $this->privateKeyPassword );

		// Stringify
		$data = (string) $message;

		// Remote post
		$response = wp_remote_post( $url, array(
			'method'    => 'POST',
			'headers'   => array(
				'Content-Type' => 'text/xml; charset=' . Pronamic_Gateways_IDealAdvanced_XML_Message::XML_ENCODING
			),
			'sslverify' => false,
			'body'      => $data
		) );

		// Handle response
		if ( ! is_wp_error( $response ) ) {
			if ( wp_remote_retrieve_response_code( $response ) == 200 ) {
				$body = wp_remote_retrieve_body( $response );

				// Suppress all XML errors
				$use_errors = libxml_use_internal_errors( true );

				$document = simplexml_load_string( $body );
	
				if ( $document !== false ) {
					$result = $this->parse_document( $document );
				} else {
					$this->error = new WP_Error( 'xml_load_error', __( 'Could not load the XML response meessage from the iDEAL provider.', 'pronamic_ideal' ) );

					foreach ( libxml_get_errors() as $error ) {
						$this->error->add( 'libxml_error', $error->message, $error );
					}

					libxml_clear_errors();
				}
				
				// Set back to previous value 
				libxml_use_internal_errors( $use_errors );
			} else {
				$this->error = new WP_Error( 'wrong_response_code', __( 'The response code (<code>%s<code>) from the iDEAL provider was incorrect.', 'pronamic_ideal' ) );
			}
		} else {
			$this->error = $response;
		}

		return $result;
	}

	//////////////////////////////////////////////////

	/**
	 * Parse the specified document and return parsed result
	 * 
	 * @param SimpleXMLElement $document
	 */
	private function parse_document( SimpleXMLElement $document ) {
		switch ( $document->getName() ) {
			case Pronamic_Gateways_IDealAdvanced_XML_ErrorResponseMessage::NAME:
				$message = Pronamic_Gateways_IDealAdvanced_XML_ErrorResponseMessage::parse( $document );

				$this->error = new WP_Error( 'ideal_advanced_error', $message->error, $message );

				return $message;
			case Pronamic_Gateways_IDealAdvanced_XML_DirectoryResponseMessage::NAME:
				return Pronamic_Gateways_IDealAdvanced_XML_DirectoryResponseMessage::parse( $document );
			case Pronamic_Gateways_IDealAdvanced_XML_TransactionResponseMessage::NAME:
				return Pronamic_Gateways_IDealAdvanced_XML_TransactionResponseMessage::parse( $document );
			case Pronamic_Gateways_IDealAdvanced_XML_StatusResponseMessage::NAME:
				return Pronamic_Gateways_IDealAdvanced_XML_StatusResponseMessage::parse( $document );
			default:
				return null;
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Get directory
	 * 
	 * @return Directory
	 */
	public function getDirectory() {
		$directory = null;

		$message = new Pronamic_Gateways_IDealAdvanced_XML_DirectoryRequestMessage();
		$merchant = $message->getMerchant();
		$merchant->id = $this->merchant_id;
		$merchant->subId = $this->sub_id;
		$merchant->authentication = Pronamic_IDeal_IDeal::AUTHENTICATION_SHA1_RSA;
		$merchant->token = Pronamic_Gateways_IDealAdvanced_Security::getShaFingerprint( $this->privateCertificate);

		$response = $this->send_message( $this->directory_request_url, $message );

		if ( $response instanceof Pronamic_Gateways_IDealAdvanced_XML_DirectoryResponseMessage ) {
			$directory = $response->directory;
		}

		return $directory;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the issuers
	 * 
	 * @return array
	 */
	public function getIssuers() {
		$issuers = null;

		$directory = $this->getDirectory();

		if ( $directory != null ) {
			$issuers = $directory->getIssuers();
		}

		return $issuers;
	}

	/**
	 * Get the issuers list
	 * 
	 * @return array
	 */
	public function getIssuerLists() {
		$lists = null;

		$directory = $this->getDirectory();

		if ( $directory != null ) {
			$lists = $directory->getLists();
		}

		return $lists;
	}

	//////////////////////////////////////////////////

	public function create_transaction( Pronamic_Gateways_IDealAdvanced_Transaction $transaction, $issuer_id ) {
		$message = new Pronamic_Gateways_IDealAdvanced_XML_TransactionRequestMessage();

		$issuer = new Pronamic_Gateways_IDealAdvanced_Issuer();
		$issuer->setId( $issuer_id );

		$merchant = $message->getMerchant();
		$merchant->id             = $this->merchant_id;
		$merchant->subId          = $this->sub_id;
		$merchant->authentication = Pronamic_IDeal_IDeal::AUTHENTICATION_SHA1_RSA;
		$merchant->returnUrl      = site_url( '/' );
		$merchant->token          = Pronamic_Gateways_IDealAdvanced_Security::getShaFingerprint( $this->privateCertificate );
		
		$message->issuer      = $issuer;
		$message->merchant    = $merchant;
		$message->transaction = $transaction;

		return $this->send_message( $this->transaction_request_url, $message );
	}

	//////////////////////////////////////////////////

	public function get_status( $transaction_id ) {
		$message = new Pronamic_Gateways_IDealAdvanced_XML_StatusRequestMessage();
	
		$merchant = $message->getMerchant();
		$merchant->id             = $this->merchant_id;
		$merchant->subId          = $this->sub_id;
		$merchant->authentication = Pronamic_IDeal_IDeal::AUTHENTICATION_SHA1_RSA;
		$merchant->returnUrl      = site_url( '/' );
		$merchant->token          = Pronamic_Gateways_IDealAdvanced_Security::getShaFingerprint( $this->privateCertificate );

		$message->transaction = new Pronamic_Gateways_IDealAdvanced_Transaction();
		$message->transaction->setId( $transaction_id );

		return $this->send_message( $this->status_request_url, $message );
	}
}
