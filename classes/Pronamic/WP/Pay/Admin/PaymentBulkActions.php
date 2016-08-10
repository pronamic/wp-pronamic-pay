<?php

/**
 * Title: WordPress admin payment bulk actions
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
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
		if ( ! current_user_can( 'edit_payment' ) ) {
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
		$status_updated = 0;

		$post_ids = filter_input( INPUT_GET, 'post', FILTER_VALIDATE_INT, FILTER_FORCE_ARRAY );

		foreach ( $post_ids as $post_id ) {
			$payment = get_pronamic_payment( $post_id );

			Pronamic_WP_Pay_Plugin::update_payment( $payment, false );

			$status_updated++;
		}

		$sendback = add_query_arg( array(
			'status_updated' => $status_updated,
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

			$message = sprintf( _n( 'Payment updated.', '%s payments updated.', $updated, 'pronamic_ideal' ), number_format_i18n( $updated ) );

			printf(
				'<div class="updated"><p>%s</p></div>',
				esc_html( $message )
			);
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
