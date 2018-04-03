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
	 * Construct and initialize a payments module object.
	 *
	 * @param Plugin $plugin The plugin.
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		// Exclude payment notes.
		add_filter( 'comments_clauses', array( $this, 'exclude_payment_comment_notes' ), 10, 2 );

		// Listen to payment status changes so we can log these in a note.
		add_action( 'pronamic_payment_status_update', array( $this, 'log_payment_status_update' ), 10, 4 );
	}

	/**
	 * Comments clauses.
	 *
	 * @param array            $clauses Array with query clauses for the comments query.
	 * @param WP_Comment_Query $query   A WordPress comment query object.
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
	 * Payment status update.
	 *
	 * @param Payment $payment The status updated payment.
	 */
	public function log_payment_status_update( $payment, $can_redirect, $old_status, $new_status ) {
		$payment->add_note( sprintf(
			__( 'Payment status changed from "%1$s" to "%2$s".', 'pronamic_ideal' ),
			esc_html( $this->plugin->payments_data_store->get_meta_status_label( $old_status ) ),
			esc_html( $this->plugin->payments_data_store->get_meta_status_label( $new_status ) )
		) );
	}
}
