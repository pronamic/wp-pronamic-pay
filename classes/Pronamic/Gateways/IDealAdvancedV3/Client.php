<?php

/**
 * Title: iDEAL client
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvancedV3_Client {
	/**
	 * Acquirer URL
	 * 
	 * @var string
	 */
	public $acquirer_url;

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
	 * Private certificate
	 * 
	 * @var string
	 */
	public $private_certificate;

	/**
	 * Private key
	 * 
	 * @var string
	 */
	public $private_key;

	/**
	 * Private key password
	 * 
	 * @var unknown_type
	 */
	public $private_key_password;
	
	//////////////////////////////////////////////////

	/**
	 * Error
	 * 
	 * @var WP_Error
	 */
	private $error;
	
	//////////////////////////////////////////////////

	/**
	 * Constructs and initialzies an iDEAL Advanced v3 client object
	 */
	public function __construct() {

	}
	
	//////////////////////////////////////////////////

	/**
	 * Get the latest error
	 * 
	 * @return WP_Error or null
	 */
	public function get_error() {
		return $this->error;
	}
	
	//////////////////////////////////////////////////

	/**
	 * Set the acquirer URL
	 * 
	 * @param string $url
	 */
	public function set_acquirer_url( $url ) {
		$this->acquirer_url            = $url;

		$this->directory_request_url   = $url;
		$this->transaction_request_url = $url;
		$this->status_request_url      = $url;
	}
	
	//////////////////////////////////////////////////

	/**
	 * Send an specific request message to an specific URL
	 * 
	 * @param string $url
	 * @param Pronamic_Gateways_IDealAdvancedV3_XML_RequestMessage $message
	 * 
	 * @return Pronamic_Gateways_IDealAdvancedV3_XML_ResponseMessage
	 */
	private function send_message( $url, Pronamic_Gateways_IDealAdvancedV3_XML_RequestMessage $message ) {
		$result = false;

		// Sign
		$document = $message->get_document();
		$document = $this->sign_document( $document );
		
		if ( $document !== false ) {
			// Stringify
			$data = $document->saveXML();
	
			// Remote post
			$response = wp_remote_post( $url, array(
				'method'    => 'POST',
				'headers'   => array(
					'Content-Type' => 'text/xml; charset=' . Pronamic_Gateways_IDealAdvancedV3_XML_Message::XML_ENCODING
				),
				'sslverify' => false,
				'body'      => $data
			) );
	
			// Handle response
			if ( ! is_wp_error( $response ) ) {
				if ( wp_remote_retrieve_response_code( $response ) == 200 ) {
					$body = wp_remote_retrieve_body( $response );
	
					$xml = Pronamic_WordPress_Util::simplexml_load_string( $body );
					
					if ( is_wp_error( $xml ) ) {
						$this->error = $xml;
					} else {
						$document = self::parse_document( $xml );
						
						if ( is_wp_error( $document ) ) {
							$this->error = $document;
						} else {
							$result = $document;
						}
					}
				} else {
					$this->error = new WP_Error( 'wrong_response_code', __( 'The response code (<code>%s<code>) from the iDEAL provider was incorrect.', 'pronamic_ideal' ) );
				}
			} else {
				$this->error = $response;
			}
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
		$this->error = null;

		$name = $document->getName();

		switch( $name ) {
			case Pronamic_Gateways_IDealAdvancedV3_XML_AcquirerErrorResMessage::NAME:
				$message = Pronamic_Gateways_IDealAdvancedV3_XML_AcquirerErrorResMessage::parse( $document );
				
				$ideal_error = $message->error;

				$this->error = new WP_Error( 'ideal_advanced_v3_error', $ideal_error->get_message(), $ideal_error );

				return $message;
			case Pronamic_Gateways_IDealAdvancedV3_XML_DirectoryResponseMessage::NAME:
				return Pronamic_Gateways_IDealAdvancedV3_XML_DirectoryResponseMessage::parse( $document );
			case Pronamic_Gateways_IDealAdvancedV3_XML_TransactionResponseMessage::NAME:
				return Pronamic_Gateways_IDealAdvancedV3_XML_TransactionResponseMessage::parse( $document );
			case Pronamic_Gateways_IDealAdvancedV3_XML_AcquirerStatusResMessage::NAME:
				return Pronamic_Gateways_IDealAdvancedV3_XML_AcquirerStatusResMessage::parse( $document );
			default:
				return new WP_Error(
					'ideal_advanced_v3_error',
					sprintf( __( 'Unknwon iDEAL message (%s)', 'pronamic_ideal' ), $name ) 
				);
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Get directory of issuers
	 * 
	 * @return Pronamic_Gateways_IDealAdvancedV3_Directory
	 */
	public function get_directory() {
		$directory = false;

		$request_dir_message = new Pronamic_Gateways_IDealAdvancedV3_XML_DirectoryRequestMessage();

		$merchant = $request_dir_message->get_merchant();
		$merchant->set_id( $this->merchant_id );
		$merchant->set_sub_id( $this->sub_id );

		$response_dir_message = $this->send_message( $this->directory_request_url, $request_dir_message );
		
		if ( $response_dir_message instanceof Pronamic_Gateways_IDealAdvancedV3_XML_DirectoryResponseMessage ) {
			$directory = $response_dir_message->get_directory();
		}

		return $directory;
	}

	//////////////////////////////////////////////////

	/**
	 * Create transaction
	 * 
	 * @param Pronamic_Gateways_IDealAdvancedV3_Transaction $transaction
	 * @param string $issuer_id
	 * @return Pronamic_Gateways_IDealAdvancedV3_XML_TransactionResponseMessage
	 */
	public function create_transaction( Pronamic_Gateways_IDealAdvancedV3_Transaction $transaction, $issuer_id ) {
		$message = new Pronamic_Gateways_IDealAdvancedV3_XML_TransactionRequestMessage();

		$merchant = $message->get_merchant();
		$merchant->set_id( $this->merchant_id );
		$merchant->set_sub_id( $this->sub_id );
		$merchant->set_return_url( add_query_arg( 'gateway', 'ideal_advanced_v3', home_url( '/' ) ) );

		$message->issuer = new Pronamic_Gateways_IDealAdvancedV3_Issuer();
		$message->issuer->set_id( $issuer_id );
		
		$message->transaction = $transaction;

		return $this->send_message( $this->transaction_request_url, $message );
	}

	//////////////////////////////////////////////////

	/**
	 * Get the status of the specified transaction ID
	 * 
	 * @param string $transaction_id
	 * @return Pronamic_Gateways_IDealAdvancedV3_XML_TransactionResponseMessage
	 */
	public function get_status( $transaction_id ) {
		$message = new Pronamic_Gateways_IDealAdvancedV3_XML_AcquirerStatusReqMessage();

		$merchant = $message->get_merchant();
		$merchant->set_id( $this->merchant_id );
		$merchant->set_sub_id( $this->sub_id );

		$message->transaction = new Pronamic_Gateways_IDealAdvancedV3_Transaction();
		$message->transaction->set_id( $transaction_id );

		return $this->send_message( $this->status_request_url, $message );
	}

	//////////////////////////////////////////////////

	/**
	 * Sign the specified DOMDocument
	 * 
	 * @see https://github.com/Maks3w/xmlseclibs/blob/v1.3.0/tests/xml-sign.phpt
	 * 
	 * @param DOMDocument $document
	 * @return DOMDocument
	 */
	private function sign_document( DOMDocument $document ) {
		$result = false;

		try {
			$dsig = new XMLSecurityDSig();
			
			// For canonicalization purposes the exclusive (9) algorithm must be used.
			// @see http://pronamic.nl/wp-content/uploads/2012/12/iDEAL-Merchant-Integration-Guide-ENG-v3.3.1.pdf #page 30 
			$dsig->setCanonicalMethod( XMLSecurityDSig::EXC_C14N );

			// For hashing purposes the SHA-256 (11) algorithm must be used.
			// @see http://pronamic.nl/wp-content/uploads/2012/12/iDEAL-Merchant-Integration-Guide-ENG-v3.3.1.pdf #page 30
			$dsig->addReference(
				$document,
				XMLSecurityDSig::SHA256,
				array( 'http://www.w3.org/2000/09/xmldsig#enveloped-signature' ),
				array( 'force_uri' => true )
			);

			// For signature purposes the RSAWithSHA 256 (12) algorithm must be used.
			// @see http://pronamic.nl/wp-content/uploads/2012/12/iDEAL-Merchant-Integration-Guide-ENG-v3.3.1.pdf #page 31
			$key = new XMLSecurityKey( XMLSecurityKey::RSA_SHA256, array( 'type' => 'private' ) );
			$key->passphrase = $this->private_key_password;
			$key->loadKey( $this->private_key );
			
			// Sign
			$dsig->sign( $key );
			
			// The public key must be referenced using a fingerprint of an X.509 
			// certificate. The fingerprint must be calculated according
			// to the following formula HEX(SHA-1(DER certificate)) (13)
			// @see http://pronamic.nl/wp-content/uploads/2012/12/iDEAL-Merchant-Integration-Guide-ENG-v3.3.1.pdf #page 31
			$fingerprint = Pronamic_Gateways_IDealAdvanced_Security::getShaFingerprint( $this->private_certificate );

			$dsig->addKeyInfoAndName( $fingerprint );

			// Add the signature
			$dsig->appendSignature( $document->documentElement );

			$result = $document;
		} catch ( Exception $e ) {
			$this->error = new WP_Error( 'xml_security', $e->getMessage(), $e );
		}
		
		return $result;
	}
}
