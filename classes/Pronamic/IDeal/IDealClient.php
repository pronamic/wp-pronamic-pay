<?php

namespace Pronamic\IDeal;

use Pronamic\IDeal\XML\StatusResponseMessage;

use Pronamic\IDeal\XML\TransactionResponseMessage;

use Pronamic\IDeal\XML\DirectoryResponseMessage;

use Pronamic\IDeal\XML\ErrorResponseMessage;

use Pronamic\IDeal\XML\StatusRequestMessage;

use Pronamic\IDeal\XML\TransactionRequestMessage;

use Pronamic\IDeal\XML\Message;
use Pronamic\IDeal\XML\DirectoryRequestMessage;
use Pronamic\IDeal\XML\DirectoryParser;
use Pronamic\IDeal\XML\ErrorParser;

/**
 * Title: iDEAL client
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class IDealClient {
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

		$contentType = 'Content-Type: text/html; charset=' . Message::XML_ENCODING;

		$url = $this->acquirerUrl;

		$parts = parse_url($url);
		$scheme = $parts['scheme'];
		$host = $parts['host'];
		$port = $parts['port'];
		$path = $parts['path'];
		$errorNumber = null;
		$errorString = null;
		
		$hostname = $scheme . '://' . $host;
		
		// Adjust the error reporting level
		$previousErrorLevel = error_reporting(E_ERROR);

		// Connect with acquirer, will throw an warning error if fails
		$resource = fsockopen($hostname, $port, $errorNumber, $errorString, $timeout);

		// Set the error reporting back to previous error reporting level
		error_reporting($previousErrorLevel);

		if($resource !== false) {
			$message = (string) $data;
			$message = utf8_decode($message);

			fputs($resource, "POST $path HTTP/1.0\r\n");
			fputs($resource, "Accept: text/html\r\n");
			fputs($resource, "Accept: charset=ISO-8859-1\r\n");
			fputs($resource, 'Content-Length: ' . strlen($data) . "\r\n");
			fputs($resource, 'Content-Type: ' . $contentType . "\r\n\r\n");
			fputs($resource, $message, strlen($message));
		
			$result = '';
			while(!feof($resource)) {
				$result .= fgets($resource, 128);
			}

			fclose($resource);
		
			$position = strpos($result, "\r\n\r\n");

			if($position !== false) {
				$body = substr($result, $position + 4);
				
				$result = $body;
			}
		} else {
			throw new \Exception('Could not connect with the acquirer');
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
				throw new \Exception('Unknown response message');
			}
		}
		
		return $result;
	}

	//////////////////////////////////////////////////

	/**
	 * Parse the specified document and return parsed result
	 * 
	 * @param unknown_type $document
	 */
	private function parseDocument($document) {
		$this->error = null;

		switch($document->getName()) {
			case ErrorResponseMessage::NAME:
				$message = ErrorResponseMessage::parse($document);

				$this->error = $message->error;

				return $message;
			case DirectoryResponseMessage::NAME:
				return DirectoryResponseMessage::parse($document);
			case TransactionResponseMessage::NAME:
				return TransactionResponseMessage::parse($document);
			case StatusResponseMessage::NAME:
				return StatusResponseMessage::parse($document);
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
	public function getDirectory(DirectoryRequestMessage $message) {
		$directory = null;

		$response = $this->sendMessage($message);
		if($response instanceof DirectoryResponseMessage) {
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
	public function getIssuers(DirectoryRequestMessage $message) {
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
	public function getIssuerLists(DirectoryRequestMessage $message) {
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
	public function createTransaction(TransactionRequestMessage $message) {
		$response = $this->sendMessage($message);
		if($response instanceof TransactionResponseMessage) {
			$message->issuer->authenticationUrl = $response->issuer->authenticationUrl;

			$message->transaction->setId((string) $response->transaction->getId());
		}

		return $response;
	}

	//////////////////////////////////////////////////

	/**
	 * Create an transaction by send an transaction request message
	 * 
	 * @param TransactionRequestMessage $message
	 */
	public function getStatus(StatusRequestMessage $message) {
		$response = $this->sendMessage($message);
		if($response instanceof StatusResponseMessage) {
			$message->transaction->setStatus($response->transaction->getStatus());
			$message->transaction->setConsumerName($response->transaction->getConsumerName());
			$message->transaction->setConsumerAccountNumber($response->transaction->getConsumerAccountNumber());
			$message->transaction->setConsumerCity($response->transaction->getConsumerCity());
		}

		return $response;
	}
}
