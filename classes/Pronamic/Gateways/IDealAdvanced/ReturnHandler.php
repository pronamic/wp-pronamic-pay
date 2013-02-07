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
		if ( isset( $_GET['gateway'], $_GET['trxid'], $_GET['ec'] ) ) {
			$gateway = filter_input( INPUT_GET, 'gateway', FILTER_SANITIZE_STRING );

			if ( $gateway == 'ideal_advanced' ) {
				$transaction_id = filter_input( INPUT_GET, 'trxid', FILTER_SANITIZE_STRING );
				$entrance_code  = filter_input( INPUT_GET, 'ec', FILTER_SANITIZE_STRING );
		
				if ( ! empty( $transaction_id ) && ! empty( $entrance_code ) ) {
					do_action( 'pronamic_ideal_advanced_return_raw', $transaction_id, $entrance_code );
				}
			}
		}
	}

	public function returns( $transaction_id, $entrance_code ) {
		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentByIdAndEc( $transaction_id, $entrance_code );
		
		if ( $payment != null ) {
			$can_redirect = true;
		
			do_action( 'pronamic_ideal_advanced_return', $payment, $can_redirect );
		}
	}
}
