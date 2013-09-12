<?php

/**
 * Title: Gateway return handler
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Sisow_ReturnHandler extends Pronamic_Gateways_ReturnHandler {
	public function listen() {
		if ( isset( $_GET['gateway'] ) ) {
			$gateway = filter_input( INPUT_GET, 'gateway', FILTER_SANITIZE_STRING );

			if ( $gateway == 'sisow' ) {
				$status         = filter_input( INPUT_GET, 'status', FILTER_SANITIZE_STRING );
				$transaction_id = filter_input( INPUT_GET, 'trxid', FILTER_SANITIZE_STRING );
				$entrance_code  = filter_input( INPUT_GET, 'ec', FILTER_SANITIZE_STRING );
				$sha1           = filter_input( INPUT_GET, 'sha1', FILTER_SANITIZE_STRING );

				if ( ! empty( $transaction_id ) ) {
					do_action( 'pronamic_ideal_sisow_return_raw', $status, $transaction_id, $entrance_code, $sha1 );
				}
			}
		}
	}

	public function returns( $status, $transaction_id, $entrance_code, $sha1 ) {
		$payment = get_pronamic_payment_by_transaction_id( $transaction_id );

		if ( $payment != null ) {
			$can_redirect = true;

			do_action( 'pronamic_ideal_sisow_return', $payment, $can_redirect );
		}
	}
}
