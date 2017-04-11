<?php

/**
 * Title: WordPress admin payment bulk actions
 * Description:
 * Copyright: Copyright (c) 2005 - 2017
 * Company: Pronamic
 *
 * @see https://www.skyverge.com/blog/add-custom-bulk-action/
 * @author Remco Tolsma
 * @version 4.1.0
 * @since 4.1.0
 */
class Pronamic_WP_Pay_Admin_PaymentBulkActions {
	/**
	 * Constructs and initializes an admin payment bulk actions object.
	 */
	public function __construct() {
		add_action( 'load-edit.php', array( $this, 'load' ) );
	}

	/**
	 * Admin init
	 */
	public function load() {
		// Current user
		if ( ! current_user_can( 'edit_payments' ) ) {
			return;
		}

		// Screen
		$screen = get_current_screen();

		if ( ! ( 'edit' === $screen->base && 'pronamic_payment' === $screen->post_type ) ) {
			return;
		}

		// Bulk actions
		$this->maybe_do_bulk_actions();

		// Admin notices
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

		// Admin footer
		add_action( 'admin_footer', array( $this, 'admin_footer' ) );
	}

	/**
	 * Maybe do bulk action.
	 *
	 * @see https://www.skyverge.com/blog/add-custom-bulk-action/
	 * @see https://github.com/WordPress/WordPress/blob/4.4.2/wp-admin/edit.php#L66-L175
	 */
	private function maybe_do_bulk_actions() {
		// Action
		$list_table = _get_list_table( 'WP_Posts_List_Table' );

		$action = $list_table->current_action();

		if ( 'pronamic_payment_check_status' !== $action ) {
			return;
		}

		// Referer
		check_admin_referer( 'bulk-posts' );

		// Sendback
		$sendback = wp_get_referer();

		if ( false === $sendback ) {
			$sendback = add_query_arg( 'post_type', 'pronamic_payment', admin_url( 'edit.php' ) );
		}

		// Post IDs
		$post_ids = filter_input( INPUT_GET, 'post', FILTER_VALIDATE_INT, FILTER_FORCE_ARRAY );

		$status_updated       = 0;
		$skipped_check        = 0;
		$unsupported_gateways = array();
		$gateways             = array();

		foreach ( $post_ids as $post_id ) {
			$payment = get_pronamic_payment( $post_id );

			// Only check status for pending payments.
			if ( Pronamic_WP_Pay_Statuses::OPEN !== $payment->status && '' !== $payment->status ) {
				$skipped_check++;

				continue;
			}

			// Make sure gateway supports `payment_status_request` feature.
			$config_id = $payment->config_id;

			if ( ! isset( $gateways[ $config_id ] ) ) {
				$gateways[ $config_id ] = Pronamic_WP_Pay_Plugin::get_gateway( $config_id );

				if ( $gateways[ $config_id ] && ! $gateways[ $config_id ]->supports( 'payment_status_request' ) ) {
					$unsupported_gateways[] = $config_id;
				}
			}

			if ( in_array( $config_id, $unsupported_gateways, true ) ) {
				continue;
			}

			Pronamic_WP_Pay_Plugin::update_payment( $payment, false );

			$status_updated++;
		}

		$sendback = add_query_arg( array(
			'status_updated'       => $status_updated,
			'skipped_check'        => $skipped_check,
			'unsupported_gateways' => implode( ',', $unsupported_gateways ),
		), $sendback );

		// Redirect
		wp_redirect( $sendback );

		exit;
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

	/**
	 * Admin footer.
	 *
	 * @see https://www.skyverge.com/blog/add-custom-bulk-action/
	 * @see https://github.com/WordPress/WordPress/blob/4.4.2/wp-admin/admin-footer.php#L59-L95
	 */
	public function admin_footer() {
		$value = 'pronamic_payment_check_status';
		$label = __( 'Check Payment Status', 'pronamic_ideal' );

		?>
		<script type="text/javascript">
			jQuery( document ).ready( function() {
				jQuery( '<option>' ).val( '<?php echo esc_js( $value ); ?>' ).text( '<?php echo esc_js( $label ); ?>' ).appendTo( 'select[name="action"]' );
				jQuery( '<option>' ).val( '<?php echo esc_js( $value ); ?>' ).text( '<?php echo esc_js( $label ); ?>' ).appendTo( 'select[name="action2"]' );
			} );
		</script>
		<?php
	}
}
