<?php
/**
 * Meta Box Payment Subscription
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

use Pronamic\WordPress\Pay\Util;

$post_id = get_the_ID();

if ( empty( $post_id ) ) {
	return;
}

$payment = get_pronamic_payment( $post_id );

$subscription = $payment->get_subscription();

if ( $subscription ) : ?>

	<table class="form-table">
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Subscription', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php edit_post_link( get_the_title( $subscription->post->ID ), '', '', $subscription->post->ID ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Description', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo esc_html( $subscription->get_description() ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Amount', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php

				echo esc_html( $subscription->get_amount()->format_i18n() );

				?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php echo esc_html_x( 'Interval', 'Recurring payment', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo esc_html( Util::format_interval( $subscription->get_interval(), $subscription->get_interval_period() ) ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php echo esc_html_x( 'Frequency', 'Recurring payment', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo esc_html( Util::format_frequency( $subscription->get_frequency() ) ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Source', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php

				$payment = $subscription->get_first_payment();

				if ( $payment ) {
					echo $payment->get_source_text(); // WPCS: XSS ok.
				} else {
					printf(
						'%s<br />%s', // WPCS: XSS ok.
						esc_html( $subscription->get_source() ),
						esc_html( $subscription->get_source_id() )
					);
				}

				?>
			</td>
		</tr>
	</table>

<?php else : ?>

	<p>
		<?php esc_html_e( 'This payment is not related to a subscription.', 'pronamic_ideal' ); ?>
	</p>

<?php endif; ?>
