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
		if ( isset( $_GET['gateway'] ) ) {
			$gateway = filter_input( INPUT_GET, 'gateway', FILTER_SANITIZE_STRING );
		
			if ( $gateway == 'buckaroo' ) {
				do_action( 'pronamic_ideal_buckaroo_return_raw', $_POST );
			}
		}
	}

	public function returns( $data ) {
		$configurations = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurations();
    
		foreach ( $configurations as $configuration ) {
			$variant = $configuration->getVariant();
				
			if ( $variant != null && $variant->getMethod() == 'buckaroo' ) {
				$buckaroo = new Pronamic_Gateways_Buckaroo_Buckaroo();

				$buckaroo->set_website_key( $configuration->buckarooWebsiteKey );
				$buckaroo->set_secret_key( $configuration->buckarooSecretKey );

				$result = $buckaroo->verify_request( $data );

				if ( $result !== false ) {
					do_action( 'pronamic_ideal_buckaroo_return', $result, $can_redirect = true );

					break;
				}
			}
		}
	}
}
