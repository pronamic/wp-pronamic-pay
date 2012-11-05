<?php

/**
 * Title: iDEAL request XML message
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
abstract class Pronamic_Gateways_IDealAdvanced_XML_RequestMessage extends Pronamic_Gateways_IDealAdvanced_XML_Message  {
	/**
	 * Merchant
	 * 
	 * @var Merchant
	 */
	private $merchant;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize an request message
	 * 
	 * @param string $name
	 */
	public function __construct($name) {
		parent::__construct($name);

		$this->merchant = new Pronamic_IDeal_Merchant();
	}

	//////////////////////////////////////////////////

	/**
	 * Get the merchant
	 * 
	 * @return string
	 */
	public function getMerchant() {
		return $this->merchant;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the DOM document
	 * 
	 * @return DOMDocument
	 */
	protected function getDocument() {
		$document = new DOMDocument(parent::XML_VERSION, parent::XML_ENCODING);
		$document->formatOutput = true;
		
		// Root
		$root = $document->createElementNS(parent::XML_NAMESPACE, $this->getName());
		$root->setAttribute('version', parent::VERSION);

		$document->appendChild($root);
		
		// Create date timestamp
		$timestamp = $this->getCreateDate()->format(Pronamic_IDeal_IDeal::DATE_FORMAT);

		$element = $document->createElement('createDateTimeStamp', $timestamp);

		$root->appendChild($element);
	
		return $document;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the sign values
	 * 
	 * @return array
	 */
	public function getSignValues() {
		return array();
	}

	/**
	 * Sign this request message
	 * .
	 * @param string $privateKeyFile
	 * @param string $privateKeyPassword
	 */
	public function sign($privateKeyFile, $privateKeyPassword) {
		$values = $this->getSignValues();

		$message = implode('', $values);

		$sign = Pronamic_IDeal_Security::signMessage($privateKeyFile, $privateKeyPassword, $message);

		$this->merchant->tokenCode = base64_encode($sign);
	}

	//////////////////////////////////////////////////

	/**
	 * Create a string representation
	 * 
	 * @return string
	 */
	public function __toString() {
		$document = $this->getDocument();

		return $document->saveXML();
	}
}
