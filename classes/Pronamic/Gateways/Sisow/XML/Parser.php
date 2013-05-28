<?php

/**
 * Title: XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
abstract class Pronamic_Gateways_Sisow_XML_Parser {
	/**
	 * Parse the specified XML element
	 * 
	 * @param SimpleXMLElement $xml
	 */
	public abstract static function parse( SimpleXMLElement $xml );
}
