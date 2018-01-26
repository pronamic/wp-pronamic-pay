<?php

global $pronamic_ideal_errors;

use Pronamic\WordPress\Pay\Gateways\IDeal_Basic\Gateway as IDeal_Basic_Gateway;
use Pronamic\WordPress\Pay\Plugin;

$gateway = Plugin::get_gateway( get_the_ID() );

if ( $gateway ) {
	wp_nonce_field( 'test_pay_gateway', 'pronamic_pay_test_nonce' );

	$is_ideal  = false;
	$is_ideal |= $gateway instanceof IDeal_Basic_Gateway;
	$is_ideal |= $gateway instanceof Pronamic_WP_Pay_Gateways_IDealAdvanced_Gateway;
	$is_ideal |= $gateway instanceof Pronamic_WP_Pay_Gateways_IDealAdvancedV3_Gateway;

	// Payment method selector
	$payment_methods = $gateway->get_payment_method_field( true );

	$inputs = array();

	foreach ( $payment_methods['choices'][0]['options'] as $payment_method => $method_name ) {
		$gateway->set_payment_method( $payment_method );

		// Payment method input HTML
		$html = $gateway->get_input_html();

		if ( ! empty( $html ) ) {
			$inputs[ $payment_method ] = array(
				'label' => $method_name,
				'html'  => $html,
			);
		}
	}

	if ( $gateway->has_error() ) {
		$pronamic_ideal_errors[] = $gateway->get_error();
	}

	include Plugin::$dirname . '/views/errors.php';

	?>
	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="pronamic-pay-test-payment-methods">
					<?php esc_html_e( 'Payment Method', 'pronamic_ideal' ); ?>
				</label>
			</th>
			<td>
				<select id="pronamic-pay-test-payment-methods" name="pronamic_pay_test_payment_method">
					<?php

					foreach ( $payment_methods['choices'][0]['options'] as $payment_method => $method_name ) {
						printf(
							'<option value="%s">%s</option>',
							esc_attr( $payment_method ),
							esc_html( $method_name )
						);
					}

					?>
				</select>
			</td>
		</tr>

		<?php foreach ( $inputs as $method => $input ) : ?>

			<tr class="pronamic-pay-cloack pronamic-pay-test-payment-method <?php echo esc_attr( $method ); ?>">
				<th scope="row">
					<?php echo esc_html( $input['label'] ); ?>
				</th>
				<td>
					<?php

					echo $input['html']; // WPCS: XSS ok.

					?>
				</td>
			</tr>

		<?php endforeach; ?>

		<tr>
			<th scope="row">
				<?php esc_html_e( 'Amount', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<label for="test_amount">€</label>

				<input name="test_amount" id="test_amount" class="regular-text code pronamic-pay-form-control" value="" type="text" size="6" />		
			</td>
		</tr>

		<?php if ( $gateway->supports( 'recurring' ) ) : ?>

			<?php

			$options = array(
				''         => __( '— Select Repeat —', 'pronamic_ideal' ),
				'daily'    => __( 'Daily', 'pronamic_ideal' ),
				'weekly'   => __( 'Weekly', 'pronamic_ideal' ),
				'monthly'  => __( 'Monthly', 'pronamic_ideal' ),
				'annually' => __( 'Annually', 'pronamic_ideal' ),
			);

			$options_interval_suffix = array(
				'daily'    => __( 'days', 'pronamic_ideal' ),
				'weekly'   => __( 'weeks', 'pronamic_ideal' ),
				'monthly'  => __( 'months', 'pronamic_ideal' ),
				'annually' => __( 'year', 'pronamic_ideal' ),
			);

			?>
			<tr>
				<th scope="row">
					<label for="pronamic-pay-test-subscription">
						<?php esc_html_e( 'Subscription', 'pronamic_ideal' ); ?>
					</label>
				</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span><?php esc_html_e( 'Test Subscription', 'pronamic_ideal' ); ?></span></legend>

						<label for="pronamic-pay-test-subscription">
							<input name="pronamic_pay_test_subscription" id="pronamic-pay-test-subscription" value="1" type="checkbox" />		
							<?php esc_html_e( 'Start a subscription for this payment.', 'pronamic_ideal' ); ?>
						</label>
					</fieldset>

					<script type="text/javascript">
						jQuery( document ).ready( function( $ ) {
							$( '#pronamic-pay-test-subscription' ).change( function() {
								$( '.pronamic-pay-test-subscription' ).toggle( $( this ).prop( 'checked' ) );
							} );
						} );
					</script>
				</td>
			</tr>
			<tr class="pronamic-pay-cloack pronamic-pay-test-subscription">
				<th scope="row">
					<label for="pronamic_pay_test_repeat_frequency"><?php esc_html_e( 'Frequency', 'pronamic_ideal' ); ?></label>
				</th>
				<td>
					<select id="pronamic_pay_test_repeat_frequency" name="pronamic_pay_test_repeat_frequency">
						<?php

						foreach ( $options as $key => $label ) {
							$interval_suffix = '';

							if ( isset( $options_interval_suffix[ $key ] ) ) {
								$interval_suffix = $options_interval_suffix[ $key ];
							}

							printf(
								'<option value="%s" data-interval-suffix="%s">%s</option>',
								esc_attr( $key ),
								esc_attr( $interval_suffix ),
								esc_html( $label )
							);
						}

						?>
					</select>
				</td>
			</tr>
			<tr class="pronamic-pay-cloack pronamic-pay-test-subscription">
				<th scope="row">
					<label for="pronamic_pay_test_repeat_interval"><?php esc_html_e( 'Repeat every', 'pronamic_ideal' ); ?></label>
				</th>
				<td>
					<select id="pronamic_pay_test_repeat_interval" name="pronamic_pay_test_repeat_interval">
						<?php

						foreach ( range( 1, 30 ) as $value ) {
							printf(
								'<option value="%s">%s</option>',
								esc_attr( $value ),
								esc_html( $value )
							);
						}

						?>
					</select>

					<span id="pronamic_pay_test_repeat_interval_suffix"><?php esc_html_e( 'days/weeks/months/year', 'pronamic_ideal' ); ?></span>
				</td>
			</tr>
			<tr class="pronamic-pay-cloack pronamic-pay-test-subscription">
				<th scope="row">
					<?php esc_html_e( 'Ends On', 'pronamic_ideal' ); ?>
				</th>
				<td>
					<div>
						<input type="radio" name="pronamic_pay_ends_on" value="never" <?php checked( 'never', '' ); ?> />

						<?php esc_html_e( 'Never', 'pronamic_ideal' ); ?>

						<input type="number" name="pronamic_pay_ends_on_never" value="1" style="visibility: hidden;" />
					</div>
					<div>
						<input type="radio" name="pronamic_pay_ends_on" value="count" <?php checked( 'count', '' ); ?> />

						<?php

						$allowed_html = array(
							'input' => array(
								'id'     => true,
								'name'   => true,
								'type'   => true,
								'value'  => true,
								'size'   => true,
								'class'  => true,
							),
						);

						echo wp_kses(
							sprintf(
								__( 'After %s times', 'pronamic_ideal' ),
								sprintf( '<input type="number" name="pronamic_pay_ends_on_count" value="%s" min="1" />', esc_attr( '' ) )
							),
							$allowed_html
						);

						?>
					</div>

					<div>
						<input type="radio" name="pronamic_pay_ends_on" value="date" />

						<?php
						echo wp_kses(
							sprintf(
								__( 'On %s', 'pronamic_ideal' ),
								sprintf( '<input type="date" id="pronamic_pay_ends_on_date" name="pronamic_pay_ends_on_date" value="%s" />', esc_attr( '' ) )
							),
							$allowed_html
						);
						?>
					</div>
				</td>
			</tr>

		<?php endif; ?>

		<tr>
			<td>

			</td>
			<td>
				<?php submit_button( __( 'Test', 'pronamic_ideal' ), 'secondary', 'test_pay_gateway', false ); ?>
			</td>
		</tr>

	</table>

	<script type="text/javascript">
		jQuery( document ).ready( function( $ ) {
			// Interval label
			function set_interval_label() {
				var text = $( '#pronamic_pay_test_repeat_frequency :selected' ).data( 'interval-suffix' );

				$( '#pronamic_pay_test_repeat_interval_suffix' ).text( text );
			}

			$( '#pronamic_pay_test_repeat_frequency' ).change( function() { set_interval_label(); } );

			set_interval_label();
		} );
	</script>

	<?php

	if ( $is_ideal || $gateway instanceof \Pronamic\WordPress\Pay\Gateways\OmniKassa2\Gateway ) {
		include Plugin::$dirname . '/views/ideal-test-cases.php';
	}
} else {
	printf(
		'<em>%s</em>',
		esc_html( __( 'Please save the entered account details of your payment provider, to make a test payment.', 'pronamic_ideal' ) )
	);
}
