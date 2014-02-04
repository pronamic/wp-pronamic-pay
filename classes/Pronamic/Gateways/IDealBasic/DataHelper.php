<?php

/**
 * Title: iDEAL Basic data helper class
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 * @see http://pronamic.nl/wp-content/uploads/2011/12/iDEAL_Basic_EN_v2.3.pdf
 */
class Pronamic_Gateways_IDealBasic_DataHelper {
	/**
	 * Alphanumerical, free text
	 * 
	 * The iDEAL Basic documentation is not very clear about which characters 
	 * are allowed within the alphanumeric (AN) specification. Pronamic 
	 * contacted some of the iDEAL providers and the ING Customer Support 
	 * Desk (csd.icm@ing.nl) replied with the following message (Dutch):
	 * 
	 * > Naast A-Z, a-z en 0-9 is alleen de spatie nog een geaccepteerd teken binnen AN.
	 * > 
	 * > Andere karakters zijn niet toegestaan. Het is met name belangrijk dat er geen html tags in voorkomen.:
	 * 
	 * @var array
	 */
	private static $characters_an = array( 'A-Z', 'a-z', '0-9', ' ' );

	/**
	 * Strictly alphanumerical (letters and numbers only)
	 * 
	 * @var array
	 */
	private static $characters_ans = array( 'A-Z', 'a-z', '0-9' );

	/**
	 * Numerical
	 * 
	 * @var array
	 */
	private static $characters_n = array( '0-9' );

	//////////////////////////////////////////////////

	/**
	 * Filter the specified characters from the string
	 * 
	 * @param array $characters
	 * @param string $string
	 * @param int $max
	 * @return string
	 */
	public static function filter( array $characters, $string, $max = null ) {
		$pattern = '#[^' . implode( $characters ) . ']#';
		
		$string = preg_replace( $pattern, '', $string );

		if ( isset( $max ) ) {
			$string = substr( $string, 0, $max );
		}
		
		return $string;
	}

	//////////////////////////////////////////////////

 	/**
 	 * Alphanumerical, free text
 	 * 
 	 * @param string $string
 	 * @param string $max
 	 * @return Ambigous <string, mixed>
 	 */
	public static function an( $string, $max = null ) {
		return self::filter( self::$characters_an, $string, $max );
	}

	public static function an16( $string ) { return self::an( $string, 16 ); }
	public static function an32( $string ) { return self::an( $string, 32 ); }

	/**
	 * Strictly alphanumerical (letters and numbers only)
	 * 
	 * @param string $string
	 * @param string $max
	 * @return Ambigous <string, mixed>
	 */
	public static function ans( $string, $max = null ) {
		return self::filter( self::$characters_ans, $string, $max );
	}

	public static function ans16( $string ) { return self::ans( $string, 16 ); }
	public static function ans32( $string ) { return self::ans( $string, 32 ); }

	/**
	 * Strictly alphanumerical (letters and numbers only)
	 * 
	 * @param string $string
	 * @param string $max
	 * @return Ambigous <string, mixed>
	 */
	public static function n( $string, $max = null ) {
		return self::filter( self::$characters_n, $string, $max );
	}
}
