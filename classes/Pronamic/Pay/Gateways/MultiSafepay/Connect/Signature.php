<?php

/**
 * Title: MutliSafepay Connect signature
 * Description:
 * Copyright: Copyright (c) 2005 - 2013
 * Company: Pronamic
 * @author Remco Tolsma
 * @since 1.4.0
 */
class Pronamic_Pay_Gateways_MultiSafepay_Connect_Signature {
	public $account;

	public $site_id;

	public $site_secure_code;

	public $notification_url;

	public $redirect_url;

	public $cancel_url;

	public $close_window;

	/////////////////////////////////////////////////

	/**
	 * Constructs and initialize an MultiSafepay Connect merchant object
	 */
	public static function generate( $amount, $currency, $account, $site_id, $transaction_id ) {
		$values = array(
			Pronamic_WP_Util::amount_to_cents( $amount ),
			$currency,
			$account,
			$site_id,
			$transaction_id,
		);

		$string = implode( '', $values );

		$signature = md5( $string );

		return $signature;
	}
}
