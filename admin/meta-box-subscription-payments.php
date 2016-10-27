<?php

$post_id = get_the_ID();

$post_type = get_post_type( $post_id );

$subscription = get_pronamic_subscription( $post_id );

$payments = $subscription->get_payments();

?>

<?php if ( empty( $payments ) ) : ?>

	<?php esc_html_e( 'No payments found.', 'pronamic_ideal' ); ?>

<?php else : ?>

	<table class="pronamic-pay-subscriptions-table">
		<thead>
			<tr>
				<th scope="col">
					<span class="pronamic-pay-tip pronamic-pay-status pronamic-pay-status" data-tip="<?php esc_attr_e( 'Status', 'pronamic_ideal' ); ?>"><?php esc_html_e( 'Status', 'pronamic_ideal' ); ?></span>
				</th>
				<th scope="col"><?php esc_html_e( 'Payment', 'pronamic_ideal' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Transaction', 'pronamic_ideal' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Amount', 'pronamic_ideal' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Date', 'pronamic_ideal' ); ?></th>
			</tr>
		</thead>

		<tbody>

			<?php foreach ( $payments as $payment ) : ?>

				<?php

				$payment_id = $payment->get_id();
				$post_type  = get_post_type( $payment_id );

				?>

				<tr>
					<td>
						<?php do_action( 'manage_' . $post_type . '_posts_custom_column', 'pronamic_payment_status', $payment_id ); ?>
					</td>
					<td>
						<?php do_action( 'manage_' . $post_type . '_posts_custom_column', 'pronamic_payment_title', $payment_id ); ?>
					</td>
					<td>
						<?php do_action( 'manage_' . $post_type . '_posts_custom_column', 'pronamic_payment_transaction', $payment_id ); ?>
					</td>
					<td>
						<?php do_action( 'manage_' . $post_type . '_posts_custom_column', 'pronamic_payment_amount', $payment_id ); ?>
					</td>
					<td>
						<?php do_action( 'manage_' . $post_type . '_posts_custom_column', 'pronamic_payment_date', $payment_id ); ?>
					</td>
				</tr>

			<?php endforeach; ?>

		</tbody>
	</table>

<?php endif; ?>
