<?php

$subscription = get_pronamic_subscription( get_the_ID() );

$payments = $subscription->get_payments();

?>

<?php if ( empty( $payments ) ) : ?>

	<?php esc_html_e( 'No payments found.', 'pronamic_ideal' ); ?>

<?php else : ?>

	<?php foreach ( $payments as $payment ) : ?>

		<?php $payment_id = $payment->get_id(); ?>

			<table class="widefat fixed" cellspacing="0">
				<tr>
					<td>
						<?php esc_html_e( 'Date', 'pronamic_ideal' ); ?>
					</td>
					<td>
						<?php echo esc_html( get_the_time( __( 'l jS \o\f F Y, h:ia', 'pronamic_ideal' ), $payment_id ) ); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php esc_html_e( 'ID', 'pronamic_ideal' ); ?>
					</td>
					<td>
						<?php echo esc_html( $payment_id ); ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php esc_html_e( 'Amount', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php

						echo esc_html( get_post_meta( $payment_id, '_pronamic_payment_currency', true ) );
						echo ' ';
						echo esc_html( get_post_meta( $payment_id, '_pronamic_payment_amount', true ) );

						?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php esc_html_e( 'Transaction ID', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo esc_html( get_post_meta( $payment_id, '_pronamic_payment_transaction_id', true ) ); ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php esc_html_e( 'Status', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php

						$status_object = get_post_status_object( get_post_status( $payment_id ) );

						if ( isset( $status_object, $status_object->label ) ) {
							echo esc_html( $status_object->label );
						} else {
							echo '—';
						}

						?>
					</td>
				</tr>
			</table>

			<br>

	<?php endforeach; ?>

<?php endif; ?>
