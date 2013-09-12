<?php

/**
 * Title: Gateway return handler
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealEasy_ReturnHandler extends Pronamic_Gateways_ReturnHandler {
	public function listen() {
		if ( isset( $_GET['gateway'] ) ) {
			$gateway = filter_input( INPUT_GET, 'gateway', FILTER_SANITIZE_STRING );

			if ( $gateway == 'ideal_easy' ) {
				if ( isset( $_GET['transaction_id'] ) && isset( $_GET['status'] ) ) {
					$transaction_id = filter_input( INPUT_GET, 'transaction_id', FILTER_SANITIZE_STRING );
					$status         = filter_input( INPUT_GET, 'status', FILTER_SANITIZE_STRING );

					do_action( 'pronamic_ideal_easy_return_raw', $transaction_id, $status );
				}
			}
		}
	}

	public function returns( $transaction_id, $status ) {
		$payment = get_pronamic_payment_by_transaction_id( $transaction_id );

		if ( $payment != null ) {
			$can_redirect = true;

			do_action( 'pronamic_ideal_easy_return', $payment, $status, $can_redirect );
		}
	}
}
