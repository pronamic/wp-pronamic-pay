<?php

class Pronamic_Gateways_Icepay_ReturnHandler extends Pronamic_Gateways_ReturnHandler {
	public function listen() {
		if ( isset( $_GET['Checksum'], $_GET['TransactionID'] ) ) {
			$transaction_id = filter_input( INPUT_GET, 'TransactionID', FILTER_SANITIZE_STRING );
			
			if ( ! empty( $transaction_id ) )
				do_action( 'pronamic_ideal_icepay_return_raw' );
		}
	}
	
	public function returns( $transaction_id ) {
		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentByIdAndEc($transaction_id );
		
		if ( null != $payment ) 
			do_action( 'pronamic_ideal_icepay_return', $payment, $can_redirect = true );
	}
}