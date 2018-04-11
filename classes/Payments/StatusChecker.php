<?php
/**
 * Status Checker
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Payments;

use Pronamic\WordPress\Pay\Core\Statuses;
use Pronamic\WordPress\Pay\Plugin;

/**
 * Title: WordPress payment status checker
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 4.5.3
 * @since 1.0.0
 */
class StatusChecker {
	/**
	 * Schedule event.
	 *
	 * @param Payment $payment The payment to schedule the status check event.
	 */
	public static function schedule_event( $payment ) {
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
		 * the Cron View (http://wordpress.org/extend/plugins/cron-view/) page.
		 */

		$time = time();

		// 15 minutes after a transaction request is sent
		$delay = 15 * MINUTE_IN_SECONDS;

		wp_schedule_single_event(
			$time + $delay, 'pronamic_ideal_check_transaction_status', array(
				'payment_id' => $payment->get_id(),
				'seconds'    => $delay,
			)
		);
	}

	/**
	 * Get the delay seconds for the specified number of tries.
	 *
	 * @param int $number_tries The number of tries to get the delay seconds for.
	 *
	 * @return int
	 */
	private function get_delay_seconds( $number_tries ) {
		switch ( $number_tries ) {
			case 0:
				// 15 minutes after a transaction request is sent.
				return 15 * MINUTE_IN_SECONDS;
			case 1:
				// Half-way through an expirationPeriod.
				return 30 * MINUTE_IN_SECONDS;
			case 2:
				// Just after an expirationPeriod.
				return HOUR_IN_SECONDS;
			case 3:
			default:
				return DAY_IN_SECONDS;
		}
	}

	/**
	 * Check status of the specified payment.
	 *
	 * @param integer $payment_id   The ID of a payment to check.
	 * @param integer $seconds      The number of seconds this status check was delayed.
	 * @param integer $number_tries The number of status check tries.
	 *
	 * @internal param string $paymentId
	 */
	public function check_status( $payment_id = null, $seconds = null, $number_tries = 1 ) {
		$payment = new Payment( $payment_id );

		// Empty payment.
		if ( null === $payment ) {
			// Payment with the specified ID could not be found, can't check the status.
			return;
		}

		// http://pronamic.nl/wp-content/uploads/2011/12/iDEAL_Advanced_PHP_EN_V2.2.pdf (page 19)
		// - No status request after a final status has been received for a transaction.
		if ( empty( $payment->status ) || Statuses::OPEN === $payment->status ) {
			// Add note.
			$note = sprintf(
				__( 'Payment status check at gateway by %s.', 'pronamic_ideal' ),
				__( 'Pronamic Pay', 'pronamic_ideal' )
			);

			$payment->add_note( $note );

			// Update payment.
			Plugin::update_payment( $payment );

			// Limit number tries.
			if ( 4 === $number_tries ) {
				return;
			}

			// Schedule check if no final status has been received.
			if ( empty( $payment->status ) || Statuses::OPEN === $payment->status ) {
				$time = time();

				$seconds = $this->get_delay_seconds( $number_tries );

				wp_schedule_single_event(
					$time + $seconds, 'pronamic_ideal_check_transaction_status', array(
						'payment_id'   => $payment->get_id(),
						'seconds'      => $seconds,
						'number_tries' => ++$number_tries,
					)
				);
			}
		}
	}
}
