<?php

/**
 * Title: Gateway return handler
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealInternetKassa_ReturnHandler extends Pronamic_Gateways_ReturnHandler {
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
				$ideal = new Pronamic_Gateways_IDealInternetKassa_IDealInternetKassa();

				$ideal->setPspId( $configuration->pspId );
				$ideal->setPassPhraseIn( $configuration->shaInPassPhrase );
				$ideal->setPassPhraseOut( $configuration->shaOutPassPhrase );

				$file = Pronamic_WordPress_IDeal_Plugin::$dirname . '/other/calculations-parameters-sha-in.txt';
				$ideal->setCalculationsParametersIn( file( $file, FILE_IGNORE_NEW_LINES ) );

				$file = Pronamic_WordPress_IDeal_Plugin::$dirname . '/other/calculations-parameters-sha-out.txt';
				$ideal->setCalculationsParametersOut( file( $file, FILE_IGNORE_NEW_LINES ) );

				$result = $ideal->verifyRequest( $data );

				if ( $result !== false ) {
					do_action( 'pronamic_ideal_internetkassa_return', $result, $can_redirect = true );
						
					break;
				}
			}
		}
	}
}
