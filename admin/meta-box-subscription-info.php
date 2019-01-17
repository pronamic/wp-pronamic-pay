<?php
/**
 * Meta Box Subscription Info
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

use Pronamic\WordPress\Pay\Core\PaymentMethods;
use Pronamic\WordPress\Pay\Core\Statuses;
use Pronamic\WordPress\Pay\Util;

$post_id = get_the_ID();

if ( empty( $post_id ) ) {
	return;
}

$subscription = get_pronamic_subscription( $post_id );

?>
<table class="form-table">
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Date', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php the_time( __( 'l jS \o\f F Y, h:ia', 'pronamic_ideal' ) ); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'ID', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php echo esc_html( $post_id ); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Status', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			$status_object = get_post_status_object( get_post_status( $post_id ) );

			if ( isset( $status_object, $status_object->label ) ) {
				echo esc_html( $status_object->label );
			} else {
				echo '—';
			}

			?>
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
			<?php esc_html_e( 'Gateway', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php edit_post_link( get_the_title( $subscription->config_id ), '', '', $subscription->config_id ); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Payment Method', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php echo esc_html( PaymentMethods::get_name( $subscription->payment_method ) ); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Amount', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			if ( current_user_can( 'edit_post', $post_id ) && apply_filters( 'pronamic_pay_subscription_amount_editable_' . $subscription->get_source(), false ) ) {
				echo esc_html( $subscription->get_total_amount()->get_currency()->get_symbol() );

				$amount = $subscription->get_total_amount()->format_i18n( '%2$s' );

				printf(
					'<input type="text" name="pronamic_subscription_amount" value="%s" size="12" />',
					esc_attr( $amount )
				);
			} else {
				echo esc_html( $subscription->get_total_amount()->format_i18n() );
			}

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
			<?php esc_html_e( 'Start Date', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			$start_date = $subscription->get_start_date();

			echo empty( $start_date ) ? '—' : esc_html( $start_date->format_i18n() );

			?>
		</td>
	</tr>

	<?php

	$frequency = $subscription->get_frequency();

	// Show end date if frequency is limited.
	if ( ! empty( $frequency ) ) :

	?>

		<tr>
			<th scope="row">
				<?php esc_html_e( 'End Date', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php

				$end_date = $subscription->get_end_date();

				echo empty( $end_date ) ? '—' : esc_html( $end_date->format_i18n() );

				?>
			</td>
		</tr>

	<?php endif; ?>

	<?php

	// Show next payment date if subscription is not cancelled or completed.
	if ( ! in_array( $subscription->get_status(), array( Statuses::CANCELLED, Statuses::COMPLETED ), true ) ) :

	?>

		<tr>
			<th scope="row">
				<?php esc_html_e( 'Next Payment Date', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php

				$next_payment = $subscription->get_next_payment_date();

				echo empty( $next_payment ) ? '—' : esc_html( $next_payment->format_i18n() );

				?>
			</td>
		</tr>

	<?php endif; ?>

	<tr>
		<th scope="row">
			<?php esc_html_e( 'Expiry Date', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			$expiry_date = $subscription->get_expiry_date();

			echo empty( $expiry_date ) ? '—' : esc_html( $expiry_date->format_i18n() );

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Consumer', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			echo esc_html( get_post_meta( $post_id, '_pronamic_subscription_consumer_name', true ) );
			echo '<br />';
			echo esc_html( get_post_meta( $post_id, '_pronamic_subscription_consumer_iban', true ) );
			echo '<br />';
			echo esc_html( get_post_meta( $post_id, '_pronamic_subscription_consumer_bic', true ) );

			?>
		</td>
	</tr>

	<?php if ( ! empty( $subscription->user_id ) ) : ?>

		<tr>
			<th scope="row">
				<?php esc_html_e( 'User', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php

				$user_id = $subscription->user_id;

				printf(
					'<a href="%s">%s</a>',
					esc_url( get_edit_user_link( $user_id ) ),
					esc_html( $user_id )
				);

				?>
			</td>
		</tr>

	<?php endif; ?>

	<tr>
		<th scope="row">
			<?php esc_html_e( 'Source', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			echo $subscription->get_source_text(); // WPCS: XSS ok.

			?>
		</td>
	</tr>

	<?php if ( 's2member' === $subscription->get_source() ) : ?>

		<tr>
			<th scope="row">
				<?php esc_html_e( 'Period', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo esc_html( get_post_meta( $subscription->get_id(), '_pronamic_subscription_s2member_period', true ) ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Level', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo esc_html( get_post_meta( $subscription->get_id(), '_pronamic_subscription_s2member_level', true ) ); ?>
			</td>
		</tr>

	<?php endif; ?>
</table>
