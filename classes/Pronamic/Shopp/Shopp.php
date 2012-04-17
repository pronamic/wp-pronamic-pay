<?php 

/**
 * Title: Shopp
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Shopp_Shopp {
	/**
	 * Payment status pending
	 * 
	 * @var string
	 */
	const PAYMENT_STATUS_PENDING = 'PENDING';

	/**
	 * Payment status expired
	 * 
	 * @var string
	 */
	const PAYMENT_STATUS_EXPIRED = 'EXPIRED';

	/**
	 * Payment status cancelled
	 * 
	 * @var string
	 */
	const PAYMENT_STATUS_CANCELLED = 'CANCELLED';

	/**
	 * Payment status failure
	 * 
	 * @var string
	 */
	const PAYMENT_STATUS_FAILURE = 'FAILURE';

	/**
	 * Payment status charged
	 * 
	 * @var string
	 */
	const PAYMENT_STATUS_CHARGED = 'CHARGED';

	/**
	 * Payment status charged
	 * 
	 * @var string
	 */
	const PAYMENT_STATUS_OPEN = 'OPEN';
	
	//////////////////////////////////////////////////

	/**
	 * Payment status authed
	 * 
	 * @since Shopp v1.2
	 * @var string
	 */
	const PAYMENT_STATUS_AUTHED = 'authed';

	/**
	 * Payment status captured
	 *
	 * @since Shopp v1.2
	 * @var string
	 */
	const PAYMENT_STATUS_CAPTURED = 'captured';
	
	//////////////////////////////////////////////////

	/**
	 * Check if the purchase is paid
	 * 
	 * @param unknown_type $purchase
	 */
	public static function isPurchasePaid($purchase) {
		$isPaid = false;

		if(version_compare(SHOPP_VERSION, '1.2', '<')) {
			// In Shopp < 1.2 an paid purchase has not the status 'PENDING'
			$isPaid = !in_array(
				$purchase->txnstatus , 
				array(
					self::PAYMENT_STATUS_PENDING
				)
			);
		} else {
			// In Shopp >= 1.2 an paid purchase has not the 'captured' status
			$isPaid = !in_array(
				$purchase->txnstatus , 
				array(
					self::PAYMENT_STATUS_CAPTURED
				)
			);
		}
		
		return $isPaid;
	}
}
