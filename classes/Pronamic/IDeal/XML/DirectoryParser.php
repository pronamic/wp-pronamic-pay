<?php

namespace Pronamic\IDeal\XML;

use Pronamic\IDeal\Directory;

/**
 * Title: Issuer XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class DirectoryParser extends Parser {
	public static function parse(\SimpleXMLElement $xml) {
		$directory = new Directory();

		$timestamp = (string) $xml->directoryDateTimeStamp;
		$directory->setDate(new \DateTime($timestamp));
		
		foreach($xml->Issuer as $element) {
			$issuer = IssuerParser::parse($element);
			$directory->addIssuer($issuer);
		}

		return $directory;
	}
}
