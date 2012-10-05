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
	 * @see Pronamic_IDeal_IDealDataProxy::getLanguageIso639Code()
	 * @return string
	 */
	public static function getLanguageIso639Code() {
		$language = self::getLanguage();
	
		return substr($language, 0, 2);
	}

	/**
	 * Get the language ISO 639 and ISO 3166 country code
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getLanguageIso639AndCountryIso3166Code()
	 * @return string
	 */
	public static function getLanguageIso639AndCountryIso3166Code() {
		return self::getLanguage();
	}

	//////////////////////////////////////////////////

	/**
	 * Transform currency code to number
	 * 
	 * @param string $code
	 * @return string
	 */
	public static function transformCurrencyCodeToNumber($code) {
		$currencies = array();

		$file = dirname(Pronamic_WordPress_IDeal_Plugin::$file) . '/other/dl_iso_table_a1.xml';
		$xmlFile = simplexml_load_file($file);
	
		foreach($xmlFile->ISO_CURRENCY as $currency) {
			$alphabeticCode = (string) $currency->ALPHABETIC_CODE;
			$numericCode = (string) $currency->NUMERIC_CODE;
		
			$currencies[$alphabeticCode] = $numericCode;
		}

		$number = null;

		if(isset($currencies[$code])) {
			$number = $currencies[$code];
		}

		return $number;
	}
}
