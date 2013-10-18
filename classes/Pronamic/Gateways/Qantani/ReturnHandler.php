<?php

/**
 * Title: Gateway return handler
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Qantani_ReturnHandler extends Pronamic_Gateways_ReturnHandler {
	public function listen() {
		if ( isset( $_GET['gateway'] ) ) {
			$gateway = filter_input( INPUT_GET, 'gateway', FILTER_SANITIZE_STRING );

			if ( $gateway == 'qantani' ) {
				$transaction_id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_STRING );
				$status         = filter_input( INPUT_GET, 'status', FILTER_SANITIZE_STRING );
				$salt           = filter_input( INPUT_GET, 'salt', FILTER_SANITIZE_STRING );
				$checksum       = filter_input( INPUT_GET, 'checksum', FILTER_SANITIZE_STRING );

				do_action( 'pronamic_ideal_qantani_return_raw', $transaction_id, $status, $salt, $checksum );
			}
		}
	}

	public function returns( $transaction_id, $status, $salt, $checksum ) {
		$payment = get_pronamic_payment_by_transaction_id( $transaction_id );

		Pronamic_WordPress_IDeal_Plugin::update_payment( $payment );
	}
}
