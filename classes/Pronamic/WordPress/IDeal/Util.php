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
	 * Remove query from URL
	 * 
	 * @param string $url
	 * @return string
	 */
	public static function removeQueryFromUrl($url) {
		// @todo see if we can use: 
		// http://php.net/parse_url and http://php.net/manual/en/function.http-build-url.php
		$url = preg_replace('/\?.*/', '', $url);

		return $url;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the language from WordPress WPLANG options or constant
	 * 
	 * @return string
	 */
	public static function getLanguage() {
		$language = get_option('WPLANG', WPLANG);

		return substr($language, 0, 2);
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
