<?php

/**
 * Title: iDEAL directory request XML message
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_PayDutch_XML_ListMethodRequestMessage extends Pronamic_Gateways_PayDutch_XML_RequestMessage {
	/**
	 * Type
	 * 
	 * @var string
	 */
	const TYPE = 'listmethod';

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize a directory request message
	 */
	public function __construct( $merchant ) {
		parent::__construct( self::TYPE );
		
		$this->merchant = $merchant;
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
		$merchant = $this->merchant;

		$element = self::add_element( $document, $document->documentElement, 'merchant' );
		self::add_elements( $document, $element, array(
			'username' => $merchant->username,
			'password' => $merchant->password
		) );

		// Return
		return $document;
	}
}
