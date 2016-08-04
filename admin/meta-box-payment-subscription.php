<?php

$post_id = get_the_ID();

$payment = get_pronamic_payment( $post_id );

$subscription = $payment->get_subscription();

if ( $subscription ) :

?>
	<table class="form-table">
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Subscription', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo get_the_title( $subscription->post->ID ); ?>
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
				<?php echo esc_html_e( 'Amount', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php

				echo esc_html( $subscription->get_currency() );
				echo ' ';
				echo esc_html( $subscription->get_amount() );

				?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php echo esc_html_x( 'Interval', 'Recurring payment', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php

				printf(
					'%s %s',
					esc_html( $subscription->get_interval() ),
					esc_html( $subscription->get_interval_period() )
				);

				?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php echo esc_html_x( 'Frequency', 'Recurring payment', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php

				if ( '' === $subscription->get_frequency() ) {
					echo esc_html_x( 'Unlimited', 'Recurring payment', 'pronamic_ideal' );
				} else {
					echo esc_html( $subscription->get_frequency() );
				}

				?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Transaction ID', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo esc_html( $subscription->get_transaction_id() ); ?>
			</td>
		</tr>
	</table>
<?php

else :

	esc_html_e( 'This payment is not related to a subscription.', 'pronamic_ideal' );

endif;

?>