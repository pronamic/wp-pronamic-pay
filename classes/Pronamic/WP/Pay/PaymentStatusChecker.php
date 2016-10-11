<?php

/**
 * Title: WordPress payment status checker
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WP_Pay_PaymentStatusChecker {
	/**
	 * Constructs and initializes a payment status checker.
	 */
	public function __construct() {
		// The 'pronamic_ideal_check_transaction_status' hook is scheduled the status requests
		add_action( 'pronamic_ideal_check_transaction_status', array( $this, 'check_status' ), 10, 3 );
	}

	/**
	 * Schedule event.
	 *
	 * @param $gateay
	 * @param $payment
	 */
	public function schedule_event( $payment ) {
		/*
		 * Schedule status requests
		 * http://pronamic.nl/wp-content/uploads/2011/12/iDEAL_Advanced_PHP_EN_V2.2.pdf (page 19)
		 *
		 * @todo
		 * Considering the number of status requests per transaction:
		 * - Maximum of five times per transaction;
		 * - Maximum of two times during the expirationPeriod;
		 * - After the expirationPeriod not more often than once per 60 minutes;
		 * - No status request after a final status has been received for a transaction;
		 * - No status request for transactions older than 7 days.
		 */

		/*
		 * The function `wp_schedule_single_event` uses the arguments array as an key for the event,
		 * that's why we also add the time to this array, besides that it's also much clearer on
		 * the Cron View (http://wordpress.org/extend/plugins/cron-view/) page
		 */

		$time = time();

		// Examples of possible times when a status request can be executed:

		// 30 seconds after a transaction request is sent
		wp_schedule_single_event( $time + 30, 'pronamic_ideal_check_transaction_status', array( 'payment_id' => $payment->get_id(), 'seconds' => 30 ) );
	}

	/**
	 * Get the delay seconds for the specified number of tries.
	 *
	 * @param int $number_tries
	 * @return int
	 */
	private function get_delay_seconds( $number_tries ) {
		switch ( $number_tries ) {
			case 0 :
				// 30 seconds after a transaction request is sent
				return 30;
			case 1 :
				// Half-way through an expirationPeriod
				return 30 * MINUTE_IN_SECONDS;
			case 2 :
				// Just after an expirationPeriod
				return HOUR_IN_SECONDS;
			case 3 :
			default :
				return DAY_IN_SECONDS;
		}
	}

	/**
	 * Check status of the specified payment
	 *
	 * @param string $paymentId
	 */
	public function check_status( $payment_id = null, $seconds = null, $number_tries = 1 ) {
		$payment = new Pronamic_WP_Pay_Payment( $payment_id );

		// Empty payment
		if ( null === $payment ) {
			// Payment with the specified ID could not be found, can't check the status
			return;
		}

		// Limit number tries
		if ( $number_tries >= 4 ) {
			return;
		}

		// http://pronamic.nl/wp-content/uploads/2011/12/iDEAL_Advanced_PHP_EN_V2.2.pdf (page 19)
		// - No status request after a final status has been received for a transaction;
		if ( empty( $payment->status ) || Pronamic_WP_Pay_Gateways_IDealAdvancedV3_Status::OPEN === $payment->status ) {
			Pronamic_WP_Pay_Plugin::update_payment( $payment );

			if ( empty( $payment->status ) || Pronamic_WP_Pay_Gateways_IDealAdvancedV3_Status::OPEN === $payment->status ) {
				$time = time();

				$seconds = $this->get_delay_seconds( $number_tries );

				wp_schedule_single_event( $time + $seconds, 'pronamic_ideal_check_transaction_status', array(
					'payment_id'   => $payment->get_id(),
					'seconds'      => $seconds,
					'number_tries' => $number_tries + 1,
				) );
			}
		}
	}
}
