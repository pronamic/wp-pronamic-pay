<?php

/**
 * Title: iDEAL response XML message
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
abstract class Pronamic_Gateways_IDealAdvancedV3_XML_ResponseMessage extends Pronamic_Gateways_IDealAdvancedV3_XML_Message {
	/**
	 * Constructs and initialize an response message
	 */
	public function __construct( $name ) {
		parent::__construct( $name );
	}

	//////////////////////////////////////////////////

	/**
	 * Parse the specified XML into an directory response message object
	 *
	 * @param SimpleXMLElement $xml
	 */
	public static function parse_create_date( SimpleXMLElement $xml, self $message ) {
		if ( $xml->createDateTimestamp ) {
			$date = new DateTime( (string) $xml->createDateTimestamp );

			$message->set_create_date( $date );
		}

		return $message;
	}
}
