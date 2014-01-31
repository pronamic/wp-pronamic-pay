<?php

/**
 * Title: Sisow utility class
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Sisow_Util {
	/**
	 * Holds the unallowed character pattern
	 * 
	 * @var string
	 */
	private static $pattern;

	/**
	 * Get unallowed character pattern
	 * 
	 * Karakterset
	 * @see http://pronamic.nl/wp-content/uploads/2013/02/sisow-rest-api-v3.2.1.pdf
	 * 
	 * Hieronder de tabel toegestane karakters.
	 * 
	 * Karakter(s)  Omschrijving
	 * A-Z          Hoofdletters
	 * a-z          Kleine letters
	 * 0-9          Cijfers
	 * =            Is/gelijk
	 *              Spatie
	 * %            Procent
	 * *            Asterisk
	 * +            Plus
	 * -            Min
	 * .            Punt
	 * /            Slash
	 * &            Ampersand
	 * @            Apestaart
	 * "            Dubbel quote
	 * '            Enkele quote
	 * :            Dubbele punt
	 * ;            Punt komma
	 * ?            Vraagteken
	 * (            Haakje openen
	 * )            Haakje sluiten
	 * $            Dollar
	 */
	public static function get_pattern() {
		if ( null === self::$pattern ) {
			$characters = array(
				'A-Z',
				'a-z',
				'0-9',
				'=',
				' ',
				'%',
				'*',
				'+',
				'-',
				'.',
				'/',
				'&',
				'@',
				'"',
				'\'',
				':',
				';',
				'?',
				'(',
				')',
				'$',
			);
			
			// We use a # as a regex delimiter instead of a / so we don't have to escape the slash
			// @see http://stackoverflow.com/q/12239424
			self::$pattern = '#[^' . implode( $characters ) . ']#';
		}
		
		return self::$pattern;
	}

	/**
	 * Filter all Sisow unallowed charachters
	 * 
	 * @param string $string
	 * @return mixed
	 */
	public static function filter( $string ) {
		return preg_filter( self::get_pattern(), '', $string );
	}
}
