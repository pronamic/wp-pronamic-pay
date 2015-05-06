<?php

/**
 * Title: XML utility class
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_XML_Util {
	public static function filter( $variable, $filter = FILTER_SANITIZE_STRING ) {
		$result = null;

		$value = filter_var( (string) $variable, $filter );

		if ( strlen( $value ) > 0 ) {
			$result = $value;
		}

		return $result;
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

		if ( null !== $value ) {
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

			if ( null !== $value ) {
				$element->appendChild( new DOMText( $value ) );
			}

			$parent->appendChild( $element );
		}
	}
}
