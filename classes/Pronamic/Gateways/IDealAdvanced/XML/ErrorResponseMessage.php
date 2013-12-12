<?php

/**
 * Title: iDEAL error response XML message
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvanced_XML_ErrorResponseMessage extends Pronamic_Gateways_IDealAdvanced_XML_ResponseMessage {
	/**
	 * The document element name
	 * 
	 * @var string
	 */
	const NAME = 'ErrorRes';

	//////////////////////////////////////////////////

	/**
	 * The error within this response message
	 * 
	 * @var Error
	 */
	public $error;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize an error response message
	 */
	public function __construct() {
		parent::__construct( self::NAME );
	}

	//////////////////////////////////////////////////

	/**
	 * Get the error object
	 * 
	 * @return Pronamic_Gateways_IDealAdvanced_Error
	 */
	public function get_error() {
		return $this->error;
	}

	/**
	 * Set the error object
	 * 
	 * @param Pronamic_Gateways_IDealAdvanced_Error $error
	 */
	public function set_error( Pronamic_Gateways_IDealAdvanced_Error $error ) {
		$this->error = $error;
	}

	//////////////////////////////////////////////////

	/**
	 * Parse the specified XML into an directory response message object
	 * 
	 * @param SimpleXMLElement $xml
	 */
	public static function parse( SimpleXMLElement $xml ) {
		$message = new self();

		$message->set_error( Pronamic_Gateways_IDealAdvanced_XML_ErrorParser::parse( $xml->Error ) );

		return $message;
	}
}
