<?php

/**
 * Title: Gateway return handler
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvanced_ReturnHandler extends Pronamic_Gateways_ReturnHandler {
	public function listen() {
		$condition  = filter_has_var( INPUT_GET, 'payment' ); 
		$condition &= filter_has_var( INPUT_GET, 'trxid' ); 
		$condition &= filter_has_var( INPUT_GET, 'ec' );

		if ( $condition ) {
			$payment_id     = filter_input( INPUT_GET, 'payment', FILTER_SANITIZE_NUMBER_INT );
			$transaction_id = filter_input( INPUT_GET, 'trxid', FILTER_SANITIZE_STRING );
			$entrance_code  = filter_input( INPUT_GET, 'ec', FILTER_SANITIZE_STRING );
		
			if ( ! empty( $transaction_id ) && ! empty( $entrance_code ) ) {
				do_action( 'pronamic_ideal_advanced_return_raw', $payment_id, $transaction_id, $entrance_code );
			}
		}
	}

	public function returns( $payment_id, $transaction_id, $entrance_code ) {
		$can_redirect = true;
		
		do_action( 'pronamic_ideal_advanced_return', $payment_id, $can_redirect );
	}
}
