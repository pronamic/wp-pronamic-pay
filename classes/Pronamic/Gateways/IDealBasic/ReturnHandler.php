<?php

/**
 * Title: Gateway return handler
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealBasic_ReturnHandler extends Pronamic_Gateways_ReturnHandler {
	public function listen() {
		if ( isset( $_GET['gateway'] ) ) {
			$gateway = filter_input( INPUT_GET, 'gateway', FILTER_SANITIZE_STRING );

			if ( $gateway == 'ideal_basic' ) {
				if ( isset( $_GET['transaction_id'] ) && isset( $_GET['status'] ) ) {
					$transaction_id = filter_input( INPUT_GET, 'transaction_id', FILTER_SANITIZE_STRING );
					$status         = filter_input( INPUT_GET, 'status', FILTER_SANITIZE_STRING );

					do_action( 'pronamic_ideal_basic_return_raw', $transaction_id, $status );
				}

				if ( filter_has_var( INPUT_GET, 'xml_notifaction' ) ) {
					$http_raw_post_data = file_get_contents( 'php://input' );
						
					$xml = Pronamic_WordPress_Util::simplexml_load_string( $result );
						
					if ( is_wp_error( $xml ) ) {
						// @todo what todo?
					} else {
						$notification = Pronamic_Gateways_IDealBasic_XML_NotificationParser::parse( $xml );
						
						$transaction_id = $notification->get_transaction_id();
						
						$payment = get_pronamic_payment_by_transaction_id( $transaction_id );
						
						if ( $payment ) {
							$payment->set_status( $notification->get_status() );
							
							Pronamic_WordPress_IDeal_Plugin::update_payment( $payment );
						}
					}
				}
			}
		}
	}

	public function returns( $transaction_id, $status ) {
		$payment = get_pronamic_payment_by_transaction_id( $transaction_id );

		if ( $payment != null ) {
			$can_redirect = true;

			do_action( 'pronamic_ideal_basic_return', $payment, $status, $can_redirect );
		}
	}
}
