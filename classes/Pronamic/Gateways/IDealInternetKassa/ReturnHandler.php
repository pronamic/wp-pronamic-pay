<?php

/**
 * Title: Gateway return handler
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
abstract class Pronamic_Gateways_IDealInternetKassa_ReturnHandler extends Pronamic_Gateways_ReturnHandler {
	public function listen() {
		if ( isset( $_GET['SHASIGN'] ) ) {
			$sha_signature = filter_input( INPUT_GET, 'SHASIGN', FILTER_SANITIZE_STRING );

			if ( ! empty( $sha_signature ) ) {
				do_action( 'pronamic_ideal_internetkassa_return_raw', $_GET );
			}
		}
	}

	public function returns( $data ) {
		$configurations = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurations();

		foreach ( $configurations as $configuration ) {
			$variant = $configuration->getVariant();
				
			if ( $variant != null && $variant->getMethod() == 'internetkassa' ) {
				$iDeal = new Pronamic_Gateways_IDealInternetKassa_IDealInternetKassa();

				$iDeal->setPspId($configuration->pspId);
				$iDeal->setPassPhraseIn($configuration->shaInPassPhrase);
				$iDeal->setPassPhraseOut($configuration->shaOutPassPhrase);

				$file = self::$dirname . '/other/calculations-parameters-sha-in.txt';
				$iDeal->setCalculationsParametersIn( file($file, FILE_IGNORE_NEW_LINES) );

				$file = self::$dirname . '/other/calculations-parameters-sha-out.txt';
				$iDeal->setCalculationsParametersOut( file($file, FILE_IGNORE_NEW_LINES) );

				$result = $iDeal->verifyRequest( $data );

				if ( $result !== false ) {
					do_action( 'pronamic_ideal_internetkassa_return', $result, $can_redirect = true );
						
					break;
				}
			}
		}
	}
}
