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
	public $acquirer_url;

	public $merchant_id;
	
	public $sub_id;
	
	public $private_key_password;
	
	public $private_key;
	
	public $private_certificate;

	public function __construct() {
		
	}

	/**
	 * Send an message
	 */
	private function send_message( Pronamic_Gateways_IDealAdvancedV3_XML_RequestMessage $message ) {
		$result = null;

		// Sign document
		$document = $message->get_document();
		$document = $this->sign_document( $document );
		
		// Stringify
		$data = $document->saveXML();

		// Remote post
		$response = wp_remote_post( $this->acquirer_url, array(
			'method' => 'POST',
			'headers' => array(
				'Content-Type' => 'text/xml'
			),
			'body' => $data
		) );

		// Handle response
		if ( ! is_wp_error( $response ) ) {
			if ( wp_remote_retrieve_response_code( $response ) == 200 ) {
				$body = wp_remote_retrieve_body( $response );

				$document = simplexml_load_string( $body );
	
				if ( $document !== false ) {
					$result = $this->parse_document( $document );
				} else {
					throw new Exception( 'Unknown response message' );
				}
			} else {
				
			}
		} else {
			
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

		switch( $document->getName() ) {
			case Pronamic_Gateways_IDealAdvanced_XML_ErrorResponseMessage::NAME:
				$message = Pronamic_Gateways_IDealAdvanced_XML_ErrorResponseMessage::parse($document);

				$this->error = $message->error;

				return $message;
			case Pronamic_Gateways_IDealAdvancedV3_XML_DirectoryResponseMessage::NAME:
				return Pronamic_Gateways_IDealAdvancedV3_XML_DirectoryResponseMessage::parse( $document );
			case Pronamic_Gateways_IDealAdvanced_XML_TransactionResponseMessage::NAME:
				return Pronamic_Gateways_IDealAdvanced_XML_TransactionResponseMessage::parse($document);
			case Pronamic_Gateways_IDealAdvanced_XML_StatusResponseMessage::NAME:
				return Pronamic_Gateways_IDealAdvanced_XML_StatusResponseMessage::parse($document);
			default:
				return null;
		}
	}

	public function get_directory() {
		$request_dir_message = new Pronamic_Gateways_IDealAdvancedV3_XML_DirectoryRequestMessage();

		$merchant = $request_dir_message->get_merchant();
		$merchant->set_id( $this->merchant_id );
		$merchant->set_sub_id( $this->sub_id );

		return $this->send_message( $request_dir_message );
	}

	public function create_transaction( Pronamic_Gateways_IDealAdvancedV3_Transaction $transaction, $issuer_id ) {
		$message = new Pronamic_Gateways_IDealAdvancedV3_XML_TransactionRequestMessage();

		$merchant = $message->get_merchant();
		$merchant->set_id( $this->merchant_id );
		$merchant->set_sub_id( $this->sub_id );
		$merchant->set_return_url( site_url( '/' ) ); 

		$message->issuer = new Pronamic_Gateways_IDealAdvancedV3_Issuer();
		$message->issuer->set_id( $issuer_id );
		
		$message->transaction = $transaction;

		echo '<pre>';
		echo htmlspecialchars( (string) $message );
		echo '</pre>';
		
		//return $this->send_message( $message );
	}
	
	private function sign_document( DOMDocument $document ) {
		$dsig = new XMLSecurityDSig();
		$dsig->setCanonicalMethod( XMLSecurityDSig::EXC_C14N );
		$dsig->addReference( 
			$document,
			XMLSecurityDSig::SHA256,
			array( 'http://www.w3.org/2000/09/xmldsig#enveloped-signature' ),
			array( 'force_uri' => true )
		);
		
		$key = new XMLSecurityKey( XMLSecurityKey::RSA_SHA256, array( 'type' => 'private' ) );
		$key->passphrase = $this->private_key_password;
		$key->loadKey( $this->private_key );
		
		$dsig->sign( $key );
		
		$fingerprint = Pronamic_Gateways_IDealAdvanced_Security::getShaFingerprint( $this->private_certificate );
		
		$dsig->addKeyInfoAndName( $fingerprint );
		$dsig->appendSignature( $document->documentElement );

		return $document;
	}
}
