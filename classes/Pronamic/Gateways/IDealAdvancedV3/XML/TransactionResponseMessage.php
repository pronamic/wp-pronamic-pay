<?php

/**
 * Title: iDEAL transaction response XML message
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvancedV3_XML_TransactionResponseMessage extends Pronamic_Gateways_IDealAdvancedV3_XML_ResponseMessage {
	/**
	 * The document element name
	 * 
	 * @var string
	 */
	const NAME = 'AcquirerTrxRes';

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize an directory response message
	 */
	public function __construct() {
		parent::__construct( self::NAME );
	}

	//////////////////////////////////////////////////

	/**
	 * Parse the specified XML into an directory response message object
	 * 
	 * @param SimpleXMLElement $xml
	 */
	public static function parse( SimpleXMLElement $xml ) {
		$message = parent::parse( $xml, new self() );
		$message->issuer = Pronamic_Gateways_IDealAdvancedV3_XML_IssuerParser::parse( $xml->Issuer );
		$message->transaction = Pronamic_Gateways_IDealAdvancedV3_XML_TransactionParser::parse( $xml->Transaction );

		return $message;
	}
}
