<?php

/**
 * Title: Gateway return handler
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Buckaroo_ReturnHandler extends Pronamic_Gateways_ReturnHandler {
	public function listen() {
	  if ( isset( $_POST['brq_signature'] ) ) {
			$sha_signature = filter_input( INPUT_POST, 'brq_signature', FILTER_SANITIZE_STRING );
  		if ( ! empty( $sha_signature ) ) {
				do_action( 'pronamic_ideal_buckaroo_return_raw', $_POST );
			}
		}
	}

	public function returns( $data ) {
		$configurations = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurations();
    
		foreach ( $configurations as $configuration ) {
			$variant = $configuration->getVariant();
				
			if ( $variant != null && $variant->getMethod() == 'buckaroo' ) {
				$ideal = new Pronamic_Gateways_Buckaroo_Buckaroo();

				$ideal->setMerchantId( $configuration->getMerchantId() );
				$ideal->sethashKey( $configuration->gethashKey() );
//				$ideal->setPassPhraseOut( $configuration->shaOutPassPhrase );

//				$file = Pronamic_WordPress_IDeal_Plugin::$dirname . '/other/calculations-parameters-sha-in.txt';
//				$ideal->setCalculationsParametersIn( file( $file, FILE_IGNORE_NEW_LINES ) );

//				$file = Pronamic_WordPress_IDeal_Plugin::$dirname . '/other/calculations-parameters-sha-out.txt';
//				$ideal->setCalculationsParametersOut( file( $file, FILE_IGNORE_NEW_LINES ) );

     		$result = $ideal->verifyRequest( $data );

				if ( $result !== false ) {
		      do_action( 'pronamic_ideal_buckaroo_return', $result, $can_redirect = true );
					break;
				}
			}
		}
	}
}
