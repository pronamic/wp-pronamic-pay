<?php

/**
 * Title: iDEAL XML message
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_PayDutch_XML_Message {
	/**
	 * The XML version of the iDEAL messages
	 * 
	 * @var string
	 */
	const XML_VERSION = '1.0';

	/**
	 * The XML encoding of the iDEAL messages
	 * 
	 * @var string
	 */
	const XML_ENCODING = 'UTF-8';

	//////////////////////////////////////////////////

	/**
	 * The name of this message
	 * 
	 * @var string
	 */
	private $name;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize an message
	 */
	public function __construct( $name ) {
		$this->name = $name;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the name of this message
	 * 
	 * return string
	 */
	public function get_name() {
		return $this->name;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the DOM document
	 * 
	 * @return DOMDocument
	 */
	public static function new_dom_document() {
		$document = new DOMDocument( self::XML_VERSION, self::XML_ENCODING );

		// Settings
		$document->preserveWhiteSpace = false;
		$document->formatOutput = true;
		
		return $document;
	}

	//////////////////////////////////////////////////

	/**
	 * Create and add an element with the specified name and value to the specified parent
	 * 
	 * @param DOMDocument $document
	 * @param DOMNode $parent
	 * @param string $name
	 * @param string $value
	 */
	public static function add_element( DOMDocument $document, DOMNode $parent, $name, $value = null ) {
		$element = $document->createElement( $name );
		
		if ( $value !== null ) {
			$element->appendChild( new DOMText( $value ) );
		}

		$parent->appendChild( $element );

		return $element;
	}

	/**
	 * Add the specified elements to the parent node
	 * 
	 * @param DOMDocument $document
	 * @param DOMNode $parent
	 * @param array $elements
	 */
	public static function add_elements( DOMDocument $document, DOMNode $parent, array $elements = array() ) {
		foreach ( $elements as $name => $value ) {
			$element = $document->createElement( $name );

			if ( $value !== null ) {
				$element->appendChild( new DOMText( $value ) );
			}
			
			$parent->appendChild( $element );
		}
	}
}
