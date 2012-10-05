<?php

/**
 * Title: iDEAL XML message
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_IDeal_XML_Message {
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

	/**
	 * The XML namespace of the iDEAL messages
	 * 
	 * @var string
	 */
	const XML_NAMESPACE = 'http://www.idealdesk.com/Message';

	//////////////////////////////////////////////////

	/**
	 * The version of the iDEAL messages
	 * 
	 * @var string
	 */
	const VERSION = '1.1.0';

	//////////////////////////////////////////////////

	/**
	 * The name of this message
	 * 
	 * @var string
	 */
	private $name;

	//////////////////////////////////////////////////

	/**
	 * The create date of this message
	 * 
	 * @var DateTime
	 */
	private $createDate;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize an message
	 */
	public function __construct($name) {
		$this->name = $name;
		$this->createDate = new DateTime(null, new DateTimeZone(Pronamic_IDeal_IDeal::TIMEZONE));
	}

	//////////////////////////////////////////////////

	/**
	 * Get the name of this message
	 * 
	 * return string
	 */
	public function getName() {
		return $this->name;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the create date
	 * 
	 * @return DateTime
	 */
	public function getCreateDate() {
		return $this->createDate;
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
	public static function addElement(DOMDocument $document, DOMNode $parent, $name, $value = null) {
		$element = $document->createElement($name);
		
		if($value !== null) {
			$element->appendChild(new DOMText($value));
		}

		$parent->appendChild($element);

		return $element;
	}
}
