<?php

/**
 * Title: XML utility class
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_XML_Util {
	public static function filter( $variable, $filter = FILTER_SANITIZE_STRING ) {
		$result = null;

		$value = filter_var( (string) $variable, $filter );
		
		if ( strlen( $value ) > 0 ) {
			$result = $value;
		}
		
		return $result;
	}
}
