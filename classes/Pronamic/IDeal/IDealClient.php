<?php

/**
 * Title: iDEAL client
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_IDeal_IDealClient {
	/**
	 * The acquirer URL
	 * 
	 * @var string
	 */
	private $acquirerUrl;
	
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
	public function setAcquirerUrl($acquirerUrl) {
		$this->acquirerUrl = $acquirerUrl;
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

	private function doHttpRequest($data, $timeout = 30) {
		$result = null;

		$contentType = 'Content-Type: text/html; charset=' . Pronamic_IDeal_XML_Message::XML_ENCODING;

		$url = new Pronamic_Net_URL($this->acquirerUrl);
		
		$hostname = $url->getScheme() . '://' . $url->getHost();

		// Connect with acquirer, will throw an warning error if fails
		$resource = @fsockopen($hostname, $url->getPort(true), $errorNumber, $errorString, $timeout);
		if($resource !== false) {
			$message = (string) $data;
			$message = utf8_decode($message);

			fputs($resource, 'POST ' . $url->getPath(true) . ' HTTP/1.0' . Pronamic_Net_HTTP::CRLF);
			fputs($resource, 'Accept: text/html' . Pronamic_Net_HTTP::CRLF);
			fputs($resource, 'Accept: charset=ISO-8859-1' . Pronamic_Net_HTTP::CRLF);
			fputs($resource, 'Content-Length: ' . strlen($data) . Pronamic_Net_HTTP::CRLF);
			fputs($resource, 'Content-Type: ' . $contentType . Pronamic_Net_HTTP::CRLF);
			fputs($resource, Pronamic_Net_HTTP::CRLF);
			fputs($resource, $message, strlen($message));

			$result = '';
			while(!feof($resource)) {
				$result .= fgets($resource, 128);
			}

			fclose($resource);
		
			$position = strpos($result, Pronamic_Net_HTTP::CRLF . Pronamic_Net_HTTP::CRLF);

			if($position !== false) {
				$body = substr($result, $position + 4);
				
				$result = $body;
			}
		} else {
			// throw new Exception('Could not connect with the acquirer');
			// @todo what to do?
			
		}
		
		return $result;
	}

	/**
	 * Send an message
	 */
	public function sendMessage($data, $timeout = 30) {
		$result = null;
		
		$response = $this->doHttpRequest($data, $timeout);
		if($response) {
			$document = simplexml_load_string($response);

			if($document !== false) {
				$result = $this->parseDocument($document);
			} else {
				throw new Exception('Unknown response message');
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
	private function parseDocument(SimpleXMLElement $document) {
		$this->error = null;

		switch($document->getName()) {
			case Pronamic_IDeal_XML_ErrorResponseMessage::NAME:
				$message = Pronamic_IDeal_XML_ErrorResponseMessage::parse($document);

				$this->error = $message->error;

				return $message;
			case Pronamic_IDeal_XML_DirectoryResponseMessage::NAME:
				return Pronamic_IDeal_XML_DirectoryResponseMessage::parse($document);
			case Pronamic_IDeal_XML_TransactionResponseMessage::NAME:
				return Pronamic_IDeal_XML_TransactionResponseMessage::parse($document);
			case Pronamic_IDeal_XML_StatusResponseMessage::NAME:
				return Pronamic_IDeal_XML_StatusResponseMessage::parse($document);
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
	public function getDirectory(Pronamic_IDeal_XML_DirectoryRequestMessage $message) {
		$directory = null;

		$response = $this->sendMessage($message);
		if($response instanceof Pronamic_IDeal_XML_DirectoryResponseMessage) {
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
	public function getIssuers(Pronamic_IDeal_XML_DirectoryRequestMessage $message) {
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
	public function getIssuerLists(Pronamic_IDeal_XML_DirectoryRequestMessage $message) {
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
	public function createTransaction(Pronamic_IDeal_XML_TransactionRequestMessage $message) {
		$response = $this->sendMessage($message);

		if($response instanceof Pronamic_IDeal_XML_TransactionResponseMessage) {
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
	public function getStatus(Pronamic_IDeal_XML_StatusRequestMessage $message) {
		$response = $this->sendMessage($message);

		if($response instanceof Pronamic_IDeal_XML_StatusResponseMessage) {
			$message->transaction->setStatus($response->transaction->getStatus());
			$message->transaction->setConsumerName($response->transaction->getConsumerName());
			$message->transaction->setConsumerAccountNumber($response->transaction->getConsumerAccountNumber());
			$message->transaction->setConsumerCity($response->transaction->getConsumerCity());
		}

		return $response;
	}
}
