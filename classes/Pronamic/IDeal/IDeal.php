<?php

/**
 * Title: iDEAL
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_IDeal_IDeal {
	/**
	 * The date format (yyyy-MMddTHH:mm:ss.SSSZ)
	 * The Z stands for the time zone (CET).
	 * 
	 * @var string 
	 */
	const DATE_FORMAT = 'Y-m-d\TH:i:s.000\Z';

	/**
	 * The timezone
	 * 
	 * @var string
	 */
	const TIMEZONE = 'UTC';

	//////////////////////////////////////////////////

	/**
	 * Indicator for SHA1 RSA authentication
	 * 
	 * @var string
	 */
	const AUTHENTICATION_SHA1_RSA = 'SHA1_RSA';

	//////////////////////////////////////////////////

	/**
	 * Indicator for test mode
	 * 
	 * @var int
	 */
	const MODE_TEST = 'test';

	/**
	 * Indicator for live mode
	 * 
	 * @var int
	 */
	const MODE_LIVE = 'live';

	//////////////////////////////////////////////////

	/**
	 * Easy
	 * 
	 * @var string
	 */
	const METHOD_EASY = 'easy';

	/**
	 * Basic
	 * 
	 * @var string
	 */
	const METHOD_BASIC = 'basic';

	/**
	 * Method kassa
	 * 
	 * @var string
	 */
	const METHOD_INTERNETKASSA = 'internetkassa';

	/**
	 * Method OmniKassa
	 * 
	 * @var string
	 */
	const METHOD_OMNIKASSA = 'omnikassa';

	/**
	 * Method advanced
	 * 
	 * @var string
	 */
	const METHOD_ADVANCED = 'advanced';

	//////////////////////////////////////////////////

	/**
	 * Format the price according to the documentation in whole cents
	 * 
	 * @param float $price
	 * @return int
	 */
	public static function formatPrice($price) {
		return round($price * 100);
	}

	//////////////////////////////////////////////////

	public static function htmlHiddenFields($data) {
		$html = '';

		foreach ( $data as $name => $value ) {
			$html .= sprintf( '<input type="hidden" name="%s" value="%s" />', esc_attr( $name ), esc_attr( $value ) );
		}

		return $html;
	} 

	//////////////////////////////////////////////////

	/**
	 * Get providers from the specified XML file
	 * 
	 * @param string $file
	 * @return array
	 */
	public static function get_providers_from_xml( $file ) {
		$providers = array();

		$xml = simplexml_load_file( $file );

		if ( $xml !== false ) {
			foreach ( $xml->provider as $provider_xml ) {
				$enabled = (string) $provider_xml['disabled'] != 'disabled';

				if ( $enabled ) {
					$provider = new Pronamic_IDeal_Provider();
					$provider->setId( (string) $provider_xml->id );
					$provider->setName( (string) $provider_xml->name );
					$provider->setUrl( (string) $provider_xml->url );
					
					foreach ( $provider_xml->variant as $variant_xml ) {
						$enabled = (string) $variant_xml['disabled'] != 'disabled';
	
						if ( $enabled ) {
							$gateway = (string) $variant_xml['gateway'];

							$variant = new Pronamic_IDeal_Variant();
							$variant->setMethod( $gateway );

							$variant->setProvider( $provider );
							$variant->setId( (string) $variant_xml->id );
							$variant->setName( (string) $variant_xml->name );

							$variant->liveSettings = new stdClass();
							$variant->liveSettings->dashboardUrl          = (string) $variant_xml->live->dashboardUrl;
							$variant->liveSettings->paymentServerUrl      = (string) $variant_xml->live->paymentServerUrl;
							$variant->liveSettings->directoryRequestUrl   = (string) $variant_xml->live->directoryRequestUrl;
							$variant->liveSettings->transactionRequestUrl = (string) $variant_xml->live->transactionRequestUrl;
							$variant->liveSettings->statusRequestUrl      = (string) $variant_xml->live->statusRequestUrl;

							$variant->testSettings = new stdClass();
							$variant->testSettings->dashboardUrl          = (string) $variant_xml->test->dashboardUrl;
							$variant->testSettings->paymentServerUrl      = (string) $variant_xml->test->paymentServerUrl;
							$variant->testSettings->directoryRequestUrl   = (string) $variant_xml->test->directoryRequestUrl;
							$variant->testSettings->transactionRequestUrl = (string) $variant_xml->test->transactionRequestUrl;
							$variant->testSettings->statusRequestUrl      = (string) $variant_xml->test->statusRequestUrl;

							$element = $variant_xml->xpath( 'certificates/file' );

							if ( $element !== false ) {
								foreach ( $element as $file_xml ) {
									$variant->certificates[] = (string) $file_xml;
								}
							}
							
							$provider->addVariant( $variant );
						}
					}
	
					$providers[$provider->getId()] = $provider;
				}
			}
		}
		
		return $providers;
	}
}
