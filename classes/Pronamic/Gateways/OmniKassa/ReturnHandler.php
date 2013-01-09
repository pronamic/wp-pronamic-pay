<?php

/**
 * Title: Gateway return handler
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
abstract class Pronamic_Gateways_OmniKassa_ReturnHandler extends Pronamic_Gateways_ReturnHandler {
	public function listen() {
		if ( isset( $_POST['Data'], $_POST['Seal'] ) ) {
			$data = filter_input( INPUT_POST, 'Data', FILTER_SANITIZE_STRING );
			$seal = filter_input( INPUT_POST, 'Seal', FILTER_SANITIZE_STRING );
				
			if ( ! empty( $data ) && ! empty( $seal ) ) {
				do_action( 'pronamic_ideal_omnikassa_return_raw', $data, $seal );
			}
		}
	}

	public function returns( $input_data, $input_seal ) {
		$data = Pronamic_Gateways_OmniKassa_OmniKassa::parsePipedString( $input_data );
		
		$transaction_reference = $data['transactionReference'];
		
		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentByIdAndEc( $transaction_reference );
		
		if ( $payment != null ) {
			$seal = Pronamic_Gateways_OmniKassa_OmniKassa::computeSeal( $input_data, $payment->configuration->getHashKey() );
		
			// Check if the posted seal is equal to our seal
			if ( strcasecmp( $input_seal, $seal ) === 0 ) {
				do_action( 'pronamic_ideal_omnikassa_return', $data, $can_redirect = true );
			}
		}
	}
}
