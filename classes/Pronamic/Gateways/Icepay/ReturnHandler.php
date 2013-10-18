<?php

class Pronamic_Gateways_Icepay_ReturnHandler extends Pronamic_Gateways_ReturnHandler {
	public function listen() {
		if ( isset( $_GET['Checksum'], $_GET['OrderID'] ) ) {
			$order_id = filter_input( INPUT_GET, 'OrderID', FILTER_SANITIZE_STRING );
			
			if ( ! empty( $order_id ) )
				do_action( 'pronamic_ideal_icepay_return_raw', $order_id );
			
			get_posts();
		}
	}
	
	public function returns( $order_id ) {
		$payment = get_pronamic_payment_by_purchase_id( $order_id );
		
		if ( null != $payment ) 
			do_action( 'pronamic_ideal_icepay_return', $payment, $can_redirect = true );
	}
}