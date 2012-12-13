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
	private $acquirerUrl;
	
	//////////////////////////////////////////////////
	
	public $directory_request_url;

	public $transaction_request_url;
	
	public $status_request_url;
	
	//////////////////////////////////////////////////

	/**
	 * The private key file
	 * 
	 * @var string
	 */
	private $privateKeyFile;

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
	private $privateCertificateFile;

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
		$this->acquirerUrl = $url;

		$this->directory_request_url   = $url;
		$this->transaction_request_url = $url;
		$this->status_request_url      = $url;
	}

	//////////////////////////////////////////////////

	public function setPrivateKey($key) {
		$this->privateKeyFile = $key;
	}

	public function setPrivateKeyPassword($password) {
		$this->privateKeyPassword = $password;
	}

	public function setPrivateCertificate($certificate) {
		$this->privateCertificateFile = $certificate;
	}

	//////////////////////////////////////////////////

	public function addPublicCertificate($file) {
		$this->publicCertificates[] = $file;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the error
	 * 
	 * @return string
	 */
	public function getError() {
		return $this->error;
	}
	
	//////////////////////////////////////////////////

	/**
	 * Send an message
	 */
	private function sendMessage( $data ) {
		$result = false;

		$url = $this->acquirerUrl;

		// Stringify
		$data = (string) $data;

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

				$document = simplexml_load_string( $body );
	
				if ( $document !== false ) {
					$result = $this->parseDocument( $document );
				} else {
					throw new Exception( 'Unknown response message' );
				}
			} else {
				var_dump( $response );
			}
		} else {
			var_dump( $response );
		}

		return $result;
	}

	//////////////////////////////////////////////////

	/**
	 * Parse the specified document and return parsed result
	 * 
	 * @param SimpleXMLElement $document
	 */
	private function parseDocument(SimpleXMLElement $document) {
		$this->error = null;

		switch($document->getName()) {
			case Pronamic_Gateways_IDealAdvanced_XML_ErrorResponseMessage::NAME:
				$message = Pronamic_Gateways_IDealAdvanced_XML_ErrorResponseMessage::parse($document);

				$this->error = $message->error;

				return $message;
			case Pronamic_Gateways_IDealAdvanced_XML_DirectoryResponseMessage::NAME:
				return Pronamic_Gateways_IDealAdvanced_XML_DirectoryResponseMessage::parse($document);
			case Pronamic_Gateways_IDealAdvanced_XML_TransactionResponseMessage::NAME:
				return Pronamic_Gateways_IDealAdvanced_XML_TransactionResponseMessage::parse($document);
			case Pronamic_Gateways_IDealAdvanced_XML_StatusResponseMessage::NAME:
				return Pronamic_Gateways_IDealAdvanced_XML_StatusResponseMessage::parse($document);
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
	public function getDirectory(Pronamic_Gateways_IDealAdvanced_XML_DirectoryRequestMessage $message) {
		$directory = null;

		$response = $this->sendMessage($message);
		if($response instanceof Pronamic_Gateways_IDealAdvanced_XML_DirectoryResponseMessage) {
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
	public function getIssuers(Pronamic_Gateways_IDealAdvanced_XML_DirectoryRequestMessage $message) {
		$issuers = null;

		$directory = $this->getDirectory($message);
		if($directory != null) {
			$issuers = $directory->getIssuers();
		}

		return $issuers;
	}

	/**
	 * Get the issuers list
	 * 
	 * @return array
	 */
	public function getIssuerLists(Pronamic_Gateways_IDealAdvanced_XML_DirectoryRequestMessage $message) {
		$lists = null;

		$directory = $this->getDirectory($message);
		if($directory != null) {
			$lists = $directory->getLists();
		}

		return $lists;
	}

	//////////////////////////////////////////////////

	/**
	 * Create an transaction by send an transaction request message
	 * 
	 * @param TransactionRequestMessage $message
	 */
	public function createTransaction(Pronamic_Gateways_IDealAdvanced_XML_TransactionRequestMessage $message) {
		$response = $this->sendMessage($message);

		if($response instanceof Pronamic_Gateways_IDealAdvanced_XML_TransactionResponseMessage) {
			$message->issuer->authenticationUrl = $response->issuer->authenticationUrl;

			$message->transaction->setId((string) $response->transaction->getId());
		} else {
			// @todo what if response is an error repsonse message, for example:
			// Field generating error: language. Parameter '' has less than 2 characters
		}

		return $response;
	}

	//////////////////////////////////////////////////

	/**
	 * Create an transaction by send an transaction request message
	 * 
	 * @param TransactionRequestMessage $message
	 */
	public function getStatus(Pronamic_Gateways_IDealAdvanced_XML_StatusRequestMessage $message) {
		$response = $this->sendMessage($message);

		if($response instanceof Pronamic_Gateways_IDealAdvanced_XML_StatusResponseMessage) {
			$message->transaction->setStatus($response->transaction->getStatus());
			$message->transaction->setConsumerName($response->transaction->getConsumerName());
			$message->transaction->setConsumerAccountNumber($response->transaction->getConsumerAccountNumber());
			$message->transaction->setConsumerCity($response->transaction->getConsumerCity());
		}

		return $response;
	}
}
