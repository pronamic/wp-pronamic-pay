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
	 * Check if Shopp is active (Automattic/developer style)
	 *
	 * @see https://bitbucket.org/Pronamic/shopp/src/12ebdb1d82a029bed956a58135833e3507baf432/Shopp.php?at=1.2.9#cl-29
	 * @see https://github.com/Automattic/developer/blob/1.1.2/developer.php#L73
	 *
	 * @return boolean
	 */
	public static function is_active() {
		return defined( 'SHOPP_VERSION' );
	}

	//////////////////////////////////////////////////

	/**
	 * Version compare
	 *
	 * @param string $version
	 * @param string $operator
	 */
	public static function version_compare( $version, $operator ) {
		$result = true;

		// @see https://github.com/ingenesis/shopp/blob/1.3/Shopp.php#L142
		if ( defined( 'SHOPP_VERSION' ) ) {
			$result = version_compare( SHOPP_VERSION, $version, $operator );
		}

		return $result;
	}

	//////////////////////////////////////////////////

	/**
	 * Check if the purchase is paid
	 *
	 * @param Purchase $purchase
	 */
	public static function is_purchase_paid( $purchase ) {
		$is_paid = false;

		if ( version_compare( SHOPP_VERSION, '1.2', '<' ) ) {
			// In Shopp < 1.2 an paid purchase has not the status 'PENDING'
			$is_paid = ! in_array(
				$purchase->txnstatus,
				array(
					self::PAYMENT_STATUS_PENDING
				)
			);
		} else {
			// In Shopp >= 1.2 an paid purchase has the 'captured' status
			$is_paid = in_array(
				$purchase->txnstatus,
				array(
					self::PAYMENT_STATUS_CAPTURED
				)
			);
		}

		return $is_paid;
	}

	//////////////////////////////////////////////////

	/**
	 * Update purchase status
	 *
	 * @param Purchase $purchase
	 * @param string $status
	 */
	public static function update_purchase_status( $purchase, $status ) {
		global $wpdb;

		$wpdb->update(
			$wpdb->prefix . SHOPP_DBPREFIX . 'purchase',
			array( 'txnstatus' => $status ),
			array( 'id' => $purchase->id )
		);
	}

	//////////////////////////////////////////////////

	/**
	 * Resession
	 */
	public static function resession() {
		global $Shopp;

		if ( method_exists( 'Shopping', 'resession' ) ) {
			// Shopp >= 1.2
			// @see https://github.com/ingenesis/shopp/blob/1.2/Shopp.php#L362-L368
			// @see https://github.com/ingenesis/shopp/blob/1.2/core/model/Shopping.php#L94-L135
			Shopping::resession();
		} elseif ( method_exists( $Shopp, 'resession' ) ) {
			// Shopp <= 1.1.9.1
			// @see https://github.com/ingenesis/shopp/blob/1.1.9.1/Shopp.php#L385-L423
			// @see https://github.com/ingenesis/shopp/blob/1.1/Shopp.php#L382-L413
			$Shopp->resession();
		}
	}
}
