<?php

$post_id = get_the_ID();

$payment = get_pronamic_payment( $post_id );

?>
<table class="form-table">
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Recurring', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			if ( $payment->get_recurring() ) {
				esc_html_e( 'Yes', 'pronamic_ideal' );
			} else {
				esc_html_e( 'No', 'pronamic_ideal' );
			}

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Description', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php echo esc_html( $payment->get_recurring_description() ); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php echo esc_html_x( 'Amount', 'Recurring payment', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			echo esc_html( get_post_meta( $post_id, '_pronamic_payment_currency', true ) );
			echo ' ';
			echo esc_html( $payment->get_recurring_amount() );

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
				esc_html( $payment->get_recurring_interval() ),
				esc_html( $payment->get_recurring_interval_period() )
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

			if ( '' === $payment->get_recurring_frequency() ) {
				echo esc_html_x( 'Unlimited', 'Recurring payment', 'pronamic_ideal' );
			} else {
				echo esc_html( $payment->get_recurring_frequency() );
			}

			?>
		</td>
	</tr>
</table>
