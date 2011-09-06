<?php

/**
 * Title: Error XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_IDeal_XML_ErrorParser extends Pronamic_IDeal_XML_Parser {
	public static function parse(SimpleXMLElement $xml) {
		$error = new Pronamic_IDeal_Error();

		$error->setCode((string) $xml->errorCode);
		$error->setMessage((string) $xml->errorMessage);
		$error->setDetail((string) $xml->errorDetail);
		$error->setConsumerMessage((string) $xml->consumerMessage);
		
		return $error;
	}
}
