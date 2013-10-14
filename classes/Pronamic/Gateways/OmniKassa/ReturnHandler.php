<?php

/**
 * Title: Gateway return handler
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_OmniKassa_ReturnHandler extends Pronamic_Gateways_ReturnHandler {
	public function listen() {
		$condition  = true;
		$condition &= filter_has_var( INPUT_POST, 'Data' );
		$condition &= filter_has_var( INPUT_POST, 'Seal' );
		
		if ( $condition ) {
			$data = filter_input( INPUT_POST, 'Data', FILTER_SANITIZE_STRING );
			$seal = filter_input( INPUT_POST, 'Seal', FILTER_SANITIZE_STRING );
			
			do_action( 'pronamic_ideal_omnikassa_return_raw', $data, $seal );
		}
	}

	public function returns( $input_data, $input_seal ) {
		$data = Pronamic_Gateways_OmniKassa_OmniKassa::parse_piped_string( $input_data );
		
		$transaction_reference = $data['transactionReference'];
		
		$payment = get_pronamic_payment_by_transaction_id( $transaction_reference );

		Pronamic_WordPress_IDeal_Plugin::update_payment( $payment );
	}
}
