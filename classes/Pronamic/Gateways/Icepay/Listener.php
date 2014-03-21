<?php

/**
 * Title: ICEPAY listener
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Icepay_Listener implements Pronamic_Pay_Gateways_ListenerInterface {
	public static function listen() {
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
			$reference = filter_input( INPUT_GET, 'OrderID', FILTER_SANITIZE_STRING );

			$payment = get_pronamic_payment( $reference );

			Pronamic_WordPress_IDeal_Plugin::update_payment( $payment );
		}
	}
}
