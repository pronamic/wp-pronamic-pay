<?php

/**
 * Title: iDEAL Basic listener
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealBasic_Listener implements Pronamic_Pay_Gateways_ListenerInterface {
	public static function listen() {
		$condition  = true;
		$condition &= filter_has_var( INPUT_GET, 'xml_notifaction' );

		if ( $condition ) {
			$http_raw_post_data = file_get_contents( 'php://input' );
						
			$xml = Pronamic_WP_Util::simplexml_load_string( $result );
						
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
