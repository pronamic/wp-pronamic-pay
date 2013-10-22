<?php

/**
 * Title: Query request XML message
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_PayDutch_XML_QueryRequestMessage extends Pronamic_Gateways_PayDutch_XML_RequestMessage {
	const TYPE = 'query';	
	
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

		// Transaction request
		$element = self::add_element( $document, $document->documentElement, 'merchant' );
		self::add_elements( $document, $element, array(
			'username'    => $this->merchant->username,
			'password'    => $this->merchant->password,
			'reference'   => $this->merchant->reference,
			'test'        => Pronamic_WP_Util::to_string_boolean( $this->merchant->test )
		) );

		// Return
		return $document;
	}
}
