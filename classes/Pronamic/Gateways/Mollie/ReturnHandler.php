<?php

/**
 * Title: Gateway return handler
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Mollie_ReturnHandler extends Pronamic_Gateways_ReturnHandler {
	public function listen() {
		if ( isset( $_GET['gateway'], $_GET['transaction_id'] ) ) {
			$gateway = filter_input( INPUT_GET, 'gateway', FILTER_SANITIZE_STRING );

			if ( $gateway == 'mollie' ) {
				$transaction_id = filter_input( INPUT_GET, 'transaction_id', FILTER_SANITIZE_STRING );

				if ( ! empty( $transaction_id ) ) {
					do_action( 'pronamic_ideal_mollie_return_raw', $transaction_id );
				}
			}
		}
	}

	public function returns( $transaction_id ) {
		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentByIdAndEc( $transaction_id );

		if ( $payment != null ) {
			$can_redirect = true;

			do_action( 'pronamic_ideal_mollie_return', $payment, $can_redirect );
		}
	}
}
