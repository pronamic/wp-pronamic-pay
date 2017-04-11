<?php

$post_id = get_the_ID();

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
				<?php esc_html_e( 'Source', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php

				$payment = $subscription->get_first_payment();

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
	</table>

<?php else : ?>

	<p>
		<?php esc_html_e( 'This payment is not related to a subscription.', 'pronamic_ideal' ); ?>
	</p>

<?php endif; ?>
