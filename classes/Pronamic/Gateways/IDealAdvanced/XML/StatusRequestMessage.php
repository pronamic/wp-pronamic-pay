<?php

/**
 * Title: iDEAL status request XML message
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvanced_XML_StatusRequestMessage extends Pronamic_Gateways_IDealAdvanced_XML_RequestMessage {
	/**
	 * The document element name
	 * 
	 * @var string
	 */
	const NAME = 'AcquirerStatusReq';

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize an status request message
	 */
	public function __construct() {
		parent::__construct(self::NAME);
	}

	//////////////////////////////////////////////////

	/**
	 * Get document
	 * 
	 * @return DOMDocument
	 */
	public function getDocument() {
		$document = parent::getDocument();
		
		// Root
		$root = $document->documentElement;

		// Merchant
		$merchant = $this->getMerchant();

		$element = self::addElement($document, $document->documentElement, 'Merchant');
		self::addElement($document, $element, 'merchantID', $merchant->id);
		self::addElement($document, $element, 'subID', $merchant->subId);
		self::addElement($document, $element, 'authentication', $merchant->authentication);
		self::addElement($document, $element, 'token', $merchant->token);
		self::addElement($document, $element, 'tokenCode', $merchant->tokenCode);
		self::addElement($document, $element, 'merchantReturnURL', $merchant->returnUrl);

		// Transaction
		$transaction = $this->transaction;

		$element = self::addElement($document, $document->documentElement, 'Transaction');
		self::addElement($document, $element, 'transactionID', $transaction->getId());

		// Return
		return $document;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the sign values for this status message
	 * 
	 * @return array
	 */
	public function getSignValues() {
		return array(
			$this->getCreateDate()->format(Pronamic_IDeal_IDeal::DATE_FORMAT) , 
			$this->merchant->id , 
			$this->merchant->subId , 
			$this->transaction->getId()
		);
	}
}
