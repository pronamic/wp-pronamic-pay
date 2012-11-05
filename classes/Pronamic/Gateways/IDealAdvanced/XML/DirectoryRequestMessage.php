<?php

/**
 * Title: iDEAL directory request XML message
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvanced_XML_DirectoryRequestMessage extends Pronamic_Gateways_IDealAdvanced_XML_RequestMessage {
	/**
	 * The document element name
	 * 
	 * @var string
	 */
	const NAME = 'DirectoryReq';

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize a directory request message
	 */
	public function __construct() {
		parent::__construct( self::NAME );
	}

	//////////////////////////////////////////////////

	/**
	 * Get document
	 * 
	 * @see Pronamic_Gateways_IDealAdvanced_XML_RequestMessage::getDocument()
	 */
	public function getDocument() {
		$document = parent::getDocument();

		// Merchant
		$merchant = $this->getMerchant();

		$element = self::addElement( $document, $document->documentElement, 'Merchant' );
		self::addElement( $document, $element, 'merchantID', $merchant->id );
		self::addElement( $document, $element, 'subID', $merchant->subId );
		self::addElement( $document, $element, 'authentication', $merchant->authentication );
		self::addElement( $document, $element, 'token', $merchant->token );
		self::addElement( $document, $element, 'tokenCode', $merchant->tokenCode );

		// Return
		return $document;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the sign values for this request message
	 * 
	 * @return array
	 */
	public function getSignValues() {
		return array(
			$this->getCreateDate()->format( Pronamic_IDeal_IDeal::DATE_FORMAT ),
			$this->getMerchant()->id,
			$this->getMerchant()->subId
		);
	}
}
