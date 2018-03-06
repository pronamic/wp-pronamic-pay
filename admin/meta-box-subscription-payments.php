<?php
/**
 * Meta Box Subscription Payments
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

$post_id = get_the_ID();

if ( empty( $post_id ) ) {
	return;
}

$post_type = get_post_type( $post_id );

$subscription = get_pronamic_subscription( $post_id );

$payments = $subscription->get_payments();

?>

<?php if ( empty( $payments ) ) : ?>

	<?php esc_html_e( 'No payments found.', 'pronamic_ideal' ); ?>

<?php else : ?>

	<table class="pronamic-pay-table widefat">
		<thead>
			<tr>
				<th scope="col">
					<span class="pronamic-pay-tip pronamic-pay-icon pronamic-pay-status" data-tip="<?php esc_attr_e( 'Status', 'pronamic_ideal' ); ?>"><?php esc_html_e( 'Status', 'pronamic_ideal' ); ?></span>
				</th>
				<th scope="col"><?php esc_html_e( 'Payment', 'pronamic_ideal' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Transaction', 'pronamic_ideal' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Amount', 'pronamic_ideal' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Date', 'pronamic_ideal' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Start Date', 'pronamic_ideal' ); ?></th>
				<th scope="col"><?php esc_html_e( 'End Date', 'pronamic_ideal' ); ?></th>
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
					<td>
						<?php

						if ( empty( $payment->start_date ) ) {
							echo '—';
						} else {
							$date = get_date_from_gmt( $payment->start_date->format( 'Y-m-d H:i:s' ) );

							echo esc_html( date_i18n( __( 'l jS \o\f F Y, h:ia', 'pronamic_ideal' ), strtotime( $date ) ) );
						}

						?>
					</td>
					<td>
						<?php

						if ( empty( $payment->end_date ) ) {
							echo '—';
						} else {
							$date = get_date_from_gmt( $payment->end_date->format( 'Y-m-d H:i:s' ) );

							echo esc_html( date_i18n( __( 'l jS \o\f F Y, h:ia', 'pronamic_ideal' ), strtotime( $date ) ) );
						}

						?>
					</td>
				</tr>

			<?php endforeach; ?>

		</tbody>
	</table>

<?php endif; ?>
