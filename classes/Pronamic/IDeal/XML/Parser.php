<?php

namespace Pronamic\IDeal\XML;

/**
 * Title: XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
abstract class Parser {
	public abstract static function parse(\SimpleXMLElement $xml);
}
