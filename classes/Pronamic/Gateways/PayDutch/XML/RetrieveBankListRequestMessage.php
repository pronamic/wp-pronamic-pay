<?php

/**
 * Title: iDEAL directory request XML message
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_PayDutch_XML_RetrieveBankListRequestMessage extends Pronamic_Gateways_PayDutch_XML_RequestMessage {
	const TYPE = 'retrievebanklist';

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize a directory request message
	 */
	public function __construct( $method_code, $test = false ) {
		parent::__construct( self::TYPE );

		$this->method_code = $method_code;
		$this->test        = $test;
	}

	//////////////////////////////////////////////////

	/**
	 * Get document
	 *
	 * @see Pronamic_Gateways_IDealAdvanced_XML_RequestMessage::getDocument()
	 */
	public function get_document() {
		$document = parent::get_document();

		// Retrieve Bank List
		$element = self::add_element( $document, $document->documentElement, 'retrievebanklist' );
		self::add_elements( $document, $element, array(
			'methodcode' => $this->method_code,
			'test'       => Pronamic_WP_Util::to_string_boolean( $this->test )
		) );

		// Return
		return $document;
	}
}
