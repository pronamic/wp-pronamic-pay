<?php
/**
 * Payments Module
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Subscriptions
 */

namespace Pronamic\WordPress\Pay\Payments;

use Pronamic\WordPress\Pay\Plugin;
use Pronamic\WordPress\Pay\Core\Statuses;

/**
 * Title: Payments module
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @see https://woocommerce.com/2017/04/woocommerce-3-0-release/
 * @see https://woocommerce.wordpress.com/2016/10/27/the-new-crud-classes-in-woocommerce-2-7/
 * @author Remco Tolsma
 * @version 3.7.0
 * @since 3.7.0
 */
class PaymentsModule {
	/**
	 * Plugin.
	 *
	 * @var Plugin $plugin
	 */
	public $plugin;

	/**
	 * Free payments to complete at shutdown.
	 *
	 * @var array
	 */
	public $free = array();

	/**
	 * Construct and initialize a payments module object.
	 *
	 * @param Plugin $plugin The plugin.
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		// Exclude payment notes.
		add_filter( 'comments_clauses', array( $this, 'exclude_payment_comment_notes' ), 10, 2 );

		// Payment redirect URL.
		add_filter( 'pronamic_payment_redirect_url', array( $this, 'payment_redirect_url' ), 5, 2 );

		// Listen to payment status changes so we can log these in a note.
		add_action( 'pronamic_payment_status_update', array( $this, 'log_payment_status_update' ), 10, 4 );

		// Shutdown.
		add_action( 'shutdown', array( $this, 'update_free_payments' ) );

		// Payment Status Checker.
		$status_checker = new StatusChecker();

		// The 'pronamic_ideal_check_transaction_status' hook is scheduled to request the payment status.
		add_action( 'pronamic_ideal_check_transaction_status', array( $status_checker, 'check_status' ), 10, 3 );
	}

	/**
	 * Comments clauses.
	 *
	 * @param array             $clauses Array with query clauses for the comments query.
	 * @param \WP_Comment_Query $query   A WordPress comment query object.
	 *
	 * @return array
	 */
	public function exclude_payment_comment_notes( $clauses, $query ) {
		$type = $query->query_vars['type'];

		// Ignore payment notes comments if it's not specifically requested.
		if ( 'payment_note' !== $type ) {
			$clauses['where'] .= " AND comment_type != 'payment_note'";
		}

		return $clauses;
	}

	/**
	 * Payment redirect URL filter.
	 *
	 * @param string  $url     A payment redirect URL.
	 * @param Payment $payment The payment to get a redirect URL for.
	 *
	 * @return string
	 */
	public function payment_redirect_url( $url, $payment ) {
		$page_id = null;

		switch ( $payment->status ) {
			case Statuses::CANCELLED:
				$page_id = pronamic_pay_get_page_id( 'cancel' );

				break;
			case Statuses::EXPIRED:
				$page_id = pronamic_pay_get_page_id( 'expired' );

				break;
			case Statuses::FAILURE:
				$page_id = pronamic_pay_get_page_id( 'error' );

				break;
			case Statuses::OPEN:
				$page_id = pronamic_pay_get_page_id( 'unknown' );

				break;
			case Statuses::SUCCESS:
				$page_id = pronamic_pay_get_page_id( 'completed' );

				break;
			default:
				$page_id = pronamic_pay_get_page_id( 'unknown' );

				break;
		}

		if ( ! empty( $page_id ) ) {
			$page_url = get_permalink( $page_id );

			if ( false !== $page_url ) {
				$url = $page_url;
			}
		}

		return $url;
	}

	/**
	 * Payment status update.
	 *
	 * @param Payment $payment      The status updated payment.
	 * @param bool    $can_redirect Whether or not redirects should be performed.
	 * @param string  $old_status   Old meta status.
	 * @param string  $new_status   New meta status.
	 *
	 * @return void
	 */
	public function log_payment_status_update( $payment, $can_redirect, $old_status, $new_status ) {
		$note = sprintf(
			__( 'Payment status changed from "%1$s" to "%2$s".', 'pronamic_ideal' ),
			esc_html( $this->plugin->payments_data_store->get_meta_status_label( $old_status ) ),
			esc_html( $this->plugin->payments_data_store->get_meta_status_label( $new_status ) )
		);

		if ( null === $old_status ) {
			$note = sprintf(
				__( 'Payment created with status "%1$s".', 'pronamic_ideal' ),
				esc_html( $this->plugin->payments_data_store->get_meta_status_label( $new_status ) )
			);
		}

		$payment->add_note( $note );
	}

	/**
	 * Update free payments.
	 */
	public function update_free_payments() {
		$can_redirect = false;

		foreach ( $this->free as $payment_id ) {
			$payment = get_pronamic_payment( $payment_id );

			Plugin::update_payment( $payment, $can_redirect );
		}
	}
}
