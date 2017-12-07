<?php

$post_id = get_the_ID();

$subscription = get_pronamic_subscription( $post_id );

$payment = $subscription->get_first_payment();

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

			$currency = $subscription->get_currency();
			$amount   = $subscription->get_amount();

			echo esc_html( Pronamic_WP_Util::format_price( $amount, $currency ) );

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php echo esc_html_x( 'Interval', 'Recurring payment', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php echo esc_html( Pronamic_WP_Util::format_interval( $subscription->get_interval(), $subscription->get_interval_period() ) ); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php echo esc_html_x( 'Frequency', 'Recurring payment', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php echo esc_html( Pronamic_WP_Util::format_frequency( $subscription->get_frequency() ) ); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'First payment', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			$first_date = get_date_from_gmt( $subscription->get_first_payment_date()->format( 'Y-m-d H:i:s' ) );

			echo esc_html( date_i18n( __( 'l jS \o\f F Y, h:ia', 'pronamic_ideal' ), strtotime( $first_date ) ) );

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Next payment', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			$next_payment = $subscription->get_next_payment_date();

			if ( $next_payment ) {
				$next_date = get_date_from_gmt( $next_payment->format( 'Y-m-d H:i:s' ) );

				echo esc_html( date_i18n( __( 'l jS \o\f F Y, h:ia', 'pronamic_ideal' ), strtotime( $next_date ) ) );
			} else {
				echo esc_html( __( 'None', 'pronamic_ideal' ) );
			}

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Final payment', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			$final_payment = $subscription->get_final_payment_date();

			if ( $final_payment ) {
				$final_date = get_date_from_gmt( $final_payment->format( 'Y-m-d H:i:s' ) );

				echo esc_html( date_i18n( __( 'l jS \o\f F Y, h:ia', 'pronamic_ideal' ), strtotime( $final_date ) ) );
			}

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Current period', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			$start_date  = $subscription->get_start_date();
			$expiry_date = $subscription->get_expiry_date();

			if ( $start_date && $expiry_date ) {
				$start_date  = get_date_from_gmt( $start_date->format( 'Y-m-d H:i:s' ) );
				$expiry_date = get_date_from_gmt( $expiry_date->format( 'Y-m-d H:i:s' ) );

				echo esc_html( sprintf(
					'%s – %s',
					date_i18n( __( 'l jS \o\f F Y, h:ia', 'pronamic_ideal' ), strtotime( $start_date ) ),
					date_i18n( __( 'l jS \o\f F Y, h:ia', 'pronamic_ideal' ), strtotime( $expiry_date ) )
				) );
			}

			if ( Pronamic_WP_Pay_Statuses::COMPLETED === $subscription->get_status() ) {
				echo esc_html( sprintf( __( 'None (subscription completed)', 'pronamic_ideal' ) ) );
			}

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
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Source', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			if ( $payment ) {
				echo $payment->get_source_text(); //xss ok
			} else {
				printf(
					'%s<br />%s', //xss ok
					esc_html( $subscription->get_source() ),
					esc_html( $subscription->get_source_id() )
				);
			}

			?>
		</td>
	</tr>

	<?php if ( 's2member' === $payment->get_source() ) : ?>

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

	<?php if ( 'wp-e-commerce' === $payment->get_source() ) : ?>

		<tr>
			<th scope="row">
				<?php esc_html_e( 'Purchase ID', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo esc_html( get_post_meta( $payment->get_id(), '_pronamic_payment_wpsc_purchase_id', true ) ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Session ID', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo esc_html( get_post_meta( $payment->get_id(), '_pronamic_payment_wpsc_session_id', true ) ); ?>
			</td>
		</tr>

	<?php endif; ?>

	<?php if ( 'membership' === $payment->get_source() ) : ?>

		<tr>
			<th scope="row">
				<?php esc_html_e( 'User ID', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo esc_html( get_post_meta( $payment->get_id(), '_pronamic_payment_membership_user_id', true ) ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Subscription ID', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo esc_html( get_post_meta( $payment->get_id(), '_pronamic_payment_membership_subscription_id', true ) ); ?>
			</td>
		</tr>

	<?php endif; ?>
</table>
