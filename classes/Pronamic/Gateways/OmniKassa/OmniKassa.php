<?php

/**
 * Title: OmniKassa gateway
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_OmniKassa_OmniKassa {
	const ISO_639_1_ENGLISH = 'en';

	const ISO_639_1_FRENCH = 'fr';
	
	const ISO_639_1_GERMAN = 'de';
	
	const ISO_639_1_ITALIAN = 'it';
	
	const ISO_639_1_SPANISH = 'es';
	
	const ISO_639_1_DUTCH = 'nl';

	public static function getSupportedLanguageCodes() {
		return array(
			self::ISO_639_1_ENGLISH ,
			self::ISO_639_1_FRENCH , 
			self::ISO_639_1_GERMAN , 
			self::ISO_639_1_ITALIAN , 
			self::ISO_639_1_SPANISH , 
			self::ISO_639_1_DUTCH
		);
	}

	public static function isSupportedLanguage($language) {
		$languages = self::getSupportedLanguageCodes();

		return in_array($language, $languages);
	}
}
