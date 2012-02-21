<?php

abstract class Pronamic_WordPress_IDeal_IDealDataProxy extends Pronamic_IDeal_IDealDataProxy {
	public function getLanguageIso639Code() {
		$language = get_option('WPLANG', WPLANG);
	
		return substr($language, 0, 2);
	}

	public function getLanguageIso639AndCountryIso3166Code() {
		return get_option('WPLANG', WPLANG);
	}
}
