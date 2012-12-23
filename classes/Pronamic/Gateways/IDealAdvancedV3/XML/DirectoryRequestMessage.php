<?php

/**
 * Title: iDEAL directory request XML message
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvancedV3_XML_DirectoryRequestMessage extends Pronamic_Gateways_IDealAdvancedV3_XML_RequestMessage {
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
	public function get_document() {
		$document = parent::get_document();

		// Merchant
		$merchant = $this->get_merchant();

		$element = self::add_element( $document, $document->documentElement, 'Merchant' );
		self::add_elements( $document, $element, array(
			'merchantID' => $merchant->get_id(),
			'subID'      => $merchant->get_sub_id()
		) );

		// Return
		return $document;
	}
}
