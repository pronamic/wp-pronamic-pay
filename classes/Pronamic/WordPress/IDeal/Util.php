<?php

/**
 * Title: WordPress iDEAL plugin
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WordPress_IDeal_Util {
	/**
	 * Language default
	 * 
	 * @var string
	 */
	const LANGUAGE_DEFAULT = 'en_US';

	//////////////////////////////////////////////////

	/**
	 * Helper function to retrieve the WordPress language
	 * 
	 * @return string
	 */
	public static function getLanguage() {
		$language = get_option('WPLANG', WPLANG);

		if(empty($language)) {
			// Prevent field generating error: language. Parameter '' has less than 2 characters
			$language = self::LANGUAGE_DEFAULT;
		}

		return $language;
	}

	/**
	 * Get the ISO 639 language code
	 * 
	 * @see Pronamic_Pay_PaymentDataInterface::get_language()
	 * @return string
	 */
	public static function get_language() {
		$language = self::getLanguage();
	
		return substr( $language, 0, 2 );
	}

	/**
	 * Get the language ISO 639 and ISO 3166 country code
	 * 
	 * @see Pronamic_Pay_PaymentDataInterface::get_language_and_country()
	 * @return string
	 */
	public static function get_language_and_country() {
		return self::getLanguage();
	}
}
