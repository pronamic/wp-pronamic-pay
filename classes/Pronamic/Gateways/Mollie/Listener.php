<?php

/**
 * Title: Mollie listener
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Mollie_Listener implements Pronamic_Pay_Gateways_ListenerInterface {
	public static function listen() {
		$condition  = true;
		$condition &= filter_has_var( INPUT_GET, 'mollie_webhook' );
		$condition &= filter_has_var( INPUT_GET, 'type' );
		$condition &= filter_has_var( INPUT_GET, 'id' );

		if ( $condition ) {
			$type = filter_input( INPUT_GET, 'type', FILTER_SANITIZE_STRING );
			$id   = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_STRING );

			if ( 'payment' == $type ) {
				$payment = get_pronamic_payment_by_transaction_id( $id );

				Pronamic_WP_Pay_Plugin::update_payment( $payment );
			}
		}
	}
}
