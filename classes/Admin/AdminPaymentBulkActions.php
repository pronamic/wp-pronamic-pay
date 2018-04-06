<?php
/**
 * Payment Bulk Actions
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Admin
 */

namespace Pronamic\WordPress\Pay\Admin;

/**
 * WordPress admin payment bulk actions
 *
 * @see https://www.skyverge.com/blog/add-custom-bulk-action/
 * @author Remco Tolsma
 * @version 4.1.0
 * @since 4.1.0
 */
class AdminPaymentBulkActions {
	/**
	 * Constructs and initializes an admin payment bulk actions object.
	 */
	public function __construct() {
		add_action( 'load-edit.php', array( $this, 'load' ) );
	}

	/**
	 * Load.
	 */
	public function load() {
		// Current user.
		if ( ! current_user_can( 'edit_payments' ) ) {
			return;
		}

		// Screen.
		$screen = get_current_screen();

		if ( 'edit-pronamic_payment' !== $screen->id ) {
			return;
		}

		// Bulk actions.
		add_filter( 'bulk_actions-' . $screen->id, array( $this, 'bulk_actions' ) );

		add_filter( 'handle_bulk_actions-' . $screen->id, array( $this, 'handle_bulk_action' ), 10, 3 );

		// Admin notices.
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Custom bulk actions.
	 *
	 * @see https://make.wordpress.org/core/2016/10/04/custom-bulk-actions/
	 * @see https://github.com/WordPress/WordPress/blob/4.7/wp-admin/includes/class-wp-list-table.php#L440-L452
	 * @param array $bulk_actions Bulk actions.
	 * @return array
	 */
	public function bulk_actions( $bulk_actions ) {
		// Don't allow edit in bulk.
		unset( $bulk_actions['edit'] );

		// Bulk check payment status.
		$bulk_actions['pronamic_payment_check_status'] = __( 'Check Payment Status', 'pronamic_ideal' );

		return $bulk_actions;
	}

	/**
	 * Handle bulk action.
	 *
	 * @see hhttps://make.wordpress.org/core/2016/10/04/custom-bulk-actions/
	 * @see https://github.com/WordPress/WordPress/blob/4.7/wp-admin/edit.php#L166-L167
	 * @param string $sendback Sendback URL.
	 * @param string $doaction Action indicator.
	 * @param array  $post_ids  Post ID's to bulk edit.
	 * @return string
	 */
	public function handle_bulk_action( $sendback, $doaction, $post_ids ) {
		if ( 'pronamic_payment_check_status' !== $doaction ) {
			return $sendback;
		}

		$status_updated       = 0;
		$skipped_check        = 0;
		$unsupported_gateways = array();
		$gateways             = array();

		foreach ( $post_ids as $post_id ) {
			$payment = get_pronamic_payment( $post_id );

			// Only check status for pending payments.
			if ( \Pronamic\WordPress\Pay\Core\Statuses::OPEN !== $payment->status && '' !== $payment->status ) {
				$skipped_check++;

				continue;
			}

			// Make sure gateway supports `payment_status_request` feature.
			$config_id = $payment->config_id;

			if ( ! isset( $gateways[ $config_id ] ) ) {
				$gateways[ $config_id ] = \Pronamic\WordPress\Pay\Plugin::get_gateway( $config_id );

				if ( $gateways[ $config_id ] && ! $gateways[ $config_id ]->supports( 'payment_status_request' ) ) {
					$unsupported_gateways[] = $config_id;
				}
			}

			if ( in_array( $config_id, $unsupported_gateways, true ) ) {
				continue;
			}

			\Pronamic\WordPress\Pay\Plugin::update_payment( $payment, false );

			$status_updated++;
		}

		$sendback = add_query_arg(
			array(
				'status_updated'       => $status_updated,
				'skipped_check'        => $skipped_check,
				'unsupported_gateways' => implode( ',', $unsupported_gateways ),
			), $sendback
		);

		return $sendback;
	}

	/**
	 * Admin notices.
	 */
	public function admin_notices() {
		if ( filter_has_var( INPUT_GET, 'status_updated' ) ) {
			$updated = filter_input( INPUT_GET, 'status_updated', FILTER_VALIDATE_INT );

			if ( $updated > 0 ) {
				$message = sprintf( _n( '%s payment updated.', '%s payments updated.', $updated, 'pronamic_ideal' ), number_format_i18n( $updated ) );

				printf(
					'<div class="notice notice-success"><p>%s</p></div>',
					esc_html( $message )
				);
			}
		}

		if ( filter_has_var( INPUT_GET, 'skipped_check' ) ) {
			$updated = filter_input( INPUT_GET, 'skipped_check', FILTER_VALIDATE_INT );

			if ( $updated > 0 ) {
				$message = sprintf(
					_n( '%s payment is not updated because it already has a final payment status.', '%s payments are not updated because they already have a final payment status.', $updated, 'pronamic_ideal' ),
					number_format_i18n( $updated )
				);

				printf(
					'<div class="notice notice-warning"><p>%s</p></div>',
					esc_html( $message )
				);
			}
		}

		if ( filter_has_var( INPUT_GET, 'unsupported_gateways' ) ) {
			$unsupported = filter_input( INPUT_GET, 'unsupported_gateways', FILTER_SANITIZE_STRING );

			if ( '' !== $unsupported ) {
				$gateways = explode( ',', $unsupported );

				foreach ( $gateways as $index => $config_id ) {
					$gateways[ $index ] = get_the_title( $config_id );
				}

				$message = sprintf( __( 'Requesting the current payment status is unsupported by %s.', 'pronamic_ideal' ), implode( ', ', $gateways ) );

				printf(
					'<div class="notice notice-error"><p>%s</p></div>',
					esc_html( $message )
				);
			}
		}
	}
}
