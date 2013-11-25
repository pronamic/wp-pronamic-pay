<?php

/**
 * Title: iDEAL directory response XML message
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvancedV3_XML_DirectoryResponseMessage extends Pronamic_Gateways_IDealAdvancedV3_XML_ResponseMessage {
	/**
	 * The document element name
	 * 
	 * @var string
	 */
	const NAME = 'DirectoryRes';

	//////////////////////////////////////////////////

	/**
	 * The directory
	 * 
	 * @var Directory
	 */
	public $directory;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize an directory response message
	 */
	public function __construct() {
		parent::__construct( self::NAME );
	}

	//////////////////////////////////////////////////

	/**
	 * Get the directory
	 * 
	 * @return Pronamic_Gateways_IDealAdvancedV3_Directory
	 */
	public function get_directory() {
		return $this->directory;
	}

	//////////////////////////////////////////////////

	/**
	 * Parse the specified XML into an directory response message object
	 * 
	 * @param SimpleXMLElement $xml
	 */
	public static function parse( SimpleXMLElement $xml ) {
		$message = self::parse_create_date( $xml, new self() );
		
		$message->directory = Pronamic_Gateways_IDealAdvancedV3_XML_DirectoryParser::parse( $xml->Directory );

		return $message;
	}
};
