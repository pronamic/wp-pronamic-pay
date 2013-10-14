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
		if ( isset( $_GET['payment'] ) ) {
			$condition  = filter_has_var( INPUT_GET, 'payment' );
			$condition &= filter_has_var( INPUT_GET, 'status' );
			$condition &= filter_has_var( INPUT_GET, 'trxid' );
			$condition &= filter_has_var( INPUT_GET, 'ec' );
			$condition &= filter_has_var( INPUT_GET, 'sha1' );
			
			if ( $condition ) {
				$payment_id     = filter_input( INPUT_GET, 'payment', FILTER_SANITIZE_NUMBER_INT );
				$status         = filter_input( INPUT_GET, 'status', FILTER_SANITIZE_STRING );
				$transaction_id = filter_input( INPUT_GET, 'trxid', FILTER_SANITIZE_STRING );
				$entrance_code  = filter_input( INPUT_GET, 'ec', FILTER_SANITIZE_STRING );
				$sha1           = filter_input( INPUT_GET, 'sha1', FILTER_SANITIZE_STRING );

				if ( ! empty( $transaction_id ) ) {
					$payment = new Pronamic_WP_Pay_Payment( $payment_id );

					do_action( 'pronamic_ideal_sisow_return_raw', $payment, $status, $transaction_id, $entrance_code, $sha1 );
				}
			}
		}
	}

	public function returns( $payment, $status, $transaction_id, $entrance_code, $sha1 ) {
		if ( $payment != null ) {
			$can_redirect = true;

			do_action( 'pronamic_ideal_sisow_return', $payment, $can_redirect );
		}
	}
}
