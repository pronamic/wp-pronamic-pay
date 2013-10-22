<?php

class Pronamic_Gateways_Icepay_Listener extends Pronamic_Gateways_ReturnHandler {
	public function listen() {
		$condition  = true;
		$condition &= filter_has_var( INPUT_GET, 'Status' );
		$condition &= filter_has_var( INPUT_GET, 'StatusCode' );
		$condition &= filter_has_var( INPUT_GET, 'Merchant' );
		$condition &= filter_has_var( INPUT_GET, 'OrderID' );
		$condition &= filter_has_var( INPUT_GET, 'PaymentID' );
		$condition &= filter_has_var( INPUT_GET, 'Reference' );
		$condition &= filter_has_var( INPUT_GET, 'TransactionID' );
		$condition &= filter_has_var( INPUT_GET, 'Checksum' );

		if ( $condition ) {
			$reference = filter_input( INPUT_GET, 'Reference', FILTER_SANITIZE_STRING );

			$payment = get_pronamic_payment( $reference );

			Pronamic_WordPress_IDeal_Plugin::update_payment( $payment );
		}
	}
}
