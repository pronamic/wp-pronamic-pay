<?php

namespace Pronamic\IDeal\XML;

/**
 * Title: iDEAL status response XML message
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class StatusResponseMessage extends ResponseMessage {
	/**
	 * The document element name
	 * 
	 * @var string
	 */
	const NAME = 'AcquirerStatusRes';

	//////////////////////////////////////////////////

	public $transaction;

	public $signature;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize an status response message
	 */
	public function __construct() {
		parent::__construct(self::NAME);
	}

	//////////////////////////////////////////////////

	/**
	 * Parse the specified XML into an directory response message object
	 * 
	 * @param \SimpleXMLElement $xml
	 */
	public static function parse(\SimpleXMLElement $xml) {
		$message = parent::parse($xml, new self());
		$message->transaction = TransactionParser::parse($xml->Transaction);
		// $message->transaction = SignatureParser::parse($xml->Signature);

		return $message;
	}
};
