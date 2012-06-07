<?php

/**
 * Title: WordPress iDEAL data proxy
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
abstract class Pronamic_WordPress_IDeal_IDealDataProxy extends Pronamic_IDeal_IDealDataProxy {
	/**
	 * Get the ISO 639 language code
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getLanguageIso639Code()
	 * @return string
	 */
	public function getLanguageIso639Code() {
		return Pronamic_WordPress_IDeal_Util::getLanguageIso639Code();
	}

	/**
	 * Get the language ISO 639 and ISO 3166 country code
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getLanguageIso639AndCountryIso3166Code()
	 * @return string
	 */
	public function getLanguageIso639AndCountryIso3166Code() {
		return Pronamic_WordPress_IDeal_Util::getLanguageIso639AndCountryIso3166Code();
	}
}
