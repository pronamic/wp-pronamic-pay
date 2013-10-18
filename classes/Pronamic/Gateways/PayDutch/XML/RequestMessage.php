<?php

/**
 * Title: iDEAL request XML message
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
abstract class Pronamic_Gateways_PayDutch_XML_RequestMessage extends Pronamic_Gateways_PayDutch_XML_Message  {
	/**
	 * Constructs and initialize an request message
	 * 
	 * @param string $name
	 */
	public function __construct( $type ) {
		parent::__construct( 'reqeust' );
		
		$this->type = $type;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the DOM document
	 * 
	 * @return DOMDocument
	 */
	protected function get_document() {
		$document = new DOMDocument( parent::XML_VERSION, parent::XML_ENCODING );
		// We can't disable preservere white space and format the output
		// this is causing 'Invalid electronic signature' errors
		// $document->preserveWhiteSpace = true;
		// $document->formatOutput = true;
		
		// Root
		$root = $document->createElement( $this->get_name() );

		$document->appendChild( $root );
		
		// Type
		$element = $document->createElement( 'type', $this->type );

		$root->appendChild( $element );
	
		return $document;
	}

	//////////////////////////////////////////////////

	/**
	 * Create a string representation
	 * 
	 * @return string
	 */
	public function __toString() {
		$document = $this->get_document();

		return $document->saveXML();
	}
}
